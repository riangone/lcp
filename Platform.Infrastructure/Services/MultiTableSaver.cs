using Dapper;
using System.Data;
using System.Linq;
using Platform.Infrastructure.Data;
using Platform.Infrastructure.Definitions;

namespace Platform.Infrastructure.Services;

/// <summary>
/// 保存结果
/// </summary>
public class SaveResult
{
    public bool Success { get; set; }
    public string? ErrorMessage { get; set; }
    public Dictionary<string, object> GeneratedIds { get; set; } = new();
    public List<string> SavedTables { get; set; } = new();
    public List<string> SkippedTables { get; set; } = new();
}

/// <summary>
/// 表保存结果
/// </summary>
public class TableSaveResult
{
    public object? GeneratedId { get; set; }
    public int AffectedRows { get; set; }
    public int SyncedCount { get; set; }
    public bool Skipped { get; set; }
}

/// <summary>
/// 多表保存服务
/// </summary>
public class MultiTableSaver
{
    private readonly IDbConnection _db;

    public MultiTableSaver(DbConnectionFactory factory)
    {
        _db = factory.Create();
    }

    /// <summary>
    /// 保存多表数据
    /// </summary>
    public async Task<SaveResult> SaveAsync(
        SaveConfig config,
        IDictionary<string, object> formData,
        IDictionary<string, object>? loadedData = null)
    {
        var result = new SaveResult();
        var generatedIds = new Dictionary<string, object>();

        IDbTransaction? transaction = null;
        if (config.Transaction.Enabled)
        {
            if (_db.State != ConnectionState.Open)
            {
                _db.Open();
            }
            transaction = _db.BeginTransaction(ParseIsolationLevel(config.Transaction.IsolationLevel));
        }

        try
        {
            // 按配置顺序保存
            if (config.SaveOrder != null)
            {
                foreach (var saveItem in config.SaveOrder.OrderBy(x => x.Order))
                {
                    // 检查条件
                    if (!string.IsNullOrEmpty(saveItem.Condition))
                    {
                        if (!EvaluateCondition(saveItem.Condition, formData, generatedIds))
                        {
                            result.SkippedTables.Add(saveItem.Table);
                            continue;
                        }
                    }

                    // 确定 CRUD 类型
                    var crudType = saveItem.CrudType ?? DetermineCrudType(saveItem.Table, formData, loadedData);

                    // 执行保存
                    var tableResult = await ExecuteCrudAsync(
                        saveItem.Table,
                        crudType,
                        formData,
                        saveItem,
                        generatedIds,
                        transaction);

                    // 保存生成的 ID
                    if (tableResult.GeneratedId != null)
                    {
                        generatedIds[$"{saveItem.Table}.Id"] = tableResult.GeneratedId;
                        result.GeneratedIds[saveItem.Table] = tableResult.GeneratedId;
                    }

                    result.SavedTables.Add(saveItem.Table);
                }
            }

            if (transaction != null)
            {
                transaction.Commit();
            }

            result.Success = true;
        }
        catch (Exception ex)
        {
            if (transaction != null)
            {
                transaction.Rollback();
            }
            result.Success = false;
            result.ErrorMessage = ex.Message;
        }
        finally
        {
            if (transaction != null)
            {
                transaction.Dispose();
            }
            if (_db.State == ConnectionState.Open)
            {
                _db.Close();
            }
        }

        return result;
    }

    /// <summary>
    /// 执行 CRUD 操作
    /// </summary>
    private async Task<TableSaveResult> ExecuteCrudAsync(
        string table,
        CrudType crudType,
        IDictionary<string, object> formData,
        SaveItemConfig config,
        Dictionary<string, object> generatedIds,
        IDbTransaction? transaction)
    {
        return crudType switch
        {
            CrudType.Insert => await InsertAsync(table, formData, config, generatedIds, transaction),
            CrudType.Update => await UpdateAsync(table, formData, config, generatedIds, transaction),
            CrudType.Upsert => await UpsertAsync(table, formData, config, generatedIds, transaction),
            CrudType.Sync => await SyncAsync(table, formData, config, generatedIds, transaction),
            CrudType.Delete => await DeleteAsync(table, formData, config, generatedIds, transaction),
            CrudType.Skip => new TableSaveResult { Skipped = true },
            _ => throw new ArgumentException($"Unknown CRUD type: {crudType}")
        };
    }

