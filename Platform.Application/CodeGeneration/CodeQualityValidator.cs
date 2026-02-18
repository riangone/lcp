using System.Text.RegularExpressions;

namespace Platform.Application.CodeGeneration;

/// <summary>
/// 代码质量验证器 - 验证生成代码的质量和规范符合性
/// 简化版本，使用基本的字符串和正则表达式检查
/// </summary>
public class CodeQualityValidator
{
    private readonly CodeQualityRules _rules;

    public CodeQualityValidator(CodeQualityRules? rules = null)
    {
        _rules = rules ?? new CodeQualityRules();
    }

    /// <summary>
    /// 验证代码质量
    /// </summary>
    public async Task<CodeQualityReport> ValidateAsync(List<GeneratedFile> files)
    {
        var report = new CodeQualityReport();
        report.ValidatedAt = DateTime.UtcNow;
        report.FileCount = files.Count;

        foreach (var file in files)
        {
            var fileReport = await ValidateFileAsync(file);
            report.FileReports.Add(fileReport);
            report.Issues.AddRange(fileReport.Issues);
        }

        // 计算总体评分
        report.OverallScore = CalculateOverallScore(report);
        report.Passed = report.Issues.Count(i => i.Severity == IssueSeverity.Error) == 0;

        await Task.CompletedTask;
        return report;
    }

    /// <summary>
    /// 验证单个文件
    /// </summary>
    private async Task<FileQualityReport> ValidateFileAsync(GeneratedFile file)
    {
        var report = new FileQualityReport
        {
            FilePath = file.RelativePath,
            FileType = file.FileType
        };

        if (file.FileType == "cs")
        {
            // C# 基础语法检查
            report.Issues.AddRange(ValidateCSharpBasicSyntax(file));

            // 代码风格检查
            report.Issues.AddRange(ValidateCodeStyle(file));

            // 命名规范检查
            report.Issues.AddRange(ValidateNamingConventions(file));
        }
        else if (file.FileType == "cshtml")
        {
            // Razor 视图基础检查
            report.Issues.AddRange(ValidateRazorSyntax(file));
        }

        // 通用检查
        report.Issues.AddRange(ValidateFileHeader(file));
        report.Issues.AddRange(ValidateFileLength(file));

        await Task.CompletedTask;
        return report;
    }

    #region C# Syntax Validation

    private List<QualityIssue> ValidateCSharpBasicSyntax(GeneratedFile file)
    {
        var issues = new List<QualityIssue>();
        var content = file.Content;

        // 检查括号匹配
        var openBraces = content.Count(c => c == '{');
        var closeBraces = content.Count(c => c == '}');
        if (openBraces != closeBraces)
        {
            issues.Add(new QualityIssue
            {
                Severity = IssueSeverity.Error,
                Rule = "Brace Mismatch",
                Message = $"括号不匹配：{openBraces} 个左括号，{closeBraces} 个右括号",
                FilePath = file.RelativePath
            });
        }

        // 检查圆括号匹配
        var openParens = content.Count(c => c == '(');
        var closeParens = content.Count(c => c == ')');
        if (openParens != closeParens)
        {
            issues.Add(new QualityIssue
            {
                Severity = IssueSeverity.Error,
                Rule = "Parenthesis Mismatch",
                Message = $"圆括号不匹配：{openParens} 个左圆括号，{closeParens} 个右圆括号",
                FilePath = file.RelativePath
            });
        }

        // 检查是否有 namespace 声明
        if (!content.Contains("namespace "))
        {
            issues.Add(new QualityIssue
            {
                Severity = IssueSeverity.Warning,
                Rule = "Missing Namespace",
                Message = "文件缺少 namespace 声明",
                FilePath = file.RelativePath
            });
        }

        // 检查是否有 class 声明
        if (!content.Contains("class "))
        {
            issues.Add(new QualityIssue
            {
                Severity = IssueSeverity.Warning,
                Rule = "Missing Class",
                Message = "文件缺少 class 声明",
                FilePath = file.RelativePath
            });
        }

        // 检查空的 catch 块
        var emptyCatchPattern = @"catch\s*\([^)]*\)\s*\{\s*\}";
        var matches = Regex.Matches(content, emptyCatchPattern, RegexOptions.Multiline);
        foreach (Match match in matches)
        {
            var lineNum = content.Substring(0, match.Index).Count(c => c == '\n') + 1;
            issues.Add(new QualityIssue
            {
                Severity = IssueSeverity.Warning,
                Rule = "Empty Catch Block",
                Message = "空的 catch 块，应该记录异常或重新抛出",
                FilePath = file.RelativePath,
                Line = lineNum
            });
        }

        return issues;
    }

    #endregion

    #region Code Style Validation

