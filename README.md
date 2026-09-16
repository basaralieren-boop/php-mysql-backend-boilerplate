# PHP MySQL Backend Boilerplate

A clean and modern PHP MySQL backend boilerplate with best practices, following MVC architecture pattern.

## Features

- 🚀 Simple routing system
- 🔌 PDO database connection
- 📦 Model-View-Controller architecture
- 🔐 JSON response formatting
- ⚙️ Environment configuration
- 📝 Database migrations
- ✅ Input validation

## Directory Structure

```
php-mysql-backend-boilerplate/
├── public/
│   └── index.php          # Main entry point
├── app/
│   ├── Controllers/       # Application controllers
│   └── Models/            # Database models
├── core/
│   ├── Config.php         # Configuration loader
│   ├── Database.php       # Database connection
│   ├── Router.php         # Request router
│   └── Response.php       # Response helper
├── database/
│   └── migrations/        # Database migrations
├── routes/
│   └── api.php            # API routes
├── .env.example           # Environment variables example
└── composer.json          # PHP dependencies
```

## Installation

### Prerequisites
- PHP >= 8.1
- MySQL/MariaDB
- Composer

### Setup

1. **Clone the repository**
```bash
git clone https://github.com/basaralieren-boop/php-mysql-backend-boilerplate.git
cd php-mysql-backend-boilerplate
```

2. **Install dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp .env.example .env
```

Edit `.env` and set your database credentials:
```
DB_HOST=localhost
DB_PORT=3306
DB_NAME=your_database
DB_USER=root
DB_PASSWORD=your_password
```

4. **Create database**
```bash
mysql -u root -p -e "CREATE DATABASE your_database;"
```

5. **Run migrations**
```bash
mysql -u root -p your_database < database/migrations/001_create_users_table.sql
```

6. **Start the server**
```bash
php -S localhost:8000 -t public
```

The API will be available at `http://localhost:8000/api`

## Quick Start

### Creating a Model

Create a new file `app/Models/User.php`:

```php
<?php
namespace App\Models;

class User extends Model
{
    protected string $table = 'users';
    protected array $fillable = ['name', 'email', 'password'];
}
```

### Creating a Controller

Create a new file `app/Controllers/UserController.php`:

```php
<?php
namespace App\Controllers;

use App\Models\User;
use Core\Response;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        Response::success($users);
    }

    public function store()
    {
        $data = $this->getJsonInput();
        
        if (!$this->validate($data, [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ])) {
            return;
        }

        $user = User::create($data);
        Response::created($user, 'User created successfully');
    }
}
```

### Adding Routes

Add to `routes/api.php`:

```php
$router->get('/api/users', function () {
    (new \App\Controllers\UserController())->index();
});

$router->post('/api/users', function () {
    (new \App\Controllers\UserController())->store();
});
```

## API Response Format

### Success Response
```json
{
  "success": true,
  "message": "Success",
  "data": {}
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "data": null
}
```

## Helper Methods

### Database Queries
```php
use Core\Database;

// Fetch one record
$user = Database::fetch("SELECT * FROM users WHERE id = ?", [1]);

// Fetch multiple records
$users = Database::fetchAll("SELECT * FROM users");

// Execute query
Database::query("INSERT INTO users (name, email) VALUES (?, ?)", ['John', 'john@example.com']);
```

### Response Helper
```php
use Core\Response;

// Success response
Response::success($data, 'Success message');

// Created response (201)
Response::created($data);

// Error response
Response::error('Error message', 400);

// Validation error
Response::validation(['email' => ['Invalid email']]);

// Not found
Response::notFound();

// Unauthorized
Response::unauthorized();
```

## Testing

```bash
composer test
```

## Security Tips

- Always use prepared statements (PDO with ? placeholders)
- Validate and sanitize all user input
- Use environment variables for sensitive data
- Hash passwords using `password_hash()` and `password_verify()`
- Implement CORS headers for cross-origin requests
- Use HTTPS in production
- Keep dependencies updated

## License

MIT License

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

For issues and questions, please open an issue on GitHub.
