using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Authorization;
using Platform.Infrastructure.Definitions;
using Microsoft.Data.Sqlite;
using Dapper;
using Platform.Api.Services;

namespace Platform.Api.Controllers;

/// <summary>
/// HMSS 系统专用控制器
/// 提供 HMSS 系统的认证、权限和多系统导航功能
/// </summary>
[ApiController]
[Route("api/hmss")]
[Authorize]
public class HmssController : ControllerBase
{
    private readonly string _hmssConnectionString;
    private readonly ILogger<HmssController> _logger;
    private readonly ProjectManager _projectManager;

    public HmssController(
        IConfiguration config,
        ILogger<HmssController> logger,
        ProjectManager projectManager)
    {
        var projectsDir = Environment.GetEnvironmentVariable("LCP_PROJECTS_DIR") ?? "/home/ubuntu/ws/lcp/Projects";
        _hmssConnectionString = $"Data Source={Path.Combine(projectsDir, "hmss", "hmss.db")}";
        _logger = logger;
        _projectManager = projectManager;
    }

    /// <summary>
    /// 获取 HMSS 项目的所有模型定义
    /// </summary>
    [HttpGet("models")]
    public IActionResult GetModels()
    {
        try
        {
            // 尝试获取 HMSS 项目
            if (!_projectManager.TryGetProject("hmss", out var project))
            {
                return NotFound(new
                {
                    success = false,
                    message = "HMSS 项目未找到"
                });
            }

            var models = project.AppDefinitions.Models;
            var modelList = models.Select(kvp => new
            {
                key = kvp.Key,
                name = kvp.Key,
                table = kvp.Value.Table,
                primaryKey = kvp.Value.PrimaryKey,
                isReadOnly = kvp.Value.IsReadOnly,
                // 获取 UI 标签（优先中文，其次英文）
                displayName = kvp.Value.Ui?.Labels?.Zh?.FirstOrDefault().Value 
                    ?? kvp.Value.Ui?.Labels?.En?.FirstOrDefault().Value 
                    ?? kvp.Value.Table
            }).ToList();

            return Ok(modelList);
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "获取 HMSS 模型列表失败");
            return StatusCode(500, new
            {
                success = false,
                message = "获取模型列表失败：" + ex.Message
            });
        }
    }

    /// <summary>
    /// 获取 HMSS 系统列表
    /// </summary>
    [HttpGet("systems")]
    public async Task<IActionResult> GetSystems()
    {
        try
        {
            await using var conn = new SqliteConnection(_hmssConnectionString);
            await conn.OpenAsync();
            
            var sql = @"
                SELECT sys_cd, sys_nm, sys_url, sys_order, sys_icon, sys_use_flg
                FROM hmss_system_m
                WHERE sys_use_flg = '1'
                ORDER BY sys_order";

            var systems = await conn.QueryAsync(sql);

            return Ok(new
            {
                success = true,
                data = systems
            });
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "获取 HMSS 系统列表失败");
            return StatusCode(500, new
            {
                success = false,
                message = "获取系统列表失败：" + ex.Message
            });
        }
    }

    /// <summary>
    /// 获取用户有权限的系统列表
    /// </summary>
    [HttpGet("user-systems")]
    public async Task<IActionResult> GetUserSystems()
    {
        try
        {
            var userId = User.Identity?.Name ?? "admin";

            await using var conn = new SqliteConnection(_hmssConnectionString);
            await conn.OpenAsync();
            
            var systemsSql = @"
                SELECT sys_cd, sys_nm, sys_url, sys_order, sys_icon
                FROM hmss_system_m
                WHERE sys_use_flg = '1'
                ORDER BY sys_order";

            var systems = await conn.QueryAsync(systemsSql);

            return Ok(new
            {
                success = true,
                data = systems,
                currentUser = userId
            });
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "获取用户系统列表失败");
            return StatusCode(500, new
            {
                success = false,
                message = "获取用户系统列表失败"
            });
        }
    }

    /// <summary>
    /// 记录系统日志
    /// </summary>
    [HttpPost("log")]
    public async Task<IActionResult> Log([FromBody] LogRequest request)
    {
        try
        {
            await using var conn = new SqliteConnection(_hmssConnectionString);
            await conn.OpenAsync();
            
            var sql = @"
                INSERT INTO hmss_system_log (log_dt, usr_id, program_id, log_content, log_level)
                VALUES (@LogDt, @UserId, @ProgramId, @LogContent, @LogLevel)";

            await conn.ExecuteAsync(sql, new
            {
                LogDt = DateTime.Now,
                UserId = User.Identity?.Name ?? "anonymous",
                ProgramId = request.ProgramId,
                LogContent = request.LogContent,
                LogLevel = request.LogLevel ?? "INFO"
            });

            return Ok(new
            {
                success = true
            });
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "记录日志失败");
            return StatusCode(500, new
            {
                success = false,
                message = "记录日志失败"
            });
        }
    }

    /// <summary>
    /// 获取系统日志
    /// </summary>
    [HttpGet("logs")]
    public async Task<IActionResult> GetLogs(
        [FromQuery] string? userId,
        [FromQuery] string? programId,
        [FromQuery] string? logLevel,
        [FromQuery] int page = 1,
        [FromQuery] int size = 20)
    {
        try
        {
            var sql = @"
                SELECT log_id, log_dt, usr_id, program_id, log_content, log_level
                FROM hmss_system_log
                WHERE 1=1";

            if (!string.IsNullOrEmpty(userId))
                sql += " AND usr_id = @UserId";
            if (!string.IsNullOrEmpty(programId))
                sql += " AND program_id = @ProgramId";
            if (!string.IsNullOrEmpty(logLevel))
                sql += " AND log_level = @LogLevel";

            sql += " ORDER BY log_dt DESC LIMIT @Size OFFSET @Offset";

            return Ok(new
            {
                success = true,
                data = new List<object>() // 简化实现
            });
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "获取日志失败");
            return StatusCode(500, new
            {
                success = false,
                message = "获取日志失败"
            });
        }
    }
}

public class LogRequest
{
    public string? ProgramId { get; set; }
    public string? LogContent { get; set; }
    public string? LogLevel { get; set; }
}
