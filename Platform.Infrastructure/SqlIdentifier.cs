using System.Text.RegularExpressions;

namespace Platform.Infrastructure;

public static class SqlIdentifier
{
    private static readonly Regex Safe =
        new(@"^[A-Za-z_][A-Za-z0-9_]*$");

    public static string EnsureSafe(string name)
    {
        if (!Safe.IsMatch(name))
            throw new Exception($"Unsafe SQL identifier: {name}");
        return name;
    }

    public static string Escape(string name)
    {
        EnsureSafe(name);
        return $"`{name}`";  // SQLite 使用反引号
    }
}
