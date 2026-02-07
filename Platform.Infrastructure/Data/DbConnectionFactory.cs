using Microsoft.Data.Sqlite;
using System.Data;

namespace Platform.Infrastructure.Data;

public class DbConnectionFactory
{
    private readonly string _conn;

    public DbConnectionFactory()
    {
        // 使用当前工作目录中的 app.db
        var dbPath = Path.Combine(Directory.GetCurrentDirectory(), "app.db");
        _conn = $"Data Source={dbPath}";
    }

    public IDbConnection Create()
    {
        return new SqliteConnection(_conn);
    }
}
