# Deploying to Railway.app

This project has been configured for deployment on Railway.

## 1. Setup

Your application code has been updated to:
- Trust Railway's load balancer (allowing HTTPS to work correctly).
- Force HTTPS on generated links in production.

## 2. Environment Variables

When you create a new project on Railway, add a **MySQL** service and a **GitHub** service (connected to this repo).

In your **Laravel Service** settings, add the following Environment Variables (Variables tab):

| Variable | Value |
|Ref | Ref |
| `APP_NAME` | `Laravel` |
| `APP_ENV` | `production` |
| `APP_KEY` | *(Copy this from your local .env file)* |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://your-project-url.up.railway.app` |
| `LOG_CHANNEL` | `stderr` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |

*Note: The variables like `${{MySQL.MYSQLHOST}}` are Railway Reference Variables. You can just type `MySQL` in the value field and Railway will autocomplete the correct connection details.*

## 3. Important Config

Railway uses **Nixpacks** by default to build Laravel projects. It will automatically:
- Install PHP, Composer, and Node.js
- Run `composer install` & `npm install`
- Run `npm run build`
- Configure Nginx and PHP-FPM

### Migrations
You should run migrations as part of your deployment.
Go to **Settings** > **Deploy** > **Deploy Command** (or "Start Command" depending on preference, but usually a custom build command is safer):

Recommended **Deploy Command**:
```bash
php artisan migrate --force && php artisan storage:link
```
(Or run these manually in the Railway CLI/Console after deployment).
