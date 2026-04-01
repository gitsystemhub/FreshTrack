<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$batch_id = $_GET['batch_id'];
$product = getProductDetails($batch_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $location = $_POST['location'];
    $notes = $_POST['notes'];
    
    $sql = "INSERT INTO product_status (batch_id, status, location, notes) 
            VALUES ('$batch_id', '$status', '$location', '$notes')";
    $conn->query($sql);
    
    header("Location: dashboard.php");
    exit;
}

require_once '../includes/header.php';
?>

<div class="container">
    <h2>Update Status for <?= $product['product_name'] ?></h2>
    <p>Batch ID: <?= $batch_id ?></p>
    
    <form method="POST">
        <div>
            <label>Status:</label>
            <select name="status" required>
                <option value="harvested">Harvested</option>
                <option value="processed">Processed</option>
                <option value="packaged">Packaged</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="on_shelf">On Shelf</option>
            </select>
        </div>
        
        <div>
            <label>Location:</label>
            <input type="text" name="location" required>
        </div>
        
        <div>
            <label>Notes:</label>
            <textarea name="notes"></textarea>
        </div>
        
        <button type="submit">Save Update</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>