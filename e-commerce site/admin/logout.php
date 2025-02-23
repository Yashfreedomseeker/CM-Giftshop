<?php
session_name("admin_session");
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

echo "<script>alert('Log out successful'); window.location.href='login.php';</script>";
exit();
?>
