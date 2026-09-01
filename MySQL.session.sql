-- Create a database
CREATE DATABASE ShoppingListApp;

-- Use the ShoppingListApp database
USE ShoppingListApp;

-- Create a table to store shopping list items
CREATE TABLE ShoppingList (
    id INT IDENTITY(1,1) PRIMARY KEY,
    item_name VARCHAR(255),
    quantity INT,
    purchased BIT
);
