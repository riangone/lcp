-- CRM 客户关系管理系统数据库架构

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
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_user_username ON "User"(Username);
CREATE INDEX IF NOT EXISTS idx_user_email ON "User"(Email);

-- ==================== 客户表 ====================
CREATE TABLE IF NOT EXISTS Customer (
    CustomerId INTEGER PRIMARY KEY AUTOINCREMENT,
    CustomerNo TEXT NOT NULL UNIQUE,
    CompanyName TEXT NOT NULL,
    ContactPerson TEXT,
    Industry TEXT,  -- Technology, Finance, Healthcare, Retail, Manufacturing, Other
    Scale TEXT,     -- Small, Medium, Large, Enterprise
    Source TEXT,    -- Website, Referral, TradeShow, ColdCall, SocialMedia, Other
    Level TEXT,     -- Hot, Warm, Cold
    Website TEXT,
    Email TEXT,
    Phone TEXT,
    Fax TEXT,
    Address TEXT,
    City TEXT,
    State TEXT,
    Country TEXT DEFAULT 'China',
    PostalCode TEXT,
    Description TEXT,
    OwnerId INTEGER,  -- 负责人
    Status TEXT DEFAULT 'Active',  -- Active, Inactive, Lost
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    CreatedBy INTEGER,
    UpdatedBy INTEGER,
    FOREIGN KEY (OwnerId) REFERENCES "User"(UserId)
);

CREATE INDEX IF NOT EXISTS idx_customer_company ON Customer(CompanyName);
CREATE INDEX IF NOT EXISTS idx_customer_owner ON Customer(OwnerId);
CREATE INDEX IF NOT EXISTS idx_customer_status ON Customer(Status);
CREATE INDEX IF NOT EXISTS idx_customer_level ON Customer(Level);

