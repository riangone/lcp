# LowCode Platform 项目文档

## 📋 文档目录

1. [项目概述](#项目概述)
2. [快速开始](#快速开始)
3. [架构设计](#架构设计)
4. [项目结构](#项目结构)
5. [多项目管理](#多项目管理)
6. [用户认证](#用户认证)
7. [API 端点](#api-端点)
8. [开发指南](#开发指南)

---

## 项目概述

LowCode Platform 是一个基于 **.NET 10** 的**运行时驱动**低代码平台。

### 核心理念

> **通过 YAML 定义驱动一切，尽可能不写代码、不生成代码**

平台根据 YAML 定义**运行时动态**生成：
- ✅ RESTful CRUD API（单个通用控制器处理所有模型）
- ✅ 动态 Web UI（列表页、表单、过滤、分页）
- ✅ 数据验证和表单支持
- ✅ 多表关联和复杂业务场景支持
- ✅ 用户认证和授权（每个项目独立）

### 技术栈

| 类别 | 技术 |
|------|------|
| **后端框架** | ASP.NET Core 10.0 |
| **ORM** | Dapper |
| **数据库** | SQLite |
| **配置格式** | YAML (YamlDotNet) |
| **前端** | Razor Views + HTMX + Pico CSS + Tailwind CSS |
| **API 文档** | Scalar / OpenAPI |
| **认证** | JWT (System.IdentityModel.Tokens.Jwt) |

---

## 快速开始

### 前置条件

- .NET 10 SDK
- SQLite

### 构建和运行

```bash
# 构建
dotnet build

# 运行
dotnet run --project Platform.Api
```

### 访问应用

- **首页**: http://localhost:5267
- **API 文档**: http://localhost:5267/docs
- **UI 界面**: http://localhost:5267/{model}

### 切换项目

访问时添加 `project` 参数：
- http://localhost:5267/?project=todo
- http://localhost:5267/?project=chinook
- http://localhost:5267/?project=ecommerce

---

## 架构设计

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
│              Project Database                           │
│           (每个项目独立的 SQLite 数据库)                   │
└─────────────────────────────────────────────────────────┘
```

### 认证架构

```
┌─────────────────────────────────────────────────────────┐
│              HTTP Request + JWT Token                   │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              JwtBearer Middleware                       │
│           - 验证 Token 有效性                             │
│           - 提取用户 Claims                              │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              AuthService (scoped)                       │
│           - 从 ProjectScope 获取当前项目                   │
│           - 操作当前项目的 User 表                         │
│           - 生成/验证 JWT Token                          │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              Project.User Table                         │
│           (每个项目独立的用户表)                           │
└─────────────────────────────────────────────────────────┘
```

---

## 项目结构

```
lcp/
├── Platform.Api/                    # ASP.NET Core Web 应用
│   ├── Controllers/
│   │   ├── GenericApiController.cs  # ★ 通用 CRUD API
│   │   ├── UiController.cs          # ★ UI 页面控制器
│   │   ├── AuthController.cs        # ★ 认证控制器
│   │   └── PageController.cs        # 多表页面控制器
│   ├── Views/
│   │   ├── Shared/
│   │   │   ├── _Layout.cshtml       # 主布局（含导航栏）
│   │   │   ├── _UserStatus.cshtml   # 用户状态组件
│   │   │   └── _DeleteDialog.cshtml
│   │   ├── Ui/
│   │   │   ├── List.cshtml          # 通用列表页
│   │   │   └── FormModal.cshtml     # 通用表单
│   │   └── Auth/
│   │       ├── Login.cshtml         # 登录页面
│   │       └── Register.cshtml      # 注册页面
│   └── wwwroot/
│       └── js/site.js               # HTMX 交互脚本
│
├── Platform.Application/            # 应用服务层
│   └── Services/
│       ├── AuthService.cs           # 应用层认证服务（旧）
│       └── AuditService.cs
│
├── Platform.Domain/                 # 领域模型层
│   └── Entities/
│       ├── User.cs                  # 用户实体
│       └── Core/
│           ├── IEntityValidator.cs
│           └── BusinessRuleValidator.cs
│
├── Platform.Infrastructure/         # 数据访问和工具
│   ├── Data/
│   │   ├── DbConnectionFactory.cs
│   │   └── user_schema.sql          # 通用用户表结构
│   ├── Repositories/
│   │   └── DynamicRepository.cs     # ★ 动态 CRUD 仓储
│   ├── Services/
│   │   └── AuthService.cs           # ★ 基础设施层认证服务
│   ├── Definitions/                 # 数据结构定义
│   │   ├── ModelDefinition.cs
│   │   └── PageDefinition.cs
│   ├── ModelBinder.cs               # ★ 模型绑定和验证
│   ├── SqlIdentifier.cs             # SQL 标识符工具
│   └── Yaml/
│       └── YamlLoader.cs            # ★ YAML 加载器
│
├── Projects/                        # ★ 项目目录（每个项目独立）
│   ├── todo/                        # TODO 项目
│   │   ├── project.yaml             # 项目配置
│   │   ├── app.yaml                 # 应用定义
│   │   ├── schema.sql               # 数据库架构
│   │   ├── data.sql                 # 种子数据
│   │   └── todo.db                  # SQLite 数据库
│   ├── chinook/                     # Chinook 音乐商店
│   └── ecommerce/                   # 电商订单系统
│
├── Definitions/                     # 框架级 YAML 定义
│   ├── app.yaml                     # 默认应用定义
│   └── pages/                       # 多表页面定义
│
├── Docs/                            # 文档目录
│   └── PROJECT_DOCUMENTATION.md     # 本文档
│
└── init_db.sql                      # 数据库初始化脚本
```

---

## 多项目管理

### 项目目录结构

每个项目包含：
- `project.yaml` - 项目配置（名称、数据库路径等）
- `app.yaml` - 应用定义（模型、页面等）
- `schema.sql` - 数据库架构
- `data.sql` - 种子数据
- `{project}.db` - SQLite 数据库

### 可用项目

| 项目 | 说明 | 访问方式 |
|------|------|----------|
| **todo** | TODO 项目管理 | `?project=todo` |
| **journal** | 日记本应用 | `?project=journal` |
| **chinook** | 音乐商店 | `?project=chinook` |
| **ecommerce** | 电商订单系统 | `?project=ecommerce` |

### 项目隔离

- ✅ 每个项目有独立的数据库
- ✅ 每个项目有独立的用户表
- ✅ 每个项目有独立的应用定义
- ✅ 运行时动态切换，无需重启

---

## 用户认证

### 认证特性

- ✅ JWT Token 认证
- ✅ BCrypt 密码加密
- ✅ 每个项目独立用户表
- ✅ 支持 Token 刷新
- ✅ 角色权限（User/Manager/Admin）

### API 端点

| 端点 | 方法 | 认证 | 说明 |
|------|------|------|------|
| `/api/auth/login` | POST | ❌ | 用户登录 |
| `/api/auth/register` | POST | ❌ | 用户注册 |
| `/api/auth/logout` | POST | ✅ | 用户登出 |
| `/api/auth/refresh` | POST | ❌ | 刷新 Token |
| `/api/auth/me` | GET | ✅ | 获取当前用户 |
| `/api/auth/change-password` | POST | ✅ | 修改密码 |

### 默认管理员账户

| 项目 | 用户名 | 密码 | 邮箱 |
|------|--------|------|------|
| **todo** | admin | admin123 | admin@todo.com |
| **chinook** | admin | admin123 | admin@chinook.com |
| **ecommerce** | admin | admin123 | admin@ecommerce.com |

---

## API 端点

### 通用 CRUD API（所有模型自动支持）

| 方法 | 端点 | 说明 |
|------|------|------|
| `GET` | `/api/{model}` | 获取所有数据 |
| `GET` | `/api/{model}/{id}` | 获取单个数据 |
| `POST` | `/api/{model}` | 创建数据 |
| `PUT` | `/api/{model}/{id}` | 更新数据 |
| `DELETE` | `/api/{model}/{id}` | 删除数据 |

### UI 端点

| 方法 | 端点 | 说明 |
|------|------|------|
| `GET` | `/{model}` | 列表页面 |
| `GET` | `/{model}/create` | 创建表单 |
| `GET` | `/{model}/edit/{id}` | 编辑表单 |
| `GET` | `/{model}/details/{id}` | 详情页面 |

### 多表 API

| 方法 | 端点 | 说明 |
|------|------|------|
| `GET` | `/api/page/{pageName}/multi-table-data` | 获取多表数据 |
| `GET` | `/api/page/{pageName}/multi-table/{id}` | 获取单条多表数据 |
| `POST` | `/api/page/{pageName}/multi-table/save` | 保存多表数据 |
| `POST` | `/api/page/{pageName}/multi-table/delete` | 删除多表数据 |

---

## 开发指南

### 1. 创建新项目

```bash
# 1. 创建项目目录
mkdir Projects/myproject

# 2. 创建 project.yaml
cat > Projects/myproject/project.yaml << EOF
name: myproject
display_name: 我的项目
description: 项目描述
version: 1.0.0
database:
  type: sqlite
  path: myproject.db
  schema: schema.sql
  seed_data: data.sql
EOF

# 3. 创建 app.yaml（定义数据模型）
# 4. 创建 schema.sql（数据库架构）
# 5. 创建 data.sql（种子数据）
# 6. 初始化数据库
sqlite3 Projects/myproject/myproject.db < Projects/myproject/schema.sql
sqlite3 Projects/myproject/myproject.db < Projects/myproject/data.sql
```

### 2. 定义数据模型

在 `app.yaml` 中定义：

```yaml
models:
  Product:
    table: Product
    primary_key: ProductId

    ui:
      labels:
        zh:
          title: 产品
          Name: 产品名称
        en:
          title: Products
          Name: Product Name

    list:
      columns: [ProductId, Name, Price]
      filters:
        Name:
          label: Name
          type: like

    form:
      fields:
        Name:
          label: Name
          type: text
          required: true

    properties:
      ProductId: { type: int }
      Name: { type: string, required: true }
      Price: { type: decimal }
```

### 3. 添加用户表

在每个项目的 `schema.sql` 中添加：

```sql
CREATE TABLE IF NOT EXISTS "User" (
    UserId INTEGER PRIMARY KEY AUTOINCREMENT,
    Username TEXT NOT NULL UNIQUE,
    Email TEXT NOT NULL UNIQUE,
    PasswordHash TEXT NOT NULL,
    DisplayName TEXT,
    Role TEXT DEFAULT 'User',
    IsActive INTEGER DEFAULT 1,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP
);

-- 默认管理员（密码：admin123）
INSERT OR IGNORE INTO "User" (Username, Email, PasswordHash, DisplayName, Role) VALUES 
('admin', 'admin@myproject.com', '$2b$11$...', '系统管理员', 'Admin');
```

### 4. 受保护的 API

在控制器中使用 `[Authorize]` 特性：

```csharp
[HttpPost("api/protected")]
[Authorize]
public async Task<IActionResult> ProtectedAction()
{
    // 只有登录用户才能访问
    var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
    // ...
}
```

---

## 常见问题

### Q: 如何切换项目？
A: 在 URL 中添加 `?project=项目名称` 参数，或在首页点击项目卡片。

### Q: 用户数据会共享吗？
A: 不会。每个项目有独立的用户表，用户数据完全隔离。

### Q: 如何添加新的数据模型？
A: 在项目的 `app.yaml` 中添加模型定义，重启服务器即可。

### Q: 支持哪些数据库？
A: 目前仅支持 SQLite，但可以通过修改 `DbConnectionFactory` 扩展。

---

## 更新日志

详见 [CHANGE_LOG.md](../CHANGE_LOG.md)

---

&copy; 2026 LowCode Platform. All rights reserved.
