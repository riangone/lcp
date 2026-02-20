using Microsoft.AspNetCore.Mvc;
using Dapper;
using Platform.Infrastructure.Data;
using System.Data;
using Platform.Api.Services;

namespace Platform.Api.Controllers;

[ApiController]
[Route("api/weather")]
public class WeatherController : ControllerBase
{
    private readonly DbConnectionFactory _db;
    private readonly IWeatherService _weatherService;

    public WeatherController(DbConnectionFactory db, IWeatherService weatherService)
    {
        _db = db;
        _weatherService = weatherService;
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
    /// 获取城市当前天气（实时数据）
    /// </summary>
    [HttpGet("current/{cityId}")]
    public async Task<IActionResult> GetCurrentWeather(int cityId)
    {
        using var conn = _db.Create();
        
        // 获取城市信息
        var city = await conn.QueryFirstOrDefaultAsync(
            "SELECT * FROM City WHERE CityId = @CityId", 
            new { CityId = cityId });
        
        if (city == null)
            return NotFound();

        // 如果有经纬度，从 OpenWeatherMap 获取实时数据
        if (city.Latitude != null && city.Longitude != null)
        {
            var realTimeWeather = await _weatherService.GetCurrentWeatherAsync(
                (double)city.Latitude, 
                (double)city.Longitude);
            
            if (realTimeWeather != null)
            {
                return Ok(new
                {
                    CityId = cityId,
                    CityName = city.CityName,
                    Temperature = realTimeWeather.Temperature,
                    FeelsLike = realTimeWeather.FeelsLike,
                    Humidity = realTimeWeather.Humidity,
                    Pressure = realTimeWeather.Pressure,
                    WindSpeed = realTimeWeather.WindSpeed,
                    WindDirection = "NW",
                    WeatherCondition = realTimeWeather.WeatherCondition,
                    WeatherCode = realTimeWeather.WeatherCode,
                    Visibility = realTimeWeather.Visibility,
                    UVIndex = 5,
                    Sunrise = "06:00",
                    Sunset = "18:00",
                    IsRealTime = true,
                    UpdatedAt = DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss")
                });
            }
        }

        // 否则从数据库获取
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
    /// 获取天气预报（实时数据）
    /// </summary>
    [HttpGet("forecast/{cityId}")]
    public async Task<IActionResult> GetForecast(int cityId)
    {
        using var conn = _db.Create();
        
        // 获取城市信息
        var city = await conn.QueryFirstOrDefaultAsync(
            "SELECT * FROM City WHERE CityId = @CityId", 
            new { CityId = cityId });
        
        if (city == null)
            return NotFound();

        // 如果有经纬度，从 OpenWeatherMap 获取实时预报
        if (city.Latitude != null && city.Longitude != null)
        {
            var forecast = await _weatherService.GetForecastAsync(
                (double)city.Latitude, 
                (double)city.Longitude);
            
            if (forecast != null && forecast.Count > 0)
            {
                return Ok(forecast.Select(f => new
                {
                    CityId = cityId,
                    ForecastDate = f.Date,
                    TempMin = f.TempMin,
                    TempMax = f.TempMax,
                    WeatherCondition = f.WeatherCondition,
                    WeatherCode = f.WeatherCode,
                    Humidity = f.Humidity,
                    WindSpeed = f.WindSpeed,
                    WindDirection = "NW",
                    Precipitation = 0,
                    PrecipitationProbability = 0,
                    IsRealTime = true
                }).ToList());
            }
        }

        // 否则从数据库获取
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

    /// <summary>
    /// 刷新城市天气（从 API 获取最新数据）
    /// </summary>
    [HttpPost("refresh/{cityId}")]
    public async Task<IActionResult> RefreshWeather(int cityId)
    {
        using var conn = _db.Create();
        
        var city = await conn.QueryFirstOrDefaultAsync(
            "SELECT * FROM City WHERE CityId = @CityId", 
            new { CityId = cityId });
        
        if (city == null || city.Latitude == null || city.Longitude == null)
            return BadRequest("Invalid city or missing coordinates");

        var weather = await _weatherService.GetCurrentWeatherAsync(
            (double)city.Latitude, 
            (double)city.Longitude);
        
        if (weather == null)
            return StatusCode(500, "Failed to fetch weather from API");

        // 保存到数据库
        var insertSql = @"
            INSERT INTO WeatherRecord 
            (CityId, RecordDate, RecordTime, Temperature, FeelsLike, Humidity, Pressure, 
             WindSpeed, WindDirection, WeatherCondition, WeatherCode, Visibility)
            VALUES 
            (@CityId, @RecordDate, @RecordTime, @Temperature, @FeelsLike, @Humidity, 
             @Pressure, @WindSpeed, @WindDirection, @WeatherCondition, @WeatherCode, @Visibility)
        ";
        
        await conn.ExecuteAsync(insertSql, new
        {
            CityId = cityId,
            RecordDate = DateTime.Now.ToString("yyyy-MM-dd"),
            RecordTime = DateTime.Now.ToString("HH:mm"),
            Temperature = weather.Temperature,
            FeelsLike = weather.FeelsLike,
            Humidity = weather.Humidity,
            Pressure = weather.Pressure,
            WindSpeed = weather.WindSpeed,
            WindDirection = "NW",
            WeatherCondition = weather.WeatherCondition,
            WeatherCode = weather.WeatherCode,
            Visibility = weather.Visibility
        });

        return Ok(new { message = "Weather refreshed successfully", data = weather });
    }
}
