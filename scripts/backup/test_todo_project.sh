#!/bin/bash
# 测试 TODO 项目页面

cd /home/ubuntu/ws/lcp

echo "╔════════════════════════════════════════════════════════╗"
echo "║           TODO 项目页面验证                            ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# 设置环境变量
export LCP_PROJECT=todo
export LCP_DB_PATH=/home/ubuntu/ws/lcp/todo.db

echo "环境变量:"
echo "  LCP_PROJECT=$LCP_PROJECT"
echo "  LCP_DB_PATH=$LCP_DB_PATH"
echo ""

# 检查文件
echo "检查文件:"
echo -n "  todo_app.yaml: "
if [ -f "Definitions/todo_app.yaml" ]; then
    echo "✅ 存在"
else
    echo "❌ 不存在"
fi

echo -n "  todo.db: "
if [ -f "$LCP_DB_PATH" ]; then
    echo "✅ 存在"
else
    echo "❌ 不存在"
fi
echo ""

# 检查 YAML 中定义的模型
echo "YAML 中定义的模型:"
grep -E "^  [A-Z]" Definitions/todo_app.yaml | head -10
echo ""

# 检查数据库表
echo "数据库中的表:"
sqlite3 $LCP_DB_PATH ".tables"
echo ""

# 检查数据
echo "数据统计:"
echo "  - Task 表记录数：$(sqlite3 $LCP_DB_PATH 'SELECT COUNT(*) FROM Task;')"
echo "  - Project 表记录数：$(sqlite3 $LCP_DB_PATH 'SELECT COUNT(*) FROM Project;')"
echo ""

# 启动应用（后台）
echo "启动应用..."
dotnet run --project Platform.Api &
APP_PID=$!

# 等待应用启动
sleep 5

# 测试页面
echo ""
echo "测试页面:"

# 测试首页
echo -n "  首页 (/): "
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/ 2>/dev/null)
if [ "$RESPONSE" = "200" ] || [ "$RESPONSE" = "302" ]; then
    echo "✅ OK ($RESPONSE)"
else
    echo "❌ FAILED ($RESPONSE)"
fi

# 测试 Task 列表页
echo -n "  Task 列表 (/ui/Task): "
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/ui/Task 2>/dev/null)
if [ "$RESPONSE" = "200" ]; then
    echo "✅ OK ($RESPONSE)"
else
    echo "❌ FAILED ($RESPONSE)"
fi

# 测试 Project 列表页
echo -n "  Project 列表 (/ui/Project): "
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/ui/Project 2>/dev/null)
if [ "$RESPONSE" = "200" ]; then
    echo "✅ OK ($RESPONSE)"
else
    echo "❌ FAILED ($RESPONSE)"
fi

# 测试 TaskWithProject 视图
echo -n "  TaskWithProject 视图 (/ui/TaskWithProject): "
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/ui/TaskWithProject 2>/dev/null)
if [ "$RESPONSE" = "200" ]; then
    echo "✅ OK ($RESPONSE)"
else
    echo "❌ FAILED ($RESPONSE)"
fi

# 测试 ProjectStats 视图
echo -n "  ProjectStats 视图 (/ui/ProjectStats): "
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/ui/ProjectStats 2>/dev/null)
if [ "$RESPONSE" = "200" ]; then
    echo "✅ OK ($RESPONSE)"
else
    echo "❌ FAILED ($RESPONSE)"
fi

# 测试 API
echo ""
echo "测试 API:"
echo -n "  /api/Task: "
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/api/Task 2>/dev/null)
if [ "$RESPONSE" = "200" ]; then
    echo "✅ OK ($RESPONSE)"
    # 显示前 2 条任务数据
    echo "     示例数据:"
    curl -s http://localhost:5267/api/Task 2>/dev/null | head -c 200
    echo "..."
else
    echo "❌ FAILED ($RESPONSE)"
fi

echo -n "  /api/Project: "
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/api/Project 2>/dev/null)
if [ "$RESPONSE" = "200" ]; then
    echo "✅ OK ($RESPONSE)"
else
    echo "❌ FAILED ($RESPONSE)"
fi

# 停止应用
echo ""
echo "停止应用..."
kill $APP_PID 2>/dev/null

echo ""
echo "╔════════════════════════════════════════════════════════╗"
echo "║                  验证完成！                            ║"
echo "╠════════════════════════════════════════════════════════╣"
echo "║  访问地址：http://localhost:5267                       ║"
echo "║  - 首页：http://localhost:5267/                        ║"
echo "║  - Task 列表：http://localhost:5267/ui/Task            ║"
echo "║  - Project 列表：http://localhost:5267/ui/Project      ║"
echo "║  - 任务项目视图：http://localhost:5267/ui/TaskWithProject  ║"
echo "║  - 项目统计：http://localhost:5267/ui/ProjectStats     ║"
echo "╚════════════════════════════════════════════════════════╝"
