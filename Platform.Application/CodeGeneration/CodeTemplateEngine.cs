namespace Platform.Application.CodeGeneration;

/// <summary>
/// 代码模板引擎 - 用于从模板生成确定性代码
/// 使用模板确保每次生成的代码一致且可维护
/// </summary>
public class CodeTemplateEngine
{
    private readonly Dictionary<string, string> _templates = new();
    private readonly CodeTemplateOptions _options;

    public CodeTemplateEngine(CodeTemplateOptions? options = null)
    {
        _options = options ?? new CodeTemplateOptions();
        InitializeBuiltInTemplates();
    }

    /// <summary>
    /// 从模板生成代码
    /// </summary>
    public string Render(string templateName, TemplateContext context)
    {
        if (!_templates.TryGetValue(templateName, out var template))
        {
            throw new InvalidOperationException($"Template '{templateName}' not found");
        }

        return RenderTemplate(template, context);
    }

    /// <summary>
    /// 注册自定义模板
    /// </summary>
    public void RegisterTemplate(string name, string content)
    {
        _templates[name] = content;
    }

    private string RenderTemplate(string template, TemplateContext context)
    {
        var result = template;

        // 替换简单变量 {VariableName}
        result = result.Replace("{Namespace}", context.Namespace);
        result = result.Replace("{GeneratorVersion}", context.GeneratorVersion);
        result = result.Replace("{GeneratedAt}", context.GeneratedAt.ToString("yyyy-MM-dd HH:mm:ss"));
        result = result.Replace("{Model.Name}", context.Model.Name);
        result = result.Replace("{Model.TableName}", context.Model.TableName);
        result = result.Replace("{Model.PrimaryKey}", context.Model.PrimaryKey);

        // 处理验证规则
        if (result.Contains("{ValidationRules}"))
        {
            var rulesCode = new System.Text.StringBuilder();
            foreach (var rule in context.ValidationRules)
            {
                rulesCode.AppendLine($"            new BusinessRule<Dictionary<string, object>>(\n                entity => {rule.Condition},\n                \"{rule.ErrorMessage}\"\n            ),");
            }
            result = result.Replace("{ValidationRules}", rulesCode.ToString());
        }

        return result;
    }

