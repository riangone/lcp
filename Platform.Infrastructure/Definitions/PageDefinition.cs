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
    /// 多表 CRUD 配置 - 单个表单对应多个表
    /// </summary>
    public MultiTableCrudDefinition? MultiTableCrud { get; set; }

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
/// 多表 CRUD 定义 - 单个表单对应多个表的 CRUD
/// </summary>
public class MultiTableCrudDefinition
{
    /// <summary>
    /// 主表配置
    /// </summary>
    public TableMappingDefinition? MainTable { get; set; }

    /// <summary>
    /// 关联表配置
    /// </summary>
    public List<TableMappingDefinition> RelatedTables { get; set; } = new();

    /// <summary>
    /// 表单字段映射 - 键为表名，值为该表的字段列表
    /// </summary>
    public Dictionary<string, List<FormFieldMappingDefinition>> FormMapping { get; set; } = new();

    /// <summary>
    /// 事务配置
    /// </summary>
    public TransactionDefinition? Transaction { get; set; }

    /// <summary>
    /// 级联操作配置
    /// </summary>
    public CascadeDefinition? Cascade { get; set; }

    /// <summary>
    /// 处理步骤配置
    /// </summary>
    public List<StepDefinition> Steps { get; set; } = new();
}

/// <summary>
/// 处理步骤定义
/// </summary>
public class StepDefinition
{
    /// <summary>
    /// 步骤唯一标识
    /// </summary>
    public string Id { get; set; } = "";

    /// <summary>
    /// 步骤名称
    /// </summary>
    public string Name { get; set; } = "";

    /// <summary>
    /// 触发时机：before_save, after_save, before_delete, after_delete, on_validate
    /// </summary>
    public string Trigger { get; set; } = "before_save";

    /// <summary>
    /// 步骤类型：script, api, notification, custom
    /// </summary>
    public string Type { get; set; } = "script";

    /// <summary>
    /// 是否停止后续步骤（如果失败）
    /// </summary>
    public bool StopOnError { get; set; } = true;

    /// <summary>
    /// 脚本配置（当 Type=script 时）
    /// </summary>
    public ScriptConfig? Script { get; set; }

    /// <summary>
    /// API 配置（当 Type=api 时）
    /// </summary>
    public ApiConfig? Api { get; set; }

    /// <summary>
    /// 通知配置（当 Type=notification 时）
    /// </summary>
    public NotificationConfig? Notification { get; set; }

    /// <summary>
    /// 自定义配置
    /// </summary>
    public Dictionary<string, object>? Custom { get; set; }
}

/// <summary>
/// 脚本配置
/// </summary>
public class ScriptConfig
{
    /// <summary>
    /// 脚本语言：csharp, javascript, python
    /// </summary>
    public string Language { get; set; } = "csharp";

    /// <summary>
    /// 脚本内容或文件路径
    /// </summary>
    public string Content { get; set; } = "";

    /// <summary>
    /// 脚本参数
    /// </summary>
    public Dictionary<string, object>? Parameters { get; set; }
}

/// <summary>
/// API 配置
/// </summary>
public class ApiConfig
{
    /// <summary>
    /// API URL
    /// </summary>
    public string Url { get; set; } = "";

    /// <summary>
    /// HTTP 方法
    /// </summary>
    public string Method { get; set; } = "POST";

    /// <summary>
    /// 请求头
    /// </summary>
    public Dictionary<string, string>? Headers { get; set; }

    /// <summary>
    /// 请求体模板
    /// </summary>
    public string? BodyTemplate { get; set; }
}

/// <summary>
/// 通知配置
/// </summary>
public class NotificationConfig
{
    /// <summary>
    /// 通知类型：email, sms, webhook
    /// </summary>
    public string Type { get; set; } = "email";

    /// <summary>
    /// 接收者
    /// </summary>
    public List<string> Recipients { get; set; } = new();

    /// <summary>
    /// 主题
    /// </summary>
    public string? Subject { get; set; }

    /// <summary>
    /// 消息模板
    /// </summary>
    public string? MessageTemplate { get; set; }
}

/// <summary>
/// 事务配置定义
/// </summary>
public class TransactionDefinition
{
    /// <summary>
    /// 是否启用事务（默认 true）
    /// </summary>
    public bool Enabled { get; set; } = true;

    /// <summary>
    /// 事务隔离级别
    /// </summary>
    public string IsolationLevel { get; set; } = "ReadCommitted";

    /// <summary>
    /// 事务超时（秒）
    /// </summary>
    public int TimeoutSeconds { get; set; } = 30;
}

/// <summary>
/// 级联操作定义
/// </summary>
public class CascadeDefinition
{
    /// <summary>
    /// 删除时级联
    /// </summary>
    public bool OnDelete { get; set; } = true;

    /// <summary>
    /// 更新时级联
    /// </summary>
    public bool OnUpdate { get; set; } = false;
}

/// <summary>
/// 表映射定义
/// </summary>
public class TableMappingDefinition
{
    /// <summary>
    /// 表名
    /// </summary>
    public string Table { get; set; } = "";

    /// <summary>
    /// 主键字段
    /// </summary>
    public string? PrimaryKey { get; set; }

    /// <summary>
    /// 外键字段（用于关联主表）
    /// </summary>
    public string? ForeignKey { get; set; }

    /// <summary>
    /// 关联类型：many(一对多) / one(一对一)
    /// </summary>
    public string Type { get; set; } = "many";
}

/// <summary>
/// 表单字段映射定义
/// </summary>
public class FormFieldMappingDefinition
{
    /// <summary>
    /// 字段名
    /// </summary>
    public string Field { get; set; } = "";

    /// <summary>
    /// 显示标签
    /// </summary>
    public string Label { get; set; } = "";

    /// <summary>
    /// 字段类型：text/number/decimal/date/select 等
    /// </summary>
    public string Type { get; set; } = "text";

    /// <summary>
    /// 是否必填
    /// </summary>
    public bool Required { get; set; }

    /// <summary>
    /// 默认值
    /// </summary>
    public string? Default { get; set; }

    /// <summary>
    /// 最大长度
    /// </summary>
    public int? MaxLength { get; set; }

    /// <summary>
    /// 选项（用于 select 类型）
    /// </summary>
    public Dictionary<string, string>? Options { get; set; }
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
