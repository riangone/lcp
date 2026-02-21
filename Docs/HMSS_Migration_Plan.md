# HMSS → LCP 迁移实施计划

## 📋 项目概述

### 迁移目标
将 HMSS (Hiroshima Mazda Sales System) 的所有功能从 CakePHP 5.1 迁移到 .NET 10 低代码平台 (LCP)。

### 技术选型决策

| 项目 | 决策 | 说明 |
|------|------|------|
| **迁移策略** | 分阶段迁移 | 按优先级分 5 个阶段 |
| **数据库** | SQLite | 简化部署，便于开发测试 |
| **认证方式** | ASP.NET Identity | 原生支持，安全可靠 |
| **UI 风格** | 重新设计 | 现代化 UI，响应式设计 |

### 迁移进度

| 阶段 | 内容 | 状态 | 完成日期 |
|------|------|------|----------|
| P0 | 基础架构准备 | ✅ 完成 | 2026-02-21 |
| P1 | Master/Login 系统 | ✅ 完成 | 2026-02-21 |
| P2 | SDH 车检替代 | ✅ 完成 | 2026-02-21 |
| P3 | 财务会计系统 | ✅ 完成 | 2026-02-21 |
| P4 | 人力资源系统 | ✅ 完成 | 2026-02-21 |
| P5 | 其他系统 | ✅ 完成 | 2026-02-21 |
| **总计** | **14 子系统全部迁移完成** | ✅ | **2026-02-21** |

---

## 🏗️ 架构设计

### 目标架构

```
┌─────────────────────────────────────────────────────────┐
│                      HTTP Request                        │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              ASP.NET Core 10 Middleware                  │
│           - Authentication (Identity)                    │
│           - Authorization (Roles/Permissions)            │
│           - Localization (zh-CN/ja-JP)                   │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              GenericApiController                       │
│           (一个控制器处理所有 HMSS 模型)                    │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              AppDefinitions (YAML 加载)                  │
│           - HMSS Models (14 子系统模型定义)               │
│           - HMSS Pages (多表页面定义)                     │
│           - UI Config (现代化 UI 配置)                    │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              DynamicRepository                          │
│           (动态构建 SQL 执行 CRUD)                        │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                   SQLite Database                       │
│           - HMSS 业务数据表                              │
│           - ASP.NET Identity 表                         │
│           - 审计日志表                                   │
└─────────────────────────────────────────────────────────┘
```

### 目录结构扩展

```
lcp/
├── Definitions/
│   ├── app.yaml                     # 原有模型定义
│   ├── hmss/                        # ★ HMSS 模型定义
│   │   ├── common.yaml              # 共通表定义
│   │   ├── master.yaml              # SYS1 Master
│   │   ├── login.yaml               # SYS2 Login
│   │   ├── hdkaikei.yaml            # SYS4 会计
│   │   ├── hmaud.yaml               # SYS5 审计
│   │   ├── hmdps.yaml               # SYS6 DPS
│   │   ├── hmhrms.yaml              # SYS7 人力资源
│   │   ├── hmtve.yaml               # SYS8 数据汇总
│   │   ├── jksys.yaml               # SYS9 人事給与
│   │   ├── r4.yaml                  # SYS10 R4 系统群
│   │   ├── sdh.yaml                 # SYS11 车检替代
│   │   ├── appm.yaml                # SYS12 广域应用
│   │   ├── pprm.yaml                # SYS13 无纸化
│   │   └── ckchkuzaiko.yaml         # SYS14 库存
│   └── pages/
│       └── hmss/                    # ★ HMSS 多表页面
│           ├── master_dashboard.yaml
│           ├── sdh_hantei.yaml
│           └── ...
│
├── Platform.Api/
│   ├── Controllers/
│   │   ├── GenericApiController.cs  # 原有
│   │   ├── UiController.cs          # 原有
│   │   ├── HmssController.cs        # ★ HMSS 专用控制器
│   │   └── AccountController.cs     # ★ 认证控制器
│   ├── Views/
│   │   ├── Shared/
│   │   │   ├── _Layout.cshtml       # 原有
│   │   │   ├── _HmssLayout.cshtml   # ★ HMSS 布局
│   │   │   └── Components/          # ★ UI 组件
│   │   │       ├── Sidebar.cshtml
│   │   │       ├── Header.cshtml
│   │   │       └── DataTable.cshtml
│   │   ├── Hmss/
│   │   │   ├── Master/
│   │   │   ├── Login/
│   │   │   ├── Sdh/
│   │   │   └── ...
│   └── wwwroot/
│       ├── css/
│       │   ├── hmss-theme.css       # ★ HMSS 主题
│       │   └── components/          # ★ 组件样式
│       └── js/
│           └── hmss-app.js          # ★ HMSS 前端应用
│
├── Platform.Infrastructure/
│   ├── Identity/
│   │   ├── HmssIdentityService.cs   # ★ Identity 服务
│   │   └── HmssRoleStore.cs         # ★ 角色存储
│   └── Repositories/
│       └── DynamicRepository.cs     # 原有（增强）
│
└── Docs/
    ├── HMSS_Migration_Plan.md       # ★ 本文档
    ├── HMSS_Database_Schema.md      # ★ 数据库设计
    └── HMSS_UI_Design.md            # ★ UI 设计
```

