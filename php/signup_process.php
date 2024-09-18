<?php

// Include the function to connect to the database
require_once('db_connection.php');

// Function to sanitize input data
function sanitizeData($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Function to validate username
function validateUsername($username, $conn) {
    $sql = "SELECT COUNT(*) AS count FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['count'] > 0;
}

// Function to hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = sanitizeData($_POST['email']);
    $username = sanitizeData($_POST['username']);
    $password = sanitizeData($_POST['password']);
    $confirmPassword = sanitizeData($_POST['confirm_password']);

    // Check if both username and password are invalid
    if (validateUsername($username, $conn) && $password !== $confirmPassword) {
        header("Location: ../signup.php?case3=true");
        exit();
    } 
    // Check if username is invalid
    else if (validateUsername($username, $conn)) {
        header("Location: ../signup.php?case1=true");
        exit();
    }
    // Check if password and confirm password match
    else if ($password !== $confirmPassword) {
        header("Location: ../signup.php?case2=true");
        exit();
    }

    // Hash password
    $hashedPassword = hashPassword($password);
    
    try {
        // Insert user into database using prepared statement
        $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email, $username, $hashedPassword]);
        header("Location: ../index.php?registered=true");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close database connection
    $conn = null;
}

?>