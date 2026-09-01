<?php
// db.php - shared database connection helper.
// Connection settings are read from environment variables so the same code
// works in local development and in the Cloud Agent environment without
// committing credentials to source control.

function get_db_connection(): mysqli
{
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $user = getenv('DB_USER') ?: 'shopping';
    $password = getenv('DB_PASSWORD') ?: 'shopping';
    $database = getenv('DB_NAME') ?: 'ShoppingListApp';
    $port = (int) (getenv('DB_PORT') ?: 3306);

    $conn = new mysqli($host, $user, $password, $database, $port);

    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['message' => 'Database connection failed: ' . $conn->connect_error]);
        exit;
    }

    $conn->set_charset('utf8mb4');

    return $conn;
}
