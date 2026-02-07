namespace Platform.Infrastructure.Definitions;

public class ListDefinition
{
    public List<string> Columns { get; set; } = new();
    public Dictionary<string, FilterDefinition>? Filters { get; set; }
}
