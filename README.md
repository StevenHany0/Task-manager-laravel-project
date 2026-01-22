# Task Manager Laravel Application

## Overview

The **Task Manager** is a Laravel-based web application that provides comprehensive task management capabilities with user authentication, role-based access control, and organizational features. It enables users to create, organize, and track tasks through a RESTful API architecture.

This README serves as the main entry point for understanding the system before diving into specific subsystems.

---

## Purpose & Scope

The application is designed to:

* Allow users to manage personal tasks efficiently.
* Support categorization, prioritization, and favorites.
* Provide administrative oversight and system-wide management.
* Enforce secure access using token-based authentication and role checks.

### User Types

* **Regular Users:**

  * Create and manage personal tasks.
  * Organize tasks using categories.
  * Mark tasks as favorites.
  * Maintain user profiles.

* **Admin Users:**

  * View and manage all tasks across users.
  * Access system-wide administrative features.

---

## Core Features

| Feature             | Description                                                       | Primary Components                                            |
| ------------------- | ----------------------------------------------------------------- | ------------------------------------------------------------- |
| User Management     | Registration, authentication, API token access                    | `UserController`, `User` model                                |
| Task Operations     | CRUD operations, priority (high/medium/low), ownership validation | `TaskController`, `Task` model                                |
| Category System     | Many-to-many task categorization                                  | `CategoryController`, `Category` model, `category_task` pivot |
| Task Favorites      | User-specific task bookmarking                                    | `favorites` pivot table                                       |
| User Profiles       | Extended user info with image uploads                             | `ProfileController`, `Profile` model                          |
| Role-Based Access   | Admin vs user permissions                                         | `checkUserRole`, `isAdmin` middleware                         |
| Email Notifications | Welcome emails on registration                                    | `WelcomeMail` mailable                                        |

---

## Technology Stack

### Framework & Runtime

* **Laravel Framework:** v12.x
* **PHP:** 8.2, 8.3, 8.4 (tested)
* **Database:** MySQL / SQLite
* **Authentication:** Laravel Sanctum (token-based)
* **Frontend Tooling:** Vite, Axios

### Key Laravel Components

| Component     | Purpose                             | Location               |
| ------------- | ----------------------------------- | ---------------------- |
| Eloquent ORM  | Data access and relationships       | `app/Models/`          |
| Form Requests | Input validation                    | `app/Http/Requests/`   |
| Middleware    | Request filtering and authorization | `app/Http/Middleware/` |
| Mailables     | Email notifications                 | `app/Mail/`            |
| Migrations    | Database schema versioning          | `database/migrations/` |

---

## System Architecture

The application follows Laravel’s **MVC architecture** with clear separation of concerns.

### Architecture Layers

| Layer          | Components                      | Purpose                             |
| -------------- | ------------------------------- | ----------------------------------- |
| Presentation   | Blade templates, API responses  | User interaction and output         |
| Application    | Controllers, middleware, routes | Request handling and business logic |
| Domain         | Eloquent models                 | Business entities and relationships |
| Infrastructure | Database, storage, sessions     | Persistence and framework services  |

---

## Domain Models & Relationships

The data layer consists of four primary models:

| Model    | Purpose                      | Key Relationships                           |
| -------- | ---------------------------- | ------------------------------------------- |
| User     | Represents application users | Has many tasks, has one profile             |
| Task     | Represents individual tasks  | Belongs to user, belongs to many categories |
| Category | Organizes tasks              | Belongs to many tasks                       |
| Profile  | Stores extended user info    | Belongs to user                             |

### Relationships (Textual ER Diagram)

```text
User (1) ────< (∞) Task >──── (∞) >──── Category
  │
  │
  └──── (1) Profile

User has many Tasks
Task belongs to User
Task belongsToMany Categories
Category belongsToMany Tasks
User has one Profile
Profile belongs to User
```

---

## Controller Responsibilities

| Controller         | Primary Methods                                                          | Responsibilities                                   |
| ------------------ | ------------------------------------------------------------------------ | -------------------------------------------------- |
| UserController     | `register()`, `login()`, `logout()`                                      | User auth, token generation, welcome emails        |
| TaskController     | `index()`, `store()`, `show()`, `update()`, `destroy()`, `getAllTasks()` | Task CRUD, ownership validation, priority handling |
| CategoryController | `index()`, `store()`, `show()`, `update()`, `destroy()`                  | Category CRUD                                      |
| ProfileController  | `show()`, `store()`, `update()`                                          | Profile management, image uploads                  |

---

## Authentication & Authorization

The system uses a multi-layered security model:

* **Authentication:** Laravel Sanctum token-based authentication via `HasApiTokens` trait.
* **Route Protection:** `auth:sanctum` middleware on protected API routes.
* **Authorization:** Custom `checkUserRole` middleware for admin-only routes.
* **Ownership Validation:** Controller-level checks ensure users modify only their own data.

---

## API Endpoints

> All protected routes require a valid Sanctum token.

### Authentication

