# 多表表单功能文档

## 概述

多表表单功能允许在一个页面中管理多个关联表的数据，支持：
- 主表与明细表联动
- 并行数据加载
- 事务性保存
- 字段映射和自动生成

## YAML 配置

### 基本结构

```yaml
pages:
  OrderCustomer:
    title: Order & Customer
    main_table: Customer
    
    data_loading:
      strategy: parallel
      sources:
        - id: customer_data
          type: table
          table: Customer
          where: "CustomerId = @CustomerId"
        
        - id: invoice_data
          type: table
          table: Invoice
          where: "CustomerId = @CustomerId"
    
    save_config:
      transaction:
        enabled: true
      save_order:
        - order: 1
          table: Customer
          crud_type: upsert
          match_fields: [CustomerId]
        - order: 2
          table: Invoice
          crud_type: insert
          field_mappings:
            CustomerId:
              source: generated_id
              from_table: Customer
              field: CustomerId
```

### 数据加载配置

| 属性 | 说明 | 可选值 |
|------|------|--------|
| `strategy` | 加载策略 | `parallel`（并行）, `sequential`（串行） |
| `sources` | 数据源列表 | - |
| `sources[].id` | 数据源 ID | - |
| `sources[].type` | 数据源类型 | `table` |
| `sources[].table` | 表名 | - |
| `sources[].where` | WHERE 条件 | 支持 `@CustomerId` 等参数 |

### 保存配置

| 属性 | 说明 | 可选值 |
|------|------|--------|
| `transaction.enabled` | 是否启用事务 | `true`, `false` |
| `save_order` | 保存顺序配置 | - |
| `save_order[].order` | 保存顺序 | 数字，小的先保存 |
| `save_order[].table` | 表名 | - |
| `save_order[].crud_type` | CRUD 类型 | `insert`, `update`, `upsert` |
| `save_order[].match_fields` | 匹配字段（用于 update/upsert） | 字段列表 |
| `save_order[].field_mappings` | 字段映射 | - |

### 字段映射

```yaml
field_mappings:
  CustomerId:
    source: generated_id        # 从生成的 ID 获取
    from_table: Customer        # 从 Customer 表
    field: CustomerId           # 获取 CustomerId 字段
  
  OrderDate:
    source: current_timestamp   # 当前时间戳
  
  Status:
    source: constant            # 常量值
    value: "Pending"            # 常量值
```

## API 端点

| 端点 | 方法 | 说明 |
|------|------|------|
| `/api/page/{pageName}/load` | GET | 加载多表数据 |
| `/api/page/{pageName}/save` | POST | 保存多表数据 |

## 使用示例

### 1. 创建页面定义

在 `Projects/{project}/pages/` 目录下创建 YAML 文件。

### 2. 访问页面

访问：`/page/{pageName}?project={project}`

### 3. 加载数据

```javascript
const response = await fetch(`/api/page/OrderCustomer/load?CustomerId=1`);
const data = await response.json();
// data.customer_data, data.invoice_data
```

### 4. 保存数据

```javascript
const formData = {
  customer_data: {
    FirstName: 'John',
    LastName: 'Doe',
    Email: 'john@example.com'
  },
  invoice_data: [
    {
      InvoiceDate: '2024-01-15',
      Total: 100.00
    }
  ]
};

const response = await fetch(`/api/page/OrderCustomer/save`, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(formData)
});
```

## 注意事项

1. **事务处理**：启用事务后，所有表保存失败会回滚
2. **字段映射**：确保 `from_table` 在 `save_order` 中排在前面
3. **主键处理**：使用 `generated_id` 自动获取刚插入的主键
4. **并发控制**：并行加载时注意数据库连接数限制

## 相关文件

- `Platform.Infrastructure/Services/PageDataLoader.cs` - 数据加载器
- `Platform.Infrastructure/Services/MultiTableSaver.cs` - 多表保存器
- `Platform.Api/Controllers/PageController.cs` - 页面控制器
