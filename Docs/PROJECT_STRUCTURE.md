# 低代码平台 - 项目结构规范

## 📁 目录结构

```
lcp/
├── Framework/                    # 框架代码（核心低代码引擎）
│   ├── Platform.Api/            # API 和 Web 应用
│   ├── Platform.Application/    # 应用服务
│   ├── Platform.Domain/         # 领域模型
│   ├── Platform.Infrastructure/ # 基础设施
│   └── wwwroot/                 # 框架静态资源
│
├── Projects/                     # 项目目录（用户创建的应用）
│   ├── todo/                    # TODO 项目示例
│   │   ├── project.yaml         # 项目配置
│   │   ├── app.yaml             # 应用定义（模型、页面）
│   │   ├── schema.sql           # 数据库结构
│   │   ├── data.sql             # 初始数据（可选）
│   │   ├── todo.db              # SQLite 数据库
│   │   ├── pages/               # 自定义页面
│   │   │   └── custom.cshtml
│   │   ├── views/               # 自定义视图覆盖
│   │   │   └── Ui/
│   │   │       └── List.cshtml
│   │   ├── css/                 # 自定义样式
│   │   │   └── custom.css
│   │   ├── js/                  # 自定义脚本
│   │   │   └── custom.js
│   │   └── extensions/          # 扩展代码
│   │       └── CustomService.cs
│   │
│   └── myapp/                   # 另一个项目
│       └── ...
│
└── Templates/                    # 项目模板
    ├── basic/                   # 基础模板
    ├── crm/                     # CRM 模板
    └── erp/                     # ERP 模板
```

## 📋 项目配置文件

### project.yaml

```yaml
# 项目配置
name: todo
display_name: TODO 项目管理
description: 任务和项目管理系统
version: 1.0.0
author: Your Name

# 数据库配置
database:
  type: sqlite
  path: todo.db
  schema: schema.sql
  seed_data: data.sql

# 功能配置
features:
  multi_language: true
  custom_pages: true
  custom_views: false
  
# 依赖扩展
extensions:
  - path: extensions/CustomService.cs
    type: service
    
# UI 配置
ui:
  theme: default
  custom_css: css/custom.css
  custom_js: js/custom.js
```

### app.yaml

```yaml
# 模型定义
models:
  Task:
    table: Task
    primary_key: Id
    # ... 模型配置

# 页面定义
pages:
  Dashboard:
    title: 仪表盘
    # ... 页面配置
```

## 🚀 项目加载机制

框架通过以下方式加载项目：

1. **扫描 Projects 目录** - 自动发现所有子目录
2. **读取 project.yaml** - 获取项目配置
3. **加载 app.yaml** - 注册模型和页面定义
4. **初始化数据库** - 执行 schema.sql 和 data.sql
5. **注册扩展** - 加载自定义服务和代码
6. **应用 UI 定制** - 加载自定义样式和视图

## 📦 创建新项目

### 方式 1: 使用 CLI

```bash
dotnet lcp new myapp --template basic
```

### 方式 2: 手动创建

```bash
cd Projects
mkdir myapp
cd myapp

# 创建必要文件
touch project.yaml
touch app.yaml
touch schema.sql
```

### 方式 3: 复制现有项目

```bash
cp -r Projects/todo Projects/myapp
# 然后修改 project.yaml 和 app.yaml
```

## 🔄 项目切换

### 开发时

```bash
# 设置当前项目
export LCP_PROJECT=todo
dotnet run --project Framework/Platform.Api
```

### 生产环境

```bash
# 部署特定项目
cp -r Projects/todo/* /var/www/lcp/
```

## 📝 最佳实践

### 1. 项目独立性

- ✅ 每个项目有自己的数据库
- ✅ 每个项目有自己的配置
- ✅ 自定义代码放在项目目录内
- ❌ 不要修改框架代码

### 2. 命名规范

- 项目目录：小写，无空格（如 `todo`, `myapp`）
- 数据库表：PascalCase（如 `Task`, `Project`）
- YAML 文件：小写（如 `app.yaml`, `schema.sql`）

### 3. 版本控制

```bash
# 框架代码
git add Framework/

# 项目代码（单独仓库）
git add Projects/todo/
```

### 4. 分发项目

```bash
# 打包项目
tar -czf todo-project.tar.gz Projects/todo/

# 部署项目
tar -xzf todo-project.tar.gz -C /path/to/lcp/Projects/
```

## 🔧 扩展示例

### 自定义服务

```csharp
// Projects/todo/extensions/TaskNotificationService.cs
using Platform.Application.Services;

namespace Projects.Todo.Extensions;

public class TaskNotificationService : ITaskNotificationService
{
    public async Task NotifyAsync(Task task)
    {
        // 发送通知逻辑
        await Task.CompletedTask;
    }
}
```

### 自定义页面

```html
@* Projects/todo/pages/Dashboard.cshtml *@
@{
    ViewData["Title"] = "仪表盘";
}

<div class="dashboard">
    <h1>TODO 项目仪表盘</h1>
    @* 自定义内容 *@
</div>
```

### 自定义样式

```css
/* Projects/todo/css/custom.css */
.card-task {
    border-left: 4px solid #3b82f6;
}

.dashboard {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

## 📚 相关文档

- [项目创建指南](./PROJECT_CREATION_GUIDE.md)
- [YAML 配置参考](./YAML_REFERENCE.md)
- [扩展示例](./EXTENSIONS.md)
