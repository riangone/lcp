using Platform.Application.CodeGeneration;

// ============================================
// AI 确定性代码生成系统 - 使用示例
// ============================================

// 1. 基本使用 - 从 YAML 生成代码
async Task BasicUsage()
{
    var settings = new CodeGenerationSettings
    {
        RootNamespace = "Platform.Api",
        VersionDirectory = ".code_versions",
        AddHeaderComments = true
    };

    var service = new CodeGenerationService(settings);

    // 从 YAML 定义生成所有模型的代码
    var result = await service.GenerateFromYamlAsync(
        yamlFilePath: "Definitions/app.yaml",
        outputDirectory: "Generated"
    );

    if (result.Success)
    {
        Console.WriteLine($"生成完成！耗时：{result.Duration:F2}秒");
        foreach (var kvp in result.ModelResults)
        {
            var modelResult = kvp.Value;
            Console.WriteLine($"  {kvp.Key}:");
            Console.WriteLine($"    文件数：{modelResult.WrittenFiles.Count}");
            Console.WriteLine($"    质量评分：{modelResult.QualityReport?.OverallScore:F1}");
        }
    }
    else
    {
        Console.WriteLine($"生成失败：{result.Error}");
    }
}

// 2. 生成单个模型
async Task GenerateSingleModel()
{
    var service = new CodeGenerationService();

    // 只生成 Artist 模型的代码
    var result = await service.GenerateFromYamlAsync(
        "Definitions/app.yaml",
        "Generated",
        modelKey: "Artist"
    );
}

// 3. 自定义生成选项
async Task CustomGenerationOptions()
{
    var settings = new CodeGenerationSettings
    {
        RootNamespace = "Platform.Api",
        GenerationOptions = new CodeGenerationOptions
        {
            GenerateApiController = true,
            GenerateUiController = false,
            GenerateViews = false,
            GenerateServices = true,
            GenerateValidators = true,
            GenerateTests = true,
            OverwriteExisting = false
        }
    };

    var service = new CodeGenerationService(settings);
    var result = await service.GenerateFromYamlAsync("Definitions/app.yaml", "Generated");
}

// 4. 版本管理
async Task VersionManagement()
{
    var service = new CodeGenerationService();

    // 获取版本历史
    var versions = await service.GetVersionHistoryAsync("Artist");
    Console.WriteLine("版本历史:");
    foreach (var version in versions.OrderByDescending(v => v.GeneratedAt))
    {
        Console.WriteLine($"  {version.VersionId} - {version.GeneratedAt}");
        Console.WriteLine($"    变更：{version.ChangeDescription}");
        Console.WriteLine($"    文件数：{version.FileCount}");
    }

    // 比较版本差异
    if (versions.Count >= 2)
    {
        var latest = versions.OrderByDescending(v => v.GeneratedAt).First();
        var previous = versions.OrderByDescending(v => v.GeneratedAt).Skip(1).First();

        var diff = await service.CompareVersionsAsync(previous.VersionId, latest.VersionId);
        Console.WriteLine($"\n版本差异:");
        Console.WriteLine($"  新增文件：{diff.AddedFiles.Count}");
        Console.WriteLine($"  修改文件：{diff.ModifiedFiles.Count}");
        Console.WriteLine($"  删除文件：{diff.DeletedFiles.Count}");
    }

    // 回滚到指定版本
    // await service.RollbackAsync("v20260219-120000-1234");
}

// 5. 检查是否需要更新
async Task CheckUpdateNeeded()
{
    var service = new CodeGenerationService();

    // 读取当前 YAML
    var yamlContent = await File.ReadAllTextAsync("Definitions/app.yaml");
    var yamlHash = ComputeHash(yamlContent);

    // 检查是否需要重新生成
    var updateCheck = await service.CheckUpdateNeededAsync(
        "Artist",
        yamlHash,
        new CodeGenerationResult() // 新的生成结果
    );

    if (updateCheck.UpdateNeeded)
    {
        Console.WriteLine($"需要更新：{updateCheck.Reason}");
    }
    else
    {
        Console.WriteLine("代码已是最新");
    }
}

// 6. 验证现有代码质量
async Task ValidateExistingCode()
{
    var service = new CodeGenerationService();

    // 验证现有代码
    var report = await service.ValidateExistingCodeAsync("Platform.Api");

    Console.WriteLine($"代码质量报告:");
    Console.WriteLine($"  总体评分：{report.OverallScore:F1}");
    Console.WriteLine($"  文件数：{report.FileCount}");
    Console.WriteLine($"  问题数：{report.Issues.Count}");

    foreach (var issue in report.Issues.Take(10))
    {
        Console.WriteLine($"  [{issue.Severity}] {issue.Rule}: {issue.Message}");
    }
}

