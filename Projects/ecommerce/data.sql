-- 电商订单系统种子数据

-- ==================== 分类数据 ====================
INSERT INTO Category (Name, Description) VALUES 
('Electronics', '电子产品类'),
('Computers', '电脑及配件'),
('Phones', '手机及配件'),
('Audio', '音频设备'),
('Clothing', '服装类'),
('Men', '男装'),
('Women', '女装'),
('Books', '图书类'),
('Fiction', '小说'),
('Non-Fiction', '非小说类'),
('Home', '家居用品'),
('Sports', '运动用品');

-- ==================== 供应商数据 ====================
INSERT INTO Supplier (CompanyName, ContactName, ContactEmail, ContactPhone, City, Country) VALUES 
('TechSupply Co.', 'John Smith', 'john@techsupply.com', '+1-555-0101', 'San Francisco', 'USA'),
('Global Electronics', 'Li Wei', 'liwei@globalelec.cn', '+86-10-8888-9999', 'Beijing', 'China'),
('Fashion World', 'Marie Dupont', 'marie@fashionworld.fr', '+33-1-2345-6789', 'Paris', 'France'),
('Book Distributors Inc.', 'Robert Johnson', 'robert@bookdist.com', '+1-555-0202', 'New York', 'USA'),
('Home Essentials', 'Zhang Min', 'zhangmin@homeessentials.cn', '+86-21-6666-8888', 'Shanghai', 'China');

-- ==================== 产品数据 ====================
INSERT INTO Product (ProductName, Description, CategoryId, SupplierId, UnitPrice, StockQuantity, ReorderLevel) VALUES 
-- 电子产品
('iPhone 15 Pro', 'Apple 最新旗舰手机', 3, 2, 8999.00, 50, 10),
('Samsung Galaxy S24', '三星旗舰智能手机', 3, 2, 6999.00, 80, 15),
('MacBook Pro 16"', 'Apple 专业笔记本电脑', 2, 1, 19999.00, 20, 5),
('Dell XPS 15', '戴尔高性能笔记本', 2, 1, 12999.00, 30, 8),
('Sony WH-1000XM5', '索尼降噪耳机', 4, 2, 2499.00, 100, 20),
('AirPods Pro 2', 'Apple 无线耳机', 4, 1, 1899.00, 150, 30),
('iPad Air', 'Apple 平板电脑', 2, 1, 4799.00, 60, 12),
('Nintendo Switch', '任天堂游戏机', 12, 2, 2099.00, 40, 10),

-- 服装
('Men''s Casual Shirt', '男士休闲衬衫', 6, 3, 299.00, 200, 50),
('Women''s Dress', '女士连衣裙', 7, 3, 599.00, 150, 30),
('Jeans Classic', '经典牛仔裤', 6, 3, 399.00, 300, 60),
('Running Shoes', '运动鞋', 12, 3, 699.00, 100, 25),

-- 图书
('The Great Gatsby', '了不起的盖茨比', 9, 4, 45.00, 500, 100),
('Atomic Habits', '原子习惯', 10, 4, 68.00, 400, 80),
('Deep Learning', '深度学习', 10, 4, 128.00, 200, 40),
('Three-Body Problem', '三体', 9, 4, 58.00, 600, 120),

-- 家居
('Smart LED Lamp', '智能 LED 台灯', 11, 5, 199.00, 250, 50),
('Ergonomic Office Chair', '人体工学办公椅', 11, 5, 1299.00, 50, 10),
('Coffee Maker', '咖啡机', 11, 5, 599.00, 80, 20),
('Air Purifier', '空气净化器', 11, 5, 899.00, 60, 15);

