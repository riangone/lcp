using Dapper;
using System.Data;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Data;

namespace Platform.Infrastructure.Repositories;

public class DynamicRepository
{
    private readonly IDbConnection _db;

    public DynamicRepository(DbConnectionFactory factory)
    {
        _db = factory.Create();
    }

    private static string Escape(string identifier) => SqlIdentifier.Escape(identifier);

    private static string GetColumns(ModelDefinition def)
        => string.Join(", ", def.Columns.Select(Escape));

    private static string GetReadableSourceSql(ModelDefinition def)
    {
        if (!string.IsNullOrWhiteSpace(def.Query))
            return $"({def.Query}) AS src";

        return Escape(def.Table);
    }

    private static void EnsureWritable(ModelDefinition def)
    {
        if (def.IsReadOnly)
            throw new Exception("This model is read-only and does not support create/update/delete.");
    }

    public async Task<IEnumerable<Dictionary<string, object>>> GetAllAsync(ModelDefinition def)
    {
        var cols = GetColumns(def);
        var sql = $"SELECT {cols} FROM {GetReadableSourceSql(def)}";
        var rows = await _db.QueryAsync(sql);

        return rows.Select(ToDict);
    }

    public async Task<Dictionary<string, object>?> GetByIdAsync(ModelDefinition def, object id)
    {
        var cols = GetColumns(def);
        var sql = $"SELECT {cols} FROM {GetReadableSourceSql(def)} WHERE {Escape(def.PrimaryKey)}=@id";
        var row = await _db.QuerySingleOrDefaultAsync(sql, new { id });

        return row == null ? null : ToDict(row);
    }

    public async Task InsertAsync(ModelDefinition def, IDictionary<string, object> data)
    {
        EnsureWritable(def);

        var cols = def.Columns.Intersect(data.Keys).ToList();
        if (!cols.Any())
            return;

        var vals = string.Join(", ", cols.Select(k => "@" + k));
        var sql = $"INSERT INTO {Escape(def.Table)} ({string.Join(", ", cols.Select(Escape))}) VALUES ({vals})";
        await _db.ExecuteAsync(sql, data);
    }

    public async Task UpdateAsync(ModelDefinition def, object id, IDictionary<string, object> data)
    {
        EnsureWritable(def);

        var update = def.Columns
            .Where(k => k != def.PrimaryKey)
            .Intersect(data.Keys)
            .ToDictionary(k => k, k => data[k]);

        if (!update.Any())
            return;

        var sets = string.Join(", ", update.Keys.Select(k => $"{Escape(k)}=@{k}"));
        var sql = $"UPDATE {Escape(def.Table)} SET {sets} WHERE {Escape(def.PrimaryKey)}=@_id";

        update["_id"] = id;
        await _db.ExecuteAsync(sql, update);
    }

    public async Task DeleteAsync(ModelDefinition def, object id)
    {
        EnsureWritable(def);

        var sql = $"DELETE FROM {Escape(def.Table)} WHERE {Escape(def.PrimaryKey)}=@id";
        await _db.ExecuteAsync(sql, new { id });
    }

    private static Dictionary<string, object> ToDict(dynamic row)
        => ((IDictionary<string, object>)row)
            .ToDictionary(k => k.Key, v => v.Value);

