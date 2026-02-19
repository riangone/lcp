using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Repositories;
using Platform.Api.Services;

namespace Platform.Api.Controllers;

[Route("ui/{model}")]
public class UiController : Controller
{
    private readonly DynamicRepository _repo;
    private readonly ProjectScope _projectScope;

    public UiController(DynamicRepository repo, ProjectScope projectScope)
    {
        _repo = repo;
        _projectScope = projectScope;
    }

    [HttpGet("")]
    public async Task<IActionResult> Index(
        string model,
        int page = 1,
        int size = 10,
        bool clear = false,
        string lang = "en",
        string? sortBy = null,
        string? sortDir = "asc",
        string editMode = "modal")
    {
        if (page < 1)
            page = 1;
        if (size <= 0)
            size = 10;
        if (size > 200)
            size = 200;

        var def = GetModel(model);
        var project = Request.Query["project"].FirstOrDefault() ?? "app";

        var filters = Request.Query
            .ToDictionary(k => k.Key, v => v.Value.ToString());

        // 如果请求清除过滤器，则重定向到没有过滤参数的 URL
        if (clear)
        {
            return RedirectToAction("Index", new { model = model, project = project, page = 1, size = size, lang = lang, sortBy = sortBy, sortDir = sortDir, editMode = editMode });
        }

        var (rows, total) = await _repo.GetPagedAsync(def, page, size, filters, sortBy, sortDir);

        ViewData["ModelDef"] = def;
        ViewData["ModelName"] = model;
        ViewData["Page"] = page;
        ViewData["Size"] = size;
        ViewData["Total"] = total;
        ViewData["Lang"] = lang;
        ViewData["SortBy"] = sortBy;
        ViewData["SortDir"] = sortDir;
        ViewData["EditMode"] = string.Equals(editMode, "page", StringComparison.OrdinalIgnoreCase) ? "page" : "modal";
        ViewData["Project"] = project;

        // 检测是否为 htmx 请求，如果是则返回部分视图内容
        if (Request.Headers["HX-Request"] == "true")
        {
            return PartialView("_ListContent", rows);
        }

        // 检查是否有专用 UI 视图定义
        var hasCustomView = def.CustomView != null && def.CustomView.Enabled && !string.IsNullOrEmpty(def.CustomView.ListTemplate);
        
        // 获取 URL 参数中的 ui 模式，如果没有则使用 YAML 配置的默认值
        var uiMode = Request.Query["ui"].FirstOrDefault();
        if (string.IsNullOrEmpty(uiMode))
        {
            // 使用 YAML 配置的默认 UI 模式
            uiMode = hasCustomView ? def.CustomView?.DefaultUiMode : "generic";
        }

        // 根据 UI 模式决定使用哪个视图
        if (uiMode == "generic" || !hasCustomView)
        {
            // 使用通用 UI
            return View("List", rows);
        }
        else
        {
            // 使用专用 UI - 使用简单的视图名称 (task_List, artist_List 等)
            var templatePath = def.CustomView?.ListTemplate ?? "";
            if (!string.IsNullOrEmpty(templatePath))
            {
                // views/task/List.cshtml -> task_List
                var viewName = templatePath.Replace("views/", "").Replace("/", "_").Replace(".cshtml", "");
                return View(viewName, rows);
            }
            // 回退到通用视图
            return View("List", rows);
        }
    }

    [HttpGet("create")]
    public IActionResult Create(string model, string editMode = "modal", string? returnUrl = null)
    {
        var def = GetModel(model);
        if (def.IsReadOnly)
            return BadRequest("This model is read-only.");

        var project = Request.Query["project"].FirstOrDefault() ?? "app";
        Prepare(model);
        ViewData["ReturnUrl"] = returnUrl;
        ViewData["Project"] = project;

        // 检查是否有专用表单视图
        var hasCustomView = def.CustomView != null && def.CustomView.Enabled && !string.IsNullOrEmpty(def.CustomView.FormTemplate);
        
        // 获取 UI 模式
        var uiMode = Request.Query["ui"].FirstOrDefault();
        if (string.IsNullOrEmpty(uiMode))
        {
            uiMode = hasCustomView ? def.CustomView?.DefaultUiMode : "generic";
        }

        // 根据 UI 模式决定使用哪个视图
        if (uiMode == "generic" || !hasCustomView)
        {
            if (string.Equals(editMode, "page", StringComparison.OrdinalIgnoreCase) &&
                Request.Headers["HX-Request"] != "true")
            {
                return View("CreatePage");
            }
            return PartialView("FormModal");
        }
        else
        {
            // 使用专用表单视图 - 使用符号链接方式
            var templatePath = def.CustomView?.FormTemplate ?? "";
            if (!string.IsNullOrEmpty(templatePath))
            {
                // views/task/Form.cshtml -> task_Form
                var viewName = templatePath.Replace("views/", "").Replace("/", "_").Replace(".cshtml", "");
                return View(viewName);
            }
            // 回退到通用视图
            if (string.Equals(editMode, "page", StringComparison.OrdinalIgnoreCase) &&
                Request.Headers["HX-Request"] != "true")
            {
                return View("CreatePage");
            }
            return PartialView("FormModal");
        }
    }

