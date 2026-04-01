<?php
require 'includes/config.php';
require 'includes/functions.php';

$batchId = 'MANGO2025MH01'; // Test with known good ID
$product = getProductDetails($batchId);

echo "<h2>Debug Output</h2>";
echo "<pre>Product Data: "; print_r($product); echo "</pre>";

// Show raw database content
$testData = $pdo->query("SELECT * FROM products WHERE batch_id = '$batchId'")->fetch();
echo "<pre>Database Row: "; print_r($testData); echo "</pre>";
?>