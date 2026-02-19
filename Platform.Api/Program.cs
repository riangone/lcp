using Platform.Infrastructure.Data;
using Platform.Infrastructure.Yaml;
using Platform.Infrastructure;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Repositories;
using Platform.Infrastructure.Shell;
using Platform.Application.Services;
using Platform.Api.TestScenarios;
using Scalar.AspNetCore;
using Platform.Infrastructure.Services;
using System.Text;
using System.Text.Json;
using YamlDotNet.Serialization;
using YamlDotNet.Serialization.NamingConventions;

var builder = WebApplication.CreateBuilder(new WebApplicationOptions
{
    Args = args,
    ContentRootPath = Directory.GetCurrentDirectory(),
    WebRootPath = "../wwwroot"
});

// ★ MVC - 支持 Views 和 Razor
builder.Services.AddControllersWithViews();
builder.Services.AddOpenApi();

// ★ 项目加载器 - 支持独立项目
var projectName = Environment.GetEnvironmentVariable("LCP_PROJECT") ?? "todo";
var projectLoader = new ProjectLoader(projectName);
var projectConfig = projectLoader.LoadProject();

Console.WriteLine($"");
Console.WriteLine($"╔════════════════════════════════════════════════════════╗");
Console.WriteLine($"║           LowCode Platform - Project Loader            ║");
Console.WriteLine($"╠════════════════════════════════════════════════════════╣");
Console.WriteLine($"║  Project: {projectConfig.DisplayName,-45} ║");
Console.WriteLine($"║  Version: {projectConfig.Version,-45} ║");
Console.WriteLine($"║  Database: {projectConfig.DatabasePath,-45} ║");
Console.WriteLine($"╚════════════════════════════════════════════════════════╝");
Console.WriteLine($"");

// YAML 定义加载
builder.Services.AddSingleton<AppDefinitions>(_ =>
{
    var appDefinitions = projectLoader.LoadAppDefinitions();
    Console.WriteLine($"[PROJECT] Loaded {appDefinitions.Models.Count} models and {appDefinitions.Pages.Count} pages");
    return appDefinitions;
});

// 数据库和服务 - 使用项目配置的数据库路径
var dbPath = projectConfig.DatabasePath;
var connString = $"Data Source={dbPath}";
builder.Services.AddScoped<DbConnectionFactory>(sp => new DbConnectionFactory(connString));

builder.Services.AddScoped<DynamicRepository>();
builder.Services.AddScoped<ModelService>();
builder.Services.AddScoped<AuthService>();
builder.Services.AddScoped<AuditService>();
builder.Services.AddSingleton<YamlModelStore>();

// 多表表单服务
builder.Services.AddScoped<PageDataLoader>();
builder.Services.AddScoped<MultiTableSaver>();

// AI 服务和快照服务
builder.Services.AddScoped<ISnapshotRepository, SnapshotRepository>();
builder.Services.AddScoped<IAiSuggestionService, MockAISuggestionService>();
builder.Services.AddScoped<AiIntegrationService>();
builder.Services.AddScoped<AiArchitectureTestScenario>();

// CSRF 保护
builder.Services.AddAntiforgery(options =>
{
    options.HeaderName = "X-CSRF-TOKEN";
});

var app = builder.Build();

// 静态文件 - 添加项目静态资源目录
app.UseStaticFiles();

// 项目静态文件（自定义 CSS/JS）
var projectWwwRoot = Path.Combine(projectLoader.ProjectDirectory, "wwwroot");
if (Directory.Exists(projectWwwRoot))
{
    app.UseStaticFiles(new StaticFileOptions
    {
        FileProvider = new Microsoft.Extensions.FileProviders.PhysicalFileProvider(projectWwwRoot),
        RequestPath = "/project"
    });
    Console.WriteLine($"[STATIC] Serving project static files from: {projectWwwRoot}");
}

// 中间件：注入 Models 和 Pages 到 ViewData
app.Use(async (context, next) =>
{
    if (context.RequestServices.GetService<AppDefinitions>() is AppDefinitions defs)
    {
        context.Items["Models"] = defs.Models;
        context.Items["Pages"] = defs.Pages;
        context.Items["ProjectConfig"] = projectConfig;
    }

    await next();
});

if (app.Environment.IsDevelopment())
{
    app.MapOpenApi();
    app.MapScalarApiReference();
}

// 首页显示项目信息
app.MapGet("/", () => Results.Redirect("/Home"));
app.MapGet("/docs", () => Results.Redirect("/scalar/v1"));

// 启用控制器
app.MapControllers();

// 启用 MVC 路由
app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Home}/{action=Index}/{id?}");

Console.WriteLine($"");
Console.WriteLine($"[READY] Application ready! Access at http://localhost:5267");
Console.WriteLine($"");

app.Run();

/// <summary>
/// 项目加载器 - 负责加载独立项目的配置和资源
/// </summary>
public class ProjectLoader
{
    public string ProjectName { get; }
    public string ProjectDirectory { get; }
    public string FrameworkDirectory { get; }

