using Platform.Infrastructure.Data;
using Platform.Infrastructure.Yaml;
using Platform.Infrastructure;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Repositories;
using Platform.Application.Services;
using Scalar.AspNetCore;

var builder = WebApplication.CreateBuilder(new WebApplicationOptions
{
    Args = args,
    ContentRootPath = Directory.GetCurrentDirectory(),
    WebRootPath = "wwwroot"
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
    var fullPath = Path.GetFullPath(yamlPath);
    Console.WriteLine($"[DEBUG] YAML Path: {fullPath}");
    Console.WriteLine($"[DEBUG] File Exists: {File.Exists(fullPath)}");
    
    return YamlLoader.Load(fullPath);
});

// 数据库和服务
builder.Services.AddScoped<DbConnectionFactory>();
builder.Services.AddScoped<DynamicRepository>();
builder.Services.AddScoped<ModelService>();
builder.Services.AddScoped<AuthService>();
builder.Services.AddScoped<AuditService>();
builder.Services.AddSingleton<YamlModelStore>();

// CSRF 保护
builder.Services.AddAntiforgery(options =>
{
    options.HeaderName = "X-CSRF-TOKEN";
});

var app = builder.Build();

// 静态文件
app.UseStaticFiles();

if (app.Environment.IsDevelopment())
{
    app.MapOpenApi();
    app.MapScalarApiReference();
}

app.MapControllers();
app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Home}/{action=Index}/{id?}");

// 首页显示所有模型的链接
app.MapGet("/", () => Results.Redirect("/Home"));

// 添加一个特定的API文档入口点
app.MapGet("/docs", () => Results.Redirect("/scalar/v1"));

// 映射控制器路由
app.MapControllerRoute(
    name: "home",
    pattern: "/Home",
    defaults: new { controller = "Home", action = "Index" });

app.Run();
