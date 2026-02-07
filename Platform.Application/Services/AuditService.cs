using Dapper;
using Platform.Infrastructure.Data;

namespace Platform.Application.Services;

public class AuditService
{
private readonly DbConnectionFactory _db;

    public AuditService(DbConnectionFactory db)
    {
        _db = db;
    }

    public async Task Log(int userId, string action, string table)
    {
        using var conn = _db.Create();

        var sql = @"INSERT INTO AuditLog
                    (UserId,Action,TableName,Timestamp)
                    VALUES (@UserId,@Action,@Table,@Ts)";

        await conn.ExecuteAsync(sql, new
        {
            UserId = userId,
            Action = action,
            Table = table,
            Ts = DateTime.UtcNow
        });
    }
}
