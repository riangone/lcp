using Dapper;
using System.Data;
using Platform.Infrastructure.Data;
using Platform.Infrastructure.Definitions;

namespace Platform.Infrastructure.Services;

/// <summary>
/// 页面数据加载器
/// </summary>
public class PageDataLoader
{
    private readonly IDbConnection _db;

    public PageDataLoader(DbConnectionFactory factory)
    {
        _db = factory.Create();
    }

    /// <summary>
    /// 加载页面数据
    /// </summary>
    public async Task<Dictionary<string, object>> LoadPageDataAsync(
        DataLoadingConfig config,
        IDictionary<string, object> parameters,
        CancellationToken cancellationToken = default)
    {
        var results = new Dictionary<string, object>();

        if (config.Sources == null || config.Sources.Count == 0)
        {
            return results;
        }

        if (config.Strategy == LoadStrategy.Parallel)
        {
            // 并行加载所有数据源
            var tasks = config.Sources.Select(async source =>
            {
                var data = await LoadSourceAsync(source, parameters, cancellationToken);
                return new { source.Id, Data = data };
            }).ToList();

            var sourceTasks = await Task.WhenAll(tasks);
            foreach (var item in sourceTasks)
            {
                results[item.Id] = item.Data;
            }
        }
        else if (config.Strategy == LoadStrategy.Sequential)
        {
            // 串行加载（有依赖关系时）
            var resolvedParams = new Dictionary<string, object>(parameters);

            foreach (var source in config.Sources)
            {
                var data = await LoadSourceAsync(source, resolvedParams, cancellationToken);
                results[source.Id] = data;

                // 将结果添加到参数中供后续数据源使用
                if (data is System.Collections.IList dataList && dataList.Count > 0)
                {
                    var firstRow = dataList[0] as IDictionary<string, object>;
                    if (firstRow != null)
                    {
                        foreach (var kvp in firstRow)
                        {
                            resolvedParams[$"{source.Id}.{kvp.Key}"] = kvp.Value;
                        }
                    }
                }
            }
        }
        else if (config.Strategy == LoadStrategy.SingleQuery)
        {
            // 单查询加载所有（需要特殊配置）
            results = await LoadSingleQueryAsync(config, parameters, cancellationToken);
        }

        return results;
    }

    /// <summary>
    /// 加载单个数据源
    /// </summary>
    private async Task<object> LoadSourceAsync(
        DataSourceConfig source,
        IDictionary<string, object> parameters,
        CancellationToken cancellationToken = default)
    {
        var resolvedParams = ResolveParameters(source.Parameters, parameters);

        if (source.Type == "query")
        {
            if (string.IsNullOrEmpty(source.Query))
            {
                throw new ArgumentException($"Query is required for query type source: {source.Id}");
            }

            var sql = source.Query;
            var rows = await _db.QueryAsync(sql, resolvedParams);
            return rows.ToList();
        }
        else if (source.Type == "table")
        {
            if (string.IsNullOrEmpty(source.Table))
            {
                throw new ArgumentException($"Table is required for table type source: {source.Id}");
            }

            var where = string.IsNullOrEmpty(source.Where)
                ? "1=1"
                : source.Where;

            // 处理 where 条件中的 null/DBNull 参数 - 如果参数为 null 则移除该条件
            if (!string.IsNullOrEmpty(source.Where))
            {
                where = ProcessWhereClause(source.Where, resolvedParams);
            }

            // 如果 where 条件处理后为空，返回空结果
            if (string.IsNullOrWhiteSpace(where) || where == "1=1" && resolvedParams.Any(kvp => kvp.Value == DBNull.Value))
            {
                // 检查是否所有参数都是 DBNull
                var hasValidParams = resolvedParams.Any(kvp => kvp.Value != DBNull.Value);
                if (!hasValidParams && source.Where != null && source.Where.Contains("@"))
                {
                    // 所有参数都是 DBNull 且 where 中有参数引用，返回空结果
                    return new List<object>();
                }
            }

            var sql = $"SELECT * FROM {Escape(source.Table)} WHERE {where}";

            if (source.LoadAll)
            {
                var rows = await _db.QueryAsync(sql, resolvedParams);
                return rows.ToList();
            }
            else
            {
                var row = await _db.QueryFirstOrDefaultAsync(sql, resolvedParams);
                // 返回数组格式，即使是单行
                return row == null ? new List<object>() : new List<object> { (IDictionary<string, object>)row };
            }
        }

        return new List<object>();
    }

