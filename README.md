# Laravel contacts API

Simple API import CSV data and list contacts endpoint
### Installation

Download project, install dependencies with composer and db migrations .
```sh
$ git clone git@github.com:ecuation/contacts-api.git
$ cd contacts-api.development
$ composer install
```

Copy .env file into the project and setup the necessary project and DB connection environment variables
```sh
$ cp .env.example ./.env
```

Run Laravel migrations with artisan command
```sh
$ php artisan migrate
```

Run phpunit test
```sh
$ php artisan test
```
