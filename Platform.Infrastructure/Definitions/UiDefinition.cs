using System.Text.Json.Serialization;

namespace Platform.Infrastructure.Definitions
{
    public class UiDefinition
    {
        public LayoutDefinition? Layout { get; set; }
        public LabelsDefinition? Labels { get; set; }
        public StylesDefinition? Styles { get; set; }
    }

    public class LayoutDefinition
    {
        public string? Theme { get; set; }
        public int GridColumns { get; set; } = 1;
    }

    public class LabelsDefinition
    {
        // 直接映射YAML中的en和zh字典
        public Dictionary<string, string>? En { get; set; }
        public Dictionary<string, string>? Zh { get; set; }
    }

    public class StylesDefinition
    {
        public string? CardClass { get; set; }
        public string? ButtonClass { get; set; }
    }
}