    private List<QualityIssue> ValidateCodeStyle(GeneratedFile file)
    {
        var issues = new List<QualityIssue>();
        var lines = file.Content.Split('\n');

        for (int i = 0; i < lines.Length; i++)
        {
            var line = lines[i];

            // 检查行长度
            if (line.Length > _rules.MaxLineLength)
            {
                issues.Add(new QualityIssue
                {
                    Severity = IssueSeverity.Warning,
                    Rule = "Line Too Long",
                    Message = $"行长度 {line.Length} 超过限制 {_rules.MaxLineLength}",
                    FilePath = file.RelativePath,
                    Line = i + 1
                });
            }

            // 检查尾随空格
            if (_rules.CheckTrailingWhitespace && (line.EndsWith(" ") || line.EndsWith("\t")))
            {
                issues.Add(new QualityIssue
                {
                    Severity = IssueSeverity.Info,
                    Rule = "Trailing Whitespace",
                    Message = "行尾有多余空格",
                    FilePath = file.RelativePath,
                    Line = i + 1
                });
            }
        }

        // 检查文件末尾是否有空行
        if (_rules.RequireNewlineAtEndOfFile &&
            file.Content.Length > 0 &&
            file.Content[^1] != '\n')
        {
            issues.Add(new QualityIssue
            {
                Severity = IssueSeverity.Info,
                Rule = "Missing Newline",
                Message = "文件末尾缺少空行",
                FilePath = file.RelativePath
            });
        }

        return issues;
    }

    #endregion

    #region Naming Convention Validation

    private List<QualityIssue> ValidateNamingConventions(GeneratedFile file)
    {
        var issues = new List<QualityIssue>();
        var content = file.Content;

        // 检查类名（PascalCase）- 简化检查
        var classPattern = @"class\s+([A-Za-z_][A-Za-z0-9_]*)";
        var classMatches = Regex.Matches(content, classPattern);
        foreach (Match match in classMatches)
        {
            var className = match.Groups[1].Value;
            if (!IsPascalCase(className))
            {
                var lineNum = content.Substring(0, match.Index).Count(c => c == '\n') + 1;
                issues.Add(new QualityIssue
                {
                    Severity = IssueSeverity.Info,
                    Rule = "Naming Convention",
                    Message = $"类名 '{className}' 建议使用 PascalCase",
                    FilePath = file.RelativePath,
                    Line = lineNum
                });
            }
        }

        // 检查方法名（PascalCase）- 简化检查
        var methodPattern = @"(?:public|private|protected|internal)\s+(?:static\s+)?(?:async\s+)?(?:[A-Za-z_][A-Za-z0-9_]*)\s+([A-Za-z_][A-Za-z0-9_]*)\s*\(";
        var methodMatches = Regex.Matches(content, methodPattern);
        foreach (Match match in methodMatches)
        {
            var methodName = match.Groups[1].Value;
            // 跳过构造函数
            if (!IsPascalCase(methodName) && methodName != ".ctor")
            {
                var lineNum = content.Substring(0, match.Index).Count(c => c == '\n') + 1;
                issues.Add(new QualityIssue
                {
                    Severity = IssueSeverity.Info,
                    Rule = "Naming Convention",
                    Message = $"方法名 '{methodName}' 建议使用 PascalCase",
                    FilePath = file.RelativePath,
                    Line = lineNum
                });
            }
        }

        return issues;
    }

    #endregion

    #region Razor Validation

    private List<QualityIssue> ValidateRazorSyntax(GeneratedFile file)
    {
        var issues = new List<QualityIssue>();
        var content = file.Content;

        // 检查 @{} 块是否匹配
        var openBraces = content.Count(c => c == '{');
        var closeBraces = content.Count(c => c == '}');
        if (openBraces != closeBraces)
        {
            issues.Add(new QualityIssue
            {
                Severity = IssueSeverity.Error,
                Rule = "Razor Syntax Error",
                Message = $"括号不匹配：{openBraces} 个左括号，{closeBraces} 个右括号",
                FilePath = file.RelativePath
            });
        }

        return issues;
    }

    #endregion

    #region File Validation

    private List<QualityIssue> ValidateFileHeader(GeneratedFile file)
    {
        var issues = new List<QualityIssue>();

        if (_rules.RequireFileHeader && file.FileType == "cs")
        {
            var lines = file.Content.Split('\n');
            var hasHeader = false;

            foreach (var line in lines.Take(10))
            {
                if (line.Contains("此文件由代码生成器自动生成") ||
                    line.Contains("auto-generated") ||
                    line.Contains("<auto-generated"))
                {
                    hasHeader = true;
                    break;
                }
            }

            if (!hasHeader)
            {
                issues.Add(new QualityIssue
                {
                    Severity = IssueSeverity.Info,
                    Rule = "Missing File Header",
                    Message = "文件缺少自动生成头注释",
                    FilePath = file.RelativePath
                });
            }
        }

        return issues;
    }

