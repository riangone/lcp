CREATE TABLE IF NOT EXISTS Product (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL,
    Price REAL NOT NULL,
    Category TEXT
);

CREATE TABLE IF NOT EXISTS Customer (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL,
    Email TEXT NOT NULL UNIQUE,
    Phone TEXT
);

CREATE TABLE IF NOT EXISTS Users (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    Email TEXT NOT NULL UNIQUE,
    Password TEXT NOT NULL,
    Role TEXT
);

CREATE TABLE IF NOT EXISTS AuditLog (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    UserId INTEGER,
    Action TEXT,
    TableName TEXT,
    Timestamp TEXT
);

-- Insert test data if not exists
INSERT OR IGNORE INTO Product (Id, Name, Price, Category) VALUES 
(1, 'iPhone 15', 999.99, 'electronics'),
(2, 'MacBook Pro', 1999.99, 'electronics'),
(3, 'iPad Air', 599.99, 'electronics'),
(4, 'Clean Code', 49.99, 'book'),
(5, 'Organic Apple', 2.99, 'food');

INSERT OR IGNORE INTO Customer (Id, Name, Email, Phone) VALUES
(1, 'John Smith', 'john@example.com', '+1-555-0101'),
(2, 'Jane Doe', 'jane@example.com', '+1-555-0102'),
(3, 'Bob Johnson', 'bob@example.com', '+1-555-0103');

INSERT OR IGNORE INTO Users (Id, Email, Password, Role) VALUES
(1, 'admin@example.com', 'admin', 'Admin');
