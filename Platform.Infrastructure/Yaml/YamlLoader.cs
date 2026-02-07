using Platform.Infrastructure.Definitions;
using YamlDotNet.Serialization;
using YamlDotNet.Serialization.NamingConventions;

namespace Platform.Infrastructure.Yaml;

public static class YamlLoader
{
    public static AppDefinitions Load(string filePath)
    {
        // 写日志文件用于调试
        using (var writer = File.AppendText("/tmp/yaml_debug.log"))
        {
            writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Attempting to load YAML from: {filePath}");
            writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] File exists: {File.Exists(filePath)}");
            writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Current directory: {Directory.GetCurrentDirectory()}");
        }

        if (!File.Exists(filePath))
        {
            var errorMsg = $"YAML file not found: {filePath}";
            using (var writer = File.AppendText("/tmp/yaml_debug.log"))
            {
                writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] ERROR: {errorMsg}");
            }
            throw new FileNotFoundException(errorMsg);
        }

        var yaml = File.ReadAllText(filePath);
        
        var deserializer = new DeserializerBuilder()
            .WithNamingConvention(UnderscoredNamingConvention.Instance)
            .IgnoreUnmatchedProperties()
            .Build();
        var defs = deserializer.Deserialize<AppDefinitions>(yaml);

        using (var writer = File.AppendText("/tmp/yaml_debug.log"))
        {
            writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Successfully loaded YAML. Models: {string.Join(", ", defs.Models.Keys)}");
        }

        return defs;
    }
}
