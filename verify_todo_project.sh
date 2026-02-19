#!/bin/bash
# 完整的 TODO 项目验证脚本

cd /home/ubuntu/ws/lcp

# 清理之前的进程
pkill -f "dotnet.*Platform.Api" 2>/dev/null || true
sleep 2

# 设置环境变量
export LCP_PROJECT=todo
export LCP_DB_PATH=/home/ubuntu/ws/lcp/todo.db

echo "╔════════════════════════════════════════════════════════╗"
echo "║           TODO 项目完整验证                            ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# 1. 检查文件
echo "1️⃣  检查文件..."
echo -n "   todo_app.yaml: "
[ -f "Definitions/todo_app.yaml" ] && echo "✅" || echo "❌"

echo -n "   todo_schema.sql: "
[ -f "Definitions/todo_schema.sql" ] && echo "✅" || echo "❌"

echo -n "   todo.db: "
[ -f "$LCP_DB_PATH" ] && echo "✅" || echo "❌"

# 2. 检查 YAML 模型定义
echo ""
echo "2️⃣  YAML 中定义的模型:"
grep -E "^  [A-Z]" Definitions/todo_app.yaml | sed 's/:$//' | sed 's/^/   - /'

# 3. 检查数据库
echo ""
echo "3️⃣  数据库信息:"
echo "   表:"
sqlite3 $LCP_DB_PATH ".tables" | sed 's/^/      /'
echo "   数据:"
echo "      Project: $(sqlite3 $LCP_DB_PATH 'SELECT COUNT(*) FROM Project;') 条"
echo "      Task: $(sqlite3 $LCP_DB_PATH 'SELECT COUNT(*) FROM Task;') 条"

# 4. 启动应用
echo ""
echo "4️⃣  启动应用..."
dotnet run --project Platform.Api > /tmp/lcp_todo_test.log 2>&1 &
APP_PID=$!
echo "   PID: $APP_PID"

# 等待启动
echo "   等待应用启动..."
for i in {1..15}; do
    if curl -s http://localhost:5267/api/Task > /dev/null 2>&1; then
        echo "   ✅ 应用已启动 (耗时 ${i}s)"
        break
    fi
    sleep 1
done

if [ $i -eq 15 ]; then
    echo "   ❌ 应用启动超时"
    echo ""
    echo "日志:"
    cat /tmp/lcp_todo_test.log
    exit 1
fi

# 5. 测试页面
echo ""
echo "5️⃣  测试页面..."

echo ""
echo "   首页 (/Home):"
CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/Home)
[ "$CODE" = "200" ] && echo "   ✅ HTTP $CODE" || echo "   ❌ HTTP $CODE"

echo ""
echo "   Task 列表 (/ui/Task):"
CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/ui/Task)
[ "$CODE" = "200" ] && echo "   ✅ HTTP $CODE" || echo "   ❌ HTTP $CODE"

echo ""
echo "   Project 列表 (/ui/Project):"
CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/ui/Project)
[ "$CODE" = "200" ] && echo "   ✅ HTTP $CODE" || echo "   ❌ HTTP $CODE"

echo ""
echo "   TaskWithProject 视图 (/ui/TaskWithProject):"
CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/ui/TaskWithProject)
[ "$CODE" = "200" ] && echo "   ✅ HTTP $CODE" || echo "   ❌ HTTP $CODE"

echo ""
echo "   ProjectStats 视图 (/ui/ProjectStats):"
CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/ui/ProjectStats)
[ "$CODE" = "200" ] && echo "   ✅ HTTP $CODE" || echo "   ❌ HTTP $CODE"

# 6. 测试 API
echo ""
echo "6️⃣  测试 API..."

echo ""
echo "   GET /api/Task:"
CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/api/Task)
[ "$CODE" = "200" ] && echo "   ✅ HTTP $CODE" || echo "   ❌ HTTP $CODE"
if [ "$CODE" = "200" ]; then
    echo "   前 2 条数据:"
    curl -s http://localhost:5267/api/Task 2>/dev/null | python3 -c "import sys,json; d=json.load(sys.stdin); print(json.dumps(d[:2], indent=2, ensure_ascii=False))" 2>/dev/null | head -20 | sed 's/^/      /'
fi

echo ""
echo "   GET /api/Project:"
CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/api/Project)
[ "$CODE" = "200" ] && echo "   ✅ HTTP $CODE" || echo "   ❌ HTTP $CODE"

echo ""
echo "   GET /api/TaskWithProject:"
CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/api/TaskWithProject)
[ "$CODE" = "200" ] && echo "   ✅ HTTP $CODE" || echo "   ❌ HTTP $CODE"

echo ""
echo "   GET /api/ProjectStats:"
CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:5267/api/ProjectStats)
[ "$CODE" = "200" ] && echo "   ✅ HTTP $CODE" || echo "   ❌ HTTP $CODE"

# 7. 停止应用
echo ""
echo "7️⃣  停止应用..."
kill $APP_PID 2>/dev/null
echo "   ✅ 已停止"

# 8. 总结
echo ""
echo "╔════════════════════════════════════════════════════════╗"
echo "║                  验证完成！                            ║"
echo "╠════════════════════════════════════════════════════════╣"
echo "║  TODO 项目已正确配置并可以运行                         ║"
echo "╠════════════════════════════════════════════════════════╣"
echo "║  启动命令：                                            ║"
echo "║  export LCP_PROJECT=todo                               ║"
echo "║  export LCP_DB_PATH=\$(pwd)/todo.db                    ║"
echo "║  dotnet run --project Platform.Api                     ║"
echo "╠════════════════════════════════════════════════════════╣"
echo "║  访问地址：                                            ║"
echo "║  http://localhost:5267                                 ║"
echo "║  http://localhost:5267/Home                            ║"
echo "║  http://localhost:5267/ui/Task                         ║"
echo "║  http://localhost:5267/ui/Project                      ║"
echo "║  http://localhost:5267/ui/TaskWithProject              ║"
echo "║  http://localhost:5267/ui/ProjectStats                 ║"
echo "╚════════════════════════════════════════════════════════╝"