    public async Task<(IEnumerable<Dictionary<string, object>> Rows, int Total)>
        GetPagedAsync(
            ModelDefinition def,
            int page,
            int size,
            IDictionary<string, string> filters,
            string? sortBy = null,
            string? sortDir = "asc")
    {
        var offset = (page - 1) * size;
        var cols = GetColumns(def);

        var where = new List<string>();
        var param = new DynamicParameters();

        foreach (var f in def.List?.Filters ?? [])
        {
            if (!filters.TryGetValue(f.Key, out var val) || string.IsNullOrWhiteSpace(val))
                continue;

            var col = Escape(f.Key);

            if (f.Value.Type == "like")
            {
                where.Add($"{col} LIKE @{f.Key}");
                param.Add(f.Key, $"%{val}%");
            }
            else
            {
                where.Add($"{col} = @{f.Key}");
                param.Add(f.Key, val);
            }
        }

        var whereSql = where.Any()
            ? "WHERE " + string.Join(" AND ", where)
            : "";

        var allowedSortColumns = (def.List?.Columns?.Any() == true ? def.List.Columns : def.Columns)
            .ToHashSet(StringComparer.OrdinalIgnoreCase);
        var effectiveSortBy = !string.IsNullOrWhiteSpace(sortBy) && allowedSortColumns.Contains(sortBy)
            ? sortBy
            : def.PrimaryKey;
        var effectiveSortDir = string.Equals(sortDir, "desc", StringComparison.OrdinalIgnoreCase)
            ? "DESC"
            : "ASC";

        var rowsSql = $@"
            SELECT {cols} FROM {GetReadableSourceSql(def)}
            {whereSql}
            ORDER BY {Escape(effectiveSortBy)} {effectiveSortDir}
            LIMIT @Size OFFSET @Offset";

        var countSql = $@"
            SELECT COUNT(*) FROM {GetReadableSourceSql(def)} {whereSql}";

        param.Add("Offset", offset);
        param.Add("Size", size);

        var rows = await _db.QueryAsync(rowsSql, param);
        var total = await _db.ExecuteScalarAsync<int>(countSql, param);

        return (rows.Select(ToDict), total);
    }

    #region Page Methods - 多表支持

    /// <summary>
    /// 获取页面所有区域的数据
    /// </summary>
    public async Task<Dictionary<string, (IEnumerable<Dictionary<string, object>> Rows, int Total)>>
        GetPageDataAsync(
            PageDefinition page,
            IDictionary<string, string> filters,
            string? mainTableId = null)
    {
        var result = new Dictionary<string, (IEnumerable<Dictionary<string, object>>, int)>();

        foreach (var section in page.Sections)
        {
            try
            {
                var sectionFilters = filters
                    .Where(kvp => kvp.Key.StartsWith($"{section.Id}_"))
                    .ToDictionary(
                        kvp => kvp.Key.Substring(section.Id.Length + 1),
                        kvp => kvp.Value);

                // 如果有主表 ID，添加关联过滤
                if (!string.IsNullOrEmpty(mainTableId) && 
                    !string.IsNullOrEmpty(section.ForeignKey) &&
                    !string.IsNullOrEmpty(section.LocalForeignKey))
                {
                    sectionFilters[section.ForeignKey] = mainTableId;
                }

                var (rows, total) = await GetSectionDataAsync(section, 1, section.PageSize, sectionFilters);
                result[section.Id] = (rows, total);
            }
            catch (Exception)
            {
                // 记录错误但继续处理其他区域
                result[section.Id] = (Enumerable.Empty<Dictionary<string, object>>(), 0);
            }
        }

        return result;
    }

    /// <summary>
    /// 获取单个区域的数据
    /// </summary>
    public async Task<(IEnumerable<Dictionary<string, object>> Rows, int Total)>
        GetSectionDataAsync(
            SectionDefinition section,
            int page,
            int size,
            IDictionary<string, string> filters)
    {
        var offset = (page - 1) * size;
        var param = new DynamicParameters();

        // 构建 SQL
        string sql;
        string countSql;

        if (section.SourceType == "query" || section.SourceType == "custom")
        {
            // 自定义查询或命名查询
            var sourceSql = section.SourceType == "query" 
                ? GetNamedQuery(section.Source!) 
                : section.Source;

            var where = BuildWhereClause(section, filters, param);
            var whereSql = where.Any() ? "WHERE " + string.Join(" AND ", where) : "";

            sql = $@"
                SELECT * FROM ({sourceSql}) AS src
                {whereSql}
                ORDER BY {Escape(section.Columns.FirstOrDefault() ?? "Id")} ASC
                LIMIT @Size OFFSET @Offset";

            countSql = $@"
                SELECT COUNT(*) FROM ({sourceSql}) AS src
                {whereSql}";
        }
        else
        {
            // 表查询
            if (string.IsNullOrEmpty(section.Source))
                throw new Exception($"Section '{section.Id}' has no data source defined.");

            var cols = section.Columns.Any() 
                ? string.Join(", ", section.Columns.Select(Escape))
                : "*";

            var where = BuildWhereClause(section, filters, param);
            var whereSql = where.Any() ? "WHERE " + string.Join(" AND ", where) : "";

            sql = $@"
                SELECT {cols} FROM {Escape(section.Source)}
                {whereSql}
                ORDER BY {Escape(section.Columns.FirstOrDefault() ?? "Id")} ASC
                LIMIT @Size OFFSET @Offset";

            countSql = $@"
                SELECT COUNT(*) FROM {Escape(section.Source)}
                {whereSql}";
        }

        param.Add("Offset", offset);
        param.Add("Size", size);

        var rows = await _db.QueryAsync(sql, param);
        var total = await _db.ExecuteScalarAsync<int>(countSql, param);

        return (rows.Select(ToDict), total);
    }

