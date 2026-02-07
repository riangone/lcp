using Microsoft.Data.Sqlite;
using Microsoft.Extensions.Configuration;
using System.Data;

namespace Platform.Infrastructure.Data;

public class DbConnectionFactory
{
    private readonly string _conn;

    public DbConnectionFactory(IConfiguration configuration = null)
    {
        // 优先使用配置中的连接字符串，否则使用默认的 app.db
        if (configuration != null)
        {
            _conn = configuration.GetConnectionString("DefaultConnection") ?? 
                   $"Data Source={Path.Combine(Directory.GetCurrentDirectory(), "app.db")}";
        }
        else
        {
            // 使用当前工作目录中的 app.db
            var dbPath = Path.Combine(Directory.GetCurrentDirectory(), "app.db");
            _conn = $"Data Source={dbPath}";
        }
    }

    public IDbConnection Create()
    {
        return new SqliteConnection(_conn);
    }
}
