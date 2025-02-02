-- See database Stuff --
-- Navigate to dir: cd html/A1/database --
-- Access: sqlite3 database.sqlite --
-- See tables: .tables --
-- View Headers: .headers on --
-- View Columns: .mode column -- 
-- Select stuff: SELECT * FROM reviews LIMIT 10; --
-- View column names: PRAGMA table_info(reviews); --


-- Drop tables if they already exist --
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS items;

-- Both tables have SQL "sanitisation" --

-- Create items table --
CREATE TABLE items (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL CHECK(length(name) >= 3),
    manufacturer TEXT NOT NULL CHECK(length(manufacturer) >= 3),
    price REAL NOT NULL CHECK(price >= 0),
    image TEXT NOT NULL CHECK(length(image) >= 3)
);

-- Create reviews table --
CREATE TABLE reviews (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    item_id INTEGER NOT NULL,
    username TEXT NOT NULL CHECK(length(username) >= 3),
    rating INTEGER NOT NULL CHECK(rating >= 1 AND rating <= 5),
    review_text TEXT NOT NULL CHECK(length(review_text) >= 5),
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

-- Insert sample data into items table
INSERT INTO items (name, manufacturer, price, image) VALUES ('Model A', 'Manufacturer X', 1200, 'images/feature1.png');
INSERT INTO items (name, manufacturer, price, image) VALUES ('Model B', 'Manufacturer Y', 50, 'images/feature2.png');
INSERT INTO items (name, manufacturer, price, image) VALUES ('Model C', 'Manufacturer Z', 700, 'images/feature3.png');

-- Insert sample data into reviews table
INSERT INTO reviews (item_id, username, rating, review_text) VALUES 
    (1, 'User1', 5, 'Amazing!'),
    (1, 'User2', 4, 'Wow'),
    (2, 'User3', 3, 'Average mouse'),
    (3, 'User4', 5, 'Good graphics card');

