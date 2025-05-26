<?php
session_start();
require_once("vnpay_config.php");
require_once("products.php");

// 1. Kiểm tra giỏ hàng
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    die("Giỏ hàng trống.");
}

// 2. Tính tổng tiền từ session (không dùng $_POST)
$total = 0;
foreach ($cart as $id => $qty) {
    if (isset($products[$id])) {
        $total += $products[$id]['price'] * $qty;
    }
}

if ($total <= 0) {
    die("Tổng tiền không hợp lệ.");
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

// 3. Thiết lập tham số thanh toán
$vnp_TxnRef = uniqid(); // Sử dụng ID duy nhất tránh trùng
$vnp_OrderInfo = "Thanh toán giỏ hàng";
$vnp_OrderType = "billpayment";
$vnp_Amount = $total * 100;
$vnp_Locale = "vn";
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

$inputData = array(
    "vnp_Version"    => "2.1.0",
    "vnp_TmnCode"    => $vnp_TmnCode,
    "vnp_Amount"     => $vnp_Amount,
    "vnp_Command"    => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode"   => "VND",
    "vnp_IpAddr"     => $vnp_IpAddr,
    "vnp_Locale"     => $vnp_Locale,
    "vnp_OrderInfo"  => $vnp_OrderInfo,
    "vnp_OrderType"  => $vnp_OrderType,
    "vnp_ReturnUrl"  => $vnp_Returnurl,
    "vnp_TxnRef"     => $vnp_TxnRef
);

// 4. Tạo query và hash
ksort($inputData);
$query = '';
$hashdata = '';

foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . '=' . urlencode($value) . '&';
    $hashdata .= urlencode($key) . '=' . urlencode($value) . '&';
}

$hashdata = rtrim($hashdata, '&');
$query = rtrim($query, '&');

$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
$vnp_Url = $vnp_Url . "?" . $query . "&vnp_SecureHash=" . $vnpSecureHash;

// 5. Chuyển hướng đến VNPAY
header("Location: " . $vnp_Url);
exit;