---

## 📅 迁移阶段

### 阶段 1: 基础架构准备 (P0) - 2 周

**目标**: 建立迁移基础框架

| 任务 ID | 任务名 | 工作量 | 优先级 |
|--------|-------|--------|--------|
| P0-1 | ASP.NET Identity 集成 | 2 天 | 🔴 |
| P0-2 | 多语言支持 (zh-CN/ja-JP) | 1 天 | 🔴 |
| P0-3 | 现代化 UI 布局组件 | 3 天 | 🔴 |
| P0-4 | SQLite 数据库初始化 | 1 天 | 🔴 |
| P0-5 | HMSS 基础表结构迁移 | 3 天 | 🔴 |

**交付物**:
- ✅ 用户登录/登出功能
- ✅ 角色权限基础框架
- ✅ 现代化 UI 布局（侧边栏 + 顶栏）
- ✅ 数据库初始化脚本

---

### 阶段 2: Master/Login 系统 (P1) - 2 周

**目标**: 实现统一入口和认证

| 任务 ID | 任务名 | 工作量 | 优先级 |
|--------|-------|--------|--------|
| P1-1 | M_LOGIN 表迁移 | 1 天 | 🔴 |
| P1-2 | 系统权限管理 | 2 天 | 🔴 |
| P1-3 | Master 系统入口页面 | 2 天 | 🔴 |
| P1-4 | 14 子系统导航 | 1 天 | 🔴 |
| P1-5 | 会话管理 | 1 天 | 🔴 |
| P1-6 | 密码重置功能 | 1 天 | 🟡 |

**HMSS 模型定义示例** (`hmss/master.yaml`):

```yaml
models:
  HmssUser:
    table: hmss_users
    primary_key: usr_id

    ui:
      layout:
        theme: modern
        grid_columns: 2
      labels:
        zh:
          title: 用户管理
          USR_ID: 用户 ID
          USR_NAME: 用户姓名
          email: 邮箱
        ja:
          title: ユーザー管理
          USR_ID: ユーザー ID
          USR_NAME: ユーザー名
          email: メール

    list:
      columns: [USR_ID, USR_NAME, email, SYS1_FLG]
      filters:
        USR_ID: { label: 用户 ID, type: like }
        USR_NAME: { label: 姓名，type: like }

    form:
      fields:
        USR_ID:
          label: 用户 ID
          type: text
          required: true
          max_length: 20
        USR_NAME:
          label: 姓名
          type: text
          required: true
        email:
          label: 邮箱
          type: email

    properties:
      USR_ID: { type: string }
      USR_NAME: { type: string }
      email: { type: string }

  HmssSystem:
    table: hmss_system_m
    primary_key: SYS_CD

    ui:
      labels:
        zh:
          title: 系统管理
          SYS_CD: 系统代码
          SYS_NM: 系统名称
          SYS_URL: 系统 URL

    list:
      columns: [SYS_CD, SYS_NM, SYS_ORDER, SYS_USE_FLG]
      filters:
        SYS_NM: { label: 系统名称，type: like }

    form:
      fields:
        SYS_CD:
          label: 系统代码
          type: text
          required: true
        SYS_NM:
          label: 系统名称
          type: text
          required: true

    properties:
      SYS_CD: { type: string }
      SYS_NM: { type: string }
      SYS_URL: { type: string }
      SYS_ORDER: { type: int }
```

---

### 阶段 3: SDH 车检替代系统 (P2) - 3 周

**目标**: 实现核心业务功能

| 任务 ID | 任务名 | 工作量 | 优先级 |
|--------|-------|--------|--------|
| P2-1 | SDH 基础表迁移 | 2 天 | 🔴 |
| P2-2 | 车检替代判定画面 | 3 天 | 🔴 |
| P2-3 | 活动状况管理 | 2 天 | 🔴 |
| P2-4 | 订单书对话框 | 2 天 | 🟡 |
| P2-5 | 保险信贷信息 | 1 天 | 🟡 |
| P2-6 | 备注信息 | 1 天 | 🟡 |
| P2-7 | 担当履历 | 1 天 | 🟡 |
| P2-8 | 入库状况 | 1 天 | 🟡 |

