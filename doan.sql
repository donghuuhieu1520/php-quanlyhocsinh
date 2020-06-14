SHOW TABLES;

INSERT INTO Accounts (username, password, name ) VALUES
('admin', '123456', 'Ban nề nếp'),
('12B2', '123456', 'Lớp 12B2'),
('12B1', '123456', 'Lớp 12B1');


INSERT INTO Classes (name) VALUES
('12B1'),
('12B2');

SELECT * FROM Accounts;
SELECT * FROM Classes;

INSERT INTO ClassesToAccounts (account_id, class_id ) VALUES
(1, 1),
(1, 2),
(2, 2),
(3, 1);
