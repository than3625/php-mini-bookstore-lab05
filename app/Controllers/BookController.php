<?php

class BookController
{
    private function repository(): BookRepository
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = (new Database($config))->getConnection();
        return new BookRepository($pdo);
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

        $books = $repo->getPaginated($q, $perPage, $offset, $sort, $direction);

        view('books/index', compact('books', 'q', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }

    public function create(): void
    {
        $errors = [];
        $old = ['title' => '', 'author' => '', 'isbn' => '', 'price' => '', 'status' => 'available'];
        view('books/create', compact('errors', 'old'));
    }

    public function store(): void
    {
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];

        if (!empty($errors)) {
            view('books/create', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->create($data['values']);
            flash_set('success', 'Sách đã được thêm vào thư viện thành công.');
            redirect('/books');
        } catch (DuplicateRecordException $e) {
            $errors['isbn'] = "Mã sách (ISBN) '" . $old['isbn'] . "' này đã tồn tại trên một cuốn sách khác rồi.";
            view('books/create', compact('errors', 'old'));
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            view('errors/500');
        }
    }

    private function validate(array $input): array
    {
        $values = [
            'title'  => trim($input['title'] ?? ''),
            'author' => trim($input['author'] ?? ''),
            'isbn'   => trim($input['isbn'] ?? ''),
            'price'  => trim($input['price'] ?? ''),
            'status' => trim($input['status'] ?? 'available'),
        ];
        $errors = [];
        $allowedStatuses = ['available', 'out_of_stock'];

        if ($values['title'] === '') {
            $errors['title'] = 'Vui lòng nhập tên tiêu đề sách.';
        }
        if ($values['author'] === '') {
            $errors['author'] = 'Vui lòng nhập tên tác giả.';
        }
        if ($values['isbn'] === '') {
            $errors['isbn'] = 'Vui lòng nhập mã định danh sách quốc tế (ISBN).';
        }
        if ($values['price'] === '' || !is_numeric($values['price']) || (float)$values['price'] < 0) {
            $errors['price'] = 'Giá tiền phải là một số và lớn hơn hoặc bằng 0.';
        }
        if (!in_array($values['status'], $allowedStatuses, true)) {
            $errors['status'] = 'Trạng thái sách không hợp lệ.';
        }

        return ['values' => $values, 'errors' => $errors];
    }

    public function edit(): void
    {
        $id = max(0, (int)($_GET['id'] ?? 0));

        $repo = $this->repository();
        $book = $repo->findById($id);

        if (!$book) {
            http_response_code(404);
            view('errors/404', []);
            return;
        }

        $errors = [];

        $old = [
            'id'     => $book['id'],
            'title'  => $book['title'],
            'author' => $book['author'],
            'isbn'   => $book['isbn'],
            'price'  => $book['price'],
            'status' => $book['status']
        ];

        view('books/edit', compact('errors', 'old'));
    }

    public function update(): void
    {
        $id = max(0, (int)($_POST['id'] ?? 0));

        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];
        $old['id'] = $id;

        if (!empty($errors)) {
            view('books/edit', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->update($id, $data['values']);
            flash_set('success', 'Cập nhật thông tin sách thành công.');
            redirect('/books');
        } catch (DuplicateRecordException $e) {
            $errors['isbn'] = "Mã sách (ISBN) '" . $old['isbn'] . "' này đã tồn tại trên một cuốn sách khác rồi.";
            view('books/edit', compact('errors', 'old'));
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            view('errors/500', []);
        }
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            view('errors/405', []);
            return;
        }

        $id = max(0, (int)($_POST['id'] ?? 0));

        try {
            $this->repository()->delete($id);
            flash_set('success', 'Đã xóa sách khỏi thư viện thành công.');
            redirect('/books');
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            view('errors/500', []);
        }
    }
}