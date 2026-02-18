using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Yaml;
using System.Security.Cryptography;
using System.Text;

namespace Platform.Application.CodeGeneration;

/// <summary>
/// 代码生成服务 - 统一入口，整合所有代码生成组件
/// 提供确定性的、可重复的代码生成能力
/// </summary>
public class CodeGenerationService
{
    private readonly ModelCodeGenerator _codeGenerator;
    private readonly CodeVersionManager _versionManager;
    private readonly CodeQualityValidator _qualityValidator;
    private readonly CodeTemplateEngine _templateEngine;
    private readonly CodeGenerationSettings _settings;

    public CodeGenerationService(CodeGenerationSettings? settings = null)
    {
        _settings = settings ?? new CodeGenerationSettings();
        _codeGenerator = new ModelCodeGenerator(new CodeTemplateOptions
        {
            IndentSize = _settings.IndentSize,
            AddHeaderComments = _settings.AddHeaderComments
        });
        _versionManager = new CodeVersionManager(_settings.VersionDirectory);
        _qualityValidator = new CodeQualityValidator(_settings.QualityRules);
        _templateEngine = new CodeTemplateEngine();
    }

    /// <summary>
    /// 从 YAML 定义生成代码（完整流程）
    /// </summary>
    public async Task<CodeGenerationPipelineResult> GenerateFromYamlAsync(
        string yamlFilePath,
        string outputDirectory,
        string? modelKey = null)
    {
        var result = new CodeGenerationPipelineResult();
        result.StartedAt = DateTime.UtcNow;

        try
        {
            // 1. 加载 YAML 定义
            result.Logs.Add($"加载 YAML 定义：{yamlFilePath}");
            var yamlContent = await File.ReadAllTextAsync(yamlFilePath);
            var yamlHash = ComputeHash(yamlContent);

            // 2. 解析模型定义
            var appDefinitions = YamlLoader.Load(yamlFilePath, Path.Combine(Path.GetDirectoryName(yamlFilePath)!, "pages"));
            result.Logs.Add($"解析完成，共 {appDefinitions.Models.Count} 个模型");

            // 3. 确定要生成的模型
            var modelsToGenerate = new Dictionary<string, ModelDefinition>();
            if (!string.IsNullOrEmpty(modelKey))
            {
                if (appDefinitions.Models.ContainsKey(modelKey))
                {
                    modelsToGenerate[modelKey] = appDefinitions.Models[modelKey];
                }
                else
                {
                    throw new InvalidOperationException($"模型 '{modelKey}' 不存在");
                }
            }
            else
            {
                modelsToGenerate = appDefinitions.Models;
            }

            // 4. 为每个模型生成代码
            foreach (var kvp in modelsToGenerate)
            {
                var modelResult = await GenerateModelAsync(kvp.Value, outputDirectory, yamlHash, kvp.Key);
                result.ModelResults.Add(kvp.Key, modelResult);
            }

            // 5. 生成项目级文件
            if (_settings.GenerateProjectFiles)
            {
                var projectFiles = await GenerateProjectFilesAsync(appDefinitions, outputDirectory);
                result.ProjectFiles = projectFiles;
            }

            result.Success = true;
            result.Logs.Add("代码生成完成");
        }
        catch (Exception ex)
        {
            result.Success = false;
            result.Error = ex.Message;
            result.Logs.Add($"错误：{ex.Message}");
        }

        result.CompletedAt = DateTime.UtcNow;
        return result;
    }