    private List<QualityIssue> ValidateFileLength(GeneratedFile file)
    {
        var issues = new List<QualityIssue>();

        var lineCount = file.Content.Split('\n').Length;
        if (lineCount > _rules.MaxFileLength)
        {
            issues.Add(new QualityIssue
            {
                Severity = IssueSeverity.Warning,
                Rule = "File Too Long",
                Message = $"文件有 {lineCount} 行，超过限制 {_rules.MaxFileLength}",
                FilePath = file.RelativePath
            });
        }

        return issues;
    }

    #endregion

    #region Helper Methods

    private double CalculateOverallScore(CodeQualityReport report)
    {
        if (report.FileCount == 0) return 0;

        var errorWeight = 10;
        var warningWeight = 3;
        var infoWeight = 1;

        var totalDeductions = report.Issues.Sum(i => i.Severity switch
        {
            IssueSeverity.Error => errorWeight,
            IssueSeverity.Warning => warningWeight,
            IssueSeverity.Info => infoWeight,
            _ => 0
        });

        var maxScore = 100;
        var score = Math.Max(0, maxScore - totalDeductions);

        return score;
    }

    private bool IsPascalCase(string name)
    {
        if (string.IsNullOrEmpty(name)) return false;
        return char.IsUpper(name[0]) && !name.Contains("_");
    }

    private bool IsVoidElement(string tagName)
    {
        var voidElements = new[] { "br", "hr", "img", "input", "meta", "link", "area", "base", "col", "embed", "param", "source", "track", "wbr" };
        return voidElements.Contains(tagName.ToLower());
    }

    #endregion
}

/// <summary>
/// 代码质量规则配置
/// </summary>
public class CodeQualityRules
{
    /// <summary>
    /// 最大行长度
    /// </summary>
    public int MaxLineLength { get; set; } = 160;

    /// <summary>
    /// 最大文件长度（行数）
    /// </summary>
    public int MaxFileLength { get; set; } = 1000;

    /// <summary>
    /// 最大类长度（行数）
    /// </summary>
    public int MaxClassLength { get; set; } = 500;

    /// <summary>
    /// 最大圈复杂度
    /// </summary>
    public int MaxCyclomaticComplexity { get; set; } = 15;

    /// <summary>
    /// 是否检查尾随空格
    /// </summary>
    public bool CheckTrailingWhitespace { get; set; } = true;

    /// <summary>
    /// 是否检查未使用的 using
    /// </summary>
    public bool CheckUnusedUsings { get; set; } = true;

    /// <summary>
    /// 是否要求文件末尾有空行
    /// </summary>
    public bool RequireNewlineAtEndOfFile { get; set; } = true;

    /// <summary>
    /// 是否要求文件头注释
    /// </summary>
    public bool RequireFileHeader { get; set; } = true;

    /// <summary>
    /// 是否将警告视为错误
    /// </summary>
    public bool TreatWarningsAsErrors { get; set; } = false;
}

/// <summary>
/// 代码质量报告
/// </summary>
public class CodeQualityReport
{
    /// <summary>
    /// 验证时间
    /// </summary>
    public DateTime ValidatedAt { get; set; }

    /// <summary>
    /// 文件数量
    /// </summary>
    public int FileCount { get; set; }

    /// <summary>
    /// 总体评分（0-100）
    /// </summary>
    public double OverallScore { get; set; }

    /// <summary>
    /// 是否通过验证
    /// </summary>
    public bool Passed { get; set; }

    /// <summary>
    /// 文件报告列表
    /// </summary>
    public List<FileQualityReport> FileReports { get; set; } = new();

    /// <summary>
    /// 所有问题列表
    /// </summary>
    public List<QualityIssue> Issues { get; set; } = new();

    /// <summary>
    /// 问题摘要
    /// </summary>
    public string Summary => $"Files: {FileCount}, Errors: {Issues.Count(i => i.Severity == IssueSeverity.Error)}, Warnings: {Issues.Count(i => i.Severity == IssueSeverity.Warning)}, Score: {OverallScore:F1}";
}

/// <summary>
/// 文件质量报告
/// </summary>
public class FileQualityReport
{
    /// <summary>
    /// 文件路径
    /// </summary>
    public string FilePath { get; set; } = "";

    /// <summary>
    /// 文件类型
    /// </summary>
    public string FileType { get; set; } = "";

    /// <summary>
    /// 问题列表
    /// </summary>
    public List<QualityIssue> Issues { get; set; } = new();
}

/// <summary>
/// 质量问题
/// </summary>
public class QualityIssue
{
    /// <summary>
    /// 严重程度
    /// </summary>
    public IssueSeverity Severity { get; set; }

    /// <summary>
    /// 规则名称
    /// </summary>
    public string Rule { get; set; } = "";

    /// <summary>
    /// 问题描述
    /// </summary>
    public string Message { get; set; } = "";

    /// <summary>
    /// 文件路径
    /// </summary>
    public string FilePath { get; set; } = "";

    /// <summary>
    /// 行号
    /// </summary>
    public int? Line { get; set; }
}

/// <summary>
/// 问题严重程度
/// </summary>
public enum IssueSeverity
{
    Info,
    Warning,
    Error
}
