<?php
session_start();
include 'php/db_connection.php';

// Function to check session status
function checkSession() {
    // Check if session variables are set
    if (!isset($_SESSION['username'])) {
        // Session is not set, redirect to login page
        header("Location: ../index.php");
        exit();
    }
}

// Call the function on every page where authentication is required
checkSession();

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css">
    <title>Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <?php
        $sql = "SELECT username, email, bdate, fname, lname FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
    ?>

<div class="padedge">
                <?php
                    try {
                        // Retrieve profile image blob from database
                        $stmt = $conn->prepare("SELECT avatar FROM users WHERE username = ?");
                        $stmt->execute([$username]);
                        $user = $stmt->fetch();

                        // Check if profile image exists and render it
                        if ($user && $user['avatar']) {
                            // Render profile image
                            echo '<img src="data:image/jpeg;base64,'.base64_encode($user['avatar']).'" class="avatar1" alt="Profile Image">';
                        } else {
                            // Render default profile image if no image found
                            echo '<img src="icon/default-avatar.png" class="avatar" alt="Profile Image">';
                        }
                    } catch (PDOException $e) {
                        // Error handling for database queries
                        echo "Error fetching profile image: " . $e->getMessage();
                    }
                ?>

                <div class="username1"><?php echo $username; ?></div>
                <div class="edgebtncon">
                    <a href ="home.php" class="edgebtn">Home</a>
                    <a href="php/logout.php" class="edgebtn">Logout</a>
                </div>
            </div>

    <div class="container">

        <?php
                if(isset($_GET['case1']) && $_GET['case1'] == 'true') {
                    echo '<div class="prompt">New password does not match</div>';
                }

                if(isset($_GET['case2']) && $_GET['case2'] == 'true') {
                    echo '<div class="prompt">Current password is incorrect</div>';
                }

                if(isset($_GET['case3']) && $_GET['case3'] == 'true') {
                    echo '<div class="promptg">Profile updated successfully</div>';
                }
        ?>

        

        <div class="plate">
            <div class="blackplate">
                <div class="backcon">
                        <a href="home.php" class="backarrow"></a>
                </div>
                <div class="imgcontainer">
                    <?php
                        try {
                            // Retrieve profile image blob from database
                            $stmt = $conn->prepare("SELECT avatar FROM users WHERE username = ?");
                            $stmt->execute([$username]);
                            $user = $stmt->fetch();

                            // Check if profile image exists and render it
                            if ($user && $user['avatar']) {
                                // Render profile image
                                echo '<img src="data:image/jpeg;base64,'.base64_encode($user['avatar']).'" class="avatar" alt="Profile Image">';
                                echo '<span class="username">' . $data['username'] . '</span>';
                            } else {
                                // Render default profile image if no image found
                                echo '<img src="icon/default-avatar.png" class="avatar" alt="Profile Image">';
                                echo '<span class="username">' . $data['username'] . '</span>';

                            }
                        } catch (PDOException $e) {
                            // Error handling for database queries
                            echo "Error fetching profile image: " . $e->getMessage();
                        }
                    ?>
                </div>
                <div class="backcon"></div>
            </div>
            
            <div class="formcontainer">
                <form action="php/profile_process.php" method="POST" enctype="multipart/form-data">
                    <div class="frm">

                        <input type="hidden" name="username" value="<?php echo $data['username']; ?>">
                        
                        <div class="leftfrm">
                            <input type="email" name="email" placeholder="Email" class="nptt" value="<?php echo $data['email']; ?>" required>
                            <input type="text" name="fname" placeholder="First Name" class="npt" value="<?php echo $data['fname']; ?>">
                            <input type="text" name="lname" placeholder="Last Name" class="npt" value="<?php echo $data['lname']; ?>">
                            <input type="date" name="bdate" class="nptb" value="<?php echo $data['bdate']; ?>">
                            <span class="inf">Suggestion: Click the calendar icon.</span>
                        </div>

                        <div class="rightfrm">
                            <input type="password" name="current_password" placeholder="Current Password" class="nptt">
                            <input type="password" name="new_password" placeholder="New Password" class="npt">
                            <input type="password" name="confirm_password" placeholder="Confirm New Password" class="npt">
                            <input type="file" id="avatar" name="avatar" accept="image/*" class="nptb">
                            <span class="inf">Click "Choose File" button to change your avatar.</span>
                        </div>
                    </div>
                    
                    <div class="btncontainer">
                        <input type="submit" value="Save Changes" id="btn" class="submit">
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <?php
    } else {
        echo "<p>User not found.</p>";
    }
    ?>

    <script src="js/profile.js"></script>
</body>
</html>
