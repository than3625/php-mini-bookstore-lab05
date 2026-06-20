<?php

class OrderController
{
    private function repository(): OrderRepository
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = (new Database($config))->getConnection();
        return new OrderRepository($pdo);
    }
    
    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;
        $sort = $_GET['sort'] ?? 'created_at';
        $direction = $_GET['direction'] ?? 'desc';
        $offset = ($page - 1) * $perPage;

        $repo = $this->repository();
        $total = $repo->countAll($q);
        $totalPages = max(1, (int) ceil($total / $perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $perPage;
        }

        $orders = $repo->getPaginated($q, $perPage, $offset, $sort, $direction);

        view('orders/index', compact('orders', 'q', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }

    public function create(): void
    {
        $errors = [];
        $old = ['order_code' => '', 'customer_name' => '', 'customer_email' => '', 'total_amount' => 0, 'status' => 'pending'];
        view('orders/create', compact('errors', 'old'));
    }

    public function store(): void
    {
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];

        if (!empty($errors)) {
            view('orders/create', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->create($old);
            flash_set('success', 'Hóa đơn đã được khởi tạo thành công.');
            redirect('/orders');
        } catch (DuplicateRecordException $e) {
            $errors['order_code'] = 'Mã đơn hàng này đã tồn tại trong hệ thống.';
            view('orders/create', compact('errors', 'old'));
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            view('errors/500');
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            view('errors/400');
            return;
        }

        $order = $this->repository()->findById($id);
        if (!$order) {
            http_response_code(404);
            view('errors/404');
            return;
        }

        $errors = [];
        $old = [
            'id'             => $order['id'],
            'order_code' => $order['order_code'],
            'customer_name' => $order['customer_name'],
            'customer_email' => $order['customer_email'],
            'total_amount' => $order['total_amount'],
            'status' => $order['status'],
        ];
        view('orders/edit', compact('errors', 'old'));
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            view('errors/400');
            return;
        }

        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];
        $old['id'] = $id;

        if (!empty($errors)) {
            view('orders/edit', compact('errors', 'old', 'id'));
            return;
        }

        try {
            $values = $data['values'];
            $values['id'] = $id;
            $this->repository()->update($id, $values);
            flash_set('success', 'Cập nhật thông tin đơn hàng thành công.');
            redirect('/orders');
        } catch (DuplicateRecordException $e) {
            $errors['order_code'] = 'Mã đơn hàng này đã tồn tại.';
            view('orders/edit', compact('errors', 'old', 'id'));
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            view('errors/500');
        }
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            view('errors/405');
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            view('errors/400');
            return;
        }

        try {
            $this->repository()->delete($id);
            flash_set('success', 'Đã xóa đơn hàng thành công.');
            redirect('/orders');
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            view('errors/500');
        }
    }

    private function validate(array $input): array
    {
        $values = [
            'order_code' => trim($input['order_code'] ?? ''),
            'customer_name' => trim($input['customer_name'] ?? ''),
            'customer_email' => trim($input['customer_email'] ?? ''),
            'total_amount' => (float) ($input['total_amount'] ?? 0),
            'status' => trim($input['status'] ?? 'pending'),
        ];
        $errors = [];

        if ($values['order_code'] === '') {
            $errors['order_code'] = 'Vui lòng nhập mã đơn hàng.';
        }
        if ($values['customer_name'] === '') {
            $errors['customer_name'] = 'Vui lòng nhập tên khách hàng.';
        }
        if ($values['customer_email'] !== '' && !filter_var($values['customer_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['customer_email'] = 'Email khách hàng không đúng định dạng.';
        }
        if ($values['total_amount'] < 0) {
            $errors['total_amount'] = 'Tổng tiền không được âm.';
        }

        return ['values' => $values, 'errors' => $errors];
    }
}