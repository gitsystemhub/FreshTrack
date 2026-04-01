<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$batch_id = $_GET['batch_id'] ?? '';
$product = getProductDetails($batch_id);

if (!$product) {
    header("Location: " . BASE_URL . "/index.php?error=not_found");
    exit;
}

$history = getProductHistory($batch_id);
$current_status = end($history)['status'] ?? 'harvested';

require_once 'includes/header.php';
?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0"><?php echo htmlspecialchars($product['product_name']); ?></h2>
            <span class="badge bg-light text-dark"><?php echo strtoupper($product['product_type']); ?></span>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item">
                        <strong>Batch ID:</strong> <code><?php echo htmlspecialchars($product['batch_id']); ?></code>
                    </li>
                    <li class="list-group-item">
                        <strong>Harvest Date:</strong> <?php echo date('d M Y', strtotime($product['harvest_date'])); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Origin Farm:</strong> <?php echo htmlspecialchars($product['origin_farm']); ?>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item">
                        <strong>Shelf Life:</strong> <?php echo $product['expected_shelf_life']; ?> days
                    </li>
                    <li class="list-group-item">
                        <strong>Current Status:</strong> 
                        <span class="badge badge-<?php echo getStatusBadgeClass($current_status); ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $current_status)); ?>
                        </span>
                    </li>
                    <li class="list-group-item">
                        <strong>Storage:</strong> <?php echo htmlspecialchars($product['storage_conditions']); ?>
                    </li>
                </ul>
            </div>
        </div>

        <h5 class="mb-3">Supply Chain Journey</h5>
        <?php if (!empty($history)): ?>
            <div class="timeline">
                <?php foreach ($history as $event): ?>
                    <div class="timeline-item mb-4">
                        <div class="timeline-marker bg-<?php echo getStatusBadgeClass($event['status']); ?>"></div>
                        <div class="timeline-content card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="card-title mb-0">
                                        <?php echo ucfirst(str_replace('_', ' ', $event['status'])); ?>
                                    </h6>
                                    <small class="text-muted">
                                        <?php echo date('d M Y H:i', strtotime($event['timestamp'])); ?>
                                    </small>
                                </div>
                                <p class="card-text mb-1">
                                    <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                    <?php echo htmlspecialchars($event['location']); ?>
                                </p>
                                <?php if (!empty($event['notes'])): ?>
                                    <p class="card-text">
                                        <i class="fas fa-info-circle text-muted me-1"></i>
                                        <?php echo htmlspecialchars($event['notes']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info mb-0">No tracking history available for this product.</div>
        <?php endif; ?>
    </div>
    <div class="card-footer bg-light">
        <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Home
        </a>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>