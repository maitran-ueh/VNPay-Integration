<?php
session_start();
include 'products.php';

if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}
?>

<h2>Danh sách sản phẩm</h2>
<ul>
<?php foreach ($products as $id => $product): ?>
    <li>
        <?= $product['name'] ?> - <?= number_format($product['price']) ?> VND
        <a href="?add=<?= $id ?>">[Thêm vào giỏ]</a>
    </li>
<?php endforeach; ?>
</ul>

<a href="cart.php">Xem giỏ hàng</a>