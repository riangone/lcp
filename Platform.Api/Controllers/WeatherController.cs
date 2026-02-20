using Microsoft.AspNetCore.Mvc;
using Dapper;
using Platform.Infrastructure.Data;
using System.Data;

namespace Platform.Api.Controllers;

[ApiController]
[Route("api/weather")]
public class WeatherController : ControllerBase
{
    private readonly DbConnectionFactory _db;

    public WeatherController(DbConnectionFactory db)
    {
        _db = db;
    }

    /// <summary>
    /// 获取城市列表（简化版）
    /// </summary>
    [HttpGet("cities")]
    public async Task<IActionResult> GetCities()
    {
        using var conn = _db.Create();
        var sql = @"
            SELECT CityId, CityNo, CityName, Country, Province, Latitude, Longitude, TimeZone
            FROM City
            ORDER BY Country, CityName
        ";
        var cities = await conn.QueryAsync(sql);
        return Ok(cities);
    }

    /// <summary>
    /// 获取城市当前天气
    /// </summary>
    [HttpGet("current/{cityId}")]
    public async Task<IActionResult> GetCurrentWeather(int cityId)
    {
        using var conn = _db.Create();
        var sql = @"
            SELECT *
            FROM WeatherRecord
            WHERE CityId = @CityId
            ORDER BY RecordDate DESC, RecordTime DESC
            LIMIT 1
        ";
        var record = await conn.QueryFirstOrDefaultAsync(sql, new { CityId = cityId });
        return record != null ? Ok(record) : NotFound();
    }

    /// <summary>
    /// 获取天气预报
    /// </summary>
    [HttpGet("forecast/{cityId}")]
    public async Task<IActionResult> GetForecast(int cityId)
    {
        using var conn = _db.Create();
        var sql = @"
            SELECT *
            FROM WeatherForecast
            WHERE CityId = @CityId
            ORDER BY ForecastDate
            LIMIT 7
        ";
        var forecasts = await conn.QueryAsync(sql, new { CityId = cityId });
        return Ok(forecasts);
    }

    /// <summary>
    /// 获取最近天气记录
    /// </summary>
    [HttpGet("records/{cityId}")]
    public async Task<IActionResult> GetRecords(int cityId, int limit = 10)
    {
        using var conn = _db.Create();
        var sql = @"
            SELECT *
            FROM WeatherRecord
            WHERE CityId = @CityId
            ORDER BY RecordDate DESC, RecordTime DESC
            LIMIT @Limit
        ";
        var records = await conn.QueryAsync(sql, new { CityId = cityId, Limit = limit });
        return Ok(records);
    }

    /// <summary>
    /// 获取天气状况代码
    /// </summary>
    [HttpGet("conditions")]
    public async Task<IActionResult> GetConditions()
    {
        using var conn = _db.Create();
        var sql = @"
            SELECT *
            FROM WeatherCondition
            ORDER BY ConditionCode
        ";
        var conditions = await conn.QueryAsync(sql);
        return Ok(conditions);
    }

    /// <summary>
    /// 搜索城市
    /// </summary>
    [HttpGet("search")]
    public async Task<IActionResult> SearchCities(string query)
    {
        using var conn = _db.Create();
        var sql = @"
            SELECT CityId, CityNo, CityName, Country, Province
            FROM City
            WHERE CityName LIKE @Query OR Country LIKE @Query
            ORDER BY CityName
            LIMIT 20
        ";
        var cities = await conn.QueryAsync(sql, new { Query = $"%{query}%" });
        return Ok(cities);
    }
}
