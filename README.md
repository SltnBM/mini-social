# Mini Social Media App
A simple PHP-based mini social media web application. This project allows users to register, login, create posts, and manage their profile with image uploads.

---

## Features
- User authentication (register, login, logout)
- User profile management (update username, password, profile image)
- Create and store posts
- Upload images for posts and profile pictures
- Session-based authentication middleware
- Simple and lightweight PHP structure

---

## Requirements
- PHP 8.x
- MySQL / MariaDB
- Apache / Nginx (or Laragon / XAMPP)

---

## How to Use
1. Make sure PHP, MySQL/MariaDB, and Apache/Nginx are installed.
2. Clone this repository
```bash
git clone https://github.com/SltnBM/mini-social.git
```
3. Move project to your server directory.
```bash
htdocs/ (XAMPP)
or
www/ (Laragon)
```
4. Create database and tables.
```bash
CREATE DATABASE mini_social;

USE mini_social;

CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE posts (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY user_id (user_id),
    CONSTRAINT fk_posts_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);
```
4. Configure database connection.
```bash
config/db.php
```
5. Run the project in browser.
```bash
http://localhost/mini-social-app
```
6. Register a new account and start using the app.

---

## Security Notes
- Passwords should be hashed using password_hash()
- Always validate user input
- Use prepared statements (PDO/MySQLi) to prevent SQL Injection
- Restrict file upload types for security

---

## Connect With Me
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Sultan%20Badra-blue?logo=linkedin\&logoColor=white\&style=flat-square)](https://www.linkedin.com/in/sultan-badra)

---

## License
This project is licensed under the MIT License. See the [LICENSE](./LICENSE) file for details.
