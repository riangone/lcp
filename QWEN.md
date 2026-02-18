# LowCodePlatform (LCP) 项目上下文

## 📋 项目概述

这是一个基于 **.NET 10** 的**运行时驱动**低代码平台，核心理念是：

> **通过 YAML 定义驱动一切，尽可能不写代码、不生成代码**

平台根据 YAML 定义**运行时动态**生成：
- ✅ RESTful CRUD API（单个通用控制器处理所有模型）
- ✅ 动态 Web UI（列表页、表单、过滤、分页）
- ✅ 数据验证和表单支持（运行时读取配置验证）
- ✅ 多表关联和复杂业务场景支持

## 🏗️ 架构设计

### 运行时驱动架构

```
┌─────────────────────────────────────────────────────────┐
│                    HTTP Request                          │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              GenericApiController                       │
│           (一个控制器处理所有模型，无代码生成)             │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              AppDefinitions (YAML 加载)                  │
│           - Models (模型定义)                            │
│           - Pages (多表页面定义)                          │
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

### 技术栈

| 类别 | 技术 |
|------|------|
| **后端框架** | ASP.NET Core 10.0 |
| **ORM** | Dapper |
| **数据库** | SQLite |
| **配置格式** | YAML (YamlDotNet) |
| **前端** | Razor Views + HTMX + Pico CSS |
| **API 文档** | Scalar / OpenAPI |

## 📁 项目结构

```
lcp/
├── Platform.Api/                    # ASP.NET Core Web 应用
│   ├── Controllers/
│   │   ├── GenericApiController.cs  # ★ 通用 CRUD API（核心）
│   │   ├── UiController.cs          # ★ UI 页面控制器（核心）
│   │   ├── PageController.cs        # 多表页面控制器
│   │   └── MultiTableController.cs  # 多表 CRUD 控制器
│   ├── Views/
│   │   ├── Shared/
│   │   │   ├── _Layout.cshtml
│   │   │   └── _DeleteDialog.cshtml
│   │   └── Ui/
│   │       ├── List.cshtml          # ★ 通用列表页模板
│   │       ├── FormModal.cshtml     # ★ 通用表单模态框
│   │       └── _ListContent.cshtml  # 列表内容（支持 HTMX）
│   └── wwwroot/
│       └── js/site.js               # HTMX 交互脚本
│
├── Platform.Application/            # 应用服务层
│   └── Services/
│       ├── AuthService.cs
│       └── AuditService.cs
│
├── Platform.Domain/                 # 领域模型层
│   └── Core/
│       ├── IEntityValidator.cs
│       ├── BusinessRuleValidator.cs # 纯函数验证逻辑
│       └── EntityStateTransition.cs # 纯函数状态转换
│
├── Platform.Infrastructure/         # 数据访问和工具
│   ├── Data/
│   │   └── DbConnectionFactory.cs   # 数据库连接工厂
│   ├── Repositories/
│   │   └── DynamicRepository.cs     # ★ 动态 CRUD 仓储（核心）
│   ├── Definitions/                 # 数据结构定义
│   │   ├── ModelDefinition.cs
│   │   ├── PageDefinition.cs
│   │   ├── MultiTableFormDefinition.cs
│   │   └── ...
│   ├── ModelBinder.cs               # ★ 模型绑定和验证（核心）
│   ├── SqlIdentifier.cs             # SQL 标识符转义工具
│   └── Yaml/
│       └── YamlLoader.cs            # ★ YAML 加载器（核心）
│
├── Definitions/                     # ★ YAML 定义文件（核心配置）
│   ├── app.yaml                     # 模型定义
│   └── pages/                       # 多表页面定义
│
├── Docs/                            # 文档
│   ├── MultiTableForm.md            # 多表表单功能文档
│   └── LowCode_Enhancement_Plan.md  # 低代码增强计划
│
├── init_db.sql                      # 数据库初始化脚本
└── LowCodePlatform.sln              # Visual Studio 解决方案
```

## 🚀 构建和运行

### 前置条件
- .NET 10 SDK
- SQLite

### 构建命令
```bash
dotnet build
```

### 运行应用
```bash
dotnet run --project Platform.Api
```

### 初始化数据库
```bash
sqlite3 app.db < init_db.sql
```

### 访问应用
- **首页**: http://localhost:5267
- **API 文档**: http://localhost:5267/docs
- **UI 界面**: http://localhost:5267/ui/{model}

## 📝 YAML 配置示例

### 单表模型定义

```yaml
models:
  Artist:
    table: Artist
    primary_key: ArtistId

    ui:
      labels:
        en:
          title: Artists
          Name: Name
        zh:
          title: 艺术家
          Name: 姓名

    list:
      columns: [ArtistId, Name]
      filters:
        Name:
          label: Name
          type: like

    form:
      fields:
        Name:
          label: Name
          type: text
          max_length: 120

    properties:
      ArtistId: { type: int }
      Name: { type: string }
