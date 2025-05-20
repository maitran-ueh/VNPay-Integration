<?php
session_start();
require_once "vnpay_config.php";

if (empty($_SESSION['last_order']['amount']) || empty($_SESSION['last_order']['code'])) {
    die("Không tìm thấy thông tin đơn hàng.");
}

$amount = (int) $_SESSION['last_order']['amount'];
if ($amount <= 0) die("Số tiền không hợp lệ.");

date_default_timezone_set('Asia/Ho_Chi_Minh');

// Thiết lập thông tin thanh toán
$vnp_TxnRef = time();
$vnp_OrderInfo = "Thanh toán đơn hàng #" . $_SESSION['last_order']['code'];
$vnp_Amount = $amount * 100;

$inputData = [
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
    "vnp_Locale" => "vn",
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => "billpayment",
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef
];

// Sắp xếp và tạo hash
ksort($inputData);
$query = http_build_query($inputData);
$hashData = urldecode($query);
$vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Tạo URL thanh toán
$vnp_Url .= '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;
header("Location: $vnp_Url");
exit;
