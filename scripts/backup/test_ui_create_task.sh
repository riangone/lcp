#!/bin/bash
# 完整测试创建 Task（通过 UI 页面）

cd /home/ubuntu/ws/lcp

echo "╔════════════════════════════════════════════════════════╗"
echo "║           测试 UI 创建 Task                             ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# 1. 获取页面和 CSRF token
echo "1. 获取 Task 页面和 CSRF Token..."
curl -s http://localhost:5267/Task -o /tmp/lcp_task_page.html 2>/dev/null

# 提取 CSRF token (从 id="csrf-token" 的 input 中提取)
TOKEN=$(grep -o 'id="csrf-token" value="[^"]*"' /tmp/lcp_task_page.html | head -1 | sed 's/id="csrf-token" value="//' | sed 's/"$//')

if [ -z "$TOKEN" ]; then
    echo "   ❌ 无法获取 CSRF token"
    exit 1
fi
echo "   ✅ Token: ${TOKEN:0:30}..."
echo ""

# 2. 获取创建表单
echo "2. 获取创建表单..."
curl -s "http://localhost:5267/Task/create" -o /tmp/lcp_create_form.html 2>/dev/null
if grep -q "__RequestVerificationToken" /tmp/lcp_create_form.html; then
    echo "   ✅ 表单包含 CSRF token"
else
    echo "   ❌ 表单缺少 CSRF token"
fi
echo ""

# 3. 测试创建（使用正确的 CSRF token 参数名）
echo "3. 测试创建 Task..."
curl -s -X POST http://localhost:5267/api/Task \
    -d "Title=UI Test Task" \
    -d "Description=Created from UI test script" \
    -d "Status=pending" \
    -d "Priority=high" \
    -d "DueDate=2026-03-15" \
    -d "__RequestVerificationToken=$TOKEN" \
    2>&1 | head -5

echo ""
echo ""

# 4. 验证创建结果
echo "4. 验证创建结果..."
curl -s http://localhost:5267/api/Task 2>/dev/null | python3 -c "
import sys, json
try:
    data = json.load(sys.stdin)
    print(f'   当前任务总数：{len(data)}')
    
    # 查找最新创建的任务
    for task in reversed(data):
        if 'UI Test Task' in str(task.get('Title', '')):
            print(f'   ✅ 找到新创建的任务:')
            print(f'      ID: {task.get(\"Id\")}')
            print(f'      Title: {task.get(\"Title\")}')
            print(f'      Status: {task.get(\"Status\")}')
            print(f'      Priority: {task.get(\"Priority\")}')
            break
except Exception as e:
    print(f'   验证失败：{e}')
"

echo ""
echo "═══════════════════════════════════════════════════════"
echo "测试完成！"
echo "═══════════════════════════════════════════════════════"
