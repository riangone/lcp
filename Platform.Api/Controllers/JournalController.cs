using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure.Definitions;

namespace Platform.Api.Controllers;

public class JournalController : Controller
{
    private readonly AppDefinitions _defs;

    public JournalController(AppDefinitions defs)
    {
        _defs = defs;
    }

    /// <summary>
    /// Journal 应用首页
    /// </summary>
    [HttpGet("/journal")]
    public IActionResult Index([FromQuery] string project = "journal")
    {
        // 设置视图数据
        ViewData["Project"] = project;
        ViewData["ActivePage"] = "Journal";
        
        // 返回 Journal 专用首页视图
        return View("~/Views/Journal/Index.cshtml");
    }
}
