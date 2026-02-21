#!/bin/bash
# 测试创建 Task

cd /home/ubuntu/ws/lcp

echo "╔════════════════════════════════════════════════════════╗"
echo "║           测试创建 Task                                ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# 先获取 CSRF token
echo "1. 获取 CSRF Token..."
curl -s http://localhost:5267/Task -o /tmp/lcp_task_page.html 2>/dev/null
TOKEN=$(grep -o 'name="__RequestVerificationToken" value="[^"]*"' /tmp/lcp_task_page.html | head -1 | sed 's/name="__RequestVerificationToken" value="//' | sed 's/"$//')

if [ -z "$TOKEN" ]; then
    echo "   无法获取 CSRF token"
    TOKEN="test_token"
fi
echo "   Token: ${TOKEN:0:30}..."
echo ""

# 测试创建
echo "2. 创建新 Task..."
echo "   数据:"
echo "     Title: Test Task"
echo "     Status: pending"
echo "     Priority: medium"
echo ""

curl -s -X POST http://localhost:5267/api/Task \
    -H "X-CSRF-TOKEN: $TOKEN" \
    -F "Title=Test Task" \
    -F "Description=This is a test task created via script" \
    -F "Status=pending" \
    -F "Priority=medium" \
    -F "DueDate=2026-03-01" \
    2>&1

echo ""
echo ""

# 检查是否创建成功
echo "3. 验证创建结果..."
curl -s http://localhost:5267/api/Task 2>/dev/null | python3 -c "
import sys, json
try:
    data = json.load(sys.stdin)
    print(f'   当前任务总数：{len(data)}')
    if len(data) > 0:
        last_task = data[-1]
        print(f'   最后一条任务：{last_task.get(\"Title\", \"N/A\")}')
except Exception as e:
    print(f'   验证失败：{e}')
"

echo ""
echo ""
echo "4. 查看最新日志..."
tail -20 /tmp/lcp_todo_server.log | grep -i error || echo "   没有明显错误"