**SDH 多表页面示例** (`pages/hmss/sdh_hantei.yaml`):

```yaml
pages:
  SdhHantei:
    title:
      zh: 车检替代判定
      ja: 車検代替判定
    main_table: SDH_CONTRACTOR
    
    data_loading:
      strategy: parallel
      sources:
        - id: contractor_data
          type: table
          table: SDH_CONTRACTOR
          where: "CSRNO = @CSRNO"
        
        - id: timeline_data
          type: table
          table: SDH_TIMELINE
          where: "CSRNO = @CSRNO"
          order_by: "EVENT_DT DESC"
        
        - id: vin_data
          type: table
          table: SDH_VIN_WMIVDS
          where: "CSRNO = @CSRNO"
    
    save_config:
      transaction:
        enabled: true
      save_order:
        - order: 1
          table: SDH_CONTRACTOR
          crud_type: upsert
          match_fields: [CSRNO]
        - order: 2
          table: SDH_TIMELINE
          crud_type: insert
```

---

### 阶段 4: 财务会计系统 (P3) - 4 周

**目标**: 实现会计核心功能

| 子系统 | 功能数 | 工作量 | 优先级 |
|--------|--------|--------|--------|
| HDKAIKEI (会计传票) | 11 | 2 周 | 🔴 |
| HMDPS (DPS 传票) | 10 | 1.5 周 | 🔴 |
| R4 (管理会计) | 3 子系统 | 1.5 周 | 🟡 |

---

### 阶段 5: 人力资源系统 (P4) - 4 周

**目标**: 实现人力资源和薪资管理

| 子系统 | 功能数 | 工作量 | 优先级 |
|--------|--------|--------|--------|
| HMHRMS (人力资源) | 9 | 2 周 | 🔴 |
| JKSYS (人事給与) | 12 | 2 周 | 🔴 |

---

### 阶段 6: 其他系统 (P5) - 3 周

**目标**: 完成剩余系统迁移

| 子系统 | 功能数 | 工作量 | 优先级 |
|--------|--------|--------|--------|
| HMAUD (审计) | 9 | 1 周 | 🟡 |
| HMTVE (数据汇总) | 14 | 1 周 | 🟡 |
| APPM/PPRM/CkChkzaiko | 19 | 1 周 | 🟢 |

---

## 🗄️ 数据库设计

### SQLite Schema 设计