    private void InitializeBuiltInTemplates()
    {
        // API 控制器模板
        RegisterTemplate("ApiController", @"using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Repositories;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace {Namespace}.Controllers;

/// <summary>
/// API 控制器 - {Model.Name}
/// 此文件由代码生成器自动生成 - 请勿手动修改
/// 生成器版本：{GeneratorVersion}
/// 生成时间：{GeneratedAt}
/// </summary>
[ApiController]
[Route(""api/[controller]"")]
public class {Model.Name}Controller : ControllerBase
{
    private readonly DynamicRepository _repo;

    public {Model.Name}Controller(DynamicRepository repo)
    {
        _repo = repo;
    }

    /// <summary>
    /// 获取所有{Model.Name}记录
    /// </summary>
    [HttpGet]
    public async Task<IActionResult> GetAll()
    {
        var rows = await _repo.GetAllAsync(""{Model.TableName}"");
        return Ok(rows);
    }

    /// <summary>
    /// 根据 ID 获取{Model.Name}记录
    /// </summary>
    [HttpGet(""{id}"")]
    public async Task<IActionResult> GetById(string id)
    {
        var row = await _repo.GetByIdAsync(""{Model.TableName}"", id);
        if (row == null)
            return NotFound();

        return Ok(row);
    }

    /// <summary>
    /// 创建新的{Model.Name}记录
    /// </summary>
    [HttpPost]
    public async Task<IActionResult> Create([FromForm] Dictionary<string, string> data)
    {
        try
        {
            await _repo.InsertAsync(""{Model.TableName}"", data);
            return CreatedAtAction(nameof(GetAll), new { });
        }
        catch (Exception ex)
        {
            return BadRequest(new { error = ex.Message });
        }
    }

    /// <summary>
    /// 更新{Model.Name}记录
    /// </summary>
    [HttpPut(""{id}"")]
    public async Task<IActionResult> Update(string id, [FromForm] Dictionary<string, string> data)
    {
        try
        {
            await _repo.UpdateAsync(""{Model.TableName}"", id, data);
            return NoContent();
        }
        catch (Exception ex)
        {
            return BadRequest(new { error = ex.Message });
        }
    }

    /// <summary>
    /// 删除{Model.Name}记录
    /// </summary>
    [HttpDelete(""{id}"")]
    public async Task<IActionResult> Delete(string id)
    {
        try
        {
            await _repo.DeleteAsync(""{Model.TableName}"", id);
            return NoContent();
        }
        catch (Exception ex)
        {
            return BadRequest(new { error = ex.Message });
        }
    }
}
");

        // 验证器模板
        RegisterTemplate("Validator", @"using Platform.Domain.Core;
using System;
using System.Collections.Generic;
using System.Linq;

namespace {Namespace}.Validators;

/// <summary>
/// {Model.Name}验证器
/// 此文件由代码生成器自动生成 - 请勿手动修改
/// 生成器版本：{GeneratorVersion}
/// </summary>
public static class {Model.Name}Validator
{
    /// <summary>
    /// 获取所有验证规则
    /// </summary>
    public static List<BusinessRule<Dictionary<string, object>>> GetRules()
    {
        return new List<BusinessRule<Dictionary<string, object>>>
        {
{ValidationRules}
        };
    }

    /// <summary>
    /// 验证实体
    /// </summary>
    public static ValidationResult Validate(Dictionary<string, object> entity)
    {
        return BusinessRuleValidator.ValidateEntity(entity, GetRules());
    }
}
");

        // 服务模板
        RegisterTemplate("Service", @"using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Repositories;
using System;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace {Namespace}.Services;

/// <summary>
/// {Model.Name}服务
/// 此文件由代码生成器自动生成 - 请勿手动修改
/// 生成器版本：{GeneratorVersion}
/// </summary>
public interface I{Model.Name}Service
{
    Task<List<Dictionary<string, object>>> GetAllAsync();
    Task<Dictionary<string, object>?> GetByIdAsync(string id);
    Task<string> CreateAsync(Dictionary<string, string> data);
    Task UpdateAsync(string id, Dictionary<string, string> data);
    Task DeleteAsync(string id);
}

/// <summary>
/// {Model.Name}服务实现
/// </summary>
public class {Model.Name}Service : I{Model.Name}Service
{
    private readonly DynamicRepository _repo;

    public {Model.Name}Service(DynamicRepository repo)
    {
        _repo = repo;
    }

    public async Task<List<Dictionary<string, object>>> GetAllAsync()
    {
        var def = await GetDefinitionAsync();
        return (await _repo.GetAllAsync(def)).ToList();
    }

    public async Task<Dictionary<string, object>?> GetByIdAsync(string id)
    {
        var def = await GetDefinitionAsync();
        return await _repo.GetByIdAsync(def, id);
    }

    public async Task<string> CreateAsync(Dictionary<string, string> data)
    {
        var def = await GetDefinitionAsync();
        await _repo.InsertAsync(def, data);
        return string.Empty;
    }

    public async Task UpdateAsync(string id, Dictionary<string, string> data)
    {
        var def = await GetDefinitionAsync();
        await _repo.UpdateAsync(def, id, data);
    }

    public async Task DeleteAsync(string id)
    {
        var def = await GetDefinitionAsync();
        await _repo.DeleteAsync(def, id);
    }

    private async Task<ModelDefinition> GetDefinitionAsync()
    {
        await Task.CompletedTask;
        return new ModelDefinition();
    }
}
");

        // 单元测试模板
        RegisterTemplate("UnitTest", @"using Xunit;
using {Namespace}.Services;
using {Namespace}.Validators;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace {Namespace}.Tests;

/// <summary>
/// {Model.Name}服务测试
/// 此文件由代码生成器自动生成 - 请勿手动修改
/// 生成器版本：{GeneratorVersion}
/// </summary>
public class {Model.Name}ServiceTests
{
    [Fact]
    public async Task GetAllAsync_ShouldReturnAllRecords()
    {
        // Arrange
        // TODO: 设置测试数据

        // Act
        // TODO: 调用服务方法

        // Assert
        // TODO: 验证结果
        await Task.CompletedTask;
        Assert.True(true);
    }

    [Fact]
    public void Validator_ShouldValidateRequiredFields()
    {
        // Arrange
        var entity = new Dictionary<string, object>();

        // Act
        var result = {Model.Name}Validator.Validate(entity);

        // Assert
        Assert.False(result.IsValid);
        Assert.NotEmpty(result.Errors);
    }
}
");
    }
}

/// <summary>
/// 代码模板选项
/// </summary>
public class CodeTemplateOptions
{
    /// <summary>
    /// 模板目录
    /// </summary>
    public string? TemplateDirectory { get; set; }

    /// <summary>
    /// 是否使用内置模板
    /// </summary>
    public bool UseBuiltInTemplates { get; set; } = true;

    /// <summary>
    /// 代码缩进（2 或 4）
    /// </summary>
    public int IndentSize { get; set; } = 4;

    /// <summary>
    /// 是否添加文件头注释
    /// </summary>
    public bool AddHeaderComments { get; set; } = true;

    /// <summary>
    /// 文件头注释模板
    /// </summary>
    public string? HeaderTemplate { get; set; }
}

/// <summary>
/// 模板上下文基类
/// </summary>
public class TemplateContext
{
    /// <summary>
    /// 命名空间
    /// </summary>
    public string Namespace { get; set; } = "";

    /// <summary>
    /// 生成器版本
    /// </summary>
    public string GeneratorVersion { get; set; } = "1.0.0";

    /// <summary>
    /// 生成时间
    /// </summary>
    public DateTime GeneratedAt { get; set; } = DateTime.UtcNow;

    /// <summary>
    /// 模型信息
    /// </summary>
    public ModelTemplateContext Model { get; set; } = new();

    /// <summary>
    /// 验证规则列表
    /// </summary>
    public List<ValidationRuleTemplateContext> ValidationRules { get; set; } = new();
}

/// <summary>
/// 模型模板上下文
/// </summary>
public class ModelTemplateContext
{
    /// <summary>
    /// 模型名称
    /// </summary>
    public string Name { get; set; } = "";

    /// <summary>
    /// 表名
    /// </summary>
    public string TableName { get; set; } = "";

    /// <summary>
    /// 主键字段
    /// </summary>
    public string PrimaryKey { get; set; } = "";

    /// <summary>
    /// 属性列表
    /// </summary>
    public List<PropertyTemplateContext> Properties { get; set; } = new();
}

/// <summary>
/// 属性模板上下文
/// </summary>
public class PropertyTemplateContext
{
    /// <summary>
    /// 属性名称
    /// </summary>
    public string Name { get; set; } = "";

    /// <summary>
    /// 属性类型
    /// </summary>
    public string Type { get; set; } = "";

    /// <summary>
    /// 是否必填
    /// </summary>
    public bool Required { get; set; }

    /// <summary>
    /// 最大长度
    /// </summary>
    public int? MaxLength { get; set; }
}

/// <summary>
/// 验证规则模板上下文
/// </summary>
public class ValidationRuleTemplateContext
{
    /// <summary>
    /// 验证条件（C# 表达式）
    /// </summary>
    public string Condition { get; set; } = "";

    /// <summary>
    /// 错误消息
    /// </summary>
    public string ErrorMessage { get; set; } = "";
}
