# thinkIT

Task 

## Requirements

php: ^8.1


## Installation
Backend setup

Create a new 'thinkit' database and fill in the credentials in the .env file

```bash
cd api
cp .env.example .env
composer install
php artisan migrate
php artisan db:seed
php artisan serve --port 8000
```
[Postman APIs](https://www.postman.com/planetary-star-261084/workspace/thinkit/overview)