    /// <summary>
    /// 插入操作
    /// </summary>
    private async Task<TableSaveResult> InsertAsync(
        string table,
        IDictionary<string, object> formData,
        SaveItemConfig config,
        Dictionary<string, object> generatedIds,
        IDbTransaction? transaction)
    {
        // 应用字段映射
        var mappedData = ApplyFieldMappings(formData, config.FieldMappings, generatedIds);

        // 过滤掉 null 值
        var validData = mappedData.Where(kvp => kvp.Value != null).ToDictionary(k => k.Key, v => v.Value);

        if (!validData.Any())
        {
            return new TableSaveResult { AffectedRows = 0 };
        }

        var cols = validData.Keys.ToList();
        var values = string.Join(", ", cols.Select(k => "@" + k));
        var sql = $"INSERT INTO {Escape(table)} ({string.Join(", ", cols.Select(Escape))}) VALUES ({values})";

        var affectedRows = await _db.ExecuteAsync(sql, validData, transaction);

        // 获取生成的 ID
        var generatedId = await GetGeneratedIdAsync(table, transaction);

        return new TableSaveResult
        {
            GeneratedId = generatedId,
            AffectedRows = affectedRows
        };
    }

    /// <summary>
    /// 更新操作
    /// </summary>
    private async Task<TableSaveResult> UpdateAsync(
        string table,
        IDictionary<string, object> formData,
        SaveItemConfig config,
        Dictionary<string, object> generatedIds,
        IDbTransaction? transaction)
    {
        // 应用字段映射
        var mappedData = ApplyFieldMappings(formData, config.FieldMappings, generatedIds);

        if (config.MatchFields == null || !config.MatchFields.Any())
        {
            throw new ArgumentException("MatchFields are required for update operation");
        }

        // 构建 WHERE 子句
        var whereConditions = config.MatchFields.Select(f => $"{Escape(f)} = @{f}").ToList();
        var whereSql = string.Join(" AND ", whereConditions);

        // 构建 SET 子句
        var updateFields = mappedData.Keys.Where(k => !config.MatchFields.Contains(k)).ToList();
        if (!updateFields.Any())
        {
            return new TableSaveResult { AffectedRows = 0 };
        }

        var sets = string.Join(", ", updateFields.Select(k => $"{Escape(k)} = @{k}"));
        var sql = $"UPDATE {Escape(table)} SET {sets} WHERE {whereSql}";

        var affectedRows = await _db.ExecuteAsync(sql, mappedData, transaction);

        return new TableSaveResult { AffectedRows = affectedRows };
    }

    /// <summary>
    /// Upsert 操作
    /// </summary>
    private async Task<TableSaveResult> UpsertAsync(
        string table,
        IDictionary<string, object> formData,
        SaveItemConfig config,
        Dictionary<string, object> generatedIds,
        IDbTransaction? transaction)
    {
        // 应用字段映射
        var mappedData = ApplyFieldMappings(formData, config.FieldMappings, generatedIds);

        if (config.MatchFields == null || !config.MatchFields.Any())
        {
            // 没有匹配字段，直接插入
            return await InsertAsync(table, formData, config, generatedIds, transaction);
        }

        // 检查是否存在
        var matchValues = config.MatchFields
            .Select(f => mappedData.ContainsKey(f) ? mappedData[f] : null)
            .ToArray();

        if (matchValues.Any(v => v == null))
        {
            // 匹配字段值不全，直接插入
            return await InsertAsync(table, formData, config, generatedIds, transaction);
        }

        var exists = await CheckExistsAsync(table, config.MatchFields, matchValues, transaction);

        if (exists)
        {
            return await UpdateAsync(table, mappedData, config, generatedIds, transaction);
        }
        else
        {
            return await InsertAsync(table, mappedData, config, generatedIds, transaction);
        }
    }

