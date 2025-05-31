<?php
session_start();
require_once '../../modal/ketnoi.php';

// Check if user is logged in and has role 2
if (!isset($_SESSION['MaNguoiDung']) || $_SESSION['role'] !== '2') {
    die('Unauthorized access');
}

$conn = new clsKetnoi();
$connection = $conn->moketnoi();

function uploadImage($file) {
    $target_dir = "../../images/";
    $name = basename($file["name"]);
    $target_file = $target_dir.basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return false;
    }
    
    // Check file size
    if ($file["size"] > 500000) { // 500KB limit
        return false;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        return false;
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $name;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            $tenLoai = $connection->real_escape_string($_POST['tenLoai']);
            $hinhLoaiSP = null;
            if (isset($_FILES['hinhLoaiSP']) && $_FILES['hinhLoaiSP']['error'] == 0) {
                $hinhLoaiSP = uploadImage($_FILES['hinhLoaiSP']);
            }
            $sql = "INSERT INTO loaisanpham (TenLoaiSP, HinhLoaiSP) VALUES ('$tenLoai', " . ($hinhLoaiSP ? "'$hinhLoaiSP'" : "NULL") . ")";
            $connection->query($sql);
            echo json_encode(['success' => true]);
            break;

        case 'edit':
            $maLoai = intval($_POST['maLoai']);
            $tenLoai = $connection->real_escape_string($_POST['tenLoai']);
            $hinhLoaiSP = null;
            if (isset($_FILES['hinhLoaiSP']) && $_FILES['hinhLoaiSP']['error'] == 0) {
                $hinhLoaiSP = uploadImage($_FILES['hinhLoaiSP']);
            }
            $sql = "UPDATE loaisanpham SETnLoaiSP = '$tenLoai'" . ($hinhLoaiSP ? ", HinhLoaiSP = '$hinhLoaiSP'" : "") . " WHERE MaLoaiSP = $maLoai";
            $connection->query($sql);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $maLoai = intval($_POST['maLoai']);
            // Check if the category is being used in any products
            $checkSql = "SELECT COUNT(*) as count FROM sanpham WHERE MaLoaiSP = $maLoai";
            $result = $connection->query($checkSql);
            $row = $result->fetch_assoc();
            if ($row['count'] > 0) {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa loại sản phẩm này vì đang được sử dụng trong sản phẩm.']);
            } else {
                // Get the image file path before deleting the category
                $getImageSql = "SELECT HinhLoaiSP FROM loaisanpham WHERE MaLoaiSP = $maLoai";
                $imageResult = $connection->query($getImageSql);
                $imageRow = $imageResult->fetch_assoc();
                
                $sql = "DELETE FROM loaisanpham WHERE MaLoaiSP = $maLoai";
                if ($connection->query($sql)) {
                    // Delete the image file if it exists
                    if ($imageRow && $imageRow['HinhLoaiSP'] && file_exists($imageRow['HinhLoaiSP'])) {
                        unlink($imageRow['HinhLoaiSP']);
                    }
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không thể xóa loại sản phẩm.']);
                }
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

$conn->dongketnoi($connection);
