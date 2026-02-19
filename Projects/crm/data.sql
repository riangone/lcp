-- CRM 客户关系管理系统种子数据

-- ==================== 管理员账户 ====================
INSERT OR IGNORE INTO "User" (Username, Email, PasswordHash, DisplayName, Role) VALUES 
('admin', 'admin@crm.com', '$2b$11$14jOHrwJX08Uz.9cB2RJEuMdeFio7qXn/cM945xp3bxNePN8q1BMS', '系统管理员', 'Admin'),
('sales1', 'sales1@crm.com', '$2b$11$14jOHrwJX08Uz.9cB2RJEuMdeFio7qXn/cM945xp3bxNePN8q1BMS', '销售员 01', 'User'),
('sales2', 'sales2@crm.com', '$2b$11$14jOHrwJX08Uz.9cB2RJEuMdeFio7qXn/cM945xp3bxNePN8q1BMS', '销售员 02', 'User');

-- ==================== 客户数据 ====================
INSERT OR IGNORE INTO Customer (CustomerNo, CompanyName, ContactPerson, Industry, Scale, Source, Level, Website, Email, Phone, Address, City, Country, Description, OwnerId, Status) VALUES
('CUST-001', '科技有限公司', '张伟', 'Technology', 'Medium', 'Website', 'Hot', 'www.tech.com', 'info@tech.com', '+86-10-8888-9999', '中关村大街 1 号', 'Beijing', 'China', '专注于软件开发和 IT 服务', 1, 'Active'),
('CUST-002', '贸易集团', '李娜', 'Retail', 'Large', 'Referral', 'Warm', 'www.trade.com', 'contact@trade.com', '+86-21-6666-8888', '南京东路 100 号', 'Shanghai', 'China', '大型零售贸易企业', 1, 'Active'),
('CUST-003', '制造股份', '王强', 'Manufacturing', 'Enterprise', 'TradeShow', 'Hot', 'www.manuf.com', 'info@manuf.com', '+86-755-8888-6666', '科技园路 88 号', 'Shenzhen', 'China', '电子产品制造', 2, 'Active'),
('CUST-004', '金融服务', '刘芳', 'Finance', 'Large', 'ColdCall', 'Warm', 'www.finance.com', 'service@finance.com', '+86-20-7777-8888', '珠江新城 88 号', 'Guangzhou', 'China', '金融投资服务', 2, 'Active'),
('CUST-005', '医疗集团', '陈明', 'Healthcare', 'Enterprise', 'Website', 'Hot', 'www.healthcare.com', 'info@healthcare.com', '+86-28-6666-9999', '高新区天府大道', 'Chengdu', 'China', '医疗服务和健康管理', 1, 'Active'),
('CUST-006', '教育科技', '赵敏', 'Technology', 'Small', 'SocialMedia', 'Cold', 'www.edu.com', 'contact@edu.com', '+86-571-8888-7777', '西湖区文一路', 'Hangzhou', 'China', '在线教育平台', 3, 'Active'),
('CUST-007', '物流公司', '孙强', 'Retail', 'Medium', 'Referral', 'Warm', 'www.logistics.com', 'info@logistics.com', '+86-27-7777-6666', '江汉路 88 号', 'Wuhan', 'China', '全国物流配送', 3, 'Active'),
('CUST-008', '能源集团', '周杰', 'Manufacturing', 'Enterprise', 'TradeShow', 'Hot', 'www.energy.com', 'contact@energy.com', '+86-24-8888-9999', '和平区南京路', 'Shenyang', 'China', '新能源开发', 2, 'Active');

