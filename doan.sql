SHOW TABLES;

INSERT INTO accounts (username, password, name ) VALUES
('admin', '123456', 'Ban nề nếp'),
('12B2', '123456', 'Lớp 12B2'),
('12B1', '123456', 'Lớp 12B1');


INSERT INTO classes (name) VALUES
('12B1'),
('12B2');

INSERT INTO roles (account, class ) VALUES
(1, 1),
(1, 2),
(3, 2),
(4, 1);