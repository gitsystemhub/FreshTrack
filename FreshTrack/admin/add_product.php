<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_id = $_POST['batch_id'];
    $product_name = $_POST['product_name'];
    $product_type = $_POST['product_type'];
    $harvest_date = $_POST['harvest_date'];
    $shelf_life = $_POST['shelf_life'];
    $storage = $_POST['storage'];
    $origin = $_POST['origin'];
    
    $sql = "INSERT INTO products (batch_id, product_name, product_type, harvest_date, expected_shelf_life, storage_conditions, origin_farm) 
            VALUES ('$batch_id', '$product_name', '$product_type', '$harvest_date', $shelf_life, '$storage', '$origin')";
    $conn->query($sql);
    
    header("Location: dashboard.php");
    exit;
}

require_once '../includes/header.php';
?>

<div class="container">
    <h2>Add New Product</h2>
    <form method="post">
        <input type="text" name="batch_id" placeholder="Batch ID" required>
        <input type="text" name="product_name" placeholder="Product Name" required>
        <select name="product_type" required>
            <option value="fruit">Fruit</option>
            <option value="vegetable">Vegetable</option>
        </select>
        <input type="date" name="harvest_date" required>
        <input type="number" name="shelf_life" placeholder="Shelf Life (days)" required>
        <textarea name="storage" placeholder="Storage Conditions" required></textarea>
        <input type="text" name="origin" placeholder="Origin Farm" required>
        <button type="submit">Add Product</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>