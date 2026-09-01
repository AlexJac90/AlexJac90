<?php
// add_item.php
header('Content-Type: application/json');
require __DIR__ . '/db.php';

$data = json_decode(file_get_contents("php://input"), true);

$item_name = trim($data['item_name'] ?? '');
$quantity = (int) ($data['quantity'] ?? 0);
$purchased = !empty($data['purchased']) ? 1 : 0;

if ($item_name === '' || $quantity <= 0) {
    http_response_code(400);
    echo json_encode(['message' => 'item_name and a positive quantity are required']);
    exit;
}

$conn = get_db_connection();

$stmt = $conn->prepare("INSERT INTO ShoppingList (item_name, quantity, purchased) VALUES (?, ?, ?)");
$stmt->bind_param("sii", $item_name, $quantity, $purchased);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Item added successfully', 'id' => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Error adding item']);
}

$stmt->close();
$conn->close();
