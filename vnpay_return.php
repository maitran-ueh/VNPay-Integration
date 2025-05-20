<?php
session_start();

if (isset($_GET['vnp_ResponseCode']) && $_GET['vnp_ResponseCode'] === '00') {
    echo "<h2>✅ Thanh toán thành công!</h2>";

    if (!empty($_SESSION['last_order'])) {
        $order = $_SESSION['last_order'];
        echo "<p>Mã đơn hàng: <strong>#" . htmlspecialchars($order['code']) . "</strong></p>";
        echo "<p>Tổng tiền: <strong>" . number_format($order['amount']) . " VND</strong></p>";
        echo "<p>Thời gian đặt: " . $order['created_at'] . "</p>";
    } else {
        echo "<p>Không tìm thấy thông tin đơn hàng trong session.</p>";
    }

    unset($_SESSION['cart']); // Phòng trường hợp chưa unset
} else {
    echo "<h2>❌ Thanh toán thất bại hoặc bị hủy.</h2>";
}
?>
<br><a href="index.php">← Quay lại mua hàng</a>
