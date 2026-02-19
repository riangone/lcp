namespace Platform.Infrastructure.Definitions
{
    public class ModelDefinition
    {
        public string Table { get; set; } = "";
        public string? Query { get; set; }
        public bool ReadOnly { get; set; }
        public string PrimaryKey { get; set; } = "";

        public UiDefinition? Ui { get; set; }
        public ListDefinition? List { get; set; }
        public FormDefinition? Form { get; set; }
        public CustomViewDefinition? CustomView { get; set; }

        public Dictionary<string, PropertyDefinition> Properties { get; set; } = new();

        public List<string> Columns => Properties.Keys.ToList();

        public bool IsReadOnly => ReadOnly || !string.IsNullOrWhiteSpace(Query);
    }

    /// <summary>
    /// 专用视图定义 - 用于定义模型的专用 UI 视图
    /// </summary>
    public class CustomViewDefinition
    {
        /// <summary>
        /// 是否启用专用视图
        /// </summary>
        public bool Enabled { get; set; } = false;

        /// <summary>
        /// 专用视图类型：custom（完全自定义）/ enhanced（增强通用）
        /// </summary>
        public string Type { get; set; } = "custom";

        /// <summary>
        /// 默认 UI 模式：custom（专用 UI）/ generic（通用 UI）
        /// 如果为 custom，默认访问时使用专用 UI
        /// 如果为 generic，默认访问时使用通用 UI，需要 ?ui=custom 才使用专用 UI
        /// </summary>
        public string DefaultUiMode { get; set; } = "custom";

        /// <summary>
        /// 专用视图模板路径（相对于项目目录）
        /// </summary>
        public string? Template { get; set; }

        /// <summary>
        /// 列表视图模板
        /// </summary>
        public string? ListTemplate { get; set; }

        /// <summary>
        /// 表单视图模板
        /// </summary>
        public string? FormTemplate { get; set; }

        /// <summary>
        /// 详情视图模板
        /// </summary>
        public string? DetailsTemplate { get; set; }

        /// <summary>
        /// 视图样式配置
        /// </summary>
        public CustomViewStyle? Style { get; set; }
    }

    /// <summary>
    /// 专用视图样式配置
    /// </summary>
    public class CustomViewStyle
    {
        /// <summary>
        /// 布局风格：card（卡片）/ timeline（时间线）/ grid（网格）/ list（列表）
        /// </summary>
        public string Layout { get; set; } = "card";

        /// <summary>
        /// 主题色
        /// </summary>
        public string? ThemeColor { get; set; }

        /// <summary>
        /// 自定义 CSS 文件
        /// </summary>
        public string? CustomCss { get; set; }

        /// <summary>
        /// 自定义 JS 文件
        /// </summary>
        public string? CustomJs { get; set; }

        /// <summary>
        /// 每页显示数量
        /// </summary>
        public int PageSize { get; set; } = 10;

        /// <summary>
        /// 是否显示侧边栏
        /// </summary>
        public bool ShowSidebar { get; set; } = true;

        /// <summary>
        /// 是否显示统计信息
        /// </summary>
        public bool ShowStats { get; set; } = false;
    }
}
