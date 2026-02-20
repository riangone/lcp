using System.Text.Json;

namespace Platform.Api.Services;

/// <summary>
/// OpenWeatherMap 天气服务
/// 文档：https://openweathermap.org/api
/// </summary>
public interface IWeatherService
{
    Task<CurrentWeather?> GetCurrentWeatherAsync(double lat, double lon);
    Task<List<WeatherForecast>?> GetForecastAsync(double lat, double lon);
}

public class OpenWeatherMapService : IWeatherService
{
    private readonly HttpClient _httpClient;
    private readonly string _apiKey;
    private readonly string _baseUrl = "https://api.openweathermap.org/data/2.5";

    public OpenWeatherMapService(HttpClient httpClient, IConfiguration config)
    {
        _httpClient = httpClient;
        // 优先从环境变量读取 API Key
        var envKey = Environment.GetEnvironmentVariable("OPENWEATHER_API_KEY");
        var configKey = config["OpenWeather:ApiKey"];
        
        if (!string.IsNullOrEmpty(envKey))
        {
            _apiKey = envKey;
            Console.WriteLine($"[Weather] Using API Key from environment: {envKey.Substring(0, Math.Min(8, envKey.Length))}...");
        }
        else if (!string.IsNullOrEmpty(configKey))
        {
            _apiKey = configKey;
            Console.WriteLine($"[Weather] Using API Key from config: {configKey.Substring(0, Math.Min(8, configKey.Length))}...");
        }
        else
        {
            _apiKey = "demo";
            Console.WriteLine("[Weather] Warning: No API Key configured, using demo mode");
        }
    }

    /// <summary>
    /// 获取当前天气
    /// </summary>
    public async Task<CurrentWeather?> GetCurrentWeatherAsync(double lat, double lon)
    {
        try
        {
            var url = $"{_baseUrl}/weather?lat={lat}&lon={lon}&appid={_apiKey}&units=metric&lang=zh_cn";
            var response = await _httpClient.GetStringAsync(url);
            var json = JsonDocument.Parse(response);
            var root = json.RootElement;

            return new CurrentWeather
            {
                Temperature = root.GetProperty("main").GetProperty("temp").GetDouble(),
                FeelsLike = root.GetProperty("main").GetProperty("feels_like").GetDouble(),
                Humidity = root.GetProperty("main").GetProperty("humidity").GetInt32(),
                Pressure = root.GetProperty("main").GetProperty("pressure").GetDouble(),
                WeatherCondition = root.GetProperty("weather")[0].GetProperty("description").GetString(),
                WeatherCode = root.GetProperty("weather")[0].GetProperty("main").GetString(),
                WindSpeed = root.GetProperty("wind").GetProperty("speed").GetDouble() * 3.6, // m/s to km/h
                Visibility = root.GetProperty("visibility").GetDouble() / 1000, // m to km
                Icon = root.GetProperty("weather")[0].GetProperty("icon").GetString()
            };
        }
        catch (Exception ex)
        {
            Console.WriteLine($"[Weather] Error getting current weather: {ex.Message}");
            return null;
        }
    }

    /// <summary>
    /// 获取 7 天天气预报
    /// </summary>
    public async Task<List<WeatherForecast>?> GetForecastAsync(double lat, double lon)
    {
        try
        {
            // OpenWeatherMap 5 天预报 API（每 3 小时一次数据）
            var url = $"{_baseUrl}/forecast?lat={lat}&lon={lon}&appid={_apiKey}&units=metric&lang=zh_cn";
            var response = await _httpClient.GetStringAsync(url);
            var json = JsonDocument.Parse(response);
            var root = json.RootElement;
            var list = root.GetProperty("list");

            // 按天聚合数据（取每天中午的数据作为代表）
            var dailyForecasts = new List<WeatherForecast>();
            var processedDates = new HashSet<string>();

            foreach (var item in list.EnumerateArray())
            {
                var dtTxt = item.GetProperty("dt_txt").GetString();
                var date = dtTxt?.Split(' ')[0];
                
                if (date != null && !processedDates.Contains(date) && dailyForecasts.Count < 7)
                {
                    processedDates.Add(date);
                    
                    var tempMin = item.GetProperty("main").GetProperty("temp_min").GetDouble();
                    var tempMax = item.GetProperty("main").GetProperty("temp_max").GetDouble();
                    
                    dailyForecasts.Add(new WeatherForecast
                    {
                        Date = date,
                        TempMin = tempMin,
                        TempMax = tempMax,
                        WeatherCondition = item.GetProperty("weather")[0].GetProperty("description").GetString(),
                        WeatherCode = item.GetProperty("weather")[0].GetProperty("main").GetString(),
                        Humidity = item.GetProperty("main").GetProperty("humidity").GetInt32(),
                        WindSpeed = item.GetProperty("wind").GetProperty("speed").GetDouble() * 3.6,
                        Icon = item.GetProperty("weather")[0].GetProperty("icon").GetString()
                    });
                }
            }

            return dailyForecasts;
        }
        catch (Exception ex)
        {
            Console.WriteLine($"[Weather] Error getting forecast: {ex.Message}");
            return null;
        }
    }
}

/// <summary>
/// 当前天气数据模型
/// </summary>
public class CurrentWeather
{
    public double Temperature { get; set; }
    public double FeelsLike { get; set; }
    public int Humidity { get; set; }
    public double Pressure { get; set; }
    public double WindSpeed { get; set; }
    public string? WeatherCondition { get; set; }
    public string? WeatherCode { get; set; }
    public double? Visibility { get; set; }
    public string? Icon { get; set; }
}

/// <summary>
/// 天气预报数据模型
/// </summary>
public class WeatherForecast
{
    public string? Date { get; set; }
    public double TempMin { get; set; }
    public double TempMax { get; set; }
    public string? WeatherCondition { get; set; }
    public string? WeatherCode { get; set; }
    public int Humidity { get; set; }
    public double WindSpeed { get; set; }
    public string? Icon { get; set; }
}
