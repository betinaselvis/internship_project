# Internship Project — Signup / Login / Profile System

This project implements a simple user authentication system with profile management.

## Tech Stack
- Frontend: HTML, CSS, Bootstrap, JavaScript (jQuery AJAX)
- Backend: PHP
- Database: MySQL (using prepared statements)
- Session Store: Redis
- Server: Apache (XAMPP)
- Client-side session: Browser localStorage

## Project Flow
Register → Login → Profile (View / Update)

---

## Prerequisites
- XAMPP (Apache + MySQL)
- Ubuntu WSL
- Redis installed and running inside WSL
- PHP extensions:
  - pdo_mysql
  - redis (phpredis)

> MongoDB is **not used** in this project.

---

## How to Run the Project Locally

### 1. Project Setup
1. Clone this repository
2. Copy the project folder to:
C:\xampp\htdocs\

yaml
Copy code
3. Start **Apache** and **MySQL** from XAMPP Control Panel

---

### 2. Start Redis (WSL)
Open Ubuntu (WSL) and run:
```bash
redis-server
3. Database Setup
Create the database and table in MySQL:

sql
Copy code
CREATE DATABASE IF NOT EXISTS internship_project;
USE internship_project;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
4. Configuration
Edit api/config.php and update:

MySQL credentials

Redis host and port

5. Access the Application
Open browser and visit:

arduino
Copy code
http://localhost/internship_project/signup.html
Session Handling
On successful login, a token is generated

Token is stored:

In Redis (server-side) with TTL

In browser localStorage (client-side)

No PHP sessions are used (as per requirement)

Notes
All backend communication is handled via jQuery AJAX

No HTML form submission is used

All database queries use prepared statements

Redis is used only for session management