    /// <summary>
    /// 同步操作（删除不存在的，更新存在的，插入新的）
    /// </summary>
    private async Task<TableSaveResult> SyncAsync(
        string table,
        IDictionary<string, object> formData,
        SaveItemConfig config,
        Dictionary<string, object> generatedIds,
        IDbTransaction? transaction)
    {
        // 获取现有数据
        var existingData = await GetExistingDataAsync(table, config, generatedIds, transaction);
        var existingIds = new HashSet<object>(existingData);

        // 获取表单数据列表
        var newData = GetFormDataList(formData, table);
        var newIds = new HashSet<object>();

        foreach (var row in newData)
        {
            object? id = null;
            if (config.MatchFields != null && config.MatchFields.Any())
            {
                var matchField = config.MatchFields.First();
                if (row.TryGetValue(matchField, out var matchValue))
                {
                    id = matchValue;
                }
            }

            if (id != null)
            {
                newIds.Add(id);
            }

            if (id != null && existingIds.Contains(id))
            {
                // 更新
                await UpdateRowAsync(table, row, config.MatchFields, transaction);
            }
            else
            {
                // 插入
                await InsertRowAsync(table, row, transaction);
            }
        }

        // 删除不存在的
        if (config.CascadeDelete?.Enabled == true)
        {
            var toDelete = existingIds.Except(newIds).ToList();
            foreach (var id in toDelete)
            {
                await DeleteRowAsync(table, config.CascadeDelete.MatchField, id, transaction);
            }
        }

        return new TableSaveResult { SyncedCount = newData.Count };
    }

    /// <summary>
    /// 删除操作
    /// </summary>
    private async Task<TableSaveResult> DeleteAsync(
        string table,
        IDictionary<string, object> formData,
        SaveItemConfig config,
        Dictionary<string, object> generatedIds,
        IDbTransaction? transaction)
    {
        if (config.MatchFields == null || !config.MatchFields.Any())
        {
            throw new ArgumentException("MatchFields are required for delete operation");
        }

        var matchValues = config.MatchFields
            .Select(f => formData.ContainsKey(f) ? formData[f] : null)
            .ToArray();

        if (matchValues.Any(v => v == null))
        {
            return new TableSaveResult { AffectedRows = 0 };
        }

        var whereConditions = config.MatchFields.Select((f, i) => $"{Escape(f)} = @p{i}").ToList();
        var whereSql = string.Join(" AND ", whereConditions);

        var parameters = new Dictionary<string, object>();
        for (int i = 0; i < matchValues.Length; i++)
        {
            parameters[$"p{i}"] = matchValues[i] ?? (object)DBNull.Value;
        }
        var sql = $"DELETE FROM {Escape(table)} WHERE {whereSql}";

        var affectedRows = await _db.ExecuteAsync(sql, parameters, transaction);

        return new TableSaveResult { AffectedRows = affectedRows };
    }

    /// <summary>
    /// 应用字段映射
    /// </summary>
    private Dictionary<string, object> ApplyFieldMappings(
        IDictionary<string, object> formData,
        Dictionary<string, FieldMappingConfig>? mappings,
        Dictionary<string, object> generatedIds)
    {
        var result = new Dictionary<string, object>(formData);

        if (mappings == null) return result;

        foreach (var mapping in mappings)
        {
            var value = mapping.Value.Source switch
            {
                "generated_id" => GetGeneratedIdValue(mapping.Value.FromTable, mapping.Value.Field, generatedIds),
                "form" => (formData.ContainsKey(mapping.Value.Field) ? formData[mapping.Value.Field] : null),
                "constant" => mapping.Value.Constant,
                _ => null
            };

            if (value != null)
            {
                result[mapping.Key] = value;
            }
        }

        return result;
    }

    /// <summary>
    /// 获取生成的 ID 值
    /// </summary>
    private object? GetGeneratedIdValue(string? fromTable, string? field, Dictionary<string, object> generatedIds)
    {
        if (string.IsNullOrEmpty(fromTable) || string.IsNullOrEmpty(field))
        {
            return null;
        }

        var key = $"{fromTable}.{field}";
        return generatedIds.TryGetValue(key, out var value) ? value : null;
    }