    /// <summary>
    /// 生成单个模型的代码
    /// </summary>
    public async Task<ModelGenerationResult> GenerateModelAsync(
        ModelDefinition model,
        string outputDirectory,
        string yamlHash,
        string modelKey)
    {
        var modelResult = new ModelGenerationResult();

        try
        {
            // 1. 准备生成上下文
            var context = new CodeGenerationContext
            {
                Model = model,
                OutputDirectory = outputDirectory,
                Namespace = _settings.RootNamespace,
                Options = _settings.GenerationOptions
            };

            // 2. 检查是否需要更新
            var updateCheck = await _versionManager.CheckUpdateNeededAsync(modelKey, yamlHash,
                await _codeGenerator.GenerateAsync(context));

            if (!updateCheck.UpdateNeeded && !_settings.ForceRegenerate)
            {
                modelResult.Skipped = true;
                modelResult.Message = "代码已是最新，无需生成";
                return modelResult;
            }

            modelResult.UpdateCheck = updateCheck;

            // 3. 生成代码
            modelResult.Logs.Add($"开始为模型 '{model.Table}' 生成代码...");
            var generationResult = await _codeGenerator.GenerateAsync(context);

            if (!generationResult.Success)
            {
                modelResult.Success = false;
                modelResult.Error = generationResult.Error;
                modelResult.Logs.AddRange(generationResult.Logs);
                return modelResult;
            }

            modelResult.GeneratedFiles = generationResult.Files;
            modelResult.Logs.AddRange(generationResult.Logs);

            // 4. 验证代码质量
            modelResult.Logs.Add("验证代码质量...");
            var qualityReport = await _qualityValidator.ValidateAsync(generationResult.Files);
            modelResult.QualityReport = qualityReport;

            if (!qualityReport.Passed && _settings.FailOnQualityError)
            {
                modelResult.Success = false;
                modelResult.Error = "代码质量验证失败";
                modelResult.Logs.Add($"质量评分：{qualityReport.OverallScore:F1}");
                return modelResult;
            }

            modelResult.Logs.Add($"质量评分：{qualityReport.OverallScore:F1}");

            // 5. 写入文件
            modelResult.Logs.Add("写入文件...");
            foreach (var file in generationResult.Files)
            {
                var fullPath = Path.Combine(outputDirectory, file.RelativePath);
                var directory = Path.GetDirectoryName(fullPath);
                if (directory != null && !Directory.Exists(directory))
                {
                    Directory.CreateDirectory(directory);
                }

                // 检查是否覆盖现有文件
                if (File.Exists(fullPath) && !context.Options.OverwriteExisting)
                {
                    modelResult.Logs.Add($"跳过现有文件：{file.RelativePath}");
                    continue;
                }

                await File.WriteAllTextAsync(fullPath, file.Content);
                modelResult.WrittenFiles.Add(file.RelativePath);
            }

            // 6. 保存版本
            modelResult.Logs.Add("保存版本信息...");
            var versionInfo = await _versionManager.SaveVersionAsync(
                generationResult,
                modelKey,
                yamlHash,
                updateCheck.Reason);
            modelResult.VersionInfo = versionInfo;

            modelResult.Success = true;
            modelResult.Logs.Add($"模型 '{model.Table}' 代码生成完成");
        }
        catch (Exception ex)
        {
            modelResult.Success = false;
            modelResult.Error = ex.Message;
            modelResult.Logs.Add($"错误：{ex.Message}");
        }

        return modelResult;
    }

    /// <summary>
    /// 获取模型的代码版本历史
    /// </summary>
    public async Task<List<CodeVersionInfo>> GetVersionHistoryAsync(string modelKey)
    {
        return await _versionManager.GetVersionHistoryAsync(modelKey);
    }

    /// <summary>
    /// 回滚到指定版本
    /// </summary>
    public async Task<bool> RollbackAsync(string versionId)
    {
        return await _versionManager.RollbackToVersionAsync(versionId);
    }

    /// <summary>
    /// 比较两个版本的差异
    /// </summary>
    public async Task<VersionDiff> CompareVersionsAsync(string versionId1, string versionId2)
    {
        return await _versionManager.CompareVersionsAsync(versionId1, versionId2);
    }

    /// <summary>
    /// 验证现有代码的质量
    /// </summary>
    public async Task<CodeQualityReport> ValidateExistingCodeAsync(string directory)
    {
        var files = new List<GeneratedFile>();

        foreach (var filePath in Directory.GetFiles(directory, "*.cs", SearchOption.AllDirectories))
        {
            var content = await File.ReadAllTextAsync(filePath);
            var relativePath = Path.GetRelativePath(directory, filePath);

            files.Add(new GeneratedFile
            {
                RelativePath = relativePath,
                Content = content,
                FileType = "cs",
                ContentHash = ComputeHash(content)
            });
        }

        return await _qualityValidator.ValidateAsync(files);
    }

    #region Private Methods

