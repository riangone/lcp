# HMSS 子系统页面修复文档

## 问题描述

### 问题 1：HMSS 子系统页面只显示 3 个表链接
**现象**：进入 HMSS 子系统的页面（如 `/hmss/sdh`）后，数据列表标签页只显示 3 个硬编码的示例数据表链接。

**原因**：`Subsystem.cshtml` 中的 `getModelsWithSystem()` 函数是硬编码的，只返回 3 个示例数据：
```javascript
function getModelsWithSystem(systemCode) {
    return [
        { name: `${systemCode}_DATA`, desc: '主要数据表', icon: 'bi-table' },
        { name: `${systemCode}_CONFIG`, desc: '系统配置表', icon: 'bi-gear' },
        { name: `${systemCode}_LOG`, desc: '日志记录表', icon: 'bi-journal-text' }
    ];
}
```

### 问题 2：SDH 子系统进入后异常
**现象**：进入 SDH 子系统页面后可能显示异常或无法加载数据模型。

**原因**：同上，没有从 YAML 配置中动态加载实际的模型定义。

## 解决方案

### 1. 添加 API 端点获取模型列表

**文件**：`Platform.Api/Controllers/HmssController.cs`

添加了新的 API 端点 `/api/hmss/models`，返回 HMSS 项目的所有模型定义：

```csharp
[HttpGet("models")]
public IActionResult GetModels()
{
    if (!_projectManager.TryGetProject("hmss", out var project))
    {
        return NotFound(new { success = false, message = "HMSS 项目未找到" });
    }

    var models = project.AppDefinitions.Models;
    var modelList = models.Select(kvp => new
    {
        key = kvp.Key,
        name = kvp.Key,
        table = kvp.Value.Table,
        primaryKey = kvp.Value.PrimaryKey,
        isReadOnly = kvp.Value.IsReadOnly,
        displayName = kvp.Value.Ui?.Labels?.Zh?.FirstOrDefault().Value 
            ?? kvp.Value.Ui?.Labels?.En?.FirstOrDefault().Value 
            ?? kvp.Value.Table
    }).ToList();

    return Ok(modelList);
}
```

### 2. 更新 Subsystem.cshtml 动态加载模型

**文件**：`Platform.Api/Views/Hmss/Subsystem.cshtml`

#### 添加模型图标和描述映射
```javascript
const modelIcons = {
    'SdhContractor': 'bi-people',
    'SdhTenpo': 'bi-buildings',
    'SdhSyasyuMst': 'bi-car-front',
    'SdhHanteiLst': 'bi-clipboard-check',
    // ... 更多映射
};

const modelDescriptions = {
    'SdhContractor': '契約者管理 - 客户信息主表',
    'SdhTenpo': '店舗管理 - 店铺信息主表',
    // ... 更多映射
};
```

#### 更新 loadModels 函数
```javascript
async function loadModels() {
    try {
        const response = await fetch(`/api/hmss/models`);
        let allModels = [];

        if (response.ok) {
            const result = await response.json();
            allModels = result || [];
        } else {
            console.warn('Models API not available, using fallback');
            allModels = getModelsWithSystemFallback(systemCode);
        }

        const models = filterModelsBySystem(allModels, systemCode);
        renderModels(models);
        updateInfo(models.length);
    } catch (error) {
        console.error('Load models error:', error);
        const models = getModelsWithSystemFallback(systemCode);
        renderModels(models);
        updateInfo(models.length);
    }
}
```

#### 添加模型过滤函数
```javascript
function filterModelsBySystem(allModels, systemCode) {
    if (!allModels || allModels.length === 0) {
        return getModelsWithSystemFallback(systemCode);
    }

    const prefix = systemCode.toLowerCase();

    return allModels.filter(model => {
        const modelName = model.name || model.key || model;
        return modelName.toLowerCase().startsWith(prefix);
    }).map(model => {
        const modelName = model.name || model.key || model;
        return {
            name: modelName,
            desc: modelDescriptions[modelName] || '数据表',
            icon: modelIcons[modelName] || 'bi-table'
        };
    });
}
```

