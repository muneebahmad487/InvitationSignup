
# Invitation via Email and Register User

A basic system to send Invitation link to user via email from Administrator of the system that allows user to register in the syste by the link sent in email.

### Setup
---
Clone the repo and follow below steps.
1. Run `composer install`
2. Copy `.env.example` to `.env` Example for linux users : `cp .env.example .env`
3. Set valid database credentials of env variables `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`
4. Run `php artisan key:generate` to generate application key
5. Run `php artisan migrate`
6. Run `php artisan db:seed` to seed your database

Thats it... Run the command `php artisan serve` and cheers, you are good to go with your new **Laravel Admin Panel** application.
