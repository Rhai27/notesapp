<?php

function connectToDatabase() {
    try {
        $servername = getenv('DB_HOST') ?: 'localhost';        // Database host
        $username = getenv('DB_USERNAME') ?: 'root';           // Database username
        $password = getenv('DB_PASSWORD') ?: '';               // Database password
        $database = getenv('DB_DATABASE') ?: 'note_db';        // Database name
        $port = getenv('DB_PORT') ?: '3306';                   // Database port, default to 3306

        // Create a new PDO instance
        $conn = new PDO("mysql:host=$servername;dbname=$database;port=$port", $username, $password);
        // Set PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Connect to the database
$conn = connectToDatabase();

// You can now use $conn for executing queries
// Example: $result = $conn->query("SELECT * FROM your_table");

?>
