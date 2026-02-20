using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure.Definitions;

namespace Platform.Api.Controllers;

public class ChinookController : Controller
{
    private readonly AppDefinitions _defs;

    public ChinookController(AppDefinitions defs)
    {
        _defs = defs;
    }

    /// <summary>
    /// Chinook 应用首页
    /// </summary>
    [HttpGet("/chinook")]
    public IActionResult Index([FromQuery] string project = "chinook")
    {
        // 设置视图数据
        ViewData["Project"] = project;
        ViewData["ActivePage"] = "Chinook";
        
        // 返回 Chinook 专用首页视图
        return View("~/Views/Chinook/Index.cshtml");
    }
}
