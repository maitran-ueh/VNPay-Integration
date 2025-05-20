<?php
session_start();
require_once 'products.php';

// Lấy và kiểm tra ID sản phẩm
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Kiểm tra sản phẩm có tồn tại không
if (!isset($products[$id])) {
    // Nếu không tồn tại thì quay về trang chính
    header("Location: index.php");
    exit;
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tăng số lượng nếu đã có trong giỏ, nếu chưa thì gán = 1
$_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;

// Quay về giỏ hàng
header("Location: cart.php");
exit;