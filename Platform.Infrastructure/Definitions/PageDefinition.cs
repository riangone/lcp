namespace Platform.Infrastructure.Definitions;

/// <summary>
/// 页面定义 - 用于在单个页面上管理多个相关表的数据
/// </summary>
public class PageDefinition
{
    /// <summary>
    /// 页面唯一标识
    /// </summary>
    public string Id { get; set; } = "";

    /// <summary>
    /// 页面标题
    /// </summary>
    public string Title { get; set; } = "";

    /// <summary>
    /// 页面描述
    /// </summary>
    public string? Description { get; set; }

    /// <summary>
    /// 主表（用于主要数据展示）
    /// </summary>
    public string? MainTable { get; set; }

    /// <summary>
    /// 页面区域定义
    /// </summary>
    public List<SectionDefinition> Sections { get; set; } = new();

    /// <summary>
    /// 页面操作定义
    /// </summary>
    public List<ActionDefinition> Actions { get; set; } = new();

    /// <summary>
    /// UI 定义
    /// </summary>
    public PageUiDefinition? Ui { get; set; }
}

/// <summary>
/// 页面区域定义
/// </summary>
public class SectionDefinition
{
    /// <summary>
    /// 区域唯一标识
    /// </summary>
    public string Id { get; set; } = "";

    /// <summary>
    /// 区域标题
    /// </summary>
    public string? Title { get; set; }

    /// <summary>
    /// 区域类型：table/form/card/list
    /// </summary>
    public string Type { get; set; } = "table";

    /// <summary>
    /// 数据源类型：model/query/custom
    /// </summary>
    public string SourceType { get; set; } = "model";

    /// <summary>
    /// 数据源 - 表名、查询名或自定义 SQL
    /// </summary>
    public string? Source { get; set; }

    /// <summary>
    /// 显示列
    /// </summary>
    public List<string> Columns { get; set; } = new();

    /// <summary>
    /// 过滤器定义
    /// </summary>
    public Dictionary<string, FilterDefinition>? Filters { get; set; }

    /// <summary>
    /// 表单字段定义（当 Type 为 form 时使用）
    /// </summary>
    public Dictionary<string, FormFieldDefinition>? Fields { get; set; }

    /// <summary>
    /// 是否可编辑
    /// </summary>
    public bool Editable { get; set; } = false;

    /// <summary>
    /// 是否只读
    /// </summary>
    public bool ReadOnly { get; set; } = false;

    /// <summary>
    /// 每页显示数量
    /// </summary>
    public int PageSize { get; set; } = 10;

    /// <summary>
    /// 关联字段 - 用于与主表关联
    /// </summary>
    public string? ForeignKey { get; set; }

    /// <summary>
    /// 关联主键
    /// </summary>
    public string? ForeignPrimaryKey { get; set; }

    /// <summary>
    /// 本地外键字段名
    /// </summary>
    public string? LocalForeignKey { get; set; }
}

/// <summary>
/// 页面操作定义
/// </summary>
public class ActionDefinition
{
    /// <summary>
    /// 操作唯一标识
    /// </summary>
    public string Id { get; set; } = "";

    /// <summary>
    /// 操作标题
    /// </summary>
    public string Title { get; set; } = "";

    /// <summary>
    /// 操作类型：submit/ajax/navigate
    /// </summary>
    public string Type { get; set; } = "submit";

    /// <summary>
    /// 操作目标 - API 端点或 URL
    /// </summary>
    public string? Target { get; set; }

    /// <summary>
    /// HTTP 方法：POST/PUT/DELETE
    /// </summary>
    public string Method { get; set; } = "POST";

    /// <summary>
    /// 操作样式类
    /// </summary>
    public string? ButtonClass { get; set; }

    /// <summary>
    /// 操作图标
    /// </summary>
    public string? Icon { get; set; }

    /// <summary>
    /// 确认提示
    /// </summary>
    public string? ConfirmMessage { get; set; }

    /// <summary>
    /// 操作影响的数据区域 ID 列表
    /// </summary>
    public List<string> AffectsSections { get; set; } = new();
}

/// <summary>
/// 页面 UI 定义
/// </summary>
public class PageUiDefinition
{
    /// <summary>
    /// 布局主题
    /// </summary>
    public string? Theme { get; set; }

    /// <summary>
    /// 标签定义
    /// </summary>
    public Dictionary<string, string>? Labels { get; set; }

    /// <summary>
    /// 样式定义
    /// </summary>
    public Dictionary<string, string>? Styles { get; set; }

    /// <summary>
    /// 区域布局配置
    /// </summary>
    public List<SectionLayoutDefinition> Layout { get; set; } = new();
}

/// <summary>
/// 区域布局定义
/// </summary>
public class SectionLayoutDefinition
{
    /// <summary>
    /// 区域 ID
    /// </summary>
    public string SectionId { get; set; } = "";

    /// <summary>
    /// 网格列数（1-12）
    /// </summary>
    public int Columns { get; set; } = 12;

    /// <summary>
    /// 网格行数
    /// </summary>
    public int Rows { get; set; } = 1;

    /// <summary>
    /// 是否占满整行
    /// </summary>
    public bool FullWidth { get; set; } = false;
}
