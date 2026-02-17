using System.Text;
using System.Text.Json;
using Platform.Infrastructure.Definitions;

namespace Platform.Infrastructure.Services;

/// <summary>
/// 步骤执行器 - 执行 YAML 定义的处理步骤
/// </summary>
public interface IStepExecutor
{
    Task<StepResult> ExecuteAsync(StepDefinition step, StepContext context);
}

/// <summary>
/// 步骤上下文
/// </summary>
public class StepContext
{
    /// <summary>
    /// 操作类型：create, update, delete
    /// </summary>
    public string ActionType { get; set; } = "";

    /// <summary>
    /// 主表数据
    /// </summary>
    public Dictionary<string, object> MainData { get; set; } = new();

    /// <summary>
    /// 关联表数据
    /// </summary>
    public Dictionary<string, List<Dictionary<string, object>>> RelatedData { get; set; } = new();

    /// <summary>
    /// 主键值
    /// </summary>
    public int? MainId { get; set; }

    /// <summary>
    /// 用户信息
    /// </summary>
    public string? UserId { get; set; }

    /// <summary>
    /// 环境变量（如 API_TOKEN 等）
    /// </summary>
    public Dictionary<string, string> Environment { get; set; } = new();
}

/// <summary>
/// 步骤执行结果
/// </summary>
public class StepResult
{
    /// <summary>
    /// 是否成功
    /// </summary>
    public bool Success { get; set; }

    /// <summary>
    /// 消息
    /// </summary>
    public string? Message { get; set; }

    /// <summary>
    /// 返回数据
    /// </summary>
    public object? Data { get; set; }

    public static StepResult Ok(string? message = null, object? data = null) =>
        new StepResult { Success = true, Message = message, Data = data };

    public static StepResult Fail(string message) =>
        new StepResult { Success = false, Message = message };
}

/// <summary>
/// 默认步骤执行器
/// </summary>
public class DefaultStepExecutor : IStepExecutor
{
    public DefaultStepExecutor()
    {
    }

    public async Task<StepResult> ExecuteAsync(StepDefinition step, StepContext context)
    {
        try
        {
            return step.Type switch
            {
                "script" => await ExecuteScriptAsync(step, context),
                "api" => await ExecuteApiAsync(step, context),
                "notification" => await ExecuteNotificationAsync(step, context),
                "custom" => await ExecuteCustomAsync(step, context),
                _ => StepResult.Ok($"Unknown step type: {step.Type}")
            };
        }
        catch (Exception ex)
        {
            return StepResult.Fail($"Step '{step.Name}' failed: {ex.Message}");
        }
    }

    private Task<StepResult> ExecuteScriptAsync(StepDefinition step, StepContext context)
    {
        if (step.Script == null)
            return Task.FromResult(StepResult.Fail("Script config is null"));

        // 简单实现：目前只支持简单的 C# 表达式
        // 实际生产环境可以使用 Roslyn 或 JavaScriptEngineSwitcher
        var script = step.Script.Content;

        // 替换模板变量
        script = ReplaceTemplates(script, context);

        // 这里简化处理，实际应该使用脚本引擎执行
        // 目前只记录日志
        Console.WriteLine($"[StepExecutor] Executing script: {step.Name}");
        Console.WriteLine($"[StepExecutor] Script: {script}");

        return Task.FromResult(StepResult.Ok($"Script '{step.Name}' executed (simulation)"));
    }

    private async Task<StepResult> ExecuteApiAsync(StepDefinition step, StepContext context)
    {
        if (step.Api == null)
            return StepResult.Fail("API config is null");

        // 简化实现：记录日志
        var url = ReplaceTemplates(step.Api.Url, context);
        Console.WriteLine($"[StepExecutor] API call: {step.Api.Method} {url}");

        return StepResult.Ok($"API call simulated: {url}");
    }

    private Task<StepResult> ExecuteNotificationAsync(StepDefinition step, StepContext context)
    {
        if (step.Notification == null)
            return Task.FromResult(StepResult.Fail("Notification config is null"));

        var notification = step.Notification;

        // 这里简化处理，实际应该集成邮件服务、短信服务等
        Console.WriteLine($"[StepExecutor] Sending {notification.Type} notification:");
        Console.WriteLine($"[StepExecutor] To: {string.Join(", ", notification.Recipients)}");
        Console.WriteLine($"[StepExecutor] Subject: {ReplaceTemplates(notification.Subject ?? "", context)}");

        return Task.FromResult(StepResult.Ok($"Notification sent to {notification.Recipients.Count} recipients (simulation)"));
    }

    private Task<StepResult> ExecuteCustomAsync(StepDefinition step, StepContext context)
    {
        // 自定义步骤需要实现 ICustomStepHandler 接口
        return Task.FromResult(StepResult.Ok("Custom step executed (not implemented)"));
    }

    /// <summary>
    /// 替换模板变量
    /// </summary>
    private string ReplaceTemplates(string template, StepContext context)
    {
        if (string.IsNullOrEmpty(template))
            return template;

        var result = template;

        // 替换主表数据
        foreach (var kvp in context.MainData)
        {
            result = result.Replace($"{{{{{kvp.Key}}}}}", kvp.Value?.ToString() ?? "");
        }

        // 替换关联表数据（JSON 格式）
        foreach (var kvp in context.RelatedData)
        {
            var json = JsonSerializer.Serialize(kvp.Value);
            result = result.Replace($"{{{{{kvp.Key}}}}}", json);
        }

        // 替换主键
        if (context.MainId.HasValue)
        {
            result = result.Replace("{{MainId}}", context.MainId.Value.ToString());
        }

        // 替换环境变量
        foreach (var kvp in context.Environment)
        {
            result = result.Replace($"{{{{{kvp.Key}}}}}", kvp.Value);
        }

        return result;
    }
}
