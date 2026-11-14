# URL Shortener

A Laravel app where users of different companies can create short URLs. Registration is invite-only.

## Roles

SuperAdmin, Admin, Member.

- SuperAdmin creates companies and invites an Admin into a company. SuperAdmin cannot create short URLs.
- Admin can invite other Admins or Members into their own company, create short URLs, and see all short URLs of their company.
- Member can create short URLs and see only the ones they created.

## Stack

PHP 8.2+, Laravel 11, MySQL, Bootstrap (via CDN)

## Setup

```bash
git clone https://github.com/gajendrasingh8/URL-Shortner.git
cd URL-Shortner
composer install
cp .env.example .env
php artisan key:generate
```

Uncomment the DB_* lines in .env, then create an empty MySQL database and set DB_DATABASE, DB_USERNAME and DB_PASSWORD. Then run:
```bash
php artisan migrate --seed
php artisan serve
```

The app runs at `http://127.0.0.1:8000`.

## SuperAdmin login

- Email: `gajendraadmin@gmail.com`
- Password: `Superadmin@1`

The account is created by a seeder.

## How to use

1. Log in as SuperAdmin and create a company.
2. Invite an Admin for that company. No email is sent. The invitation link is shown on the screen after the invitation is created.
3. Open the link in an incognito window (a logged-in browser gets redirected to the dashboard), then enter a name and password to register.
4. Log in as the new Admin to invite Members or create short URLs.

## Tests

The tests need a separate database, otherwise `RefreshDatabase` will wipe the development data. Create an empty database (for example `url_shortener_test`), set its name in `phpunit.xml`, and run:

```bash
php artisan test
```

## Notes

- A new short code is generated every time, even for the same long URL.
- Invitations are not emailed. The link is displayed on screen.

## AI tools used

I used Claude for:
- Reviewing the UI design and test cases
- Drafting the first version of this README

I wrote the application code (migrations, models, controllers, policies, seeders, views) myself.