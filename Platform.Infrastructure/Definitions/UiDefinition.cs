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
        public Dictionary<string, Dictionary<string, string>>? Languages { get; set; }
    }

    public class StylesDefinition
    {
        public string? CardClass { get; set; }
        public string? ButtonClass { get; set; }
    }
}