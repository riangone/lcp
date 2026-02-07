using Platform.Infrastructure.Definitions;

namespace Platform.Infrastructure;

public static class ModelBinder
{
    public static Dictionary<string, object> Bind(
        ModelDefinition def,
        Dictionary<string, string> input)
    {
        var result = new Dictionary<string, object>();

        if (def.Form == null)
            throw new Exception("Form definition not found");

        foreach (var field in def.Form.Fields)
        {
            var name = field.Key;

            if (!def.Properties.ContainsKey(name))
                throw new Exception($"Property '{name}' is not defined");

            var propDef = def.Properties[name];
            var fieldDef = field.Value;

            var required = propDef.Required || fieldDef.Required;

            if (!input.TryGetValue(name, out var raw))
            {
                if (required)
                    throw new Exception($"{name} is required");
                continue;
            }

            // 字符串长度校验
            if (fieldDef.MinLength.HasValue && raw.Length < fieldDef.MinLength.Value)
                throw new Exception($"{name} must be at least {fieldDef.MinLength.Value} characters");

            if (fieldDef.MaxLength.HasValue && raw.Length > fieldDef.MaxLength.Value)
                throw new Exception($"{name} must be at most {fieldDef.MaxLength.Value} characters");

            if (fieldDef.Type == "select")
            {
                if (string.IsNullOrEmpty(raw))
                {
                    if (required)
                        throw new Exception($"{name} is required");
                    continue;
                }

                if (fieldDef.Options == null || !fieldDef.Options.ContainsKey(raw))
                    throw new Exception($"{name} has invalid value");

                result[name] = raw;
                continue;
            }

            var value = ConvertValue(name, raw, propDef.Type, fieldDef);
            result[name] = value;
        }

        return result;
    }

    private static object ConvertValue(string name, string raw, string type, FormFieldDefinition fieldDef)
    {
        try
        {
            return type switch
            {
                "int" => ValidateInt(name, raw, fieldDef),
                "decimal" => ValidateDecimal(name, raw, fieldDef),
                "date" => ValidateDate(name, raw),
                _ => raw
            };
        }
        catch (Exception ex)
        {
            throw new Exception(ex.Message);
        }
    }

    private static int ValidateInt(string name, string raw, FormFieldDefinition fieldDef)
    {
        if (!int.TryParse(raw, out var val))
            throw new Exception($"{name} must be an integer");

        if (fieldDef.Min.HasValue && val < fieldDef.Min.Value)
            throw new Exception($"{name} must be >= {fieldDef.Min.Value}");

        if (fieldDef.Max.HasValue && val > fieldDef.Max.Value)
            throw new Exception($"{name} must be <= {fieldDef.Max.Value}");

        return val;
    }

    private static decimal ValidateDecimal(string name, string raw, FormFieldDefinition fieldDef)
    {
        if (!decimal.TryParse(raw, System.Globalization.NumberStyles.Any, System.Globalization.CultureInfo.InvariantCulture, out var val))
        {
            if (!decimal.TryParse(raw, out val))
                throw new Exception($"{name} must be a decimal number");
        }

        if (fieldDef.Min.HasValue && val < fieldDef.Min.Value)
            throw new Exception($"{name} must be >= {fieldDef.Min.Value}");

        if (fieldDef.Max.HasValue && val > fieldDef.Max.Value)
            throw new Exception($"{name} must be <= {fieldDef.Max.Value}");

        return val;
    }

    private static DateTime ValidateDate(string name, string raw)
    {
        if (!DateTime.TryParse(raw, out var val))
            throw new Exception($"{name} must be a valid date");

        return val;
    }
}
