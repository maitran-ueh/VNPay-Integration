<?php
session_start();

if (empty($_SESSION['last_order'])) {
    exit("Không tìm thấy thông tin đơn hàng.");
}

$order = $_SESSION['last_order'];
$amount = (int)($order['amount'] ?? 0);
$orderId = $order['code'] ?? ('OD' . time());

if ($amount <= 0) exit("Số tiền không hợp lệ.");

// Cấu hình MoMo (sandbox)
$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
$partnerCode = "MOMO4MUD20240115_TEST";
$accessKey = "Ekj9og2VnRfOuIys";
$secretKey = "PseUbm2s8QVJEbexsh8H3Jz2qa9tDqoa";

// Thông tin giao dịch
$requestId = time() . "";
$orderInfo = "Thanh toán đơn hàng #" . $orderId;
$redirectUrl = "http://localhost/momo_return.php";
$ipnUrl = "http://localhost/momo_ipn.php";
$requestType = "captureWallet";

// Tạo chữ ký
$rawHash = "accessKey=$accessKey&amount=$amount&extraData=&ipnUrl=$ipnUrl"
         . "&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode"
         . "&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";

$signature = hash_hmac("sha256", $rawHash, $secretKey);

// Dữ liệu gửi
$data = [
    'partnerCode' => $partnerCode,
    'accessKey' => $accessKey,
    'requestId' => $requestId,
    'amount' => (string)$amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'extraData' => '',
    'requestType' => $requestType,
    'signature' => $signature,
    'lang' => 'vi'
];

// Gửi đến MoMo
$ch = curl_init($endpoint);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json']
]);

$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response, true);

// Xử lý kết quả
if (!empty($result['payUrl'])) {
    header("Location: " . $result['payUrl']);
    exit;
}

echo "<h3>❌ Lỗi khi tạo thanh toán MoMo</h3>";
echo "<pre>" . htmlspecialchars(print_r($result, true)) . "</pre>";
