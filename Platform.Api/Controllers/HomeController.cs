using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure.Definitions;
using Platform.Api.Services;

namespace Platform.Api.Controllers;

public class HomeController : Controller
{
    private readonly ProjectScope _projectScope;

    public HomeController(ProjectScope projectScope)
    {
        _projectScope = projectScope;
    }

    [HttpGet("/Home")]
    public IActionResult Index()
    {
        ViewData["Title"] = "LowCode Platform";
        ViewData["ActivePage"] = "Home";
        ViewData["Models"] = _projectScope.CurrentProject?.AppDefinitions.Models;
        ViewData["Pages"] = _projectScope.CurrentProject?.AppDefinitions.Pages;
        ViewData["ProjectName"] = _projectScope.CurrentProject?.Name ?? "app";
        ViewData["ProjectConfig"] = _projectScope.CurrentProject;
        
        // 获取所有可用项目
        ViewData["AvailableProjects"] = _projectScope.GetAvailableProjects();

        return View();
    }
}
