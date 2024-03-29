## FILESHARE APP

# About Fileshare

Fileshare is a web application developed for internal team use to share files.

# Features

- File Uploads
- File Downloads
- Copy link to clipboard
- Email notifications
- Access rights and permission
- User email verification
- Search filters
- Bulk Delete

# Build

This application was developed with Laravel 8, PHP 7.4 and MYSQL 5.7.

# Installation

- Clone Repository
    > `git clone https://github.com/Eric-Josh/file-share-v2`

- Install all dependencies
    > `cd file-share-v2`

    > `composer install or composer update`

    > `npm install`

- Create DB

- Copy .env.example to .env
    > `cp .env.example .env`

- Generate APP_KEY
    > `php artisan key:generate`

- Configure your DB and mail server

- Run Migration
    > `php artisan migrate`

- Run DB Seeder
    > `php artisan db:seed`

- Run app
    > `php artisan serve`

- Login with demo User
    > `email: jdoe@fileshareapp.com`

    > `password: jdoe1234`

- Add a cron job for mail notification as the app uses as job queue
    > `* * * * * /usr/local/bin/php /home/user/public_html/project/artisan schedule:run >> /home/user/public_html/project/storage/logs/jobs.log 2>&1`