    [HttpGet("edit/{id}")]
    public async Task<IActionResult> Edit(string model, string id, string editMode = "modal", string? returnUrl = null)
    {
        var def = GetModel(model);
        if (def.IsReadOnly)
            return BadRequest("This model is read-only.");

        var row = await _repo.GetByIdAsync(def, id);

        if (row == null)
            return NotFound();

        var project = Request.Query["project"].FirstOrDefault() ?? "app";
        Prepare(model);
        ViewData["Row"] = row;
        ViewData["ReturnUrl"] = returnUrl;
        ViewData["Project"] = project;

        // 检查是否有专用表单视图
        if (def.CustomView != null && def.CustomView.Enabled && !string.IsNullOrEmpty(def.CustomView.FormTemplate))
        {
            // Project dir from Razor options
            return View("Journal/Form");
            // Check view exists
            {
                return View("Journal/Form");
            }
        }

        if (string.Equals(editMode, "page", StringComparison.OrdinalIgnoreCase) &&
            Request.Headers["HX-Request"] != "true")
        {
            return View("EditPage", row);
        }

        return PartialView("FormModal");
    }

    [HttpGet("details/{id}")]
    public async Task<IActionResult> Details(string model, string id, string? returnUrl = null)
    {
        var def = GetModel(model);
        var row = await _repo.GetByIdAsync(def, id);

        if (row == null)
            return NotFound();

        var project = Request.Query["project"].FirstOrDefault() ?? "app";
        Prepare(model);
        ViewData["Row"] = row;
        ViewData["ReturnUrl"] = returnUrl;
        ViewData["Project"] = project;

        // 检查是否有专用详情视图
        if (def.CustomView != null && def.CustomView.Enabled && !string.IsNullOrEmpty(def.CustomView.DetailsTemplate))
        {
            // Project dir from Razor options
            var viewName = def.CustomView.DetailsTemplate.Replace("/", "_").Replace(".cshtml", "");
            // Check view exists
            {
                return View("Journal/Form");
            }
        }

        return View("DetailsPage", row);
    }

    [HttpPost("edit-page/{id}")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> EditPage(
        string model,
        string id,
        [FromForm] Dictionary<string, string> data,
        string? returnUrl = null)
    {
        try
        {
            var def = GetModel(model);
            if (def.IsReadOnly)
                return BadRequest("This model is read-only.");

            var objData = ModelBinder.Bind(def, data);

            await _repo.UpdateAsync(def, id, objData);

            if (!string.IsNullOrWhiteSpace(returnUrl) && Url.IsLocalUrl(returnUrl))
                return Redirect(returnUrl);

            var project = Request.Query["project"].FirstOrDefault() ?? "app";
            return RedirectToAction("Index", new { model = model, project = project });
        }
        catch (Exception ex)
        {
            var def = GetModel(model);
            var row = await _repo.GetByIdAsync(def, id);
            if (row == null)
                return NotFound();

            Prepare(model);
            ViewData["Row"] = row;
            ViewData["ReturnUrl"] = returnUrl;
            ViewData["EditError"] = ex.Message;
            return View("EditPage", row);
        }
    }

    [HttpPost("create-page")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> CreatePage(
        string model,
        [FromForm] Dictionary<string, string> data,
        string? returnUrl = null)
    {
        try
        {
            var def = GetModel(model);
            if (def.IsReadOnly)
                return BadRequest("This model is read-only.");

            var objData = ModelBinder.Bind(def, data);

            await _repo.InsertAsync(def, objData);

            if (!string.IsNullOrWhiteSpace(returnUrl) && Url.IsLocalUrl(returnUrl))
                return Redirect(returnUrl);

            var project = Request.Query["project"].FirstOrDefault() ?? "app";
            return RedirectToAction("Index", new { model = model, project = project });
        }
        catch (Exception ex)
        {
            Prepare(model);
            ViewData["ReturnUrl"] = returnUrl;
            ViewData["CreateError"] = ex.Message;
            return View("CreatePage");
        }
    }

    private void Prepare(string model)
    {
        ViewData["ModelDef"] = GetModel(model);
        ViewData["ModelName"] = model;
    }

    private ModelDefinition GetModel(string model)
    {
        var appDefs = _projectScope.CurrentProject?.AppDefinitions;
        if (appDefs == null)
            throw new Exception("No project selected");

        if (!appDefs.AllowedModels.Contains(model))
            throw new Exception($"Model '{model}' not defined");

        // Find the actual key (case-insensitive)
        var actualKey = appDefs.Models.Keys.First(k =>
            k.Equals(model, StringComparison.OrdinalIgnoreCase));

        return appDefs.Models[actualKey];
    }
}
