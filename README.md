# LowCode Platform - UI & CRUD 实现

## 🎯 项目概述

这是一个基于 .NET 10 的低代码平台，能够根据 YAML 定义自动生成：
- ✅ RESTful CRUD API
- ✅ 动态 Web UI（带实时更新）
- ✅ 数据验证和表单支持
- ✅ 分页、过滤、搜索功能

## 🏗️ 架构

```
Platform.Api/              # ASP.NET Core Web 应用
  ├── Controllers/
  │   ├── GenericApiController.cs   # 通用 CRUD API
  │   └── UiController.cs           # UI 页面控制器
  ├── Views/
  │   ├── _ViewStart.cshtml
  │   ├── Shared/
  │   │   ├── _Layout.cshtml        # 主布局
  │   │   ├── _DeleteDialog.cshtml
  │   │   └── _ErrorDialog.cshtml
  │   └── Ui/
  │       ├── List.cshtml           # 列表页面
  │       └── FormModal.cshtml      # 表单模态框
  └── wwwroot/
      └── js/site.js               # HTMX 交互脚本

Platform.Infrastructure/   # 数据访问和业务逻辑
  ├── Definitions/        # 数据结构定义
  ├── Repositories/       # 数据仓储
  ├── YamlLoader.cs       # YAML 加载器
  ├── ModelBinder.cs      # 模型绑定和验证
  └── SqlIdentifier.cs    # SQL 安全工具

Definitions/
  └── app.yaml            # 应用定义文件（关键配置）
```

## 🚀 快速开始

### 1. 查看应用定义

编辑 `Definitions/app.yaml` 定义你的数据模型：

```yaml
models:
  Product:
    table: Product
    primary_key: Id
    
    list:
      columns: [Id, Name, Price, Category]
      filters:
        Name:
          label: Product Name
          type: like
        Category:
          label: Category
          type: select
          options:
            food: Food
            book: Book
    
    form:
      title: Product
      fields:
        Name:
          label: Product Name
          type: text
          required: true
          min_length: 3
          max_length: 100
        Price:
          label: Price
          type: number
          required: true
          min: 0
    
    properties:
      Id:
        type: int
      Name:
        type: string
        required: true
      Price:
        type: decimal
```

### 2. 启动应用

```bash
# 编译
dotnet build

# 初始化数据库（如果需要）
sqlite3 app.db < init_db.sql

# 运行 - 按 F5 或
dotnet run --project Platform.Api
```

访问 http://localhost:5267

### 3. 使用功能

| 功能 | 链接 | 说明 |
|------|------|------|
| **UI 界面** | http://localhost:5267/ui/Product | 查看、创建、编辑、删除数据 |
| **API 文档** | http://localhost:5267/ | OpenAPI 文档（自动跳转） |
| **列表页** | http://localhost:5267/ui/{model} | 分页数据列表 |
| **创建** | 点击 "➕ New" 按钮 | 打开表单模态框 |
| **编辑** | 点击 "Edit" 按钮 | 编辑现有记录 |
| **删除** | 点击 "🗑" 按钮 | 删除记录 |
| **过滤** | 使用表单过滤 | 搜索和过滤数据 |

## 📋 主要功能

### 数据定义 (YAML)

- `table`: SQLite 表名
- `query`: 可选，自定义查询（用于多表关联/聚合/视图替代）
- `read_only`: 可选，设置为 `true` 时禁用新增/编辑/删除
- `primary_key`: 主键字段
- `list.columns`: 列表显示的列
- `list.filters`: 过滤条件配置
- `form.fields`: 表单字段定义
- `properties`: 属性类型映射

### 多表关联/视图示例

```yaml
models:
  InvoiceWithCustomer:
    query: |
      SELECT
        i.InvoiceId,
        i.InvoiceDate,
        i.Total,
        c.FirstName || ' ' || c.LastName AS CustomerName,
        c.Country
      FROM Invoice i
      JOIN Customer c ON c.CustomerId = i.CustomerId
    primary_key: InvoiceId
    read_only: true

    list:
      columns: [InvoiceId, InvoiceDate, CustomerName, Country, Total]
      filters:
        CustomerName:
          label: Customer
          type: like
        Country:
          label: Country
          type: like

    properties:
      InvoiceId: { type: int }
      InvoiceDate: { type: date }
      CustomerName: { type: string }
      Country: { type: string }
      Total: { type: decimal }
```

### 字段类型

| 类型 | HTML 输入 | 说明 |
|------|---------|------|
| `text` | `<input type="text">` | 文本输入 |
| `email` | `<input type="email">` | 邮箱输入 |
| `number` | `<input type="number">` | 数字输入 |
| `decimal` | `<input type="number" step="0.01">` | 小数输入 |
| `date` | `<input type="date">` | 日期选择 |
| `select` | `<select>` | 下拉选择 |

