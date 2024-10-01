# <center> HighBid </center>
## About
## Dependencies
* Docker

## Installation

Clone and open the project
```bash
git clone git@github.com/yelloweq/HighBid && cd HighBid
```

Install composer dependencies using docker
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
Run laravel sail (docker) <br> 
Generate app key <br>
Compile Assets 
```bash
./vendor/bin/sail up -d && \
./vendor/bin/sail php artisan key:generate && \
./vendor/bin/sail npm install && \
./vendor/bin/sail npm run dev
```


### Database Setup
Seeded database setup
```bash
./vendor/bin/sail php artisan migrate:fresh --seed
```

<small>Alternatively migrate without seeding</small>
```bash
./vendor/bin/sail php artisan migrate
```

### Jobs & Queues
Run these commands to ensure that listings expire and emails are sent to users
```bash
./vendor/bin/sail php artisan queue:work 
```
```bash
./vendor/bin/sail php artisan schedule:work 
```

### Tests
Unit testing
```bash
./vendor/bin/sail phpunit
```
<style>
    h2 {
        margin:0;;
        width:100%;
    };
    
</style>
