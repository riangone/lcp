using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Yaml;
using System.Collections.Concurrent;

namespace Platform.Api.Services;

/// <summary>
/// 项目管理服务 - 支持运行时动态加载和切换项目
/// </summary>
public class ProjectManager
{
    private readonly string _projectsDirectory;
    private readonly ConcurrentDictionary<string, ProjectInfo> _projectCache = new();
    private readonly ILogger<ProjectManager> _logger;

    public ProjectManager(string projectsDirectory, ILogger<ProjectManager> logger)
    {
        _projectsDirectory = projectsDirectory;
        _logger = logger;
        
        // 扫描并加载所有可用项目
        ScanProjects();
    }

    /// <summary>
    /// 扫描 Projects 目录加载所有项目
    /// </summary>
    private void ScanProjects()
    {
        if (!Directory.Exists(_projectsDirectory))
        {
            _logger.LogWarning($"Projects directory not found: {_projectsDirectory}");
            return;
        }

        foreach (var dir in Directory.GetDirectories(_projectsDirectory))
        {
            var projectName = Path.GetFileName(dir);
            var projectFile = Path.Combine(dir, "project.yaml");
            
            if (File.Exists(projectFile))
            {
                try
                {
                    var projectInfo = LoadProjectInfo(projectName, dir);
                    _projectCache[projectName] = projectInfo;
                    _logger.LogInformation($"Loaded project: {projectName} ({projectInfo.DisplayName})");
                }
                catch (Exception ex)
                {
                    _logger.LogError(ex, $"Failed to load project: {projectName}");
                }
            }
        }
        
        _logger.LogInformation($"Scanned {_projectCache.Count} projects");
    }

    /// <summary>
    /// 加载项目信息
    /// </summary>
    private ProjectInfo LoadProjectInfo(string projectName, string projectDirectory)
    {
        var projectFile = Path.Combine(projectDirectory, "project.yaml");
        var appFile = Path.Combine(projectDirectory, "app.yaml");
        
        // 解析 project.yaml
        var projectYaml = File.ReadAllText(projectFile);
        var projectConfig = ParseProjectYaml(projectYaml);
        
        // 解析 app.yaml
        var appDefinitions = YamlLoader.Load(appFile, Path.Combine(projectDirectory, "pages"));
        
        // 设置数据库路径
        var dbPath = Path.Combine(projectDirectory, projectConfig.Database.Path);
        
        return new ProjectInfo
        {
            Name = projectName,
            DisplayName = projectConfig.DisplayName,
            Description = projectConfig.Description,
            Version = projectConfig.Version,
            Directory = projectDirectory,
            DatabasePath = dbPath,
            AppDefinitions = appDefinitions
        };
    }

    /// <summary>
    /// 解析 project.yaml
    /// </summary>
    private ProjectConfig ParseProjectYaml(string yaml)
    {
        var deserializer = new YamlDotNet.Serialization.DeserializerBuilder()
            .WithNamingConvention(YamlDotNet.Serialization.NamingConventions.UnderscoredNamingConvention.Instance)
            .IgnoreUnmatchedProperties()
            .Build();
        
        try
        {
            var config = deserializer.Deserialize<ProjectConfig>(yaml);
            
            if (config.Database == null)
            {
                config.Database = new DatabaseConfig();
            }
            
            return config;
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Failed to parse project.yaml");
            return new ProjectConfig
            {
                Name = "unknown",
                DisplayName = "Unknown Project",
                Version = "1.0.0",
                Database = new DatabaseConfig { Path = "app.db" }
            };
        }
    }

    /// <summary>
    /// 获取所有可用项目
    /// </summary>
    public IEnumerable<ProjectInfo> GetAllProjects()
    {
        return _projectCache.Values;
    }

    /// <summary>
    /// 获取指定项目
    /// </summary>
    public bool TryGetProject(string projectName, out ProjectInfo project)
    {
        return _projectCache.TryGetValue(projectName, out project);
    }

    /// <summary>
    /// 获取默认项目
    /// </summary>
    public ProjectInfo GetDefaultProject()
    {
        return _projectCache.Values.FirstOrDefault() 
            ?? throw new InvalidOperationException("No projects available");
    }

    /// <summary>
    /// 刷新项目缓存
    /// </summary>
    public void RefreshProject(string projectName)
    {
        if (_projectCache.TryRemove(projectName, out var oldProject))
        {
            try
            {
                var newProject = LoadProjectInfo(projectName, oldProject.Directory);
                _projectCache[projectName] = newProject;
                _logger.LogInformation($"Refreshed project: {projectName}");
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, $"Failed to refresh project: {projectName}");
                _projectCache[projectName] = oldProject;
            }
        }
    }
}

/// <summary>
/// 项目信息
/// </summary>
public class ProjectInfo
{
    public string Name { get; set; } = "";
    public string DisplayName { get; set; } = "";
    public string Description { get; set; } = "";
    public string Version { get; set; } = "1.0.0";
    public string Directory { get; set; } = "";
    public string DatabasePath { get; set; } = "";
    public AppDefinitions AppDefinitions { get; set; } = new();
}

/// <summary>
/// 项目配置
/// </summary>
public class ProjectConfig
{
    public string Name { get; set; } = "";
    public string DisplayName { get; set; } = "";
    public string Version { get; set; } = "1.0.0";
    public string Description { get; set; } = "";
    public DatabaseConfig Database { get; set; } = new();
}

/// <summary>
/// 数据库配置
/// </summary>
public class DatabaseConfig
{
    public string Type { get; set; } = "sqlite";
    public string Path { get; set; } = "app.db";
    public string Schema { get; set; } = "schema.sql";
    public string SeedData { get; set; } = "data.sql";
}
