-- TODO 项目示例数据
-- 用于初始化项目的测试数据

-- 项目示例数据
INSERT OR IGNORE INTO Project (Id, Name, Description, Status, StartDate, EndDate) VALUES
(1, 'Website Redesign', 'Redesign company website with modern UI/UX', 'active', '2026-01-01', '2026-03-31'),
(2, 'Mobile App Development', 'Build cross-platform mobile application', 'active', '2026-02-01', '2026-06-30'),
(3, 'Database Migration', 'Migrate legacy database to new system', 'planning', '2026-03-01', '2026-04-30'),
(4, 'Marketing Campaign', 'Q1 2026 marketing campaign planning', 'completed', '2026-01-01', '2026-01-31'),
(5, 'Security Audit', 'Annual security audit and compliance check', 'on_hold', '2026-04-01', '2026-05-31');

-- 任务示例数据
INSERT OR IGNORE INTO Task (Id, Title, Description, Status, Priority, DueDate, ProjectId, CreatedAt) VALUES
-- Website Redesign 项目任务
(1, 'Design homepage mockup', 'Create initial homepage design mockup', 'completed', 'high', '2026-01-15', 1, '2026-01-02 09:00:00'),
(2, 'Implement responsive navigation', 'Build mobile-friendly navigation menu', 'in_progress', 'medium', '2026-02-10', 1, '2026-01-10 10:30:00'),
(3, 'Optimize images and assets', 'Compress and optimize all images for web', 'pending', 'low', '2026-02-28', 1, '2026-01-15 14:00:00'),
(4, 'Setup CI/CD pipeline', 'Configure automated deployment', 'pending', 'high', '2026-02-15', 1, '2026-01-20 11:00:00'),

-- Mobile App Development 项目任务
(5, 'Define app requirements', 'Document functional and non-functional requirements', 'completed', 'urgent', '2026-02-05', 2, '2026-01-25 09:30:00'),
(6, 'Design app wireframes', 'Create wireframes for all screens', 'in_progress', 'high', '2026-02-20', 2, '2026-02-01 10:00:00'),
(7, 'Setup React Native project', 'Initialize React Native project structure', 'pending', 'medium', '2026-02-25', 2, '2026-02-05 15:00:00'),
(8, 'Implement user authentication', 'Build login and registration screens', 'pending', 'high', '2026-03-10', 2, '2026-02-08 09:00:00'),
(9, 'Integrate push notifications', 'Setup Firebase push notifications', 'pending', 'medium', '2026-03-20', 2, '2026-02-10 11:30:00'),

-- Database Migration 项目任务
(10, 'Analyze current schema', 'Document existing database structure', 'pending', 'high', '2026-03-10', 3, '2026-02-15 10:00:00'),
(11, 'Design new schema', 'Create optimized database schema', 'pending', 'urgent', '2026-03-20', 3, '2026-02-20 14:00:00'),
(12, 'Write migration scripts', 'Develop data migration scripts', 'pending', 'high', '2026-04-10', 3, '2026-03-01 09:00:00'),

-- Marketing Campaign 项目任务（已完成）
(13, 'Create campaign content', 'Write copy and design graphics', 'completed', 'high', '2026-01-15', 4, '2026-01-02 09:00:00'),
(14, 'Setup social media posts', 'Schedule posts across all platforms', 'completed', 'medium', '2026-01-20', 4, '2026-01-05 10:00:00'),
(15, 'Analyze campaign results', 'Generate performance report', 'completed', 'low', '2026-02-05', 4, '2026-01-25 15:00:00');

-- 更新已完成任务的 CompletedAt
UPDATE Task SET CompletedAt = datetime('now') WHERE Status = 'completed';
