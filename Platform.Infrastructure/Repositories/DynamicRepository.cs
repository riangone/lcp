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
}
