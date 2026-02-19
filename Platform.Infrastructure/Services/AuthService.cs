using System.Data;
using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Security.Cryptography;
using System.Text;
using Dapper;
using Microsoft.Data.Sqlite;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.Logging;
using Microsoft.IdentityModel.Tokens;
using Platform.Domain.Entities;
using Platform.Infrastructure.Data;

namespace Platform.Infrastructure.Services;

/// <summary>
/// 认证服务 - 每个项目独立的用户认证
/// </summary>
public interface IAuthService
{
    Task<AuthResult> LoginAsync(LoginRequest request, string? ipAddress = null);
    Task<AuthResult> RegisterAsync(RegisterRequest request, string? ipAddress = null);
    Task<bool> LogoutAsync(string token);
    Task<User?> GetUserByUsernameAsync(string username);
    Task<User?> GetUserByIdAsync(int userId);
    Task<bool> UpdatePasswordAsync(int userId, string currentPassword, string newPassword);
    Task<AuthResult> RefreshTokenAsync(string refreshToken);
    string GenerateJwtToken(User user, int expiresInMinutes = 60);
}

public class AuthService : IAuthService
{
    private readonly IDbConnection _db;
    private readonly IConfiguration _config;
    private readonly ILogger<AuthService> _logger;

    public AuthService(
        DbConnectionFactory factory,
        IConfiguration config,
        ILogger<AuthService> logger)
    {
        _db = factory.Create();
        _config = config;
        _logger = logger;
    }

    /// <summary>
    /// 用户登录
    /// </summary>
    public async Task<AuthResult> LoginAsync(LoginRequest request, string? ipAddress = null)
    {
        try
        {
            // 查找用户
            var user = await GetUserByUsernameAsync(request.Username);
            if (user == null)
            {
                return new AuthResult
                {
                    Success = false,
                    Message = "用户名或密码错误"
                };
            }

            // 检查用户是否激活
            if (!user.IsActive)
            {
                return new AuthResult
                {
                    Success = false,
                    Message = "账户已被禁用"
                };
            }

            // 验证密码
            if (!BCrypt.Net.BCrypt.Verify(request.Password, user.PasswordHash))
            {
                return new AuthResult
                {
                    Success = false,
                    Message = "用户名或密码错误"
                };
            }

            // 更新登录信息
            await UpdateLoginInfoAsync(user.UserId, ipAddress);

            // 生成 JWT Token
            var expiresInMinutes = request.RememberMe ? 60 * 24 * 7 : 60; // 7 天或 1 小时
            var token = GenerateJwtToken(user, expiresInMinutes);

            // 生成 Refresh Token
            var refreshToken = GenerateRefreshToken();
            await SaveRefreshTokenAsync(user.UserId, token, refreshToken, ipAddress);

            _logger.LogInformation($"User {user.Username} logged in successfully");

            return new AuthResult
            {
                Success = true,
                Token = token,
                RefreshToken = refreshToken,
                User = user,
                ExpiresIn = expiresInMinutes * 60,
                Message = "登录成功"
            };
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Login failed");
            return new AuthResult
            {
                Success = false,
                Message = "登录失败：" + ex.Message
            };
        }
    }

