using YamlDotNet.Serialization;

namespace Platform.Infrastructure.Definitions;

public class FormFieldDefinition
{
    public string Label { get; set; } = "";
    public string Type { get; set; } = "text";
    public bool Required { get; set; }

    public Dictionary<string, string>? Options { get; set; }

    [YamlMember(Alias = "min_length")]
    public int? MinLength { get; set; }

    [YamlMember(Alias = "max_length")]
    public int? MaxLength { get; set; }

    [YamlMember(Alias = "min")]
    public decimal? Min { get; set; }

    [YamlMember(Alias = "max")]
    public decimal? Max { get; set; }
}
