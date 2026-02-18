using System.Security.Cryptography;
using System.Text;
using System.Text.Json;

namespace Platform.Application.CodeGeneration;

/// <summary>
/// 代码版本管理器 - 管理生成代码的版本和变更追踪
/// 确保代码生成的稳定性和可追溯性
/// </summary>
public class CodeVersionManager
{
    private readonly string _versionDirectory;
    private readonly CodeVersionStore _store;

    public CodeVersionManager(string versionDirectory)
    {
        _versionDirectory = versionDirectory;
        _store = new CodeVersionStore(versionDirectory);

        if (!Directory.Exists(versionDirectory))
        {
            Directory.CreateDirectory(versionDirectory);
        }
    }

    /// <summary>
    /// 保存生成的代码版本
    /// </summary>
    public async Task<CodeVersionInfo> SaveVersionAsync(
        CodeGenerationResult result,
        string modelKey,
        string yamlHash,
        string? changeDescription = null)
    {
        var versionInfo = new CodeVersionInfo
        {
            VersionId = GenerateVersionId(),
            ModelKey = modelKey,
            YamlHash = yamlHash,
            GeneratedAt = DateTime.UtcNow,
            ChangeDescription = changeDescription ?? "代码生成",
            GeneratorVersion = result.Files.FirstOrDefault()?.Content.Contains("生成器版本") == true
                ? ExtractGeneratorVersion(result.Files.First())
                : "unknown",
            FileCount = result.Files.Count,
            ContentHash = result.ContentHash,
            Status = "active"
        };

        // 保存版本元数据
        await _store.SaveVersionAsync(versionInfo);

        // 保存文件内容
        foreach (var file in result.Files)
        {
            await _store.SaveFileAsync(versionInfo.VersionId, file);
        }

        // 检查与上一版本的差异
        var previousVersion = await GetPreviousVersionAsync(modelKey);
        if (previousVersion != null)
        {
            var diff = await CompareVersionsAsync(previousVersion.VersionId, versionInfo.VersionId);
            versionInfo.ChangesFromPrevious = diff;
            await _store.UpdateVersionAsync(versionInfo);
        }

        return versionInfo;
    }

    /// <summary>
    /// 获取模型的代码版本历史
    /// </summary>
    public async Task<List<CodeVersionInfo>> GetVersionHistoryAsync(string modelKey)
    {
        return await _store.GetVersionsByModelAsync(modelKey);
    }

    /// <summary>
    /// 获取指定版本的代码
    /// </summary>
    public async Task<CodeGenerationResult> GetVersionAsync(string versionId)
    {
        var versionInfo = await _store.GetVersionAsync(versionId);
        if (versionInfo == null)
            throw new InvalidOperationException($"版本 {versionId} 不存在");

        var files = await _store.GetFilesAsync(versionId);
        return new CodeGenerationResult
        {
            Files = files,
            ContentHash = versionInfo.ContentHash,
            Success = true
        };
    }

    /// <summary>
    /// 比较两个版本的差异
    /// </summary>
    public async Task<VersionDiff> CompareVersionsAsync(string versionId1, string versionId2)
    {
        var files1 = await _store.GetFilesAsync(versionId1);
        var files2 = await _store.GetFilesAsync(versionId2);

        var diff = new VersionDiff
        {
            SourceVersion = versionId1,
            TargetVersion = versionId2
        };

        var files1Dict = files1.ToDictionary(f => f.RelativePath);
        var files2Dict = files2.ToDictionary(f => f.RelativePath);

        // 新增的文件
        foreach (var file in files2)
        {
            if (!files1Dict.ContainsKey(file.RelativePath))
            {
                diff.AddedFiles.Add(file.RelativePath);
            }
            else if (files1Dict[file.RelativePath].ContentHash != file.ContentHash)
            {
                // 修改的文件
                diff.ModifiedFiles.Add(new FileDiff
                {
                    Path = file.RelativePath,
                    OldHash = files1Dict[file.RelativePath].ContentHash,
                    NewHash = file.ContentHash
                });
            }
        }

        // 删除的文件
        foreach (var file in files1)
        {
            if (!files2Dict.ContainsKey(file.RelativePath))
            {
                diff.DeletedFiles.Add(file.RelativePath);
            }
        }

        return diff;
    }

    /// <summary>
    /// 检查 YAML 定义是否有变更
    /// </summary>
    public async Task<bool> HasYamlChangedAsync(string modelKey, string currentYamlHash)
    {
        var latestVersion = await GetLatestVersionAsync(modelKey);
        if (latestVersion == null)
            return true; // 没有历史版本，认为有变更

        return latestVersion.YamlHash != currentYamlHash;
    }

    /// <summary>
    /// 获取最新版本
    /// </summary>
    public async Task<CodeVersionInfo?> GetLatestVersionAsync(string modelKey)
    {
        var versions = await GetVersionHistoryAsync(modelKey);
        return versions.OrderByDescending(v => v.GeneratedAt).FirstOrDefault();
    }

