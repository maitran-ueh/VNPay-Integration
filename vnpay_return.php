<?php
session_start();
require_once 'db.php';
require_once 'products.php';
require_once 'vnpay_config.php';

// 1. Kiểm tra chữ ký
if (!isset($_GET['vnp_SecureHash'])) {
    die("Dữ liệu không hợp lệ.");
}

$vnp_SecureHash = $_GET['vnp_SecureHash'];
$vnp_Data = $_GET;

unset($vnp_Data['vnp_SecureHash']);
unset($vnp_Data['vnp_SecureHashType']);

ksort($vnp_Data);
$hashData = '';

foreach ($vnp_Data as $key => $value) {
    $hashData .= urlencode($key) . '=' . urlencode($value) . '&';
}

$hashData = rtrim($hashData, '&');
$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// 2. So sánh chữ ký
if ($secureHash !== $vnp_SecureHash) {
    die("<h3 style='color:red;'>❌ Sai chữ ký xác thực - Dữ liệu bị giả mạo!</h3>");
}

// 3. Kiểm tra kết quả giao dịch
if ($_GET['vnp_ResponseCode'] === '00') {
    echo "<h2>✅ Thanh toán thành công!</h2>";

    $order_id   = $_GET['vnp_TxnRef'];
    $amount     = $_GET['vnp_Amount'] / 100;
    $order_desc = $_GET['vnp_OrderInfo'] ?? '';
    $bank_code  = $_GET['vnp_BankCode'] ?? '';
    $pay_date   = DateTime::createFromFormat('YmdHis', $_GET['vnp_PayDate'])->format('Y-m-d H:i:s');
    $status     = 'success';

    try {
        $stmt = $pdo->prepare("INSERT INTO orders (order_id, amount, order_desc, bank_code, pay_date, transaction_status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $amount, $order_desc, $bank_code, $pay_date, $status]);
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Lỗi khi lưu đơn hàng: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    if (!empty($_SESSION['cart'])) {
        echo "<h3>Chi tiết đơn hàng:</h3>";
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $qty) {
            if (isset($products[$id])) {
                $item = $products[$id];
                $subtotal = $item['price'] * $qty;
                $total += $subtotal;
                echo htmlspecialchars($item['name']) . " - SL: {$qty} - Tổng: " . number_format($subtotal) . " VND<br>";
            }
        }
        echo "<br><strong>Tổng đơn hàng: " . number_format($total) . " VND</strong>";
        unset($_SESSION['cart']);
    } else {
        echo "<p>Không có sản phẩm trong giỏ.</p>";
    }

} else {
    echo "<h2>❌ Thanh toán thất bại hoặc bị hủy.</h2>";
}
?>

<br><br>
<a href="index.php">← Quay lại mua hàng</a>
