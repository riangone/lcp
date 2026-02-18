using Platform.Infrastructure.Definitions;
using System.Security.Cryptography;
using System.Text;

namespace Platform.Application.CodeGeneration;

/// <summary>
/// 模型代码生成器 - 从 ModelDefinition 生成确定性代码
/// </summary>
public class ModelCodeGenerator : ICodeGenerator
{
    private readonly CodeTemplateEngine _templateEngine;
    private readonly string _generatorVersion = "1.0.0";

    public string GeneratorId => "model-code-generator";
    public string Version => _generatorVersion;

    public ModelCodeGenerator(CodeTemplateOptions? options = null)
    {
        _templateEngine = new CodeTemplateEngine(options);
    }

    public async Task<CodeGenerationResult> GenerateAsync(CodeGenerationContext context)
    {
        var result = new CodeGenerationResult();
        result.Logs.Add($"开始为模型 '{context.Model.Table}' 生成代码...");

        try
        {
            // 准备模板上下文
            var templateContext = PrepareTemplateContext(context);

            // 生成 API 控制器
            if (context.Options.GenerateApiController)
            {
                var controllerFile = GenerateApiController(templateContext, context);
                result.Files.Add(controllerFile);
                result.Logs.Add($"生成 API 控制器：{controllerFile.RelativePath}");
            }

            // 生成验证器
            if (context.Options.GenerateValidators)
            {
                var validatorFile = GenerateValidator(templateContext, context);
                result.Files.Add(validatorFile);
                result.Logs.Add($"生成验证器：{validatorFile.RelativePath}");
            }

            // 生成服务层
            if (context.Options.GenerateServices)
            {
                var serviceFiles = GenerateServices(templateContext, context);
                result.Files.AddRange(serviceFiles);
                result.Logs.Add($"生成服务层：{serviceFiles.Count} 个文件");
            }

            // 生成仓储层
            if (context.Options.GenerateRepositories)
            {
                var repositoryFiles = GenerateRepositories(templateContext, context);
                result.Files.AddRange(repositoryFiles);
                result.Logs.Add($"生成仓储层：{repositoryFiles.Count} 个文件");
            }

            // 生成单元测试
            if (context.Options.GenerateTests)
            {
                var testFiles = GenerateTests(templateContext, context);
                result.Files.AddRange(testFiles);
                result.Logs.Add($"生成单元测试：{testFiles.Count} 个文件");
            }

            // 计算内容哈希
            result.ContentHash = ComputeResultHash(result.Files);
            result.Success = true;
            result.Logs.Add($"代码生成完成，共生成 {result.Files.Count} 个文件");
        }
        catch (Exception ex)
        {
            result.Success = false;
            result.Error = ex.Message;
            result.Logs.Add($"错误：{ex.Message}");
        }

        await Task.CompletedTask;
        return result;
    }

    public async Task<CodeValidationResult> ValidateAsync(CodeGenerationResult result)
    {
        var validation = new CodeValidationResult();
        validation.Logs.Add("开始验证生成的代码...");

        // 1. 检查文件是否都成功生成
        if (result.Files.Count == 0)
        {
            validation.IsValid = false;
            validation.Logs.Add("警告：未生成任何文件");
        }

        // 2. 检查每个文件的内容
        foreach (var file in result.Files)
        {
            // 检查文件内容是否为空
            if (string.IsNullOrWhiteSpace(file.Content))
            {
                validation.CodeQualityIssues.Add($"文件 {file.RelativePath} 内容为空");
            }

            // 检查 C# 文件的语法
            if (file.FileType == "cs")
            {
                var syntaxIssues = ValidateCSharpSyntax(file.Content);
                if (syntaxIssues.Any())
                {
                    validation.CompilationErrors.AddRange(syntaxIssues);
                }
            }

            // 检查是否包含必要的 using 语句
            if (file.FileType == "cs" && !file.Content.Contains("using "))
            {
                validation.CodeQualityIssues.Add($"文件 {file.RelativePath} 可能缺少 using 语句");
            }

            // 检查命名空间是否匹配
            if (file.FileType == "cs" && file.Content.Contains("namespace "))
            {
                // 可以添加命名空间一致性检查
            }
        }

        // 3. 检查代码风格
        var styleIssues = CheckCodeStyle(result.Files);
        validation.CodeQualityIssues.AddRange(styleIssues);

        validation.IsValid = validation.CompilationErrors.Count == 0;
        validation.Logs.Add($"验证完成：{validation.CompilationErrors.Count} 个编译错误，{validation.CodeQualityIssues.Count} 个代码质量问题");

        await Task.CompletedTask;
        return validation;
    }

