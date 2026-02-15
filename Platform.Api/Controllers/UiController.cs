using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Repositories;

namespace Platform.Api.Controllers;

[Route("ui/{model}")]
public class UiController : Controller
{
    private readonly DynamicRepository _repo;
    private readonly AppDefinitions _defs;

    public UiController(DynamicRepository repo, AppDefinitions defs)
    {
        _repo = repo;
        _defs = defs;
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

        var filters = Request.Query
            .ToDictionary(k => k.Key, v => v.Value.ToString());

        // 如果请求清除过滤器，则重定向到没有过滤参数的URL
        if (clear)
        {
            return RedirectToAction("Index", new { model = model, page = 1, size = size, lang = lang, sortBy = sortBy, sortDir = sortDir, editMode = editMode });
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

        // 检测是否为htmx请求，如果是则返回部分视图内容
        if (Request.Headers["HX-Request"] == "true")
        {
            return PartialView("_ListContent", rows);
        }

        return View("List", rows);
    }

    [HttpGet("create")]
    public IActionResult Create(string model, string editMode = "modal", string? returnUrl = null)
    {
        var def = GetModel(model);
        if (def.IsReadOnly)
            return BadRequest("This model is read-only.");

        Prepare(model);
        ViewData["ReturnUrl"] = returnUrl;

        if (string.Equals(editMode, "page", StringComparison.OrdinalIgnoreCase) &&
            Request.Headers["HX-Request"] != "true")
        {
            return View("CreatePage");
        }

        return PartialView("FormModal");
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

        Prepare(model);
        ViewData["Row"] = row;
        ViewData["ReturnUrl"] = returnUrl;

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

        Prepare(model);
        ViewData["Row"] = row;
        ViewData["ReturnUrl"] = returnUrl;
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

            return RedirectToAction("Index", new { model = model });
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

            return RedirectToAction("Index", new { model = model });
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
        if (!_defs.AllowedModels.Contains(model))
            throw new Exception($"Model '{model}' not defined");

        // Find the actual key (case-insensitive)
        var actualKey = _defs.Models.Keys.First(k => 
            k.Equals(model, StringComparison.OrdinalIgnoreCase));
        
        return _defs.Models[actualKey];
    }
}
