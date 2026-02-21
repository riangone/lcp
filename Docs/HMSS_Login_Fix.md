# HMSS 登录功能修复记录

## 问题描述

访问 `http://localhost:5268/hmss/sdhproject=hmss` 时显示"用户 ID 或密码错误"，无法正常登录。

## 问题原因

1. **URL 格式错误**：
   - ❌ 错误：`/hmss/sdhproject=hmss`
   - ✅ 正确：`/hmss/sdh?project=hmss`

2. **认证逻辑未实现**：
   - `HmssAuthController.cs` 中的登录验证是硬编码的简化实现
   - 没有真正从数据库验证用户凭据
   - 密码验证未支持 BCrypt 哈希

3. **数据库密码为无效哈希**：
   - `hmss_users` 表中的密码是占位符数据，不是有效的 BCrypt 哈希

## 修复内容

### 1. 添加 BCrypt.Net-Next 包引用

**文件**: `Platform.Api/Platform.Api.csproj`

```xml
<PackageReference Include="BCrypt.Net-Next" Version="4.0.3" />
```

### 2. 重写 HmssAuthController 登录逻辑

**文件**: `Platform.Api/Controllers/HmssAuthController.cs`

主要修改：
- 从数据库 `hmss_users` 表查询用户
- 支持 BCrypt 哈希密码验证
- 支持明文密码比较（开发环境）
- 构建用户权限 Claims（包括各系统权限标志）
- 记录登录日志

关键代码：
```csharp
// 从数据库验证用户
var user = await conn.QueryFirstOrDefaultAsync(userSql, new { UserId = request.UserId });

// 验证密码（BCrypt 或明文）
if (user.pass.StartsWith("$2a$") || user.pass.StartsWith("$2b$"))
{
    passwordValid = BCrypt.Net.BCrypt.Verify(request.Password, user.pass);
}
else
{
    passwordValid = request.Password == user.pass;
}
```

### 3. 更新数据库密码哈希

**数据库**: `/home/ubuntu/ws/lcp/Projects/hmss/hmss.db`

```sql
UPDATE hmss_users SET pass = '$2b$12$lhiFpjbmv23yO0YeHuC1IueREBBuV05blGA3.ioUd6FM0ZywanTQm' 
WHERE usr_id = 'admin';
```

密码：`admin123`

### 4. 更新 Schema 文件

**文件**: 
- `Definitions/hmss/schema.sql`
- `Projects/hmss/schema.sql`

更新初始用户密码为正确的 BCrypt 哈希。

## 测试验证

### 测试 1：正确密码登录
```bash
curl -X POST http://localhost:5268/api/hmss/auth/login \
  -H "Content-Type: application/json" \
  -d '{"userId":"admin","password":"admin123","rememberMe":true}'
```

**结果**: ✅ 成功
```json
{
  "success": true,
  "message": "登录成功",
  "data": {
    "userId": "admin",
    "userName": "管理员",
    "redirectUrl": "/hmss/master"
  }
}
```

### 测试 2：错误密码登录
```bash
curl -X POST http://localhost:5268/api/hmss/auth/login \
  -H "Content-Type: application/json" \
  -d '{"userId":"admin","password":"wrongpassword","rememberMe":true}'
```

**结果**: ✅ 正确返回错误
```json
{
  "success": false,
  "message": "用户 ID 或密码错误"
}
```

### 测试 3：访问受保护页面
```bash
curl -s http://localhost:5268/hmss/sdh?project=hmss -b /tmp/hmss_cookies.txt
```

**结果**: ✅ 成功返回 SDH 子系统页面

## 使用说明

### 登录页面
```
http://localhost:5268/hmss/login
```

### 默认凭据
- **用户 ID**: `admin`
- **密码**: `admin123`

### 正确的 URL 格式

| 功能 | URL |
|------|------|
| 登录页面 | `/hmss/login` |
| 主页面 | `/hmss/master` |
| SDH 系统 | `/hmss/sdh?project=hmss` |
| HDKAIKEI 系统 | `/hmss/hdkaikei?project=hmss` |
| HMHRMS 系统 | `/hmss/hmhrms?project=hmss` |

## 注意事项

1. **URL 格式**：使用查询参数格式 `?project=hmss`，不要拼接在路径后面
2. **密码哈希**：生产环境应使用 BCrypt 哈希，不要使用明文密码
3. **Cookie 认证**：登录后会设置 `HMSS_AUTH` Cookie，有效期 7 天
4. **权限系统**：用户权限通过 `sys1_flg` ~ `sys14_flg` 字段控制各系统访问权限

## 相关文件

| 文件 | 说明 |
|------|------|
| `Platform.Api/Controllers/HmssAuthController.cs` | 认证控制器 |
| `Platform.Api/Controllers/HmssMvcController.cs` | HMSS MVC 页面控制器 |
| `Platform.Api/Views/Hmss/Login.cshtml` | 登录页面视图 |
| `Platform.Api/Platform.Api.csproj` | 项目配置（BCrypt 引用） |
| `Definitions/hmss/schema.sql` | 数据库 Schema |
| `Projects/hmss/hmss.db` | HMSS 数据库 |

## 修复日期
2026-02-21
