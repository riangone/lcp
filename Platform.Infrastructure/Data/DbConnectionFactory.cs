using Microsoft.Data.Sqlite;
using Microsoft.Extensions.Configuration;
using System.Data;

namespace Platform.Infrastructure.Data;

public class DbConnectionFactory
{
    private readonly string _conn;

    public DbConnectionFactory(IConfiguration configuration = null)
    {
        // 支持通过环境变量指定数据库路径
        // 使用方式：export LCP_DB_PATH=/path/to/todo.db
        var dbPath = Environment.GetEnvironmentVariable("LCP_DB_PATH");
        
        if (!string.IsNullOrEmpty(dbPath))
        {
            _conn = $"Data Source={dbPath}";
        }
        // 优先使用配置中的连接字符串，否则使用默认的 app.db
        else if (configuration != null)
        {
            _conn = configuration.GetConnectionString("DefaultConnection") ??
                   $"Data Source={Path.Combine(Directory.GetCurrentDirectory(), "app.db")}";
        }
        else
        {
            // 使用当前工作目录中的 app.db
            dbPath = Path.Combine(Directory.GetCurrentDirectory(), "app.db");
            _conn = $"Data Source={dbPath}";
        }
    }

    public IDbConnection Create()
    {
        return new SqliteConnection(_conn);
    }
}
