-- ============================================
-- HMSS Database Schema for LCP (SQLite)
-- 车检代替销售系统数据库结构
-- ============================================

-- ============================================
-- 基础表 - 用户和系统管理
-- ============================================

-- 用户表
CREATE TABLE IF NOT EXISTS hmss_users (
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
CREATE TABLE IF NOT EXISTS hmss_system_m (
    sys_cd VARCHAR(10) PRIMARY KEY,
    sys_nm VARCHAR(50) NOT NULL,
    sys_url VARCHAR(100),
    sys_order INTEGER DEFAULT 0,
    sys_use_flg CHAR(1) DEFAULT '1',
    sys_icon VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 菜单阶层主表
CREATE TABLE IF NOT EXISTS hmss_menu_kaisou_mst (
    menu_id VARCHAR(20) PRIMARY KEY,
    menu_nm VARCHAR(50) NOT NULL,
    parent_menu_id VARCHAR(20),
    menu_order INTEGER DEFAULT 0,
    menu_level INTEGER DEFAULT 0,
    sys_cd VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 程序主表
CREATE TABLE IF NOT EXISTS hmss_program_mst (
    program_id VARCHAR(20) PRIMARY KEY,
    program_nm VARCHAR(50) NOT NULL,
    program_path VARCHAR(100),
    program_type VARCHAR(10),
    sys_cd VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 系统日志表
CREATE TABLE IF NOT EXISTS hmss_system_log (
    log_id INTEGER PRIMARY KEY AUTOINCREMENT,
    log_dt DATETIME DEFAULT CURRENT_TIMESTAMP,
    usr_id VARCHAR(20),
    program_id VARCHAR(20),
    log_content TEXT,
    log_level VARCHAR(10) DEFAULT 'INFO'
);

-- ============================================
-- SDH 车检替代系统表
-- ============================================

-- VIN WMIVDS 表
CREATE TABLE IF NOT EXISTS sdh_vin_wmivds (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20) NOT NULL,
    vin VARCHAR(50),
    wmivds VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (csrno) REFERENCES sdh_contractor(csrno)
);

-- VIN VIS 表
CREATE TABLE IF NOT EXISTS sdh_vin_vis (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20) NOT NULL,
    vin VARCHAR(50),
    vis VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (csrno) REFERENCES sdh_contractor(csrno)
);

-- 契约者表
CREATE TABLE IF NOT EXISTS sdh_contractor (
    csrno VARCHAR(20) PRIMARY KEY,
    csr_nm VARCHAR(100) NOT NULL,
    kana VARCHAR(100),
    tel VARCHAR(20),
    tel2 VARCHAR(20),
    fax VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    post_cd VARCHAR(10),
    city_cd VARCHAR(10),
    tenpo_cd VARCHAR(10),
    jyasyu_cd VARCHAR(10),
    xh10caid VARCHAR(10),
    xg11koteiid VARCHAR(10),
    dm_fka_kb VARCHAR(1),
    xhktgkbn VARCHAR(10),
    csrrank VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 店铺表
CREATE TABLE IF NOT EXISTS sdh_tenpo (
    tenpo_cd VARCHAR(10) PRIMARY KEY,
    tenpo_nm VARCHAR(100) NOT NULL,
    address TEXT,
    tel VARCHAR(20),
    fax VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 时间线表
CREATE TABLE IF NOT EXISTS sdh_timeline (
    tl_id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20) NOT NULL,
    event_dt DATETIME,
    event_type VARCHAR(20),
    event_content TEXT,
    event_kb VARCHAR(10),
    usr_id VARCHAR(20),
    tenpo_cd VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (csrno) REFERENCES sdh_contractor(csrno),
    FOREIGN KEY (tenpo_cd) REFERENCES sdh_tenpo(tenpo_cd)
);

-- 判定车种主表
CREATE TABLE IF NOT EXISTS sdh_syasyu_mst (
    syasyu_cd VARCHAR(10) PRIMARY KEY,
    syasyu_nm VARCHAR(100) NOT NULL,
    display_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 判定列表
CREATE TABLE IF NOT EXISTS sdh_hantei_lst (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    yymm VARCHAR(6) NOT NULL,
    syadai VARCHAR(50),
    carno VARCHAR(20),
    csrno VARCHAR(20),
    hantei_cd VARCHAR(10),
    hantei_dt DATETIME,
    tenpo_cd VARCHAR(10),
    usr_id VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (csrno) REFERENCES sdh_contractor(csrno),
    FOREIGN KEY (tenpo_cd) REFERENCES sdh_tenpo(tenpo_cd)
);

-- 活动状况表
CREATE TABLE IF NOT EXISTS sdh_katsudo (
    katsudo_id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20) NOT NULL,
    hantei_cd VARCHAR(10) NOT NULL,
    hantei_dt DATETIME,
    jyasyu_cd VARCHAR(10),
    syasyu_cd VARCHAR(10),
    syadai VARCHAR(50),
    carno VARCHAR(20),
    vin VARCHAR(50),
    wmivds VARCHAR(50),
    vis VARCHAR(50),
    memo TEXT,
    tenpo_cd VARCHAR(10),
    usr_id VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (csrno) REFERENCES sdh_contractor(csrno),
    FOREIGN KEY (tenpo_cd) REFERENCES sdh_tenpo(tenpo_cd)
);

-- 订单书表
CREATE TABLE IF NOT EXISTS sdh_chumon (
    chumon_id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20) NOT NULL,
    chumon_dt DATETIME,
    chumon_kb VARCHAR(10),
    syasyu_cd VARCHAR(10),
    syadai VARCHAR(50),
    carno VARCHAR(20),
    kin_gaku DECIMAL(12,2),
    memo TEXT,
    tenpo_cd VARCHAR(10),
    usr_id VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (csrno) REFERENCES sdh_contractor(csrno),
    FOREIGN KEY (tenpo_cd) REFERENCES sdh_tenpo(tenpo_cd)
);

-- 保险信贷表
CREATE TABLE IF NOT EXISTS sdh_hoken (
    hoken_id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20) NOT NULL,
    hoken_kb VARCHAR(10),
    hoken_nm VARCHAR(100),
    kikan_start DATE,
    kikan_end DATE,
    kin_gaku DECIMAL(12,2),
    memo TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (csrno) REFERENCES sdh_contractor(csrno)
);

-- 备注表
CREATE TABLE IF NOT EXISTS sdh_biko (
    biko_id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20) NOT NULL,
    biko_nayo TEXT NOT NULL,
    usr_id VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (csrno) REFERENCES sdh_contractor(csrno)
);

