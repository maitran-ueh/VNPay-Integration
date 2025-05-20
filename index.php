<?php
// index.php
session_start();
require_once 'products.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="container py-4">
    <h1 class="mb-4">🛍️ Danh sách sản phẩm</h1>

    <div class="row">
        <?php foreach ($products as $id => $product): ?>
            <div class="col-md-4">
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text"><?= number_format($product['price']) ?> VNĐ</p>
                        <a href="add_to_cart.php?id=<?= urlencode($id) ?>" class="btn btn-primary">
                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-4">
        <a href="cart.php" class="btn btn-outline-success">
            <i class="bi bi-bag"></i> Xem giỏ hàng
        </a>
    </div>
</body>
</html>
