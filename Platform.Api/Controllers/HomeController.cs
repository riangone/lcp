using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure.Definitions;

namespace Platform.Api.Controllers;

public class HomeController : Controller
{
    private readonly AppDefinitions _defs;

    public HomeController(AppDefinitions defs)
    {
        _defs = defs;
    }

    [HttpGet("/Home")]
    public IActionResult Index()
    {
        ViewData["Title"] = "LowCode Platform - Home";
        ViewData["ActivePage"] = "Home";
        ViewData["Models"] = _defs.Models;
        ViewData["Pages"] = _defs.Pages;
        ViewData["ProjectName"] = Environment.GetEnvironmentVariable("LCP_PROJECT") ?? "app";
        
        // 注入项目配置
        if (HttpContext.Items.TryGetValue("ProjectConfig", out var projectConfig))
        {
            ViewData["ProjectConfig"] = projectConfig;
        }

        return View();
    }
}
