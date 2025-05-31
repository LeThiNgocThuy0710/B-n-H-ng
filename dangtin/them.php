<?php

session_start();
// $user_id = $_SESSION['email'];



// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root"; // thay thế bằng tên đăng nhập cơ sở dữ liệu của bạn
$password = ""; // thay thế bằng mật khẩu cơ sở dữ liệu của bạn
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem form có được gửi hay không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $TenSP = $_POST['TenSP'];
    $MaLoaiSP = $_POST['MaLoaiSP'];
    $DonGia = $_POST['DonGia'];
    $SoLuongTon = $_POST['SoLuongTon'];
    $TinhTrang = $_POST['TinhTrang'];
    $MoTa = $_POST['MoTa'];

    // Xử lý file hình ảnh
    $target_dir = "../images/"; // thư mục lưu hình ảnh
    $target_dir_empty =basename($_FILES["HinhSP"]["name"]);
    $target_file = $target_dir . basename($_FILES["HinhSP"]["name"]);
    move_uploaded_file($_FILES["HinhSP"]["tmp_name"], $target_file);
    
    // Chuẩn bị truy vấn để thêm sản phẩm
    $sql = "INSERT INTO sanpham (TenSP, MaLoaiSP, DonGia, SoLuongTon, TinhTrang, MoTa, HinhSP, MaNguoiDung)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";


    $MaNguoiDung = $_SESSION['MaNguoiDung']; 

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siidsssi", $TenSP, $MaLoaiSP, $DonGia, $SoLuongTon, $TinhTrang, $MoTa, $target_dir_empty, $MaNguoiDung);

    // Thực thi truy vấn
    if ($stmt->execute()) {
        echo "Đăng tin thành công!";
        header("Location:index.php");
        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    // Đóng kết nối
    $stmt->close();
}
$conn->close();
?>
