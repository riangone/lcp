using Platform.Infrastructure.Definitions;
using YamlDotNet.Serialization;
using YamlDotNet.Serialization.NamingConventions;

namespace Platform.Infrastructure.Yaml;

public static class YamlLoader
{
    public static AppDefinitions Load(string filePath, string pagesDir)
    {
        using (var writer = File.AppendText("/tmp/yaml_debug.log"))
        {
            writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Loading YAML from: {filePath}");
            writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Loading pages from: {pagesDir}");
        }

        if (!File.Exists(filePath))
        {
            throw new FileNotFoundException($"YAML file not found: {filePath}");
        }

        var yaml = File.ReadAllText(filePath);

        var deserializer = new DeserializerBuilder()
            .WithNamingConvention(UnderscoredNamingConvention.Instance)
            .IgnoreUnmatchedProperties()
            .Build();

        var defs = deserializer.Deserialize<AppDefinitions>(yaml);

        // 加载页面定义
        defs.Pages = LoadPages(pagesDir, deserializer);

        using (var writer = File.AppendText("/tmp/yaml_debug.log"))
        {
            writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Loaded. Models: {string.Join(", ", defs.Models.Keys)}, Pages: {string.Join(", ", defs.Pages.Keys)}");
        }

        return defs;
    }

    /// <summary>
    /// 加载首页配置
    /// </summary>
    public static HomeDefinition? LoadHome(string filePath)
    {
        if (!File.Exists(filePath))
        {
            return null;
        }

        var yaml = File.ReadAllText(filePath);

        var deserializer = new DeserializerBuilder()
            .WithNamingConvention(UnderscoredNamingConvention.Instance)
            .IgnoreUnmatchedProperties()
            .Build();

        try
        {
            var home = deserializer.Deserialize<HomeDefinition>(yaml);
            return home;
        }
        catch (Exception ex)
        {
            using (var writer = File.AppendText("/tmp/yaml_debug.log"))
            {
                writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Error loading home config from {filePath}: {ex.Message}");
            }
            return null;
        }
    }

    private static Dictionary<string, PageDefinition> LoadPages(string pagesDir, IDeserializer deserializer)
    {
        var pages = new Dictionary<string, PageDefinition>();

        if (!Directory.Exists(pagesDir))
        {
            using (var writer = File.AppendText("/tmp/yaml_debug.log"))
            {
                writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Pages directory not found: {pagesDir}");
            }
            return pages;
        }

        var files = Directory.GetFiles(pagesDir, "*.yaml");
        using (var writer = File.AppendText("/tmp/yaml_debug.log"))
        {
            writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Found {files.Length} page files");
        }

        foreach (var file in files)
        {
            try
            {
                var pageName = Path.GetFileNameWithoutExtension(file);
                var yaml = File.ReadAllText(file);
                
                using (var writer = File.AppendText("/tmp/yaml_debug.log"))
                {
                    writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Loading page: {pageName} from {file}");
                }

                var page = deserializer.Deserialize<PageDefinition>(yaml);
                page.Id = pageName;
                pages[pageName] = page;
                
                using (var writer = File.AppendText("/tmp/yaml_debug.log"))
                {
                    writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Loaded page: {pageName}, title: {page.Title}");
                }
            }
            catch (Exception ex)
            {
                using (var writer = File.AppendText("/tmp/yaml_debug.log"))
                {
                    writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Error loading page from {file}: {ex.Message}");
                    writer.WriteLine($"[{DateTime.Now:yyyy-MM-dd HH:mm:ss.fff}] Stack: {ex.StackTrace}");
                }
            }
        }

        return pages;
    }
}