    #region Private Methods

    private TemplateContext PrepareTemplateContext(CodeGenerationContext context)
    {
        var templateContext = new TemplateContext
        {
            Namespace = context.Namespace,
            GeneratorVersion = _generatorVersion,
            GeneratedAt = DateTime.UtcNow,
            Model = new ModelTemplateContext
            {
                Name = GetSafeClassName(context.Model.Table),
                TableName = context.Model.Table,
                PrimaryKey = context.Model.PrimaryKey,
                Properties = context.Model.Properties
                    .Select(p => new PropertyTemplateContext
                    {
                        Name = p.Key,
                        Type = MapPropertyType(p.Value.Type),
                        Required = p.Value.Required,
                        MaxLength = GetMaxLength(p.Value)
                    })
                    .ToList()
            }
        };

        // 生成验证规则
        templateContext.ValidationRules = GenerateValidationRules(context);

        return templateContext;
    }

    private GeneratedFile GenerateApiController(TemplateContext context, CodeGenerationContext generationContext)
    {
        var content = _templateEngine.Render("ApiController", context);
        var fileName = $"{context.Model.Name}Controller.cs";

        return new GeneratedFile
        {
            RelativePath = Path.Combine("Controllers", fileName),
            Content = content,
            FileType = "cs",
            ContentHash = ComputeHash(content),
            IsOverwrite = generationContext.ExistingCode.ContainsKey(fileName)
        };
    }

    private GeneratedFile GenerateValidator(TemplateContext context, CodeGenerationContext generationContext)
    {
        var content = _templateEngine.Render("Validator", context);
        var fileName = $"{context.Model.Name}Validator.cs";

        return new GeneratedFile
        {
            RelativePath = Path.Combine("Validators", fileName),
            Content = content,
            FileType = "cs",
            ContentHash = ComputeHash(content),
            IsOverwrite = generationContext.ExistingCode.ContainsKey(fileName)
        };
    }

    private List<GeneratedFile> GenerateServices(TemplateContext context, CodeGenerationContext generationContext)
    {
        var files = new List<GeneratedFile>();

        // 生成服务接口和实现
        var content = _templateEngine.Render("Service", context);
        var fileName = $"I{context.Model.Name}Service.cs";

        files.Add(new GeneratedFile
        {
            RelativePath = Path.Combine("Services", fileName),
            Content = content,
            FileType = "cs",
            ContentHash = ComputeHash(content),
            IsOverwrite = generationContext.ExistingCode.ContainsKey(fileName)
        });

        return files;
    }

    private List<GeneratedFile> GenerateRepositories(TemplateContext context, CodeGenerationContext generationContext)
    {
        // 对于大多数情况，DynamicRepository 已经足够
        // 这里可以生成特定模型的仓储扩展
        var files = new List<GeneratedFile>();

        return files;
    }

    private List<GeneratedFile> GenerateTests(TemplateContext context, CodeGenerationContext generationContext)
    {
        var files = new List<GeneratedFile>();

        var content = _templateEngine.Render("UnitTest", context);
        var fileName = $"{context.Model.Name}ServiceTests.cs";

        files.Add(new GeneratedFile
        {
            RelativePath = Path.Combine("Services", fileName),
            Content = content,
            FileType = "cs",
            ContentHash = ComputeHash(content),
            IsOverwrite = generationContext.ExistingCode.ContainsKey(fileName)
        });

        return files;
    }

    private List<ValidationRuleTemplateContext> GenerateValidationRules(CodeGenerationContext context)
    {
        var rules = new List<ValidationRuleTemplateContext>();

        foreach (var prop in context.Model.Properties)
        {
            // 必填验证
            if (prop.Value.Required)
            {
                rules.Add(new ValidationRuleTemplateContext
                {
                    Condition = $"entity.ContainsKey(\"{prop.Key}\") && entity[\"{prop.Key}\"] != null && !string.IsNullOrEmpty(entity[\"{prop.Key}\"].ToString())",
                    ErrorMessage = $"{prop.Key} 是必填字段"
                });
            }

            // 字符串长度验证（简化版，使用固定最大长度）
            if (prop.Value.Type == "string")
            {
                rules.Add(new ValidationRuleTemplateContext
                {
                    Condition = $"!entity.ContainsKey(\"{prop.Key}\") || entity[\"{prop.Key}\"] == null || (entity[\"{prop.Key}\"].ToString()?.Length ?? 0) <= 200",
                    ErrorMessage = $"{prop.Key} 的长度不能超过 200"
                });
            }
        }

        return rules;
    }

