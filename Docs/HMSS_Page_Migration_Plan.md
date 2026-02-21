# HMSS 页面迁移计划

## 概述

将 `projects-hmss`（旧 PHP CakePHP 项目）中的 HMSS 子系统页面迁移到新的 .NET LowCode Platform 框架。

## 目标系统

### 现有子系统列表

| 子系统代码 | 子系统名称 | 页面数量 | 优先级 |
|----------|----------|---------|-------|
| **SDH** | 车检替代系统 | 7 | P0 |
| **HDKAIKEI** | 会计系统 | 21 | P0 |
| **HMDPS** | DPS 系统 | 13 | P0 |
| **R4** | R4 系统 | 3 | P0 |
| **JKSYS** | 人事系统 | ? | P1 |
| **HMAUD** | 审计系统 | ? | P1 |
| **HMTVE** | TVE 系统 | ? | P1 |
| **APPM** | APPM 系统 | ? | P2 |
| **PPRM** | PPRM 系统 | ? | P2 |
| **HMHRMS** | HRMS 系统 | ? | P2 |
| **CkChkzaiko** | 库存系统 | ? | P2 |

## 迁移策略

### 1. 数据模型层（已完成）

现有 YAML 定义位于 `Definitions/hmss/`：
- `common.yaml` - 公共模型
- `sdh.yaml` - SDH 模型
- `hdkaikei.yaml` - HDKAIKEI 模型
- `hmdps.yaml` - HMDPS 模型
- `r4.yaml` - R4 模型
- 其他...

### 2. 页面定义层（需要创建）

为每个子系统的复杂页面创建多表表单定义：

```yaml
# Definitions/hmss/pages/sdh01.yaml
pages:
  Sdh01:
    title: 车检替代判定
    main_table: SdhHantei
    
    data_loading:
      strategy: parallel
      sources:
        - id: hantei_data
          type: table
          table: sdh_hantei
          where: "VIN = @VIN"
        
        - id: contractor_data
          type: table
          table: sdh_contractor
          where: "CSRNO = @CSRNO"
        
        - id: timeline_data
          type: table
          table: sdh_timeline
          where: "VIN = @VIN"
    
    save_config:
      transaction:
        enabled: true
      save_order:
        - order: 1
          table: sdh_contractor
          crud_type: upsert
          match_fields: [CSRNO]
        - order: 2
          table: sdh_hantei
          crud_type: upsert
          match_fields: [VIN]
        - order: 3
          table: sdh_timeline
          crud_type: insert
```

### 3. UI 视图层（需要创建）

为每个页面创建 Razor 视图：

```
Platform.Api/Views/HmssPage/
├── Sdh/
│   ├── Sdh01.cshtml       # 车检替代判定
│   ├── Sdh02.cshtml       # 契约者管理
│   ├── Sdh03.cshtml       # 活动状况
│   └── ...
├── Hdkaikei/
│   ├── MainMenu.cshtml
│   ├── DenpyoSearch.cshtml
│   └── ...
└── ...
```

## 迁移步骤

### Phase 1: SDH 系统（P0）

1. **SDH01 - 车检替代判定**
   - 主表：`sdh_hantei`
   - 关联表：`sdh_contractor`, `sdh_timeline`, `sdh_biko`
   - 功能：判定、搜索、登录、修正、删除
   - 优先级：最高

2. **SDH02 - 契约者管理**
   - 主表：`sdh_contractor`
   - 功能：契约者搜索、登录、修正

3. **SDH03 - 活动状况管理**
   - 主表：`sdh_katsudo`
   - 功能：活动状况登录、搜索

4. **SDH04-SDH07** - 其他功能

### Phase 2: HDKAIKEI 系统（P0）

主要页面：
- `HDKDenpyoSearch` - 传票搜索
- `HDKShiwakeInput` - 仕訳入力
- `HDKShiharaiInput` - 支払い入力
- `HDKPatternSearch` - パターン搜索
- `HDKKamokuMst` - 科目マスタ
- `HDKSyainMstEdit` - 社員マスタ
- `HDKTorihikisakiMst` - 取引先マスタ

### Phase 3: HMDPS 系统（P0）

主要页面：
- `HMDPS100DenpyoSearch` - 传票搜索
- `HMDPS101ShiwakeDenpyoInput` - 仕訳传票入力
- `HMDPS102ShiharaiDenpyoInput` - 支払い传票入力
- `HMDPS103PatternSearch` - パターン搜索

### Phase 4: R4 系统（P0）

- `R4K` - 会计系统
- `R4G` - 系统
- `KRSS` - 系统

## 技术实现

### 1. 多表表单支持

现有框架已支持多表表单，需要增强：
- [x] 多表数据加载
- [x] 事务保存
- [ ] 字段映射（from_table, from_field）
- [ ] 动态字段验证

### 2. 专用 UI 组件

为 HMSS 创建专用 UI 组件：
- 复杂的表格布局
- 多标签页支持
- 动态行添加/删除
- 条件显示/隐藏

### 3. 业务规则验证

在 YAML 中定义业务规则：
```yaml
models:
  SdhHantei:
    business_rules:
      - name: 判定日期检查
        condition: "HANTEI_DT >= CONTRACT_DT"
        message: "判定日期不能早于契约日期"
        severity: error
```

## 进度追踪

| 子系统 | 模型定义 | 页面定义 | UI 视图 | 测试 | 状态 |
|-------|---------|---------|--------|-----|------|
| SDH   | ✅      | ⏳      | ⏳     | ⏳   | 进行中 |
| HDKAIKEI | ✅   | ⏳      | ⏳     | ⏳   | 待开始 |
| HMDPS | ✅      | ⏳      | ⏳     | ⏳   | 待开始 |
| R4    | ✅      | ⏳      | ⏳     | ⏳   | 待开始 |

## 注意事项

1. **保持旧系统功能**：确保迁移后功能与旧系统一致
2. **渐进式迁移**：先迁移核心功能，再迁移辅助功能
3. **数据兼容性**：确保新旧系统可以共用数据库
4. **用户培训**：UI 变化需要提前通知用户

## 参考文档

- [HMSS Migration Plan](HMSS_Migration_Plan.md)
- [MultiTableForm](MultiTableForm.md)
- [LowCode Enhancement Plan](LowCode_Enhancement_Plan.md)
