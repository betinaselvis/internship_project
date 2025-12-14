# Internship Project — Signup / Login / Profile

This project demonstrates a simple registration and login system using:
- Frontend: HTML, Bootstrap, CSS, jQuery AJAX
- Backend: PHP (MySQL with prepared statements, MongoDB, Redis)
- Local session on client-side: `localStorage`

Flow: Register → Login → Profile (update)

Prereqs:
- XAMPP (PHP + MySQL)
- Redis server running (localhost)
- MongoDB Atlas connection string
- PHP extensions: `pdo_mysql`, `mongodb`, `redis` (phpredis) or use `predis` library
- Composer if you need to install the MongoDB PHP library (`composer require mongodb/mongodb`) or Predis (`composer require predis/predis`) and `ext-mongodb` PECL extension

If you need to install dependencies via Composer:

```bash
cd /path/to/internship_project
composer require mongodb/mongodb
composer require predis/predis
```

And enable php extensions (php.ini):
- extension=redis
- extension=mongodb

Then restart Apache from XAMPP Control Panel.

Configuration:
- Edit `api/config.php` and update `MYSQL_*`, `MONGODB_URI`, `REDIS_*` values according to your environment.
- Make sure your MySQL database is created and you ran the SQL in `db/schema.sql`.
- If you’re using Atlas, replace the placeholder with your connection string.

Setup
1. Copy the `internship_project` folder to your XAMPP `htdocs` directory.
2. Configure `api/config.php` with MySQL and MongoDB settings.
3. Create MySQL database and run the SQL below to create the `users` table:

```sql
CREATE DATABASE IF NOT EXISTS internship_project;
USE internship_project;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

4. Start XAMPP and Redis.
5. Visit `http://localhost/internship_project/signup.html` to register.

Quick API examples (curl)

Register:

```bash
curl -X POST -d "username=testuser&email=test@example.com&password=secret" http://localhost/internship_project/api/register.php
```

Login (returns token):

```bash
curl -X POST -d "email=test@example.com&password=secret" http://localhost/internship_project/api/login.php
```

Get Profile (POST token):

```bash
curl -X POST -d "token=<TOKEN>" http://localhost/internship_project/api/profile.php
```

Update Profile (POST token):

```bash
curl -X POST -d "token=<TOKEN>&full_name=Your%20Name&age=30&dob=1995-01-01&contact=1234567890&address=Some%20Address" http://localhost/internship_project/api/profile_update.php
```

Notes
- All PHP endpoints return JSON.
- The login session token is stored in Redis as `session:<token> => user_id` with TTL (set in `api/config.php`).
- Client stores `auth_token` and `user_id` in `localStorage` to maintain session.

If Redis PHP extension is not available, you can install `predis/predis` with Composer and adjust config.php accordingly.
