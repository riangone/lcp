-- 电商订单系统数据库架构

-- ==================== 分类表 ====================
CREATE TABLE IF NOT EXISTS Category (
    CategoryId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE,
    Description TEXT,
    ParentCategoryId INTEGER,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ParentCategoryId) REFERENCES Category(CategoryId)
);

-- ==================== 供应商表 ====================
CREATE TABLE IF NOT EXISTS Supplier (
    SupplierId INTEGER PRIMARY KEY AUTOINCREMENT,
    CompanyName TEXT NOT NULL,
    ContactName TEXT,
    ContactEmail TEXT,
    ContactPhone TEXT,
    Address TEXT,
    City TEXT,
    Country TEXT,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ==================== 产品表 ====================
CREATE TABLE IF NOT EXISTS Product (
    ProductId INTEGER PRIMARY KEY AUTOINCREMENT,
    ProductName TEXT NOT NULL,
    Description TEXT,
    CategoryId INTEGER,
    SupplierId INTEGER,
    UnitPrice DECIMAL(10,2) NOT NULL DEFAULT 0,
    StockQuantity INTEGER NOT NULL DEFAULT 0,
    ReorderLevel INTEGER DEFAULT 10,
    Discontinued INTEGER DEFAULT 0,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (CategoryId) REFERENCES Category(CategoryId),
    FOREIGN KEY (SupplierId) REFERENCES Supplier(SupplierId)
);

-- ==================== 客户表 ====================
CREATE TABLE IF NOT EXISTS Customer (
    CustomerId INTEGER PRIMARY KEY AUTOINCREMENT,
    FirstName TEXT NOT NULL,
    LastName TEXT NOT NULL,
    Email TEXT NOT NULL UNIQUE,
    Phone TEXT,
    Address TEXT,
    City TEXT,
    State TEXT,
    Country TEXT DEFAULT 'China',
    PostalCode TEXT,
    CustomerType TEXT DEFAULT 'Retail',  -- Retail, Wholesale, VIP
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ==================== 订单表 ====================
CREATE TABLE IF NOT EXISTS "Order" (
    OrderId INTEGER PRIMARY KEY AUTOINCREMENT,
    OrderNumber TEXT NOT NULL UNIQUE,
    CustomerId INTEGER NOT NULL,
    OrderDate TEXT DEFAULT CURRENT_TIMESTAMP,
    RequiredDate TEXT,
    ShippedDate TEXT,
    Status TEXT DEFAULT 'Pending',  -- Pending, Confirmed, Processing, Shipped, Delivered, Cancelled
    ShippingAddress TEXT,
    ShippingCity TEXT,
    ShippingCountry TEXT DEFAULT 'China',
    ShippingPostalCode TEXT,
    Subtotal DECIMAL(10,2) DEFAULT 0,
    Discount DECIMAL(10,2) DEFAULT 0,
    Tax DECIMAL(10,2) DEFAULT 0,
    ShippingCost DECIMAL(10,2) DEFAULT 0,
    TotalAmount DECIMAL(10,2) DEFAULT 0,
    PaymentMethod TEXT,  -- CreditCard, PayPal, BankTransfer, COD
    PaymentStatus TEXT DEFAULT 'Unpaid',  -- Unpaid, Paid, Refunded
    Notes TEXT,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (CustomerId) REFERENCES Customer(CustomerId)
);

-- ==================== 订单项表 ====================
CREATE TABLE IF NOT EXISTS OrderItem (
    OrderItemId INTEGER PRIMARY KEY AUTOINCREMENT,
    OrderId INTEGER NOT NULL,
    ProductId INTEGER NOT NULL,
    UnitPrice DECIMAL(10,2) NOT NULL,
    Quantity INTEGER NOT NULL DEFAULT 1,
    Discount DECIMAL(10,2) DEFAULT 0,
    Subtotal DECIMAL(10,2) NOT NULL,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (OrderId) REFERENCES "Order"(OrderId) ON DELETE CASCADE,
    FOREIGN KEY (ProductId) REFERENCES Product(ProductId)
);

-- ==================== 库存变动日志表 ====================
CREATE TABLE IF NOT EXISTS InventoryLog (
    LogId INTEGER PRIMARY KEY AUTOINCREMENT,
    ProductId INTEGER NOT NULL,
    ChangeType TEXT NOT NULL,  -- In, Out, Adjustment
    Quantity INTEGER NOT NULL,
    PreviousStock INTEGER,
    NewStock INTEGER,
    ReferenceType TEXT,  -- Order, Return, Adjustment
    ReferenceId INTEGER,
    Notes TEXT,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    CreatedBy TEXT,
    FOREIGN KEY (ProductId) REFERENCES Product(ProductId)
);

-- ==================== 索引 ====================
CREATE INDEX IF NOT EXISTS idx_product_category ON Product(CategoryId);
CREATE INDEX IF NOT EXISTS idx_product_supplier ON Product(SupplierId);
CREATE INDEX IF NOT EXISTS idx_product_name ON Product(ProductName);
CREATE INDEX IF NOT EXISTS idx_order_customer ON "Order"(CustomerId);
CREATE INDEX IF NOT EXISTS idx_order_status ON "Order"(Status);
CREATE INDEX IF NOT EXISTS idx_order_date ON "Order"(OrderDate);
CREATE INDEX IF NOT EXISTS idx_order_number ON "Order"(OrderNumber);
CREATE INDEX IF NOT EXISTS idx_order_item_order ON OrderItem(OrderId);
CREATE INDEX IF NOT EXISTS idx_order_item_product ON OrderItem(ProductId);
CREATE INDEX IF NOT EXISTS idx_customer_email ON Customer(Email);
CREATE INDEX IF NOT EXISTS idx_customer_country ON Customer(Country);

-- ==================== 用户表 ====================
CREATE TABLE IF NOT EXISTS "User" (
    UserId INTEGER PRIMARY KEY AUTOINCREMENT,
    Username TEXT NOT NULL UNIQUE,
    Email TEXT NOT NULL UNIQUE,
    PasswordHash TEXT NOT NULL,
    DisplayName TEXT,
    Role TEXT DEFAULT 'User',
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

CREATE INDEX IF NOT EXISTS idx_user_username ON "User"(Username);
CREATE INDEX IF NOT EXISTS idx_user_email ON "User"(Email);
CREATE INDEX IF NOT EXISTS idx_user_role ON "User"(Role);

-- 默认管理员账户（密码：admin123）
INSERT OR IGNORE INTO "User" (Username, Email, PasswordHash, DisplayName, Role) VALUES 
('admin', 'admin@ecommerce.com', '$2a$11$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', '系统管理员', 'Admin');