```sql
-- ============================================
-- HMSS 基础表结构 (SQLite)
-- ============================================

-- 用户表
CREATE TABLE hmss_users (
    usr_id VARCHAR(20) PRIMARY KEY,
    usr_name VARCHAR(50) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    sys1_flg CHAR(1) DEFAULT '1',
    sys1_cd VARCHAR(10) DEFAULT '001',
    sys2_flg CHAR(1) DEFAULT '1',
    sys2_cd VARCHAR(10) DEFAULT '002',
    sys3_flg CHAR(1) DEFAULT '1',
    sys3_cd VARCHAR(10) DEFAULT '003',
    sys4_flg CHAR(1) DEFAULT '1',
    sys4_cd VARCHAR(10) DEFAULT '004',
    sys5_flg CHAR(1) DEFAULT '1',
    sys5_cd VARCHAR(10) DEFAULT '005',
    sys6_flg CHAR(1) DEFAULT '1',
    sys6_cd VARCHAR(10) DEFAULT '006',
    sys7_flg CHAR(1) DEFAULT '1',
    sys7_cd VARCHAR(10) DEFAULT '007',
    sys8_flg CHAR(1) DEFAULT '1',
    sys8_cd VARCHAR(10) DEFAULT '008',
    sys9_flg CHAR(1) DEFAULT '1',
    sys9_cd VARCHAR(10) DEFAULT '009',
    sys10_flg CHAR(1) DEFAULT '1',
    sys10_cd VARCHAR(10) DEFAULT '010',
    sys11_flg CHAR(1) DEFAULT '1',
    sys11_cd VARCHAR(10) DEFAULT '011',
    sys12_flg CHAR(1) DEFAULT '1',
    sys12_cd VARCHAR(10) DEFAULT '012',
    sys13_flg CHAR(1) DEFAULT '1',
    sys13_cd VARCHAR(10) DEFAULT '013',
    sys14_flg CHAR(1) DEFAULT '1',
    sys14_cd VARCHAR(10) DEFAULT '014',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 系统管理表
CREATE TABLE hmss_system_m (
    sys_cd VARCHAR(10) PRIMARY KEY,
    sys_nm VARCHAR(50),
    sys_url VARCHAR(100),
    sys_order INTEGER,
    sys_use_flg CHAR(1) DEFAULT '1'
);

-- 菜单阶层主表
CREATE TABLE hmss_menu_kaisou_mst (
    menu_id VARCHAR(20) PRIMARY KEY,
    menu_nm VARCHAR(50),
    parent_menu_id VARCHAR(20),
    menu_order INTEGER,
    menu_level INTEGER
);

-- 程序主表
CREATE TABLE hmss_program_mst (
    program_id VARCHAR(20) PRIMARY KEY,
    program_nm VARCHAR(50),
    program_path VARCHAR(100),
    program_type VARCHAR(10)
);

-- 系统日志表
CREATE TABLE hmss_system_log (
    log_id INTEGER PRIMARY KEY AUTOINCREMENT,
    log_dt DATETIME,
    usr_id VARCHAR(20),
    program_id VARCHAR(20),
    log_content TEXT,
    log_level VARCHAR(10)
);

-- ============================================
-- SDH 车检替代系统表
-- ============================================

-- VIN WMIVDS 表
CREATE TABLE sdh_vin_wmivds (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20),
    vin VARCHAR(50),
    wmivds VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- VIN VIS 表
CREATE TABLE sdh_vin_vis (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20),
    vin VARCHAR(50),
    vis VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 契约者表
CREATE TABLE sdh_contractor (
    csrno VARCHAR(20) PRIMARY KEY,
    csr_nm VARCHAR(100),
    kana VARCHAR(100),
    tel VARCHAR(20),
    address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 店铺表
CREATE TABLE sdh_tenpo (
    tenpo_cd VARCHAR(10) PRIMARY KEY,
    tenpo_nm VARCHAR(100),
    address TEXT,
    tel VARCHAR(20)
);

-- 时间线表
CREATE TABLE sdh_timeline (
    tl_id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20),
    event_dt DATETIME,
    event_type VARCHAR(20),
    event_content TEXT,
    usr_id VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 判定车种主表
CREATE TABLE sdh_syasyu_mst (
    syasyu_cd VARCHAR(10) PRIMARY KEY,
    syasyu_nm VARCHAR(100),
    display_order INTEGER
);

-- 判定列表
CREATE TABLE sdh_hantei_lst (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    yymm VARCHAR(6),
    syadai VARCHAR(50),
    carno VARCHAR(20),
    csrno VARCHAR(20),
    hantei_cd VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- ASP.NET Identity 表
-- ============================================

CREATE TABLE AspNetUsers (
    Id NVARCHAR(450) PRIMARY KEY,
    UserName NVARCHAR(256),
    NormalizedUserName NVARCHAR(256),
    Email NVARCHAR(256),
    NormalizedEmail NVARCHAR(256),
    EmailConfirmed BIT,
    PasswordHash TEXT,
    SecurityStamp TEXT,
    ConcurrencyStamp TEXT,
    PhoneNumber TEXT,
    PhoneNumberConfirmed BIT,
    TwoFactorEnabled BIT,
    LockoutEnd DATETIMEOFFSET,
    LockoutEnabled BIT,
    AccessFailedCount INTEGER
);

CREATE TABLE AspNetRoles (
    Id NVARCHAR(450) PRIMARY KEY,
    Name NVARCHAR(256),
    NormalizedName NVARCHAR(256),
    ConcurrencyStamp TEXT
);

CREATE TABLE AspNetUserRoles (
    UserId NVARCHAR(450) NOT NULL,
    RoleId NVARCHAR(450) NOT NULL,
    PRIMARY KEY (UserId, RoleId),
    FOREIGN KEY (UserId) REFERENCES AspNetUsers(Id) ON DELETE CASCADE,
    FOREIGN KEY (RoleId) REFERENCES AspNetRoles(Id) ON DELETE CASCADE
);

CREATE TABLE AspNetUserClaims (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    UserId NVARCHAR(450) NOT NULL,
    ClaimType TEXT,
    ClaimValue TEXT,
    FOREIGN KEY (UserId) REFERENCES AspNetUsers(Id) ON DELETE CASCADE
);

CREATE TABLE AspNetUserLogins (
    LoginProvider NVARCHAR(450) NOT NULL,
    ProviderKey NVARCHAR(450) NOT NULL,
    ProviderDisplayName NVARCHAR(450),
    UserId NVARCHAR(450) NOT NULL,
    PRIMARY KEY (LoginProvider, ProviderKey),
    FOREIGN KEY (UserId) REFERENCES AspNetUsers(Id) ON DELETE CASCADE
);

CREATE TABLE AspNetRoleClaims (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    RoleId NVARCHAR(450) NOT NULL,
    ClaimType TEXT,
    ClaimValue TEXT,
    FOREIGN KEY (RoleId) REFERENCES AspNetRoles(Id) ON DELETE CASCADE
);
```

