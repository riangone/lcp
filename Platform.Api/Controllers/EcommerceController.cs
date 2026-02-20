using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure.Definitions;

namespace Platform.Api.Controllers;

public class EcommerceController : Controller
{
    private readonly AppDefinitions _defs;

    public EcommerceController(AppDefinitions defs)
    {
        _defs = defs;
    }

    /// <summary>
    /// Ecommerce 应用首页
    /// </summary>
    [HttpGet("/ecommerce")]
    public IActionResult Index([FromQuery] string project = "ecommerce")
    {
        // 设置视图数据
        ViewData["Project"] = project;
        ViewData["ActivePage"] = "Ecommerce";
        
        // 返回 Ecommerce 专用首页视图
        return View("~/Views/Ecommerce/Index.cshtml");
    }
}
