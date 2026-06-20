<?php
session_start();

// 1. Nạp các file Core cốt lõi của hệ thống
require __DIR__ . '/../app/Core/helpers.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/Database.php';
require __DIR__ . '/../app/Core/DuplicateRecordException.php';

// 2. Đổi LeadRepository thành BookRepository đúng bài Bookstore nha Than
require __DIR__ . '/../app/Repositories/BookRepository.php';
require __DIR__ . '/../app/Repositories/OrderRepository.php';

// 3. Đổi LeadController thành BookController luôn nè bà
require __DIR__ . '/../app/Controllers/HomeController.php';
require __DIR__ . '/../app/Controllers/HealthController.php';
require __DIR__ . '/../app/Controllers/BookController.php';
require __DIR__ . '/../app/Controllers/OrderController.php';

$router = new Router();

// --- ĐỊNH TUYẾN HỆ THỐNG (ROUTING) ---

// Trang chủ và Kiểm tra trạng thái DB (Task T06)
$router->get('/', [HomeController::class, 'index']);
$router->get('/health', [HealthController::class, 'index']);

// MODULE A: Quản lý Kho Sách (Đổi toàn bộ từ leads sang books)
$router->get('/books', [BookController::class, 'index']);
$router->get('/books/create', [BookController::class, 'create']);
$router->post('/books/store', [BookController::class, 'store']);
$router->get('/books/edit', [BookController::class, 'edit']);
$router->post('/books/update', [BookController::class, 'update']);
$router->post('/books/delete', [BookController::class, 'delete']);

// MODULE B: Quản lý Hóa đơn Đặt hàng
$router->get('/orders', [OrderController::class, 'index']);
$router->get('/orders/create', [OrderController::class, 'create']);
$router->post('/orders/store', [OrderController::class, 'store']);
$router->get('/orders/edit', [OrderController::class, 'edit']);
$router->post('/orders/update', [OrderController::class, 'update']);
$router->post('/orders/delete', [OrderController::class, 'delete']);


// --- XỬ LÝ KHỐI ĐIỀU HƯỚNG BẢO MẬT (DISPATCH & CATCH 500) ---
$isProduction = true; 
// - false: Môi trường Development (Hiện lỗi thô để dễ debug sửa code)
// - true: Môi trường Production (Chặn lỗi hệ thống nhạy cảm, tăng tính bảo mật)
try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (\Throwable $e) {
    error_log($e->getMessage());
    if ($isProduction === false) {
        throw $e;
    } else {
        http_response_code(500);
        require __DIR__ . '/../app/Views/errors/500.php';
    }
}