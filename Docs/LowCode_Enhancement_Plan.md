# 低代码框架增强计划

## 📋 核心理念

**通过 YAML 定义驱动一切，尽可能不写代码、不生成代码**

当前的 GenericApiController 和 DynamicRepository 已经实现了这个理念：
- 一个控制器处理所有模型
- 运行时读取 YAML 定义
- 动态执行 CRUD 操作
- 无需为每个模型创建代码文件

## ✅ 现有低代码能力

### 1. 数据模型定义 (YAML)

```yaml
models:
  Artist:
    table: Artist
    primary_key: ArtistId
    list:
      columns: [ArtistId, Name]
      filters:
        Name:
          label: Name
          type: like
    form:
      fields:
        Name:
          type: text
          max_length: 120
    properties:
      ArtistId: { type: int }
      Name: { type: string }
```

### 2. 自动生成的功能

| 功能 | 实现方式 | 状态 |
|------|----------|------|
| CRUD API | GenericApiController | ✅ 已实现 |
| 列表页面 | UiController + List.cshtml | ✅ 已实现 |
| 表单模态框 | FormModal.cshtml | ✅ 已实现 |
| 分页 | DynamicRepository.GetPagedAsync | ✅ 已实现 |
| 过滤 | DynamicRepository + YAML 配置 | ✅ 已实现 |
| 验证 | ModelBinder | ✅ 已实现 |
| 多语言 UI | Ui.Labels.En/Zh | ✅ 已实现 |
| 多表关联视图 | ModelDefinition.Query | ✅ 已实现 |
| 只读模型 | ModelDefinition.ReadOnly | ✅ 已实现 |
| 多表表单 | PageDefinition + MultiTableCrud | ✅ 已实现 |

### 3. 运行时驱动架构

```
┌─────────────────────────────────────────────────────────┐
│                    HTTP Request                          │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              GenericApiController                       │
│           (一个控制器处理所有模型)                        │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              AppDefinitions (YAML 加载)                  │
│           - Models                                      │
│           - Pages                                       │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              DynamicRepository                          │
│           (动态构建 SQL 执行 CRUD)                        │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                   SQLite Database                       │
└─────────────────────────────────────────────────────────┘
```

## 🚀 增强方向

### 方向 1：增强 YAML 定义能力

#### 1.1 业务规则验证（通过 YAML 配置）

当前需要写代码：
```csharp
// 需要写验证器类
public static class ArtistValidator { ... }
```

增强后通过 YAML 配置：
```yaml
models:
  Artist:
    validations:
      - name: NameRequired
        field: Name
        rule: not_empty
        message: "Name is required"
      
      - name: NameLength
        field: Name
        rule: length_between
        min: 2
        max: 120
        message: "Name must be between 2 and 120 characters"
      
      - name: UniqueName
        field: Name
        rule: unique
        message: "Name must be unique"
```

**实现**：扩展 ModelBinder，在运行时读取 validation 配置并执行

#### 1.2 计算字段（通过表达式配置）

```yaml
models:
  Invoice:
    computed_fields:
      TotalWithTax:
        expression: "Total * 1.1"
        type: decimal
        label: "Total with Tax"
      
      CustomerFullName:
        expression: "FirstName + ' ' + LastName"
        type: string
        label: "Full Name"
```

**实现**：扩展 DynamicRepository，在查询结果上应用计算字段

#### 1.3 级联操作（通过 YAML 配置）

```yaml
models:
  Customer:
    cascade:
      on_delete:
        - table: Invoice
          field: CustomerId
          action: delete  # 或 set_null, restrict
```

**实现**：扩展 DynamicRepository.DeleteAsync，读取 cascade 配置

#### 1.4 审计字段（通过 YAML 配置）

```yaml
models:
  Product:
    audit:
      created_by: true
      created_at: true
      modified_by: true
      modified_at: true
```

**实现**：扩展 ModelBinder 和 DynamicRepository，自动填充审计字段

### 方向 2：增强 UI 动态性

#### 2.1 动态表单布局

