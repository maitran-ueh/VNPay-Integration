<?php
session_start();
require_once 'products.php'; // File chứa danh sách sản phẩm

if (isset($_GET['vnp_ResponseCode']) && $_GET['vnp_ResponseCode'] === '00') {
    echo "<h2>✅ Thanh toán thành công!</h2>";

    // Hiển thị thông tin đơn hàng nếu có giỏ hàng
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $total = 0;

        echo "<h3>Chi tiết đơn hàng:</h3>";

        foreach ($cart as $id => $qty) {
            if (isset($products[$id])) {
                $item = $products[$id];
                $subtotal = $item['price'] * $qty;
                $total += $subtotal;
                echo "{$item['name']} - SL: $qty - Tổng: " . number_format($subtotal) . " VND<br>";
            }
        }

        echo "<br><strong>Tổng đơn hàng: " . number_format($total) . " VND</strong>";

        // Sau khi hiển thị, xóa giỏ hàng
        unset($_SESSION['cart']);
    } else {
        echo "<p>Không có sản phẩm nào trong giỏ hàng.</p>";
    }
} else {
    echo "<h2>❌ Thanh toán thất bại hoặc bị hủy.</h2>";
}
?>

<br><br>
<a href="index.php">← Quay lại mua hàng</a>