    /// <summary>
    /// 用户注册
    /// </summary>
    public async Task<AuthResult> RegisterAsync(RegisterRequest request, string? ipAddress = null)
    {
        try
        {
            // 检查用户名是否已存在
            var existingUser = await GetUserByUsernameAsync(request.Username);
            if (existingUser != null)
            {
                return new AuthResult
                {
                    Success = false,
                    Message = "用户名已存在"
                };
            }

            // 检查邮箱是否已存在
            var sql = "SELECT UserId FROM \"User\" WHERE Email = @Email";
            var existingEmail = await _db.ExecuteScalarAsync<int?>(sql, new { request.Email });
            if (existingEmail.HasValue)
            {
                return new AuthResult
                {
                    Success = false,
                    Message = "邮箱已被注册"
                };
            }

            // 创建用户
            var passwordHash = BCrypt.Net.BCrypt.HashPassword(request.Password);
            var user = new User
            {
                Username = request.Username,
                Email = request.Email,
                PasswordHash = passwordHash,
                DisplayName = request.DisplayName ?? request.Username,
                Role = "User",
                IsActive = true,
                CreatedAt = DateTime.UtcNow,
                UpdatedAt = DateTime.UtcNow
            };

            sql = @"
                INSERT INTO ""User"" 
                (Username, Email, PasswordHash, DisplayName, Role, IsActive, CreatedAt, UpdatedAt)
                VALUES 
                (@Username, @Email, @PasswordHash, @DisplayName, @Role, @IsActive, @CreatedAt, @UpdatedAt);
                SELECT last_insert_rowid();";

            var userId = await _db.ExecuteScalarAsync<int>(sql, user);
            user.UserId = userId;

            _logger.LogInformation($"User {user.Username} registered successfully");

            // 自动登录
            return await LoginAsync(new LoginRequest
            {
                Username = request.Username,
                Password = request.Password,
                RememberMe = false
            }, ipAddress);
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Registration failed");
            return new AuthResult
            {
                Success = false,
                Message = "注册失败：" + ex.Message
            };
        }
    }

    /// <summary>
    /// 登出
    /// </summary>
    public async Task<bool> LogoutAsync(string token)
    {
        try
        {
            // 删除会话
            var sql = "DELETE FROM UserSession WHERE Token = @Token";
            await _db.ExecuteAsync(sql, new { Token = token });
            return true;
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Logout failed");
            return false;
        }
    }

    /// <summary>
    /// 刷新 Token
    /// </summary>
    public async Task<AuthResult> RefreshTokenAsync(string refreshToken)
    {
        try
        {
            var sql = @"
                SELECT s.*, u.*
                FROM UserSession s
                JOIN ""User"" u ON u.UserId = s.UserId
                WHERE s.Token = @RefreshToken
                AND s.ExpiresAt > @Now
                AND u.IsActive = 1";

            var session = await _db.QueryFirstOrDefaultAsync(sql, new
            {
                RefreshToken = refreshToken,
                Now = DateTime.UtcNow
            });

            if (session == null)
            {
                return new AuthResult
                {
                    Success = false,
                    Message = "Token 已过期或无效"
                };
            }

            var user = new User
            {
                UserId = session.UserId,
                Username = session.Username,
                Email = session.Email,
                DisplayName = session.DisplayName,
                Role = session.Role,
                IsActive = session.IsActive
            };

            // 生成新 Token
            var newToken = GenerateJwtToken(user, 60);
            var newRefreshToken = GenerateRefreshToken();

            // 更新会话
            sql = @"
                UPDATE UserSession
                SET Token = @NewToken, ExpiresAt = @ExpiresAt
                WHERE Token = @OldToken";

            await _db.ExecuteAsync(sql, new
            {
                NewToken = newToken,
                OldToken = refreshToken,
                ExpiresAt = DateTime.UtcNow.AddMinutes(60)
            });

            return new AuthResult
            {
                Success = true,
                Token = newToken,
                RefreshToken = newRefreshToken,
                User = user,
                ExpiresIn = 3600
            };
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Token refresh failed");
            return new AuthResult
            {
                Success = false,
                Message = "Token 刷新失败：" + ex.Message
            };
        }
    }

    /// <summary>
    /// 获取用户
    /// </summary>
    public async Task<User?> GetUserByUsernameAsync(string username)
    {
        var sql = "SELECT * FROM \"User\" WHERE Username = @Username";
        return await _db.QueryFirstOrDefaultAsync<User>(sql, new { Username = username });
    }

    /// <summary>
    /// 获取用户
    /// </summary>
    public async Task<User?> GetUserByIdAsync(int userId)
    {
        var sql = "SELECT * FROM \"User\" WHERE UserId = @UserId";
        return await _db.QueryFirstOrDefaultAsync<User>(sql, new { UserId = userId });
    }