### 验证规则

```yaml
fields:
  Name:
    required: true              # 必填
    min_length: 3               # 最小长度
    max_length: 100             # 最大长度
  
  Price:
    type: number
    min: 0                       # 最小值
    max: 1000000                # 最大值
```

### 过滤类型

| 类型 | 说明 |
|------|------|
| `like` | 模糊匹配 (LIKE '%value%') |
| `eq` | 精确匹配 (= value) |
| `select` | 下拉选择过滤 |

## 🔌 API 端点

```
GET    /api/{model}              # 获取所有数据
POST   /api/{model}              # 创建数据（form-data）
PUT    /api/{model}/{id}         # 更新数据（form-data）
DELETE /api/{model}/{id}         # 删除数据
```

### 示例请求

```bash
# 获取所有产品
curl http://localhost:5267/api/Product

# 创建产品
curl -X POST http://localhost:5267/api/Product \
  -H "X-CSRF-TOKEN: {token}" \
  -F "Name=iPhone 15 Pro" \
  -F "Price=1299.99" \
  -F "Category=electronics"

# 更新产品
curl -X PUT http://localhost:5267/api/Product/1 \
  -H "X-CSRF-TOKEN: {token}" \
  -F "Name=iPhone 15 Pro Max" \
  -F "Price=1399.99"

# 删除产品
curl -X DELETE http://localhost:5267/api/Product/1 \
  -H "X-CSRF-TOKEN: {token}"
```

## 🎨 前端技术栈

- **Pico CSS**: 极简化 CSS 框架（从 CDN 加载）
- **HTMX**: 动态交互库（无需页面刷新）
- **Razor Views**: ASP.NET Core 视图引擎

## 💾 数据库

使用 **SQLite**，自动创建：
-  `app.db` 文件

### 初始化

```bash
sqlite3 app.db < init_db.sql
```

### 添加新表

在 `init_db.sql` 中添加 SQL 语句，然后重新初始化。

## 🛠️ 扩展

### 添加新模型

1. 在 `Definitions/app.yaml` 添加模型定义
2. 在 SQLite 创建对应的表
3. 应用会自动生成 UI 和 API

### 自定义验证

编辑 `Platform.Infrastructure/ModelBinder.cs` 中的 `ConvertValue` 方法。

### 自定义 UI

编辑 Razor 视图文件：
- `Views/Ui/List.cshtml` - 列表页
- `Views/Ui/FormModal.cshtml` - 表单页
- `Views/Shared/_Layout.cshtml` - 全局布局

## 📦 项目结构

```
LowCodePlatform/
├── Platform.Api/              # Web 应用
├── Platform.Application/      # 应用服务
├── Platform.Domain/           # 领域模型
├── Platform.Infrastructure/   # 数据访问 & 工具
├── Definitions/               # YAML 定义
│   └── app.yaml               # 核心配置文件
├── init_db.sql                # 数据库初始化脚本
└── app.db                      # SQLite 数据库
```

## 🔐 安全特性

- ✅ CSRF 保护（X-CSRF-TOKEN）
- ✅ SQL 注入防护（参数化查询 + 标识符验证）
- ✅ 输入验证（类型检查 + 长度限制）
- ✅ 表单验证（服务端 + 客户端）

## 📝 示例数据

应用已预置示例数据：

**Product 表**
| Id | Name | Price | Category |
|---|---|---|---|
| 1 | iPhone 15 | 999.99 | electronics |
| 2 | MacBook Pro | 1999.99 | electronics |
| 3 | Organic Apple | 2.99 | food |

**Customer 表**
| Id | Name | Email | Phone |
|---|---|---|---|
| 1 | John Smith | john@example.com | +1-555-0101 |
| 2 | Jane Doe | jane@example.com | +1-555-0102 |

## 🧪 测试

```bash
# 编译测试
dotnet build

# 运行应用
dotnet run --project Platform.Api

# 在浏览器打开
http://localhost:5267
```

## 📚 技术文档

- [ASP.NET Core MVC](https://docs.microsoft.com/aspnet/core)
- [Dapper ORM](https://github.com/DapperLib/Dapper)
- [HTMX](https://htmx.org)
- [YamlDotNet](https://github.com/aaubry/YamlDotNet)

## ✨ 主要特性

✅ 零代码数据 CRUD  
✅ 自动表单生成  
✅ 实时搜索和过滤  
✅ 响应式设计  
✅ RESTful API  
✅ 数据验证  
✅ 分页支持  
✅ CSRF 保护  
✅ SQL 注入防护  

## 🚀 下一步

1. 添加更多数据模型到 `app.yaml`
2. 在 SQLite 创建对应的表
3. 重启应用
4. 享受自动生成的 CRUD UI！

---

**Happy Low-Code Coding! 🎉**
