## FILESHARE

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

- Create DB

- Copy .env.example to .env

- Configure your DB and mail server

- Run Migration
    > `php artisan run migration`

- Run DB Seeder
    > `php artisan db:seed`

- Demo User
    > `email: jdoe@fileshare.com`
    > `password: jdoe1234`

- Run the queue cmd for mail notification 
    > `php artisan queue:work`

    For more details on queues visit [Laravel Queues](https://laravel.com/docs/8.x/queues).