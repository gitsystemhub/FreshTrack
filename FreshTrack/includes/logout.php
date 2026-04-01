<?php
require_once 'config.php';
require_once 'auth.php';

logoutUser();
header("Location: index.php");
exit;
?>