-- ==================== 联系人数据 ====================
INSERT OR IGNORE INTO Contact (CustomerId, FirstName, LastName, Title, Department, Email, Phone, Mobile, IsPrimary) VALUES
(1, '伟', '张', '总经理', '管理层', 'zhangwei@tech.com', '+86-10-8888-9999', '+86-138-0000-0001', 1),
(1, '敏', '李', '技术总监', '技术部', 'limin@tech.com', '+86-10-8888-9998', '+86-138-0000-0002', 0),
(2, '娜', '李', '采购经理', '采购部', 'lina@trade.com', '+86-21-6666-8888', '+86-138-0000-0003', 1),
(2, '强', '王', '销售总监', '销售部', 'wangqiang@trade.com', '+86-21-6666-8887', '+86-138-0000-0004', 0),
(3, '强', '王', '厂长', '生产部', 'wangqiang@manuf.com', '+86-755-8888-6666', '+86-138-0000-0005', 1),
(3, '芳', '刘', '质量经理', '质检部', 'liufang@manuf.com', '+86-755-8888-6665', '+86-138-0000-0006', 0),
(4, '芳', '刘', '投资总监', '投资部', 'liufang@finance.com', '+86-20-7777-8888', '+86-138-0000-0007', 1),
(5, '明', '陈', '院长', '管理层', 'chenming@healthcare.com', '+86-28-6666-9999', '+86-138-0000-0008', 1),
(5, '杰', '周', '信息主任', '信息部', 'zhoujie@healthcare.com', '+86-28-6666-9998', '+86-138-0000-0009', 0),
(6, '敏', '赵', 'CEO', '管理层', 'zhaomin@edu.com', '+86-571-8888-7777', '+86-138-0000-0010', 1),
(7, '强', '孙', '总经理', '管理层', 'sunqiang@logistics.com', '+86-27-7777-6666', '+86-138-0000-0011', 1),
(8, '杰', '周', '董事长', '管理层', 'zhoujie@energy.com', '+86-24-8888-9999', '+86-138-0000-0012', 1);

-- ==================== 销售机会 ====================
INSERT OR IGNORE INTO Opportunity (OpportunityNo, CustomerId, ContactId, Title, Stage, Probability, Amount, ExpectedCloseDate, NextStep, OwnerId, Status, Priority) VALUES
('OPP-001', 1, 1, '企业软件系统项目', 'Proposal', 60, 500000.00, '2026-03-31', '提交详细方案', 1, 'Open', 'High'),
('OPP-002', 2, 3, 'ERP 系统升级', 'Negotiation', 80, 350000.00, '2026-03-15', '合同谈判', 1, 'Open', 'High'),
('OPP-003', 3, 5, '生产线自动化改造', 'Qualification', 40, 1200000.00, '2026-04-30', '需求调研', 2, 'Open', 'Urgent'),
('OPP-004', 4, 7, '金融数据分析平台', 'Prospecting', 20, 800000.00, '2026-05-31', '初次拜访', 2, 'Open', 'Medium'),
('OPP-005', 5, 8, '医院信息管理系统', 'Proposal', 70, 2000000.00, '2026-03-20', '方案演示', 1, 'Open', 'Urgent'),
('OPP-006', 6, 10, '在线教育平台开发', 'Qualification', 30, 450000.00, '2026-06-30', '技术交流', 3, 'Open', 'Low'),
('OPP-007', 7, 11, '物流追踪系统', 'Negotiation', 75, 280000.00, '2026-03-10', '价格谈判', 3, 'Open', 'Medium'),
('OPP-008', 8, 12, '能源监控平台', 'Proposal', 50, 1500000.00, '2026-04-15', '技术方案确认', 2, 'Open', 'High');

-- ==================== 产品数据 ====================
INSERT OR IGNORE INTO Product (ProductNo, ProductName, Category, Specification, Unit, UnitPrice, CostPrice, StockQuantity, Description, IsActive) VALUES
('PROD-001', '企业版软件许可', 'Software', '标准版', 'License', 50000.00, 20000.00, 100, '企业级软件系统许可', 1),
('PROD-002', '专业版软件许可', 'Software', '专业版', 'License', 30000.00, 12000.00, 200, '专业版软件系统许可', 1),
('PROD-003', '标准版软件许可', 'Software', '标准版', 'License', 15000.00, 6000.00, 500, '标准版软件系统许可', 1),
('PROD-004', '实施服务费', 'Service', '人天', 'Day', 5000.00, 3000.00, 0, '系统实施服务', 1),
('PROD-005', '技术支持年费', 'Service', '年', 'Year', 20000.00, 5000.00, 0, '年度技术支持服务', 1),
('PROD-006', '定制开发费', 'Service', '人天', 'Day', 8000.00, 5000.00, 0, '定制化开发服务', 1),
('PROD-007', '培训服务费', 'Service', '次', 'Session', 10000.00, 3000.00, 0, '用户培训服务', 1),
('PROD-008', '云服务器年费', 'Hardware', '年', 'Year', 50000.00, 35000.00, 0, '云服务器租赁', 1),
('PROD-009', '数据库许可', 'Software', '套', 'License', 80000.00, 40000.00, 50, '企业数据库许可', 1),
('PROD-010', '接口开发包', 'Software', '套', 'License', 25000.00, 10000.00, 100, 'API 接口开发包', 1);