-- ==================== 客户数据 ====================
INSERT INTO Customer (FirstName, LastName, Email, Phone, City, Country, CustomerType) VALUES 
('Wei', 'Zhang', 'zhang.wei@email.com', '+86-138-0000-0001', 'Beijing', 'China', 'VIP'),
('Li', 'Wang', 'wang.li@email.com', '+86-138-0000-0002', 'Shanghai', 'China', 'Retail'),
('Fang', 'Li', 'li.fang@email.com', '+86-138-0000-0003', 'Guangzhou', 'China', 'Wholesale'),
('John', 'Smith', 'john.smith@email.com', '+1-555-1001', 'New York', 'USA', 'Retail'),
('Emma', 'Johnson', 'emma.j@email.com', '+1-555-1002', 'Los Angeles', 'USA', 'VIP'),
('Marie', 'Dubois', 'marie.d@email.com', '+33-6-1234-5678', 'Paris', 'France', 'Retail'),
('Hans', 'Mueller', 'hans.m@email.com', '+49-30-1234-5678', 'Berlin', 'Germany', 'Wholesale'),
('Yuki', 'Tanaka', 'yuki.t@email.com', '+81-3-1234-5678', 'Tokyo', 'Japan', 'Retail'),
('Ming', 'Chen', 'chen.ming@email.com', '+86-138-0000-0009', 'Shenzhen', 'China', 'VIP'),
('Xiao', 'Liu', 'liu.xiao@email.com', '+86-138-0000-0010', 'Chengdu', 'China', 'Retail');

-- ==================== 订单数据 ====================
-- 订单 1 - 已完成订单
INSERT INTO "Order" (OrderNumber, CustomerId, OrderDate, RequiredDate, ShippedDate, Status, 
    ShippingAddress, ShippingCity, ShippingCountry, Subtotal, Discount, Tax, ShippingCost, TotalAmount,
    PaymentMethod, PaymentStatus) VALUES 
('ORD-2024-0001', 1, '2024-01-15 10:30:00', '2024-01-20', '2024-01-17', 'Delivered',
    '朝阳区建国路 93 号', 'Beijing', 'China', 11498.00, 500.00, 110.00, 20.00, 11128.00,
    'CreditCard', 'Paid');

INSERT INTO OrderItem (OrderId, ProductId, UnitPrice, Quantity, Discount, Subtotal) VALUES 
(1, 1, 8999.00, 1, 0, 8999.00),
(1, 5, 2499.00, 1, 0, 2499.00),
(1, 13, 45.00, 2, 0, 90.00);

-- 订单 2 - 处理中订单
INSERT INTO "Order" (OrderNumber, CustomerId, OrderDate, RequiredDate, Status, 
    ShippingAddress, ShippingCity, ShippingCountry, Subtotal, Discount, Tax, ShippingCost, TotalAmount,
    PaymentMethod, PaymentStatus) VALUES 
('ORD-2024-0002', 2, '2024-02-10 14:20:00', '2024-02-15', 'Processing',
    '浦东新区陆家嘴环路 100 号', 'Shanghai', 'China', 19999.00, 0, 200.00, 0, 20199.00,
    'CreditCard', 'Paid');

INSERT INTO OrderItem (OrderId, ProductId, UnitPrice, Quantity, Discount, Subtotal) VALUES 
(2, 3, 19999.00, 1, 0, 19999.00);

-- 订单 3 - 待处理订单
INSERT INTO "Order" (OrderNumber, CustomerId, OrderDate, RequiredDate, Status, 
    ShippingAddress, ShippingCity, ShippingCountry, Subtotal, Discount, Tax, ShippingCost, TotalAmount,
    PaymentMethod, PaymentStatus) VALUES 
('ORD-2024-0003', 3, '2024-02-18 09:15:00', '2024-02-25', 'Pending',
    '天河区天河路 385 号', 'Guangzhou', 'China', 3596.00, 100.00, 35.00, 25.00, 3556.00,
    'PayPal', 'Unpaid');

INSERT INTO OrderItem (OrderId, ProductId, UnitPrice, Quantity, Discount, Subtotal) VALUES 
(3, 9, 299.00, 5, 50.00, 1445.00),
(3, 10, 599.00, 3, 50.00, 1747.00),
(3, 12, 699.00, 1, 0, 699.00);

