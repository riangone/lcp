namespace Platform.Infrastructure.Definitions
{
    public class AppDefinitions
    {
        public Dictionary<string, ModelDefinition> Models { get; set; } = new();

        public HashSet<string> AllowedModels =>
            Models.Keys.ToHashSet(StringComparer.OrdinalIgnoreCase);
    }
}
