<?php
// get_items.php - returns all shopping list items so the frontend can
// restore state on page load.
header('Content-Type: application/json');
require __DIR__ . '/db.php';

$conn = get_db_connection();

$result = $conn->query("SELECT id, item_name, quantity, purchased FROM ShoppingList ORDER BY id ASC");

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = [
        'id' => (int) $row['id'],
        'item_name' => $row['item_name'],
        'quantity' => (int) $row['quantity'],
        'purchased' => (bool) $row['purchased'],
    ];
}

echo json_encode($items);

$conn->close();
