<?php
session_start();
session_destroy();

header("Location: login.php");

$status = "<span style='color:red;'><b>You successfully logged out. Come back soon.</b></span>";
?>
