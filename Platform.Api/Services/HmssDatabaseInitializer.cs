using Microsoft.Data.Sqlite;

namespace Platform.Api.Services;

/// <summary>
/// HMSS 数据库初始化服务
/// </summary>
public interface IHmssDatabaseInitializer
{
    Task InitializeAsync();
}

public class HmssDatabaseInitializer : IHmssDatabaseInitializer
{
    private readonly string _connectionString;
    private readonly ILogger<HmssDatabaseInitializer> _logger;
    private readonly IWebHostEnvironment _environment;

    public HmssDatabaseInitializer(
        string connectionString,
        ILogger<HmssDatabaseInitializer> logger,
        IWebHostEnvironment environment)
    {
        _connectionString = connectionString;
        _logger = logger;
        _environment = environment;
    }

    public async Task InitializeAsync()
    {
        try
        {
            _logger.LogInformation("Initializing HMSS database...");

            // 确保数据库目录存在
            var dbPath = GetDatabasePath();
            var dbDir = Path.GetDirectoryName(dbPath);
            if (!string.IsNullOrEmpty(dbDir) && !Directory.Exists(dbDir))
            {
                Directory.CreateDirectory(dbDir);
                _logger.LogInformation($"Created database directory: {dbDir}");
            }

            // 执行 Schema
            await ExecuteSchemaAsync(dbPath);

            _logger.LogInformation("HMSS database initialized successfully");
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Failed to initialize HMSS database");
            throw;
        }
    }

    private string GetDatabasePath()
    {
        var dbName = "hmss.db";
        
        // 开发环境使用项目目录
        if (_environment.IsDevelopment())
        {
            var projectsDir = Environment.GetEnvironmentVariable("LCP_PROJECTS_DIR") ?? "/home/ubuntu/ws/lcp/Projects";
            return Path.Combine(projectsDir, "hmss", dbName);
        }
        
        // 生产环境使用数据目录
        var dataDir = Path.Combine(_environment.ContentRootPath, "App_Data");
        return Path.Combine(dataDir, dbName);
    }

    private async Task ExecuteSchemaAsync(string dbPath)
    {
        await using var connection = new SqliteConnection($"Data Source={dbPath}");
        await connection.OpenAsync();

        // 读取 Schema 文件
        var schemaPath = Path.Combine(_environment.ContentRootPath, "Definitions", "hmss", "schema.sql");
        
        if (!File.Exists(schemaPath))
        {
            _logger.LogWarning($"Schema file not found: {schemaPath}");
            return;
        }

        var schema = await File.ReadAllTextAsync(schemaPath);
        
        // 分割 SQL 语句并执行
        var statements = SplitSqlStatements(schema);
        
        foreach (var statement in statements)
        {
            if (string.IsNullOrWhiteSpace(statement))
                continue;

            try
            {
                await using var command = connection.CreateCommand();
                command.CommandText = statement;
                await command.ExecuteNonQueryAsync();
            }
            catch (Exception ex)
            {
                _logger.LogWarning(ex, "Error executing SQL statement: {Statement}", 
                    statement.Length > 100 ? statement[..100] + "..." : statement);
            }
        }
    }

    private List<string> SplitSqlStatements(string sql)
    {
        // 简单的 SQL 语句分割（按分号分割）
        return sql.Split(';', StringSplitOptions.RemoveEmptyEntries)
            .Select(s => s.Trim())
            .Where(s => !s.StartsWith("--") && !s.StartsWith("SELECT") && !s.StartsWith("INSERT OR IGNORE"))
            .Concat(
                sql.Split(';', StringSplitOptions.RemoveEmptyEntries)
                    .Select(s => s.Trim())
                    .Where(s => s.StartsWith("INSERT OR IGNORE"))
            )
            .ToList();
    }
}

/// <summary>
/// 扩展方法
/// </summary>
public static class HmssDatabaseInitializerExtensions
{
    public static IServiceCollection AddHmssDatabaseInitializer(
        this IServiceCollection services,
        string connectionString)
    {
        services.AddSingleton<IHmssDatabaseInitializer>(sp =>
        {
            var logger = sp.GetRequiredService<ILogger<HmssDatabaseInitializer>>();
            var env = sp.GetRequiredService<IWebHostEnvironment>();
            return new HmssDatabaseInitializer(connectionString, logger, env);
        });

        return services;
    }

    public static async Task InitializeHmssDatabaseAsync(this IServiceProvider serviceProvider)
    {
        using var scope = serviceProvider.CreateScope();
        var initializer = scope.ServiceProvider.GetRequiredService<IHmssDatabaseInitializer>();
        await initializer.InitializeAsync();
    }
}
