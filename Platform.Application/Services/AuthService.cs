using Dapper;
using Platform.Infrastructure.Data;

namespace Platform.Application.Services;

public class AuthService
{
private readonly DbConnectionFactory _db;

    public AuthService(DbConnectionFactory db)
    {
        _db = db;
    }

    public async Task<dynamic?> Login(string email, string password)
    {
        using var conn = _db.Create();

        var sql = @"SELECT * FROM Users
                    WHERE Email=@Email AND Password=@Password";

        return await conn.QueryFirstOrDefaultAsync(
            sql,
            new { Email = email, Password = password });
    }
}
