<?php
// update_item.php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$item_name = $data['item_name'];
$purchased = $data['purchased'];

$serverName = "localhost";
$username = "your_username";
$password = "your_password";
$database = "ShoppingListApp";

$conn = new mysqli($serverName, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE ShoppingList SET purchased = '$purchased' WHERE item_name = '$item_name'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['message' => 'Item updated successfully']);
} else {
    echo json_encode(['message' => 'Error updating item']);
}

$conn->close();
?>
