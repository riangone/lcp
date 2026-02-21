-- ============================================
-- HMSS 数据库初始化脚本
-- 用于在应用启动时自动创建和初始化数据库
-- ============================================

-- 检查表是否已存在
SELECT CASE 
    WHEN COUNT(*) > 0 THEN 'Tables exist'
    ELSE 'Tables need creation'
END AS Status
FROM sqlite_master 
WHERE type = 'table' AND name = 'hmss_users';
