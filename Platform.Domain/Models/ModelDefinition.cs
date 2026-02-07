namespace Platform.Domain.Models;

public class ModelDefinition
{
public string Name { get; set; } = "";
public string Table { get; set; } = "";
public string PrimaryKey { get; set; } = "Id";
public Dictionary<string, FieldDefinition> Fields { get; set; } = new();
}

public class FieldDefinition
{
public string Type { get; set; } = "text";
public bool Required { get; set; }
}
