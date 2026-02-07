using Microsoft.AspNetCore.Mvc;
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
    public async Task<IActionResult> Index(string model, int page = 1, int size = 10, bool clear = false)
    {
        var def = GetModel(model);

        var filters = Request.Query
            .ToDictionary(k => k.Key, v => v.Value.ToString());

        // 如果请求清除过滤器，则重定向到没有过滤参数的URL
        if (clear)
        {
            return RedirectToAction("Index", new { model = model, page = 1, size = size });
        }

        var (rows, total) = await _repo.GetPagedAsync(def, page, size, filters);

        ViewData["ModelDef"] = def;
        ViewData["ModelName"] = model;
        ViewData["Page"] = page;
        ViewData["Size"] = size;
        ViewData["Total"] = total;

        return View("List", rows);
    }

    [HttpGet("create")]
    public IActionResult Create(string model)
    {
        Prepare(model);
        return PartialView("FormModal");
    }

    [HttpGet("edit/{id}")]
    public async Task<IActionResult> Edit(string model, string id)
    {
        var def = GetModel(model);
        var row = await _repo.GetByIdAsync(def, id);

        if (row == null)
            return NotFound();

        Prepare(model);
        ViewData["Row"] = row;

        return PartialView("FormModal");
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
