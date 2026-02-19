-- 通用用户表结构
-- 添加到每个项目的 schema.sql 中

CREATE TABLE IF NOT EXISTS "User" (
    UserId INTEGER PRIMARY KEY AUTOINCREMENT,
    Username TEXT NOT NULL UNIQUE,
    Email TEXT NOT NULL UNIQUE,
    PasswordHash TEXT NOT NULL,
    DisplayName TEXT,
    Role TEXT DEFAULT 'User',  -- User, Admin, Manager
    Avatar TEXT,
    Bio TEXT,
    IsActive INTEGER DEFAULT 1,
    LastLoginAt TEXT,
    LastLoginIP TEXT,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    CreatedBy TEXT,
    UpdatedBy TEXT
);

-- 用户角色表（可选，用于更复杂的权限管理）
CREATE TABLE IF NOT EXISTS UserRole (
    RoleId INTEGER PRIMARY KEY AUTOINCREMENT,
    RoleName TEXT NOT NULL UNIQUE,
    Description TEXT,
    Permissions TEXT,  -- JSON 格式存储权限列表
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP
);

-- 用户会话表（用于管理登录会话）
CREATE TABLE IF NOT EXISTS UserSession (
    SessionId TEXT PRIMARY KEY,
    UserId INTEGER NOT NULL,
    Token TEXT NOT NULL,
    IPAddress TEXT,
    UserAgent TEXT,
    ExpiresAt TEXT NOT NULL,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserId) REFERENCES "User"(UserId) ON DELETE CASCADE
);

-- 索引
CREATE INDEX IF NOT EXISTS idx_user_username ON "User"(Username);
CREATE INDEX IF NOT EXISTS idx_user_email ON "User"(Email);
CREATE INDEX IF NOT EXISTS idx_user_role ON "User"(Role);
CREATE INDEX IF NOT EXISTS idx_session_user ON UserSession(UserId);
CREATE INDEX IF NOT EXISTS idx_session_token ON UserSession(Token);
CREATE INDEX IF NOT EXISTS idx_session_expires ON UserSession(ExpiresAt);

-- 初始管理员账户（密码：admin123，实际使用时请修改）
-- 密码哈希使用 BCrypt 生成
INSERT OR IGNORE INTO "User" (Username, Email, PasswordHash, DisplayName, Role) VALUES 
('admin', 'admin@example.com', '$2a$11$rQZ9vXJXL5K5Z5Z5Z5Z5ZeYhQGYhQGYhQGYhQGYhQGYhQGYhQGYhQ', '系统管理员', 'Admin');

-- 插入默认角色
INSERT OR IGNORE INTO UserRole (RoleName, Description, Permissions) VALUES 
('User', '普通用户', '["read","write:own"]'),
('Manager', '管理员', '["read","write","delete:own"]'),
('Admin', '超级管理员', '["*"]');