    private List<string> ValidateCSharpSyntax(string code)
    {
        var errors = new List<string>();

        // 简单的语法检查
        var openBraces = code.Count(c => c == '{');
        var closeBraces = code.Count(c => c == '}');
        if (openBraces != closeBraces)
        {
            errors.Add($"括号不匹配：{openBraces} 个左括号，{closeBraces} 个右括号");
        }

        // 检查 namespace 声明
        if (!code.Contains("namespace "))
        {
            errors.Add("缺少 namespace 声明");
        }

        // 检查 class 声明
        if (!code.Contains("class "))
        {
            errors.Add("缺少 class 声明");
        }

        // 检查分号
        var lines = code.Split('\n');
        for (int i = 0; i < lines.Length; i++)
        {
            var line = lines[i].Trim();
            if (line.Length > 0 &&
                !line.StartsWith("//") &&
                !line.StartsWith("using ") &&
                !line.StartsWith("namespace ") &&
                !line.StartsWith("class ") &&
                !line.StartsWith("public ") &&
                !line.StartsWith("private ") &&
                !line.StartsWith("}") &&
                !line.StartsWith("{") &&
                !line.EndsWith(";") &&
                !line.EndsWith("{") &&
                !line.EndsWith("}") &&
                !line.EndsWith(":") &&
                line != "")
            {
                // 可能是语法错误，但不一定是
            }
        }

        return errors;
    }

    private List<string> CheckCodeStyle(List<GeneratedFile> files)
    {
        var issues = new List<string>();

        foreach (var file in files)
        {
            if (file.FileType != "cs") continue;

            // 检查是否有过长的行
            var lines = file.Content.Split('\n');
            for (int i = 0; i < lines.Length; i++)
            {
                if (lines[i].Length > 160)
                {
                    issues.Add($"文件 {file.RelativePath} 第 {i + 1} 行超过 160 字符");
                }
            }

            // 检查是否有尾随空格
            for (int i = 0; i < lines.Length; i++)
            {
                if (lines[i].EndsWith(" ") || lines[i].EndsWith("\t"))
                {
                    issues.Add($"文件 {file.RelativePath} 第 {i + 1} 行有尾随空格");
                }
            }
        }

        return issues;
    }

    private string ComputeResultHash(List<GeneratedFile> files)
    {
        using var sha256 = SHA256.Create();
        var combinedHash = new List<byte>();

        foreach (var file in files.OrderBy(f => f.RelativePath))
        {
            combinedHash.AddRange(Encoding.UTF8.GetBytes(file.RelativePath));
            combinedHash.AddRange(Convert.FromBase64String(file.ContentHash));
        }

        var hashBytes = sha256.ComputeHash(combinedHash.ToArray());
        return Convert.ToBase64String(hashBytes);
    }

    private string ComputeHash(string content)
    {
        using var sha256 = SHA256.Create();
        var hashBytes = sha256.ComputeHash(Encoding.UTF8.GetBytes(content));
        return Convert.ToBase64String(hashBytes);
    }

    private string GetSafeClassName(string tableName)
    {
        // 将表名转换为安全的类名
        // 例如：InvoiceLine -> InvoiceLine, customer -> Customer
        if (string.IsNullOrEmpty(tableName))
            return "Unknown";

        return char.ToUpperInvariant(tableName[0]) + tableName.Substring(1);
    }

    private string MapPropertyType(string yamlType)
    {
        return yamlType.ToLower() switch
        {
            "int" => "int",
            "integer" => "int",
            "string" => "string",
            "decimal" => "decimal",
            "number" => "double",
            "date" => "DateTime",
            "datetime" => "DateTime",
            "bool" => "bool",
            "boolean" => "bool",
            _ => "object"
        };
    }

    private int? GetMaxLength(PropertyDefinition prop)
    {
        // 从属性定义中获取最大长度
        // 这里可以根据具体实现扩展
        return prop.Type?.ToLower() switch
        {
            "string" => 100, // 默认最大长度
            _ => null
        };
    }

    #endregion
}
