# Internship Project — Signup / Login / Profile System

This project implements a simple user authentication system with profile management. The frontend is built using HTML, CSS, Bootstrap, and JavaScript with jQuery AJAX, while the backend is implemented in PHP. MySQL is used as the primary database with prepared statements, Redis is used for session management, Apache (XAMPP) is used as the web server, and browser localStorage is used on the client side to store authentication tokens. The application follows the flow: Register → Login → Profile (View / Update).

## Prerequisites
To run this project locally, XAMPP (Apache and MySQL) must be installed, Ubuntu WSL must be available, Redis must be installed and running inside WSL, and the PHP extensions pdo_mysql and redis (phpredis) must be enabled. MongoDB is not used in this project.

## Local Setup and Execution
The project folder should be placed inside the XAMPP htdocs directory at `C:\xampp\htdocs\`. Apache and MySQL should be started from the XAMPP Control Panel. Redis should be started by opening Ubuntu (WSL) and running the command `redis-server`.

A MySQL database named `internship_project` must be created, and the required table should be set up using the following SQL:

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
