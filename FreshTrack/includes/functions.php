<?php
function getProductDetails($batch_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products WHERE batch_id = ?");
    $stmt->bind_param("s", $batch_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getProductHistory($batch_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM product_status WHERE batch_id = ? ORDER BY timestamp ASC");
    $stmt->bind_param("s", $batch_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAllProducts() {
    global $conn;
    $result = $conn->query("SELECT * FROM products ORDER BY harvest_date DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getStatusBadgeClass($status) {
    $statusClasses = [
        'harvested' => 'success',
        'processed' => 'info',
        'packaged' => 'primary',
        'shipped' => 'warning',
        'delivered' => 'secondary',
        'on_shelf' => 'dark'
    ];
    return $statusClasses[$status] ?? 'light';
}
?>