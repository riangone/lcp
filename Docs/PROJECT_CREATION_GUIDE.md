# 低代码平台 - 项目创建指南

## 📋 概述

本低代码平台允许你**只通过 YAML 定义**即可创建不同的项目，无需编写代码。平台采用运行时驱动架构，根据 YAML 配置动态生成 CRUD API 和 UI 界面。

## 🚀 快速开始

### 1. 创建新项目

#### 步骤 1: 创建 YAML 定义文件

在 `Definitions/` 目录下创建 `{project}_app.yaml` 文件：

```yaml
# Definitions/myapp_app.yaml
models:
  MyModel:
    table: MyModel
    primary_key: Id

    ui:
      labels:
        en:
          title: My Models
        zh:
          title: 我的模型

    list:
      columns: [Id, Name, Status]
      filters:
        Name:
          label: Name
          type: like
        Status:
          label: Status
          type: select
          options:
            active: Active
            inactive: Inactive

    form:
      fields:
        Name:
          label: Name
          type: text
          required: true
          max_length: 200
        Status:
          label: Status
          type: select
          required: true
          options:
            active: Active
            inactive: Inactive
          default: active

    properties:
      Id:
        type: int
      Name:
        type: string
        required: true
      Status:
        type: string
        required: true
```

#### 步骤 2: 创建数据库表结构

在 `Definitions/` 目录下创建 `{project}_schema.sql` 文件：

```sql
-- Definitions/myapp_schema.sql
CREATE TABLE IF NOT EXISTS MyModel (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL,
    Status TEXT NOT NULL DEFAULT 'active',
    CreatedAt TEXT DEFAULT (datetime('now'))
);

-- 插入示例数据
INSERT INTO MyModel (Id, Name, Status) VALUES
(1, 'Item 1', 'active'),
(2, 'Item 2', 'inactive');
```

#### 步骤 3: 初始化数据库

```bash
# 创建数据库
sqlite3 myapp.db < Definitions/myapp_schema.sql
```

#### 步骤 4: 启动应用

```bash
# 设置环境变量
export LCP_PROJECT=myapp
export LCP_DB_PATH=/path/to/myapp.db

# 启动应用
dotnet run --project Platform.Api
```

#### 步骤 5: 访问应用

打开浏览器访问：http://localhost:5267

## 📝 TODO 项目示例

平台已包含完整的 TODO 项目示例，演示如何创建任务管理系统。

### 文件位置

- YAML 定义：`Definitions/todo_app.yaml`
- 数据库脚本：`Definitions/todo_schema.sql`
- 初始化脚本：`init_todo_project.sh`

### 启动 TODO 项目

```bash
# 方法 1: 使用初始化脚本
./init_todo_project.sh

# 方法 2: 手动初始化
sqlite3 todo.db < Definitions/todo_schema.sql

# 启动应用
export LCP_PROJECT=todo
export LCP_DB_PATH=$(pwd)/todo.db
dotnet run --project Platform.Api
```

### 访问 TODO 项目

- 首页：http://localhost:5267
- 任务列表：http://localhost:5267/ui/Task
- 项目列表：http://localhost:5267/ui/Project
- 项目任务视图：http://localhost:5267/ui/TaskWithProject
- 项目统计：http://localhost:5267/ui/ProjectStats

## 📐 YAML 配置详解

### 模型定义结构

```yaml
models:
  ModelName:
    # 数据库表名
    table: table_name
    
    # 主键字段
    primary_key: Id
    
    # 是否只读（用于视图/查询）
    read_only: true/false
    
    # 自定义查询（用于多表关联）
    query: |
      SELECT ...
    
    # UI 配置
    ui:
      layout:
        theme: default
        grid_columns: 2
      labels:
        en: { ... }
        zh: { ... }
      styles:
        card_class: "card-name"
        button_class: "btn btn-primary"
    
    # 列表配置
    list:
      columns: [Id, Name, ...]
      filters:
        FieldName:
          label: Field Label
          type: like/eq/select
          options: { ... }  # select 类型需要
    
    # 表单配置
    form:
      title: Model Name
      fields:
        FieldName:
          label: Field Label
          type: text/textarea/number/date/select
          required: true/false
          max_length: 200
          options: { ... }  # select 类型需要
          default: value
    
    # 属性定义
    properties:
      FieldName:
        type: int/string/decimal/date/datetime
        required: true/false
```

### 字段类型

| 类型 | 说明 | HTML 输入 |
|------|------|----------|
| `text` | 文本 | `<input type="text">` |
| `textarea` | 多行文本 | `<textarea>` |
| `number` | 数字 | `<input type="number">` |
| `decimal` | 小数 | `<input type="number" step="0.01">` |
| `date` | 日期 | `<input type="date">` |
| `select` | 下拉选择 | `<select>` |

### 过滤类型

| 类型 | 说明 | SQL |
|------|------|-----|
| `like` | 模糊匹配 | `LIKE '%value%'` |
| `eq` | 精确匹配 | `= value` |
| `select` | 下拉选择 | `= value` |

## 🔄 项目切换

平台支持通过环境变量快速切换项目：

```bash
# 切换到 TODO 项目
export LCP_PROJECT=todo
export LCP_DB_PATH=/path/to/todo.db
dotnet run --project Platform.Api

# 切换到默认项目
export LCP_PROJECT=app
export LCP_DB_PATH=/path/to/app.db
dotnet run --project Platform.Api

# 切换到自定义项目
export LCP_PROJECT=myapp
export LCP_DB_PATH=/path/to/myapp.db
dotnet run --project Platform.Api
```

