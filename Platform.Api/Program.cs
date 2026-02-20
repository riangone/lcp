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
using Platform.Api.Services;
using System.Text;
using System.Text.Json;
using YamlDotNet.Serialization;
using YamlDotNet.Serialization.NamingConventions;
using Microsoft.AspNetCore.Authentication.JwtBearer;
using Microsoft.IdentityModel.Tokens;
using System.Text;

var builder = WebApplication.CreateBuilder(new WebApplicationOptions
{
    Args = args,
    ContentRootPath = Directory.GetCurrentDirectory(),
    WebRootPath = "../wwwroot"
});

// ★ MVC - 支持 Views 和 Razor
builder.Services.AddControllersWithViews()
    .AddRazorOptions(options =>
    {
        // 添加项目视图目录
        var projectsDir = Environment.GetEnvironmentVariable("LCP_PROJECTS_DIR") ?? "/home/ubuntu/ws/lcp/Projects";
        if (Directory.Exists(projectsDir))
        {
            foreach (var projectDir in Directory.GetDirectories(projectsDir))
            {
                var viewsDir = Path.Combine(projectDir, "views");
                if (Directory.Exists(viewsDir))
                {
                    // 支持 views/{viewName}.cshtml 格式
                    options.ViewLocationFormats.Add(Path.Combine(viewsDir, "{0}.cshtml"));
                }
            }
        }
    });
builder.Services.AddOpenApi();

// ★ JWT 认证配置
builder.Services.AddAuthentication(options =>
{
    options.DefaultAuthenticateScheme = JwtBearerDefaults.AuthenticationScheme;
    options.DefaultChallengeScheme = JwtBearerDefaults.AuthenticationScheme;
})
.AddJwtBearer(options =>
{
    options.TokenValidationParameters = new TokenValidationParameters
    {
        ValidateIssuer = true,
        ValidateAudience = true,
        ValidateLifetime = true,
        ValidateIssuerSigningKey = true,
        ValidIssuer = builder.Configuration["Jwt:Issuer"] ?? "LowCodePlatform",
        ValidAudience = builder.Configuration["Jwt:Audience"] ?? "LowCodePlatform",
        IssuerSigningKey = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(
            builder.Configuration["Jwt:Key"] ?? "YourSuperSecretKeyThatIsAtLeast32CharactersLong!"))
    };

    // 支持从 Cookie 读取 Token
    options.Events = new JwtBearerEvents
    {
        OnMessageReceived = context =>
        {
            var token = context.Request.Cookies["token"];
            if (!string.IsNullOrEmpty(token))
            {
                context.Token = token;
            }
            return Task.CompletedTask;
        }
    };
});

builder.Services.AddAuthorization();

// ★ 项目管理服务 - 支持运行时动态切换项目
var projectsDirectory = Environment.GetEnvironmentVariable("LCP_PROJECTS_DIR") ?? "/home/ubuntu/ws/lcp/Projects";
builder.Services.AddSingleton<ProjectManager>(sp => 
{
    var logger = sp.GetRequiredService<ILogger<ProjectManager>>();
    return new ProjectManager(projectsDirectory, logger);
});

// ★ 项目作用域服务 - 每请求存储当前项目
builder.Services.AddScoped<ProjectScope>();

// YAML 定义加载 - 从 ProjectScope 动态获取（scoped 而非 singleton）
builder.Services.AddScoped<AppDefinitions>(sp =>
{
    var projectScope = sp.GetRequiredService<ProjectScope>();
    return projectScope.CurrentProject?.AppDefinitions 
        ?? throw new InvalidOperationException("No project selected");
});

// 数据库和服务 - 从 ProjectScope 动态获取数据库路径
builder.Services.AddScoped<DbConnectionFactory>(sp =>
{
    var projectScope = sp.GetRequiredService<ProjectScope>();
    var dbPath = projectScope.CurrentProject?.DatabasePath ?? "app.db";
    var connString = $"Data Source={dbPath}";
    return new DbConnectionFactory(connString);
});

builder.Services.AddScoped<DynamicRepository>();
builder.Services.AddScoped<ModelService>();
builder.Services.AddScoped<Platform.Infrastructure.Services.IAuthService, Platform.Infrastructure.Services.AuthService>();
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

// 静态文件
app.UseStaticFiles();

// ★ 认证和授权中间件
app.UseAuthentication();
app.UseAuthorization();

// 中间件：从 URL 参数切换项目（必须在所有控制器之前执行）
app.Use(async (context, next) =>
{
    var projectScope = context.RequestServices.GetRequiredService<ProjectScope>();
    var projectName = context.Request.Query["project"].FirstOrDefault() 
        ?? context.Request.Headers["X-Project"].FirstOrDefault();
    
    if (!string.IsNullOrEmpty(projectName))
    {
        if (projectScope.SwitchProject(projectName))
        {
            context.Response.Headers["X-Project-Switched"] = projectName;
            Console.WriteLine($"[PROJECT] Switched to: {projectName}");
        }
    }
    
    // 存储当前项目到 HttpContext 供视图使用
    if (projectScope.CurrentProject != null)
    {
        context.Items["CurrentProject"] = projectScope.CurrentProject;
        context.Items["Models"] = projectScope.CurrentProject.AppDefinitions.Models;
        context.Items["Pages"] = projectScope.CurrentProject.AppDefinitions.Pages;
    }

    await next();
});

if (app.Environment.IsDevelopment())
{
    app.MapOpenApi();
    app.MapScalarApiReference();
}

// 首页显示项目信息（保留 query 参数）
app.MapGet("/", (HttpContext context) =>
{
    var project = context.Request.Query["project"].FirstOrDefault();
    if (string.IsNullOrEmpty(project))
    {
        return Results.Redirect("/Home");
    }
    // 天气应用重定向到专用 UI
    if (project == "weather")
    {
        return Results.Redirect($"/ui/weather?project={project}");
    }
    return Results.Redirect($"/Home?project={project}");
});
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
