# 确定性代码生成系统文档

## 📋 概述

本系统旨在解决 AI 生成代码的**随机性**和**不可维护性**问题，通过以下机制确保生成的代码是**稳定的、一致的、可维护的**：

1. **模板驱动生成** - 使用预定义的代码模板，确保每次生成的代码结构一致
2. **版本管理** - 追踪每次代码生成的版本，支持回滚和差异比较
3. **质量验证** - 自动验证生成代码的语法、风格和质量
4. **变更检测** - 只在 YAML 定义变更时重新生成代码
5. **确定性输出** - 相同的输入始终产生相同的输出

## 🏗️ 架构设计

```
┌─────────────────────────────────────────────────────────────┐
│                    CodeGenerationService                     │
│                      (统一入口服务)                           │
└─────────────────────────────────────────────────────────────┘
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
        ▼                     ▼                     ▼
┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐
│ ModelCodeGenerator│ │CodeVersionManager│ │CodeQualityValidator│
│   (代码生成器)    │ │   (版本管理器)    │ │   (质量验证器)    │
└──────────────────┘ └──────────────────┘ └──────────────────┘
        │                     │                     │
        │                     │                     │
        ▼                     ▼                     ▼
┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐
│ CodeTemplateEngine│ │  versions.json   │ │  Roslyn Analyzer │
│   (模板引擎)      │ │  (版本存储)      │ │  (代码分析)      │
└──────────────────┘ └──────────────────┘ └──────────────────┘
```

## 📦 核心组件

### 1. ICodeGenerator (代码生成器接口)

定义代码生成的标准接口：

```csharp
public interface ICodeGenerator
{
    string GeneratorId { get; }
    string Version { get; }
    Task<CodeGenerationResult> GenerateAsync(CodeGenerationContext context);
    Task<CodeValidationResult> ValidateAsync(CodeGenerationResult result);
}
```

### 2. ModelCodeGenerator (模型代码生成器)

从 `ModelDefinition` 生成确定性代码：

- 生成 API 控制器
- 生成验证器
- 生成服务层（可选）
- 生成单元测试（可选）

### 3. CodeTemplateEngine (代码模板引擎)

使用模板确保代码一致性：

```csharp
var engine = new CodeTemplateEngine();
var code = engine.Render("ApiController", new TemplateContext
{
    Namespace = "Platform.Api",
    Model = new ModelTemplateContext { Name = "Product" }
});
```

### 4. CodeVersionManager (代码版本管理器)

管理代码版本和变更追踪：

- 保存每次生成的版本
- 比较版本差异
- 支持回滚
- 检测 YAML 变更

### 5. CodeQualityValidator (代码质量验证器)

验证生成代码的质量：

- C# 语法分析（使用 Roslyn）
- 代码风格检查
- 命名规范验证
- 复杂度分析

### 6. CodeGenerationService (代码生成服务)

统一入口，整合所有组件：

```csharp
var service = new CodeGenerationService(settings);
var result = await service.GenerateFromYamlAsync(
    "Definitions/app.yaml",
    "output"
);
```

## 🚀 使用指南

### 基本使用

```csharp
using Platform.Application.CodeGeneration;

// 1. 创建服务
var settings = new CodeGenerationSettings
{
    RootNamespace = "Platform.Api",
    VersionDirectory = ".code_versions",
    AddHeaderComments = true,
    FailOnQualityError = false
};

var service = new CodeGenerationService(settings);

// 2. 从 YAML 生成代码
var result = await service.GenerateFromYamlAsync(
    yamlFilePath: "Definitions/app.yaml",
    outputDirectory: "Generated",
    modelKey: null // null 表示生成所有模型
);

// 3. 检查结果
if (result.Success)
{
    Console.WriteLine($"生成完成，耗时：{result.Duration:F2}秒");
    foreach (var kvp in result.ModelResults)
    {
        var modelResult = kvp.Value;
        Console.WriteLine($"  {kvp.Key}: {(modelResult.Success ? "成功" : "失败")}");
        Console.WriteLine($"    文件：{modelResult.WrittenFiles.Count}");
        Console.WriteLine($"    质量评分：{modelResult.QualityReport?.OverallScore:F1}");
    }
}
```

### 生成单个模型

```csharp
// 只生成 Artist 模型的代码
var result = await service.GenerateFromYamlAsync(
    "Definitions/app.yaml",
    "Generated",
    modelKey: "Artist"
);
```

### 查看版本历史

```csharp
// 获取版本历史
var versions = await service.GetVersionHistoryAsync("Artist");
foreach (var version in versions.OrderByDescending(v => v.GeneratedAt))
{
    Console.WriteLine($"{version.VersionId} - {version.GeneratedAt}");
    Console.WriteLine($"  YAML Hash: {version.YamlHash}");
    Console.WriteLine($"  变更：{version.ChangeDescription}");
}
```

### 比较版本差异