-- 订单 4 - 已发货订单
INSERT INTO "Order" (OrderNumber, CustomerId, OrderDate, RequiredDate, ShippedDate, Status, 
    ShippingAddress, ShippingCity, ShippingCountry, Subtotal, Discount, Tax, ShippingCost, TotalAmount,
    PaymentMethod, PaymentStatus) VALUES 
('ORD-2024-0004', 4, '2024-02-05 16:45:00', '2024-02-12', '2024-02-08', 'Shipped',
    '123 Broadway Ave', 'New York', 'USA', 6999.00, 0, 70.00, 50.00, 7119.00,
    'CreditCard', 'Paid');

INSERT INTO OrderItem (OrderId, ProductId, UnitPrice, Quantity, Discount, Subtotal) VALUES 
(4, 2, 6999.00, 1, 0, 6999.00);

-- 订单 5 - 多商品订单
INSERT INTO "Order" (OrderNumber, CustomerId, OrderDate, RequiredDate, Status, 
    ShippingAddress, ShippingCity, ShippingCountry, Subtotal, Discount, Tax, ShippingCost, TotalAmount,
    PaymentMethod, PaymentStatus) VALUES 
('ORD-2024-0005', 5, '2024-02-12 11:00:00', '2024-02-18', 'Confirmed',
    '456 Sunset Blvd', 'Los Angeles', 'USA', 2846.00, 200.00, 26.50, 35.00, 2707.50,
    'PayPal', 'Paid');

INSERT INTO OrderItem (OrderId, ProductId, UnitPrice, Quantity, Discount, Subtotal) VALUES 
(5, 6, 1899.00, 1, 0, 1899.00),
(5, 14, 68.00, 3, 0, 204.00),
(5, 15, 128.00, 2, 0, 256.00),
(5, 19, 199.00, 2, 200.00, 198.00);

-- 订单 6 - 批发订单
INSERT INTO "Order" (OrderNumber, CustomerId, OrderDate, RequiredDate, Status, 
    ShippingAddress, ShippingCity, ShippingCountry, Subtotal, Discount, Tax, ShippingCost, TotalAmount,
    PaymentMethod, PaymentStatus) VALUES 
('ORD-2024-0006', 6, '2024-02-14 08:30:00', '2024-02-20', 'Pending',
    '789 Rue de Rivoli', 'Paris', 'France', 1596.00, 150.00, 14.50, 45.00, 1505.50,
    'BankTransfer', 'Unpaid');

INSERT INTO OrderItem (OrderId, ProductId, UnitPrice, Quantity, Discount, Subtotal) VALUES 
(6, 13, 45.00, 10, 50.00, 400.00),
(6, 14, 68.00, 5, 0, 340.00),
(6, 16, 58.00, 8, 0, 464.00),
(6, 17, 199.00, 2, 100.00, 298.00);

-- ==================== 库存变动日志 ====================
INSERT INTO InventoryLog (ProductId, ChangeType, Quantity, PreviousStock, NewStock, ReferenceType, ReferenceId, Notes) VALUES 
(1, 'Out', 1, 51, 50, 'Order', 1, '订单发货'),
(5, 'Out', 1, 101, 100, 'Order', 1, '订单发货'),
(13, 'Out', 2, 502, 500, 'Order', 1, '订单发货'),
(3, 'Out', 1, 21, 20, 'Order', 2, '订单发货'),
(2, 'Out', 1, 81, 80, 'Order', 4, '订单发货'),
(6, 'Out', 1, 151, 150, 'Order', 5, '订单发货'),
(14, 'Out', 3, 403, 400, 'Order', 5, '订单发货'),
(15, 'Out', 2, 202, 200, 'Order', 5, '订单发货'),
(19, 'Out', 2, 252, 250, 'Order', 5, '订单发货');
