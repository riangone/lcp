# 创建 Task 问题调试总结

## 问题描述
通过 UI 页面创建 Task 时返回 400 Bad Request 错误。

## 调试过程

### 1. 发现问题
- ✅ GET /api/Task - 正常返回 16 条数据
- ❌ POST /api/Task - 返回 400 Bad Request

### 2. 原因分析
CSRF 验证失败。ASP.NET Core 的 `[ValidateAntiForgeryToken]` 特性要求请求中包含有效的 CSRF token。

### 3. 已尝试的修复

#### 修复 1: 修改 htmx 配置
修改 `FormModal.cshtml` 中的 htmx CSRF token 发送方式：

```javascript
// 修改前
evt.detail.headers['RequestVerificationToken'] = token;

// 修改后  
evt.detail.parameters['__RequestVerificationToken'] = token;
```

#### 修复 2: 添加额外的 token 参数
在控制器中显式声明 token 参数：

```csharp
[HttpPost]
[ValidateAntiForgeryToken]
public async Task<IActionResult> Create(
    string model,
    [FromForm] Dictionary<string, string> data,
    [FromHeader] string? RequestVerificationToken,
    [FromForm] string? __RequestVerificationToken)
```

### 4. 当前状态

**无 CSRF 验证时**：创建成功 ✅
**有 CSRF 验证时**：返回 400 ❌

## 解决方案

### 方案 1: 禁用 CSRF 验证（推荐用于低代码平台）

对于主要通过 YAML 配置的低代码平台，可以禁用 CSRF 验证：

```csharp
[HttpPost]
// [ValidateAntiForgeryToken]  // 禁用 CSRF
public async Task<IActionResult> Create(...)
```

### 方案 2: 修复 htmx CSRF token 发送

确保 htmx 正确发送 CSRF token：

1. 在 `FormModal.cshtml` 中：
```html
@Html.AntiForgeryToken()
```

2. htmx 配置：
```javascript
document.addEventListener('htmx:configRequest', function(evt) {
  const token = document.querySelector('input[name="__RequestVerificationToken"]');
  if (token && token.value) {
    evt.detail.parameters['__RequestVerificationToken'] = token.value;
  }
});
```

3. 或者使用 htmx 内置的 CSRF 支持：
```html
<meta name="csrf-token" content="@tokens.GetCsrfToken(Context)">
```

```javascript
htmx.config.useTemplateFragments = true;
htmx.config.selfRequestsOnly = false;
```

### 方案 3: 使用表单提交而非 htmx

对于创建操作，使用传统表单提交：

```html
<form method="post" action="/api/Task">
  @Html.AntiForgeryToken()
  <!-- 表单字段 -->
  <button type="submit">Save</button>
</form>
```

## 当前修改的文件

1. `Platform.Api/Controllers/GenericApiController.cs`
   - 添加了调试日志
   - 添加了额外的 token 参数

2. `Platform.Api/Views/Ui/FormModal.cshtml`
   - 修改了 htmx CSRF token 发送方式

## 验证步骤

```bash
# 1. 启动服务器
export LCP_PROJECT=todo
export LCP_DB_PATH=$(pwd)/todo.db
dotnet run --project Platform.Api

# 2. 测试创建
curl -X POST http://localhost:5267/api/Task \
  -d "Title=Test Task" \
  -d "Status=pending" \
  -d "Priority=medium"

# 3. 验证结果
curl http://localhost:5267/api/Task
```

## 后续步骤

1. 如果禁用 CSRF，确保平台有其他安全措施（如认证、授权）
2. 如果需要 CSRF 保护，考虑使用 htmx 的内置 CSRF 支持
3. 测试其他 CRUD 操作（Update、Delete）是否也有同样问题