    public ProjectLoader(string projectName)
    {
        ProjectName = projectName;
        
        // 项目目录：支持环境变量 LCP_PROJECTS_DIR 或默认路径
        var projectsDir = Environment.GetEnvironmentVariable("LCP_PROJECTS_DIR") ?? "/home/ubuntu/ws/lcp/Projects";
        ProjectDirectory = Path.Combine(projectsDir, projectName);
        
        // 框架目录
        FrameworkDirectory = "/home/ubuntu/ws/lcp/Framework";
        
        if (!Directory.Exists(ProjectDirectory))
        {
            throw new DirectoryNotFoundException($"Project '{projectName}' not found at {ProjectDirectory}");
        }
        
        Console.WriteLine($"[PROJECT] Project directory: {ProjectDirectory}");
    }

    /// <summary>
    /// 加载项目配置
    /// </summary>
    public ProjectConfiguration LoadProject()
    {
        var projectFile = Path.Combine(ProjectDirectory, "project.yaml");
        if (!File.Exists(projectFile))
        {
            throw new FileNotFoundException($"project.yaml not found at {projectFile}");
        }

        // 使用 YamlDotNet 解析
        var yaml = File.ReadAllText(projectFile);
        var config = ParseProjectYaml(yaml);

        // 设置数据库路径（相对于项目目录）
        var dbPath = Path.Combine(ProjectDirectory, config.Database.Path);
        config.Database.Path = dbPath;

        Console.WriteLine($"[DB] Database path: {config.DatabasePath}");

        // 初始化数据库（如果需要）
        InitializeDatabase(config);

        return config;
    }

    /// <summary>
    /// 加载应用定义
    /// </summary>
    public AppDefinitions LoadAppDefinitions()
    {
        var appFile = Path.Combine(ProjectDirectory, "app.yaml");
        var pagesDir = Path.Combine(ProjectDirectory, "pages");
        
        if (!File.Exists(appFile))
        {
            throw new FileNotFoundException($"app.yaml not found at {appFile}");
        }

        return YamlLoader.Load(appFile, pagesDir);
    }

    #region Private Methods

    private ProjectConfiguration ParseProjectYaml(string yaml)
    {
        // 使用 YamlDotNet 解析
        var deserializer = new DeserializerBuilder()
            .WithNamingConvention(UnderscoredNamingConvention.Instance)
            .IgnoreUnmatchedProperties()
            .Build();
        
        try
        {
            var config = deserializer.Deserialize<ProjectConfiguration>(yaml);
            
            // 确保默认值
            if (config.Database == null)
            {
                config.Database = new DatabaseConfig();
            }
            if (string.IsNullOrEmpty(config.Database.Path))
            {
                config.Database.Path = "app.db";
            }
            if (string.IsNullOrEmpty(config.Database.Schema))
            {
                config.Database.Schema = "schema.sql";
            }
            if (string.IsNullOrEmpty(config.Database.SeedData))
            {
                config.Database.SeedData = "data.sql";
            }
            
            return config;
        }
        catch (Exception ex)
        {
            Console.WriteLine($"[ERROR] Failed to parse project.yaml: {ex.Message}");
            // 返回默认配置
            return new ProjectConfiguration
            {
                Name = "unknown",
                DisplayName = "Unknown Project",
                Version = "1.0.0",
                Database = new DatabaseConfig { Path = "app.db" }
            };
        }
    }

    private void InitializeDatabase(ProjectConfiguration config)
    {
        var dbPath = config.DatabasePath;
        
        if (!File.Exists(dbPath))
        {
            Console.WriteLine($"[DB] Creating database: {dbPath}");
            
            var schemaFile = Path.Combine(ProjectDirectory, config.Database.Schema);
            if (File.Exists(schemaFile))
            {
                Console.WriteLine($"[DB] Executing schema: {schemaFile}");
            }
        }
        else
        {
            Console.WriteLine($"[DB] Database exists: {dbPath}");
        }
    }

    #endregion
}

/// <summary>
/// 项目配置
/// </summary>
public class ProjectConfiguration
{
    public string Name { get; set; } = "";
    public string DisplayName { get; set; } = "";
    public string Version { get; set; } = "1.0.0";
    public string Description { get; set; } = "";
    
    // 数据库配置
    public DatabaseConfig Database { get; set; } = new();
    
    // 便捷属性
    public string DatabasePath => Database?.Path ?? "app.db";
    public string SchemaFile => Database?.Schema ?? "schema.sql";
    public string DataFile => Database?.SeedData ?? "data.sql";
}

/// <summary>
/// 数据库配置
/// </summary>
public class DatabaseConfig
{
    public string Type { get; set; } = "sqlite";
    public string Path { get; set; } = "app.db";
    public string Schema { get; set; } = "schema.sql";
    public string SeedData { get; set; } = "data.sql";
}
