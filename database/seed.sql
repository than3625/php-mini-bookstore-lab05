USE php_bookstore_lab05;

INSERT INTO users (name, email, password_hash, role)
VALUES
('Admin User', 'admin@example.com', '$2y$10$examplehashadmin', 'admin'),
('Sales Staff', 'sales@example.com', '$2y$10$examplehashstaff', 'staff');

INSERT INTO books (title, author, isbn, price, status) VALUES
('De Men Phieu Luu Ky', 'To Hoai', '9786042185523', 55000.00, 'available'),
('Dat Rung Phuong Nam', 'Doan Gioi', '9786042185608', 68000.00, 'available'),
('Tuoi Tho Du Doi', 'Phung Quan', '9786045634516', 115000.00, 'available'),
('Mat Biec', 'Nguyen Nhat Anh', '9786041123456', 90000.00, 'available'),
('Cho Toi Xin Mot Ve Di Tuoi Tho', 'Nguyen Nhat Anh', '9786041123463', 85000.00, 'available'),
('Kinh Van Hoa - Tap 1', 'Nguyen Nhat Anh', '9786041123470', 75000.00, 'available'),
('So Do', 'Vu Trong Phung', '9786049635229', 48000.00, 'out_of_stock'),
('Chi Pheo', 'Nam Cao', '9786049635236', 42000.00, 'available'),
('Tat Den', 'Ngo Tat To', '9786049635243', 45000.00, 'available'),
('Nha Gia Kim', 'Paulo Coelho', '9786043031232', 79000.00, 'available'),
('Dac Nhan Tam', 'Dale Carnegie', '9786045889312', 86000.00, 'available'),
('Hat Giong Tam Hon', 'Nhieu tac gia', '9786045889329', 62000.00, 'available'),
('Bat Tre Dong Xanh', 'J.D. Salinger', '9786043351111', 95000.00, 'out_of_stock'),
('Cha Giau Cha Ngheo', 'Robert Kiyosaki', '9786043351128', 125000.00, 'available'),
('Sach Den', 'Osho', '9786043351135', 150000.00, 'available'),
('Doc Vi Bat Ky Ai', 'David J. Lieberman', '9786043351142', 88000.00, 'available');

INSERT INTO orders (order_code, customer_name, customer_email, total_amount, status) VALUES
('ORD-2026-001', 'Nguyen Van A', 'anguyen@gmail.com', 123000.00, 'completed'),
('ORD-2026-002', 'Tran Thi B', 'btran@gmail.com', 75000.00, 'pending'),
('ORD-2026-003', 'Le Van C', 'cle@gmail.com', 215000.00, 'completed'),
('ORD-2026-004', 'Pham Minh D', 'dpham@gmail.com', 90000.00, 'cancelled'),
('ORD-2026-005', 'Hoang Ngo E', 'ehoang@gmail.com', 160000.00, 'pending'),
('ORD-2026-006', 'Vu Hoang F', 'fvu@gmail.com', 48000.00, 'completed'),
('ORD-2026-007', 'Dang Ngoc G', 'gdang@gmail.com', 320000.00, 'completed'),
('ORD-2026-008', 'Bui Tien H', 'hbui@gmail.com', 85000.00, 'pending'),
('ORD-2026-009', 'Ngo Gia I', 'ingo@gmail.com', 115000.00, 'completed'),
('ORD-2026-010', 'Quach Thanh J', 'jquach@gmail.com', 55000.00, 'cancelled'),
('ORD-2026-011', 'Do Thuy K', 'kdo@gmail.com', 250000.00, 'completed'),
('ORD-2026-012', 'Duong Quoc L', 'lduong@gmail.com', 138000.00, 'pending'),
('ORD-2026-013', 'Ly Hai M', 'mly@gmail.com', 95000.00, 'completed'),
('ORD-2026-014', 'Ta Minh N', 'ntam@gmail.com', 42000.00, 'completed'),
('ORD-2026-015', 'Phan Thanh O', 'ophan@gmail.com', 180000.00, 'pending'),
('ORD-2026-016', 'Nguyen Du P', 'pnguyen@gmail.com', 79000.00, 'completed');