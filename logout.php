<!-- logout.php -->
<?php
session_start();
session_destroy(); // Destroy the session data
header("Location: index.php"); // Redirect to index page after logout
exit();
?>
