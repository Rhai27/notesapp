<?php

function connectToDatabase($servername, $username, $password, $database) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // Set PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Database credentials
$servername = "localhost"; // Change this to your database server name
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$database = "note_db"; // Change this to your database name

// Connect to the database
$conn = connectToDatabase($servername, $username, $password, $database);

// You can now use $conn for executing queries
// Example: $result = $conn->query("SELECT * FROM your_table");

?>
