<?php
session_start();
session_unset(); // clear all session variables
session_destroy(); // destroy the session
header("Location: admin_login.php"); // redirect to login page
exit();
?>
