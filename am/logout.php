<?PHP
session_start();

// remove all session variables
session_unset();

// destroy the session
session_destroy();

// Redirect user to index page
echo "<script>window.location = '/';</script>";
?>