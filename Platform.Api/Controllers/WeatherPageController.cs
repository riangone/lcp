using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure.Definitions;
using Platform.Api.Services;

namespace Platform.Api.Controllers;

public class WeatherPageController : Controller
{
    private readonly ProjectScope _projectScope;

    public WeatherPageController(ProjectScope projectScope)
    {
        _projectScope = projectScope;
    }

    [HttpGet("/ui/weather")]
    public IActionResult Index()
    {
        ViewData["Title"] = "Weather App";
        ViewData["ActivePage"] = "Weather";
        ViewData["ProjectName"] = "weather";
        return View();
    }
}
