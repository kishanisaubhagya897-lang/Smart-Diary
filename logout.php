<?php
// logout.php - Handle user logout

// Start the session
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to the login page or home page
header("Location: login.php"); // Change this to your desired redirect page
exit();
?>
