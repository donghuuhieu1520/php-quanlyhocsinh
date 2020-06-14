# php-quanlyhocsinh

Web quản lý hoạt động nề nếp của học sinh trong trường THPT

# Requirement
- composer
- php > 7
- mysql 5.6

# Usages

- git clone  https://github.com/donghuuhieu1520/php-quanlyhocsinh.git
- add .env file at root, it should look like this

<pre>
<code>
DB_USER=root
DB_PASS=xxxxxxxxx
DB_HOST=localhost:3306
DB_DRIVER=pdo_mysql
ENVIRONMENT=development
</code>
</pre>

- composer install
- vendor/bin/doctrine orm:schema-tool:drop --force
- vendor/bin/doctrine orm:schema-tool:create

