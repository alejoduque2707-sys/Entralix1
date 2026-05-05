<?php
session_start();

// 🔥 solo elimina sesión admin
unset($_SESSION['admin']);

header("Location: admin_login.php");
exit();
?>