namespace Platform.Api.Controllers;

/// <summary>
/// HMSS 登录请求
/// </summary>
public class HmssLoginRequest
{
    public string UserId { get; set; } = "";
    public string Password { get; set; } = "";
    public bool RememberMe { get; set; }
}

/// <summary>
/// HMSS 修改密码请求
/// </summary>
public class HmssChangePasswordRequest
{
    public string OldPassword { get; set; } = "";
    public string NewPassword { get; set; } = "";
}
