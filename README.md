# Mini Issue Tracker

A small team issue tracker built for the PRITECH Laravel technical task.

## Features

- Project CRUD with start dates and deadlines
- Issue CRUD with status, priority, due date, and project assignment
- Issue filtering by status, priority, and tag
- Unique tags with custom colors
- AJAX tag attach/detach without a page reload
- AJAX comments with inline validation and pagination
- Demo factories and seed data
- Feature tests for the main workflows

## Stack

- PHP 8.3+
- Laravel 13
- SQLite
- Blade
- Tailwind CSS 4
- Vanilla JavaScript and Fetch API

## Local setup

```bash
git clone https://github.com/VesaGashii/issue-tracker-app.git
cd issue-tracker-app

composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate:fresh --seed

composer run dev
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

The seeded database contains three projects, several issues, five tags, and comments.

## Tests

```bash
php artisan test
vendor/bin/pint --test
npm run build
```

## Main routes

- `/projects` – manage projects and view their issues
- `/issues` – browse, filter, create, and manage issues
- `/tags` – create and list tags

On an issue detail page, comments and tag changes are handled through AJAX.

## Notes

- SQLite is used by default for quick local setup.
- Running `php artisan migrate:fresh --seed` deletes local database data and recreates the demo dataset.
- Authentication and assignment to users are optional bonus requirements and are not included in the core implementation.
