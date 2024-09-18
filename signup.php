<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/signup.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="plate">
            <div class="formcontainer">
                <div class="label1">
                    Create Account
                </div>
                <div class="label2">
                    Please fill the input below.
                </div>
                <form action="php/signup_process.php" method="POST" class="fillform">
                    <input type="email" class="npt" name="email" placeholder="Email" required>
                    <input type="text" class="npt" name="username" placeholder="Username" required>
                    <?php
                        if(isset($_GET['case1']) && $_GET['case1'] == 'true') {
                            echo '<div class="prompt">Username already taken.</div>';
                        }

                        if(isset($_GET['case3']) && $_GET['case3'] == 'true') {
                            echo '<div class="prompt">Username already taken.</div>';
                        }
                    ?>
                    <input type="password" class="npt" name="password" placeholder="Password" required>
                    <input type="password" class="npt" name="confirm_password" placeholder="Confirm Password" required>
                    <?php
                            if(isset($_GET['case2']) && $_GET['case2'] == 'true') {
                                echo '<div class="prompt">Password does not match.</div>';
                            }

                            if(isset($_GET['case3']) && $_GET['case3'] == 'true') {
                                echo '<div class="prompt">Password does not match.</div>';
                            }
                    ?>
                    <input type="submit" class="submit" value="Sign Up">
                </form>
                <div class="label3">
                    Already have an account? <a href="index.php" class="link1">Sign in</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
