using System.Text.RegularExpressions;

namespace Platform.Infrastructure;

public static class SqlIdentifier
{
    private static readonly Regex Safe =
        new(@"^[A-Za-z_][A-Za-z0-9_]*$");

    public static string EnsureSafe(string name)
    {
        // 如果已经是引号包裹的标识符，移除引号后验证
        var unquoted = Unquote(name);
        if (!Safe.IsMatch(unquoted))
            throw new Exception($"Unsafe SQL identifier: {name}");
        return unquoted;
    }

    public static string Escape(string name)
    {
        // 如果已经带引号，直接使用
        if (name.StartsWith("\"") && name.EndsWith("\""))
            return name;
        if (name.StartsWith("`") && name.EndsWith("`"))
            return name;
        
        EnsureSafe(name);
        return $"`{name}`";  // SQLite 使用反引号
    }

    /// <summary>
    /// 移除标识符的引号
    /// </summary>
    public static string Unquote(string name)
    {
        if (string.IsNullOrEmpty(name))
            return name;
        
        // 移除双引号
        if (name.StartsWith("\"") && name.EndsWith("\"") && name.Length > 1)
            return name.Substring(1, name.Length - 2);
        
        // 移除反引号
        if (name.StartsWith("`") && name.EndsWith("`") && name.Length > 1)
            return name.Substring(1, name.Length - 2);
        
        return name;
    }
}
