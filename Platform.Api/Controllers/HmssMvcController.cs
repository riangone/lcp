using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Authorization;
using Platform.Infrastructure;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Repositories;
using Platform.Api.Services;

namespace Platform.Api.Controllers;

/// <summary>
/// HMSS 系统 MVC 控制器
/// 提供 HMSS 系统的页面渲染
///
/// 使用框架的 DynamicRepository 和 ModelBinder 进行数据访问和绑定
/// 但使用 HMSS 专用的视图和布局
/// </summary>
public class HmssPageController : Controller
{
    private readonly DynamicRepository _repo;
    private readonly ProjectScope _projectScope;

    public HmssPageController(
        DynamicRepository repo,
        ProjectScope projectScope)
    {
        _repo = repo;
        _projectScope = projectScope;
    }

    /// <summary>
    /// HMSS 登录页面（公开访问）
    /// </summary>
    [HttpGet("/hmss/login")]
    [AllowAnonymous]
    public IActionResult Login()
    {
        return View("/Views/Hmss/Login.cshtml");
    }

    /// <summary>
    /// HMSS 主页面 - 系统入口（需要认证）
    /// </summary>
    [HttpGet("/hmss/master")]
    [Authorize]
    public IActionResult Master()
    {
        return View("/Views/Hmss/Master.cshtml");
    }

    /// <summary>
    /// SDH 车检替代系统主页面
    /// </summary>
    [HttpGet("/hmss/sdh")]
    [Authorize]
    public IActionResult Sdh()
    {
        ViewData["SystemCode"] = "SDH";
        return View("/Views/HmssPage/Subsystem.cshtml");
    }

    /// <summary>
    /// SDH 车检替代判定画面
    /// </summary>
    [HttpGet("/hmss/sdh/hantei")]
    [Authorize]
    public IActionResult SdhHantei()
    {
        ViewData["SystemCode"] = "SDH";
        ViewData["SubsystemPage"] = "hantei";
        return View("/Views/HmssPage/Subsystem.cshtml");
    }

    /// <summary>
    /// SDH 活动状况管理画面
    /// </summary>
    [HttpGet("/hmss/sdh/katsudo")]
    [Authorize]
    public IActionResult SdhKatsudo()
    {
        ViewData["SystemCode"] = "SDH";
        ViewData["SubsystemPage"] = "katsudo";
        return View("/Views/HmssPage/Subsystem.cshtml");
    }

    /// <summary>
    /// HMSS 表列表导航页面
    /// </summary>
    [HttpGet("/hmss/tables")]
    [Authorize]
    public IActionResult TableList()
    {
        return View("/Views/Hmss/TableList.cshtml");
    }

    /// <summary>
    /// HMSS 子系统页面（通用处理）
    /// 支持 /hmss/{system} 路由，如 /hmss/jksys, /hmss/r4 等
    /// </summary>
    [HttpGet("/hmss/{system:regex(hdkaikei|hmaud|hmdps|hmhrms|hmtve|jksys|r4|appm|pprm|ckchkzaiko)}")]
    [Authorize]
    public IActionResult Subsystem(string system)
    {
        // 定义子系统到视图名称的映射
        var systemViews = new Dictionary<string, string>(StringComparer.OrdinalIgnoreCase)
        {
            { "hdkaikei", "Subsystem" },
            { "hmaud", "Subsystem" },
            { "hmdps", "Subsystem" },
            { "hmhrms", "Subsystem" },
            { "hmtve", "Subsystem" },
            { "jksys", "Subsystem" },
            { "r4", "Subsystem" },
            { "appm", "Subsystem" },
            { "pprm", "Subsystem" },
            { "ckchkzaiko", "Subsystem" }
        };

        // 如果有专用视图则返回，否则返回通用子系统视图
        if (systemViews.TryGetValue(system, out var viewName))
        {
            ViewData["SystemCode"] = system.ToUpper();
            // 使用绝对路径返回 HmssPage 目录中的视图
            return View($"/Views/HmssPage/{viewName}.cshtml");
        }

        // 默认重定向到表列表
        return RedirectToAction("TableList");
    }

    /// <summary>
    /// HMSS 旧路由兼容性重定向
    /// 将 /hmss/{model} 重定向到 /hmss/models/{model}
    /// </summary>
    [HttpGet("/hmss/{model}")]
    public IActionResult RedirectLegacy(string model)
    {
        return Redirect($"/hmss/models/{model}?project=hmss");
    }

    /// <summary>
    /// HMSS 通用 UI 列表页面（支持 /hmss/models/{model} 路由）
    /// 使用框架的 DynamicRepository 获取数据
    /// </summary>
    [HttpGet("/hmss/models/{model}")]
    public async Task<IActionResult> UiList(
        string model,
        int page = 1,
        int size = 20,
        string? sortBy = null,
        string? sortDir = "asc")
    {
        if (page < 1) page = 1;
        if (size <= 0) size = 20;
        if (size > 200) size = 200;

        var def = GetModel(model);
        var project = Request.Query["project"].ToString() ?? "hmss";

        var filters = Request.Query
            .ToDictionary(k => k.Key, v => v.Value.ToString() ?? "");

        var result = await _repo.GetPagedAsync(def, page, size, filters, sortBy, sortDir);
        var rows = result.Rows;
        var total = result.Total;

        ViewData["ModelDef"] = def;
        ViewData["ModelName"] = model;
        ViewData["Page"] = page;
        ViewData["Size"] = size;
        ViewData["Total"] = total;
        ViewData["SortBy"] = sortBy;
        ViewData["SortDir"] = sortDir;
        ViewData["Project"] = project;

        // 检测是否为 htmx 请求
        if (Request.Headers["HX-Request"] == "true")
        {
            return View("/Views/HmssPage/_ListContent.cshtml", rows);
        }

        return View("/Views/HmssPage/List.cshtml", rows);
    }

