namespace Platform.Domain.Entities;

/// <summary>
/// 用户实体
/// </summary>
public class User
{
    public int UserId { get; set; }
    public string Username { get; set; } = "";
    public string Email { get; set; } = "";
    public string PasswordHash { get; set; } = "";
    public string? DisplayName { get; set; }
    public string Role { get; set; } = "User";
    public string? Avatar { get; set; }
    public string? Bio { get; set; }
    public bool IsActive { get; set; } = true;
    public DateTime? LastLoginAt { get; set; }
    public string? LastLoginIP { get; set; }
    public DateTime CreatedAt { get; set; }
    public DateTime UpdatedAt { get; set; }
    public string? CreatedBy { get; set; }
    public string? UpdatedBy { get; set; }
}

/// <summary>
/// 用户角色
/// </summary>
public class UserRole
{
    public int RoleId { get; set; }
    public string RoleName { get; set; } = "";
    public string? Description { get; set; }
    public string? Permissions { get; set; }
    public DateTime CreatedAt { get; set; }
}

/// <summary>
/// 用户会话
/// </summary>
public class UserSession
{
    public string SessionId { get; set; } = "";
    public int UserId { get; set; }
    public string Token { get; set; } = "";
    public string? IPAddress { get; set; }
    public string? UserAgent { get; set; }
    public DateTime ExpiresAt { get; set; }
    public DateTime CreatedAt { get; set; }
}

/// <summary>
/// 登录请求
/// </summary>
public class LoginRequest
{
    public string Username { get; set; } = "";
    public string Password { get; set; } = "";
    public bool RememberMe { get; set; }
}

/// <summary>
/// 注册请求
/// </summary>
public class RegisterRequest
{
    public string Username { get; set; } = "";
    public string Email { get; set; } = "";
    public string Password { get; set; } = "";
    public string? DisplayName { get; set; }
}

/// <summary>
/// 认证结果
/// </summary>
public class AuthResult
{
    public bool Success { get; set; }
    public string? Token { get; set; }
    public string? RefreshToken { get; set; }
    public User? User { get; set; }
    public string? Message { get; set; }
    public int ExpiresIn { get; set; }  // 秒
}
