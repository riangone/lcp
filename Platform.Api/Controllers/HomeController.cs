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
        var project = _projectScope.CurrentProject;

        ViewData["Title"] = project?.HomeConfig?.Title ?? "LowCode Platform";
        ViewData["ActivePage"] = "Home";
        ViewData["Models"] = project?.AppDefinitions.Models;
        ViewData["Pages"] = project?.AppDefinitions.Pages;
        ViewData["ProjectName"] = project?.Name ?? "app";
        ViewData["ProjectDisplayName"] = project?.DisplayName ?? "LowCode Platform";
        ViewData["ProjectConfig"] = project;

        // 传递 HomeConfig 到视图
        return View(project?.HomeConfig);
    }
}