    /// <summary>
    /// HMSS 创建页面
    /// </summary>
    [HttpGet("/hmss/models/{model}/create")]
    public IActionResult Create(string model)
    {
        var def = GetModel(model);
        if (def.IsReadOnly)
            return BadRequest("This model is read-only.");

        ViewData["ModelName"] = model;
        ViewData["ModelDef"] = def;
        ViewData["Project"] = Request.Query["project"].FirstOrDefault() ?? "hmss";
        return View("/Views/Ui/CreatePage.cshtml");
    }

    /// <summary>
    /// HMSS 编辑页面
    /// </summary>
    [HttpGet("/hmss/models/{model}/edit/{id}")]
    public async Task<IActionResult> Edit(string model, string id)
    {
        var def = GetModel(model);
        if (def.IsReadOnly)
            return BadRequest("This model is read-only.");

        var row = await _repo.GetByIdAsync(def, id);
        if (row == null)
            return NotFound();

        ViewData["ModelName"] = model;
        ViewData["ModelDef"] = def;
        ViewData["Project"] = Request.Query["project"].FirstOrDefault() ?? "hmss";
        return View("/Views/Ui/EditPage.cshtml", row);
    }

    /// <summary>
    /// HMSS 详情页面
    /// </summary>
    [HttpGet("/hmss/models/{model}/details/{id}")]
    public async Task<IActionResult> Details(string model, string id)
    {
        var def = GetModel(model);
        var row = await _repo.GetByIdAsync(def, id);
        if (row == null)
            return NotFound();

        ViewData["ModelName"] = model;
        ViewData["ModelDef"] = def;
        ViewData["Project"] = Request.Query["project"].FirstOrDefault() ?? "hmss";
        return View("/Views/Ui/DetailsPage.cshtml", row);
    }

    /// <summary>
    /// HMSS 创建数据（POST）
    /// </summary>
    [HttpPost("/hmss/models/{model}/create")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> CreatePost(
        string model,
        [FromForm] Dictionary<string, string> data)
    {
        try
        {
            var def = GetModel(model);
            if (def.IsReadOnly)
                return BadRequest("This model is read-only.");

            var objData = ModelBinder.Bind(def, data);
            await _repo.InsertAsync(def, objData);

            var project = Request.Query["project"].FirstOrDefault() ?? "hmss";
            return RedirectToAction("UiList", new { model, project });
        }
        catch (Exception ex)
        {
            var def = GetModel(model);
            ViewData["ModelName"] = model;
            ViewData["ModelDef"] = def;
            ViewData["Project"] = Request.Query["project"].FirstOrDefault() ?? "hmss";
            ViewData["CreateError"] = ex.Message;
            return View("/Views/Ui/CreatePage.cshtml");
        }
    }

    /// <summary>
    /// HMSS 更新数据（POST）
    /// </summary>
    [HttpPost("/hmss/models/{model}/edit/{id}")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> EditPost(
        string model,
        string id,
        [FromForm] Dictionary<string, string> data)
    {
        try
        {
            var def = GetModel(model);
            if (def.IsReadOnly)
                return BadRequest("This model is read-only.");

            var objData = ModelBinder.Bind(def, data);
            await _repo.UpdateAsync(def, id, objData);

            var project = Request.Query["project"].FirstOrDefault() ?? "hmss";
            return RedirectToAction("UiList", new { model, project });
        }
        catch (Exception ex)
        {
            var def = GetModel(model);
            var row = await _repo.GetByIdAsync(def, id);
            if (row == null)
                return NotFound();

            ViewData["ModelName"] = model;
            ViewData["ModelDef"] = def;
            ViewData["Project"] = Request.Query["project"].FirstOrDefault() ?? "hmss";
            ViewData["EditError"] = ex.Message;
            return View("/Views/Ui/EditPage.cshtml", row);
        }
    }

    /// <summary>
    /// HMSS 删除数据
    /// </summary>
    [HttpPost("/hmss/models/{model}/delete/{id}")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Delete(string model, string id)
    {
        try
        {
            var def = GetModel(model);
            if (def.IsReadOnly)
                return BadRequest("This model is read-only.");

            await _repo.DeleteAsync(def, id);

            return Content(string.Empty);
        }
        catch (Exception ex)
        {
            var errorResponse = new { error = ex.Message };
            return BadRequest(errorResponse);
        }
    }

    /// <summary>
    /// 错误页面
    /// </summary>
    [HttpGet("/hmss/error")]
    public IActionResult Error(int? code)
    {
        ViewBag.StatusCode = code ?? 500;
        return View();
    }

    private ModelDefinition GetModel(string model)
    {
        var project = _projectScope.CurrentProject;
        if (project == null)
            throw new Exception("No project selected");

        var appDefs = project.AppDefinitions;
        if (appDefs == null)
            throw new Exception($"Project '{project.Name}' has no AppDefinitions");

        // 首先尝试通过模型名查找
        var actualKey = appDefs.Models.Keys.FirstOrDefault(k =>
            k.Equals(model, StringComparison.OrdinalIgnoreCase));

        // 如果找不到，尝试通过表名查找
        if (actualKey == null)
        {
            actualKey = appDefs.Models.Keys.FirstOrDefault(k =>
                appDefs.Models[k].Table.Equals(model, StringComparison.OrdinalIgnoreCase));
        }

        if (actualKey == null)
            throw new Exception($"Model '{model}' not defined");

        return appDefs.Models[actualKey];
    }
}
