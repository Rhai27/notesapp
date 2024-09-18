<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">

        <?php
                if(isset($_GET['case1']) && $_GET['case1'] == 'true') {
                    echo '<div class="prompt">Invalid credentials</div>';
                }

                if(isset($_GET['case2']) && $_GET['case2'] == 'true') {
                    echo '<div class="prompt">Invalid credentials.</div>';
                }
        ?>

        <?php
                if(isset($_GET['registered']) && $_GET['registered'] == 'true') {
                    echo '<div class="promptg">Account registered successfully.</div>';
                }
        ?>

        <div class="plate">
            <div class="title">
                Thought
            </div>
            
            <div class="formcontainer">
                <div class="label1">
                    Log In
                </div>
                <div class="label2">
                    Please sign in to continue.
                </div>
                <form action="php/login_process.php" method="POST" class="fillform">
                    <input type="text" class="npt" name="username" placeholder="Username">
                    <input type="password" class="npt" name="password" placeholder="Password">
                    <input type="submit" class="submit" value="Log In">
                </form>
                <div class="label3">
                    Don't have an account? <a href="signup.php" class="link1">Sign up</a>
                </div>
            </div>
        </div>
    </div>

    <script src="js/index.js"></script>
</body>
</html>