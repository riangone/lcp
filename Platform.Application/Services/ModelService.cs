using Dapper;
using Platform.Infrastructure.Data;
using Platform.Infrastructure.Yaml;

namespace Platform.Application.Services;

public class ModelService
{
private readonly YamlModelStore _store;
    private readonly DbConnectionFactory _db;

    public ModelService(YamlModelStore store, DbConnectionFactory db)
    {
        _store = store;
        _db = db;
    }

    public async Task<IEnumerable<dynamic>> GetAll(string model)
    {
        var def = _store.Get(model);

        using var conn = _db.Create();
        var sql = $"SELECT * FROM {def.Table}";
        return await conn.QueryAsync(sql);
    }
}
