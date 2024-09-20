To add a default value for `lname` along with `fname`, you can modify the registration script similarly to how you handled `fname`. Here's the updated code:

### Updated Registration Code

```php
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
    
    // Default birthdate if not provided
    $bdate = isset($_POST['bdate']) ? sanitizeData($_POST['bdate']) : '2000-01-01';

    // Default fname and lname if not provided
    $fname = isset($_POST['fname']) ? sanitizeData($_POST['fname']) : 'Guest';
    $lname = isset($_POST['lname']) ? sanitizeData($_POST['lname']) : 'User';  // Default value for lname

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
        $sql = "INSERT INTO users (email, username, password, bdate, fname, lname) VALUES (?, ?, ?, ?, ?, ?)";  // Added lname
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email, $username, $hashedPassword, $bdate, $fname, $lname]);  // Added lname
        header("Location: ../index.php?registered=true");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close database connection
    $conn = null;
}

?>
```

### Key Changes:
- Added a line to set a default value for `lname`, similar to how you did for `fname`. If `lname` is not provided, it defaults to `'User'`.
- Updated the SQL `INSERT` statement to include `lname` as well.
- Made sure to pass `lname` to the execute function.

With these changes, your registration process will now handle both first and last names, providing default values if they are not supplied by the user.