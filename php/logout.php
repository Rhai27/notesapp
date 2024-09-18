<?php
session_start();
session_destroy(); // Destroy all data associated with the current session
header("Location: ../index.php"); // Redirect to the login page after logout
exit();
?>
