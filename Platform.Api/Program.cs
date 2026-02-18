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

var builder = WebApplication.CreateBuilder(new WebApplicationOptions
{
    Args = args,
    ContentRootPath = Directory.GetCurrentDirectory(),
    WebRootPath = "../wwwroot"
});

// ★ MVC - 支持 Views 和 Razor
builder.Services.AddControllersWithViews();
builder.Services.AddOpenApi();

// ★ 支持通过环境变量选择项目配置
// 使用方式：export LCP_PROJECT=todo 然后 dotnet run
var projectName = Environment.GetEnvironmentVariable("LCP_PROJECT") ?? "app";
var yamlFileName = $"{projectName}_app.yaml";

Console.WriteLine($"");
Console.WriteLine($"╔════════════════════════════════════════════════════════╗");
Console.WriteLine($"║           LowCode Platform - Project Loader            ║");
Console.WriteLine($"╠════════════════════════════════════════════════════════╣");
Console.WriteLine($"║  Project: {projectName,-45} ║");
Console.WriteLine($"║  Config:  {yamlFileName,-45} ║");
Console.WriteLine($"╚════════════════════════════════════════════════════════╝");
Console.WriteLine($"");

// YAML 定义加载
builder.Services.AddSingleton<AppDefinitions>(_ =>
{
    var basePath = AppContext.BaseDirectory;

    // 从 bin/Debug/net10.0 返回到项目根目录
    var yamlPath = Path.Combine(basePath, "..", "..", "..", "..", "Definitions", yamlFileName);
    var pagesPath = Path.Combine(basePath, "..", "..", "..", "..", "Definitions", "pages");

    var fullPath = Path.GetFullPath(yamlPath);
    var pagesFullPath = Path.GetFullPath(pagesPath);

    Console.WriteLine($"[PROJECT] Loading YAML from: {fullPath}");

    if (!File.Exists(fullPath))
    {
        // 如果指定的项目文件不存在，回退到默认的 app.yaml
        Console.WriteLine($"[WARN] {yamlFileName} not found, falling back to app.yaml");
        fullPath = Path.Combine(basePath, "..", "..", "..", "..", "Definitions", "app.yaml");
    }

    return YamlLoader.Load(fullPath, pagesFullPath);
});

// 数据库和服务
builder.Services.AddScoped<DbConnectionFactory>(sp => new DbConnectionFactory(builder.Configuration));
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

// 静态文件
app.UseStaticFiles();

// 中间件：注入 Models 和 Pages 到 ViewData
app.Use(async (context, next) =>
{
    if (context.RequestServices.GetService<AppDefinitions>() is AppDefinitions defs)
    {
        // 注入 Models
        context.Items["Models"] = defs.Models;

        // 注入 Pages
        context.Items["Pages"] = defs.Pages;
    }

    await next();
});

if (app.Environment.IsDevelopment())
{
    app.MapOpenApi();
    app.MapScalarApiReference();
}

// 首页显示项目信息
app.MapGet("/", () =>
{
    return Results.Redirect("/Home");
});

// 添加一个特定的 API 文档入口点
app.MapGet("/docs", () => Results.Redirect("/scalar/v1"));

// 启用控制器 - 这会处理 API 控制器和 MVC 控制器
app.MapControllers();

// 启用 MVC 路由
app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Home}/{action=Index}/{id?}");

app.Run();
