<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $bdate = $_POST['bdate'];

    // Check if avatar is provided
    if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatar = file_get_contents($_FILES['avatar']['tmp_name']); // Read the file content if avatar is provided
    } else {
        $avatar = null;
    }

    // Validate if the user is logged in
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        echo "User not logged in.";
        exit();
    }

    // Validate if any password field is filled
    if (!empty($new_password) || !empty($confirm_password) || !empty($current_password)) {
        // Validate password fields
        if ($new_password != $confirm_password) {
            header("Location: ../profile.php?case1=true");
            exit();
        }

        // Validate the current password
        $sql = "SELECT password FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!password_verify($current_password, $userData['password'])) {
            header("Location: ../profile.php?case2=true");
            exit();
        }

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    } else {
        // If password fields are empty, keep the current password
        $sql = "SELECT password FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password = $userData['password'];
    }

    // Prepare UPDATE query
    $sql = "UPDATE users SET email = :email, fname = :fname, lname = :lname, bdate = :bdate";

    // Add password update only if new password is provided
    if (!empty($new_password)) {
        $sql .= ", password = :password";
    }

    // Add avatar update only if new avatar is provided
    if ($avatar !== null) {
        $sql .= ", avatar = :avatar";
    }

    $sql .= " WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':bdate', $bdate);

    // Bind password parameter if new password is provided
    if (!empty($new_password)) {
        $stmt->bindParam(':password', $hashed_password);
    }

    // Bind avatar parameter if new avatar is provided
    if ($avatar !== null) {
        $stmt->bindParam(':avatar', $avatar, PDO::PARAM_LOB); // Bind the avatar data as LONGBLOB
    }

    $stmt->bindParam(':username', $username);

    // Execute the update query
    if ($stmt->execute()) {
        header("Location: ../profile.php?case3=true");
        exit();
    } else {
        echo "Error updating profile.";
    }
} else {
    echo "Invalid request.";
}
?>