```

### 多表关联视图（只读）

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
        CustomerName: { label: Customer, type: like }
        Country: { label: Country, type: like }

    properties:
      InvoiceId: { type: int }
      InvoiceDate: { type: date }
      CustomerName: { type: string }
      Country: { type: string }
      Total: { type: decimal }
```

### 多表页面定义

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

## 🔌 API 端点

### 通用 CRUD API（所有模型自动支持）

| 方法 | 端点 | 说明 |
|------|------|------|
| `GET` | `/api/{model}` | 获取所有数据 |
| `POST` | `/api/{model}` | 创建数据 |
| `PUT` | `/api/{model}/{id}` | 更新数据 |
| `DELETE` | `/api/{model}/{id}` | 删除数据 |

### UI 端点

| 方法 | 端点 | 说明 |
|------|------|------|
| `GET` | `/ui/{model}` | 列表页面 |
| `GET` | `/ui/{model}/create` | 创建表单 |
| `GET` | `/ui/{model}/edit/{id}` | 编辑表单 |
| `GET` | `/ui/{model}/details/{id}` | 详情页面 |

### 多表 API

| 方法 | 端点 | 说明 |
|------|------|------|
| `GET` | `/api/page/{pageName}/load` | 加载多表数据 |
| `POST` | `/api/page/{pageName}/save` | 保存多表数据 |

## 🎯 核心机制

### 1. GenericApiController - 通用控制器

**一个控制器处理所有模型，无需为每个模型创建控制器**

```csharp
[ApiController]
[Route("api/{model}")]
public class GenericApiController : ControllerBase
{
    private readonly DynamicRepository _repo;
    private readonly AppDefinitions _defs;

    [HttpPost]
    public async Task<IActionResult> Create(
        string model,
        [FromForm] Dictionary<string, string> data)
    {
        var def = GetModel(model);  // 从 YAML 获取定义
        var objData = ModelBinder.Bind(def, data);  // 运行时绑定验证
        await _repo.InsertAsync(def, objData);  // 动态执行 SQL
        return Ok();
    }
}
```

### 2. DynamicRepository - 动态仓储

**运行时动态构建 SQL，无需为每个表创建仓储类**

```csharp
public class DynamicRepository
{
    public async Task InsertAsync(ModelDefinition def, IDictionary<string, object> data)
    {
        // 根据 YAML 定义动态构建 SQL
        var cols = def.Columns.Intersect(data.Keys).ToList();
        var sql = $"INSERT INTO {Escape(def.Table)} (...) VALUES (...)";
        await _db.ExecuteAsync(sql, data);
    }
}
```

### 3. ModelBinder - 模型绑定器

**运行时读取 YAML 配置进行数据绑定和验证**

```csharp
public static class ModelBinder
{
    public static Dictionary<string, object> Bind(
        ModelDefinition def,
        Dictionary<string, string> input)
    {
        // 读取 YAML 中的 form.fields 配置
        foreach (var field in def.Form.Fields)
        {
            // 运行时验证类型、长度、必填等
            var value = ConvertValue(name, raw, propDef.Type, fieldDef);
            result[name] = value;
        }
        return result;
    }
}
```