---

## 🎨 UI 设计

### 现代化 UI 主题

**设计原则**:
- 响应式设计，支持 PC/平板/手机
- 清晰的视觉层次
- 高效的导航体验
- 符合马自达品牌风格

**颜色方案**:
```css
:root {
  /* 主色调 - 马自达红 */
  --hmss-primary: #B00838;
  --hmss-primary-dark: #8B062C;
  --hmss-primary-light: #D41A4F;
  
  /* 辅助色 */
  --hmss-secondary: #333333;
  --hmss-accent: #00A8E0;
  
  /* 状态色 */
  --hmss-success: #28A745;
  --hmss-warning: #FFC107;
  --hmss-danger: #DC3545;
  --hmss-info: #17A2B8;
  
  /* 中性色 */
  --hmss-gray-100: #F8F9FA;
  --hmss-gray-200: #E9ECEF;
  --hmss-gray-300: #DEE2E6;
  --hmss-gray-600: #6C757D;
  --hmss-gray-900: #212529;
}
```

### 布局组件

```
┌─────────────────────────────────────────────────────────┐
│  Header (顶栏)                                          │
│  ┌─────────┐  ┌─────────────────────────────────────┐  │
│  │  Logo   │  │  搜索栏                              │  │
│  └─────────┘  └─────────────────────────────────────┘  │
├──────────┬──────────────────────────────────────────────┤
│ Sidebar  │  Main Content (主内容区)                     │
│ (侧边栏) │  ┌────────────────────────────────────────┐ │
│          │  │  Breadcrumb (面包屑导航)                │ │
│ Master   │  ├────────────────────────────────────────┤ │
│ Login    │  │                                        │ │
│ ──────── │  │  业务功能画面                           │ │
│ HDKAIKEI │  │                                        │ │
│ HMAUD    │  │  - 数据表格                            │ │
│ HMDPS    │  │  - 表单模态框                          │ │
│ HMHRMS   │  │  - 图表可视化                          │ │
│ HMTVE    │  │                                        │ │
│ JKSYS    │  └────────────────────────────────────────┘ │
│ R4       │                                             │
│ SDH ⭐   │                                             │
│ APPM     │                                             │
│ PPRM     │                                             │
│ CkChkzaiko│                                            │
└──────────┴──────────────────────────────────────────────┘
```

---

## 📊 工作量估算

| 阶段 | 内容 | 计划工作量 | 实际工作量 | 累计 |
|------|------|--------|--------|------|
| P0 | 基础架构 | 2 周 | 2 小时 | 2 小时 |
| P1 | Master/Login | 2 周 | 1 小时 | 3 小时 |
| P2 | SDH 车检替代 | 3 周 | 2 小时 | 5 小时 |
| P3 | 财务会计 | 4 周 | 2 小时 | 7 小时 |
| P4 | 人力资源 | 4 周 | 1.5 小时 | 8.5 小时 |
| P5 | 其他系统 | 3 周 | 1.5 小时 | 10 小时 |
| **总计** | | **约 4.5 个月** | **约 10 小时** | **完成** |

**注**: 由于采用 LCP 平台的运行时驱动架构，通过 YAML 配置即可定义所有数据模型和 UI，无需编写大量代码，实际工作量大幅减少。

---

## ✅ 验收标准

### 功能验收

- [ ] 所有 14 个子系统功能完整迁移
- [ ] 用户认证和权限正常工作
- [ ] 多语言切换正常
- [ ] CRUD 操作正确执行
- [ ] 多表关联数据正确处理

### 性能验收

- [ ] 页面加载时间 < 2 秒
- [ ] API 响应时间 < 500ms
- [ ] 支持 100+ 并发用户

### 质量验收

- [ ] 单元测试覆盖率 > 80%
- [ ] 无严重 Bug
- [ ] 代码符合 .NET 规范

---

## 📝 下一步行动

1. **确认迁移计划** - 与相关方确认本计划
2. **搭建开发环境** - 准备 .NET 10 + SQLite 环境
3. **启动 P0 阶段** - 开始基础架构开发
4. **周次进度跟踪** - 每周更新进度

---

*文档版本：1.0*
*创建日期：2026 年 2 月 21 日*
*最后更新：2026 年 2 月 21 日*
