using Platform.Domain.Models;
using YamlDotNet.Serialization;
using YamlDotNet.Serialization.NamingConventions;

namespace Platform.Infrastructure.Yaml;

public class YamlModelStore
{
private readonly Dictionary<string, ModelDefinition> _models = new();

    public YamlModelStore()
    {
        var dir = "Definitions/models";
        if (!Directory.Exists(dir))
            return;

        var files = Directory.GetFiles(dir, "*.yaml");

        var deserializer = new DeserializerBuilder()
            .WithNamingConvention(CamelCaseNamingConvention.Instance)
            .Build();

        foreach (var file in files)
        {
            var yaml = File.ReadAllText(file);
            var model = deserializer.Deserialize<ModelDefinition>(yaml);
            _models[model.Name] = model;
        }
    }

    public ModelDefinition Get(string name)
    {
        if (!_models.ContainsKey(name))
            throw new Exception($"Model {name} not found");

        return _models[name];
    }
}
