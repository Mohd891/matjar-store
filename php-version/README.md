# Matjar – PHP + MySQL version

The later version of the project: products, reviews, accounts and orders are stored in a MySQL database.
It needs a PHP server, so it **cannot run on GitHub Pages** (use the static version in the repository root for that).

## Run locally (XAMPP / MAMP / any PHP 8 + MySQL)
1. Copy this folder into your web root (e.g. `htdocs/matjar`).
2. Import `setup.sql` in phpMyAdmin (or `mysql -u root < setup.sql`). It creates the `matjardb` database with sample data.
3. Open `http://localhost/matjar/index.php`.

Database settings are in `dbcm.php` (default: `localhost`, user `root`, empty password, database `matjardb`).
They can be overridden with the environment variables `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`.

## Security fixes made to the original course version
- SQL queries use **prepared statements** (the original was open to SQL injection, e.g. login with `' OR '1'='1`).
- Passwords are stored with **`password_hash`** and checked with `password_verify` (originally plain text).
- Checkout **reads prices from the database**; prices sent by the browser are ignored, and the order is saved in a transaction.
- All database output is escaped with `htmlspecialchars` (XSS), database errors are logged instead of shown to visitors,
  the session id is regenerated on login, and inputs are validated on the server as well as in JavaScript.

Not covered (would be needed for a real shop): CSRF tokens, rate limiting on login, HTTPS, a non-root database user, real payment.
