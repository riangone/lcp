using Platform.Infrastructure.Definitions;

namespace Platform.Application.CodeGeneration;

/// <summary>
/// 代码生成器接口 - 用于从 YAML 定义生成确定性、可维护的代码
/// </summary>
public interface ICodeGenerator
{
    /// <summary>
    /// 生成器唯一标识
    /// </summary>
    string GeneratorId { get; }

    /// <summary>
    /// 生成器版本（用于追踪变更）
    /// </summary>
    string Version { get; }

    /// <summary>
    /// 生成代码
    /// </summary>
    /// <param name="context">生成上下文</param>
    /// <returns>生成的代码文件列表</returns>
    Task<CodeGenerationResult> GenerateAsync(CodeGenerationContext context);

    /// <summary>
    /// 验证生成的代码是否有效
    /// </summary>
    Task<CodeValidationResult> ValidateAsync(CodeGenerationResult result);
}

/// <summary>
/// 代码生成上下文
/// </summary>
public class CodeGenerationContext
{
    /// <summary>
    /// 模型定义
    /// </summary>
    public required ModelDefinition Model { get; set; }

    /// <summary>
    /// 页面定义（可选）
    /// </summary>
    public PageDefinition? Page { get; set; }

    /// <summary>
    /// 输出目录
    /// </summary>
    public required string OutputDirectory { get; set; }

    /// <summary>
    /// 命名空间
    /// </summary>
    public required string Namespace { get; set; }

    /// <summary>
    /// 生成选项
    /// </summary>
    public CodeGenerationOptions Options { get; set; } = new();

    /// <summary>
    /// 现有代码（用于增量生成）
    /// </summary>
    public Dictionary<string, string> ExistingCode { get; set; } = new();
}

/// <summary>
/// 代码生成选项
/// </summary>
public class CodeGenerationOptions
{
    /// <summary>
    /// 是否生成 API 控制器
    /// </summary>
    public bool GenerateApiController { get; set; } = true;

    /// <summary>
    /// 是否生成 UI 控制器
    /// </summary>
    public bool GenerateUiController { get; set; } = true;

    /// <summary>
    /// 是否生成 Razor 视图
    /// </summary>
    public bool GenerateViews { get; set; } = true;

    /// <summary>
    /// 是否生成服务层
    /// </summary>
    public bool GenerateServices { get; set; } = false;

    /// <summary>
    /// 是否生成仓储层
    /// </summary>
    public bool GenerateRepositories { get; set; } = false;

    /// <summary>
    /// 是否生成验证器
    /// </summary>
    public bool GenerateValidators { get; set; } = true;

    /// <summary>
    /// 是否生成单元测试
    /// </summary>
    public bool GenerateTests { get; set; } = false;

    /// <summary>
    /// 是否覆盖现有文件
    /// </summary>
    public bool OverwriteExisting { get; set; } = false;

    /// <summary>
    /// 是否格式化代码
    /// </summary>
    public bool FormatCode { get; set; } = true;

    /// <summary>
    /// 是否添加文件头注释
    /// </summary>
    public bool AddHeaderComments { get; set; } = true;
}

/// <summary>
/// 代码生成结果
/// </summary>
public class CodeGenerationResult
{
    /// <summary>
    /// 生成的文件列表
    /// </summary>
    public List<GeneratedFile> Files { get; set; } = new();

    /// <summary>
    /// 生成过程中的日志
    /// </summary>
    public List<string> Logs { get; set; } = new();

    /// <summary>
    /// 生成是否成功
    /// </summary>
    public bool Success { get; set; }

    /// <summary>
    /// 错误信息
    /// </summary>
    public string? Error { get; set; }

    /// <summary>
    /// 生成的哈希值（用于变更检测）
    /// </summary>
    public string ContentHash { get; set; } = "";
}

/// <summary>
/// 生成的文件
/// </summary>
public class GeneratedFile
{
    /// <summary>
    /// 文件路径（相对于输出目录）
    /// </summary>
    public required string RelativePath { get; set; }

    /// <summary>
    /// 文件内容
    /// </summary>
    public required string Content { get; set; }

    /// <summary>
    /// 文件类型
    /// </summary>
    public required string FileType { get; set; } // cs, cshtml, sql, etc.

    /// <summary>
    /// 内容哈希
    /// </summary>
    public string ContentHash { get; set; } = "";

    /// <summary>
    /// 是否是覆盖现有文件
    /// </summary>
    public bool IsOverwrite { get; set; }
}

/// <summary>
/// 代码验证结果
/// </summary>
public class CodeValidationResult
{
    /// <summary>
    /// 验证是否通过
    /// </summary>
    public bool IsValid { get; set; }

    /// <summary>
    /// 编译错误列表
    /// </summary>
    public List<string> CompilationErrors { get; set; } = new();

    /// <summary>
    /// 代码质量问题列表
    /// </summary>
    public List<string> CodeQualityIssues { get; set; } = new();

    /// <summary>
    /// 验证日志
    /// </summary>
    public List<string> Logs { get; set; } = new();
}
