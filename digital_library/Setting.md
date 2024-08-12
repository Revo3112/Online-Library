# Digital Library Website

Welcome to the Digital Library Website project! This web application is designed to provide a platform for managing and browsing a digital collection of books.

## Features

- User authentication and authorization.
- Browse and search for books by category and title.
- Admin panel for managing categories and books.

## Prerequisites

Before you begin, ensure you have met the following requirements:

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- A web server (Apache or Nginx)

## Installation

### 1. Install Dependencies

Run the following command to install PHP dependencies using Composer:

```bash
composer install
```

### 2. Install Dependencies Tambahan

Run the following command to install PHP dependencies using Composer:

```bash
composer require phpoffice/phpspreadsheet
composer require dompdf/dompdf
```

### 3. Environment Configuration

settings in the `.env` file:

```plaintext
database.default.hostname = localhost
database.default.database = digital_lib
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

### 4. Import the Database

Import the `digital_lib.sql` file into your MySQL database:

### 5. Run the Application

Start the PHP built-in server or configure your web server:

```bash
php spark serve
```

Open your browser and visit `http://localhost:8080` to access the application.

## Account Settings

Use the following accounts to test the application:

### User Accounts

1. **Account One**

   - **Username**: Alex
   - **Password**: 12345678
   - **Role**: user
   - **Email**: test@gmail.com

2. **Account Two**
   - **Username**: Adam
   - **Password**: 123456789
   - **Role**: user
   - **Email**: test2@gmail.com

### Admin Account

3. **Admin Account**
   - **Username**: admin
   - **Password**: admin123
   - **Role**: admin
   - **Email**: admin@gmail.com

## Usage

- Users can add and search for books.
- Admins can manage categories, account and books through the admin panel.
