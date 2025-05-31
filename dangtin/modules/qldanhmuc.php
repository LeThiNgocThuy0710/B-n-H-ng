<?php
session_start();
require_once '../../modal/ketnoi.php';

// Check if user is logged in and has role 2
if (!isset($_SESSION['MaNguoiDung']) || $_SESSION['role'] !== '2') {
    header('Location: login.php');
    exit();
}

$conn = new clsKetnoi();
$connection = $conn->moketnoi();

// Function to get all categories
function getCategories($connection) {
    $sql = "SELECT * FROM loaisanpham ORDER BY MaLoaiSP";
    $result = $connection->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

$categories = getCategories($connection);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $tenLoai = $connection->real_escape_string($_POST['tenLoai']);
                $sql = "INSERT INTO loaisanpham (TenLoaiSP) VALUES ('$tenLoai')";
                $connection->query($sql);
                break;
            case 'edit':
                $maLoai = intval($_POST['maLoai']);
                $tenLoai = $connection->real_escape_string($_POST['tenLoai']);
                $sql = "UPDATE loaisanpham SET TenLoaiSP = '$tenLoai' WHERE MaLoaiSP = $maLoai";
                $connection->query($sql);
                break;
            case 'delete':
                $maLoai = intval($_POST['maLoai']);
                $sql = "DELETE FROM loaisanpham WHERE MaLoaiSP = $maLoai";
                $connection->query($sql);
                break;
        }
        header('Location: quan_ly_loai_san_pham.php');
        exit();
    }
}

$conn->dongketnoi($connection);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý loại sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <div class="container mt-5">
    <h1 class="mb-4">Quản lý loại sản phẩm</h1>
        
        <!-- Add Category Form -->
        <form id="addCategoryForm" class="mb-4" enctype="multipart/form-data">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <input type="text" class="form-control" name="tenLoai" placeholder="Tên loại sản phẩm mới" required>
                </div>
                <div class="col-auto">
                    <input type="file" class="form-control" name="hinhLoaiSP" accept="image/*">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Thêm loại sản phẩm</button>
                </div>
            </div>
        </form>

        <!-- Categories Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên loại sản phẩm</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="categoriesTableBody">
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['MaLoaiSP']); ?></td>
                        <td>
                            <?php if ($category['HinhLoaiSP']): ?>
                                <img src="../../images/<?php echo htmlspecialchars($category['HinhLoaiSP']); ?>" alt="<?php echo htmlspecialchars($category['TenLoaiSP']); ?>" class="category-image" width="50px">
                            <?php else: ?>
                                <span>Không có hình</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($category['TenLoaiSP']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-category" data-id="<?php echo $category['MaLoaiSP']; ?>">Sửa</button>
                            <button class="btn btn-sm btn-danger delete-category" data-id="<?php echo $category['MaLoaiSP']; ?>">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa loại sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm" enctype="multipart/form-data">
                        <input type="hidden" id="editCategoryId" name="maLoai">
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label">Tên loại sản phẩm</label>
                            <input type="text" class="form-control" id="editCategoryName" name="tenLoai" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCategoryImage" class="form-label">Hình ảnh loại sản phẩm</label>
                            <input type="file" class="form-control" id="editCategoryImage" name="hinhLoaiSP" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <img id="currentCategoryImage" src="" alt="Current Image" style="max-width: 100px; display: none;">
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Add Category
            $('#addCategoryForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('action', 'add');
                $.ajax({
                    url: 'category_actions.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        location.reload();
                    }
                });
            });

            // Edit Category
            $('.edit-category').click(function() {
                var id = $(this).data('id');
                var name = $(this).closest('tr').find('td:nth-child(3)').text();
                var imgSrc = $(this).closest('tr').find('img').attr('src');
                $('#editCategoryId').val(id);
                $('#editCategoryName').val(name);
                if (imgSrc) {
                    $('#currentCategoryImage').attr('src', imgSrc).show();
                } else {
                    $('#currentCategoryImage').hide();
                }
                $('#editCategoryModal').modal('show');
            });

            $('#editCategoryForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('action', 'edit');
                $.ajax({
                    url: 'category_actions.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        location.reload();
                    }
                });
            });

            // Delete Category
            $('.delete-category').click(function() {
                if (confirm('Bạn có chắc chắn muốn xóa loại sản phẩm này?')) {
                    var id = $(this).data('id');
                    $.post('category_actions.php', { action: 'delete', maLoai: id }, function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }, 'json');
                }
            });
        });
    </script>
</body>
</html>

