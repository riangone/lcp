# 运行时项目切换功能说明

## ✅ 已实现功能

1. **项目扫描和加载**
   - ProjectManager 自动扫描 Projects 目录
   - 加载所有包含 project.yaml 的项目
   - 缓存项目配置和 AppDefinitions

2. **项目作用域服务**
   - ProjectScope 每请求级别存储当前项目
   - 支持通过 URL 参数切换项目

3. **首页项目卡片**
   - 点击卡片跳转到 `/?project=xxx`
   - 显示切换提示

## ⚠️ 当前限制

由于架构限制，完整的运行时项目切换需要以下调整：

1. **AppDefinitions 作用域问题**
   - 当前是 Singleton，所有请求共享
   - 需要改为 Scoped，每请求独立

2. **数据库连接作用域**
   - 需要根据项目动态切换数据库
   - 已实现但需要更多测试

## 🔧 当前使用方式

### 方式 1: 重启应用切换项目（推荐）
```bash
# 切换到 TODO 项目
pkill dotnet
export LCP_PROJECT=todo
dotnet run --project Platform.Api

# 切换到日记本项目
pkill dotnet
export LCP_PROJECT=journal
dotnet run --project Platform.Api
```

### 方式 2: 使用 URL 参数（实验性）
```
http://localhost:5267/?project=todo
http://localhost:5267/?project=journal
```

注意：由于 AppDefinitions 是 singleton，URL 参数切换可能不会完全生效。

## 📋 后续完善计划

1. 将 AppDefinitions 改为 scoped
2. 添加项目切换 API 端点
3. 添加项目预加载策略
4. 支持项目热重载

## 📁 项目目录结构

```
Projects/
├── todo/           # TODO 项目管理
│   ├── project.yaml
│   ├── app.yaml
│   ├── schema.sql
│   ├── data.sql
│   └── todo.db
│
└── journal/        # 日记本应用
    ├── project.yaml
    ├── app.yaml
    ├── schema.sql
    ├── data.sql
    └── journal.db
```

## 🚀 启动说明

```bash
cd /home/ubuntu/ws/lcp

# 清理旧进程
pkill -9 dotnet

# 启动应用（自动扫描所有项目）
dotnet run --project Platform.Api
```

启动后访问 http://localhost:5267 查看首页。