    /// <summary>
    /// 回滚到指定版本
    /// </summary>
    public async Task<bool> RollbackToVersionAsync(string versionId)
    {
        var version = await _store.GetVersionAsync(versionId);
        if (version == null)
            return false;

        version.Status = "rolled_back";
        await _store.UpdateVersionAsync(version);

        return true;
    }

    /// <summary>
    /// 检查生成的代码是否需要更新
    /// </summary>
    public async Task<UpdateCheckResult> CheckUpdateNeededAsync(
        string modelKey,
        string currentYamlHash,
        CodeGenerationResult newGeneration)
    {
        var result = new UpdateCheckResult();

        // 检查 YAML 是否有变更
        var latestVersion = await GetLatestVersionAsync(modelKey);
        if (latestVersion == null)
        {
            result.UpdateNeeded = true;
            result.Reason = "首次生成代码";
            return result;
        }

        if (latestVersion.YamlHash != currentYamlHash)
        {
            result.UpdateNeeded = true;
            result.Reason = "YAML 定义已变更";

            // 计算差异
            var diff = await CompareVersionsAsync(latestVersion.VersionId, "");
            result.YamlChanged = true;
            return result;
        }

        // 检查生成器版本是否有变更
        var newGeneratorVersion = ExtractGeneratorVersion(newGeneration.Files.FirstOrDefault());
        if (latestVersion.GeneratorVersion != newGeneratorVersion)
        {
            result.UpdateNeeded = true;
            result.Reason = $"生成器版本更新：{latestVersion.GeneratorVersion} -> {newGeneratorVersion}";
            result.GeneratorVersionChanged = true;
            return result;
        }

        // 检查内容是否有变更
        if (latestVersion.ContentHash != newGeneration.ContentHash)
        {
            result.UpdateNeeded = true;
            result.Reason = "生成的代码内容有变更";
            return result;
        }

        result.UpdateNeeded = false;
        result.Reason = "代码已是最新";
        return result;
    }

    #region Private Methods

    private string GenerateVersionId()
    {
        // 生成格式：vYYYYMMDD-HHMMSS-XXXX
        var now = DateTime.UtcNow;
        var random = Random.Shared.Next(1000, 9999);
        return $"v{now:yyyyMMdd-HHmmss}-{random}";
    }

    private async Task<CodeVersionInfo?> GetPreviousVersionAsync(string modelKey)
    {
        var versions = await GetVersionHistoryAsync(modelKey);
        return versions.OrderByDescending(v => v.GeneratedAt).Skip(1).FirstOrDefault();
    }

    private string ExtractGeneratorVersion(GeneratedFile? file)
    {
        if (file == null) return "unknown";

        // 从文件头注释中提取生成器版本
        var lines = file.Content.Split('\n');
        foreach (var line in lines.Take(10)) // 在前 10 行查找
        {
            if (line.Contains("生成器版本："))
            {
                var parts = line.Split(':');
                if (parts.Length > 1)
                {
                    return parts[1].Trim();
                }
            }
        }

        return "unknown";
    }

    #endregion
}

/// <summary>
/// 代码版本存储
/// </summary>
public class CodeVersionStore
{
    private readonly string _baseDirectory;
    private readonly string _versionsFile;

    public CodeVersionStore(string baseDirectory)
    {
        _baseDirectory = baseDirectory;
        _versionsFile = Path.Combine(baseDirectory, "versions.json");

        if (!Directory.Exists(baseDirectory))
        {
            Directory.CreateDirectory(baseDirectory);
        }

        if (!File.Exists(_versionsFile))
        {
            File.WriteAllText(_versionsFile, "[]");
        }
    }

    public async Task SaveVersionAsync(CodeVersionInfo version)
    {
        var versions = await LoadVersionsAsync();
        versions.Add(version);
        await SaveVersionsAsync(versions);
    }

    public async Task UpdateVersionAsync(CodeVersionInfo version)
    {
        var versions = await LoadVersionsAsync();
        var index = versions.FindIndex(v => v.VersionId == version.VersionId);
        if (index >= 0)
        {
            versions[index] = version;
            await SaveVersionsAsync(versions);
        }
    }

    public async Task<CodeVersionInfo?> GetVersionAsync(string versionId)
    {
        var versions = await LoadVersionsAsync();
        return versions.FirstOrDefault(v => v.VersionId == versionId);
    }

    public async Task<List<CodeVersionInfo>> GetVersionsByModelAsync(string modelKey)
    {
        var versions = await LoadVersionsAsync();
        return versions.Where(v => v.ModelKey == modelKey).ToList();
    }

    public async Task SaveFileAsync(string versionId, GeneratedFile file)
    {
        var fileDir = Path.Combine(_baseDirectory, versionId);
        if (!Directory.Exists(fileDir))
        {
            Directory.CreateDirectory(fileDir);
        }

        var filePath = Path.Combine(fileDir, file.RelativePath);
        var directory = Path.GetDirectoryName(filePath);
        if (directory != null && !Directory.Exists(directory))
        {
            Directory.CreateDirectory(directory);
        }

        await File.WriteAllTextAsync(filePath, file.Content);
    }

