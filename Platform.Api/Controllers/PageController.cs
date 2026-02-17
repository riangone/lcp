using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Repositories;

namespace Platform.Api.Controllers;

[Route("page")]
public class PageController : Controller
{
    private readonly DynamicRepository _repo;
    private readonly AppDefinitions _defs;

    public PageController(DynamicRepository repo, AppDefinitions defs)
    {
        _repo = repo;
        _defs = defs;
    }

    /// <summary>
    /// 渲染页面
    /// </summary>
    [HttpGet("{pageName}")]
    public async Task<IActionResult> Index(string pageName)
    {
        var page = GetPage(pageName);
        
        // 获取过滤器参数
        var filters = Request.Query
            .ToDictionary(k => k.Key, v => v.Value.ToString());

        // 获取主表 ID（如果有）
        var mainTableId = filters.TryGetValue("mainTableId", out var mtid) ? mtid : null;

        // 获取所有区域的数据
        var pageData = await _repo.GetPageDataAsync(page, filters, mainTableId);

        ViewData["PageDef"] = page;
        ViewData["PageName"] = pageName;
        ViewData["Lang"] = Request.Query["lang"].FirstOrDefault() ?? "en";

        // 检测是否为 htmx 请求
        if (Request.Headers["HX-Request"] == "true")
        {
            // 返回部分视图
            return PartialView("~/Views/Ui/PageView.cshtml", pageData);
        }

        return View("~/Views/Ui/PageView.cshtml", pageData);
    }

    /// <summary>
    /// 刷新单个区域
    /// </summary>
    [HttpGet("{pageName}/section/{sectionId}")]
    public async Task<IActionResult> GetSection(string pageName, string sectionId)
    {
        var page = GetPage(pageName);
        var section = page.Sections.FirstOrDefault(s => s.Id == sectionId);
        
        if (section == null)
            return NotFound($"Section '{sectionId}' not found.");

        // 获取过滤器参数
        var filters = Request.Query
            .ToDictionary(k => k.Key, v => v.Value.ToString());

        // 获取主表 ID（如果有）
        var mainTableId = filters.TryGetValue("mainTableId", out var mtid) ? mtid : null;

        // 获取该区域的数据
        var (rows, total) = await _repo.GetSectionDataAsync(section, 1, section.PageSize, filters);

        var pageData = new Dictionary<string, (IEnumerable<Dictionary<string, object>>, int)>
        {
            [sectionId] = (rows, total)
        };

        ViewData["PageDef"] = page;
        ViewData["PageName"] = pageName;
        ViewData["Lang"] = Request.Query["lang"].FirstOrDefault() ?? "en";

        return PartialView("~/Views/Ui/PageView.cshtml", pageData);
    }

    /// <summary>
    /// 保存表单区域
    /// </summary>
    [HttpPost("{pageName}/section/{sectionId}/save")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> SaveSection(string pageName, string sectionId, [FromForm] Dictionary<string, string> data)
    {
        var page = GetPage(pageName);
        var section = page.Sections.FirstOrDefault(s => s.Id == sectionId);
        
        if (section == null)
            return NotFound($"Section '{sectionId}' not found.");

        if (section.ReadOnly)
            return BadRequest("Section is read-only.");

        try
        {
            // 绑定数据
            var objData = ModelBinder.Bind(section, data);

            // 插入数据
            await _repo.ExecutePageActionAsync(page, $"save_{sectionId}", objData);

            // 刷新区域
            return await GetSection(pageName, sectionId);
        }
        catch (Exception ex)
        {
            ViewData["Error"] = ex.Message;
            return await GetSection(pageName, sectionId);
        }
    }

    /// <summary>
    /// 删除区域记录
    /// </summary>
    [HttpPost("{pageName}/section/{sectionId}/delete")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> DeleteSectionRow(string pageName, string sectionId, [FromForm] string id)
    {
        var page = GetPage(pageName);
        var section = page.Sections.FirstOrDefault(s => s.Id == sectionId);
        
        if (section == null)
            return NotFound($"Section '{sectionId}' not found.");

        if (section.ReadOnly)
            return BadRequest("Section is read-only.");

        try
        {
            var data = new Dictionary<string, object> { ["id"] = id };
            await _repo.ExecutePageActionAsync(page, $"delete_{sectionId}", data);

            return Ok();
        }
        catch (Exception ex)
        {
            return BadRequest(ex.Message);
        }
    }

    /// <summary>
    /// 执行页面操作
    /// </summary>
    [HttpPost("{pageName}/action/{actionId}")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> ExecuteAction(string pageName, string actionId, [FromForm] Dictionary<string, string> data)
    {
        var page = GetPage(pageName);
        var action = page.Actions.FirstOrDefault(a => a.Id == actionId);
        
        if (action == null)
            return NotFound($"Action '{actionId}' not found.");

        try
        {
            // 收集选中的 ID
            var selectedIds = new Dictionary<string, object>();
            foreach (var section in page.Sections.Where(s => s.Editable && !s.ReadOnly))
            {
                var key = $"{section.Id}_selected";
                if (data.TryGetValue(key, out var value))
                {
                    selectedIds[section.Id] = value;
                }
            }

            await _repo.ExecutePageActionAsync(page, actionId, selectedIds);

            // 返回成功消息
            return Json(new { success = true, message = "操作成功" });
        }
        catch (Exception ex)
        {
            return Json(new { success = false, message = ex.Message });
        }
    }

    private PageDefinition GetPage(string pageName)
    {
        if (!_defs.AllowedPages.Contains(pageName))
            throw new Exception($"Page '{pageName}' not defined");

        // Find the actual key (case-insensitive)
        var actualKey = _defs.Pages.Keys.First(k =>
            k.Equals(pageName, StringComparison.OrdinalIgnoreCase));

        return _defs.Pages[actualKey];
    }
}