    private async Task<List<GeneratedFile>> GenerateProjectFilesAsync(AppDefinitions definitions, string outputDirectory)
    {
        var files = new List<GeneratedFile>();

        // 可以生成项目级的配置文件
        // 如：README.md, 项目文档等

        await Task.CompletedTask;
        return files;
    }

    private string ComputeHash(string content)
    {
        using var sha256 = SHA256.Create();
        var hashBytes = sha256.ComputeHash(Encoding.UTF8.GetBytes(content));
        return Convert.ToBase64String(hashBytes);
    }

    #endregion
}

/// <summary>
/// 代码生成设置
/// </summary>
public class CodeGenerationSettings
{
    /// <summary>
    /// 根命名空间
    /// </summary>
    public string RootNamespace { get; set; } = "Platform.Api";

    /// <summary>
    /// 版本存储目录
    /// </summary>
    public string VersionDirectory { get; set; } = ".code_versions";

    /// <summary>
    /// 代码缩进大小
    /// </summary>
    public int IndentSize { get; set; } = 4;

    /// <summary>
    /// 是否添加文件头注释
    /// </summary>
    public bool AddHeaderComments { get; set; } = true;

    /// <summary>
    /// 是否生成项目文件
    /// </summary>
    public bool GenerateProjectFiles { get; set; } = true;

    /// <summary>
    /// 是否强制重新生成（即使没有变更）
    /// </summary>
    public bool ForceRegenerate { get; set; } = false;

    /// <summary>
    /// 质量验证失败时是否停止
    /// </summary>
    public bool FailOnQualityError { get; set; } = false;

    /// <summary>
    /// 代码质量规则
    /// </summary>
    public CodeQualityRules QualityRules { get; set; } = new();

    /// <summary>
    /// 生成选项
    /// </summary>
    public CodeGenerationOptions GenerationOptions { get; set; } = new();
}

/// <summary>
/// 代码生成流水线结果
/// </summary>
public class CodeGenerationPipelineResult
{
    /// <summary>
    /// 开始时间
    /// </summary>
    public DateTime StartedAt { get; set; }

    /// <summary>
    /// 完成时间
    /// </summary>
    public DateTime CompletedAt { get; set; }

    /// <summary>
    /// 是否成功
    /// </summary>
    public bool Success { get; set; }

    /// <summary>
    /// 错误信息
    /// </summary>
    public string? Error { get; set; }

    /// <summary>
    /// 日志
    /// </summary>
    public List<string> Logs { get; set; } = new();

    /// <summary>
    /// 模型生成结果
    /// </summary>
    public Dictionary<string, ModelGenerationResult> ModelResults { get; set; } = new();

    /// <summary>
    /// 项目文件
    /// </summary>
    public List<GeneratedFile> ProjectFiles { get; set; } = new();

    /// <summary>
    /// 耗时（秒）
    /// </summary>
    public double Duration => (CompletedAt - StartedAt).TotalSeconds;
}

/// <summary>
/// 模型生成结果
/// </summary>
public class ModelGenerationResult
{
    /// <summary>
    /// 是否成功
    /// </summary>
    public bool Success { get; set; }

    /// <summary>
    /// 是否跳过
    /// </summary>
    public bool Skipped { get; set; }

    /// <summary>
    /// 消息
    /// </summary>
    public string? Message { get; set; }

    /// <summary>
    /// 错误信息
    /// </summary>
    public string? Error { get; set; }

    /// <summary>
    /// 日志
    /// </summary>
    public List<string> Logs { get; set; } = new();

    /// <summary>
    /// 生成的文件
    /// </summary>
    public List<GeneratedFile> GeneratedFiles { get; set; } = new();

    /// <summary>
    /// 写入的文件
    /// </summary>
    public List<string> WrittenFiles { get; set; } = new();

    /// <summary>
    /// 质量报告
    /// </summary>
    public CodeQualityReport? QualityReport { get; set; }

    /// <summary>
    /// 版本信息
    /// </summary>
    public CodeVersionInfo? VersionInfo { get; set; }

    /// <summary>
    /// 更新检查结果
    /// </summary>
    public UpdateCheckResult? UpdateCheck { get; set; }
}