* `POST /api/register`
* `POST /api/login`
* `POST /api/logout`

### Tasks

* `GET /api/tasks` – List user tasks
* `POST /api/tasks` – Create task
* `GET /api/tasks/{id}` – View task
* `PUT /api/tasks/{id}` – Update task
* `DELETE /api/tasks/{id}` – Delete task
* `GET /api/admin/tasks` – View all tasks (admin only)

### Categories

* `GET /api/categories`
* `POST /api/categories`
* `PUT /api/categories/{id}`
* `DELETE /api/categories/{id}`

### Profiles

* `GET /api/profile`
* `POST /api/profile`
* `PUT /api/profile`

---

## Validation Rules (Example: Tasks)

```php
$request->validate([
    'title' => 'required|string|max:255',
    'description' => 'nullable|string',
    'priority' => 'required|in:high,medium,low',
    'categories' => 'array|exists:categories,id'
]);
```

---

## Database Schema Overview

### Main Tables

#### `users`

| Column     | Type   | Description          |
| ---------- | ------ | -------------------- |
| id         | bigint | Primary key          |
| name       | string | User name            |
| email      | string | Unique email         |
| password   | string | Hashed password      |
| role       | string | user / admin         |
| timestamps | —      | Created & updated at |

#### `tasks`

| Column      | Type   | Description          |
| ----------- | ------ | -------------------- |
| id          | bigint | Primary key          |
| user_id     | bigint | FK → users.id        |
| title       | string | Task title           |
| description | text   | Task description     |
| priority    | string | high / medium / low  |
| timestamps  | —      | Created & updated at |

#### `categories`

| Column     | Type   | Description          |
| ---------- | ------ | -------------------- |
| id         | bigint | Primary key          |
| name       | string | Category name        |
| timestamps | —      | Created & updated at |

#### `category_task` (Pivot Table)

| Column      | Type   | Description        |
| ----------- | ------ | ------------------ |
| category_id | bigint | FK → categories.id |
| task_id     | bigint | FK → tasks.id      |

#### `favorites` (Pivot Table)

| Column  | Type   | Description   |
| ------- | ------ | ------------- |
| user_id | bigint | FK → users.id |
| task_id | bigint | FK → tasks.id |

#### `profiles`

| Column     | Type   | Description          |
| ---------- | ------ | -------------------- |
| id         | bigint | Primary key          |
| user_id    | bigint | FK → users.id        |
| bio        | text   | User bio             |
| image      | string | Profile image path   |
| timestamps | —      | Created & updated at |

---

## File Organization

```text
task-manager-laravel-project/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Request handlers
│   │   ├── Middleware/       # Request filters
│   │   └── Requests/         # Validation classes
│   ├── Mail/                 # Email templates
│   └── Models/               # Eloquent models
├── database/
│   └── migrations/           # Schema definitions
├── routes/
│   ├── api.php               # API endpoints
│   └── web.php               # Web routes
├── storage/
│   └── app/public/           # Uploaded files
└── .github/workflows/        # CI/CD automation
```

---

## CI/CD & Quality Assurance

The project includes automated workflows:

| Workflow           | File                                     | Purpose                            |
| ------------------ | ---------------------------------------- | ---------------------------------- |
| Testing            | `.github/workflows/tests.yml`            | PHP matrix testing (8.2, 8.3, 8.4) |
| Issue Management   | `.github/workflows/issues.yml`           | Automated issue labeling           |
| PR Validation      | `.github/workflows/pull-requests.yml`    | Pull request checks                |
| Release Automation | `.github/workflows/update-changelog.yml` | Changelog automation               |

### Code Quality Standards

* `.editorconfig` – Formatting rules
* `.styleci.yml` – Laravel style enforcement
* `.gitattributes` – Git optimizations
* `phpunit.xml` – Test configuration

---

## Installation & Setup

### Prerequisites

* PHP ^8.2
* Composer
* MySQL or SQLite

### Setup Steps

```bash
# 1. Clone the repository
git clone <your-repo-url>
cd task-manager-laravel-project

# 2. Install backend dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure database in .env
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# 6. Run migrations
php artisan migrate

# 7. (Optional) Seed database
php artisan db:seed

# 8. Run development server
php artisan serve
```

Visit: `http://127.0.0.1:8000`

---



## 📚 System Architecture & Design (PDFs)

- 🔄 [Request Flow with Code Entities](docs/Request%20Flow%20with%20Code%20Entities.pdf)
- 🔐 [Authentication and Authorization Layers](docs/Authentication%20and%20Authorization%20Layers.pdf)
- 🧩 [Domain Models and Relationships](docs/Domain%20Models%20and%20Relationships.pdf)


---

## Contribution Guidelines

1. Fork the repository.
2. Create a new branch: `git checkout -b feature-name`.
3. Commit your changes: `git commit -m "Add new feature"`.
4. Push to your branch: `git push origin feature-name`.
5. Open a Pull Request.

---

## License

This project is open-source and available under the **MIT License**.

---

*Maintained by Steven Hany Elia*
