# OpenWeatherMap 实时天气数据配置说明

## 1. 获取 API Key

访问 https://openweathermap.org/api 注册并获取免费 API Key：

1. 注册账号：https://home.openweathermap.org/users/sign_up
2. 登录后在 API keys 页面获取 Key
3. 免费额度：60 次调用/分钟，1000 次/天

## 2. 配置 API Key

### 方式一：环境变量（推荐）

```bash
export OPENWEATHER_API_KEY=your_api_key_here
```

### 方式二：appsettings.json

```json
{
  "OpenWeather": {
    "ApiKey": "your_api_key_here"
  }
}
```

## 3. 重启应用

```bash
dotnet run --project Platform.Api
```

## 4. 测试实时天气

```bash
# 获取北京实时天气
curl "http://localhost:5267/api/weather/current/1?project=weather"

# 获取天气预报
curl "http://localhost:5267/api/weather/forecast/1?project=weather"

# 手动刷新天气数据
curl -X POST "http://localhost:5267/api/weather/refresh/1?project=weather"
```

## 5. API 端点

| 端点 | 说明 |
|------|------|
| `GET /api/weather/current/{cityId}` | 获取当前天气（优先实时数据） |
| `GET /api/weather/forecast/{cityId}` | 获取 7 天预报（优先实时数据） |
| `POST /api/weather/refresh/{cityId}` | 强制从 API 刷新并保存 |

## 6. 数据来源说明

- **有 API Key**：从 OpenWeatherMap 获取实时数据
- **无 API Key**：从数据库读取预置数据

系统会自动降级，无需担心服务中断。

## 7. 支持的城市

当前支持的城市（在 database 中有经纬度）：

| 城市 | 经纬度 |
|------|--------|
| 北京 | 39.9042, 116.4074 |
| 上海 | 31.2304, 121.4737 |
| 广州 | 23.1291, 113.2644 |
| 深圳 | 22.5431, 114.0579 |
| 东京 | 35.6762, 139.6503 |
| 首尔 | 37.5665, 126.9780 |
| 纽约 | 40.7128, -74.0060 |
| 伦敦 | 51.5074, -0.1278 |
| ... | ... |

所有 20 个城市都支持实时天气查询！
