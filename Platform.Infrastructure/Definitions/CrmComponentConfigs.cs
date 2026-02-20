namespace Platform.Infrastructure.Definitions;

/// <summary>
/// CRM 导航管道配置
/// </summary>
public class NavPipelineConfig
{
    public LocalizedText Title { get; set; } = new();
    public List<NavStageConfig> Stages { get; set; } = new();
}

/// <summary>
/// 导航阶段配置
/// </summary>
public class NavStageConfig
{
    public string Icon { get; set; } = "📍";
    public LocalizedText Label { get; set; } = new();
    public string Href { get; set; } = "#";
    public string Color { get; set; } = "from-gray-500 to-gray-600";
}

/// <summary>
/// 统计行配置
/// </summary>
public class StatsRowConfig
{
    public LocalizedText Title { get; set; } = new();
    public string Background { get; set; } = "bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50";
    public List<StatItemConfig> Items { get; set; } = new();
}

/// <summary>
/// 角色操作配置
/// </summary>
public class RoleBasedActionsConfig
{
    public LocalizedText Title { get; set; } = new();
    public Dictionary<string, List<RoleActionConfig>> Roles { get; set; } = new();
}

/// <summary>
/// 角色操作项配置
/// </summary>
public class RoleActionConfig
{
    public string Icon { get; set; } = "📋";
    public LocalizedText Label { get; set; } = new();
    public string Href { get; set; } = "#";
    public string Badge { get; set; } = "";
    public string Color { get; set; } = "blue";
}

/// <summary>
/// 现代化卡片网格配置
/// </summary>
public class CardGridModernConfig
{
    public GridColumnsConfig Columns { get; set; } = new();
    public List<CardModernConfig> Cards { get; set; } = new();
}

/// <summary>
/// 卡片配置
/// </summary>
public class CardModernConfig
{
    public string Icon { get; set; } = "📊";
    public LocalizedText Title { get; set; } = new();
    public LocalizedText Description { get; set; } = new();
    public string Href { get; set; } = "#";
    public string Gradient { get; set; } = "from-gray-500 to-gray-600";
    public List<CardStatConfig> Stats { get; set; } = new();
}

/// <summary>
/// 卡片统计配置
/// </summary>
public class CardStatConfig
{
    public string Label { get; set; } = "";
    public string Value { get; set; } = "";
}

/// <summary>
/// 漏斗图配置
/// </summary>
public class FunnelChartConfig
{
    public LocalizedText Title { get; set; } = new();
    public LocalizedText Subtitle { get; set; } = new();
    public List<FunnelStageConfig> Stages { get; set; } = new();
}

/// <summary>
/// 漏斗阶段配置
/// </summary>
public class FunnelStageConfig
{
    public LocalizedText Name { get; set; } = new();
    public int Value { get; set; }
    public string Color { get; set; } = "bg-blue-500";
}

/// <summary>
/// 活动时间线配置
/// </summary>
public class ActivityTimelineConfig
{
    public LocalizedText Title { get; set; } = new();
    public LocalizedText Subtitle { get; set; } = new();
    public int Limit { get; set; } = 10;
}

/// <summary>
/// 提醒配置
/// </summary>
public class RemindersConfig
{
    public LocalizedText Title { get; set; } = new();
    public List<ReminderItemConfig> Items { get; set; } = new();
}

/// <summary>
/// 提醒项配置
/// </summary>
public class ReminderItemConfig
{
    public string Icon { get; set; } = "📋";
    public LocalizedText Text { get; set; } = new();
    public string Time { get; set; } = "";
    public string Priority { get; set; } = "medium";
}
