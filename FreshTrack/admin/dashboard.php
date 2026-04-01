<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}

$products = getAllProducts();

$page_title = "Admin Dashboard";
require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Product Dashboard</h1>
    <a href="<?php echo BASE_URL; ?>/admin/add_product.php" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Add Product
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Batch ID</th>
                        <th>Product Name</th>
                        <th>Type</th>
                        <th>Harvest Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): 
                        $history = getProductHistory($product['batch_id']);
                        $current_status = end($history)['status'] ?? 'harvested';
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['batch_id']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo ucfirst($product['product_type']); ?></td>
                            <td><?php echo date('d M Y', strtotime($product['harvest_date'])); ?></td>
                            <td>
                                <span class="badge bg-<?php echo getStatusBadgeClass($current_status); ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $current_status)); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/admin/update_status.php?batch_id=<?php echo $product['batch_id']; ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Update
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>