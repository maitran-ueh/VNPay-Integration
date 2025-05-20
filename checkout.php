<?php
session_start();
require_once 'db.php'; // Kết nối CSDL
require_once 'products.php';

// Chỉ cho phép POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit;
}

// Kiểm tra giỏ hàng và phương thức thanh toán
if (empty($_SESSION['cart']) || !in_array($_POST['gateway'], ['vnpay', 'momo'])) {
    header("Location: cart.php");
    exit;
}

$cart = $_SESSION['cart'];
$total = 0;
$orderCode = 'OD' . rand(1000, 9999);

// Lưu vào CSDL
foreach ($cart as $id => $qty) {
    if (!isset($products[$id])) continue;

    $product = $products[$id];
    $price = $product['price'];
    $subtotal = $price * $qty;
    $total += $subtotal;

    $stmt = $pdo->prepare("
        INSERT INTO orders (order_code, product_id, product_name, quantity, price, total_price)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $orderCode,
        $id,
        $product['name'],
        $qty,
        $price,
        $subtotal
    ]);
}

// Ghi lại thông tin đơn hàng để hiển thị sau thanh toán
$_SESSION['last_order'] = [
    'code' => $orderCode,
    'amount' => $total,
    'created_at' => date('Y-m-d H:i:s')
];

// Xoá giỏ hàng
unset($_SESSION['cart']);

// Điều hướng tới cổng thanh toán tương ứng
$gateway = $_POST['gateway'];
$amount = (int) $total;

if ($gateway === 'vnpay') {
    header("Location: vnpay_payment.php?amount=$amount");
} elseif ($gateway === 'momo') {
    header("Location: momo_payment.php?amount=$amount");
} else {
    header("Location: cart.php");
}
exit;