#### 添加回退函数（硬编码数据）
```javascript
function getModelsWithSystemFallback(systemCode) {
    const systemModels = {
        'SDH': [
            { name: 'SdhContractor', desc: '契約者管理', icon: 'bi-people' },
            { name: 'SdhTenpo', desc: '店舗管理', icon: 'bi-buildings' },
            { name: 'SdhSyasyuMst', desc: '判定車種マスタ', icon: 'bi-car-front' },
            { name: 'SdhHanteiLst', desc: '判定リスト', icon: 'bi-clipboard-check' },
            { name: 'SdhTimeline', desc: 'タイムライン', icon: 'bi-timeline' },
            { name: 'SdhKatsudo', desc: '活動状況', icon: 'bi-activity' },
            { name: 'SdhVinWmivds', desc: 'VIN WMIVDS', icon: 'bi-upc-scan' },
            { name: 'SdhVinVis', desc: 'VIN VIS', icon: 'bi-qr-code-scan' },
            { name: 'SdhChumon', desc: '注文書', icon: 'bi-file-earmark-text' },
            { name: 'SdhHoken', desc: '保険', icon: 'bi-shield-check' }
        ],
        // ... 其他系统
    };

    return systemModels[systemCode] || [
        { name: `${systemCode}_DATA`, desc: '主要数据表', icon: 'bi-table' },
        { name: `${systemCode}_CONFIG`, desc: '系统配置表', icon: 'bi-gear' },
        { name: `${systemCode}_LOG`, desc: '日志记录表', icon: 'bi-journal-text' }
    ];
}
```

## 修改的文件

1. **Platform.Api/Controllers/HmssController.cs**
   - 添加 `ProjectManager` 依赖注入
   - 添加 `/api/hmss/models` API 端点

2. **Platform.Api/Views/Hmss/Subsystem.cshtml**
   - 添加模型图标映射 `modelIcons`
   - 添加模型描述映射 `modelDescriptions`
   - 更新 `loadModels()` 函数从 API 加载数据
   - 添加 `filterModelsBySystem()` 函数过滤模型
   - 添加 `getModelsWithSystemFallback()` 回退函数

## 预期效果

### SDH 子系统
进入 `/hmss/sdh` 页面后，数据列表标签页应显示以下模型：
1. SdhContractor - 契約者管理
2. SdhTenpo - 店舗管理
3. SdhSyasyuMst - 判定車種マスタ
4. SdhHanteiLst - 判定リスト
5. SdhTimeline - タイムライン
6. SdhKatsudo - 活動状況
7. SdhVinWmivds - VIN WMIVDS
8. SdhVinVis - VIN VIS
9. SdhChumon - 注文書
10. SdhHoken - 保険

### 其他子系统
每个子系统都会根据其前缀过滤显示相应的模型。

## 测试步骤

1. 启动应用：`dotnet run --project Platform.Api`
2. 访问 HMSS 主页：`/hmss/master`
3. 点击任意子系统（如 SDH）
4. 检查"数据列表"标签页是否显示正确的模型列表
5. 点击模型卡片应能跳转到对应的 UI 页面

## 注意事项

1. **API 端点需要认证**：`/api/hmss/models` 端点使用了 `[Authorize]` 特性，需要先登录
2. **回退机制**：如果 API 不可用，页面会使用硬编码的回退数据，确保页面不会完全失败
3. **模型命名规范**：模型名称应以系统代码前缀开头（如 `Sdh`、`Hdkaikei` 等）
4. **YAML 配置**：确保 `Projects/hmss/app.yaml` 正确引用了所有子系统 YAML 文件

## 后续优化建议

1. **缓存机制**：可以在服务端缓存模型列表，减少每次请求的开销
2. **权限控制**：根据用户权限过滤可访问的模型
3. **动态图标**：可以从 YAML 配置中读取图标，而不是硬编码
4. **搜索功能**：在子系统页面添加模型搜索功能
