<?php
// Cấu hình chung
define("BASE_URL", "http://localhost:3000/"); // URL frontend hoặc hệ thống của bạn

// Thông tin cấu hình VNPAY (test credentials)
$vnp_TmnCode = "NJJ0R8FS"; // Mã website do VNPAY cấp
$vnp_HashSecret = "BYKJBHPPZKQMKBIBGGXIYKWYFAYSJXCW"; // Chuỗi ký bảo mật do VNPAY cấp
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; // Địa chỉ sandbox VNPAY
$vnp_Returnurl = BASE_URL . "vnpay_return.php"; // URL trả về sau thanh toán