当前需要修改 Razor 视图，增强后通过 YAML：

```yaml
models:
  Product:
    form:
      layout:
        - type: section
          title: "Basic Info"
          columns: 2
          fields: [Name, Category]
        
        - type: section
          title: "Pricing"
          columns: 1
          fields: [Price, Cost]
        
        - type: conditional
          field: Category
          equals: "electronics"
          show:
            fields: [WarrantyPeriod]
```

#### 2.2 动态列表操作

```yaml
models:
  Order:
    list:
      actions:
        - name: Approve
          icon: ✓
          style: success
          action_type: api
          api:
            method: POST
            url: "/api/order/{id}/approve"
          confirm: "Approve this order?"
          refresh: true
        
        - name: Export
          icon: 📥
          style: secondary
          action_type: download
          url: "/api/order/{id}/export"
```

### 方向 3：增强多表表单能力

#### 3.1 更强大的保存策略

```yaml
pages:
  OrderCustomer:
    save_config:
      save_order:
        - order: 1
          table: Customer
          crud_type: upsert
          match_fields: [Email]
        
        - order: 2
          table: Order
          crud_type: insert
          field_mappings:
            CustomerId:
              source: generated_id
              from_table: Customer
              field: CustomerId
```

**当前已实现大部分，需要完善**

### 方向 4：工作流和自动化

#### 4.1 YAML 定义的工作流

```yaml
models:
  Order:
    workflow:
      states: [Pending, Approved, Shipped, Delivered, Cancelled]
      initial_state: Pending
      
      transitions:
        - from: Pending
          to: Approved
          action: approve
          trigger: api
          
        - from: Approved
          to: Shipped
          action: ship
          trigger: api
          
        - from: Pending
          to: Cancelled
          action: cancel
          trigger: api
          condition: "DaysSinceCreated > 7"
      
      on_enter:
        Shipped:
          - type: notification
            to: customer
            template: "Your order has been shipped"
```

**实现**：创建工作流引擎，运行时读取 YAML 执行

### 方向 5：权限控制

#### 5.1 YAML 定义的权限

```yaml
models:
  Product:
    permissions:
      read: [admin, user]
      create: [admin]
      update: [admin]
      delete: [admin]
      
      field_permissions:
        Cost:
          read: [admin]  # 只有 admin 能看到成本
        Price:
          update: [admin, manager]  # 只有 admin 和经理能改价格
```

**实现**：创建权限中间件，运行时检查

## 📦 实现优先级

### P0 - 核心增强（立即实现）

1. **业务规则验证** - 扩展现有 ModelBinder
2. **完善多表表单** - 已有基础，需要测试和文档
3. **权限控制基础** - 简单的角色权限

### P1 - 重要增强（近期实现）

4. **计算字段** - 扩展查询结果处理
5. **级联操作** - 扩展 CRUD 方法
6. **审计字段** - 自动填充

### P2 - 高级功能（后续实现）

7. **动态表单布局** - 需要修改视图渲染
8. **工作流引擎** - 新的子系统
9. **动态列表操作** - UI 增强

## 🎯 关键设计原则

1. **运行时驱动** - 不要生成代码，在运行时读取 YAML 执行
2. **一个控制器处理所有** - 不要为每个模型创建控制器
3. **配置优于编码** - 能通过 YAML 配置的就不写代码
4. **渐进式增强** - 保持现有功能，逐步增强

## 📝 与代码生成的对比

| 方案 | 优点 | 缺点 |
|------|------|------|
| **代码生成** | 生成的代码可单独优化 | 生成的文件多，难以维护 |
| **运行时驱动** | 无需生成文件，修改 YAML 即可 | 性能略低（但可接受） |

**我们的选择：运行时驱动为主，必要时生成代码**

## 🔧 下一步行动

1. **审查现有 YAML 定义** - 确保所有功能都能通过 YAML 配置
2. **扩展现有组件** - ModelBinder, DynamicRepository
3. **添加新特性** - 按优先级实现 P0、P1 功能
4. **完善文档** - YAML 配置参考文档
