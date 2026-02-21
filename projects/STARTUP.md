# HMSS 系统启动说明

## 系统已配置完成

### 当前配置

- **PHP 版本**: 8.3
- **数据库**: SQLite
- **数据库文件**: `config/hmss.sqlite`
- **开发服务器**: PHP 内置服务器
- **访问地址**: http://localhost:8765/

### 启动方法

```bash
# 方法 1: 使用启动脚本
./start_server.sh

# 方法 2: 直接启动
php -S localhost:8765 -t webroot
```

### 目录结构

```
hmss/
├── config/
│   ├── app_local.php      # SQLite 配置
│   └── hmss.sqlite        # SQLite 数据库文件
├── logs/                   # 日志目录
├── tmp/                    # 临时目录
├── webroot/                # Web 根目录
└── start_server.sh         # 启动脚本
```

### 初始登录信息

SQLite 数据库中已创建以下测试用户：

| 用户 ID | 密码 | 名称 |
|--------|------|------|
| `admin` | `admin123` | 管理员 |
| `test` | `test123` | 测试用户 |
| `gdmz` | `gdmz123` | GDMZ 用户 |

**注意**: 生产环境务必修改密码！

### 注意事项

1. **数据库**: 当前使用 SQLite，适用于开发测试。生产环境需要配置 MySQL。

2. **权限**: 确保以下目录可写：
   - `logs/`
   - `tmp/`
   - `config/hmss.sqlite`

3. **敏感信息**: 
   - `config/app_local.php` 包含示例配置
   - 生产环境应使用环境变量配置数据库和密码

### 停止服务器

按 `Ctrl+C` 停止开发服务器。

### 常见问题

**Q: 端口 8765 已被占用？**
```bash
# 查找占用端口的进程
lsof -i :8765
# 杀死进程
kill -9 <PID>
```

**Q: 权限错误？**
```bash
chmod -R 775 logs/ tmp/ config/hmss.sqlite
```

**Q: 需要 MySQL 数据库？**
编辑 `config/app_local.php`，修改 Datasources 配置为 MySQL。
