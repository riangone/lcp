using Microsoft.AspNetCore.Authentication;
using Microsoft.AspNetCore.Authentication.Cookies;
using Microsoft.AspNetCore.Authentication.JwtBearer;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using Platform.Domain.Entities;
using Platform.Infrastructure.Services;

namespace Platform.Api.Controllers;

/// <summary>
/// 认证控制器 - 处理用户登录、注册、登出
/// </summary>
public class AuthController : Controller
{
    private readonly IAuthService _authService;
    private readonly ILogger<AuthController> _logger;

    public AuthController(IAuthService authService, ILogger<AuthController> logger)
    {
        _authService = authService;
        _logger = logger;
    }

    /// <summary>
    /// 登录页面
    /// </summary>
    [HttpGet("/auth/login")]
    public IActionResult LoginPage()
    {
        return View();
    }

    /// <summary>
    /// 注册页面
    /// </summary>
    [HttpGet("/auth/register")]
    public IActionResult RegisterPage()
    {
        return View();
    }

    /// <summary>
    /// 用户登录 API
    /// </summary>
    [HttpPost("api/auth/login")]
    [AllowAnonymous]
    public async Task<IActionResult> Login([FromBody] LoginRequest request)
    {
        var ipAddress = GetIpAddress();
        var result = await _authService.LoginAsync(request, ipAddress);

        if (!result.Success)
        {
            return BadRequest(new { error = result.Message });
        }

        // 设置 Cookie
        Response.Cookies.Append("refreshToken", result.RefreshToken!, new CookieOptions
        {
            HttpOnly = true,
            Expires = DateTimeOffset.UtcNow.AddDays(7),
            Secure = true,
            SameSite = SameSiteMode.Strict
        });

        return Ok(new
        {
            token = result.Token,
            refreshToken = result.RefreshToken,
            expiresIn = result.ExpiresIn,
            user = new
            {
                result.User!.UserId,
                result.User.Username,
                result.User.Email,
                result.User.DisplayName,
                result.User.Role
            }
        });
    }

    /// <summary>
    /// 用户注册 API
    /// </summary>
    [HttpPost("api/auth/register")]
    [AllowAnonymous]
    public async Task<IActionResult> Register([FromBody] RegisterRequest request)
    {
        var ipAddress = GetIpAddress();
        var result = await _authService.RegisterAsync(request, ipAddress);

        if (!result.Success)
        {
            return BadRequest(new { error = result.Message });
        }

        return Ok(new
        {
            message = "注册成功",
            user = new
            {
                result.User!.UserId,
                result.User.Username,
                result.User.Email,
                result.User.DisplayName,
                result.User.Role
            }
        });
    }

    /// <summary>
    /// 用户登出 API
    /// </summary>
    [HttpPost("api/auth/logout")]
    [Authorize]
    public async Task<IActionResult> Logout()
    {
        var token = HttpContext.User.FindFirst(JwtRegisteredClaimNames.Jti)?.Value;
        if (!string.IsNullOrEmpty(token))
        {
            await _authService.LogoutAsync(token);
        }

        Response.Cookies.Delete("refreshToken");
        return Ok(new { message = "登出成功" });
    }

    /// <summary>
    /// 刷新 Token API
    /// </summary>
    [HttpPost("api/auth/refresh")]
    [AllowAnonymous]
    public async Task<IActionResult> RefreshToken([FromForm] string refreshToken)
    {
        var result = await _authService.RefreshTokenAsync(refreshToken);

        if (!result.Success)
        {
            return Unauthorized(new { error = result.Message });
        }

        Response.Cookies.Append("refreshToken", result.RefreshToken!, new CookieOptions
        {
            HttpOnly = true,
            Expires = DateTimeOffset.UtcNow.AddDays(7),
            Secure = true,
            SameSite = SameSiteMode.Strict
        });

        return Ok(new
        {
            token = result.Token,
            refreshToken = result.RefreshToken,
            expiresIn = result.ExpiresIn
        });
    }

    /// <summary>
    /// 获取当前用户信息 API
    /// </summary>
    [HttpGet("api/auth/me")]
    [Authorize]
    public async Task<IActionResult> GetCurrentUser()
    {
        var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
        if (string.IsNullOrEmpty(userId) || !int.TryParse(userId, out var id))
        {
            return Unauthorized();
        }

        var user = await _authService.GetUserByIdAsync(id);
        if (user == null)
        {
            return NotFound();
        }

        return Ok(new
        {
            user.UserId,
            user.Username,
            user.Email,
            user.DisplayName,
            user.Role,
            user.Avatar,
            user.Bio
        });
    }

    /// <summary>
    /// 更新当前用户信息 API
    /// </summary>
    [HttpPut("api/auth/me")]
    [Authorize]
    public async Task<IActionResult> UpdateCurrentUser([FromBody] UpdateUserRequest request)
    {
        var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
        if (string.IsNullOrEmpty(userId) || !int.TryParse(userId, out var id))
        {
            return Unauthorized();
        }

        // TODO: 实现更新逻辑

        return Ok(new { message = "更新成功" });
    }

    /// <summary>
    /// 修改密码 API
    /// </summary>
    [HttpPost("api/auth/change-password")]
    [Authorize]
    public async Task<IActionResult> ChangePassword([FromBody] ChangePasswordRequest request)
    {
        var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
        if (string.IsNullOrEmpty(userId) || !int.TryParse(userId, out var id))
        {
            return Unauthorized();
        }

        var success = await _authService.UpdatePasswordAsync(id, request.CurrentPassword, request.NewPassword);
        if (!success)
        {
            return BadRequest(new { error = "密码修改失败，请检查当前密码是否正确" });
        }

        return Ok(new { message = "密码修改成功" });
    }

    #region Helper Methods

    private string? GetIpAddress()
    {
        var ip = Request.Headers["X-Forwarded-For"].FirstOrDefault()
            ?? Request.Headers["X-Real-IP"].FirstOrDefault()
            ?? HttpContext.Connection.RemoteIpAddress?.ToString();
        return ip;
    }

    #endregion
}

/// <summary>
/// 更新用户请求
/// </summary>
public class UpdateUserRequest
{
    public string? DisplayName { get; set; }
    public string? Avatar { get; set; }
    public string? Bio { get; set; }
}

/// <summary>
/// 修改密码请求
/// </summary>
public class ChangePasswordRequest
{
    public string CurrentPassword { get; set; } = "";
    public string NewPassword { get; set; } = "";
}
