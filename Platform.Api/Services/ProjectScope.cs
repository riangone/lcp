namespace Platform.Api.Services;

/// <summary>
/// 项目作用域服务 - 在请求级别存储当前项目
/// </summary>
public class ProjectScope
{
    private readonly AsyncLocal<ProjectInfo?> _currentProject = new();
    private readonly ProjectManager _projectManager;

    public ProjectScope(ProjectManager projectManager)
    {
        _projectManager = projectManager;
        
        // 默认加载第一个项目
        CurrentProject = _projectManager.GetDefaultProject();
    }

    /// <summary>
    /// 当前项目
    /// </summary>
    public ProjectInfo? CurrentProject
    {
        get => _currentProject.Value;
        set => _currentProject.Value = value;
    }

    /// <summary>
    /// 切换项目
    /// </summary>
    public bool SwitchProject(string projectName)
    {
        if (_projectManager.TryGetProject(projectName, out var project))
        {
            CurrentProject = project;
            return true;
        }
        return false;
    }

    /// <summary>
    /// 获取所有可用项目
    /// </summary>
    public IEnumerable<ProjectInfo> GetAvailableProjects()
    {
        return _projectManager.GetAllProjects();
    }
}
