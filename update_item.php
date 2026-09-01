<?php
// update_item.php
header('Content-Type: application/json');
require __DIR__ . '/db.php';

$data = json_decode(file_get_contents("php://input"), true);

$item_name = trim($data['item_name'] ?? '');
$purchased = !empty($data['purchased']) ? 1 : 0;

if ($item_name === '') {
    http_response_code(400);
    echo json_encode(['message' => 'item_name is required']);
    exit;
}

$conn = get_db_connection();

$stmt = $conn->prepare("UPDATE ShoppingList SET purchased = ? WHERE item_name = ?");
$stmt->bind_param("is", $purchased, $item_name);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Item updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Error updating item']);
}

$stmt->close();
$conn->close();