    /// <summary>
    /// 检查记录是否存在
    /// </summary>
    private async Task<bool> CheckExistsAsync(
        string table,
        List<string> matchFields,
        object[] matchValues,
        IDbTransaction? transaction)
    {
        var whereConditions = matchFields.Select(f => $"{Escape(f)} = @{f}").ToList();
        var whereSql = string.Join(" AND ", whereConditions);

        var parameters = matchFields.Zip(matchValues, (f, v) => new { Field = f, Value = v })
            .ToDictionary(x => x.Field, x => (object?)x.Value);

        var sql = $"SELECT 1 FROM {Escape(table)} WHERE {whereSql} LIMIT 1";

        var result = await _db.ExecuteScalarAsync<int?>(sql, parameters, transaction);
        return result.HasValue;
    }

    /// <summary>
    /// 获取现有数据
    /// </summary>
    private async Task<List<object>> GetExistingDataAsync(
        string table,
        SaveItemConfig config,
        Dictionary<string, object> generatedIds,
        IDbTransaction? transaction)
    {
        var results = new List<object>();

        if (config.CascadeDelete?.Enabled != true || string.IsNullOrEmpty(config.CascadeDelete.MatchField))
        {
            return results;
        }

        // 获取匹配字段的值
        var matchFieldValue = GetGeneratedIdValue(
            config.CascadeDelete.Source?.Split('.').FirstOrDefault(),
            config.CascadeDelete.Source?.Split('.').LastOrDefault(),
            generatedIds);

        if (matchFieldValue == null)
        {
            return results;
        }

        var sql = $"SELECT {Escape(config.CascadeDelete.MatchField)} FROM {Escape(table)} WHERE {Escape(config.CascadeDelete.MatchField)} IS NOT NULL";
        var rows = await _db.QueryAsync(sql, transaction: transaction);

        foreach (var row in rows)
        {
            var id = ((IDictionary<string, object>)row)[config.CascadeDelete.MatchField];
            results.Add(id);
        }

        return results;
    }

    /// <summary>
    /// 获取表单数据列表
    /// </summary>
    private List<Dictionary<string, object>> GetFormDataList(
        IDictionary<string, object> formData,
        string table)
    {
        var results = new List<Dictionary<string, object>>();

        // 查找形如 "TableName[0].FieldName" 的键
        var tableKeys = formData.Keys.Where(k => k.StartsWith(table + "[", StringComparison.OrdinalIgnoreCase)).ToList();

        if (!tableKeys.Any())
        {
            return results;
        }

        // 提取行索引
        var indices = new HashSet<int>();
        foreach (var key in tableKeys)
        {
            var match = System.Text.RegularExpressions.Regex.Match(key, System.Text.RegularExpressions.Regex.Escape(table) + @"\[(\d+)\]", System.Text.RegularExpressions.RegexOptions.IgnoreCase);
            if (match.Success)
            {
                indices.Add(int.Parse(match.Groups[1].Value));
            }
        }

        foreach (var index in indices.OrderBy(i => i))
        {
            var row = new Dictionary<string, object>();
            foreach (var key in tableKeys)
            {
                var match = System.Text.RegularExpressions.Regex.Match(key, System.Text.RegularExpressions.Regex.Escape(table) + $@"\[{index}\]\.(.+)", System.Text.RegularExpressions.RegexOptions.IgnoreCase);
                if (match.Success)
                {
                    var field = match.Groups[1].Value;
                    var value = formData[key]?.ToString() ?? "";

                    if (decimal.TryParse(value, System.Globalization.NumberStyles.Any, System.Globalization.CultureInfo.InvariantCulture, out var decimalVal))
                    {
                        row[field] = decimalVal;
                    }
                    else if (int.TryParse(value, out var intVal))
                    {
                        row[field] = intVal;
                    }
                    else
                    {
                        row[field] = value;
                    }
                }
            }
            if (row.Any())
            {
                results.Add(row);
            }
        }

        return results;
    }