```csharp
// 比较两个版本
var diff = await service.CompareVersionsAsync(
    "v20260219-120000-1234",
    "v20260219-140000-5678"
);

Console.WriteLine($"新增文件：{diff.AddedFiles.Count}");
Console.WriteLine($"修改文件：{diff.ModifiedFiles.Count}");
Console.WriteLine($"删除文件：{diff.DeletedFiles.Count}");
```

### 回滚到指定版本

```csharp
// 回滚
var success = await service.RollbackAsync("v20260219-120000-1234");
```

### 验证现有代码

```csharp
// 验证现有代码质量
var report = await service.ValidateExistingCodeAsync("Platform.Api");
Console.WriteLine($"总体评分：{report.OverallScore:F1}");
Console.WriteLine($"问题数：{report.Issues.Count}");
```

## ⚙️ 配置选项

### CodeGenerationSettings

| 属性 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `RootNamespace` | string | "Platform.Api" | 根命名空间 |
| `VersionDirectory` | string | ".code_versions" | 版本存储目录 |
| `IndentSize` | int | 4 | 代码缩进大小 |
| `AddHeaderComments` | bool | true | 添加文件头注释 |
| `GenerateProjectFiles` | bool | true | 生成项目文件 |
| `ForceRegenerate` | bool | false | 强制重新生成 |
| `FailOnQualityError` | bool | false | 质量失败时停止 |

### CodeGenerationOptions

| 属性 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `GenerateApiController` | bool | true | 生成 API 控制器 |
| `GenerateUiController` | bool | true | 生成 UI 控制器 |
| `GenerateViews` | bool | true | 生成 Razor 视图 |
| `GenerateServices` | bool | false | 生成服务层 |
| `GenerateRepositories` | bool | false | 生成仓储层 |
| `GenerateValidators` | bool | true | 生成验证器 |
| `GenerateTests` | bool | false | 生成单元测试 |
| `OverwriteExisting` | bool | false | 覆盖现有文件 |
| `FormatCode` | bool | true | 格式化代码 |

### CodeQualityRules

| 属性 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `MaxLineLength` | int | 160 | 最大行长度 |
| `MaxFileLength` | int | 1000 | 最大文件长度 |
| `MaxClassLength` | int | 500 | 最大类长度 |
| `MaxCyclomaticComplexity` | int | 15 | 最大圈复杂度 |
| `CheckTrailingWhitespace` | bool | true | 检查尾随空格 |
| `RequireNewlineAtEndOfFile` | bool | true | 要求文件末尾空行 |
| `RequireFileHeader` | bool | true | 要求文件头注释 |

## 📝 代码模板

### 内置模板

系统预置以下模板：

1. **ApiController** - API 控制器模板
2. **Validator** - 验证器模板
3. **Service** - 服务层模板
4. **UnitTest** - 单元测试模板

### 自定义模板

```csharp
var engine = new CodeTemplateEngine();

// 注册自定义模板
engine.RegisterTemplate("CustomController", @"
using Microsoft.AspNetCore.Mvc;

namespace {{Namespace}}.Controllers;

/// <summary>
/// {{Model.Name}} 自定义控制器
/// </summary>
public class {{Model.Name}}Controller : ControllerBase
{
    // 自定义实现
}
");

// 使用模板
var code = engine.Render("CustomController", context);
```

### 模板语法

```
// 变量替换
{{Namespace}}
{{Model.Name}}
{{GeneratorVersion}}

// 条件块
{{#if GenerateServices}}
// 生成服务代码
{{/if}}

// 循环块
{{#each Properties}}
public {{Type}} {{Name}} { get; set; }
{{/each}}
```

## 🔄 工作流程

```
┌─────────────┐
│ YAML 定义    │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ 计算 YAML 哈希 │
└──────┬──────┘
       │
       ▼
┌─────────────┐     ┌─────────────┐
│ 检查版本历史 │────▶│ 有变更？     │
└─────────────┘     └──────┬──────┘
                           │
              ┌────────────┼────────────┐
              │ No         │ Yes        │
              │            │            │
              ▼            ▼            ▼
         ┌────────┐  ┌──────────┐  ┌──────────┐
         │ 跳过   │  │ 生成代码  │  │ 质量验证 │
         └────────┘  └─────┬────┘  └─────┬────┘
                           │             │
                           ▼             ▼
                    ┌──────────┐  ┌──────────┐
                    │ 保存版本  │  │ 通过？   │
                    └─────┬────┘  └─────┬────┘
                          │             │
                          │      ┌──────┼──────┐
                          │      │ No   │ Yes  │
                          │      │      │      │
                          │      ▼      ▼      │
                          │ ┌──────┐ ┌──────┐ │
                          │ │ 报错 │ │ 写入 │ │
                          │ └──────┘ └──┬───┘ │
                          │             │      │
                          └─────────────┼──────┘
                                        │
                                        ▼
                                 ┌──────────┐
                                 │ 完成     │
                                 └──────────┘
```

## 📊 版本存储结构

