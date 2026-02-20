-- 天气应用数据库表结构

-- 城市表
CREATE TABLE IF NOT EXISTS City (
    CityId INTEGER PRIMARY KEY AUTOINCREMENT,
    CityNo TEXT NOT NULL UNIQUE,
    CityName TEXT NOT NULL,
    Country TEXT,
    Province TEXT,
    Latitude REAL,
    Longitude REAL,
    TimeZone TEXT,
    CreatedAt TEXT DEFAULT (datetime('now')),
    UpdatedAt TEXT DEFAULT (datetime('now'))
);

-- 天气记录表
CREATE TABLE IF NOT EXISTS WeatherRecord (
    RecordId INTEGER PRIMARY KEY AUTOINCREMENT,
    CityId INTEGER NOT NULL,
    RecordDate TEXT NOT NULL,
    RecordTime TEXT,
    Temperature REAL,
    FeelsLike REAL,
    Humidity INTEGER,
    Pressure REAL,
    WindSpeed REAL,
    WindDirection TEXT,
    WeatherCondition TEXT,
    WeatherCode TEXT,
    Visibility REAL,
    UVIndex INTEGER,
    Sunrise TEXT,
    Sunset TEXT,
    CreatedAt TEXT DEFAULT (datetime('now')),
    UpdatedAt TEXT DEFAULT (datetime('now')),
    FOREIGN KEY (CityId) REFERENCES City(CityId)
);

-- 天气预报表
CREATE TABLE IF NOT EXISTS WeatherForecast (
    ForecastId INTEGER PRIMARY KEY AUTOINCREMENT,
    CityId INTEGER NOT NULL,
    ForecastDate TEXT NOT NULL,
    TempMin REAL,
    TempMax REAL,
    WeatherCondition TEXT,
    WeatherCode TEXT,
    Humidity INTEGER,
    WindSpeed REAL,
    WindDirection TEXT,
    Precipitation REAL,
    PrecipitationProbability INTEGER,
    CreatedAt TEXT DEFAULT (datetime('now')),
    UpdatedAt TEXT DEFAULT (datetime('now')),
    FOREIGN KEY (CityId) REFERENCES City(CityId)
);

-- 天气状况代码表
CREATE TABLE IF NOT EXISTS WeatherCondition (
    ConditionCode TEXT PRIMARY KEY,
    ConditionName TEXT NOT NULL,
    Icon TEXT,
    Description TEXT
);

-- 创建索引
CREATE INDEX IF NOT EXISTS idx_weather_record_city ON WeatherRecord(CityId);
CREATE INDEX IF NOT EXISTS idx_weather_record_date ON WeatherRecord(RecordDate);
CREATE INDEX IF NOT EXISTS idx_weather_forecast_city ON WeatherForecast(CityId);
CREATE INDEX IF NOT EXISTS idx_weather_forecast_date ON WeatherForecast(ForecastDate);
