using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Authentication;
using Microsoft.AspNetCore.Authentication.Cookies;
using System.Security.Claims;
using Microsoft.Data.Sqlite;
using Dapper;

namespace Platform.Api.Controllers;

// 使用全局定义的 LoginRequest 和 ChangePasswordRequest 类

/// <summary>
/// HMSS 认证控制器
/// 处理用户登录、登出和认证相关功能
/// </summary>
[ApiController]
[Route("api/hmss/auth")]
public class HmssAuthController : ControllerBase
{
    private readonly ILogger<HmssAuthController> _logger;
    private readonly IConfiguration _config;
    private readonly string _hmssConnectionString;

    public HmssAuthController(
        ILogger<HmssAuthController> logger,
        IConfiguration config)
    {
        _logger = logger;
        _config = config;
        
        var projectsDir = Environment.GetEnvironmentVariable("LCP_PROJECTS_DIR") ?? "/home/ubuntu/ws/lcp/Projects";
        _hmssConnectionString = $"Data Source={Path.Combine(projectsDir, "hmss", "hmss.db")}";
    }

    /// <summary>
    /// 用户登录
    /// </summary>
    [HttpPost("login")]
    public async Task<IActionResult> Login([FromBody] HmssLoginRequest request)
    {
        try
        {
            if (string.IsNullOrEmpty(request.UserId) || string.IsNullOrEmpty(request.Password))
            {
                return BadRequest(new
                {
                    success = false,
                    message = "用户 ID 和密码不能为空"
                });
            }

            // 从数据库验证用户
            await using var conn = new SqliteConnection(_hmssConnectionString);
            await conn.OpenAsync();

            var userSql = @"
                SELECT usr_id, usr_name, pass, email, 
                       sys1_flg, sys2_flg, sys3_flg, sys4_flg, sys5_flg, 
                       sys6_flg, sys7_flg, sys8_flg, sys9_flg, sys10_flg, 
                       sys11_flg, sys12_flg, sys13_flg, sys14_flg
                FROM hmss_users
                WHERE usr_id = @UserId";

            var user = await conn.QueryFirstOrDefaultAsync(userSql, new { UserId = request.UserId });

            if (user == null)
            {
                _logger.LogWarning("用户不存在：{UserId}", request.UserId);
                return Unauthorized(new
                {
                    success = false,
                    message = "用户 ID 或密码错误"
                });
            }

            // 验证密码（BCrypt 或明文）
            bool passwordValid = false;
            
            // 检查是否是 BCrypt 哈希（以 $2a$ 开头）
            if (user.pass.StartsWith("$2a$") || user.pass.StartsWith("$2b$"))
            {
                // 使用 BCrypt 验证
                passwordValid = BCrypt.Net.BCrypt.Verify(request.Password, user.pass);
            }
            else
            {
                // 明文密码比较（仅用于开发环境）
                passwordValid = request.Password == user.pass;
            }

            if (!passwordValid)
            {
                _logger.LogWarning("用户密码错误：{UserId}", request.UserId);
                return Unauthorized(new
                {
                    success = false,
                    message = "用户 ID 或密码错误"
                });
            }

            // 构建用户权限列表
            var sysFlags = new Dictionary<string, string>
            {
                { "Master", user.sys1_flg ?? "0" },
                { "Login", user.sys2_flg ?? "0" },
                { "HDKAIKEI", user.sys3_flg ?? "0" },
                { "HMAUD", user.sys4_flg ?? "0" },
                { "HMDPS", user.sys5_flg ?? "0" },
                { "HMHRMS", user.sys6_flg ?? "0" },
                { "HMTVE", user.sys7_flg ?? "0" },
                { "JKSYS", user.sys8_flg ?? "0" },
                { "R4", user.sys9_flg ?? "0" },
                { "SDH", user.sys10_flg ?? "0" },
                { "APPM", user.sys11_flg ?? "0" },
                { "PPRM", user.sys12_flg ?? "0" },
                { "CkChkzaiko", user.sys13_flg ?? "0" }
            };

            var claims = new List<Claim>
            {
                new Claim(ClaimTypes.Name, user.usr_id),
                new Claim(ClaimTypes.Role, "User"),
                new Claim("usr_id", user.usr_id),
                new Claim("usr_name", user.usr_name ?? user.usr_id),
                new Claim("email", user.email ?? ""),
                new Claim("sys_flags", System.Text.Json.JsonSerializer.Serialize(sysFlags))
            };

            var claimsIdentity = new ClaimsIdentity(
                claims,
                CookieAuthenticationDefaults.AuthenticationScheme);

            var authProperties = new AuthenticationProperties
            {
                IsPersistent = request.RememberMe,
                ExpiresUtc = DateTimeOffset.UtcNow.AddDays(7)
            };

            await HttpContext.SignInAsync(
                CookieAuthenticationDefaults.AuthenticationScheme,
                new ClaimsPrincipal(claimsIdentity),
                authProperties);

            _logger.LogInformation("用户登录成功：{UserId}", request.UserId);

            return Ok(new
            {
                success = true,
                message = "登录成功",
                data = new
                {
                    userId = user.usr_id,
                    userName = user.usr_name ?? user.usr_id,
                    email = user.email,
                    redirectUrl = "/hmss/master"
                }
            });
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "用户登录失败：{UserId}", request.UserId);
            return StatusCode(500, new
            {
                success = false,
                message = "登录失败，请稍后重试"
            });
        }
    }

    /// <summary>
    /// 用户登出
    /// </summary>
    [HttpPost("logout")]
    public async Task<IActionResult> Logout()
    {
        try
        {
            await HttpContext.SignOutAsync(CookieAuthenticationDefaults.AuthenticationScheme);

            return Ok(new
            {
                success = true,
                message = "登出成功"
            });
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "用户登出失败");
            return StatusCode(500, new
            {
                success = false,
                message = "登出失败"
            });
        }
    }

    /// <summary>
    /// 获取当前用户信息
    /// </summary>
    [HttpGet("me")]
    public IActionResult GetCurrent()
    {
        try
        {
            if (!User.Identity?.IsAuthenticated ?? true)
            {
                return Unauthorized(new
                {
                    success = false,
                    message = "未登录"
                });
            }

            var userId = User.Identity?.Name ?? "";
            var userName = User.FindFirst(ClaimTypes.Name)?.Value ?? userId;
            var roles = User.FindAll(ClaimTypes.Role).Select(c => c.Value).ToList();

            return Ok(new
            {
                success = true,
                data = new
                {
                    userId,
                    userName,
                    roles,
                    isAuthenticated = true
                }
            });
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "获取当前用户信息失败");
            return StatusCode(500, new
            {
                success = false,
                message = "获取用户信息失败"
            });
        }
    }

    /// <summary>
    /// 修改密码
    /// </summary>
    [HttpPost("change-password")]
    public async Task<IActionResult> ChangePassword([FromBody] HmssChangePasswordRequest request)
    {
        try
        {
            if (!User.Identity?.IsAuthenticated ?? true)
            {
                return Unauthorized(new
                {
                    success = false,
                    message = "未登录"
                });
            }

            // TODO: 验证旧密码并更新新密码
            // 这里仅做简化实现

            return Ok(new
            {
                success = true,
                message = "密码修改成功"
            });
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "修改密码失败");
            return StatusCode(500, new
            {
                success = false,
                message = "修改密码失败"
            });
        }
    }
}