    public async Task<List<GeneratedFile>> GetFilesAsync(string versionId)
    {
        var files = new List<GeneratedFile>();
        var versionDir = Path.Combine(_baseDirectory, versionId);

        if (!Directory.Exists(versionDir))
            return files;

        foreach (var filePath in Directory.GetFiles(versionDir, "*.*", SearchOption.AllDirectories))
        {
            var relativePath = Path.GetRelativePath(versionDir, filePath);
            var content = await File.ReadAllTextAsync(filePath);

            files.Add(new GeneratedFile
            {
                RelativePath = relativePath,
                Content = content,
                FileType = Path.GetExtension(filePath).TrimStart('.'),
                ContentHash = ComputeHash(content)
            });
        }

        return files;
    }

    private async Task<List<CodeVersionInfo>> LoadVersionsAsync()
    {
        var json = await File.ReadAllTextAsync(_versionsFile);
        return JsonSerializer.Deserialize<List<CodeVersionInfo>>(json) ?? new List<CodeVersionInfo>();
    }

    private async Task SaveVersionsAsync(List<CodeVersionInfo> versions)
    {
        var json = JsonSerializer.Serialize(versions, new JsonSerializerOptions
        {
            WriteIndented = true,
            PropertyNamingPolicy = JsonNamingPolicy.CamelCase
        });
        await File.WriteAllTextAsync(_versionsFile, json);
    }

    private string ComputeHash(string content)
    {
        using var sha256 = SHA256.Create();
        var hashBytes = sha256.ComputeHash(Encoding.UTF8.GetBytes(content));
        return Convert.ToBase64String(hashBytes);
    }
}

/// <summary>
/// 代码版本信息
/// </summary>
public class CodeVersionInfo
{
    /// <summary>
    /// 版本 ID
    /// </summary>
    public required string VersionId { get; set; }

    /// <summary>
    /// 模型键
    /// </summary>
    public required string ModelKey { get; set; }

    /// <summary>
    /// YAML 定义哈希
    /// </summary>
    public required string YamlHash { get; set; }

    /// <summary>
    /// 生成时间
    /// </summary>
    public DateTime GeneratedAt { get; set; }

    /// <summary>
    /// 变更描述
    /// </summary>
    public string? ChangeDescription { get; set; }

    /// <summary>
    /// 生成器版本
    /// </summary>
    public string GeneratorVersion { get; set; } = "";

    /// <summary>
    /// 文件数量
    /// </summary>
    public int FileCount { get; set; }

    /// <summary>
    /// 内容哈希
    /// </summary>
    public string ContentHash { get; set; } = "";

    /// <summary>
    /// 版本状态：active, rolled_back, deprecated
    /// </summary>
    public string Status { get; set; } = "active";

    /// <summary>
    /// 与上一版本的差异
    /// </summary>
    public VersionDiff? ChangesFromPrevious { get; set; }
}

/// <summary>
/// 版本差异
/// </summary>
public class VersionDiff
{
    /// <summary>
    /// 源版本
    /// </summary>
    public string SourceVersion { get; set; } = "";

    /// <summary>
    /// 目标版本
    /// </summary>
    public string TargetVersion { get; set; } = "";

    /// <summary>
    /// 新增的文件
    /// </summary>
    public List<string> AddedFiles { get; set; } = new();

    /// <summary>
    /// 修改的文件
    /// </summary>
    public List<FileDiff> ModifiedFiles { get; set; } = new();

    /// <summary>
    /// 删除的文件
    /// </summary>
    public List<string> DeletedFiles { get; set; } = new();

    /// <summary>
    /// 差异摘要
    /// </summary>
    public string Summary => $"Added: {AddedFiles.Count}, Modified: {ModifiedFiles.Count}, Deleted: {DeletedFiles.Count}";
}

/// <summary>
/// 文件差异
/// </summary>
public class FileDiff
{
    /// <summary>
    /// 文件路径
    /// </summary>
    public string Path { get; set; } = "";

    /// <summary>
    /// 旧哈希
    /// </summary>
    public string OldHash { get; set; } = "";

    /// <summary>
    /// 新哈希
    /// </summary>
    public string NewHash { get; set; } = "";
}

/// <summary>
/// 更新检查结果
/// </summary>
public class UpdateCheckResult
{
    /// <summary>
    /// 是否需要更新
    /// </summary>
    public bool UpdateNeeded { get; set; }

    /// <summary>
    /// 原因
    /// </summary>
    public string Reason { get; set; } = "";

    /// <summary>
    /// YAML 是否变更
    /// </summary>
    public bool YamlChanged { get; set; }

    /// <summary>
    /// 生成器版本是否变更
    /// </summary>
    public bool GeneratorVersionChanged { get; set; }
}
