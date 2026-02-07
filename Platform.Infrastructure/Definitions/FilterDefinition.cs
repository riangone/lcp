namespace Platform.Infrastructure.Definitions;

public class FilterDefinition
{
    public string Label { get; set; } = "";
    public string Type { get; set; } = "eq"; // eq / like
    public Dictionary<string, string>? Options { get; set; }
}
