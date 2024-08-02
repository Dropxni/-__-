<?php
session_start();
session_destroy();
header('Location: login_personal.php');
exit();
?>