-- ==================== 联系人表 ====================
CREATE TABLE IF NOT EXISTS Contact (
    ContactId INTEGER PRIMARY KEY AUTOINCREMENT,
    CustomerId INTEGER NOT NULL,
    FirstName TEXT NOT NULL,
    LastName TEXT NOT NULL,
    FullName TEXT GENERATED ALWAYS AS (FirstName || ' ' || LastName) STORED,
    Title TEXT,  -- 职位
    Department TEXT,
    Email TEXT,
    Phone TEXT,
    Mobile TEXT,
    Fax TEXT,
    Address TEXT,
    IsPrimary INTEGER DEFAULT 0,  -- 是否主要联系人
    Description TEXT,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (CustomerId) REFERENCES Customer(CustomerId) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_contact_customer ON Contact(CustomerId);
CREATE INDEX IF NOT EXISTS idx_contact_name ON Contact(LastName, FirstName);

-- ==================== 销售机会表 ====================
CREATE TABLE IF NOT EXISTS Opportunity (
    OpportunityId INTEGER PRIMARY KEY AUTOINCREMENT,
    OpportunityNo TEXT NOT NULL UNIQUE,
    CustomerId INTEGER NOT NULL,
    ContactId INTEGER,
    Title TEXT NOT NULL,
    Description TEXT,
    Stage TEXT DEFAULT 'Prospecting',  -- Prospecting, Qualification, Proposal, Negotiation, ClosedWon, ClosedLost
    Probability INTEGER DEFAULT 10,  -- 成功概率 0-100
    Amount DECIMAL(15,2),  -- 预计金额
    ExpectedCloseDate TEXT,
    ActualCloseDate TEXT,
    CloseReason TEXT,  -- 成交/失败原因
    Competitor TEXT,
    NextStep TEXT,
    NextStepDate TEXT,
    OwnerId INTEGER,  -- 负责人
    Status TEXT DEFAULT 'Open',  -- Open, Won, Lost, Closed
    Priority TEXT DEFAULT 'Medium',  -- Low, Medium, High, Urgent
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    CreatedBy INTEGER,
    UpdatedBy INTEGER,
    FOREIGN KEY (CustomerId) REFERENCES Customer(CustomerId),
    FOREIGN KEY (ContactId) REFERENCES Contact(ContactId),
    FOREIGN KEY (OwnerId) REFERENCES "User"(UserId)
);

CREATE INDEX IF NOT EXISTS idx_opportunity_customer ON Opportunity(CustomerId);
CREATE INDEX IF NOT EXISTS idx_opportunity_stage ON Opportunity(Stage);
CREATE INDEX IF NOT EXISTS idx_opportunity_owner ON Opportunity(OwnerId);
CREATE INDEX IF NOT EXISTS idx_opportunity_status ON Opportunity(Status);

-- ==================== 产品表 ====================
CREATE TABLE IF NOT EXISTS Product (
    ProductId INTEGER PRIMARY KEY AUTOINCREMENT,
    ProductNo TEXT NOT NULL UNIQUE,
    ProductName TEXT NOT NULL,
    Category TEXT,
    Specification TEXT,
    Unit TEXT DEFAULT 'PCS',
    UnitPrice DECIMAL(15,2) NOT NULL DEFAULT 0,
    CostPrice DECIMAL(15,2),
    StockQuantity INTEGER DEFAULT 0,
    ReorderLevel INTEGER DEFAULT 10,
    Supplier TEXT,
    Description TEXT,
    IsActive INTEGER DEFAULT 1,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_product_category ON Product(Category);
CREATE INDEX IF NOT EXISTS idx_product_name ON Product(ProductName);

-- ==================== 报价表 ====================
CREATE TABLE IF NOT EXISTS Quote (
    QuoteId INTEGER PRIMARY KEY AUTOINCREMENT,
    QuoteNo TEXT NOT NULL UNIQUE,
    CustomerId INTEGER NOT NULL,
    ContactId INTEGER,
    OpportunityId INTEGER,
    QuoteDate TEXT DEFAULT CURRENT_TIMESTAMP,
    ExpiryDate TEXT,
    Status TEXT DEFAULT 'Draft',  -- Draft, Sent, Accepted, Rejected, Expired
    Subtotal DECIMAL(15,2) DEFAULT 0,
    Discount DECIMAL(15,2) DEFAULT 0,
    Tax DECIMAL(15,2) DEFAULT 0,
    TotalAmount DECIMAL(15,2) DEFAULT 0,
    Notes TEXT,
    Terms TEXT,
    OwnerId INTEGER,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    CreatedBy INTEGER,
    UpdatedBy INTEGER,
    FOREIGN KEY (CustomerId) REFERENCES Customer(CustomerId),
    FOREIGN KEY (ContactId) REFERENCES Contact(ContactId),
    FOREIGN KEY (OpportunityId) REFERENCES Opportunity(OpportunityId),
    FOREIGN KEY (OwnerId) REFERENCES "User"(UserId)
);

CREATE INDEX IF NOT EXISTS idx_quote_customer ON Quote(CustomerId);
CREATE INDEX IF NOT EXISTS idx_quote_status ON Quote(Status);
CREATE INDEX IF NOT EXISTS idx_quote_date ON Quote(QuoteDate);

-- ==================== 报价明细表 ====================
CREATE TABLE IF NOT EXISTS QuoteItem (
    QuoteItemId INTEGER PRIMARY KEY AUTOINCREMENT,
    QuoteId INTEGER NOT NULL,
    ProductId INTEGER NOT NULL,
    Quantity INTEGER NOT NULL DEFAULT 1,
    UnitPrice DECIMAL(15,2) NOT NULL,
    Discount DECIMAL(15,2) DEFAULT 0,
    Subtotal DECIMAL(15,2) NOT NULL,
    Notes TEXT,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (QuoteId) REFERENCES Quote(QuoteId) ON DELETE CASCADE,
    FOREIGN KEY (ProductId) REFERENCES Product(ProductId)
);

CREATE INDEX IF NOT EXISTS idx_quote_item_quote ON QuoteItem(QuoteId);
CREATE INDEX IF NOT EXISTS idx_quote_item_product ON QuoteItem(ProductId);

-- ==================== 订单表 ====================
CREATE TABLE IF NOT EXISTS "Order" (
    OrderId INTEGER PRIMARY KEY AUTOINCREMENT,
    OrderNo TEXT NOT NULL UNIQUE,
    CustomerId INTEGER NOT NULL,
    ContactId INTEGER,
    QuoteId INTEGER,
    OpportunityId INTEGER,
    OrderDate TEXT DEFAULT CURRENT_TIMESTAMP,
    RequiredDate TEXT,
    ShippedDate TEXT,
    Status TEXT DEFAULT 'Pending',  -- Pending, Confirmed, Processing, Shipped, Delivered, Cancelled
    Subtotal DECIMAL(15,2) DEFAULT 0,
    Discount DECIMAL(15,2) DEFAULT 0,
    Tax DECIMAL(15,2) DEFAULT 0,
    ShippingCost DECIMAL(15,2) DEFAULT 0,
    TotalAmount DECIMAL(15,2) DEFAULT 0,
    PaymentTerms TEXT,
    PaymentStatus TEXT DEFAULT 'Unpaid',  -- Unpaid, Partial, Paid, Refunded
    ShippingAddress TEXT,
    ShippingCity TEXT,
    ShippingCountry TEXT,
    Notes TEXT,
    OwnerId INTEGER,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    CreatedBy INTEGER,
    UpdatedBy INTEGER,
    FOREIGN KEY (CustomerId) REFERENCES Customer(CustomerId),
    FOREIGN KEY (ContactId) REFERENCES Contact(ContactId),
    FOREIGN KEY (QuoteId) REFERENCES Quote(QuoteId),
    FOREIGN KEY (OpportunityId) REFERENCES Opportunity(OpportunityId),
    FOREIGN KEY (OwnerId) REFERENCES "User"(UserId)
);

CREATE INDEX IF NOT EXISTS idx_order_customer ON "Order"(CustomerId);
CREATE INDEX IF NOT EXISTS idx_order_status ON "Order"(Status);
CREATE INDEX IF NOT EXISTS idx_order_date ON "Order"(OrderDate);
CREATE INDEX IF NOT EXISTS idx_order_payment ON "Order"(PaymentStatus);

-- ==================== 订单明细表 ====================
CREATE TABLE IF NOT EXISTS OrderItem (
    OrderItemId INTEGER PRIMARY KEY AUTOINCREMENT,
    OrderId INTEGER NOT NULL,
    ProductId INTEGER NOT NULL,
    Quantity INTEGER NOT NULL DEFAULT 1,
    UnitPrice DECIMAL(15,2) NOT NULL,
    Discount DECIMAL(15,2) DEFAULT 0,
    Subtotal DECIMAL(15,2) NOT NULL,
    Notes TEXT,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (OrderId) REFERENCES "Order"(OrderId) ON DELETE CASCADE,
    FOREIGN KEY (ProductId) REFERENCES Product(ProductId)
);

CREATE INDEX IF NOT EXISTS idx_order_item_order ON OrderItem(OrderId);
CREATE INDEX IF NOT EXISTS idx_order_item_product ON OrderItem(ProductId);

-- ==================== 活动表 ====================
CREATE TABLE IF NOT EXISTS Activity (
    ActivityId INTEGER PRIMARY KEY AUTOINCREMENT,
    ActivityType TEXT NOT NULL,  -- Call, Meeting, Email, Task, Note, Other
    Subject TEXT NOT NULL,
    Description TEXT,
    RelatedTo TEXT,  -- Customer, Contact, Opportunity
    RelatedId INTEGER,
    Status TEXT DEFAULT 'Pending',  -- Pending, Completed, Cancelled
    Priority TEXT DEFAULT 'Normal',  -- Low, Normal, High, Urgent
    DueDate TEXT,
    CompletedDate TEXT,
    OwnerId INTEGER,
    CreatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (OwnerId) REFERENCES "User"(UserId)
);

CREATE INDEX IF NOT EXISTS idx_activity_type ON Activity(ActivityType);
CREATE INDEX IF NOT EXISTS idx_activity_status ON Activity(Status);
CREATE INDEX IF NOT EXISTS idx_activity_owner ON Activity(OwnerId);
CREATE INDEX IF NOT EXISTS idx_activity_duedate ON Activity(DueDate);

-- ==================== 视图：客户统计 ====================
CREATE VIEW IF NOT EXISTS CustomerStats AS
SELECT 
    c.CustomerId,
    c.CompanyName,
    c.Level,
    c.Status,
    COUNT(DISTINCT cont.ContactId) AS ContactCount,
    COUNT(DISTINCT opp.OpportunityId) AS OpportunityCount,
    COALESCE(SUM(opp.Amount), 0) AS TotalOpportunityValue,
    COUNT(DISTINCT o.OrderId) AS OrderCount,
    COALESCE(SUM(o.TotalAmount), 0) AS TotalOrderValue
FROM Customer c
LEFT JOIN Contact cont ON cont.CustomerId = c.CustomerId
LEFT JOIN Opportunity opp ON opp.CustomerId = c.CustomerId AND opp.Status != 'Lost'
LEFT JOIN "Order" o ON o.CustomerId = c.CustomerId
GROUP BY c.CustomerId, c.CompanyName, c.Level, c.Status;

-- ==================== 视图：销售漏斗 ====================
CREATE VIEW IF NOT EXISTS SalesFunnel AS
SELECT 
    Stage,
    COUNT(*) AS OpportunityCount,
    COALESCE(SUM(Amount), 0) AS TotalValue,
    AVG(Probability) AS AvgProbability
FROM Opportunity
WHERE Status = 'Open'
GROUP BY Stage
ORDER BY 
    CASE Stage
        WHEN 'Prospecting' THEN 1
        WHEN 'Qualification' THEN 2
        WHEN 'Proposal' THEN 3
        WHEN 'Negotiation' THEN 4
        ELSE 5
    END;

-- ==================== 视图：订单客户视图 ====================
CREATE VIEW IF NOT EXISTS OrderWithCustomer AS
SELECT 
    o.OrderId,
    o.OrderNo,
    o.OrderDate,
    o.Status,
    o.TotalAmount,
    o.PaymentStatus,
    c.CompanyName AS CustomerName,
    c.ContactPerson,
    c.Phone AS CustomerPhone,
    c.Email AS CustomerEmail,
    cont.FullName AS ContactName,
    cont.Phone AS ContactPhone
FROM "Order" o
JOIN Customer c ON c.CustomerId = o.CustomerId
LEFT JOIN Contact cont ON cont.ContactId = o.ContactId;
