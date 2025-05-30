

This project is a Laravel + Inertia.js + Vue 3 application for managing forms with admin-only access.

## Prerequisites

- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL or other supported database

## Setup Instructions

### 1. Clone the Repository

```sh
git clone https://github.com/Mahedi250/Form-builder.git
cd form
```

### 2. Install PHP Dependencies

```sh
composer install
```

### 3. Install Node Dependencies

```sh
npm install
```

### 4. Copy and Configure Environment File

```sh
cp .env.example .env
```

Edit `.env` and set your database credentials and other settings.

### 5. Generate Application Key

```sh
php artisan key:generate
```

### 6. Run Migrations and Seeders

This will create all tables and seed an admin user and example forms:

```sh
php artisan migrate:fresh --seed
```

### 7. Build Frontend Assets

For development (with hot reload):

```sh
npm run dev
```

For production:

```sh
npm run build
```

### 8. Start the Laravel Server

```sh
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000) in your browser.

---

## Default Admin Login

- **Email:** admin@test.com
- **Password:** password

> Change the password after first login for security!

---

## Features

- Admin-only form management (using Spatie Laravel Permission)
- Inertia.js + Vue 3 frontend
- Example forms and fields seeded
- Role-based access control

---

