using Platform.Infrastructure.Definitions;
using YamlDotNet.Serialization;
using YamlDotNet.Serialization.NamingConventions;
using System.Dynamic;

namespace Platform.Infrastructure.Yaml;

public static class YamlLoader
{
    public static AppDefinitions Load(string filePath, string pagesDir)
    {
        if (!File.Exists(filePath))
        {
            throw new FileNotFoundException($"YAML file not found: {filePath}");
        }

        var baseDir = Path.GetDirectoryName(filePath)!;
        var yaml = File.ReadAllText(filePath);

        var deserializer = new DeserializerBuilder()
            .WithNamingConvention(UnderscoredNamingConvention.Instance)
            .IgnoreUnmatchedProperties()
            .Build();

        // 先反序列化为 ExpandoObject 以支持 imports
        var expandoDeserializer = new DeserializerBuilder()
            .WithNamingConvention(UnderscoredNamingConvention.Instance)
            .IgnoreUnmatchedProperties()
            .Build();

        var expando = expandoDeserializer.Deserialize<ExpandoObject>(yaml);
        var dict = (IDictionary<string, object>)expando;

        var defs = new AppDefinitions();

        // 处理 imports
        if (dict.ContainsKey("imports"))
        {
            var imports = dict["imports"] as IEnumerable<object>;
            if (imports != null)
            {
                foreach (var import in imports)
                {
                    var importPath = import?.ToString();
                    if (string.IsNullOrEmpty(importPath)) continue;

                    // 解析相对路径
                    var fullPath = Path.IsPathRooted(importPath)
                        ? importPath
                        : Path.GetFullPath(Path.Combine(baseDir, importPath));

                    if (File.Exists(fullPath))
                    {
                        Console.WriteLine($"[YAML] Importing: {fullPath}");
                        var importYaml = File.ReadAllText(fullPath);
                        var importDefs = deserializer.Deserialize<AppDefinitions>(importYaml);

                        // 合并模型定义
                        foreach (var kvp in importDefs.Models)
                        {
                            defs.Models[kvp.Key] = kvp.Value;
                        }
                    }
                    else
                    {
                        Console.WriteLine($"[YAML] Import file not found: {fullPath}");
                    }
                }
            }
        }

        // 处理当前文件的 models（如果有）
        if (dict.ContainsKey("models"))
        {
            var localDefs = deserializer.Deserialize<AppDefinitions>(yaml);
            foreach (var kvp in localDefs.Models)
            {
                defs.Models[kvp.Key] = kvp.Value;
            }
        }

        // 加载页面定义
        defs.Pages = LoadPages(pagesDir, deserializer);

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

        // 使用 ExpandoObject 捕获所有字段
        var expandoDeserializer = new DeserializerBuilder()
            .WithNamingConvention(UnderscoredNamingConvention.Instance)
            .IgnoreUnmatchedProperties()
            .Build();

        try
        {
            // 反序列化为 ExpandoObject 以捕获所有字段
            var expando = expandoDeserializer.Deserialize<ExpandoObject>(yaml);
            var dict = (IDictionary<string, object>)expando;

            // 提取 title
            var title = dict.ContainsKey("title") ? dict["title"]?.ToString() ?? "" : "";

            // 提取并转换 layout
            var layout = new List<HomeComponentConfig>();
            if (dict.ContainsKey("layout"))
            {
                var layoutObj = dict["layout"];

                // 处理不同类型的集合
                IEnumerable<object>? layoutList = null;
                if (layoutObj is List<object> list)
                {
                    layoutList = list;
                }
                else if (layoutObj is IEnumerable<object> enumerable)
                {
                    layoutList = enumerable.ToList();
                }
                else if (layoutObj is System.Collections.IList nonGenericList)
                {
                    layoutList = nonGenericList.Cast<object>();
                }

                if (layoutList != null)
                {
                    foreach (var item in layoutList)
                    {
                        // 处理泛型字典
                        if (item is IDictionary<string, object> itemDict)
                        {
                            var type = itemDict.ContainsKey("type") ? itemDict["type"]?.ToString() ?? "" : "";
                            var data = new Dictionary<string, object>();
                            foreach (var kvp in itemDict)
                            {
                                if (kvp.Key != "type")
                                {
                                    data[kvp.Key] = kvp.Value;
                                }
                            }

                            layout.Add(new HomeComponentConfig
                            {
                                Type = type,
                                Data = data
                            });
                        }
                        // 处理非泛型字典（YamlDotNet 默认行为）
                        else if (item is System.Collections.IDictionary itemNonGeneric)
                        {
                            var type = itemNonGeneric.Contains("type") ? itemNonGeneric["type"]?.ToString() ?? "" : "";
                            var data = new Dictionary<string, object>();
                            foreach (var key in itemNonGeneric.Keys)
                            {
                                if (key?.ToString() != "type")
                                {
                                    data[key!.ToString()!] = itemNonGeneric[key]!;
                                }
                            }

                            layout.Add(new HomeComponentConfig
                            {
                                Type = type,
                                Data = data
                            });
                        }
                    }
                }
            }

            return new HomeDefinition
            {
                Title = title,
                Layout = layout
            };
        }
        catch (Exception ex)
        {
            Console.WriteLine($"Error loading home config from {filePath}: {ex.Message}");
            return null;
        }
    }

    private static Dictionary<string, PageDefinition> LoadPages(string pagesDir, IDeserializer deserializer)
    {
        var pages = new Dictionary<string, PageDefinition>();

        if (!Directory.Exists(pagesDir))
        {
            return pages;
        }

        var files = Directory.GetFiles(pagesDir, "*.yaml");

        foreach (var file in files)
        {
            try
            {
                var pageName = Path.GetFileNameWithoutExtension(file);
                var yaml = File.ReadAllText(file);

                var page = deserializer.Deserialize<PageDefinition>(yaml);
                page.Id = pageName;
                pages[pageName] = page;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error loading page from {file}: {ex.Message}");
            }
        }

        return pages;
    }
}
