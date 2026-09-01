<?php
// add_item.php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$item_name = $data['item_name'];
$quantity = $data['quantity'];
$purchased = $data['purchased'];

$serverName = "localhost";
$username = "your_username";
$password = "your_password";
$database = "ShoppingListApp";

$conn = new mysqli($serverName, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO ShoppingList (item_name, quantity, purchased) VALUES ('$item_name', $quantity, '$purchased')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['message' => 'Item added successfully']);
} else {
    echo json_encode(['message' => 'Error adding item']);
}

$conn->close();
?>