-- ==================== 报价数据 ====================
INSERT OR IGNORE INTO Quote (QuoteNo, CustomerId, ContactId, OpportunityId, QuoteDate, ExpiryDate, Status, Subtotal, Discount, Tax, TotalAmount, Notes, OwnerId) VALUES
('QT-001', 1, 1, 1, '2026-02-15', '2026-03-15', 'Sent', 150000.00, 10000.00, 14000.00, 154000.00, '企业软件系统报价', 1),
('QT-002', 2, 3, 2, '2026-02-10', '2026-03-10', 'Accepted', 350000.00, 20000.00, 33000.00, 363000.00, 'ERP 系统升级报价', 1),
('QT-003', 5, 8, 5, '2026-02-12', '2026-03-12', 'Draft', 500000.00, 30000.00, 47000.00, 517000.00, '医院信息管理系统报价', 1);

-- ==================== 报价明细 ====================
INSERT OR IGNORE INTO QuoteItem (QuoteId, ProductId, Quantity, UnitPrice, Discount, Subtotal) VALUES
(1, 1, 2, 50000.00, 5000.00, 95000.00),
(1, 4, 10, 5000.00, 0, 50000.00),
(1, 5, 1, 20000.00, 5000.00, 15000.00),
(2, 2, 5, 30000.00, 10000.00, 140000.00),
(2, 4, 20, 5000.00, 5000.00, 95000.00),
(2, 6, 15, 8000.00, 5000.00, 115000.00),
(3, 1, 5, 50000.00, 20000.00, 230000.00),
(3, 4, 30, 5000.00, 5000.00, 145000.00),
(3, 5, 3, 20000.00, 5000.00, 55000.00);

-- ==================== 订单数据 ====================
INSERT OR IGNORE INTO "Order" (OrderNo, CustomerId, ContactId, QuoteId, OpportunityId, OrderDate, Status, Subtotal, Discount, Tax, ShippingCost, TotalAmount, PaymentStatus, Notes, OwnerId) VALUES
('ORD-001', 2, 3, 2, 2, '2026-02-18', 'Confirmed', 350000.00, 20000.00, 33000.00, 0, 363000.00, 'Paid', 'ERP 系统升级项目', 1),
('ORD-002', 1, 1, NULL, 1, '2026-02-15', 'Processing', 100000.00, 5000.00, 9500.00, 0, 104500.00, 'Partial', '首期款已付', 1);

-- ==================== 订单明细 ====================
INSERT OR IGNORE INTO OrderItem (OrderId, ProductId, Quantity, UnitPrice, Discount, Subtotal) VALUES
(1, 2, 5, 30000.00, 10000.00, 140000.00),
(1, 4, 20, 5000.00, 5000.00, 95000.00),
(1, 6, 15, 8000.00, 5000.00, 115000.00),
(2, 1, 2, 50000.00, 5000.00, 95000.00),
(2, 5, 1, 20000.00, 0, 20000.00);

-- ==================== 活动数据 ====================
INSERT OR IGNORE INTO Activity (ActivityType, Subject, Description, RelatedTo, RelatedId, Status, Priority, DueDate, OwnerId) VALUES
('Meeting', '客户需求调研', '科技有限公司软件系统需求讨论', 'Customer', 1, 'Completed', 'High', '2026-02-10', 1),
('Call', '方案演示跟进', '贸易集团 ERP 系统方案演示', 'Opportunity', 2, 'Completed', 'Normal', '2026-02-12', 1),
('Email', '报价单发送', '发送医院信息管理系统报价单', 'Quote', 3, 'Completed', 'High', '2026-02-12', 1),
('Task', '技术方案编写', '制造股份生产线自动化技术方案', 'Opportunity', 3, 'Pending', 'Urgent', '2026-02-25', 2),
('Meeting', '商务谈判', '金融数据分析平台合同谈判', 'Opportunity', 4, 'Pending', 'High', '2026-02-28', 2),
('Note', '客户拜访记录', '教育科技客户拜访记录', 'Customer', 6, 'Completed', 'Low', '2026-02-15', 3),
('Call', '物流追踪系统跟进', '物流公司项目跟进电话', 'Opportunity', 7, 'Pending', 'Normal', '2026-02-22', 3),
('Task', '能源监控平台方案', '准备技术方案和预算', 'Opportunity', 8, 'Pending', 'High', '2026-02-28', 2);