    /// <summary>
    /// 更新行
    /// </summary>
    private async Task UpdateRowAsync(
        string table,
        Dictionary<string, object> row,
        List<string>? matchFields,
        IDbTransaction? transaction)
    {
        if (matchFields == null || !matchFields.Any() || !row.Any())
        {
            return;
        }

        var updateFields = row.Keys.Where(k => !matchFields.Contains(k)).ToList();
        if (!updateFields.Any())
        {
            return;
        }

        var sets = string.Join(", ", updateFields.Select(k => $"{Escape(k)} = @{k}"));
        var where = string.Join(" AND ", matchFields.Select(f => $"{Escape(f)} = @{f}"));
        var sql = $"UPDATE {Escape(table)} SET {sets} WHERE {where}";

        await _db.ExecuteAsync(sql, row, transaction);
    }

    /// <summary>
    /// 插入行
    /// </summary>
    private async Task InsertRowAsync(
        string table,
        Dictionary<string, object> row,
        IDbTransaction? transaction)
    {
        if (!row.Any())
        {
            return;
        }

        var cols = row.Keys.ToList();
        var values = string.Join(", ", cols.Select(k => "@" + k));
        var sql = $"INSERT INTO {Escape(table)} ({string.Join(", ", cols.Select(Escape))}) VALUES ({values})";

        await _db.ExecuteAsync(sql, row, transaction);
    }

    /// <summary>
    /// 删除行
    /// </summary>
    private async Task DeleteRowAsync(
        string table,
        string? matchField,
        object id,
        IDbTransaction? transaction)
    {
        if (string.IsNullOrEmpty(matchField))
        {
            return;
        }

        var sql = $"DELETE FROM {Escape(table)} WHERE {Escape(matchField)} = @id";
        await _db.ExecuteAsync(sql, new { id }, transaction);
    }

    /// <summary>
    /// 获取生成的 ID
    /// </summary>
    private async Task<object?> GetGeneratedIdAsync(string table, IDbTransaction? transaction)
    {
        var sql = $"SELECT last_insert_rowid()";
        var id = await _db.ExecuteScalarAsync<long>(sql, transaction: transaction);
        return id > 0 ? id : (object?)null;
    }

    /// <summary>
    /// 判断 CRUD 类型
    /// </summary>
    private CrudType DetermineCrudType(string table, IDictionary<string, object> formData, IDictionary<string, object>? loadedData)
    {
        // 默认使用 Upsert
        return CrudType.Upsert;
    }

    /// <summary>
    /// 评估条件表达式
    /// </summary>
    private bool EvaluateCondition(string condition, IDictionary<string, object> formData, Dictionary<string, object> generatedIds)
    {
        // 简化实现：替换变量后评估
        var replaced = condition;

        // 替换表单字段
        foreach (var kvp in formData)
        {
            replaced = replaced.Replace($"data.{kvp.Key}", kvp.Value?.ToString() ?? "null");
        }

        // 替换生成的 ID
        foreach (var kvp in generatedIds)
        {
            replaced = replaced.Replace($"generated.{kvp.Key}", kvp.Value?.ToString() ?? "null");
        }

        // 简单评估（实际应该使用表达式求值引擎）
        try
        {
            // 处理 null
            replaced = replaced.Replace("null", "");

            // 简单比较
            if (replaced.Contains("!="))
            {
                var parts = replaced.Split("!=");
                if (parts.Length == 2)
                {
                    return parts[0].Trim() != parts[1].Trim();
                }
            }

            if (replaced.Contains("=="))
            {
                var parts = replaced.Split("==");
                if (parts.Length == 2)
                {
                    return parts[0].Trim() == parts[1].Trim();
                }
            }

            return true; // 默认返回 true
        }
        catch
        {
            return true; // 评估失败默认执行
        }
    }

    /// <summary>
    /// 解析隔离级别
    /// </summary>
    private IsolationLevel ParseIsolationLevel(string level)
    {
        return level.ToLower() switch
        {
            "readuncommitted" => IsolationLevel.ReadUncommitted,
            "readcommitted" => IsolationLevel.ReadCommitted,
            "repeatableread" => IsolationLevel.RepeatableRead,
            "serializable" => IsolationLevel.Serializable,
            _ => IsolationLevel.ReadCommitted
        };
    }

    /// <summary>
    /// 转义表名
    /// </summary>
    private string Escape(string identifier)
    {
        return identifier.Replace("\"", "\"\"");
    }
}