    /// <summary>
    /// 构建 WHERE 子句
    /// </summary>
    private List<string> BuildWhereClause(
        SectionDefinition section,
        IDictionary<string, string> filters,
        DynamicParameters param)
    {
        var where = new List<string>();

        var sectionFilters = section.Filters ?? new Dictionary<string, FilterDefinition>();

        foreach (var f in sectionFilters)
        {
            if (!filters.TryGetValue(f.Key, out var val) || string.IsNullOrWhiteSpace(val))
                continue;

            var col = Escape(f.Key);

            if (f.Value.Type == "like")
            {
                where.Add($"{col} LIKE @{f.Key}");
                param.Add(f.Key, $"%{val}%");
            }
            else if (f.Value.Type == "in")
            {
                var values = val.Split(',').Select(v => v.Trim()).ToArray();
                var inParams = string.Join(", ", values.Select((v, i) => $"@{f.Key}_{i}"));
                where.Add($"{col} IN ({inParams})");
                for (int i = 0; i < values.Length; i++)
                {
                    param.Add($"{f.Key}_{i}", values[i]);
                }
            }
            else
            {
                where.Add($"{col} = @{f.Key}");
                param.Add(f.Key, val);
            }
        }

        // 添加外键过滤
        if (!string.IsNullOrEmpty(section.ForeignKey) && 
            !string.IsNullOrEmpty(section.LocalForeignKey) &&
            filters.TryGetValue(section.ForeignKey, out var fkValue))
        {
            where.Add($"{Escape(section.LocalForeignKey)} = @{section.ForeignKey}");
            param.Add(section.ForeignKey, fkValue);
        }

        return where;
    }

    /// <summary>
    /// 获取命名查询
    /// </summary>
    private string GetNamedQuery(string queryName)
    {
        // 这里可以从配置中获取命名查询
        // 目前返回空字符串，实际使用时需要从 YAML 或其他配置源加载
        throw new Exception($"Named query '{queryName}' not found.");
    }

    /// <summary>
    /// 执行页面操作（批量 CRUD）
    /// </summary>
    public async Task ExecutePageActionAsync(
        PageDefinition page,
        string actionId,
        IDictionary<string, object> data,
        string? mainTableId = null)
    {
        var action = page.Actions.FirstOrDefault(a => a.Id == actionId);
        if (action == null)
            throw new Exception($"Action '{actionId}' not found.");

        using var transaction = _db.BeginTransaction();

        try
        {
            foreach (var sectionId in action.AffectsSections)
            {
                var section = page.Sections.FirstOrDefault(s => s.Id == sectionId);
                if (section == null || section.ReadOnly)
                    continue;

                if (string.IsNullOrEmpty(section.Source))
                    continue;

                // 根据操作类型执行不同的逻辑
                if (action.Method == "DELETE")
                {
                    var ids = data.ContainsKey("ids") 
                        ? ((string)data["ids"]).Split(',') 
                        : new[] { data.ContainsKey("id") ? data["id"]!.ToString()! : "" };

                    foreach (var id in ids.Where(i => !string.IsNullOrEmpty(i)))
                    {
                        var deleteSql = $"DELETE FROM {Escape(section.Source)} WHERE {Escape(section.Columns.First())}=@id";
                        await _db.ExecuteAsync(deleteSql, new { id }, transaction);
                    }
                }
                else if (action.Method == "POST" || action.Method == "PUT")
                {
                    // 插入或更新操作
                    var cols = section.Columns.Intersect(data.Keys).ToList();
                    if (!cols.Any())
                        continue;

                    var sets = string.Join(", ", cols.Select(k => $"{Escape(k)}=@{k}"));
                    var sql = $"INSERT INTO {Escape(section.Source)} ({string.Join(", ", cols.Select(Escape))}) VALUES ({string.Join(", ", cols.Select(k => "@" + k))})";
                    
                    await _db.ExecuteAsync(sql, data, transaction);
                }
            }

            transaction.Commit();
        }
        catch
        {
            transaction.Rollback();
            throw;
        }
    }

