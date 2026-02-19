using System.Collections.Generic;

namespace Platform.Infrastructure.Definitions;

/// <summary>
/// 首页定义 - YAML 驱动的首页配置
/// </summary>
public class HomeDefinition
{
    /// <summary>
    /// 页面标题
    /// </summary>
    public string Title { get; set; } = "LowCode Platform";

    /// <summary>
    /// 布局组件列表
    /// </summary>
    public List<HomeComponentConfig> Layout { get; set; } = new();
}

/// <summary>
/// 首页组件配置
/// </summary>
public class HomeComponentConfig
{
    /// <summary>
    /// 组件类型
    /// </summary>
    public string Type { get; set; } = "";

    /// <summary>
    /// 组件配置数据
    /// </summary>
    public Dictionary<string, object> Data { get; set; } = new();
}

/// <summary>
/// 多语言文本
/// </summary>
public class LocalizedText
{
    public string? Zh { get; set; }
    public string? En { get; set; }

    public string GetText(string lang)
    {
        return lang == "zh" ? (Zh ?? En ?? "") : (En ?? Zh ?? "");
    }
}

/// <summary>
/// Hero 组件配置
/// </summary>
public class HeroConfig
{
    public string Icon { get; set; } = "🚀";
    public LocalizedText Title { get; set; } = new();
    public LocalizedText Subtitle { get; set; } = new();
    public HeroStyleConfig Style { get; set; } = new();
}

public class HeroStyleConfig
{
    public string Background { get; set; } = "from-blue-50 via-white to-purple-50";
    public string TitleSize { get; set; } = "text-5xl";
    public string SubtitleSize { get; set; } = "text-xl";
}

/// <summary>
/// 卡片网格组件配置
/// </summary>
public class CardGridConfig
{
    public LocalizedText Title { get; set; } = new();
    public string Source { get; set; } = "models"; // models, pages, projects
    public CardStyleConfig CardStyle { get; set; } = new();
    public GridColumnsConfig Columns { get; set; } = new();
    public List<ProjectCardConfig>? Projects { get; set; }
}

public class CardStyleConfig
{
    public string BaseClass { get; set; } = "bg-white rounded-xl p-6 shadow-md";
    public string HoverClass { get; set; } = "hover:shadow-lg hover:-translate-y-1";
}

public class GridColumnsConfig
{
    public int Mobile { get; set; } = 1;
    public int Tablet { get; set; } = 2;
    public int Desktop { get; set; } = 3;
    public int Wide { get; set; } = 4;
}

/// <summary>
/// 项目卡片配置
/// </summary>
public class ProjectCardConfig
{
    public string Key { get; set; } = "";
    public string Icon { get; set; } = "📁";
    public LocalizedText Name { get; set; } = new();
    public LocalizedText Description { get; set; } = new();
    public string Color { get; set; } = "blue";
    public string GradientFrom { get; set; } = "";
    public string GradientTo { get; set; } = "";
}

/// <summary>
/// 统计组件配置
/// </summary>
public class StatsConfig
{
    public List<StatItemConfig> Items { get; set; } = new();
}

public class StatItemConfig
{
    public string Value { get; set; } = "";
    public LocalizedText Label { get; set; } = new();
    public string Color { get; set; } = "blue";
}

/// <summary>
/// 警告提示组件配置
/// </summary>
public class AlertConfig
{
    public string AlertType { get; set; } = "info"; // info, success, warning, error
    public LocalizedText Content { get; set; } = new();
    public AlertLinkConfig? Link { get; set; }
}

public class AlertLinkConfig
{
    public LocalizedText Text { get; set; } = new();
    public string Href { get; set; } = "/";
}
