using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Repositories;
using Platform.Infrastructure.Services;

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
    /// 渲染多表 CRUD 页面
    /// </summary>
    [HttpGet("{pageName}")]
    public async Task<IActionResult> Index(string pageName)
    {
        var page = GetPage(pageName);

        // 如果是多表 CRUD 页面，使用专用视图
        if (page.MultiTableCrud != null)
        {
            ViewData["PageDef"] = page;
            ViewData["PageName"] = pageName;
            ViewData["Lang"] = Request.Query["lang"].FirstOrDefault() ?? "en";
            return View("~/Views/Ui/MultiTableForm.cshtml");
        }

        // 否则使用普通页面视图
        var filters = Request.Query
            .ToDictionary(k => k.Key, v => v.Value.ToString());

        var mainTableId = filters.TryGetValue("mainTableId", out var mtid) ? mtid : null;
        var pageData = await _repo.GetPageDataAsync(page, filters, mainTableId);

        ViewData["PageDef"] = page;
        ViewData["PageName"] = pageName;
        ViewData["Lang"] = Request.Query["lang"].FirstOrDefault() ?? "en";

        if (Request.Headers["HX-Request"] == "true")
        {
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
    /// 保存多表表单数据
    /// </summary>
    [HttpPost("{pageName}/multi-table/save")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> SaveMultiTable(string pageName, [FromForm] Dictionary<string, string> data, string? id = null)
    {
        var page = GetPage(pageName);
        
        if (page.MultiTableCrud == null)
            return BadRequest("This page does not support multi-table CRUD.");

        try
        {
            // 将 string 字典转换为 object 字典
            var objData = data.ToDictionary(kvp => kvp.Key, kvp => (object)kvp.Value);
            
            // 执行 before_save 步骤
            var stepContext = new StepContext
            {
                ActionType = string.IsNullOrWhiteSpace(id) ? "create" : "update",
                MainData = objData,
                UserId = User.Identity?.Name
            };
            
            var beforeSteps = page.MultiTableCrud.Steps
                .Where(s => s.Trigger == "before_save" || s.Trigger == "on_validate")
                .OrderBy(s => s.Id)
                .ToList();
                
            foreach (var step in beforeSteps)
            {
                var executor = new DefaultStepExecutor();
                var result = await executor.ExecuteAsync(step, stepContext);
                
                if (!result.Success && step.StopOnError)
                {
                    return Json(new { success = false, message = result.Message });
                }
            }
            
            int mainId;
            
            if (string.IsNullOrWhiteSpace(id))
            {
                // 插入
                mainId = await _repo.MultiTableInsertAsync(page.MultiTableCrud, objData);
            }
            else
            {
                // 更新
                if (int.TryParse(id, out var mainIdInt))
                {
                    await _repo.MultiTableUpdateAsync(page.MultiTableCrud, mainIdInt, objData);
                    mainId = mainIdInt;
                }
                else
                {
                    return BadRequest("Invalid ID format");
                }
            }
            
            // 更新上下文
            stepContext.MainId = mainId;
            
            // 执行 after_save 步骤
            var afterSteps = page.MultiTableCrud.Steps
                .Where(s => s.Trigger == "after_save")
                .OrderBy(s => s.Id)
                .ToList();
                
            foreach (var step in afterSteps)
            {
                var executor = new DefaultStepExecutor();
                _ = executor.ExecuteAsync(step, stepContext); // 不等待完成
            }
            
            return Json(new { success = true, id = mainId, message = "保存成功" });
        }
        catch (Exception ex)
        {
            return Json(new { success = false, message = ex.Message });
        }
    }

    /// <summary>
    /// 获取多表数据
    /// </summary>
    [HttpGet("{pageName}/multi-table/{id}")]
    public async Task<IActionResult> GetMultiTable(string pageName, int id)
    {
        var page = GetPage(pageName);
        
        if (page.MultiTableCrud == null)
            return BadRequest("This page does not support multi-table CRUD.");

        try
        {
            var tableData = await _repo.MultiTableSelectAsync(page.MultiTableCrud, id);
            return Json(new { success = true, data = tableData });
        }
        catch (Exception ex)
        {
            return Json(new { success = false, message = ex.Message });
        }
    }

    /// <summary>
    /// 获取多表列表数据
    /// </summary>
    [HttpGet("{pageName}/multi-table-data")]
    public async Task<IActionResult> GetMultiTableData(string pageName)
    {
        var page = GetPage(pageName);
        
        if (page.MultiTableCrud == null)
            return BadRequest("This page does not support multi-table CRUD.");

        try
        {
            // 这里简化处理，只返回主表数据
            var mainTable = page.MultiTableCrud.MainTable;
            if (mainTable == null)
                return Json(new { success = true, data = new List<object>() });

            var sql = $"SELECT * FROM {mainTable.Table} ORDER BY {mainTable.PrimaryKey} DESC LIMIT 100";
            var rows = await _repo.GetAllAsync(new ModelDefinition 
            { 
                Table = mainTable.Table, 
                PrimaryKey = mainTable.PrimaryKey ?? "Id",
                Properties = new Dictionary<string, PropertyDefinition>()
            });

            return Json(new { success = true, data = rows });
        }
        catch (Exception ex)
        {
            return Json(new { success = false, message = ex.Message });
        }
    }

    /// <summary>
    /// 删除多表数据
    /// </summary>
    [HttpPost("{pageName}/multi-table/delete")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> DeleteMultiTable(string pageName, [FromForm] string id)
    {
        var page = GetPage(pageName);
        
        if (page.MultiTableCrud == null)
            return BadRequest("This page does not support multi-table CRUD.");

        if (int.TryParse(id, out var mainId))
        {
            try
            {
                await _repo.MultiTableDeleteAsync(page.MultiTableCrud, mainId);
                return Json(new { success = true, message = "删除成功" });
            }
            catch (Exception ex)
            {
                return Json(new { success = false, message = ex.Message });
            }
        }
        
        return BadRequest("Invalid ID format");
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