    #region Multi-Table CRUD - 多表事务性 CRUD

    /// <summary>
    /// 多表插入 - 单个表单数据插入到多个表
    /// </summary>
    public async Task<int> MultiTableInsertAsync(MultiTableCrudDefinition multiTableDef, IDictionary<string, object> data)
    {
        using var transaction = _db.BeginTransaction();
        try
        {
            int mainId = 0;

            // 1. 插入主表
            if (multiTableDef.MainTable != null)
            {
                var mainTableFields = multiTableDef.FormMapping.GetValueOrDefault(multiTableDef.MainTable.Table, new List<FormFieldMappingDefinition>());
                var mainData = FilterDataForTable(data, mainTableFields.Select(f => f.Field).ToList());

                var cols = mainData.Keys.ToList();
                if (cols.Any())
                {
                    var values = string.Join(", ", cols.Select(k => "@" + k));
                    var sql = $"INSERT INTO {Escape(multiTableDef.MainTable.Table)} ({string.Join(", ", cols.Select(Escape))}) VALUES ({values})";
                    await _db.ExecuteAsync(sql, mainData, transaction);

                    // 获取自增主键
                    var lastIdSql = "SELECT last_insert_rowid()";
                    mainId = await _db.ExecuteScalarAsync<int>(lastIdSql, transaction: transaction);
                }
            }

            // 2. 插入关联表
            foreach (var relatedTable in multiTableDef.RelatedTables)
            {
                var tableFields = multiTableDef.FormMapping.GetValueOrDefault(relatedTable.Table, new List<FormFieldMappingDefinition>());
                var fieldNames = tableFields.Select(f => f.Field).ToList();

                if (relatedTable.Type == "many")
                {
                    // 一对多：处理多行数据
                    var rowsData = ExtractRowsForTable(data, relatedTable.Table);
                    foreach (var rowData in rowsData)
                    {
                        var filteredData = FilterDataForTable(rowData, fieldNames);
                        
                        // 添加外键
                        if (!string.IsNullOrEmpty(relatedTable.ForeignKey) && mainId > 0)
                        {
                            filteredData[relatedTable.ForeignKey] = mainId;
                        }

                        var cols = filteredData.Keys.ToList();
                        if (cols.Any())
                        {
                            var values = string.Join(", ", cols.Select(k => "@" + k));
                            var sql = $"INSERT INTO {Escape(relatedTable.Table)} ({string.Join(", ", cols.Select(Escape))}) VALUES ({values})";
                            await _db.ExecuteAsync(sql, filteredData, transaction);
                        }
                    }
                }
                else
                {
                    // 一对一：处理单行数据
                    var filteredData = FilterDataForTable(data, fieldNames);
                    
                    // 添加外键
                    if (!string.IsNullOrEmpty(relatedTable.ForeignKey) && mainId > 0)
                    {
                        filteredData[relatedTable.ForeignKey] = mainId;
                    }

                    var cols = filteredData.Keys.ToList();
                    if (cols.Any())
                    {
                        var values = string.Join(", ", cols.Select(k => "@" + k));
                        var sql = $"INSERT INTO {Escape(relatedTable.Table)} ({string.Join(", ", cols.Select(Escape))}) VALUES ({values})";
                        await _db.ExecuteAsync(sql, filteredData, transaction);
                    }
                }
            }

            transaction.Commit();
            return mainId;
        }
        catch
        {
            transaction.Rollback();
            throw;
        }
    }

