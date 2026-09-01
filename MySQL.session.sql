-- Create a database
CREATE DATABASE IF NOT EXISTS ShoppingListApp;

-- Use the ShoppingListApp database
USE ShoppingListApp;

-- Create a table to store shopping list items
CREATE TABLE IF NOT EXISTS ShoppingList (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    purchased TINYINT(1) NOT NULL DEFAULT 0
);
