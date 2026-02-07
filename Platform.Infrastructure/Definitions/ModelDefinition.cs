namespace Platform.Infrastructure.Definitions
{
    public class ModelDefinition
    {
        public string Table { get; set; } = "";
        public string PrimaryKey { get; set; } = "";

        public ListDefinition? List { get; set; }
        public FormDefinition? Form { get; set; }

        public Dictionary<string, PropertyDefinition> Properties { get; set; } = new();

        public List<string> Columns => Properties.Keys.ToList();
    }
}
