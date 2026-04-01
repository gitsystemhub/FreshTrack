<?php
require 'includes/config.php';
$result = $pdo->query("SHOW TABLES");
print_r($result->fetchAll());