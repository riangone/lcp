import re

with open('Platform.Api/Views/Ui/FormModal.cshtml', 'r') as f:
    content = f.read()

# 替换 CSRF 相关代码
old_pattern = r"// 自动添加 CSRF 令牌到 htmx 请求\s+document.addEventListener\('htmx:configRequest', function\(evt\) \{[^}]+\}\);"
new_code = """// 自动添加 CSRF 令牌到 htmx 请求
  document.addEventListener('htmx:configRequest', function(evt) {
    const token = document.querySelector('input[name="__RequestVerificationToken"]');
    if (token && token.value) {
      evt.detail.parameters['__RequestVerificationToken'] = token.value;
    }
  });"""

content = re.sub(old_pattern, new_code, content)

with open('Platform.Api/Views/Ui/FormModal.cshtml', 'w') as f:
    f.write(content)

print("✅ 修改完成")
