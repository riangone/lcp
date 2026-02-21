# TODO 项目验证说明

## ✅ 已完成的配置

### 1. 文件检查
- ✅ `Definitions/todo_app.yaml` - TODO 项目 YAML 定义
- ✅ `Definitions/todo_schema.sql` - 数据库表结构
- ✅ `todo.db` - SQLite 数据库（含示例数据）

### 2. YAML 定义的模型
```yaml
models:
  Task:              # 任务表
  Project:           # 项目表
  TaskWithProject:   # 任务项目关联视图（只读）
  ProjectStats:      # 项目统计视图（只读）
```

### 3. 数据库内容
- Project 表：5 个项目
- Task 表：15 条任务

## 🚀 启动方式

```bash
# 方式 1: 使用 env 命令
env LCP_PROJECT=todo LCP_DB_PATH=/home/ubuntu/ws/lcp/todo.db dotnet run --project Platform.Api

# 方式 2: 使用 export
export LCP_PROJECT=todo
export LCP_DB_PATH=/home/ubuntu/ws/lcp/todo.db
dotnet run --project Platform.Api
```

## 📋 访问页面

启动应用后，访问以下页面：

| 页面 | URL | 说明 |
|------|-----|------|
| 首页 | http://localhost:5267/Home | 显示所有模型列表 |
| Task 列表 | http://localhost:5267/Task | 任务管理页面 |
| Project 列表 | http://localhost:5267/Project | 项目管理页面 |
| TaskWithProject | http://localhost:5267/TaskWithProject | 任务项目关联视图 |
| ProjectStats | http://localhost:5267/ProjectStats | 项目统计视图 |

## 🔌 API 端点

| 端点 | 说明 |
|------|------|
| GET /api/Task | 获取所有任务 |
| GET /api/Project | 获取所有项目 |
| GET /api/TaskWithProject | 获取任务项目关联数据 |
| GET /api/ProjectStats | 获取项目统计数据 |

## ✅ 验证步骤

### 1. 检查文件
```bash
ls -la Definitions/todo_app.yaml
ls -la Definitions/todo_schema.sql
ls -la todo.db
```

### 2. 检查 YAML 模型
```bash
grep "^  [A-Z]" Definitions/todo_app.yaml
```

### 3. 检查数据库
```bash
sqlite3 todo.db ".tables"
sqlite3 todo.db "SELECT COUNT(*) FROM Project;"
sqlite3 todo.db "SELECT COUNT(*) FROM Task;"
```

### 4. 启动应用
```bash
env LCP_PROJECT=todo LCP_DB_PATH=$(pwd)/todo.db dotnet run --project Platform.Api
```

### 5. 访问页面
在浏览器中打开 http://localhost:5267

## 📝 注意事项

1. **端口占用**: 如果端口 5267 被占用，先停止之前的进程：
   ```bash
   pkill -f "dotnet.*Platform.Api"
   ```

2. **环境变量**: 确保环境变量正确传递：
   - `LCP_PROJECT=todo` - 指定项目
   - `LCP_DB_PATH=...` - 指定数据库路径

3. **日志查看**: 应用日志会显示加载的 YAML 文件：
   ```
   [PROJECT] Loading YAML from: /path/to/todo_app.yaml
   ```

## 🎯 预期结果

启动成功后，首页应显示：
- 标题："📋 TODO 项目管理"
- 4 个模型卡片：Task, Project, TaskWithProject, ProjectStats

访问 `/Task` 应显示：
- 任务列表（15 条数据）
- 分页功能
- 过滤功能（按标题、状态、优先级）
- 新建/编辑/删除按钮
