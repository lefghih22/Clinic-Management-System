<?php
session_start();


session_unset();
session_destroy();


header("Location: Doctor_login.php");
exit;
?>
