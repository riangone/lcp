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
('Login', '登录認証', '/hmss/login', 2, '1', 'bi:box-arrow-in-right'),
('HDKAIKEI', '会計伝票', '/hmss/hdkaikei', 3, '1', 'bi:calculator'),
('HMAUD', '内部監査', '/hmss/hmaud', 4, '1', 'bi:shield-check'),
('HMDPS', 'DPS 伝票', '/hmss/hmdps', 5, '1', 'bi:file-earmark-text'),
('HMHRMS', '人力資源', '/hmss/hmhrms', 6, '1', 'bi:people'),
('HMTVE', 'データ集計', '/hmss/hmtve', 7, '1', 'bi:graph-up'),
('JKSYS', '人事給与', '/hmss/jksys', 8, '1', 'bi:currency-yen'),
('R4', '管理会計', '/hmss/r4', 9, '1', 'bi:pie-chart'),
('SDH', '車検代替', '/hmss/sdh', 10, '1', 'bi:car-front'),
('APPM', '広域應用', '/hmss/appm', 11, '1', 'bi:app-indicator'),
('PPRM', '紙なし化', '/hmss/pprm', 12, '1', 'bi:file-earmark-check'),
('CkChkzaiko', '在庫確認', '/hmss/ckchkzaiko', 13, '1', 'bi:boxes');

-- 默认管理员用户 (密码：admin123)
-- BCrypt hash: $2b$12$lhiFpjbmv23yO0YeHuC1IueREBBuV05blGA3.ioUd6FM0ZywanTQm
INSERT OR IGNORE INTO hmss_users (usr_id, usr_name, pass, email, sys1_flg, sys2_flg, sys3_flg, sys4_flg, sys5_flg, sys6_flg, sys7_flg, sys8_flg, sys9_flg, sys10_flg, sys11_flg, sys12_flg, sys13_flg, sys14_flg) VALUES
('admin', '管理员', '$2b$12$lhiFpjbmv23yO0YeHuC1IueREBBuV05blGA3.ioUd6FM0ZywanTQm', 'admin@hmss.com', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');

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
-- PPRM 无纸化系统表
-- ============================================

-- 承认管理表
CREATE TABLE IF NOT EXISTS pprom_approve (
    approve_id INTEGER PRIMARY KEY AUTOINCREMENT,
    approve_title VARCHAR(200) NOT NULL,
    requester VARCHAR(50),
    approver VARCHAR(50),
    status CHAR(1) DEFAULT '1',
    request_dt DATETIME,
    approve_dt DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- DC 画像管理表
CREATE TABLE IF NOT EXISTS pprom_dc_image (
    image_id INTEGER PRIMARY KEY AUTOINCREMENT,
    image_name VARCHAR(200) NOT NULL,
    file_path VARCHAR(500),
    file_size DECIMAL(12,2),
    upload_dt DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 登录管理表
CREATE TABLE IF NOT EXISTS pprom_login (
    login_id INTEGER PRIMARY KEY AUTOINCREMENT,
    login_nm VARCHAR(100) NOT NULL,
    login_type CHAR(1) DEFAULT '1',
    status CHAR(1) DEFAULT '1',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 菜单权限表
CREATE TABLE IF NOT EXISTS pprom_menu_auth (
    auth_id INTEGER PRIMARY KEY AUTOINCREMENT,
    auth_nm VARCHAR(100) NOT NULL,
    menu_id VARCHAR(20),
    auth_level CHAR(1) DEFAULT '1',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- PPRM 索引
-- ============================================

CREATE INDEX IF NOT EXISTS idx_pprom_approve_status ON pprom_approve(status);
CREATE INDEX IF NOT EXISTS idx_pprom_approve_requester ON pprom_approve(requester);
CREATE INDEX IF NOT EXISTS idx_pprom_dc_image_upload_dt ON pprom_dc_image(upload_dt);
CREATE INDEX IF NOT EXISTS idx_pprom_login_status ON pprom_login(status);

-- ============================================
-- R4 管理会计系统表
-- ============================================

-- 売上表
CREATE TABLE IF NOT EXISTS r4k_uriage (
    uriage_id INTEGER PRIMARY KEY AUTOINCREMENT,
    uriage_dt DATE NOT NULL,
    bumon_cd VARCHAR(10) NOT NULL,
    kamoku_cd VARCHAR(10),
    uriage_gaku DECIMAL(12,2) NOT NULL,
    remark VARCHAR(500),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 経理処理表
CREATE TABLE IF NOT EXISTS r4k_kaikei (
    kaikei_id INTEGER PRIMARY KEY AUTOINCREMENT,
    kaikei_dt DATE NOT NULL,
    kamoku_cd VARCHAR(10) NOT NULL,
    karitae CHAR(1) NOT NULL,
    kingaku DECIMAL(12,2) NOT NULL,
    remark VARCHAR(500),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 請求表
CREATE TABLE IF NOT EXISTS r4k_seikyu (
    seikyu_id INTEGER PRIMARY KEY AUTOINCREMENT,
    seikyu_dt DATE NOT NULL,
    gyousya_cd VARCHAR(10) NOT NULL,
    seikyu_gaku DECIMAL(12,2) NOT NULL,
    shiharai_dt DATE,
    remark VARCHAR(500),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- R4 索引
-- ============================================

CREATE INDEX IF NOT EXISTS idx_r4k_uriage_bumon_cd ON r4k_uriage(bumon_cd);
CREATE INDEX IF NOT EXISTS idx_r4k_uriage_uriage_dt ON r4k_uriage(uriage_dt);
CREATE INDEX IF NOT EXISTS idx_r4k_kaikei_kamoku_cd ON r4k_kaikei(kamoku_cd);
CREATE INDEX IF NOT EXISTS idx_r4k_kaikei_kaikei_dt ON r4k_kaikei(kaikei_dt);
CREATE INDEX IF NOT EXISTS idx_r4k_seikyu_gyousya_cd ON r4k_seikyu(gyousya_cd);
CREATE INDEX IF NOT EXISTS idx_r4k_seikyu_seikyu_dt ON r4k_seikyu(seikyu_dt);
