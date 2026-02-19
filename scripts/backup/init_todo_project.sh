#!/bin/bash
# TODO 项目快速初始化脚本

echo "╔════════════════════════════════════════════════════════╗"
echo "║           TODO 项目初始化                              ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# 设置项目根目录
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
DEFINITIONS_DIR="$SCRIPT_DIR/Definitions"

# 检查文件是否存在
if [ ! -f "$DEFINITIONS_DIR/todo_app.yaml" ]; then
    echo "❌ 错误：todo_app.yaml 不存在"
    exit 1
fi

if [ ! -f "$DEFINITIONS_DIR/todo_schema.sql" ]; then
    echo "❌ 错误：todo_schema.sql 不存在"
    exit 1
fi

# 创建数据库
DB_FILE="$SCRIPT_DIR/todo.db"
echo "📦 创建数据库：$DB_FILE"

# 删除旧数据库（如果需要）
if [ -f "$DB_FILE" ]; then
    echo "⚠️  发现现有数据库，是否删除？(y/N): "
    read -r response
    if [[ "$response" =~ ^[Yy]$ ]]; then
        rm "$DB_FILE"
        echo "✅ 已删除旧数据库"
    fi
fi

# 初始化数据库
echo "🔧 初始化数据库表结构..."
sqlite3 "$DB_FILE" < "$DEFINITIONS_DIR/todo_schema.sql"

if [ $? -eq 0 ]; then
    echo "✅ 数据库初始化成功！"
else
    echo "❌ 数据库初始化失败"
    exit 1
fi

# 验证数据
echo ""
echo "📊 验证数据..."
TASK_COUNT=$(sqlite3 "$DB_FILE" "SELECT COUNT(*) FROM Task;")
PROJECT_COUNT=$(sqlite3 "$DB_FILE" "SELECT COUNT(*) FROM Project;")

echo "   - 项目数：$PROJECT_COUNT"
echo "   - 任务数：$TASK_COUNT"

echo ""
echo "╔════════════════════════════════════════════════════════╗"
echo "║                  初始化完成！                          ║"
echo "╠════════════════════════════════════════════════════════╣"
echo "║  启动方式：                                            ║"
echo "║  1. export LCP_PROJECT=todo                            ║"
echo "║  2. export LCP_DB_PATH=$DB_FILE                        ║"
echo "║  3. dotnet run --project Platform.Api                  ║"
echo "║                                                        ║"
echo "║  访问地址：http://localhost:5267                       ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""