    /// <summary>
    /// 多表更新 - 更新多个表的数据
    /// </summary>
    public async Task MultiTableUpdateAsync(MultiTableCrudDefinition multiTableDef, int mainId, IDictionary<string, object> data)
    {
        using var transaction = _db.BeginTransaction();
        try
        {
            // 1. 更新主表
            if (multiTableDef.MainTable != null)
            {
                var mainTableFields = multiTableDef.FormMapping.GetValueOrDefault(multiTableDef.MainTable.Table, new List<FormFieldMappingDefinition>());
                var mainData = FilterDataForTable(data, mainTableFields.Select(f => f.Field).ToList());

                var updateCols = mainData.Keys.Where(k => k != multiTableDef.MainTable.PrimaryKey).ToList();
                if (updateCols.Any())
                {
                    var sets = string.Join(", ", updateCols.Select(k => $"{Escape(k)}=@{k}"));
                    var sql = $"UPDATE {Escape(multiTableDef.MainTable.Table)} SET {sets} WHERE {Escape(multiTableDef.MainTable.PrimaryKey)}=@_id";
                    mainData["_id"] = mainId;
                    await _db.ExecuteAsync(sql, mainData, transaction);
                }
            }

            // 2. 更新关联表
            foreach (var relatedTable in multiTableDef.RelatedTables)
            {
                var tableFields = multiTableDef.FormMapping.GetValueOrDefault(relatedTable.Table, new List<FormFieldMappingDefinition>());
                var fieldNames = tableFields.Select(f => f.Field).ToList();

                if (relatedTable.Type == "many")
                {
                    // 一对多：先删除旧的，再插入新的
                    var fkColumn = relatedTable.ForeignKey ?? $"{multiTableDef.MainTable?.PrimaryKey ?? "Id"}";
                    var deleteSql = $"DELETE FROM {Escape(relatedTable.Table)} WHERE {Escape(fkColumn)}=@id";
                    await _db.ExecuteAsync(deleteSql, new { id = mainId }, transaction);

                    // 插入新数据
                    var rowsData = ExtractRowsForTable(data, relatedTable.Table);
                    foreach (var rowData in rowsData)
                    {
                        var filteredData = FilterDataForTable(rowData, fieldNames);
                        filteredData[fkColumn] = mainId;

                        var cols = filteredData.Keys.ToList();
                        if (cols.Any())
                        {
                            var values = string.Join(", ", cols.Select(k => "@" + k));
                            var sql = $"INSERT INTO {Escape(relatedTable.Table)} ({string.Join(", ", cols.Select(Escape))}) VALUES ({values})";
                            await _db.ExecuteAsync(sql, filteredData, transaction);
                        }
                    }
                }
                else
                {
                    // 一对一：直接更新
                    var filteredData = FilterDataForTable(data, fieldNames);
                    var fkColumn = relatedTable.ForeignKey ?? $"{multiTableDef.MainTable?.PrimaryKey ?? "Id"}";

                    var updateCols = filteredData.Keys.ToList();
                    if (updateCols.Any())
                    {
                        var sets = string.Join(", ", updateCols.Select(k => $"{Escape(k)}=@{k}"));
                        var sql = $"UPDATE {Escape(relatedTable.Table)} SET {sets} WHERE {Escape(fkColumn)}=@_id";
                        filteredData["_id"] = mainId;
                        await _db.ExecuteAsync(sql, filteredData, transaction);
                    }
                }
            }

            transaction.Commit();
        }
        catch
        {
            transaction.Rollback();
            throw;
        }
    }

    /// <summary>
    /// 多表删除 - 删除多个表的关联数据
    /// </summary>
    public async Task MultiTableDeleteAsync(MultiTableCrudDefinition multiTableDef, int mainId)
    {
        using var transaction = _db.BeginTransaction();
        try
        {
            // 1. 先删除关联表数据（外键约束）
            foreach (var relatedTable in multiTableDef.RelatedTables)
            {
                var fkColumn = relatedTable.ForeignKey ?? $"{multiTableDef.MainTable?.PrimaryKey ?? "Id"}";
                var deleteSql = $"DELETE FROM {Escape(relatedTable.Table)} WHERE {Escape(fkColumn)}=@id";
                await _db.ExecuteAsync(deleteSql, new { id = mainId }, transaction);
            }

            // 2. 再删除主表数据
            if (multiTableDef.MainTable != null)
            {
                var deleteSql = $"DELETE FROM {Escape(multiTableDef.MainTable.Table)} WHERE {Escape(multiTableDef.MainTable.PrimaryKey)}=@id";
                await _db.ExecuteAsync(deleteSql, new { id = mainId }, transaction);
            }

            transaction.Commit();
        }
        catch
        {
            transaction.Rollback();
            throw;
        }
    }

