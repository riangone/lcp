# 多表表单功能文档

## 概述

多表表单功能允许在单个页面上管理多个相关或不相关表的数据，支持复杂的业务场景，如：
- 订单 + 客户 + 订单明细
- 主表 + 多个不关联的查找表
- 跨多个表的事务性保存

## 配置结构

### 1. 数据加载配置 (DataLoadingConfig)

```yaml
data_loading:
  strategy: parallel  # parallel | sequential | single_query
  timeout_ms: 5000
  
  sources:
    - id: customer_data
      type: table     # table | query
      table: Customer
      where: "CustomerId = @CustomerId"
      parameters:
        - name: CustomerId
          source: QueryString  # QueryString | Form | Route | Constant | GeneratedId
          default: null
```

### 2. 保存配置 (SaveConfig)

```yaml
save_config:
  transaction:
    enabled: true
    isolation_level: ReadCommitted
    
  save_order:
    - order: 1
      table: Customer
      crud_type: upsert  # insert | update | upsert | sync | delete | skip
      match_fields:
        - CustomerId
      condition: "data.FirstName != null"
      field_mappings:
        CustomerId:
          source: generated_id
          from_table: Customer
          field: CustomerId
      output:
        generated_id: CustomerId
      cascade_delete:
        enabled: true
        match_field: InvoiceId
        source: Invoice.InvoiceId
```

### 3. CRUD 类型说明

| 类型 | 说明 |
|------|------|
| `insert` | 插入新记录 |
| `update` | 更新现有记录 |
| `upsert` | 存在则更新，不存在则插入 |
| `sync` | 同步（删除不存在的，更新存在的，插入新的） |
| `delete` | 删除记录 |
| `skip` | 跳过不处理 |

### 4. 字段映射源

| 源类型 | 说明 |
|--------|------|
| `form` | 从表单数据获取 |
| `generated_id` | 从上一步生成的 ID 获取 |
| `constant` | 使用固定值 |

## API 端点

### 加载数据
```
GET /api/multi-table/{pageName}/load?CustomerId=1&InvoiceId=100
```

响应：
```json
{
  "success": true,
  "data": {
    "customer_data": { "CustomerId": 1, "FirstName": "John", ... },
    "invoice_data": { "InvoiceId": 100, "Total": 99.99, ... },
    "invoice_lines": [...],
    "employees": [...]
  }
}
```

### 保存数据
```
POST /api/multi-table/{pageName}/save
Content-Type: application/x-www-form-urlencoded

CustomerId=1&FirstName=John&...
```

响应：
```json
{
  "success": true,
  "ids": {
    "Customer": 1,
    "Invoice": 100
  }
}
```

## 使用示例

### 示例 1：新建订单（客户已存在）

```yaml
save_config:
  save_order:
    - order: 1
      table: Invoice
      crud_type: insert
    - order: 2
      table: InvoiceLine
      crud_type: sync
```

### 示例 2：注册客户并下单

```yaml
save_config:
  save_order:
    - order: 1
      table: Customer
      crud_type: insert
      output:
        generated_id: CustomerId
    - order: 2
      table: Invoice
      crud_type: insert
      field_mappings:
        CustomerId:
          source: generated_id
          from_table: Customer
          field: CustomerId
    - order: 3
      table: InvoiceLine
      crud_type: sync
```

### 示例 3：更新订单（不修改客户）

```yaml
save_config:
  save_order:
    - order: 1
      table: Customer
      crud_type: skip
    - order: 2
      table: Invoice
      crud_type: update
    - order: 3
      table: InvoiceLine
      crud_type: sync
```

## 最佳实践

1. **保存顺序**：有外键依赖的表先保存（如 Customer -> Invoice -> InvoiceLine）
2. **事务使用**：涉及多个表的保存操作应启用事务
3. **字段映射**：使用 `field_mappings` 处理表之间的 ID 引用
4. **条件执行**：使用 `condition` 控制何时执行特定表的保存
5. **同步模式**：对于明细表，使用 `sync` 模式自动处理增删改

## 注意事项

1. 确保 `match_fields` 配置正确，否则 upsert 操作可能无法正常工作
2. `sync` 模式会删除不存在的记录，使用时需谨慎
3. 字段映射的 `from_table` 必须与 `save_order` 中的表名一致
4. 并行加载时，数据源之间不能有依赖关系

## 测试

访问示例页面：
```
http://localhost:5267/page/OrderCustomer?CustomerId=1&InvoiceId=100
```
