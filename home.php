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


<?php

/** @var Connection $connection */
$connection = require_once 'php/pdo.php';
// Read notes from database
$notes = $connection->getNotes($username);
$pnotes = $connection->getpinNotes($username);

$currentNote = [
    'id' => '',
    'title' => '',
    'description' => ''
];
if (isset($_GET['id'])) {
    $currentNote = $connection->getNoteById($_GET['id']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/home.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>

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
                            echo '<img src="data:image/jpeg;base64,'.base64_encode($user['avatar']).'" class="avatar" alt="Profile Image">';
                        } else {
                            // Render default profile image if no image found
                            echo '<img src="icon/default-avatar.png" class="avatar" alt="Profile Image">';
                        }
                    } catch (PDOException $e) {
                        // Error handling for database queries
                        echo "Error fetching profile image: " . $e->getMessage();
                    }
                ?>

                <div class="username"><?php echo $username; ?></div>
                <div class="edgebtncon">
                    <a href ="profile.php" class="edgebtn">Profile</a>
                    <a href="php/logout.php" class="edgebtn">Logout</a>
                </div>
            </div>
    <div class="container">

        <div class="padcontainer">
            

            <div class="pad">
                <form class="new-note" action="php/create.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $currentNote['id'] ?>">
                    <input type="text" class="inptitle" name="title" placeholder="Note Title" maxlength="25" required value="<?php echo $currentNote['title'] ?>">
                    <div class="divWithLine"></div>
                    <textarea class="inpdsc" name="description" id="noteDescription" cols="30" rows="12" placeholder="Note Description" maxlength="2000" required><?php echo $currentNote['description'] ?></textarea>
                    <div class="charCount" id="charCount">Characters: <?php echo strlen($currentNote['description']); ?> / 2000</div>
                    <input type="hidden" name="username" value="<?php echo $username; ?>">
                    <input type="hidden" name="pinned" value="no">

                    <div class="btncon">
                        <?php if ($currentNote['id']): ?>
                            <button class="notebtn">
                                Update Note
                            </button>
                            <a href="home.php" class="notebtn">
                                Cancel Update
                            </a>
                        <?php else: ?>
                            <button class="notebtn">
                                Add Note
                            </button>
                        <?php endif ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="shelf">
            <div class="label">
                    Favorites
            </div>
            <div class="shelf_t">
                <div class="notes">
                    <?php foreach ($pnotes as $note): ?>
                        <div class="note">
                            <div class="title">
                                <a href="?id=<?php echo $note['id'] ?>">
                                    <?php echo $note['title'] ?>
                                </a>
                            </div>
                            <div class="description">
                                <?php 
                                    $description = $note['description'];
                                    echo mb_substr($description, 0, 30, 'UTF-8');
                                    if (mb_strlen($description) > 30) {
                                        echo '...';
                                    }
                                ?>
                            </div>
                                
                            <small><?php echo date('d/m/Y H:i', strtotime($note['create_date'])) ?></small>
                            <form id="deleteForm_<?php echo $note['id'] ?>" class="delete-form" action="php/delete.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $note['id'] ?>">
                                <button class="close"></button>
                            </form>
                            <form action="php/pin.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                                <input type="hidden" name="pinned" value="no">
                                <button class="pin"></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="label">
                    All Notes
            </div>
            <div class="shelf_b">
                

                <div class="notes">
                    <?php foreach ($notes as $note): ?>
                        <div class="note">
                            <div class="title">
                                <a href="?id=<?php echo $note['id'] ?>">
                                    <?php echo $note['title'] ?>
                                </a>
                            </div>
                            <div class="description">
                                <?php 
                                    $description = $note['description'];
                                    echo mb_substr($description, 0, 50, 'UTF-8');
                                    if (mb_strlen($description) > 50) {
                                        echo '...';
                                    }
                                ?>
                            </div>
                            <small><?php echo date('d/m/Y H:i', strtotime($note['create_date'])) ?></small>
                            <form id="deleteForm_<?php echo $note['id'] ?>" class="delete-form" action="php/delete.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $note['id'] ?>">
                                <button class="close"></button>
                            </form>

                            <form action="php/pin.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                                <input type="hidden" name="pinned" value="yes">
                                <button class="unpin"></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    

    <div id="confirmationMessage" class="modal">
    </div>

    <script>
        // Function to handle form submission with confirmation
        function confirmDelete(formId) {
            // Get the form element
            var form = document.getElementById(formId);

            // Display confirmation message
            var confirmationMessage = document.getElementById('confirmationMessage');
            confirmationMessage.innerHTML = `
                <div class="confirmation-message">
                    Are you sure you want to delete this note?
                    <div class="buttons-container">
                        <button class="delete-button" onclick="proceedDelete('${formId}')">Yes</button>
                        <button class="cancel-button" onclick="cancelDelete()">No</button>
                    </div>
                </div>
            `;

            confirmationMessage.style.display = 'flex';
        }

        // Function to proceed with delete
        function proceedDelete(formId) {
            var form = document.getElementById(formId);
            form.submit();
        }

        // Function to cancel delete
        function cancelDelete() {
            var confirmationMessage = document.getElementById('confirmationMessage');
            confirmationMessage.style.display = 'none';
        }

        // Attach event listeners to all delete forms
        var deleteForms = document.querySelectorAll('form.delete-form');
        deleteForms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                // Prevent default form submission
                event.preventDefault();
                // Get the form id
                var formId = form.getAttribute('id');
                // Call the confirmation function
                confirmDelete(formId);
            });
        });
    </script>

    <script src="js/home.js"></script>
</body>
</html>
