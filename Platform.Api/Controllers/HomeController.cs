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

    public IActionResult Index()
    {
        // 传递模型定义给视图
        ViewData["Models"] = _defs.Models;
        ViewData["Pages"] = _defs.Pages;
        return View();
    }
}