// 7. 使用自定义模板
async Task UseCustomTemplate()
{
    var engine = new CodeTemplateEngine();

    // 注册自定义模板
    engine.RegisterTemplate("CustomController", @"using Microsoft.AspNetCore.Mvc;

namespace {Namespace}.Controllers;

/// <summary>
/// {Model.Name} 自定义控制器
/// </summary>
public class {Model.Name}Controller : ControllerBase
{
    // 自定义实现
}
");

    // 使用模板生成代码
    var context = new TemplateContext
    {
        Namespace = "Platform.Api",
        Model = new ModelTemplateContext
        {
            Name = "Product",
            TableName = "Product"
        }
    };

    var code = engine.Render("CustomController", context);
    Console.WriteLine(code);
}

// 8. 配置质量规则
async Task ConfigureQualityRules()
{
    var settings = new CodeGenerationSettings
    {
        QualityRules = new CodeQualityRules
        {
            MaxLineLength = 120,           // 更严格的行长度
            MaxFileLength = 500,           // 更严格的文件长度
            RequireFileHeader = true,      // 要求文件头
            CheckTrailingWhitespace = true,// 检查尾随空格
            TreatWarningsAsErrors = false  // 不将警告视为错误
        }
    };

    var service = new CodeGenerationService(settings);
    var result = await service.GenerateFromYamlAsync("Definitions/app.yaml", "Generated");

    // 检查质量报告
    foreach (var kvp in result.ModelResults)
    {
        var qualityReport = kvp.Value.QualityReport;
        if (qualityReport != null)
        {
            Console.WriteLine($"{kvp.Key}: 评分={qualityReport.OverallScore:F1}");
            Console.WriteLine($"  问题：{qualityReport.Issues.Count}");
        }
    }
}

// 辅助方法：计算哈希
string ComputeHash(string content)
{
    using var sha256 = System.Security.Cryptography.SHA256.Create();
    var hashBytes = sha256.ComputeHash(System.Text.Encoding.UTF8.GetBytes(content));
    return Convert.ToBase64String(hashBytes);
}

// ============================================
// 实际使用场景
// ============================================

// 场景 1: CI/CD 中自动检查代码是否需要更新
async Task CI_CD_Check()
{
    var service = new CodeGenerationService();
    var yamlContent = await File.ReadAllTextAsync("Definitions/app.yaml");
    var yamlHash = ComputeHash(yamlContent);

    // 检查所有模型
    var appDefinitions = YamlLoader.Load("Definitions/app.yaml", "Definitions/pages");
    foreach (var modelKey in appDefinitions.Models.Keys)
    {
        var updateCheck = await service.CheckUpdateNeededAsync(modelKey, yamlHash, ...);
        if (updateCheck.UpdateNeeded)
        {
            Console.WriteLine($"模型 {modelKey} 需要更新：{updateCheck.Reason}");
            // 触发重新生成
            await service.GenerateModelAsync(...);
        }
    }
}

// 场景 2: 定期生成代码质量报告
async Task GenerateQualityReport()
{
    var service = new CodeGenerationService();
    var report = await service.ValidateExistingCodeAsync("Platform.Api");

    var reportContent = $@"
# 代码质量报告

生成时间：{DateTime.UtcNow:yyyy-MM-dd HH:mm:ss}

## 总体评分
{report.OverallScore:F1} / 100

## 统计
- 文件数：{report.FileCount}
- 错误数：{report.Issues.Count(i => i.Severity == IssueSeverity.Error)}
- 警告数：{report.Issues.Count(i => i.Severity == IssueSeverity.Warning)}
- 建议数：{report.Issues.Count(i => i.Severity == IssueSeverity.Info)}

## 主要问题
{string.Join("\n", report.Issues.Take(20).Select(i => $"- [{i.Severity}] {i.Rule}: {i.Message}"))}
";

    await File.WriteAllTextAsync("Docs/CodeQualityReport.md", reportContent);
}

// 场景 3: 新版本发布前比较代码变更
async Task CompareBeforeRelease()
{
    var service = new CodeGenerationService();

    // 获取当前版本和上一版本
    var versions = await service.GetVersionHistoryAsync("Artist");
    var latest = versions.OrderByDescending(v => v.GeneratedAt).First();
    var previous = versions.OrderByDescending(v => v.GeneratedAt).Skip(1).First();

    // 比较差异
    var diff = await service.CompareVersionsAsync(previous.VersionId, latest.VersionId);

    // 生成变更日志
    var changelog = $@"
# 变更日志

## {latest.VersionId} ({latest.GeneratedAt:yyyy-MM-dd})

{latest.ChangeDescription}

### 文件变更
- 新增：{diff.AddedFiles.Count}
- 修改：{diff.ModifiedFiles.Count}
- 删除：{diff.DeletedFiles.Count}

### 新增文件
{string.Join("\n", diff.AddedFiles.Select(f => $"- {f}"))}

### 修改文件
{string.Join("\n", diff.ModifiedFiles.Select(f => $"- {f.Path}"))}
";

    await File.WriteAllTextAsync("Docs/CHANGELOG_Latest.md", changelog);
}