```
.code_versions/
├── versions.json              # 版本元数据
├── v20260219-120000-1234/     # 版本目录
│   ├── Controllers/
│   │   └── ArtistController.cs
│   ├── Validators/
│   │   └── ArtistValidator.cs
│   └── ...
├── v20260219-140000-5678/
│   └── ...
└── ...
```

### versions.json 格式

```json
[
  {
    "versionId": "v20260219-120000-1234",
    "modelKey": "Artist",
    "yamlHash": "abc123...",
    "generatedAt": "2026-02-19T12:00:00Z",
    "changeDescription": "初始生成",
    "generatorVersion": "1.0.0",
    "fileCount": 2,
    "contentHash": "def456...",
    "status": "active",
    "changesFromPrevious": null
  }
]
```

## ✅ 质量保证

### 语法验证

- C# 语法正确性（使用 Roslyn）
- Razor 语法正确性
- 括号匹配
- 命名空间声明

### 代码风格

- 行长度限制
- 尾随空格检查
- 文件末尾空行
- 缩进一致性

### 命名规范

- 类名：PascalCase
- 方法名：PascalCase
- 字段名：camelCase 或 _camelCase

### 复杂度控制

- 圈复杂度 ≤ 15
- 类长度 ≤ 500 行
- 文件长度 ≤ 1000 行

## 🔧 最佳实践

### 1. 首次生成

```csharp
// 首次生成时，建议启用所有选项
var settings = new CodeGenerationSettings
{
    GenerateProjectFiles = true,
    AddHeaderComments = true,
    OverwriteExisting = true // 首次可以覆盖
};
```

### 2. 增量生成

```csharp
// 后续生成时，只生成变更的部分
var settings = new CodeGenerationSettings
{
    ForceRegenerate = false, // 默认不强制
    OverwriteExisting = false // 不覆盖现有文件
};
```

### 3. CI/CD 集成

```csharp
// 在 CI/CD 中，启用严格模式
var settings = new CodeGenerationSettings
{
    FailOnQualityError = true,
    TreatWarningsAsErrors = true
};

var result = await service.GenerateFromYamlAsync(...);
if (!result.Success)
{
    Environment.Exit(1);
}
```

### 4. 版本审查

```csharp
// 定期审查版本历史
var versions = await service.GetVersionHistoryAsync("Artist");
var recentChanges = versions
    .OrderByDescending(v => v.GeneratedAt)
    .Take(10);

foreach (var version in recentChanges)
{
    Console.WriteLine($"{version.VersionId}: {version.ChangeDescription}");
}
```

## 🐛 故障排除

### 问题：生成的代码与预期不符

**解决**：
1. 检查 YAML 定义是否正确
2. 查看模板内容是否符合预期
3. 检查 `TemplateContext` 是否正确填充

### 问题：质量验证失败

**解决**：
1. 查看 `QualityReport.Issues` 详情
2. 调整 `CodeQualityRules` 配置
3. 检查模板是否符合规范

### 问题：版本管理异常

**解决**：
1. 检查 `.code_versions/versions.json` 是否损坏
2. 删除版本目录重新生成
3. 确保 YAML 哈希计算一致

## 📈 扩展点

### 添加自定义生成器

```csharp
public class CustomCodeGenerator : ICodeGenerator
{
    public string GeneratorId => "custom-generator";
    public string Version => "1.0.0";

    public async Task<CodeGenerationResult> GenerateAsync(CodeGenerationContext context)
    {
        // 自定义生成逻辑
        return await Task.FromResult(new CodeGenerationResult());
    }

    public async Task<CodeValidationResult> ValidateAsync(CodeGenerationResult result)
    {
        // 自定义验证逻辑
        return await Task.FromResult(new CodeValidationResult());
    }
}
```

### 添加自定义验证规则

```csharp
var rules = new CodeQualityRules
{
    MaxLineLength = 120, // 更严格的行长度
    MaxCyclomaticComplexity = 10 // 更严格的复杂度
};

var validator = new CodeQualityValidator(rules);
```

### 添加自定义模板

```csharp
var engine = new CodeTemplateEngine();
engine.RegisterTemplate("MyTemplate", @"
// 自定义模板内容
");
```

## 📚 相关文件

- `ICodeGenerator.cs` - 代码生成器接口
- `CodeTemplateEngine.cs` - 代码模板引擎
- `ModelCodeGenerator.cs` - 模型代码生成器
- `CodeVersionManager.cs` - 代码版本管理器
- `CodeQualityValidator.cs` - 代码质量验证器
- `CodeGenerationService.cs` - 代码生成服务

## 🎯 总结

通过本系统，您可以：

1. ✅ **确定性生成** - 相同 YAML 始终生成相同代码
2. ✅ **版本追踪** - 记录每次变更，支持回滚
3. ✅ **质量保证** - 自动验证语法、风格、复杂度
4. ✅ **变更检测** - 只在必要时重新生成
5. ✅ **可维护性** - 生成的代码结构一致，易于维护

这确保了 AI 生成的代码是**稳定运行的、统一的、可维护的**应用程序，而不是每次创建随机的代码。
