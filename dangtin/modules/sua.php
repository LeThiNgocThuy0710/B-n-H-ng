<?php
$servername = "localhost";
$username = "root"; // thay thế bằng tên đăng nhập cơ sở dữ liệu của bạn
$password = ""; // thay thế bằng mật khẩu cơ sở dữ liệu của bạn
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Lấy thông tin sản phẩm để sửa
    $sql = "SELECT * FROM sanpham WHERE MaSP = ? and isDeleted = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Sản phẩm không tồn tại.";
        exit();
    }
}

// Xử lý form sửa sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $TenSP = $_POST['TenSP'];
    $DonGia = $_POST['DonGia'];
    $SoLuongTon = $_POST['SoLuongTon'];
    $TinhTrang = $_POST['TinhTrang'];
    
    $sql = "UPDATE sanpham SET TenSP = ?, DonGia = ?, SoLuongTon = ?, TinhTrang = ? WHERE MaSP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdisi", $TenSP, $DonGia, $SoLuongTon, $TinhTrang, $id);
    $stmt->execute();

    header("Location:quanlytin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <style>
        .main {
        max-width: 500px; /* Giới hạn chiều rộng tối đa */
        margin: 0 auto; /* Căn giữa */
        background-color: #fff; /* Màu nền trắng */
        border-radius: 8px; /* Bo góc */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Bóng đổ */
        padding: 20px; /* Padding cho nội dung */
        }

        h1 {
            text-align: center; /* Căn giữa tiêu đề */
            color: #333; /* Màu chữ tối */
            margin-bottom: 20px; /* Khoảng cách dưới tiêu đề */
        }

        label {
            display: block; /* Hiển thị label dưới dạng block để dễ dàng căn chỉnh */
            margin-bottom: 5px; /* Khoảng cách dưới mỗi label */
            color: #555; /* Màu chữ cho label */
        }

        input[type="text"],
        input[type="number"] {
            width: 100%; /* Chiều rộng 100% */
            padding: 10px; /* Padding cho ô input */
            margin-bottom: 15px; /* Khoảng cách dưới mỗi ô input */
            border: 1px solid #ddd; /* Đường viền xung quanh ô input */
            border-radius: 4px; /* Bo góc cho ô input */
            box-sizing: border-box; /* Bao gồm padding và border trong chiều rộng */
        }

        input[type="submit"] {
            width: 100%; /* Chiều rộng 100% */
            padding: 10px; /* Padding cho nút submit */
            background-color: #007bff; /* Màu nền cho nút */
            color: white; /* Màu chữ trắng */
            border: none; /* Không có đường viền */
            border-radius: 4px; /* Bo góc cho nút */
            cursor: pointer; /* Hiệu ứng con trỏ khi di chuột */
            transition: background-color 0.3s; /* Hiệu ứng chuyển màu khi di chuột */
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Màu nền khi di chuột qua nút */
        }
    </style>
</head>
<body>
    <div class="main">
        <h1>Sửa sản phẩm</h1>
        <form method="POST">
            <label for="TenSP">Tên sản phẩm:</label>
            <input type="text" name="TenSP" value="<?php echo $product['TenSP']; ?>" required>
            <br>
            <label for="DonGia">Giá:</label>
            <input type="number" name="DonGia" value="<?php echo $product['DonGia']; ?>" required>
            <br>
            <label for="SoLuongTon">Số lượng tồn:</label>
            <input type="number" name="SoLuongTon" value="<?php echo $product['SoLuongTon']; ?>" required>
            <br>
            <label for="TinhTrang">Tình trạng:</label>
            <input type="text" name="TinhTrang" value="<?php echo $product['TinhTrang']; ?>" required>
            <br>
            <input type="submit" value="Cập nhật">
        </form>
    </div>
</body>
</html>