using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Services;
using Platform.Infrastructure.Data;

namespace Platform.Api.Controllers;

[Route("api/multi-table")]
public class MultiTableController : Controller
{
    private readonly PageDataLoader _loader;
    private readonly MultiTableSaver _saver;
    private readonly AppDefinitions _defs;
    private readonly DbConnectionFactory _dbFactory;

    public MultiTableController(
        PageDataLoader loader,
        MultiTableSaver saver,
        AppDefinitions defs,
        DbConnectionFactory dbFactory)
    {
        _loader = loader;
        _saver = saver;
        _defs = defs;
        _dbFactory = dbFactory;
    }

    /// <summary>
    /// 加载页面数据
    /// </summary>
    [HttpGet("{pageName}/load")]
    public async Task<IActionResult> Load(string pageName)
    {
        try
        {
            var page = GetPage(pageName);

            if (page.DataLoading == null)
            {
                return Json(new { success = false, message = "No data loading configuration found" });
            }

            var parameters = Request.Query
                .ToDictionary(k => k.Key, v => (object)v.Value.ToString());

            Console.WriteLine($"[MultiTableController] Loading page: {pageName}");
            Console.WriteLine($"[MultiTableController] Parameters: {string.Join(", ", parameters.Select(kvp => $"{kvp.Key}={kvp.Value}"))}");
            Console.WriteLine($"[MultiTableController] DataLoading sources: {page.DataLoading.Sources?.Count ?? 0}");
            foreach (var source in page.DataLoading.Sources ?? new List<DataSourceConfig>())
            {
                Console.WriteLine($"[MultiTableController]   Source: {source.Id}, Table: {source.Table}, Where: {source.Where}");
            }

            // 加载所有配置的数据源
            var data = await _loader.LoadPageDataAsync(page.DataLoading, parameters);

            Console.WriteLine($"[MultiTableController] Loaded data keys: {string.Join(", ", data.Keys)}");

            return Json(new { success = true, data });
        }
        catch (Exception ex)
        {
            Console.WriteLine($"[MultiTableController] ERROR: {ex.Message}");
            Console.WriteLine($"[MultiTableController] StackTrace: {ex.StackTrace}");
            return Json(new { success = false, message = ex.Message });
        }
    }

    /// <summary>
    /// 保存多表数据
    /// </summary>
    [HttpPost("{pageName}/save")]
    public async Task<IActionResult> Save(string pageName)
    {
        try
        {
            var page = GetPage(pageName);
            
            if (page.SaveConfig == null)
            {
                return Json(new { success = false, message = "No save configuration found" });
            }

            // 读取表单数据
            var formData = Request.Form
                .SelectMany(kvp => kvp.Value, (kvp, val) => new { Key = kvp.Key, Value = val })
                .ToDictionary(x => x.Key, x => (object)x.Value);

            // 加载现有数据（用于判断 CRUD 类型）
            var loadedData = new Dictionary<string, object>();
            if (page.DataLoading != null)
            {
                var parameters = Request.Query
                    .ToDictionary(k => k.Key, v => (object)v.Value.ToString());
                loadedData = await _loader.LoadPageDataAsync(page.DataLoading, parameters);
            }

            // 执行保存
            var result = await _saver.SaveAsync(page.SaveConfig, formData, loadedData);

            if (result.Success)
            {
                // 执行保存后钩子
                if (page.SaveConfig.Hooks?.AfterSave != null)
                {
                    await ExecuteHooks(page.SaveConfig.Hooks.AfterSave, result);
                }

                return Json(new { success = true, ids = result.GeneratedIds });
            }
            else
            {
                return Json(new { success = false, message = result.ErrorMessage });
            }
        }
        catch (Exception ex)
        {
            return Json(new { success = false, message = ex.Message });
        }
    }

    /// <summary>
    /// 执行钩子
    /// </summary>
    private async Task ExecuteHooks(List<HookConfig> hooks, SaveResult result)
    {
        foreach (var hook in hooks)
        {
            try
            {
                if (hook.Type == "notification")
                {
                    // 发送通知（简化实现）
                    Console.WriteLine($"Notification: {hook.Template}");
                }
                else if (hook.Type == "redirect")
                {
                    // 重定向（由前端处理）
                    Console.WriteLine($"Redirect: {hook.Url}");
                }
                else if (hook.Type == "script")
                {
                    // 执行脚本（简化实现）
                    Console.WriteLine($"Script: {hook.Script}");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Hook execution failed: {ex.Message}");
                // 钩子失败不影响主流程
            }
        }

        await Task.CompletedTask;
    }

    /// <summary>
    /// 获取页面定义
    /// </summary>
    private PageDefinition GetPage(string pageName)
    {
        if (!_defs.AllowedPages.Contains(pageName))
            throw new Exception($"Page '{pageName}' not defined");

        var actualKey = _defs.Pages.Keys.First(k =>
            k.Equals(pageName, StringComparison.OrdinalIgnoreCase));

        return _defs.Pages[actualKey];
    }
}
