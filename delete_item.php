<?php
// delete_item.php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$item_name = $data['item_name'];

$serverName = "localhost";
$username = "your_username";
$password = "your_password";
$database = "ShoppingListApp";

$conn = new mysqli($serverName, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM ShoppingList WHERE item_name = '$item_name'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['message' => 'Item deleted successfully']);
} else {
    echo json_encode(['message' => 'Error deleting item']);
}

$conn->close();
?>