## 📊 自动生成的功能

定义 YAML 后，平台自动生成：

### API 端点

| 方法 | 端点 | 说明 |
|------|------|------|
| `GET` | `/api/{model}` | 获取所有数据 |
| `POST` | `/api/{model}` | 创建数据 |
| `PUT` | `/api/{model}/{id}` | 更新数据 |
| `DELETE` | `/api/{model}/{id}` | 删除数据 |

### UI 页面

| 端点 | 说明 |
|------|------|
| `/ui/{model}` | 列表页面（分页、过滤、排序） |
| `/ui/{model}/create` | 创建表单 |
| `/ui/{model}/edit/{id}` | 编辑表单 |
| `/ui/{model}/details/{id}` | 详情页面 |

### 功能特性

- ✅ 分页支持
- ✅ 过滤和搜索
- ✅ 列排序
- ✅ 表单验证
- ✅ 多语言支持（中/英）
- ✅ 响应式设计
- ✅ HTMX 无刷新交互

## 🎯 高级功能

### 多表关联视图

使用 `query` 属性定义多表关联：

```yaml
models:
  OrderWithCustomer:
    query: |
      SELECT
        o.Id,
        o.OrderDate,
        o.Total,
        c.Name as CustomerName,
        c.Email
      FROM [Order] o
      JOIN Customer c ON c.Id = o.CustomerId
    primary_key: Id
    read_only: true

    list:
      columns: [Id, OrderDate, CustomerName, Total]
      filters:
        CustomerName:
          label: Customer
          type: like

    properties:
      Id: { type: int }
      OrderDate: { type: date }
      CustomerName: { type: string }
      Total: { type: decimal }
```

### 统计视图

使用 SQL 聚合函数创建统计视图：

```yaml
models:
  ProductStats:
    query: |
      SELECT
        Category,
        COUNT(*) as TotalProducts,
        AVG(Price) as AvgPrice,
        MIN(Price) as MinPrice,
        MAX(Price) as MaxPrice
      FROM Product
      GROUP BY Category
    primary_key: Category
    read_only: true

    list:
      columns: [Category, TotalProducts, AvgPrice, MinPrice, MaxPrice]

    properties:
      Category: { type: string }
      TotalProducts: { type: int }
      AvgPrice: { type: decimal }
      MinPrice: { type: decimal }
      MaxPrice: { type: decimal }
```

## 🛠️ 最佳实践

### 1. 命名规范

- YAML 文件名：`{project}_app.yaml`
- 表名：使用 PascalCase（如 `Task`, `Project`）
- 字段名：使用 PascalCase（如 `Id`, `Title`, `DueDate`）

### 2. 数据库设计

- 每个表必须有主键
- 使用 `INTEGER PRIMARY KEY AUTOINCREMENT` 自增主键
- 添加适当的索引提高查询性能
- 使用外键约束保持数据完整性

### 3. YAML 配置

- 为所有字段提供多语言标签
- 为 select 字段定义明确的选项
- 为必填字段设置 `required: true`
- 为文本字段设置合理的 `max_length`

### 4. 示例数据

- 在 schema.sql 中包含示例数据
- 示例数据应覆盖各种场景
- 使用 `INSERT OR IGNORE` 避免重复插入

## 📚 参考示例

### TODO 项目完整示例

查看以下文件了解完整的 TODO 项目配置：

- `Definitions/todo_app.yaml` - TODO 项目 YAML 定义
- `Definitions/todo_schema.sql` - TODO 项目数据库结构
- `init_todo_project.sh` - TODO 项目初始化脚本

### 其他示例

查看 `Definitions/app.yaml` 中的 Chinook 数据库示例，包含：
- Artist（艺术家）
- Album（专辑）
- Track（音轨）
- Employee（员工）
- Invoice（发票）
- InvoiceWithCustomer（发票客户视图）
- ProjectStats（项目统计）

## ❓ 常见问题

### Q: 如何添加新字段？

A: 在 YAML 的 `properties` 中添加字段定义，然后在数据库中执行 `ALTER TABLE` 添加列：

```sql
ALTER TABLE Task ADD COLUMN NewColumn TEXT;
```

### Q: 如何修改现有字段？

A: 修改 YAML 配置后重启应用即可。如果涉及数据库结构变更，需要执行相应的 SQL。

### Q: 如何删除项目？

A: 删除对应的 YAML 文件和数据库文件即可：

```bash
rm Definitions/myapp_app.yaml
rm myapp.db
```

### Q: 支持哪些数据库？

A: 目前主要支持 SQLite。如需支持其他数据库，需要修改 `DbConnectionFactory.cs`。

### Q: 如何自定义 UI？

A: 可以修改 Razor 视图文件：
- `Views/Ui/List.cshtml` - 列表页
- `Views/Ui/FormModal.cshtml` - 表单页
- `Views/Shared/_Layout.cshtml` - 布局页

## 🔗 相关资源

- [ASP.NET Core 文档](https://docs.microsoft.com/aspnet/core)
- [Dapper ORM](https://github.com/DapperLib/Dapper)
- [HTMX](https://htmx.org)
- [YamlDotNet](https://github.com/aaubry/YamlDotNet)
