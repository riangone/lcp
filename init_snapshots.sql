-- 快照表用于存储AI生成的建议和决策
CREATE TABLE IF NOT EXISTS Snapshots (
    Id TEXT PRIMARY KEY,
    ModelType TEXT NOT NULL,
    Data TEXT NOT NULL,           -- 存储序列化的数据
    Provenance TEXT NOT NULL,     -- 存储证迹信息
    CreatedAt DATETIME NOT NULL,
    Status TEXT NOT NULL DEFAULT 'Pending',
    ApprovedAt DATETIME NULL,
    ApprovedBy TEXT NULL
);

-- 为快照表创建索引以提高查询性能
CREATE INDEX IF NOT EXISTS idx_snapshots_model_status ON Snapshots(ModelType, Status);
CREATE INDEX IF NOT EXISTS idx_snapshots_created_at ON Snapshots(CreatedAt);