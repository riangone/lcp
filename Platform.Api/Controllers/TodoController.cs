using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure.Definitions;

namespace Platform.Api.Controllers;

public class TodoController : Controller
{
    private readonly AppDefinitions _defs;

    public TodoController(AppDefinitions defs)
    {
        _defs = defs;
    }

    /// <summary>
    /// TODO 应用首页
    /// </summary>
    [HttpGet("/todo")]
    public IActionResult Index([FromQuery] string project = "todo")
    {
        // 设置视图数据
        ViewData["Project"] = project;
        ViewData["ActivePage"] = "TODO";
        
        // 返回 TODO 专用首页视图
        return View("~/Views/Todo/Index.cshtml");
    }
}
