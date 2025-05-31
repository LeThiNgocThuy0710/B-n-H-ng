<?php
session_start();
if (!isset($_SESSION['TenDangNhap'])) {
    header('location:../login/login.php');
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bán Đồ Cũ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/bandocu1/index.php">NewJean</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="me-auto">

                </div>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/bandocu1/index.php">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="/bandocu1/view/sanpham.php">Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="/bandocu1/tinnhan/tinnhan.php">Tin nhắn</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Tài khoản</a>
                        <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                            <?php
                            if (isset($_SESSION['TenDangNhap'])) {
                                if ($_SESSION['role'] === '2') {
                                    echo '
                            <li><a class="dropdown-item" href="/bandocu1/donhang/qldonhang.php">Admin: Quản lý Đơn hàng</a></li>
                            <li><a class="dropdown-item" href="/bandocu1/dangtin/modules/quanlytin.php">Admin: Quản lý Bài Đăng</a></li>
<li><a class="dropdown-item" href="/bandocu1/dangtin/modules/qldanhmuc.php">Admin: Quản lý Danh mục</a></li>                            <hr/>';

                            echo ' <li><a class="dropdown-item" href="/bandocu1/qltaikhoan.php">Quản lý Tài Khoản</a></li>
                                                                <li><a class="dropdown-item" href="/bandocu1/donhang/luusp.php">Danh mục yêu thích</a></li>
                            <li><a class="dropdown-item" href="/bandocu1/donhang/donmua.php">Đơn hàng đã mua</a></li>
                                                          <li><a class="dropdown-item" href="/bandocu1/donhang/donban.php">Đơn hàng đang bán</a></li>
                           
     
                                    <li><a class="dropdown-item" href="/bandocu1/dangxuat.php">Đăng xuất</a></li>';
                                } ?>

                                <li>
                                    <?php
                                    if ($_SESSION['role'] === '1') {
                                        echo ' <li><a class="dropdown-item" href="/bandocu1/dangtin/modules/quanlytin.php">Quản lý Bài Đăng</a></li><li><a class="dropdown-item" href="/bandocu1/qltaikhoan.php">Quản lý Tài Khoản</a></li>
                                                                <li><a class="dropdown-item" href="/bandocu1/donhang/luusp.php">Danh mục yêu thích</a></li>
                            <li><a class="dropdown-item" href="/bandocu1/donhang/donmua.php">Đơn hàng đã mua</a></li>
                                                          <li><a class="dropdown-item" href="/bandocu1/donhang/donban.php">Đơn hàng đang bán</a></li>
                           
     
                                    <li><a class="dropdown-item" href="/bandocu1/dangxuat.php">Đăng xuất</a></li>';
                                    }
                            } else {
                                echo '<li><a class="dropdown-item" href="/bandocu1//login/login.php">Đăng nhập</a></li>';
                            }
                            ?>
                            </li>
                        </ul>
                    </li>
                    <div class="ms-2">
                        <a href="/bandocu1/view/giohang.php" class="btn btn-outline-light me-2">Giỏ hàng</a>
                        <a href="/bandocu1/dangtin/index.php" class="btn btn-warning">Đăng tin</a>
                    </div>
                </ul>
            </div>
        </div>
    </nav>
    <div class="main">
        <h2>Đăng tin mới</h2>
        <form action="them.php" method="POST" enctype="multipart/form-data">
            <label for="title">Tên sản phẩm:</label>
            <input type="text" name="TenSP" required><br><br>

            <label for="description">Mô tả:</label>
            <textarea name="MoTa" required></textarea><br><br>

            <label for="price">Giá (VNĐ):</label>
            <input type="number" name="DonGia" required><br><br>

            <label for="unit">Số Lượng:</label>
            <input type="number" name="SoLuongTon" required><br><br>

            <label for="TinhTrang">Tình trạng:</label>
            <input type="text" name="TinhTrang" required><br>

            <label for="category">Danh mục:</label>
            <select name="MaLoaiSP" required>
                <option value="" disabled selected hidden>Chọn loại danh mục...</option>
                <option value="1">Giày</option>
                <option value="2">Quần</option>
                <option value="3">Phụ kiện</option>
                <option value="4">Túi xách</option>
                <option value="5">Nón</option>
                <option value="6">Mỹ phẩm</option>
                <option value="7">Sách</option>
                <option value="8">Áo</option>
                <option value="9">Đồ gia dụng</option>
                <option value="10">Đồ điện tử</option>
            </select><br><br>
            <label for="product_image">Hình sản phẩm:</label>
            <input type="file" name="HinhSP" accept="image/*" required><br><br>
            <button class="btn-dangtin" name="themtin" type="submit">Đăng tin</button>
        </form>
    </div>
    <!-- Footer -->
    <!-- <footer class="bg-dark text-white text-center py-4">
       
            <div class="footer-container">
                <div class="footer-section">
                <h4>Thông tin liên hệ</h4>
                <p>Địa chỉ: 12 Nguyễn Văn Bảo, phường 4, Quận Gò Vấp, TP.HCM</p>
                <p>Điện thoại: 0123 456 789</p>
                </div>
                <div class="footer-section">
                <h4>Về chúng tôi</h4>
                <ul>
                    <li><a href="#">Trang chủ</a></li>
                    <li><a href="#">Giới thiệu</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
                </div>
                <div class="footer-section">
                <h4>Mạng xã hội</h4>
                <ul>
                    <li><a href="#"><i class="fa-brands fa-facebook"></i>&nbsp;Facebook</a></li>
                    <li><a href="#"><i class="fa-brands fa-instagram"></i>&nbsp;Instagram</a></li>
                    <li><a href="#"><i class="fa-brands fa-viber"></i>&nbsp;Viper</a></li>
                </ul>
                </div>
            </div>
      
    </footer> -->

    <!-- Thêm JS Bootstrap -->
</body>

</html>