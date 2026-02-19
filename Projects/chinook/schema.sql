-- Chinook Database Schema
-- Modified to work with our low-code platform

PRAGMA foreign_keys = ON;

-- Create tables
CREATE TABLE [Album]
(
    [AlbumId] INTEGER PRIMARY KEY AUTOINCREMENT,
    [Title] NVARCHAR(160) NOT NULL,
    [ArtistId] INTEGER NOT NULL,
    FOREIGN KEY ([ArtistId]) REFERENCES [Artist] ([ArtistId])
);

CREATE TABLE [Artist]
(
    [ArtistId] INTEGER PRIMARY KEY AUTOINCREMENT,
    [Name] NVARCHAR(120)
);

CREATE TABLE [Customer]
(
    [CustomerId] INTEGER PRIMARY KEY AUTOINCREMENT,
    [FirstName] NVARCHAR(40) NOT NULL,
    [LastName] NVARCHAR(20) NOT NULL,
    [Company] NVARCHAR(80),
    [Address] NVARCHAR(70),
    [City] NVARCHAR(40),
    [State] NVARCHAR(40),
    [Country] NVARCHAR(40),
    [PostalCode] NVARCHAR(10),
    [Phone] NVARCHAR(24),
    [Fax] NVARCHAR(24),
    [Email] NVARCHAR(60) NOT NULL,
    [SupportRepId] INTEGER,
    FOREIGN KEY ([SupportRepId]) REFERENCES [Employee] ([EmployeeId])
);

CREATE TABLE [Employee]
(
    [EmployeeId] INTEGER PRIMARY KEY AUTOINCREMENT,
    [LastName] NVARCHAR(20) NOT NULL,
    [FirstName] NVARCHAR(20) NOT NULL,
    [Title] NVARCHAR(30),
    [ReportsTo] INTEGER,
    [BirthDate] DATETIME,
    [HireDate] DATETIME,
    [Address] NVARCHAR(70),
    [City] NVARCHAR(40),
    [State] NVARCHAR(40),
    [Country] NVARCHAR(40),
    [PostalCode] NVARCHAR(10),
    [Phone] NVARCHAR(24),
    [Fax] NVARCHAR(24),
    [Email] NVARCHAR(60),
    FOREIGN KEY ([ReportsTo]) REFERENCES [Employee] ([EmployeeId])
);

CREATE TABLE [Genre]
(
    [GenreId] INTEGER PRIMARY KEY AUTOINCREMENT,
    [Name] NVARCHAR(120)
);

CREATE TABLE [Invoice]
(
    [InvoiceId] INTEGER PRIMARY KEY AUTOINCREMENT,
    [CustomerId] INTEGER NOT NULL,
    [InvoiceDate] DATETIME NOT NULL,
    [BillingAddress] NVARCHAR(70),
    [BillingCity] NVARCHAR(40),
    [BillingState] NVARCHAR(40),
    [BillingCountry] NVARCHAR(40),
    [BillingPostalCode] NVARCHAR(10),
    [Total] NUMERIC(10,2) NOT NULL,
    FOREIGN KEY ([CustomerId]) REFERENCES [Customer] ([CustomerId])
);

CREATE TABLE [InvoiceLine]
(
    [InvoiceLineId] INTEGER PRIMARY KEY AUTOINCREMENT,
    [InvoiceId] INTEGER NOT NULL,
    [TrackId] INTEGER NOT NULL,
    [UnitPrice] NUMERIC(10,2) NOT NULL,
    [Quantity] INTEGER NOT NULL,
    FOREIGN KEY ([InvoiceId]) REFERENCES [Invoice] ([InvoiceId]),
    FOREIGN KEY ([TrackId]) REFERENCES [Track] ([TrackId])
);

CREATE TABLE [MediaType]
(
    [MediaTypeId] INTEGER PRIMARY KEY AUTOINCREMENT,
    [Name] NVARCHAR(120)
);

CREATE TABLE [Playlist]
(
    [PlaylistId] INTEGER PRIMARY KEY AUTOINCREMENT,
    [Name] NVARCHAR(120)
);

CREATE TABLE [PlaylistTrack]
(
    [PlaylistId] INTEGER NOT NULL,
    [TrackId] INTEGER NOT NULL,
    PRIMARY KEY ([PlaylistId], [TrackId]),
    FOREIGN KEY ([PlaylistId]) REFERENCES [Playlist] ([PlaylistId]),
    FOREIGN KEY ([TrackId]) REFERENCES [Track] ([TrackId])
);

CREATE TABLE [Track]
(
    [TrackId] INTEGER PRIMARY KEY AUTOINCREMENT,
    [Name] NVARCHAR(200) NOT NULL,
    [AlbumId] INTEGER,
    [MediaTypeId] INTEGER NOT NULL,
    [GenreId] INTEGER,
    [Composer] NVARCHAR(220),
    [Milliseconds] INTEGER NOT NULL,
    [Bytes] INTEGER,
    [UnitPrice] NUMERIC(10,2) NOT NULL,
    FOREIGN KEY ([AlbumId]) REFERENCES [Album] ([AlbumId]),
    FOREIGN KEY ([GenreId]) REFERENCES [Genre] ([GenreId]),
    FOREIGN KEY ([MediaTypeId]) REFERENCES [MediaType] ([MediaTypeId])
);

-- Insert sample data would go here