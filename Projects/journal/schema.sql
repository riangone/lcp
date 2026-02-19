-- 日记本应用 - 数据库结构

-- 分类表
CREATE TABLE IF NOT EXISTS Category (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE,
    Color TEXT DEFAULT '#3b82f6',
    Description TEXT,
    CreatedAt TEXT DEFAULT (datetime('now'))
);

-- 日记表
CREATE TABLE IF NOT EXISTS Entry (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    Title TEXT NOT NULL,
    Content TEXT NOT NULL,
    Mood TEXT NOT NULL DEFAULT 'neutral',
    CategoryId INTEGER,
    Date TEXT DEFAULT (date('now')),
    CreatedAt TEXT DEFAULT (datetime('now')),
    UpdatedAt TEXT DEFAULT (datetime('now')),
    FOREIGN KEY (CategoryId) REFERENCES Category(Id)
);

-- 标签表
CREATE TABLE IF NOT EXISTS Tag (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE,
    Color TEXT DEFAULT '#10b981',
    CreatedAt TEXT DEFAULT (datetime('now'))
);

-- 日记标签关联表
CREATE TABLE IF NOT EXISTS EntryTag (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    EntryId INTEGER NOT NULL,
    TagId INTEGER NOT NULL,
    CreatedAt TEXT DEFAULT (datetime('now')),
    FOREIGN KEY (EntryId) REFERENCES Entry(Id) ON DELETE CASCADE,
    FOREIGN KEY (TagId) REFERENCES Tag(Id) ON DELETE CASCADE,
    UNIQUE(EntryId, TagId)
);

-- 创建索引
CREATE INDEX IF NOT EXISTS idx_entry_date ON Entry(Date);
CREATE INDEX IF NOT EXISTS idx_entry_mood ON Entry(Mood);
CREATE INDEX IF NOT EXISTS idx_entry_category ON Entry(CategoryId);
CREATE INDEX IF NOT EXISTS idx_entrytag_entry ON EntryTag(EntryId);
CREATE INDEX IF NOT EXISTS idx_entrytag_tag ON EntryTag(TagId);
