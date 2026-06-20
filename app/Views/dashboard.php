<?php ob_start(); ?>

<h1>Bookstore Management Dashboard</h1>
<p>Hệ thống quản lý cửa hàng sách: Thực hành kết nối Database, viết tầng Repository, xử lý Form CRUD, Tìm kiếm, Phân trang và Bảo mật.</p>

<div class="grid" style="display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap;">

    <div class="card" style="padding: 20px; border: 1px solid #ccc; border-radius: 8px; width: 250px; background: #fff;">
        <h2>Database & PDO</h2>
        <p>Kết nối MySQL bằng PDO theo mô hình Singleton (T05), an toàn trước lỗ hổng SQL Injection, bắt lỗi DB thân thiện.</p>
    </div>

    <div class="card" style="padding: 20px; border: 1px solid #ccc; border-radius: 8px; width: 250px; background: #fff;">
        <h2>Repository Pattern</h2>
        <p>Tách biệt hoàn toàn logic truy vấn SQL của hai bảng (books, orders) ra khỏi Controller bằng lớp Repository riêng biệt (T07).</p>
    </div>

    <div class="card" style="padding: 20px; border: 1px solid #ccc; border-radius: 8px; width: 250px; background: #fff;">
        <h2>Secure CRUD Modules</h2>
        <p>Xây dựng phân hệ quản lý Sách và Đơn hàng đầy đủ tính năng, áp dụng PRG Pattern và bắt trùng Unique Key 1062.</p>
    </div>

    <div class="card" style="padding: 20px; border: 1px solid #ccc; border-radius: 8px; width: 250px; background: #fff;">
        <h2>Search & Pagination</h2>
        <p>Tìm kiếm đa trường (Tên sách/Tác giả/ISBN), phân trang hiệu năng cao, sắp xếp an toàn bằng cơ chế Whitelist Sort/Direction.</p>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Bookstore Dashboard';
require __DIR__ . '/layout.php';
?>