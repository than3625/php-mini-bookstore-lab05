<?php
session_start();

require __DIR__ . '/../app/Core/helpers.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/Database.php';
require __DIR__ . '/../app/Core/DuplicateRecordException.php';

require __DIR__ . '/../app/Repositories/BookRepository.php';
require __DIR__ . '/../app/Repositories/OrderRepository.php';

require __DIR__ . '/../app/Controllers/HomeController.php';
require __DIR__ . '/../app/Controllers/HealthController.php';
require __DIR__ . '/../app/Controllers/BookController.php';
require __DIR__ . '/../app/Controllers/OrderController.php';

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/health', [HealthController::class, 'index']);

$router->get('/books', [BookController::class, 'index']);
$router->get('/books/create', [BookController::class, 'create']);
$router->post('/books/store', [BookController::class, 'store']);
$router->get('/books/edit', [BookController::class, 'edit']);
$router->post('/books/update', [BookController::class, 'update']);
$router->post('/books/delete', [BookController::class, 'delete']);

$router->get('/orders', [OrderController::class, 'index']);
$router->get('/orders/create', [OrderController::class, 'create']);
$router->post('/orders/store', [OrderController::class, 'store']);
$router->get('/orders/edit', [OrderController::class, 'edit']);
$router->post('/orders/update', [OrderController::class, 'update']);
$router->post('/orders/delete', [OrderController::class, 'delete']);

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