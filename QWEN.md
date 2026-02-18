# LowCodePlatform (LCP) 项目上下文

## 📋 项目概述

这是一个基于 **.NET 10** 的低代码平台，能够根据 YAML 定义自动生成：
- RESTful CRUD API
- 动态 Web UI（支持实时更新）
- 数据验证和表单支持
- 分页、过滤、搜索功能
- AI 三层架构整合（函数式核心、确定性外壳、非确定性边缘）

## 🏗️ 技术栈

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
│   │   ├── GenericApiController.cs  # 通用 CRUD API
│   │   ├── UiController.cs          # UI 页面控制器
│   │   └── AiController.cs          # AI 相关 API
│   ├── Views/
│   │   ├── Shared/
│   │   │   ├── _Layout.cshtml
│   │   │   ├── _DeleteDialog.cshtml
│   │   │   └── _ErrorDialog.cshtml
│   │   └── Ui/
│   │       ├── List.cshtml          # 列表页面
│   │       └── FormModal.cshtml     # 表单模态框
│   ├── TestScenarios/
│   ├── wwwroot/
│   │   └── js/site.js               # HTMX 交互脚本
│   ├── Program.cs                   # 应用入口和 DI 配置
│   └── Platform.Api.csproj
│
├── Platform.Application/            # 应用服务层
│   └── Services/
│       ├── IAiSuggestionService.cs
│       ├── MockAISuggestionService.cs
│       ├── AiIntegrationService.cs
│       ├── AuthService.cs
│       └── AuditService.cs
│
├── Platform.Domain/                 # 领域模型层
│   └── Core/
│       ├── IEntityValidator.cs      # 实体验证器接口
│       ├── BusinessRuleValidator.cs # 业务规则验证（纯函数）
│       └── EntityStateTransition.cs # 状态转换（纯函数）
│
├── Platform.Infrastructure/         # 数据访问和工具
│   ├── Data/
│   │   └── DbConnectionFactory.cs   # 数据库连接工厂
│   ├── Repositories/
│   │   ├── DynamicRepository.cs     # 动态 CRUD 仓储
│   │   └── SnapshotRepository.cs    # 快照仓储
│   ├── Definitions/                 # 数据结构定义
│   ├── Services/
│   ├── Shell/                       # 确定性外壳组件
│   │   ├── Snapshot.cs              # 快照模型
│   │   └── ISnapshotRepository.cs
│   ├── Yaml/
│   │   └── YamlLoader.cs            # YAML 加载器
│   ├── ModelBinder.cs               # 模型绑定和验证
│   └── SqlIdentifier.cs             # SQL 标识符转义工具
│
├── Definitions/                     # YAML 定义文件
│   ├── app.yaml                     # 核心应用配置
│   └── pages/                       # 多表页面配置
│
├── Docs/                            # 文档
│   └── MultiTableForm.md            # 多表表单功能文档
│
├── init_db.sql                      # 数据库初始化脚本
├── LowCodePlatform.sln              # Visual Studio 解决方案
└── package.json                     # Node.js 配置（Puppeteer 测试）
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
# 使用 SQLite CLI
sqlite3 app.db < init_db.sql

# 或使用已有的 Chinook 数据库
# chinook.db 或 chinook_with_data.db 已包含示例数据
```

### 访问应用
- **首页**: http://localhost:5267
- **API 文档**: http://localhost:5267/docs
- **UI 界面**: http://localhost:5267/ui/{model}

## 📝 YAML 配置示例

### 单表模型定义 (Definitions/app.yaml)

```yaml
models:
  Artist:
    table: Artist
    primary_key: ArtistId

    ui:
      layout:
        theme: default
        grid_columns: 2
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
      title: Artist
      fields:
        Name:
          label: Name
          type: text
          max_length: 120

    properties:
      ArtistId: { type: int }
      Name: { type: string }
```

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
        CustomerName: { label: Customer, type: like }
        Country: { label: Country, type: like }

    properties:
      InvoiceId: { type: int }
      InvoiceDate: { type: date }
      CustomerName: { type: string }
      Country: { type: string }
      Total: { type: decimal }
```

## 🔌 API 端点

### 通用 CRUD API

| 方法 | 端点 | 说明 |
|------|------|------|
| `GET` | `/api/{model}` | 获取所有数据 |
| `POST` | `/api/{model}` | 创建数据（form-data） |
| `PUT` | `/api/{model}/{id}` | 更新数据（form-data） |
| `DELETE` | `/api/{model}/{id}` | 删除数据 |

### AI 相关 API

| 方法 | 端点 | 说明 |
|------|------|------|
| `POST` | `/api/ai/suggest` | 生成 AI 建议 |
| `GET` | `/api/ai/pending` | 获取待审批快照 |
| `POST` | `/api/ai/approve/{id}` | 审批快照 |
| `POST` | `/api/ai/reject/{id}` | 拒绝快照 |

### 多表表单 API

| 方法 | 端点 | 说明 |
|------|------|------|
| `GET` | `/api/multi-table/{pageName}/load` | 加载多表数据 |
| `POST` | `/api/multi-table/{pageName}/save` | 保存多表数据 |

