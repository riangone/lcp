using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Authentication;
using Microsoft.AspNetCore.Authentication.Cookies;
using System.Security.Claims;

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

    public HmssAuthController(
        ILogger<HmssAuthController> logger,
        IConfiguration config)
    {
        _logger = logger;
        _config = config;
    }

    /// <summary>
    /// 用户登录
    /// </summary>
    [HttpPost("login")]
    public async Task<IActionResult> Login([FromBody] HmssLoginRequest request)
    {
        try
        {
            // TODO: 从数据库验证用户
            // 这里使用简化实现
            if (string.IsNullOrEmpty(request.UserId) || string.IsNullOrEmpty(request.Password))
            {
                return BadRequest(new
                {
                    success = false,
                    message = "用户 ID 和密码不能为空"
                });
            }

            // 简化验证：admin/admin123
            if (request.UserId == "admin" && request.Password == "admin123")
            {
                var claims = new List<Claim>
                {
                    new Claim(ClaimTypes.Name, request.UserId),
                    new Claim(ClaimTypes.Role, "Admin"),
                    new Claim("usr_id", request.UserId)
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

                return Ok(new
                {
                    success = true,
                    message = "登录成功",
                    data = new
                    {
                        userId = request.UserId,
                        userName = "管理员",
                        redirectUrl = "/hmss/master"
                    }
                });
            }

            return Unauthorized(new
            {
                success = false,
                message = "用户 ID 或密码错误"
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
