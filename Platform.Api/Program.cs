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

// YAML 定义加载
builder.Services.AddSingleton<AppDefinitions>(_ =>
{
    var basePath = AppContext.BaseDirectory;
    Console.WriteLine($"[DEBUG] BaseDirectory: {basePath}");

    // 从 bin/Debug/net10.0 返回到项目根目录
    var yamlPath = Path.Combine(basePath, "..", "..", "..", "..", "Definitions", "app.yaml");
    var pagesPath = Path.Combine(basePath, "..", "..", "..", "..", "Definitions", "pages");
    
    var fullPath = Path.GetFullPath(yamlPath);
    var pagesFullPath = Path.GetFullPath(pagesPath);
    
    Console.WriteLine($"[DEBUG] YAML Path: {fullPath}, Exists: {File.Exists(fullPath)}");
    Console.WriteLine($"[DEBUG] Pages Path: {pagesFullPath}, Exists: {Directory.Exists(pagesFullPath)}");

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

// AI服务和快照服务
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

// 首页显示所有模型的链接
app.MapGet("/", () => Results.Redirect("/Home"));

// 添加一个特定的API文档入口点
app.MapGet("/docs", () => Results.Redirect("/scalar/v1"));

// 启用控制器 - 这会处理API控制器和MVC控制器
app.MapControllers();

// 启用MVC路由
app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Home}/{action=Index}/{id?}");

app.Run();
