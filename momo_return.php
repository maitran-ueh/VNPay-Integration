<?php
session_start();

$orderId = $_GET['orderId'] ?? 'Không rõ';

if (isset($_GET['resultCode']) && $_GET['resultCode'] == 0) {
    unset($_SESSION['cart']);
    echo "<h2>🎉 Thanh toán thành công! Mã đơn hàng: <strong>#$orderId</strong></h2>";
} else {
    echo "<h2>❌ Thanh toán thất bại hoặc bị hủy.</h2>";
}
?>
<br><a href='index.php'>← Quay lại trang mua hàng</a>
