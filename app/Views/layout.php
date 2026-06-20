<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title><?= e($title ?? 'Bookstore Management System') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<nav class="navbar">
    <a href="/">Dashboard</a>
    <a href="/books">Books</a>
    <a href="/books/create">Create Book</a>
    <a href="/orders">Orders</a>
    <a href="/orders/create">Create Order</a>
    <a href="/health">Health</a>
</nav>
<main class="container">
    <?php if ($success = flash_get('success')): ?>
        <div class="alert success"><?= e($success) ?></div>
    <?php endif; ?>
    <?= $content ?? '' ?>
</main>
</body>
</html>
