<?php
session_start();

// Include the function to connect to the database
require_once('db_connection.php');

// Function to sanitize input data
function sanitizeData($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = sanitizeData($_POST['username']);
    $password = sanitizeData($_POST['password']);

    try {
        // Prepare SQL statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row) {
            // Username found, check password
            $hashedPassword = $row['password'];
            
            // Verify password
            if (password_verify($password, $hashedPassword)) {
                // Password verified
                $_SESSION['username'] = $username;
                header("Location: ../home.php");
                exit();
            } else {
                // Invalid password
                header("Location: ../index.php?case1=true");
                exit();
            }
        } else {
            // Username not found
            header("Location: ../index.php?case2=true");
            exit();
        }
    } catch(PDOException $e) {
        // Handle database error
        echo "Error: " . $e->getMessage();
    }

    // Close database connection
    $conn = null;
}
?>
