# Multi-Currency Ledger Service
### Prerequisites
Ensure you have the following installed:
- PHP 8.3
- Laravel 10
- MySQL
- Docker

### Installation Steps
```sh
git clone https://github.com/akromjon/ledger-service.git
cd ledger-service
composer install
cp .env.example .env
php artisan sail:install
./vendor/bin/sail up -q
./vendor/bin/sail php artisan test
```

