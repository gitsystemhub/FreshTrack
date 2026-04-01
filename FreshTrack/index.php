<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<div class="hero mb-5">
    <div class="container">
        <h1 class="display-4 fw-bold">Track Your Fresh Produce</h1>
        <p class="lead mb-4">From farm to table - complete transparency</p>
        <form action="<?php echo BASE_URL; ?>/track.php" method="get" class="row g-3 justify-content-center">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg" name="batch_id" 
                           placeholder="Enter Batch ID (e.g. MANGO2023MH01)" required>
                    <button class="btn btn-warning btn-lg" type="submit">
                        <i class="fas fa-search me-2"></i>Track
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-shield-alt text-success mb-3" style="font-size: 2.5rem;"></i>
                    <h3 class="h5">Tamper-Proof</h3>
                    <p class="text-muted">Blockchain-verified records ensure data integrity throughout the supply chain.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-leaf text-success mb-3" style="font-size: 2.5rem;"></i>
                    <h3 class="h5">Freshness Guaranteed</h3>
                    <p class="text-muted">Real-time tracking of shelf life and storage conditions.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-history text-success mb-3" style="font-size: 2.5rem;"></i>
                    <h3 class="h5">Complete History</h3>
                    <p class="text-muted">View the entire journey from harvest to store shelf.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>