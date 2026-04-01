<?php
function getStatusBadgeClass($status) {
    $statusMap = [
        'harvested' => 'success',
        'processed' => 'info',
        'packaged' => 'primary',
        'shipped' => 'secondary',
        'delivered' => 'warning',
        'on_shelf' => 'dark'
    ];
    
    return $statusMap[$status] ?? 'light';
}

function getStatusSequence($currentStatus) {
    $allStatuses = ['harvested', 'processed', 'packaged', 'shipped', 'delivered', 'on_shelf'];
    $currentIndex = array_search($currentStatus, $allStatuses);
    return $currentIndex !== false ? array_slice($allStatuses, $currentIndex + 1) : [];
}

function generateBatchId($productType) {
    $prefix = strtoupper(substr($productType, 0, 1));
    $random = strtoupper(bin2hex(random_bytes(3)));
    $date = date('ymd');
    return $prefix . $date . $random;
}
?>