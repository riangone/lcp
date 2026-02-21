# HMSS 业务系统 - 完整业务功能与开发设计文档

## 目录

1. [系统概述](#1-系统概述)
2. [技术架构](#2-技术架构)
3. [路由与访问控制](#3-路由与访问控制)
4. [业务模块详解](#4-业务模块详解)
5. [数据库设计](#5-数据库设计)
6. [开发规范](#6-开发规范)
7. [部署与运维](#7-部署与运维)

---

## 1. 系统概述

### 1.1 系统简介

**HMSS** (Hiroshima Mazda Sales System) 是一个面向马自达汽车销售店的企业级综合业务管理系统，集成了财务会计、人力资源、车检管理、数据汇总等多个核心业务功能。

### 1.2 系统定位

- **行业**: 汽车销售与售后服务
- **用户**: 马自达销售店员工、本部管理人员
- **业务覆盖**: 财务会计、人力资源、车检替代促进、内部管理、数据汇总等

### 1.3 多系统架构

系统采用**单点多系统**架构，通过统一登录入口访问 20 个子系统：

| 系统编号 | 系统代码 | 系统名称 | 业务领域 |
|---------|---------|---------|---------|
| SYS1 | Master | 主数据管理 | 系统入口/权限管理 |
| SYS2 | Login | 登录认证 | 用户认证/权限验证 |
| SYS3 | Main | 主功能 | 用户信息维护 |
| SYS4 | HDKAIKEI | TMRH HD 伝票集計 | 会计传票汇总 |
| SYS5 | HMAUD | 内部統制システム | 内部审计/合规 |
| SYS6 | HMDPS | DPS 伝票処理 | DPS 传票处理 |
| SYS7 | HMHRMS | 人力资源系统 | 人事/給与/评价 |
| SYS8 | HMTVE | データ集計システム | 数据汇总/统计 |
| SYS9 | JKSYS | 人事給与系统 | 人事給与/评价 |
| SYS10 | R4 | R4 系统群 | 管理会计 |
| SYS11 | SDH | 車検代替判定 | 车检替代促进 |
| SYS12 | APPM | 広アプシステム | 广域应用 |
| SYS13 | PPRM | ペーパレス化支援 | 无纸化支持 |
| SYS14 | CkChkzaiko | 在庫チェック | 库存确认 |

---

## 2. 技术架构

### 2.1 技术栈

| 层级 | 技术 |
|------|------|
| **框架** | CakePHP 5.1.x |
| **语言** | PHP >= 8.1 |
| **数据库** | MySQL 8.0 / Oracle |
| **前端** | Milligram CSS + 原生 JavaScript |
| **Web 服务器** | Apache (mod_rewrite) |
| **模板引擎** | CakePHP View |
| **电子表格** | PHPSpreadsheet |

### 2.2 目录结构

```
hmss/
├── bin/                          # 命令行工具
│   ├── cake                      # CakePHP CLI
│   ├── cake.bat
│   └── cake.php
├── config/                       # 配置文件
│   ├── app.php                   # 主配置
│   ├── app_local.php             # 本地配置 (环境特定)
│   ├── routes.php                # 路由配置
│   ├── bootstrap.php             # 引导配置
│   └── schema/                   # 数据库 Schema
├── plugins/                      # 插件目录
├── resources/                    # 资源文件
├── src/                          # 源代码
│   ├── Console/                  # 控制台命令
│   ├── Controller/               # 控制器
│   │   ├── Master/
│   │   ├── Login/
│   │   ├── Main/
│   │   ├── HDKAIKEI/
│   │   ├── HMAUD/
│   │   ├── HMDPS/
│   │   ├── HMHRMS/
│   │   ├── HMTVE/
│   │   ├── JKSYS/
│   │   ├── R4/
│   │   │   ├── R4G/
│   │   │   ├── R4K/
│   │   │   └── KRSS/
│   │   ├── SDH/
│   │   ├── APPM/
│   │   ├── PPRM/
│   │   └── CkChkzaiko/
│   ├── Model/                    # 模型
│   │   ├── Entity/               # 实体类
│   │   ├── Table/                # 表类
│   │   ├── Behavior/             # 行为
│   │   └── Component/            # 组件
│   └── View/                     # 视图
├── templates/                    # 视图模板
│   ├── Master/
│   ├── Login/
│   ├── Main/
│   ├── HDKAIKEI/
│   ├── HMAUD/
│   ├── HMDPS/
│   ├── HMHRMS/
│   ├── HMTVE/
│   ├── JKSYS/
│   ├── R4/
│   │   ├── R4G/
│   │   ├── R4K/
│   │   └── KRSS/
│   ├── SDH/
│   ├── APPM/
│   ├── PPRM/
│   └── CkChkzaiko/
├── webroot/                      # Web 根目录
│   ├── css/                      # 样式文件
│   ├── js/                       # JavaScript 文件
│   ├── img/                      # 图片资源
│   └── files/                    # 上传文件
├── index.php                     # 入口文件
├── composer.json                 # Composer 配置
├── phpunit.xml.dist              # PHPUnit 配置
├── phpcs.xml                     # 代码规范配置
└── phpstan.neon                  # 静态分析配置
```

### 2.3 MVC 架构

```
┌─────────────────────────────────────────────────────────┐
│                      HTTP Request                        │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                    routes.php (路由)                      │
│         URL → Controller/Action 映射                      │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                   Controller (控制器)                     │
│   • 请求处理                                             │
│   • 业务逻辑调用                                         │
│   • 数据验证                                             │
│   • 视图渲染                                             │
└─────────────────────────────────────────────────────────┘
                    │                   │
                    ▼                   ▼
        ┌───────────────────┐  ┌───────────────────┐
        │  Model (模型)      │  │  Component (组件)  │
        │  • 数据库操作      │  │  • 通用功能        │
        │  • 业务规则        │  │  • 邮件发送        │
        │  • 数据验证        │  │  • 文件处理        │
        └───────────────────┘  └───────────────────┘
                    │
                    ▼
        ┌───────────────────┐
        │   Database        │
        │  • MySQL (用户)   │
        │  • Oracle (业务)  │
        └───────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────┐
│                   View (视图)                            │
│   • 模板渲染                                             │
│   • 数据展示                                             │
│   • 表单处理                                             │
└─────────────────────────────────────────────────────────┘
```

---

## 3. 路由与访问控制

### 3.1 路由配置

**配置文件**: `config/routes.php`

**路由规则**:

```php
// 基础域名路径
$domain = '/gdmz/cake';

// URL 解析
$url = str_replace($domain, '', $_SERVER['REQUEST_URI']);
$arr_url = explode('/', $url);

// 系统 ID 获取
$sys_id = $arr_url[1];

// R4 系统特殊处理
if ('R4G' == $arr_url[1] || 'R4K' == $arr_url[1] || 'KRSS' == $arr_url[1]) {
    $arr_url[1] = 'R4/' . $arr_url[1];
}

// 路由映射规则
// 2 段：/{system}         → {system}Controller::index()
// 3 段：/{system}/{ctrl}  → {ctrl}Controller::index()
// 4 段：/{system}/{ctrl}/{action} → {ctrl}Controller::{action}()
```

### 3.2 访问 URL 示例

| URL | 映射目标 |
|-----|---------|
| `/gdmz/cake/Master` | MasterController::index() |
| `/gdmz/cake/Login/Login` | LoginController::index() |
| `/gdmz/cake/HDKAIKEI/HDKAIKEI` | HDKAIKEIController::index() |
| `/gdmz/cake/R4/R4K` | R4KController::index() |
| `/gdmz/cake/JKSYS/FrmJinkenhiEnt` | FrmJinkenhiEntController::index() |
| `/gdmz/cake/SDH/SDH01` | SDH01Controller::index() |

### 3.3 权限控制

**权限表结构** (`M_LOGIN`):

```sql
CREATE TABLE M_LOGIN (
    USR_ID          VARCHAR(20) PRIMARY KEY,  -- 用户 ID
    USR_NAME        VARCHAR(50),               -- 用户姓名
    PASS            VARCHAR(255),              -- 密码
    email           VARCHAR(100),              -- 邮箱
    SYS1_FLG        CHAR(1),                   -- 系统 1 权限
    SYS1_CD         VARCHAR(10),               -- 系统 1 代码
    SYS2_FLG        CHAR(1),
    SYS2_CD         VARCHAR(10),
    -- ... SYS1 ~ SYS20
    SYS20_FLG       CHAR(1),
    SYS20_CD        VARCHAR(10)
);
```

**权限验证流程**:

```
1. 用户登录 → 验证 M_LOGIN 表
2. 获取系统权限标志 (SYSx_FLG)
3. 访问系统时检查权限
4. 无权限 → 重定向到登录页
```

---

## 4. 业务模块详解

### 4.1 Master (主数据管理)

#### 4.1.1 模块概述

系统入口模块，提供统一的用户界面和系统切换功能。

#### 4.1.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| MST-001 | 系统入口 | 显示所有可用系统的标签页 |
| MST-002 | 权限管理 | 用户系统权限验证 (SYS1_FLG ~ SYS20_FLG) |
| MST-003 | 系统映射 | 系统代码映射 (SYS1_CD ~ SYS20_CD) |
| MST-004 | 登录检查 | 会话状态验证 |

#### 4.1.3 Controller

| Controller | 方法 | 说明 |
|-----------|------|------|
| MasterController | index() | 主页面显示 |
| MasterController | render_login() | 登录渲染页 |

#### 4.1.4 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `user` | 用户主表 | USR_ID, USR_NAME, PASS, email |
| `M_LOGIN` | 登录权限表 | USR_ID, SYS1_FLG~SYS20_FLG |
| `system_m` | 系统管理表 | SYS_CD, SYS_NM, SYS_URL |

---

### 4.2 Login (登录认证)

#### 4.2.1 模块概述

统一认证模块，处理用户登录、密码找回、会话管理。

#### 4.2.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| LOG-001 | 用户登录 | ID/密码认证 |
| LOG-002 | 密码找回 | 邮件发送 ID/密码 |
| LOG-003 | 会话管理 | Session 创建/销毁 |
| LOG-004 | 多系统登录 | 支持 20 个子系统 |
| LOG-005 | 权限验证 | 菜单加载权限检查 |

#### 4.2.3 Controller

| Controller | 方法 | 说明 |
|-----------|------|------|
| LoginController | index() | 登录页面 |
| LoginController | login() | 登录处理 |
| LoginController | logout() | 登出处理 |
| LoginController | password_reset() | 密码重置 |

#### 4.2.4 Model

| Model | 方法 | 说明 |
|-------|------|------|
| Login.php | m_select_user() | 用户信息查询 |
| Login.php | m_update_login_time() | 登录时间更新 |

#### 4.2.5 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `user` | 用户表 | USR_ID, USR_NAME, PASS, email, SYSx_FLG |

---

### 4.3 HDKAIKEI (TMRH HD 伝票集計)

#### 4.3.1 模块概述

会计传票汇总系统，处理会计分录录入、传票管理、OBC 系统数据交换。

#### 4.3.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| HDK-001 | 主菜单 | 功能导航 |
| HDK-002 | 仕訳入力 | 会计分录录入 |
| HDK-003 | 支払入力 | 支付录入 |
| HDK-004 | 伝票検索 | 传票搜索 |
| HDK-005 | 科目マスタ | 会计科目管理 |
| HDK-006 | OBC 导入 | OBC 数据导入 |
| HDK-007 | OBC 导出 | OBC 数据导出 |
| HDK-008 | 社員マスタ | 社员管理 |
| HDK-009 | 銀行検索 | 银行搜索 |
| HDK-010 | 取引先検索 | 客户搜索 |
| HDK-011 | パターン検索 | 模式搜索 |

#### 4.3.3 Controller (20 个)

| Controller | 功能 |
|-----------|------|
| HDKAIKEIController | 主控制器 |
| FrmHDKAIKEIMainMenuController | 主菜单 |
| HDKShiwakeInputController | 仕訳入力 |
| HDKShiharaiInputController | 支払入力 |
| HDKDenpyoSearchController | 伝票検索 |
| HDKKamokuMstController | 科目マスタ |
| HDKOBCDataExpImpController | OBC 数据导入导出 |
| HDKOut4OBCController | OBC 输出 |
| HDKSyainMstEditController | 社員マスタ編集 |
| HDKBankSearchController | 銀行検索 |
| HDKTorihikisakiSearchController | 取引先検索 |
| HDKPatternSearchController | パターン検索 |

#### 4.3.4 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `HDK_MST_KAMOKU` | HD 科目マスタ | KAMOKU_CD, KAMOKU_NM |
| `HDK_MST_TORIHIKISAKI` | HD 取引先マスタ | TORIHIKI_CD, TORIHIKI_NM |
| `HDK_MST_BUMON` | HD 部門マスタ | BUMON_CD, BUMON_NM |
| `HDK_MST_SYAIN` | HD 社員マスタ | SYAIN_NO, SYAIN_NM |
| `HDK_DENPYO` | HD 伝票 | DENPYO_NO, DENPYO_DT |
| `HDK_SHIWAKE` | HD 仕訳 | SHIWAKE_NO, KAMOKU_CD, KINGAKU |
| `HDK_SHIHARAI` | HD 支払 | SHIHARAI_NO, KINGAKU |

---

### 4.4 HMAUD (内部統制システム)

#### 4.4.1 模块概述

内部审计系统，管理审计计划、审计实绩、报告、议事录。

#### 4.4.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| HMA-001 | 監査実績入力 | 审计实绩录入 |
| HMA-002 | 監査員メンテ | 审计员管理 |
| HMA-003 | SKD 登録 | 审计计划登记 |
| HMA-004 | レポート入力 | 报告录入 |
| HMA-005 | 巡監メンテ | 巡监管理 |
| HMA-006 | 拠点メンテ | 据点管理 |
| HMA-007 | 議事録管理 | 议事录上传下载 |
| HMA-008 | 監査項目定義 | 审计项目定义 |
| HMA-009 | 監査除外日管理 | 审计除外日管理 |

#### 4.4.3 Controller (22 个)

| Controller | 功能 |
|-----------|------|
| HMAUDController | 主控制器 |
| FrmHMAUDMainMenuController | 主菜单 |
| HMAUDKansaJissekiInputController | 監査実績入力 |
| HMAUDKansaJinMenteController | 監査員メンテ |
| HMAUDSKDTorokuController | SKD 登録 |
| HMAUDReportInputController | レポート入力 |
| HMAUDKuruMenteController | 巡監メンテ |
| HMAUDKyotenMenteController | 拠点メンテ |
| HMAUDGijirokuUploadController | 議事録アップロード |

#### 4.4.4 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `HMAUD_KANSA_JIN` | 監査員マスタ | KANSA_JIN_CD, KANSA_JIN_NM |
| `HMAUD_KANSA_JISSEKI` | 監査実績 | JISSEKI_ID, KANSA_DT, RESULT |
| `HMAUD_SKD` | スケジュール | SKD_ID, START_DT, END_DT |
| `HMAUD_REPORT` | レポート | REPORT_ID, REPORT_CONTENT |
| `HMAUD_KYOTEN` | 拠点マスタ | KYOTEN_CD, KYOTEN_NM |
| `HMAUD_KURU` | 巡監マスタ | KURU_CD, KURU_NM |
| `HMAUD_GIJIROKU` | 議事録 | GIJIROKU_ID, FILE_PATH |

---

### 4.5 HMDPS (DPS 伝票処理)

#### 4.5.1 模块概述

DPS 传票处理系统，处理仕訳伝票、支払伝票的录入和搜索。

#### 4.5.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| HDP-001 | 仕訳伝票入力 | 仕訳传票录入 |
| HDP-002 | 支払伝票入力 | 支払传票录入 |
| HDP-003 | 伝票検索 | 传票搜索 |
| HDP-004 | パターン検索 | 模式搜索 |
| HDP-005 | バーコード出力 | 条形码输出 |
| HDP-006 | CSV 再出力 | CSV 重新输出 |
| HDP-007 | 取引先検索 | 客户搜索 |
| HDP-008 | 科目検索 | 科目搜索 |
| HDP-009 | 社員検索 | 社员搜索 |
| HDP-010 | 部署検索 | 部署搜索 |

#### 4.5.3 Controller (12 个)

| Controller | 功能 |
|-----------|------|
| HMDPSController | 主控制器 |
| FrmHMDPSMainMenuController | 主菜单 |
| HMDPS100DenpyoSearchController | 伝票検索 |
| HMDPS101ShiwakeDenpyoInputController | 仕訳伝票入力 |
| HMDPS102ShiharaiDenpyoInputController | 支払伝票入力 |
| HMDPS700TorihikisakiSearchController | 取引先検索 |
| HMDPS701KamokuSearchController | 科目検索 |

#### 4.5.4 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `HMDPS_DENPYO` | DPS 伝票 | DENPYO_NO, DENPYO_DT |
| `HMDPS_SHIWAKE` | DPS 仕訳 | SHIWAKE_NO, KAMOKU_CD |
| `HMDPS_SHIHARAI` | DPS 支払 | SHIHARAI_NO, KINGAKU |
| `HMDPS_PATTERN` | パターンマスタ | PATTERN_CD, PATTERN_NM |

---

### 4.6 HMHRMS (人力资源系统)

#### 4.6.1 模块概述

人力资源管理系统，管理员工个人信息、家族情况、学历、职历、资格等。

#### 4.6.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| HHR-001 | 社員個人情報 | 员工个人信息管理 |
| HHR-002 | 家族状況管理 | 家族情况管理 |
| HHR-003 | 学歴管理 | 学历管理 |
| HHR-004 | 社外職歴管理 | 外部职历管理 |
| HHR-005 | 表彰歴管理 | 表彰历管理 |
| HHR-006 | 資格免許管理 | 资格执照管理 |
| HHR-007 | 通勤方法管理 | 通勤方法管理 |
| HHR-008 | カスタムフィールド | 自定义字段管理 |
| HHR-009 | 履歴管理 | 履历管理 |

#### 4.6.3 Controller

| Controller | 功能 |
|-----------|------|
| HMHRMSController | 主控制器 |

#### 4.6.4 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `employee` | 社員マスタ | EMP_ID, EMP_NM, BIRTH_DT |
| `employee_sub_table_*` | 社員サブテーブル | SUB_ID, EMP_ID, DATA |
| `custom_fields` | カスタムフィールド | FIELD_ID, FIELD_NM |
| `custom_values_*` | カスタム値 | VALUE_ID, FIELD_ID, VALUE |
| `custom_values_history` | カスタム値履歴 | HISTORY_ID, CHANGE_DT |

---

### 4.7 HMTVE (データ集計システム)

#### 4.7.1 模块概述

数据汇总系统，处理销售数据汇总、展示会管理、订单管理、目标实绩管理。

#### 4.7.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| HTV-001 | 入力データ K | 输入数据 K |
| HTV-002 | 入力データ S | 输入数据 S |
| HTV-003 | 集計 S | 汇总 S |
| HTV-004 | 集計 K 店舗 | 汇总 K 店铺 |
| HTV-005 | 集計 K 本部 | 汇总 K 本部 |
| HTV-006 | 展示会管理 | 展示会搜索/登记 |
| HTV-007 | 出欠管理 | 出席管理 |
| HTV-008 | 宣伝注文 | 宣传订单管理 |
| HTV-009 | カタログ注文 | 目录订单管理 |
| HTV-010 | プレゼント注文 | 礼品订单管理 |
| HTV-011 | 報告場所数 | 报告场所数管理 |
| HTV-012 | 目標実績 | 目标实绩管理 |
| HTV-013 | 紹介確認 | 介绍确认管理 |
| HTV-014 | マスタ管理 | 主数据管理 |

#### 4.7.3 Controller (42 个)

| Controller | 功能 |
|-----------|------|
| HMTVEController | 主控制器 |
| FrmHMTVEMainMenuController | 主菜单 |
| HMTVE030InputDataKController | 入力データ K |
| HMTVE040InputDataSController | 入力データ S |
| HMTVE050TotalSController | 集計 S |
| HMTVE060TotalKShopController | 集計 K 店舗 |
| HMTVE070TotalKHonbuController | 集計 K 本部 |
| HMTVE080ExhibitionSearchController | 展示会検索 |
| HMTVE090ExhibitionEntryController | 展示会登録 |
| HMTVE100AttendanceControlController | 出欠管理 |
| HMTVE110~150 | 宣伝注文管理 |
| HMTVE160~191 | カタログ注文管理 |
| HMTVE200~230 | プレゼント注文管理 |
| HMTVE240~250 | 報告場所数管理 |
| HMTVE260~270 | 目標実績管理 |
| HMTVE280~290 | 紹介確認管理 |
| HMTVE300~350 | マスタ管理 |

#### 4.7.4 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `HMTVE_INPUT_DATA_K` | 入力データ K | DATA_ID, INPUT_DT |
| `HMTVE_INPUT_DATA_S` | 入力データ S | DATA_ID, INPUT_DT |
| `HMTVE_TOTAL_S` | 集計 S | TOTAL_ID, TOTAL_VALUE |
| `HMTVE_TOTAL_K` | 集計 K | TOTAL_ID, SHOP_CD |
| `HMTVE_EXHIBITION` | 展示会 | EXH_ID, EXH_NM, START_DT |
| `HMTVE_ATTENDANCE` | 出欠 | ATT_ID, EMP_ID, STATUS |
| `HMTVE_PUBLICITY_ORDER` | 宣伝注文 | PUB_ORDER_ID, ORDER_DT |
| `HMTVE_CATALOG_ORDER` | カタログ注文 | CAT_ORDER_ID, ORDER_DT |
| `HMTVE_PRESENT_ORDER` | プレゼント注文 | PRE_ORDER_ID, ORDER_DT |
| `HMTVE_SYAIN_MST` | 社員マスタ | SYAIN_NO, SYAIN_NM |
| `HMTVE_TSYASYU_MST` | 車種マスタ | SYASYU_CD, SYASYU_NM |

---

### 4.8 JKSYS (人事給与系统)

#### 4.8.1 模块概述

人事給与系统，处理人事管理、給与计算、评价管理、出差精算。

#### 4.8.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| JKS-001 | 人件費入力 | 人事费录入 |
| JKS-002 | 人件費明細 | 人事费明细 |
| JKS-003 | 人件費 CSV | 人事费 CSV 输出 |
| JKS-004 | 賞与計算 | 奖金计算 |
| JKS-005 | 評価管理 | 评价管理 |
| JKS-006 | 出張精算 | 出差精算 |
| JKS-007 | 兵庫専用機能 | 兵库支店专用 |
| JKS-008 | 拠点振替 | 据点振替 |
| JKS-009 | 事業所税 | 事业所税计算 |
| JKS-010 | 係数マスタ | 系数管理 |
| JKS-011 | Excel 取込 | Excel 数据导入 |
| JKS-012 | パス管理 | 路径管理 |

#### 4.8.3 Controller (43 个)

| Controller | 功能 |
|-----------|------|
| JKSYSController | 主控制器 |
| FrmJKSYSMainMenuController | 主菜单 |
| FrmJinkenhiEntController | 人件費入力 |
| FrmJinkenhiMeisaiController | 人件費明細 |
| FrmJinkenhiCsvController | 人件費 CSV |
| FrmSyoreikinSyoriMenteController | 賞与処理 |
| FrmHyokaKikanEntController | 評価期間入力 |
| FrmHyokaNewDataUpdController | 評価新規データ |
| FrmSyukkouSeikyuController | 出張精算 |
| FrmKyotenFurikaeController | 拠点振替 |
| FrmJigyousyoZeiController | 事業所税 |
| FrmExcelTorikomiController | Excel 取込 |

#### 4.8.4 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `JKJINKENHI` | 人件費 | JINKEN_ID, EMP_ID, KINGAKU |
| `JKJINKENHITABUSYOFRI` | 人件費部署振替 | FRI_ID, FROM_BUMON |
| `JKJINKENHIKEKKIN` | 人件費締金 | KEKKIN_ID, KEKKIN_DT |
| `JKJINKENHI_EXCLUDE` | 人件費除外 | EXCLUDE_ID, EMP_ID |
| `JKSYAIN` | 社員マスタ | SYAIN_NO, SYAIN_NM |
| `JKBUMON` | 部署マスタ | BUMON_CD, BUMON_NM |
| `JKKEISAN` | 計算マスタ | KEISAN_CD, KEISAN_VALUE |
| `JKSHIKYU` | 支給 | SHIKYU_ID, SHIKYU_NM |
| `JKSONOTA` | その他 | SONOTA_ID, SONOTA_NM |
| `JKKOYOURIREKI` | 雇用履歴 | KOYOU_ID, EMP_ID |
| `JKIDOURIREKI` | 異動履歴 | IDOU_ID, EMP_ID |
| `JKHYOUKARIREKI` | 評価履歴 | HYOUKA_ID, EMP_ID |
| `JKSYOREIKINMST` | 賞与金マスタ | SYOREI_CD, SYOREI_NM |
| `JKKEISUMST` | 係数マスタ | KEISU_CD, KEISU_VALUE |

---

### 4.9 R4 系统群

#### 4.9.1 R4G (R4G 系统)

**功能概述**: 赤伝管理、請求処理、FD データ管理

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| R4G-001 | 赤伝管理 | 赤伝管理 |
| R4G-002 | 請求処理 | 請求处理 |
| R4G-003 | FD データ | FD 数据管理 |
| R4G-004 | お買上マスタ | 购买主数据 |

**数据库表**:
- `R4G_AKADEN` - 赤伝
- `R4G_BILL` - 請求
- `R4G_FD_DATA` - FD データ
- `R4G_OKAIAGE_MST` - お買上マスタ

---

#### 4.9.2 R4K (管理会計システム)

**功能概述**: 管理会计系统，处理预算、原価、売上、経理、振替等。

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| R4K-001 | 予算管理 | 预算管理 |
| R4K-002 | 原価管理 | 原価管理 |
| R4K-003 | 売上管理 | 売上管理 |
| R4K-004 | 経理処理 | 経理处理 |
| R4K-005 | 振替処理 | 振替处理 |
| R4K-006 | 請求発行 | 請求发行 |
| R4K-007 | ランキング | 排名管理 |
| R4K-008 | 帳票出力 | 报表输出 |

**数据库表**:
- `R4K_BUMON_MST` - 部署マスタ
- `R4K_SYAIN_MST` - 社員マスタ
- `R4K_GYOUSYA_MST` - 業者マスタ
- `R4K_YOSAN_MST` - 予算マスタ
- `R4K_GENKA` - 原価
- `R4K_URIAGE` - 売上
- `R4K_KAIKEI` - 経理
- `R4K_FURIKAE` - 振替
- `R4K_SEIKYU` - 請求
- `R4K_RANKING` - ランキング

---

#### 4.9.3 KRSS (KRSS 系统)

**功能概述**: 经营分析系统，处理预算、模拟、损益、费用、实绩分析。

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| KRS-001 | 予算管理 | 预算管理 |
| KRS-002 | シミュレーション | 模拟分析 |
| KRS-003 | 損益明細 | 损益明细 |
| KRS-004 | 費用明細 | 费用明细 |
| KRS-005 | 本部実績 | 本部实绩 |
| KRS-006 | 管理チェック | 管理检查 |
| KRS-007 | 経営利益ツリー | 经营利益树 |
| KRS-008 | 売上実績 | 売上实绩 |

**数据库表**:
- `KRSS_YOSAN` - 予算
- `KRSS_SIMULATION` - シミュレーション
- `KRSS_SONEKI` - 損益
- `KRSS_HIYOU` - 費用
- `KRSS_HONBU_JISSEKI` - 本部実績
- `KRSS_URIAKE_JISSEKI` - 売上実績

---

### 4.10 SDH (車検代替判定系统)

#### 4.10.1 模块概述

车检替代判定系统，追踪客户车检状态，推动车检入厂或替代销售。

#### 4.10.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| SDH-001 | 車検代替判定 | 车检替代判定主画面 |
| SDH-002 | 活動状況管理 | 活动状况管理 |
| SDH-003 | 注文書ダイアログ | 订单书对话框 |
| SDH-004 | 保険信貸情報 | 保险信贷信息 |
| SDH-005 | 備考情報 | 备注信息 |
| SDH-006 | 担当履歴 | 担当履历 |
| SDH-007 | 入庫状況 | 入库状况 |

#### 4.10.3 Controller (7 个)

| Controller | 功能 |
|-----------|------|
| SDH01Controller | 車検代替判定主画面 |
| SDH02Controller | 活動状況管理 |
| SDH03Controller | 注文書ダイアログ |
| SDH04Controller | 保険信貸情報 |
| SDH05Controller | 備考情報 |
| SDH06Controller | 担当履歴 |
| SDH07Controller | 入庫状況 |

#### 4.10.4 活动状况分类

| 分类代码 | 分类名 | 说明 |
|---------|-------|------|
| rel="1" | 代替促進 | 替代促进 (16 车型) |
| rel="2" | 入庫促進 | 入库促进 |
| rel="3" | 入促・代促 | 入库促进·替代促进 |
| rel="4" | 代替確定 | 替代确定 (16 车型) |
| rel="5" | 代替予定 | 替代预定 |
| rel="6" | 入庫確定 | 入库确定 |
| rel="7" | 入庫予定 | 入库预定 |
| rel="8" | 他社代替 | 他社替代 |
| rel="9" | 他社入庫 | 他社入库 |
| rel="10" | 転売 | 转卖 |
| rel="11" | 県外転出 | 县外转出 |
| rel="12" | 入庫 X | 入库 X |
| rel="13" | 車両なし | 无车辆 |
| rel="14" | 連絡 X | 联络 X |
| rel="15" | 所在不明 | 所在不明 |
| rel="16" | 業者契約 | 业者契约 |
| rel="17" | リース | 租赁 |
| rel="18" | リース指定工場 | 租赁指定工厂 |
| rel="19" | 納入依頼 | 纳入依赖 |

#### 4.10.5 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `SDH_VIN_WMIVDS` | VIN WMIVDS | VIN, WMIVDS |
| `SDH_VIN_VIS` | VIN VIS | VIN, VIS |
| `SDH_CONTRACTOR` | 契約者 | CSRNO, CSR_NM |
| `SDH_TENPO` | 店舗 | TENPO_CD, TENPO_NM |
| `SDH_TIMELINE` | タイムライン | TL_ID, EVENT_DT |
| `HANTEISYASYUMST` | 判定車種マスタ | SYASYU_CD, SYASYU_NM |
| `HANTEILST` | 判定リスト | YYMM, SYADAI, CARNO |

#### 4.10.6 客户分类数据

| 分类 | 代码 | 说明 |
|------|------|------|
| **CSRRANK** | A~H | 客户来源 (自己开拓、引继、业贩等) |
| **XH10CAID** | 0~9 | 车辆区分 (自新直、自新业、他社新等) |
| **XG11KOTEIID** | F~M | 固定化分类 (固定特、未入库等) |
| **DM_FKA_KB** | 0/1 | DM 可否区分 |
| **XHKTGKBN** | 1~11 | 引续区分 (本人管理、未管理等) |

---

### 4.11 APPM (広アプシステム)

#### 4.11.1 模块概述

广域应用系统，管理账户、消息、通知条件。

#### 4.11.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| APP-001 | アカウント発行 | 账户发行 |
| APP-002 | アカウント一覧 | 账户一览 |
| APP-003 | メッセージ登録 | 消息登记 |
| APP-004 | メッセージ一覧 | 消息一览 |
| APP-005 | お知らせ条件 | 通知条件 |
| APP-006 | UX 条件 | UX 条件 |
| APP-007 | プレビュー | 预览 |

#### 4.11.3 Controller (12 个)

| Controller | 功能 |
|-----------|------|
| APPMController | 主控制器 |
| FrmAPPMMainMenuController | 主菜单 |
| FrmAkauntoHakkoController | アカウント発行 |
| FrmAkauntoIchiranSanshoController | アカウント一覧 |
| FrmMessejiTorokuController | メッセージ登録 |
| FrmMessejiIchiranSanshoController | メッセージ一覧 |

#### 4.11.4 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `APPM_ACCOUNT` | アカウント | ACCOUNT_ID, ACCOUNT_NM |
| `APPM_MESSEJI` | メッセージ | MESSEJI_ID, MESSEJI_CONTENT |
| `APPM_OSHIRASE_JOKEN` | お知らせ条件 | JOKEN_ID, JOKEN_CONTENT |
| `APPM_UX_JOKEN` | UX 条件 | UX_ID, UX_CONTENT |

---

### 4.12 PPRM (ペーパレス化支援系统)

#### 4.12.1 模块概述

无纸化支持系统，管理审批状态、DC 图像、登录、菜单权限。

#### 4.12.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| PPR-001 | 承認状態検索 | 审批状态搜索 |
| PPR-002 | 承認処理 | 审批处理 |
| PPR-003 | DC 画像関連 | DC 图像关联 |
| PPR-004 | DC 検索 | DC 搜索 |
| PPR-005 | DC 金額種別 | DC 金额种别 |
| PPR-006 | DC 出力 | DC 输出 |
| PPR-007 | 登录一覧 | 登录一览 |
| PPR-008 | 登录登録 | 登录登记 |
| PPR-009 | メニュー権限 | 菜单权限 |
| PPR-010 | 権限制御 | 权限控制 |

#### 4.12.3 Controller (20 个)

| Controller | 功能 |
|-----------|------|
| PPRMController | 主控制器 |
| FrmPPRMMainMenuController | 主菜单 |
| PPRM100ApproveStateSearchController | 承認状態検索 |
| PPRM101ApproveActController | 承認処理 |
| PPRM201DCImageRelationController | DC 画像関連 |
| PPRM202DCSearchController | DC 検索 |
| PPRM800LoginListController | 登录一覧 |
| PPRM801LoginEntryController | 登录登録 |
| PPRM802MenuAuthMstMntController | メニュー権限 |

#### 4.12.4 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `PPRM_APPROVE` | 承認 | APPROVE_ID, STATUS |
| `PPRM_DC_IMAGE` | DC 画像 | IMAGE_ID, IMAGE_PATH |
| `PPRM_DC_MONEY` | DC 金額 | MONEY_ID, MONEY_VALUE |
| `PPRM_LOGIN` | 登录 | LOGIN_ID, LOGIN_NM |
| `PPRM_MENU_AUTH_MST` | メニュー権限マスタ | AUTH_ID, AUTH_NM |
| `PPRM_MENU_NAME_MST` | メニュー名マスタ | MENU_ID, MENU_NM |
| `PPRM_AUTHORITY_CTL` | 権限制御 | CTL_ID, CTL_VALUE |

---

### 4.13 CkChkzaiko (在庫チェック系统)

#### 4.13.1 模块概述

库存确认系统，管理库存搜索和报表输出。

#### 4.13.2 功能清单

| 功能 ID | 功能名 | 说明 |
|--------|-------|------|
| CKZ-001 | 在庫検索 | 库存搜索 |
| CKZ-002 | 帳票出力 | 报表输出 |

#### 4.13.3 Controller

| Controller | 功能 |
|-----------|------|
| CkChkzaikoController | 主控制器 |

#### 4.13.4 数据库表

| 表名 | 说明 | 主要字段 |
|------|------|---------|
| `CK_CHKZAIKO` | 在庫チェック | CHECK_ID, CHECK_DT |
| `ZAIKO` | 在庫 | ZAIKO_ID, ZAIKO_QTY |

---

## 5. 数据库设计

### 5.1 数据库架构

系统采用**双数据库架构**:

```
┌─────────────────────────────────────────────────────────┐
│                     MySQL Database                       │
│  • 用户管理 (user 表)                                    │
│  • 登录权限 (M_LOGIN)                                    │
│  • 系统配置                                              │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                    Oracle Database                       │
│  • 业务数据                                              │
│  • 各子系统专用表                                        │
│  • 主数据                                                │
└─────────────────────────────────────────────────────────┘
```

### 5.2 共通数据库表

#### 5.2.1 用户管理表

```sql
-- 用户表 (MySQL)
CREATE TABLE user (
    USR_ID          VARCHAR(20) PRIMARY KEY,
    USR_NAME        VARCHAR(50) NOT NULL,
    PASS            VARCHAR(255) NOT NULL,
    email           VARCHAR(100),
    SYS1_FLG        CHAR(1),
    SYS1_CD         VARCHAR(10),
    SYS2_FLG        CHAR(1),
    SYS2_CD         VARCHAR(10),
    -- ... SYS3 ~ SYS19
    SYS20_FLG       CHAR(1),
    SYS20_CD        VARCHAR(10),
    CREATED_DT      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UPDATED_DT      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 登录权限表 (Oracle)
CREATE TABLE M_LOGIN (
    USR_ID          VARCHAR2(20) PRIMARY KEY,
    USR_NAME        VARCHAR2(50),
    PASS            VARCHAR2(255),
    email           VARCHAR2(100),
    SYS1_FLG        CHAR(1),
    SYS1_CD         VARCHAR2(10),
    -- ... SYS2 ~ SYS20
    LAST_LOGIN_DT   TIMESTAMP
);
```

#### 5.2.2 系统配置表

```sql
-- 系统管理表
CREATE TABLE system_m (
    SYS_CD          VARCHAR(10) PRIMARY KEY,
    SYS_NM          VARCHAR(50),
    SYS_URL         VARCHAR(100),
    SYS_ORDER       NUMBER,
    SYS_USE_FLG     CHAR(1)
);

-- 菜单阶层主表
CREATE TABLE HMENUKAISOUMST (
    MENU_ID         VARCHAR(20) PRIMARY KEY,
    MENU_NM         VARCHAR(50),
    PARENT_MENU_ID  VARCHAR(20),
    MENU_ORDER      NUMBER,
    MENU_LEVEL      NUMBER
);

-- 程序主表
CREATE TABLE HPROGRAMMST (
    PROGRAM_ID      VARCHAR(20) PRIMARY KEY,
    PROGRAM_NM      VARCHAR(50),
    PROGRAM_PATH    VARCHAR(100),
    PROGRAM_TYPE    VARCHAR(10)
);

-- 系统日志表
CREATE TABLE HSYSTEMLOGDATA (
    LOG_ID          NUMBER PRIMARY KEY,
    LOG_DT          TIMESTAMP,
    USR_ID          VARCHAR(20),
    PROGRAM_ID      VARCHAR(20),
    LOG_CONTENT     CLOB,
    LOG_LEVEL       VARCHAR(10)
);
```

#### 5.2.3 主数据表

```sql
-- 科目主表
CREATE TABLE M_KAMOKU (
    KAMOKU_CD       VARCHAR(10) PRIMARY KEY,
    KAMOKU_NM       VARCHAR(50),
    KAMOKU_KANA     VARCHAR(50),
    KAMOKU_LEVEL    NUMBER,
    PARENT_KAMOKU_CD VARCHAR(10)
);

-- 代码主表
CREATE TABLE M_CODE (
    CODE_CD         VARCHAR(10) PRIMARY KEY,
    CODE_NM         VARCHAR(50),
    CODE_VALUE      VARCHAR(100),
    CODE_ORDER      NUMBER
);
```

### 5.3 各系统专用表

详见各模块章节的数据库表说明。

---

## 6. 开发规范

### 6.1 编码规范

**遵循标准**: CakePHP 编码规范 + PSR-12

**配置文件**: `phpcs.xml`

```xml
<ruleset name="App">
    <rule ref="CakePHP"/>
    <file>src/</file>
    <file>tests/</file>
</ruleset>
```

**EditorConfig**:

```ini
[*]
indent_style = space
indent_size = 4
end_of_line = lf
insert_final_newline = true
trim_trailing_whitespace = true
```

### 6.2 命名约定

| 类型 | 命名规则 | 示例 |
|------|---------|------|
| **Controller** | PascalCase + `Controller` | `HDKAIKEIController` |
| **Model** | PascalCase | `SDH01`, `Login` |
| **View** | 小写 + 下划线 | `index.php`, `sdh01_sel_busyo.php` |
| **方法** | camelCase | `m_select_user()`, `getHanteiList()` |
| **变量** | snake_case / camelCase | `$tenpo_cd`, `$arrInputData` |
| **常量** | UPPER_SNAKE_CASE | `DEBUG`, `CACHE_TIME` |
| **数据库表** | UPPER_SNAKE_CASE | `M_LOGIN`, `HDK_DENPYO` |
| **数据库字段** | UPPER_SNAKE_CASE | `USR_ID`, `KAMOKU_CD` |

### 6.3 Controller 规范

```php
<?php
namespace App\Controller\SDH;

use App\Controller\AppController;
use App\Model\SDH\SDH01;

/**
 * 車検代替判定画面
 * SDH01Controller.
 */
class SDH01Controller extends AppController
{
    // 变量声明
    public $autoLayout = true;
    private $tenpo_cd = '';
    private $m_SDH01;

    /**
     * デフォルトで最初に実行される機能.
     */
    public function index()
    {
        // 处理逻辑
    }

    /**
     * 店铺代码取得.
     *
     * @return void
     */
    public function get_tenpo()
    {
        // 处理逻辑
    }
}
```

### 6.4 Model 规范

```php
<?php
namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH01 extends ClsComDb
{
    /**
     * 店铺代码取得 SQL.
     *
     * @param string $ip IP アドレス
     * @param string $userid ユーザー ID
     *
     * @return string SQL 文
     */
    public function m_select_kyotn_cd_sql($ip, $userid)
    {
        $str_sql = '';
        $str_sql .= 'SELECT * ';
        $str_sql .= 'FROM HKTNYAKUIN ';
        $str_sql .= 'WHERE SYAIN_NO = \'' . $userid . '\'';

        return $str_sql;
    }
}
```

### 6.5 测试规范

**测试框架**: PHPUnit 10.x/11.x

**测试目录**: `tests/TestCase/`

**运行测试**:
```bash
composer test
# 或
bin/cake test
```

### 6.6 代码检查

```bash
# 代码规范检查
composer cs-check

# 自动修复
composer cs-fix

# 静态分析 (PHPStan)
composer stan

# 运行所有检查
composer check
```

---

## 7. 部署与运维

### 7.1 环境要求

| 项目 | 要求 |
|------|------|
| **PHP** | >= 8.1 |
| **Web 服务器** | Apache 2.4+ (mod_rewrite) |
| **数据库** | MySQL 8.0+ / Oracle 19c+ |
| **Composer** | 2.x |
| **扩展** | mbstring, intl, pdo_mysql, pdo_oci |

### 7.2 安装步骤

```bash
# 1. 克隆代码
git clone <repository_url> hmss
cd hmss

# 2. 安装依赖
composer install --no-dev --optimize-autoloader

# 3. 配置本地设置
cp config/app_local.example.php config/app_local.php
cp config/.env.example config/.env

# 4. 编辑配置
# - 数据库连接信息
# - 安全盐值
# - 邮件设置

# 5. 设置权限
chmod -R 775 logs/ tmp/ webroot/files/

# 6. 数据库迁移
bin/cake migrations migrate

# 7. 清除缓存
bin/cake cache clear_all
```

### 7.3 配置文件

**config/app_local.php**:

```php
<?php
return [
    'debug' => false,
    
    'Security' => [
        'salt' => env('SECURITY_SALT', '<随机生成>'),
    ],
    
    'Datasources' => [
        'default' => [
            'className' => Connection::class,
            'driver' => Mysql::class,
            'host' => 'localhost',
            'username' => 'hmss_user',
            'password' => 'secure_password',
            'database' => 'hmss',
            'encoding' => 'utf8mb4',
            'timezone' => 'Asia/Tokyo',
        ],
    ],
];
```

### 7.4 Apache 配置

```apache
<VirtualHost *:80>
    ServerName hmss.example.com
    DocumentRoot /var/www/hmss/webroot
    
    <Directory /var/www/hmss/webroot>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/hmss_error.log
    CustomLog ${APACHE_LOG_DIR}/hmss_access.log combined
</VirtualHost>
```

### 7.5 日志管理

**日志目录**: `logs/`

**日志类型**:
- `error.log` - 错误日志
- `debug.log` - 调试日志
- `system.log` - 系统日志

**日志配置**:
```php
'Log' => [
    'debug' => [
        'className' => FileLog::class,
        'path' => LOGS,
        'levels' => ['notice', 'info', 'debug'],
        'file' => 'debug',
    ],
    'error' => [
        'className' => FileLog::class,
        'path' => LOGS,
        'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
        'file' => 'error',
    ],
],
```

### 7.6 性能优化

**缓存配置**:
```bash
# 启用 OPcache
extension=opcache.so
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=10000

# 启用 Redis 缓存 (可选)
extension=redis.so
```

**数据库优化**:
- 为常用查询字段添加索引
- 定期执行 ANALYZE TABLE
- 配置查询缓存

### 7.7 安全建议

1. **生产环境配置**:
   - 设置 `debug = false`
   - 使用强密码和随机 salt
   - 启用 HTTPS

2. **文件权限**:
   ```bash
   chmod 644 config/app_local.php
   chmod 775 logs/ tmp/ webroot/files/
   ```

3. **数据库安全**:
   - 使用专用数据库用户
   - 限制数据库用户权限
   - 定期备份

4. **会话安全**:
   ```php
   'Session' => [
       'defaults' => 'php',
       'ini' => [
           'session.cookie_secure' => true,
           'session.cookie_httponly' => true,
       ],
   ],
   ```

---

## 附录

### A. 术语表

| 术语 | 说明 |
|------|------|
| 伝票 | 传票 (会计凭证) |
| 仕訳 | 会计分录 |
| 科目 | 会计科目 |
| 取引先 | 客户/供应商 |
| 部署 | 部门 |
| 社員 | 员工 |
| 車検 | 车检 |
| 代替 | 替代销售 |
| 入庫 | 入库 |
| 拠点 | 据点/分支机构 |

### B. 相关文件

- `composer.json` - Composer 依赖配置
- `phpunit.xml.dist` - PHPUnit 测试配置
- `phpcs.xml` - 代码规范配置
- `phpstan.neon` - 静态分析配置
- `.editorconfig` - 编辑器配置
- `.gitignore` - Git 忽略配置

### C. 联系方式

- 系统管理员：系统管理担当
- 开发担当：开发部
- 运维担当：运维部

---

*文档版本：1.0*
*最后更新：2026 年 2 月*