    /// <summary>
    /// 更新密码
    /// </summary>
    public async Task<bool> UpdatePasswordAsync(int userId, string currentPassword, string newPassword)
    {
        try
        {
            var user = await GetUserByIdAsync(userId);
            if (user == null)
                return false;

            // 验证当前密码
            if (!BCrypt.Net.BCrypt.Verify(currentPassword, user.PasswordHash))
                return false;

            // 更新密码
            var newHash = BCrypt.Net.BCrypt.HashPassword(newPassword);
            var sql = "UPDATE \"User\" SET PasswordHash = @PasswordHash, UpdatedAt = @UpdatedAt WHERE UserId = @UserId";
            await _db.ExecuteAsync(sql, new
            {
                PasswordHash = newHash,
                UpdatedAt = DateTime.UtcNow,
                UserId = userId
            });

            return true;
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Password update failed");
            return false;
        }
    }

    /// <summary>
    /// 生成 JWT Token
    /// </summary>
    public string GenerateJwtToken(User user, int expiresInMinutes = 60)
    {
        var key = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(
            _config["Jwt:Key"] ?? "YourSuperSecretKeyThatIsAtLeast32CharactersLong!"));
        var credentials = new SigningCredentials(key, SecurityAlgorithms.HmacSha256);

        var claims = new[]
        {
            new Claim(ClaimTypes.NameIdentifier, user.UserId.ToString()),
            new Claim(ClaimTypes.Name, user.Username),
            new Claim(ClaimTypes.Email, user.Email),
            new Claim(ClaimTypes.Role, user.Role),
            new Claim("DisplayName", user.DisplayName ?? user.Username),
            new Claim(JwtRegisteredClaimNames.Jti, Guid.NewGuid().ToString())
        };

        var token = new JwtSecurityToken(
            issuer: _config["Jwt:Issuer"] ?? "LowCodePlatform",
            audience: _config["Jwt:Audience"] ?? "LowCodePlatform",
            claims: claims,
            expires: DateTime.UtcNow.AddMinutes(expiresInMinutes),
            signingCredentials: credentials
        );

        return new JwtSecurityTokenHandler().WriteToken(token);
    }

    #region Private Methods

    private async Task UpdateLoginInfoAsync(int userId, string? ipAddress)
    {
        try
        {
            var sql = @"
                UPDATE ""User""
                SET LastLoginAt = @LastLoginAt,
                    LastLoginIP = @LastLoginIP,
                    UpdatedAt = @UpdatedAt
                WHERE UserId = @UserId";

            await _db.ExecuteAsync(sql, new
            {
                LastLoginAt = DateTime.UtcNow,
                LastLoginIP = ipAddress,
                UpdatedAt = DateTime.UtcNow,
                UserId = userId
            });
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Failed to update login info");
        }
    }

    private async Task SaveRefreshTokenAsync(int userId, string token, string refreshToken, string? ipAddress)
    {
        try
        {
            var sql = @"
                INSERT INTO UserSession (SessionId, UserId, Token, IPAddress, ExpiresAt, CreatedAt)
                VALUES (@SessionId, @UserId, @Token, @IPAddress, @ExpiresAt, @CreatedAt)";

            await _db.ExecuteAsync(sql, new
            {
                SessionId = Guid.NewGuid().ToString(),
                UserId = userId,
                Token = refreshToken,
                IPAddress = ipAddress,
                ExpiresAt = DateTime.UtcNow.AddDays(7),
                CreatedAt = DateTime.UtcNow
            });
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Failed to save refresh token");
        }
    }

    private static string GenerateRefreshToken()
    {
        var randomNumber = new byte[64];
        using var rng = RandomNumberGenerator.Create();
        rng.GetBytes(randomNumber);
        return Convert.ToBase64String(randomNumber);
    }

    #endregion
}
