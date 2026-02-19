#!/bin/bash
# 测试 TODO 项目页面 - 显示详细错误

cd /home/ubuntu/ws/lcp

# 设置环境变量
export LCP_PROJECT=todo
export LCP_DB_PATH=/home/ubuntu/ws/lcp/todo.db

echo "启动 TODO 项目应用..."

# 启动应用（后台）
dotnet run --project Platform.Api > /tmp/lcp_todo.log 2>&1 &
APP_PID=$!

echo "应用 PID: $APP_PID"
echo "等待应用启动..."

# 等待应用启动
for i in {1..10}; do
    if curl -s http://localhost:5267/ > /dev/null 2>&1; then
        echo "✅ 应用已启动"
        break
    fi
    sleep 1
done

echo ""
echo "═══════════════════════════════════════════════════════"
echo "测试页面"
echo "═══════════════════════════════════════════════════════"

# 测试首页
echo ""
echo "1. 首页 (/):"
curl -s -L http://localhost:5267/ 2>/dev/null | grep -o '<title>.*</title>' | head -1

# 测试 Task 列表页
echo ""
echo "2. Task 列表 (/ui/Task):"
RESPONSE=$(curl -s http://localhost:5267/ui/Task 2>/dev/null)
if echo "$RESPONSE" | grep -q "500"; then
    echo "   ❌ 返回 500 错误"
else
    echo "   ✅ 正常"
    echo "$RESPONSE" | grep -o '<h[1-3].*>.*</h[1-3]>' | head -3
fi

# 测试 Project 列表页
echo ""
echo "3. Project 列表 (/ui/Project):"
RESPONSE=$(curl -s http://localhost:5267/ui/Project 2>/dev/null)
if echo "$RESPONSE" | grep -q "500"; then
    echo "   ❌ 返回 500 错误"
else
    echo "   ✅ 正常"
fi

# 测试 API
echo ""
echo "4. API 测试 (/api/Task):"
curl -s http://localhost:5267/api/Task 2>/dev/null | head -c 300
echo "..."

echo ""
echo ""
echo "═══════════════════════════════════════════════════════"
echo "应用日志（最后 30 行）"
echo "═══════════════════════════════════════════════════════"
tail -30 /tmp/lcp_todo.log

echo ""
echo "停止应用..."
kill $APP_PID 2>/dev/null
