using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure.Definitions;
using Platform.Api.Services;

namespace Platform.Api.Controllers;

public class WeatherUiController : Controller
{
    private readonly ProjectScope _projectScope;

    public WeatherUiController(ProjectScope projectScope)
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
