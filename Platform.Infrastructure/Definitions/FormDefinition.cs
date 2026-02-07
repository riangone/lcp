namespace Platform.Infrastructure.Definitions;

public class FormDefinition
{
    public string Title { get; set; } = "";
    public Dictionary<string, FormFieldDefinition> Fields { get; set; } = new();
}
