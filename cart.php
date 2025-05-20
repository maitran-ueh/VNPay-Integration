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

<h2>Giỏ hàng</h2>
<?php if (empty($cart)): ?>
    <p>Giỏ hàng trống.</p>
<?php else: ?>
    <form action="vnpay_create_payment.php" method="post">
    <table border="1" cellpadding="10">
        <tr><th>Sản phẩm</th><th>Số lượng</th><th>Giá</th><th>Thao tác</th></tr>
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
                <td><a href="cart.php?remove=<?= $id ?>" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"><strong>Tổng cộng:</strong></td>
            <td><strong><?= number_format($total) ?> VND</strong></td>
        </tr>
    </table>
    <input type="hidden" name="amount" value="<?= $total ?>">
    <br>
    <button type="submit">Thanh toán bằng VNPAY (QR)</button>
    </form>
<?php endif; ?>

<br>
<a href="index.php">← Quay lại mua hàng</a>