-- 担当履历表
CREATE TABLE IF NOT EXISTS sdh_tanto (
    tanto_id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20) NOT NULL,
    tanto_usr_id VARCHAR(20) NOT NULL,
    tanto_nm VARCHAR(50),
    start_dt DATE,
    end_dt DATE,
    memo TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (csrno) REFERENCES sdh_contractor(csrno)
);

-- 入库状况表
CREATE TABLE IF NOT EXISTS sdh_nyuko (
    nyuko_id INTEGER PRIMARY KEY AUTOINCREMENT,
    csrno VARCHAR(20) NOT NULL,
    nyuko_kb VARCHAR(10) NOT NULL,
    nyuko_dt DATETIME,
    syasyu_cd VARCHAR(10),
    syadai VARCHAR(50),
    carno VARCHAR(20),
    vin VARCHAR(50),
    memo TEXT,
    tenpo_cd VARCHAR(10),
    usr_id VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (csrno) REFERENCES sdh_contractor(csrno),
    FOREIGN KEY (tenpo_cd) REFERENCES sdh_tenpo(tenpo_cd)
);

-- ============================================
-- HDKAIKEI 会计传票系统表
-- ============================================

-- 科目主表
CREATE TABLE IF NOT EXISTS hdk_kamoku_mst (
    kamoku_cd VARCHAR(10) PRIMARY KEY,
    kamoku_nm VARCHAR(50) NOT NULL,
    kamoku_kana VARCHAR(50),
    kamoku_level INTEGER DEFAULT 0,
    parent_kamoku_cd VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 取引先主表
CREATE TABLE IF NOT EXISTS hdk_torihikisaki_mst (
    torihiki_cd VARCHAR(10) PRIMARY KEY,
    torihiki_nm VARCHAR(100) NOT NULL,
    torihiki_kana VARCHAR(100),
    tel VARCHAR(20),
    fax VARCHAR(20),
    address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 部門主表
CREATE TABLE IF NOT EXISTS hdk_bumon_mst (
    bumon_cd VARCHAR(10) PRIMARY KEY,
    bumon_nm VARCHAR(50) NOT NULL,
    bumon_kana VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 社員主表
CREATE TABLE IF NOT EXISTS hdk_syain_mst (
    syain_no VARCHAR(10) PRIMARY KEY,
    syain_nm VARCHAR(50) NOT NULL,
    syain_kana VARCHAR(50),
    bumon_cd VARCHAR(10),
    tel VARCHAR(20),
    email VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 伝票表
CREATE TABLE IF NOT EXISTS hdk_denpyo (
    denpyo_no INTEGER PRIMARY KEY AUTOINCREMENT,
    denpyo_dt DATE,
    torihiki_cd VARCHAR(10),
    bumon_cd VARCHAR(10),
    syain_no VARCHAR(10),
    kingaku DECIMAL(12,2),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 仕訳表
CREATE TABLE IF NOT EXISTS hdk_shiwake (
    shiwake_no INTEGER PRIMARY KEY AUTOINCREMENT,
    denpyo_no INTEGER,
    kamoku_cd VARCHAR(10),
    karitae VARCHAR(1),
    kingaku DECIMAL(12,2),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 支払表
CREATE TABLE IF NOT EXISTS hdk_shiharai (
    shiharai_no INTEGER PRIMARY KEY AUTOINCREMENT,
    denpyo_no INTEGER,
    shiharai_dt DATE,
    kingaku DECIMAL(12,2),
    shiharai_hoho VARCHAR(1),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- パターン主表
CREATE TABLE IF NOT EXISTS hdk_pattern_mst (
    pattern_cd VARCHAR(10) PRIMARY KEY,
    pattern_nm VARCHAR(50) NOT NULL,
    pattern_content TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- HMDPS DPS 传票系统表
-- ============================================

-- DPS 伝票表
CREATE TABLE IF NOT EXISTS hmdps_denpyo (
    denpyo_no INTEGER PRIMARY KEY AUTOINCREMENT,
    denpyo_dt DATE,
    torihiki_cd VARCHAR(10),
    bumon_cd VARCHAR(10),
    syain_no VARCHAR(10),
    kingaku DECIMAL(12,2),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- DPS 仕訳表
CREATE TABLE IF NOT EXISTS hmdps_shiwake (
    shiwake_no INTEGER PRIMARY KEY AUTOINCREMENT,
    denpyo_no INTEGER,
    kamoku_cd VARCHAR(10),
    karitae VARCHAR(1),
    kingaku DECIMAL(12,2),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- DPS 支払表
CREATE TABLE IF NOT EXISTS hmdps_shiharai (
    shiharai_no INTEGER PRIMARY KEY AUTOINCREMENT,
    denpyo_no INTEGER,
    shiharai_dt DATE,
    kingaku DECIMAL(12,2),
    shiharai_hoho VARCHAR(1),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- DPS パターン主表
CREATE TABLE IF NOT EXISTS hmdps_pattern_mst (
    pattern_cd VARCHAR(10) PRIMARY KEY,
    pattern_nm VARCHAR(50) NOT NULL,
    pattern_content TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- 索引
-- ============================================

CREATE INDEX IF NOT EXISTS idx_sdh_contractor_tenpo ON sdh_contractor(tenpo_cd);
CREATE INDEX IF NOT EXISTS idx_sdh_contractor_csrnm ON sdh_contractor(csr_nm);
CREATE INDEX IF NOT EXISTS idx_sdh_timeline_csrno ON sdh_timeline(csrno);
CREATE INDEX IF NOT EXISTS idx_sdh_timeline_event_dt ON sdh_timeline(event_dt);
CREATE INDEX IF NOT EXISTS idx_sdh_hantei_lst_csrno ON sdh_hantei_lst(csrno);
CREATE INDEX IF NOT EXISTS idx_sdh_hantei_lst_yymm ON sdh_hantei_lst(yymm);
CREATE INDEX IF NOT EXISTS idx_sdh_katsudo_csrno ON sdh_katsudo(csrno);
CREATE INDEX IF NOT EXISTS idx_sdh_katsudo_hantei_cd ON sdh_katsudo(hantei_cd);
CREATE INDEX IF NOT EXISTS idx_sdh_chumon_csrno ON sdh_chumon(csrno);
CREATE INDEX IF NOT EXISTS idx_sdh_hoken_csrno ON sdh_hoken(csrno);
CREATE INDEX IF NOT EXISTS idx_sdh_biko_csrno ON sdh_biko(csrno);
CREATE INDEX IF NOT EXISTS idx_sdh_tanto_csrno ON sdh_tanto(csrno);
CREATE INDEX IF NOT EXISTS idx_sdh_nyuko_csrno ON sdh_nyuko(csrno);

-- ============================================
-- 初始数据
-- ============================================

-- 系统管理初始数据
INSERT OR IGNORE INTO hmss_system_m (sys_cd, sys_nm, sys_url, sys_order, sys_use_flg, sys_icon) VALUES
('Master', '主数据管理', '/hmss/master', 1, '1', 'bi:house-gear'),
('Login', '登录认证', '/hmss/login', 2, '1', 'bi:box-arrow-in-right'),
('HDKAIKEI', '会计传票', '/hmss/hdkaikei', 3, '1', 'bi:calculator'),
('HMAUD', '内部审计', '/hmss/hmaud', 4, '1', 'bi:shield-check'),
('HMDPS', 'DPS 传票', '/hmss/hmdps', 5, '1', 'bi:file-earmark-text'),
('HMHRMS', '人力资源', '/hmss/hmhrms', 6, '1', 'bi:people'),
('HMTVE', '数据汇总', '/hmss/hmtve', 7, '1', 'bi:graph-up'),
('JKSYS', '人事給与', '/hmss/jksys', 8, '1', 'bi:currency-yen'),
('R4', '管理会计', '/hmss/r4', 9, '1', 'bi:pie-chart'),
('SDH', '车检替代', '/hmss/sdh', 10, '1', 'bi:car-front'),
('APPM', '广域应用', '/hmss/appm', 11, '1', 'bi:app-indicator'),
('PPRM', '无纸化', '/hmss/pprm', 12, '1', 'bi:file-earmark-check'),
('CkChkzaiko', '库存确认', '/hmss/ckchkuzaiko', 13, '1', 'bi:boxes');

-- 默认管理员用户 (密码：admin123)
INSERT OR IGNORE INTO hmss_users (usr_id, usr_name, pass, email, sys1_flg, sys2_flg, sys3_flg, sys4_flg, sys5_flg, sys6_flg, sys7_flg, sys8_flg, sys9_flg, sys10_flg, sys11_flg, sys12_flg, sys13_flg, sys14_flg) VALUES
('admin', '管理员', '$2a$11$rW3XLqKQeQk6zN.X1oWpGOqvqO8LqQJ7Lx9L5L5L5L5L5L5L5L5L5', 'admin@hmss.com', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');

-- 判定车种初始数据
INSERT OR IGNORE INTO sdh_syasyu_mst (syasyu_cd, syasyu_nm, display_order) VALUES
('MAZDA3', 'MAZDA3', 1),
('CX5', 'CX-5', 2),
('CX8', 'CX-8', 3),
('CX60', 'CX-60', 4),
('ATENZA', 'ATENZA', 5),
('DEMI0', 'DEMIO', 6),
('ROADSTER', 'ROADSTER', 7),
('CX3', 'CX-3', 8),
('CX30', 'CX-30', 9),
('MX30', 'MX-30', 10);

-- 店铺初始数据
INSERT OR IGNORE INTO sdh_tenpo (tenpo_cd, tenpo_nm, tel) VALUES
('001', '广岛本店', '082-123-4567'),
('002', '东广岛店', '082-234-5678'),
('003', '吴店', '082-345-6789'),
('004', '福山店', '084-456-7890'),
('005', '尾道店', '084-567-8901');

-- 活动状况分类（用于 UI 显示）
-- rel="1": 代替促進 (16 车型)
-- rel="2": 入庫促進
-- rel="3": 入促・代促
-- rel="4": 代替確定 (16 车型)
-- rel="5": 代替予定
-- rel="6": 入庫確定
-- rel="7": 入庫予定
-- rel="8": 他社代替
-- rel="9": 他社入庫
-- rel="10": 転売
-- rel="11": 県外転出
-- rel="12": 入庫 X
-- rel="13": 車両なし
-- rel="14": 連絡 X
-- rel="15": 所在不明
-- rel="16": 業者契約
-- rel="17": リース
-- rel="18": リース指定工場
-- rel="19": 納入依頼

-- ============================================
-- HMHRMS 人力资源系统表
-- ============================================

-- 社員基本信息表
CREATE TABLE IF NOT EXISTS employee (
    emp_id VARCHAR(10) PRIMARY KEY,
    emp_nm VARCHAR(50) NOT NULL,
    emp_kana VARCHAR(50),
    birth_dt DATE,
    sex VARCHAR(1),
    address TEXT,
    tel VARCHAR(20),
    email VARCHAR(100),
    hire_dt DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 社員家族表
CREATE TABLE IF NOT EXISTS employee_family (
    family_id INTEGER PRIMARY KEY AUTOINCREMENT,
    emp_id VARCHAR(10) NOT NULL,
    family_nm VARCHAR(50),
    family_kana VARCHAR(50),
    relation VARCHAR(20),
    birth_dt DATE,
    sex VARCHAR(1),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id)
);

-- 社員学历表
CREATE TABLE IF NOT EXISTS employee_gakureki (
    gakureki_id INTEGER PRIMARY KEY AUTOINCREMENT,
    emp_id VARCHAR(10) NOT NULL,
    start_dt DATE,
    end_dt DATE,
    school_nm VARCHAR(100),
    dept_nm VARCHAR(100),
    degree VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id)
);

-- 社員职历表
CREATE TABLE IF NOT EXISTS employee_shokureki (
    shokureki_id INTEGER PRIMARY KEY AUTOINCREMENT,
    emp_id VARCHAR(10) NOT NULL,
    start_dt DATE,
    end_dt DATE,
    company_nm VARCHAR(100),
    dept_nm VARCHAR(100),
    position VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id)
);

-- 社員资格表
CREATE TABLE IF NOT EXISTS employee_shikaku (
    shikaku_id INTEGER PRIMARY KEY AUTOINCREMENT,
    emp_id VARCHAR(10) NOT NULL,
    shikaku_nm VARCHAR(100),
    acquire_dt DATE,
    issuer VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id)
);

-- ============================================
-- JKSYS 人事給与系统表
-- ============================================

-- 人事费表
CREATE TABLE IF NOT EXISTS jk_jinkenhi (
    jinken_id INTEGER PRIMARY KEY AUTOINCREMENT,
    yymm VARCHAR(6) NOT NULL,
    emp_id VARCHAR(10) NOT NULL,
    shikyu_gaku DECIMAL(10,2),
    genkin_gaku DECIMAL(10,2),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id)
);

-- 退职金表
CREATE TABLE IF NOT EXISTS jk_syoreikin (
    syorei_id INTEGER PRIMARY KEY AUTOINCREMENT,
    emp_id VARCHAR(10) NOT NULL,
    yymm VARCHAR(6) NOT NULL,
    syorei_gaku DECIMAL(10,2),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id)
);

-- 出勤管理表
CREATE TABLE IF NOT EXISTS jk_syukkou (
    syukkou_id INTEGER PRIMARY KEY AUTOINCREMENT,
    emp_id VARCHAR(10) NOT NULL,
    start_dt DATE,
    end_dt DATE,
    destination VARCHAR(200),
    purpose TEXT,
    seikyu_gaku DECIMAL(10,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id)
);

-- 评价表
CREATE TABLE IF NOT EXISTS jk_hyoka (
    hyoka_id INTEGER PRIMARY KEY AUTOINCREMENT,
    emp_id VARCHAR(10) NOT NULL,
    hyoka_kikan VARCHAR(20),
    hyoka_score DECIMAL(5,2),
    hyoka_rank VARCHAR(1),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id)
);

-- 就职活动履历表
CREATE TABLE IF NOT EXISTS jk_koyou_rireki (
    koyou_id INTEGER PRIMARY KEY AUTOINCREMENT,
    emp_id VARCHAR(10) NOT NULL,
    start_dt DATE,
    end_dt DATE,
    koyou_keitai VARCHAR(10),
    kinmu_time VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id)
);

-- 异动履历表
CREATE TABLE IF NOT EXISTS jk_idou_rireki (
    idou_id INTEGER PRIMARY KEY AUTOINCREMENT,
    emp_id VARCHAR(10) NOT NULL,
    idou_dt DATE,
    idou_shurui VARCHAR(10),
    before_bumon VARCHAR(50),
    after_bumon VARCHAR(50),
    before_shokugyou VARCHAR(50),
    after_shokugyou VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id)
);

-- ============================================
-- R4 管理会计系统表
-- ============================================

-- 部署主表
CREATE TABLE IF NOT EXISTS r4k_bumon_mst (
    bumon_cd VARCHAR(10) PRIMARY KEY,
    bumon_nm VARCHAR(50) NOT NULL,
    bumon_kana VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 社員主表
CREATE TABLE IF NOT EXISTS r4k_syain_mst (
    syain_no VARCHAR(10) PRIMARY KEY,
    syain_nm VARCHAR(50) NOT NULL,
    syain_kana VARCHAR(50),
    bumon_cd VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bumon_cd) REFERENCES r4k_bumon_mst(bumon_cd)
);

-- 業者主表
CREATE TABLE IF NOT EXISTS r4k_gyousya_mst (
    gyousya_cd VARCHAR(10) PRIMARY KEY,
    gyousya_nm VARCHAR(100) NOT NULL,
    gyousya_kana VARCHAR(100),
    tel VARCHAR(20),
    address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 予算管理表
CREATE TABLE IF NOT EXISTS r4k_yosan (
    yosan_id INTEGER PRIMARY KEY AUTOINCREMENT,
    yosan_year VARCHAR(4) NOT NULL,
    bumon_cd VARCHAR(10) NOT NULL,
    kamoku_cd VARCHAR(10) NOT NULL,
    yosan_gaku DECIMAL(12,2),
    jisseci_gaku DECIMAL(12,2),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bumon_cd) REFERENCES r4k_bumon_mst(bumon_cd)
);

-- 原価管理表
CREATE TABLE IF NOT EXISTS r4k_genka (
    genka_id INTEGER PRIMARY KEY AUTOINCREMENT,
    genka_dt DATE,
    gyousya_cd VARCHAR(10),
    bumon_cd VARCHAR(10),
    kamoku_cd VARCHAR(10),
    kingaku DECIMAL(12,2),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gyousya_cd) REFERENCES r4k_gyousya_mst(gyousya_cd),
    FOREIGN KEY (bumon_cd) REFERENCES r4k_bumon_mst(bumon_cd)
);

-- ============================================
-- HMAUD 审计系统表
-- ============================================

-- 监察人表
CREATE TABLE IF NOT EXISTS hmaud_kansa_jin (
    kansa_jin_cd VARCHAR(10) PRIMARY KEY,
    kansa_jin_nm VARCHAR(50) NOT NULL,
    kansa_jin_kana VARCHAR(50),
    tel VARCHAR(20),
    email VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 监察实绩表
CREATE TABLE IF NOT EXISTS hmaud_kansa_jisseki (
    jisseki_id INTEGER PRIMARY KEY AUTOINCREMENT,
    kansa_dt DATE,
    kansa_jin_cd VARCHAR(10),
    target_bumon VARCHAR(50),
    result TEXT,
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kansa_jin_cd) REFERENCES hmaud_kansa_jin(kansa_jin_cd)
);

-- SKD 表
CREATE TABLE IF NOT EXISTS hmaud_skd (
    skd_id INTEGER PRIMARY KEY AUTOINCREMENT,
    start_dt DATE,
    end_dt DATE,
    kansa_jin_cd VARCHAR(10),
    target_bumon VARCHAR(50),
    status VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kansa_jin_cd) REFERENCES hmaud_kansa_jin(kansa_jin_cd)
);

-- 报告表
CREATE TABLE IF NOT EXISTS hmaud_report (
    report_id INTEGER PRIMARY KEY AUTOINCREMENT,
    report_dt DATE,
    report_nm VARCHAR(100),
    content TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 议事录表
CREATE TABLE IF NOT EXISTS hmaud_gijiroku (
    gijiroku_id INTEGER PRIMARY KEY AUTOINCREMENT,
    kaigi_dt DATETIME,
    kaigi_nm VARCHAR(100),
    content TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- HMTVE 数据汇总系统表
-- ============================================

-- 输入数据 K 表
CREATE TABLE IF NOT EXISTS hmtve_input_data_k (
    data_id INTEGER PRIMARY KEY AUTOINCREMENT,
    input_dt DATE,
    tenpo_cd VARCHAR(10),
    syasyu_cd VARCHAR(10),
    taikai_cd VARCHAR(10),
    kazu INTEGER,
    kingaku DECIMAL(12,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 输入数据 S 表
CREATE TABLE IF NOT EXISTS hmtve_input_data_s (
    data_id INTEGER PRIMARY KEY AUTOINCREMENT,
    input_dt DATE,
    tenpo_cd VARCHAR(10),
    syasyu_cd VARCHAR(10),
    taikai_cd VARCHAR(10),
    kazu INTEGER,
    kingaku DECIMAL(12,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 展览表
CREATE TABLE IF NOT EXISTS hmtve_exhibition (
    exhibition_id INTEGER PRIMARY KEY AUTOINCREMENT,
    exhibition_dt DATE,
    exhibition_nm VARCHAR(100),
    location VARCHAR(200),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 出勤表
CREATE TABLE IF NOT EXISTS hmtve_attendance (
    attendance_id INTEGER PRIMARY KEY AUTOINCREMENT,
    emp_id VARCHAR(10),
    work_dt DATE,
    hours DECIMAL(5,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 广报订单表
CREATE TABLE IF NOT EXISTS hmtve_publicity_order (
    order_id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_dt DATE,
    order_nm VARCHAR(100),
    amount DECIMAL(12,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- APPM 广域应用系统表
-- ============================================

-- 账户表
CREATE TABLE IF NOT EXISTS appm_account (
    account_id INTEGER PRIMARY KEY AUTOINCREMENT,
    account_nm VARCHAR(100) NOT NULL,
    account_type VARCHAR(20),
    status VARCHAR(10),
    remark TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 消息表
CREATE TABLE IF NOT EXISTS appm_messeji (
    message_id INTEGER PRIMARY KEY AUTOINCREMENT,
    message_dt DATETIME,
    title VARCHAR(200),
    content TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 通知表
CREATE TABLE IF NOT EXISTS appm_oshirase (
    oshirase_id INTEGER PRIMARY KEY AUTOINCREMENT,
    oshirase_dt DATETIME,
    title VARCHAR(200),
    content TEXT,
    priority INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- PPRM 无纸化系统表
-- ============================================

-- 审批表
CREATE TABLE IF NOT EXISTS pprm_approve (
    approve_id INTEGER PRIMARY KEY AUTOINCREMENT,
    approve_dt DATETIME,
    target_id INTEGER,
    status VARCHAR(10),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- DC 图像表
CREATE TABLE IF NOT EXISTS pprm_dc_image (
    image_id INTEGER PRIMARY KEY AUTOINCREMENT,
    file_name VARCHAR(255),
    file_path VARCHAR(500),
    file_size DECIMAL(10,2),
    upload_dt DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 登录表
CREATE TABLE IF NOT EXISTS pprom_login (
    login_id INTEGER PRIMARY KEY AUTOINCREMENT,
    login_nm VARCHAR(100) NOT NULL,
    login_type VARCHAR(10),
    status VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 菜单权限表
CREATE TABLE IF NOT EXISTS pprom_menu_auth (
    auth_id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id VARCHAR(50),
    menu_id VARCHAR(50),
    auth_level VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
