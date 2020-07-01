SHOW TABLES;

INSERT INTO Accounts (username, password, name ) VALUES
('admin', '$2y$11$coWIqdy85EWjAAqES/Ja1.FpewdvEUAdhbPW9HoeMC/v8yWzbVkZW', 'Ban nề nếp'),
('12B2', '$2y$11$coWIqdy85EWjAAqES/Ja1.FpewdvEUAdhbPW9HoeMC/v8yWzbVkZW', 'Lớp 12B2'),
('12B1', '$2y$11$coWIqdy85EWjAAqES/Ja1.FpewdvEUAdhbPW9HoeMC/v8yWzbVkZW', 'Lớp 12B1');


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

INSERT INTO Students (first_name, last_name, gender, phone,class_id) VALUES
("Dong Huu", "Hieu", true, "0981925281", 1),
("Nguyen Cuu Nhat", "Thanh", true, "0981925282", 1),
("Nguyen Ba Nhat", "Long", true, "0981925283", 1);

SELECT * FROM Accounts;

SELECT * FROM ACLs;

INSERT INTO ACLs (can_create_rule, can_read_rule, can_delete_rule, can_update_rule, can_read_student, can_create_student, can_delete_student, can_update_student, can_read_account, can_create_account, can_delete_account, can_update_account) VALUES
(true, true, true, true, true, true, true, true, true, true, true, true), -- acl of ban ne nep
(false, true, false, false, true, false, false, true, false, false, false, false), -- acl cho cac lop con lai
(false, true, false, false, true, false, false, true, false, false, false, false);

UPDATE Accounts SET acl_id = 1 WHERE id = 1;
UPDATE Accounts SET acl_id = 2 WHERE id = 2;
UPDATE Accounts SET acl_id = 3 WHERE id = 3;

