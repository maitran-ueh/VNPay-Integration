<?php
session_start();
require_once("vnpay_config.php");

if (!isset($_POST['amount']) || !is_numeric($_POST['amount'])) {
    die("Dữ liệu không hợp lệ.");
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

$vnp_TxnRef = time(); // Mã giao dịch duy nhất
$vnp_OrderInfo = "Thanh toan gio hang";
$vnp_OrderType = "billpayment";
$vnp_Amount = (int)$_POST['amount'] * 100; // Đơn vị là đồng x 100
$vnp_Locale = "vn";
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef
);

ksort($inputData);
$hashdata = "";
$query = "";

foreach ($inputData as $key => $value) {
    $hashdata .= urlencode($key) . "=" . urlencode($value) . "&";
    $query .= urlencode($key) . "=" . urlencode($value) . "&";
}
$hashdata = rtrim($hashdata, "&");
$query = rtrim($query, "&");

$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
$vnp_Url = $vnp_Url . "?" . $query . "&vnp_SecureHash=" . $vnpSecureHash;

header("Location: " . $vnp_Url);
exit;