using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;

namespace Platform.Infrastructure.Definitions;

/// <summary>
/// CRUD 操作类型
/// </summary>
public enum CrudType
{
    Insert,     // 插入
    Update,     // 更新
    Upsert,     // 插入或更新
    Sync,       // 同步（删除不存在的，更新存在的，插入新的）
    Delete,     // 删除
    Skip        // 跳过
}

/// <summary>
/// 数据加载策略
/// </summary>
public enum LoadStrategy
{
    Parallel,       // 并行加载
    Sequential,     // 串行加载
    SingleQuery     // 单查询加载
}

/// <summary>
/// 参数来源
/// </summary>
public enum ParameterSource
{
    QueryString,    // URL 查询参数
    Form,           // 表单数据
    Route,          // 路由参数
    Constant,       // 常量
    GeneratedId     // 上一步生成的 ID
}

/// <summary>
/// 数据源配置
/// </summary>
public class DataSourceConfig
{
    public string Id { get; set; } = string.Empty;
    public string Type { get; set; } = "table"; // table | query
    public string? Table { get; set; }
    public string? Query { get; set; }
    public string? PrimaryKey { get; set; }
    public string? ForeignKey { get; set; }
    public string? Where { get; set; }
    public bool LoadAll { get; set; } = false;
    public List<ParameterConfig>? Parameters { get; set; }
}

/// <summary>
/// 参数配置
/// </summary>
public class ParameterConfig
{
    public string Name { get; set; } = string.Empty;
    public ParameterSource Source { get; set; } = ParameterSource.QueryString;
    public string? Field { get; set; }
    public string? FromTable { get; set; }
    public object? Constant { get; set; }
    public object? Default { get; set; }
}

/// <summary>
/// 数据加载配置
/// </summary>
public class DataLoadingConfig
{
    public LoadStrategy Strategy { get; set; } = LoadStrategy.Parallel;
    public int TimeoutMs { get; set; } = 5000;
    public List<DataSourceConfig>? Sources { get; set; }
}

/// <summary>
/// 字段映射配置
/// </summary>
public class FieldMappingConfig
{
    public string TargetField { get; set; } = string.Empty;
    public string Source { get; set; } = "form"; // form | generated_id | constant
    public string? Field { get; set; }
    public string? FromTable { get; set; }
    public object? Constant { get; set; }
}

/// <summary>
/// 级联删除配置
/// </summary>
public class CascadeDeleteConfig
{
    public bool Enabled { get; set; } = false;
    public string? MatchField { get; set; }
    public string? Source { get; set; } // 格式：Table.Field
}

/// <summary>
/// 输出配置（获取生成的 ID）
/// </summary>
public class OutputConfig
{
    public string GeneratedId { get; set; } = "Id";
}

/// <summary>
/// 保存项配置
/// </summary>
public class SaveItemConfig
{
    public int Order { get; set; }
    public string Table { get; set; } = string.Empty;
    public CrudType? CrudType { get; set; }
    public string? Condition { get; set; }
    public List<string>? MatchFields { get; set; }
    public Dictionary<string, FieldMappingConfig>? FieldMappings { get; set; }
    public OutputConfig? Output { get; set; }
    public CascadeDeleteConfig? CascadeDelete { get; set; }
}

/// <summary>
/// 事务配置
/// </summary>
public class TransactionConfig
{
    public bool Enabled { get; set; } = true;
    public string IsolationLevel { get; set; } = "ReadCommitted";
}

/// <summary>
/// 钩子配置
/// </summary>
public class HookConfig
{
    public string Type { get; set; } = "notification"; // notification | redirect | script
    public string? Template { get; set; }
    public string? Url { get; set; }
    public int? DelayMs { get; set; }
    public string? Script { get; set; }
}

/// <summary>
/// 钩子集合配置
/// </summary>
public class HooksConfig
{
    public List<HookConfig>? AfterSave { get; set; }
    public List<HookConfig>? BeforeSave { get; set; }
    public List<HookConfig>? AfterDelete { get; set; }
}

/// <summary>
/// 保存配置
/// </summary>
public class SaveConfig
{
    public TransactionConfig Transaction { get; set; } = new();
    public List<SaveItemConfig>? SaveOrder { get; set; }
    public HooksConfig? Hooks { get; set; }
}

/// <summary>
/// 表单区域配置
/// </summary>
public class FormSectionConfig
{
    public string Id { get; set; } = string.Empty;
    public string Title { get; set; } = string.Empty;
    public string Type { get; set; } = "form"; // form | table | editable_table | card
    public string? Table { get; set; }
    public string? Source { get; set; }
    public string? SourceType { get; set; }
    public List<string>? Fields { get; set; }
    public List<string>? Columns { get; set; }
    public bool Editable { get; set; } = true;
    public int PageSize { get; set; } = 10;
}