    /// <summary>
    /// 获取多表数据
    /// </summary>
    public async Task<Dictionary<string, List<Dictionary<string, object>>>> MultiTableSelectAsync(MultiTableCrudDefinition multiTableDef, int mainId)
    {
        var result = new Dictionary<string, List<Dictionary<string, object>>>();

        // 1. 获取主表数据
        if (multiTableDef.MainTable != null)
        {
            var mainTableFields = multiTableDef.FormMapping.GetValueOrDefault(multiTableDef.MainTable.Table, new List<FormFieldMappingDefinition>());
            var cols = mainTableFields.Any() 
                ? string.Join(", ", mainTableFields.Select(f => Escape(f.Field)))
                : "*";

            var sql = $"SELECT {cols} FROM {Escape(multiTableDef.MainTable.Table)} WHERE {Escape(multiTableDef.MainTable.PrimaryKey)}=@id";
            var row = await _db.QueryFirstOrDefaultAsync(sql, new { id = mainId });
            
            if (row != null)
            {
                result[multiTableDef.MainTable.Table] = new List<Dictionary<string, object>> { ((IDictionary<string, object>)row).ToDictionary(k => k.Key, v => v.Value) };
            }
        }

        // 2. 获取关联表数据
        foreach (var relatedTable in multiTableDef.RelatedTables)
        {
            var tableFields = multiTableDef.FormMapping.GetValueOrDefault(relatedTable.Table, new List<FormFieldMappingDefinition>());
            var cols = tableFields.Any()
                ? string.Join(", ", tableFields.Select(f => Escape(f.Field)))
                : "*";

            var fkColumn = relatedTable.ForeignKey ?? $"{multiTableDef.MainTable?.PrimaryKey ?? "Id"}";
            var sql = $"SELECT {cols} FROM {Escape(relatedTable.Table)} WHERE {Escape(fkColumn)}=@id";
            var rows = await _db.QueryAsync(sql, new { id = mainId });
            
            result[relatedTable.Table] = rows.Select(r => ((IDictionary<string, object>)r).ToDictionary(k => k.Key, v => v.Value)).ToList();
        }

        return result;
    }

    /// <summary>
    /// 为表过滤数据
    /// </summary>
    private IDictionary<string, object> FilterDataForTable(IDictionary<string, object> data, List<string> fields)
    {
        return data.Where(kvp => fields.Contains(kvp.Key, StringComparer.OrdinalIgnoreCase))
                   .ToDictionary(kvp => kvp.Key, kvp => kvp.Value);
    }

    /// <summary>
    /// 提取表的多行数据（用于一对多）
    /// 支持格式：TableName[0].FieldName, TableName[1].FieldName 等
    /// </summary>
    private List<Dictionary<string, object>> ExtractRowsForTable(IDictionary<string, object> data, string tableName)
    {
        var rows = new List<Dictionary<string, object>>();

        // 查找形如 "TableName[0].FieldName" 的键
        var tableKeys = data.Keys.Where(k => k.StartsWith($"{tableName}[", StringComparison.OrdinalIgnoreCase)).ToList();

        if (!tableKeys.Any())
            return rows;

        // 提取行索引 - 匹配格式 [0], [1], [2] 等
        var indices = new HashSet<int>();
        foreach (var key in tableKeys)
        {
            var match = System.Text.RegularExpressions.Regex.Match(key, $@"\[{tableName}\]\[(\d+)\]", System.Text.RegularExpressions.RegexOptions.IgnoreCase);
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
                // 匹配格式：TableName[0].FieldName
                var match = System.Text.RegularExpressions.Regex.Match(key, $@"\[{tableName}\]\[{index}\]\.(.+)", System.Text.RegularExpressions.RegexOptions.IgnoreCase);
                if (match.Success)
                {
                    var field = match.Groups[1].Value;
                    var value = data[key]?.ToString() ?? "";
                    
                    // 尝试转换数字类型
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
                rows.Add(row);
            }
        }

        return rows;
    }

    #endregion

    #endregion
}