    /// <summary>
    /// 处理 where 条件，移除值为 null 的参数条件
    /// </summary>
    private string ProcessWhereClause(string where, IDictionary<string, object> parameters)
    {
        var result = where;
        
        // 查找所有 @ParameterName 模式的参数
        var paramPattern = @"@(\w+)";
        var matches = System.Text.RegularExpressions.Regex.Matches(where, paramPattern);
        
        foreach (System.Text.RegularExpressions.Match match in matches)
        {
            var paramName = match.Groups[1].Value;
            if (parameters.TryGetValue(paramName, out var paramValue))
            {
                // 参数为 null、DBNull.Value、0 或空字符串时，移除条件
                var shouldRemove = paramValue == null || 
                                   paramValue == DBNull.Value || 
                                   (paramValue is int i && i == 0) ||
                                   (paramValue is string s && string.IsNullOrEmpty(s));
                
                if (shouldRemove)
                {
                    // 参数为 null，移除整个条件（假设条件格式为：AND Field = @Param 或 WHERE Field = @Param）
                    // 简单处理：移除包含该参数的条件子句
                    var conditionPattern = $@"(\s*(AND\s+|OR\s+|WHERE\s+))?[^\s]+\s*=\s*@{paramName}";
                    result = System.Text.RegularExpressions.Regex.Replace(result, conditionPattern, "", System.Text.RegularExpressions.RegexOptions.IgnoreCase);
                }
            }
        }
        
        // 清理多余的 AND/OR 在开头
        result = System.Text.RegularExpressions.Regex.Replace(result, @"^\s*(AND\s+|OR\s+)", "", System.Text.RegularExpressions.RegexOptions.IgnoreCase);
        
        // 如果结果为空，返回 1=1
        return string.IsNullOrWhiteSpace(result) ? "1=1" : result;
    }

    /// <summary>
    /// 单查询加载
    /// </summary>
    private async Task<Dictionary<string, object>> LoadSingleQueryAsync(
        DataLoadingConfig config,
        IDictionary<string, object> parameters,
        CancellationToken cancellationToken = default)
    {
        var results = new Dictionary<string, object>();

        // 如果配置了单查询，执行该查询并将结果映射到不同的数据源
        // 这需要额外的配置支持，暂时返回空结果
        await Task.CompletedTask;
        return results;
    }

    /// <summary>
    /// 解析参数
    /// </summary>
    private Dictionary<string, object> ResolveParameters(
        List<ParameterConfig>? parameterConfigs,
        IDictionary<string, object> inputParameters)
    {
        var resolved = new Dictionary<string, object>();

        if (parameterConfigs == null)
        {
            return inputParameters.ToDictionary(k => k.Key, v => v.Value);
        }

        foreach (var param in parameterConfigs)
        {
            var value = param.Source switch
            {
                ParameterSource.QueryString => GetParameterValue(param.Name, inputParameters, param.Default),
                ParameterSource.Form => GetParameterValue(param.Name, inputParameters, param.Default),
                ParameterSource.Route => GetParameterValue(param.Name, inputParameters, param.Default),
                ParameterSource.Constant => param.Constant,
                ParameterSource.GeneratedId => GetGeneratedId(param.FromTable, param.Field, inputParameters),
                _ => param.Default
            };

            // 将 null 值也添加到字典中，以便后续处理
            resolved[param.Name] = value ?? DBNull.Value;
        }

        return resolved;
    }

    /// <summary>
    /// 获取参数值
    /// </summary>
    private object? GetParameterValue(string name, IDictionary<string, object> parameters, object? defaultValue)
    {
        if (parameters.TryGetValue(name, out var value))
        {
            return value;
        }

        // 尝试不区分大小写
        var key = parameters.Keys.FirstOrDefault(k => k.Equals(name, StringComparison.OrdinalIgnoreCase));
        if (key != null)
        {
            return parameters[key];
        }

        return defaultValue;
    }

    /// <summary>
    /// 获取生成的 ID
    /// </summary>
    private object? GetGeneratedId(string? fromTable, string? field, IDictionary<string, object> parameters)
    {
        if (string.IsNullOrEmpty(fromTable) || string.IsNullOrEmpty(field))
        {
            return null;
        }

        var key = $"{fromTable}.{field}";
        return parameters.TryGetValue(key, out var value) ? value : null;
    }

    /// <summary>
    /// 转义表名
    /// </summary>
    private string Escape(string identifier)
    {
        return identifier.Replace("\"", "\"\"");
    }
}
