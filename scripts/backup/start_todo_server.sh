#!/bin/bash
# 启动 TODO 项目服务器

cd /home/ubuntu/ws/lcp

# 清理旧进程
pkill -9 -f "dotnet" 2>/dev/null || true
sleep 2

# 设置环境变量
export LCP_PROJECT=todo
export LCP_DB_PATH=/home/ubuntu/ws/lcp/todo.db

echo "╔════════════════════════════════════════════════════════╗"
echo "║           启动 TODO 项目服务器                         ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""
echo "项目：$LCP_PROJECT"
echo "数据库：$LCP_DB_PATH"
echo ""

# 启动服务器（后台）
nohup dotnet run --project Platform.Api > /tmp/lcp_todo_server.log 2>&1 &
PID=$!
echo "服务器 PID: $PID"
echo ""

# 等待启动
echo "等待服务器启动..."
for i in 1 2 3 4 5 6 7 8 9 10; do
    sleep 1
    if curl -s http://localhost:5267/api/Task > /dev/null 2>&1; then
        echo "✅ 服务器已启动！(耗时 ${i}s)"
        break
    fi
    echo "  等待中... (${i}s)"
done

echo ""
echo "═══════════════════════════════════════════════════════"
echo "访问地址:"
echo "  首页：http://localhost:5267/Home"
echo "  Task: http://localhost:5267/ui/Task"
echo "  Project: http://localhost:5267/ui/Project"
echo "  API: http://localhost:5267/api/Task"
echo "═══════════════════════════════════════════════════════"
echo ""

# 测试 API
echo "测试 API:"
curl -s http://localhost:5267/api/Task | python3 -c "import sys,json; d=json.load(sys.stdin); print(f'  Task API: 返回 {len(d)} 条数据')" 2>/dev/null || echo "  Task API: 无法访问"

curl -s http://localhost:5267/api/Project | python3 -c "import sys,json; d=json.load(sys.stdin); print(f'  Project API: 返回 {len(d)} 条数据')" 2>/dev/null || echo "  Project API: 无法访问"

echo ""
echo "服务器进程 PID: $PID (运行中)"
echo "日志文件：/tmp/lcp_todo_server.log"
