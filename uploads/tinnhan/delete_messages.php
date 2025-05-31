<?php
session_start();
require_once '../tinnhan/funtions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['MaNguoiDung'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secondhandn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối database']);
    exit();
}

$current_user_id = $_SESSION['MaNguoiDung'];
$message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;

if (!$message_id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin cần thiết']);
    exit();
}

$delete_query = "DELETE FROM tinnhan WHERE MaTinNhan = ? AND MaNguoiGui = ?";
$stmt = $conn->prepare($delete_query);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Lỗi chuẩn bị câu lệnh: ' . $conn->error]);
    exit();
}

$stmt->bind_param("ii", $message_id, $current_user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Tin nhắn đã được xóa']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể xóa tin nhắn. Có thể bạn không có quyền xóa tin nhắn này.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi xóa tin nhắn: ' . $stmt->error]);
}

$stmt->close();
$conn->close();

