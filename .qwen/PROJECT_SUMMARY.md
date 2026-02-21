用户要求我根据对话历史生成一个综合性的项目总结，使用特定的 markdown 格式。我需要基于之前读取的 PROJECT_SUMMARY.md 内容，以及我对整个 LCP 项目的了解，创建一个全面但精炼的总结。

让我按照要求的格式组织内容：
1. Overall Goal - 项目的总体目标
2. Key Knowledge - 关键技术知识、架构决策、用户偏好等
3. Recent Actions - 最近的行动和成果
4. Current Plan - 当前计划，使用状态标记

我会用中文回复，因为输出语言偏好是中文。# LowCodePlatform (LCP) 项目总结

## Overall Goal
构建一个基于 .NET 10 的**运行时驱动**低代码平台，通过 YAML 定义动态生成 CRUD API 和 Web UI，支持多项目独立配置和自定义视图扩展。

## Key Knowledge

### 核心架构原则
- **运行时驱动**：YAML 配置在运行时加载执行，而非代码生成
- **单控制器模式**：`GenericApiController` 一个控制器处理所有模型的 CRUD
- **多项目支持**：`Projects/` 目录下每个项目有独立的 SQLite 数据库和 YAML 配置
- **项目切换**：通过 URL 参数 `?project={name}` 切换（journal, chinook, ecommerce, crm, todo）

### 技术栈
| 层级 | 技术 |
|------|------|
| 后端 | ASP.NET Core 10.0 |
| ORM | Dapper |
| 数据库 | SQLite（每项目独立） |
| 前端 | Razor Views + HTMX + Tailwind CSS |
| 配置 | YAML (YamlDotNet) |

### 构建与运行
```bash
dotnet build
dotnet run --project Platform.Api --urls "http://localhost:5267"
```

### 关键约定
- **项目参数传递**：所有导航链接必须保留 `&project={name}`
- **UI 切换**：通过 `&ui=custom` 或 `&ui=generic` URL 参数
- **自定义视图位置**：`Platform.Api/Views/Ui/{ViewName}/`
- **YAML 配置**：`custom_view.default_ui_mode` 控制默认 UI 行为

### 核心文件
| 文件 | 职责 |
|------|------|
| `GenericApiController.cs` | 通用 CRUD API 控制器 |
| `DynamicRepository.cs` | 动态 SQL 生成仓储 |
| `ModelBinder.cs` | 运行时模型绑定和验证 |
| `UiController.cs` | UI 页面控制器（支持项目切换） |
| `ModelDefinition.cs` | 模型定义（含 CustomViewDefinition） |
| `Projects/{name}/app.yaml` | 项目模型配置 |

## Recent Actions

### 已完成的 Bug 修复
1. **"Model undefined" 错误** - 根本原因：`UiController` 直接注入 `AppDefinitions` 而非 `ProjectScope`
2. **删除按钮 API 路径错误** - 从 `/api/data/{model}/{id}` 改为 `/api/{model}/{id}`
3. **项目参数丢失** - 在所有导航链接、表单、重定向中添加 `project` 参数：
   - `_ListContent.cshtml`（分页、过滤、编辑按钮）
   - `GenericApiController.cs`（HX-Redirect 头）
   - `UiController.cs`（清除过滤重定向、创建/编辑重定向）

### 自定义 UI 系统实现
1. **添加 CustomViewDefinition** 到 `ModelDefinition.cs`：
   - `enabled`: 启用自定义 UI
   - `default_ui_mode`: "custom" 或 "generic"
   - `list_template`, `form_template`, `details_template`: 视图路径
   - `style`: 布局、主题、分页配置

2. **创建 Journal 项目专属 UI**：
   - **列表视图**：时间线布局、心情表情 (😊🙂😐😔😠)、统计信息、搜索、心情过滤
   - **表单视图**：富文本编辑器、心情选择器、分类下拉
   - **详情视图**：全屏阅读模式、动画效果
   - **自定义 CSS**：`Projects/journal/css/custom.css`

3. **UI 切换机制**：
   - YAML 配置：`default_ui_mode: custom` 或 `generic`
   - URL 覆盖：`&ui=custom` 或 `&ui=generic`
   - 两种 UI 类型均有切换按钮
   - 首页卡片根据配置自动链接到对应 UI

### 修改的文件
| 文件 | 变更内容 |
|------|----------|
| `ModelDefinition.cs` | 添加 CustomViewDefinition 类 |
| `UiController.cs` | UI 模式检测和视图切换 |
| `GenericApiController.cs` | 重定向中添加 project 参数 |
| `Views/Ui/Journal/*.cshtml` | 自定义视图（列表、表单、详情） |
| `_CardGrid.cshtml` | 首页卡片自动链接到自定义 UI |
| `_ListContent.cshtml` | 添加自定义 UI 切换按钮 |
| `Projects/journal/app.yaml` | 自定义视图配置 |

## Current Plan

### [DONE]
1. ✅ 修复 CRUD "Model undefined" 错误
2. ✅ 修复项目参数在导航中丢失的问题
3. ✅ 实现 CustomViewDefinition YAML 配置
4. ✅ 创建 Journal 自定义 UI（时间线、表单、详情）
5. ✅ 添加 UI 切换机制（按钮 + URL 参数）
6. ✅ 配置首页卡片自动链接到对应 UI
7. ✅ 设置 Journal Entry 默认 UI 为 generic（表格），自定义 UI 可通过按钮访问

### [IN PROGRESS]
- 无

### [TODO]
1. **扩展自定义 UI 到其他项目** - 为 ecommerce、crm 等项目创建专属视图
2. **完善详情视图支持** - 目前 List 和 Form 可用，Details 视图需要完整的 YAML 配置支持
3. **改进视图定位系统** - 当前需要复制视图文件到 `Platform.Api/Views/`，考虑使用嵌入式视图或更好的路径解析
4. **添加自定义 UI 模板库** - 创建可复用的模板（看板、日历、时间线、仪表盘等）
5. **编写文档** - 记录自定义 UI 创建流程，供用户参考
6. **业务规则验证** - 通过 YAML 配置验证规则（P0 优先级）
7. **完善多表表单** - 已有基础，需要测试和文档

---

## 访问信息

### 运行中的服务器
- **URL**: http://localhost:5267
- **可用项目**: journal, chinook, ecommerce, crm, todo

### 关键 URL 示例
| 页面 | URL | UI 类型 |
|------|-----|---------|
| Journal 首页 | `/Home?project=journal` | - |
| Journal Entry (自定义) | `/ui/Entry?project=journal&ui=custom` | 时间线 |
| Journal Entry (通用) | `/ui/Entry?project=journal` | 表格 |
| Artist (通用) | `/ui/Artist?project=chinook` | 表格 |

### YAML 配置示例
```yaml
models:
  Entry:
    table: Entry
    custom_view:
      enabled: true
      type: custom
      default_ui_mode: generic    # generic=表格默认，custom=时间线默认
      list_template: "views/entry/List.cshtml"
      form_template: "views/entry/Form.cshtml"
      style:
        layout: timeline
        show_stats: true
```

---
**更新时间**: 2026-02-21

---

## Summary Metadata
**Update time**: 2026-02-21T00:19:58.835Z 
