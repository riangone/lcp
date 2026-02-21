#!/bin/bash
# HMSS 开发服务器启动脚本

cd /home/ubuntu/ws/hmss

# 检查 PHP 是否安装
if ! command -v php &> /dev/null; then
    echo "错误：PHP 未安装"
    exit 1
fi

# 检查 SQLite 数据库文件是否存在
if [ ! -f config/hmss.sqlite ]; then
    touch config/hmss.sqlite
    chmod 664 config/hmss.sqlite
    echo "创建了 SQLite 数据库文件：config/hmss.sqlite"
fi

# 设置目录权限
chmod -R 775 logs/ tmp/ config/hmss.sqlite webroot/files/ 2>/dev/null

# 启动服务器
echo "启动 HMSS 开发服务器..."
echo "访问地址：http://0.0.0.0:8765/"
echo "按 Ctrl+C 停止服务器"
echo ""

php -S 0.0.0.0:8765 -t webroot
