namespace Platform.Infrastructure.Definitions;

/// <summary>
/// CRM 导航管道配置
/// </summary>
public class NavPipelineConfig
{
    public dynamic? Title { get; set; }
    public List<NavStageConfig>? Stages { get; set; }
}

/// <summary>
/// 导航阶段配置
/// </summary>
public class NavStageConfig
{
    public string? Icon { get; set; }
    public dynamic? Label { get; set; }
    public string? Href { get; set; }
    public string? Color { get; set; }
}

/// <summary>
/// 统计行配置
/// </summary>
public class StatsRowConfig
{
    public dynamic? Title { get; set; }
    public string? Background { get; set; }
    public List<StatItemConfig>? Items { get; set; }
}

/// <summary>
/// 角色操作配置
/// </summary>
public class RoleBasedActionsConfig
{
    public dynamic? Title { get; set; }
    public Dictionary<string, List<RoleActionConfig>>? Roles { get; set; }
}

/// <summary>
/// 角色操作项配置
/// </summary>
public class RoleActionConfig
{
    public string? Icon { get; set; }
    public dynamic? Label { get; set; }
    public string? Href { get; set; }
    public string? Badge { get; set; }
    public string? Color { get; set; }
}

/// <summary>
/// 现代化卡片网格配置
/// </summary>
public class CardGridModernConfig
{
    public GridColumnsConfig? Columns { get; set; }
    public List<CardConfig>? Cards { get; set; }
}

/// <summary>
/// 卡片配置
/// </summary>
public class CardConfig
{
    public string? Icon { get; set; }
    public dynamic? Title { get; set; }
    public dynamic? Description { get; set; }
    public string? Href { get; set; }
    public string? Gradient { get; set; }
    public List<CardStatConfig>? Stats { get; set; }
}

/// <summary>
/// 卡片统计配置
/// </summary>
public class CardStatConfig
{
    public string? Label { get; set; }
    public string? Value { get; set; }
}

/// <summary>
/// 漏斗图配置
/// </summary>
public class FunnelChartConfig
{
    public dynamic? Title { get; set; }
    public dynamic? Subtitle { get; set; }
    public List<FunnelStageConfig>? Stages { get; set; }
}

/// <summary>
/// 漏斗阶段配置
/// </summary>
public class FunnelStageConfig
{
    public dynamic? Name { get; set; }
    public int Value { get; set; }
    public string? Color { get; set; }
}

/// <summary>
/// 活动时间线配置
/// </summary>
public class ActivityTimelineConfig
{
    public dynamic? Title { get; set; }
    public dynamic? Subtitle { get; set; }
    public int? Limit { get; set; }
}

/// <summary>
/// 提醒配置
/// </summary>
public class RemindersConfig
{
    public dynamic? Title { get; set; }
    public List<ReminderItemConfig>? Items { get; set; }
}

/// <summary>
/// 提醒项配置
/// </summary>
public class ReminderItemConfig
{
    public string? Icon { get; set; }
    public dynamic? Text { get; set; }
    public string? Time { get; set; }
    public string? Priority { get; set; }
}