### 4. YamlLoader - YAML 加载器

**应用启动时加载 YAML 定义到内存**

```csharp
public static class YamlLoader
{
    public static AppDefinitions Load(string filePath, string pagesDir)
    {
        var yaml = File.ReadAllText(filePath);
        var deserializer = new DeserializerBuilder()
            .WithNamingConvention(UnderscoredNamingConvention.Instance)
            .Build();
        return deserializer.Deserialize<AppDefinitions>(yaml);
    }
}
```

## 📦 已配置的数据模型

基于 Chinook 数据库：

| 模型 | 表名 | 说明 |
|------|------|------|
| `Artist` | Artist | 艺术家 |
| `Album` | Album | 专辑 |
| `Track` | Track | 音轨 |
| `Genre` | Genre | 音乐流派 |
| `MediaType` | MediaType | 媒体类型 |
| `Employee` | Employee | 员工 |
| `Invoice` | Invoice | 发票 |
| `InvoiceWithCustomer` | (查询) | 发票客户关联视图（只读） |

## 🔐 安全特性

- ✅ CSRF 保护（X-CSRF-TOKEN）
- ✅ SQL 注入防护（参数化查询 + 标识符验证）
- ✅ 输入验证（运行时类型检查 + 长度限制）
- ✅ 表单验证（服务端 + 客户端）
- ✅ JWT 认证支持

## 🚀 增强计划

### P0 - 核心增强

1. **业务规则验证** - 通过 YAML 配置验证规则
2. **完善多表表单** - 已有基础，需要测试和文档
3. **权限控制基础** - 简单的角色权限

### P1 - 重要增强

4. **计算字段** - 通过表达式配置
5. **级联操作** - 通过 YAML 配置
6. **审计字段** - 自动填充创建/修改信息

### P2 - 高级功能

7. **动态表单布局** - 通过 YAML 配置布局
8. **工作流引擎** - YAML 定义的状态机
9. **动态列表操作** - 通过 YAML 配置操作按钮

详细计划见 `Docs/LowCode_Enhancement_Plan.md`

## 🎯 设计原则

1. **运行时驱动** - 不要生成代码，在运行时读取 YAML 执行
2. **一个控制器处理所有** - 不要为每个模型创建控制器
3. **配置优于编码** - 能通过 YAML 配置的就不写代码
4. **渐进式增强** - 保持现有功能，逐步增强

## 📚 重要文件说明

| 文件 | 说明 |
|------|------|
| `Definitions/app.yaml` | 核心配置文件，定义所有数据模型 |
| `Program.cs` | 应用入口，配置依赖注入和中间件 |
| `GenericApiController.cs` | ★ 通用 CRUD API 控制器 |
| `DynamicRepository.cs` | ★ 动态数据仓储 |
| `ModelBinder.cs` | ★ 模型绑定和验证 |
| `YamlLoader.cs` | ★ YAML 加载器 |
| `Docs/MultiTableForm.md` | 多表表单功能详细文档 |
| `Docs/LowCode_Enhancement_Plan.md` | 低代码增强计划 |

## 🔗 相关资源

- [ASP.NET Core MVC 文档](https://docs.microsoft.com/aspnet/core)
- [Dapper ORM](https://github.com/DapperLib/Dapper)
- [HTMX](https://htmx.org)
- [YamlDotNet](https://github.com/aaubry/YamlDotNet)

## 💡 与代码生成的对比

| 方案 | 优点 | 缺点 |
|------|------|------|
| **运行时驱动（本项目）** | 无需生成文件，修改 YAML 即可，维护简单 | 性能略低（但可接受） |
| **代码生成** | 生成的代码可单独优化 | 生成的文件多，难以维护 |

**本项目的选择：运行时驱动为主，必要时生成代码**