## 🎯 AI 三层架构

项目实现了 AI 三层架构模式：

### 1. Functional Core (函数式核心)
- **位置**: `Platform.Domain/Core/`
- **特点**: 纯函数，无副作用
- **组件**:
  - `IEntityValidator` - 实体验证器接口
  - `BusinessRuleValidator` - 业务规则验证
  - `EntityStateTransition` - 状态转换

### 2. Deterministic Shell (确定性外壳)
- **位置**: `Platform.Infrastructure/Shell/`
- **特点**: 处理副作用，确定性行为
- **组件**:
  - `Snapshot` / `Provenance` - 快照和证迹模型
  - `ISnapshotRepository` / `SnapshotRepository` - 快照仓储

### 3. Non-deterministic Edge (非确定性边缘)
- **位置**: `Platform.Application/Services/`
- **特点**: AI/ML 集成，非确定性行为
- **组件**:
  - `IAiSuggestionService` - AI 建议服务接口
  - `MockAISuggestionService` - 模拟 AI 服务
  - `AiIntegrationService` - AI 集成协调器

## 🛠️ 开发约定

### 代码风格
- 使用 C# 10+ 特性（`record`、模式匹配等）
- 启用 nullable reference types
- 依赖注入优先
- 仓储模式进行数据访问

### 测试实践
- 函数式核心组件应编写单元测试
- AI 服务使用模拟实现进行测试
- Puppeteer 用于端到端测试（`test_page.js`）

### 数据库约定
- 使用 SQLite 进行开发和测试
- 主键统一使用 `Id` 或 `{TableName}Id` 格式
- 所有数据库变更需更新 `init_db.sql`

### YAML 配置约定
- 模型名称使用 PascalCase
- 表名使用数据库实际名称（如 Chinook 数据库的表名）
- 支持中英文双语标签

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
- ✅ 输入验证（类型检查 + 长度限制）
- ✅ 表单验证（服务端 + 客户端）
- ✅ JWT 认证支持（Microsoft.AspNetCore.Authentication.JwtBearer）

## 🤖 AI 代码生成系统

项目包含完整的**确定性代码生成系统**，确保 AI 生成的代码是稳定的、一致的、可维护的：

### 核心组件

| 组件 | 文件 | 说明 |
|------|------|------|
| **代码生成器接口** | `ICodeGenerator.cs` | 定义代码生成标准接口 |
| **模型代码生成器** | `ModelCodeGenerator.cs` | 从 ModelDefinition 生成代码 |
| **代码模板引擎** | `CodeTemplateEngine.cs` | 使用模板确保代码一致性 |
| **版本管理器** | `CodeVersionManager.cs` | 追踪代码版本和变更 |
| **质量验证器** | `CodeQualityValidator.cs` | 验证代码语法和质量 |
| **生成服务** | `CodeGenerationService.cs` | 统一入口，整合所有组件 |

### 使用示例

```csharp
var service = new CodeGenerationService(new CodeGenerationSettings
{
    RootNamespace = "Platform.Api",
    AddHeaderComments = true
});

// 从 YAML 生成代码
var result = await service.GenerateFromYamlAsync(
    "Definitions/app.yaml",
    "Generated"
);
```

### 主要特性

- ✅ **模板驱动** - 预定义模板确保代码结构一致
- ✅ **版本管理** - 追踪每次生成，支持回滚
- ✅ **质量验证** - Roslyn 分析语法和质量
- ✅ **变更检测** - 只在 YAML 变更时重新生成
- ✅ **确定性输出** - 相同输入产生相同输出

详细文档见 `Docs/CodeGeneration.md`

## 📚 重要文件说明

| 文件 | 说明 |
|------|------|
| `Definitions/app.yaml` | 核心配置文件，定义所有数据模型 |
| `Program.cs` | 应用入口，配置依赖注入和中间件 |
| `DynamicRepository.cs` | 核心数据仓储，处理动态 CRUD |
| `ModelBinder.cs` | 模型绑定和类型转换 |
| `SqlIdentifier.cs` | SQL 标识符转义工具，防止注入 |
| `init_db.sql` | 数据库初始化和测试数据 |
| `Docs/MultiTableForm.md` | 多表表单功能详细文档 |

## 🐛 已知问题/注意事项

1. **YAML 路径解析**: `Program.cs` 中 YAML 文件路径从 `bin/Debug/net10.0` 返回到项目根目录
2. **静态文件路径**: `WebRootPath` 设置为 `../wwwroot`
3. **模拟 AI 服务**: `MockAISuggestionService` 是模拟实现，需要替换为真实 AI 模型
4. **多表表单**: 复杂的多表配置需要参考 `Docs/MultiTableForm.md`

## 🔗 相关资源

- [ASP.NET Core MVC 文档](https://docs.microsoft.com/aspnet/core)
- [Dapper ORM](https://github.com/DapperLib/Dapper)
- [HTMX](https://htmx.org)
- [YamlDotNet](https://github.com/aaubry/YamlDotNet)
- [Scalar API 文档](https://github.com/scalar/scalar)
