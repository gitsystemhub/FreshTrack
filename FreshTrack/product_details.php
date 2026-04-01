<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$batchId = isset($_GET['batch_id']) ? trim($_GET['batch_id']) : '';

if (empty($batchId)) {
    header("Location: index.php");
    exit;
}

$product = getProductDetails($batchId);

if (!$product) {
    $error = "No product found with Batch ID: " . htmlspecialchars($batchId);
}

require_once 'includes/header.php';
?>

<div class="container product-details">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    <?php else: ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>
            <span class="batch-id">Batch: <?php echo htmlspecialchars($product['batch_id']); ?></span>
        </div>
        
        <div class="row">
            <div class="col-md-5">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Product Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <th>Type</th>
                                    <td><?php echo ucfirst($product['product_type']); ?></td>
                                </tr>
                                <tr>
                                    <th>Harvest Date</th>
                                    <td><?php echo date('M j, Y', strtotime($product['harvest_date'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Days Since Harvest</th>
                                    <td>
                                        <?php 
                                        $harvestDate = new DateTime($product['harvest_date']);
                                        $currentDate = new DateTime();
                                        echo $currentDate->diff($harvestDate)->days;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Origin Farm</th>
                                    <td><?php echo htmlspecialchars($product['origin_farm']); ?></td>
                                </tr>
                                <tr>
                                    <th>Shelf Life</th>
                                    <td><?php echo $product['expected_shelf_life']; ?> days</td>
                                </tr>
                                <tr>
                                    <th>Storage</th>
                                    <td><?php echo htmlspecialchars($product['storage_conditions']); ?></td>
                                </tr>
                                <tr>
                                    <th>Current Status</th>
                                    <td>
                                        <span class="badge badge-<?php echo getStatusBadgeClass($product['current_status']); ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $product['current_status'])); ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Freshness Indicator</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="freshness-circle mx-auto mb-3">
                            <div class="circle-progress" data-value="<?php echo $product['freshness_percentage'] / 100; ?>">
                                <span><?php echo round($product['freshness_percentage']); ?>%</span>
                            </div>
                        </div>
                        <p class="text-muted mb-0">Based on harvest date and expected shelf life</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Supply Chain Journey</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline-vertical">
                            <?php foreach ($product['history'] as $event): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker <?php echo $event['status']; ?>"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6><?php echo ucfirst(str_replace('_', ' ', $event['status'])); ?></h6>
                                            <small class="text-muted"><?php echo date('M j, Y H:i', strtotime($event['timestamp'])); ?></small>
                                        </div>
                                        <p class="mb-1"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?></p>
                                        <?php if (!empty($event['notes'])): ?>
                                            <p class="mb-1"><?php echo htmlspecialchars($event['notes']); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($event['blockchain_hash'])): ?>
                                            <div class="blockchain-verification mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-link"></i> Blockchain Verification:
                                                    <?php if ($event['blockchain_verified']): ?>
                                                        <span class="text-success"><i class="fas fa-check-circle"></i> Verified</span>
                                                    <?php else: ?>
                                                        <span class="text-danger"><i class="fas fa-times-circle"></i> Tampered</span>
                                                    <?php endif; ?>
                                                </small>
                                                <button class="btn btn-sm btn-outline-secondary btn-blockchain-details" 
                                                        data-hash="<?php echo $event['blockchain_hash']; ?>">
                                                    View Details
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="track.php?batch_id=<?php echo $product['batch_id']; ?>" class="btn btn-success">
                <i class="fas fa-sync-alt"></i> Refresh Status
            </a>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
        
        <!-- Blockchain Modal -->
        <div class="modal fade" id="blockchainModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Blockchain Verification Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Transaction Hash:</label>
                            <input type="text" class="form-control" id="blockchain-hash" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status:</label>
                            <input type="text" class="form-control" id="blockchain-status" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data:</label>
                            <textarea class="form-control" id="blockchain-data" rows="6" readonly></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize circle progress
    const progressElements = document.querySelectorAll('.circle-progress');
    progressElements.forEach(el => {
        const value = parseFloat(el.getAttribute('data-value'));
        const circumference = 2 * Math.PI * 40;
        const strokeDashOffset = circumference * (1 - value);
        
        el.innerHTML = `
            <svg class="progress-ring" width="120" height="120">
                <circle class="progress-ring-circle" stroke-width="8" stroke-linecap="round" 
                        fill="transparent" r="40" cx="60" cy="60"/>
            </svg>
            <span>${Math.round(value * 100)}%</span>
        `;
        
        const circle = el.querySelector('.progress-ring-circle');
        circle.style.strokeDasharray = `${circumference} ${circumference}`;
        circle.style.strokeDashoffset = strokeDashOffset;
        
        // Set color based on value
        if (value > 0.7) {
            circle.style.stroke = '#28a745';
        } else if (value > 0.4) {
            circle.style.stroke = '#ffc107';
        } else {
            circle.style.stroke = '#dc3545';
        }
    });
    
    // Blockchain details modal
    const blockchainButtons = document.querySelectorAll('.btn-blockchain-details');
    blockchainButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const hash = this.getAttribute('data-hash');
            const eventItem = this.closest('.timeline-item');
            const status = eventItem.querySelector('h6').textContent;
            const location = eventItem.querySelector('.fa-map-marker-alt').nextSibling.textContent.trim();
            const notes = eventItem.querySelector('p.mb-1:last-child')?.textContent || 'No additional notes';
            const timestamp = eventItem.querySelector('small.text-muted').textContent.trim();
            
            document.getElementById('blockchain-hash').value = hash;
            document.getElementById('blockchain-status').value = status;
            document.getElementById('blockchain-data').value = `Location: ${location}\nTimestamp: ${timestamp}\nNotes: ${notes}`;
            
            const modal = new bootstrap.Modal(document.getElementById('blockchainModal'));
            modal.show();
        });
    });
});
</script>

<style>
.freshness-circle {
    position: relative;
    width: 120px;
    height: 120px;
}

.circle-progress {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.circle-progress span {
    position: absolute;
    font-size: 1.5rem;
    font-weight: bold;
}

.progress-ring-circle {
    transition: stroke-dashoffset 0.5s ease;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
}

.timeline-vertical {
    position: relative;
    padding-left: 30px;
}

.timeline-vertical::before {
    content: '';
    position: absolute;
    left: 11px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    z-index: 1;
}

.timeline-marker.harvested { background-color: #28a745; }
.timeline-marker.processed { background-color: #17a2b8; }
.timeline-marker.packaged { background-color: #007bff; }
.timeline-marker.shipped { background-color: #6f42c1; }
.timeline-marker.delivered { background-color: #fd7e14; }
.timeline-marker.on_shelf { background-color: #20c997; }

.timeline-content {
    padding: 15px;
    background-color: white;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.blockchain-verification {
    padding: 8px;
    background-color: #f8f9fa;
    border-radius: 4px;
    border-left: 3px solid #6c757d;
}

.btn-blockchain-details {
    margin-top: 5px;
    font-size: 0.7rem;
    padding: 0.15rem 0.5rem;
}
</style>

<?php require_once 'includes/footer.php'; ?>