<?php
session_start();
include 'products.php';

$cart = $_SESSION['cart'] ?? [];

// Xử lý xóa sản phẩm nếu có ?remove=ID
if (isset($_GET['remove'])) {
    $removeId = (int)$_GET['remove'];
    unset($_SESSION['cart'][$removeId]);
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <script>
        function confirmDelete() {
            return confirm("Bạn có chắc chắn muốn xoá sản phẩm này?");
        }
        function confirmCheckout() {
            return confirm("Xác nhận đặt hàng?");
        }
    </script>
</head>
<body class="container py-4">
    <h1 class="mb-4">🛒 Giỏ hàng</h1>
<?php if (empty($cart)): ?>
    <p>Giỏ hàng trống.</p>
<?php else: ?>
    <form action="vnpay_payment.php" method="post">
    <table border="1" cellpadding="10" class="table table-bordered">
                    <thead>
                        <tr><th>Sản phẩm</th><th>Số lượng</th><th>Giá</th><th>Thao tác</th></tr>
                    </thead>
                    <tbody>
        <?php 
        $total = 0;
        foreach ($cart as $id => $qty): 
            $item = $products[$id];
            $subtotal = $item['price'] * $qty;
            $total += $subtotal;
        ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $qty ?></td>
                <td><?= number_format($subtotal) ?> VND</td>
                <td><a href="cart.php?remove=<?= $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tr>
            <td colspan="4"><strong>Tổng cộng:</strong> <strong><?= number_format($total) ?> VND</strong></td>
        </tr>
    </table>
    <input type="hidden" name="amount" value="<?= $total ?>">
    <br>
    <button type="submit">Thanh toán bằng VNPAY (QR)</button>
    </form>
<?php endif; ?>

<div class="mt-4">
        <a href="index.php" class="btn btn-secondary">← Tiếp tục mua hàng</a>
    </div>
</body>
</html>