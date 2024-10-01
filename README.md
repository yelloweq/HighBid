Installation
* install & run docker

git clone ...
```bash
```

```bash
cd HighBid
```

Install dependencies
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
Generate app key
```bash
./vendor/bin sail php artisan key:generate 
```

Run sail
```bash
./vendor/bin/sail up -d
```

Setup database table
```bash
./vendor/bin/sail php artisan migrate
```

Test data seeding
```bash
./vendor/bin/sail php artisan migrate:fresh --seed
```

Running tests
```bash
./vendor/bin/sail phpunit
```

Running jobs and scheduler
for jobs, emails and periodic tasks
```bash
./vendor/bin/sail php artisan queue:work 
```
```bash
./vendor/bin/sail php artisan schedule:work 
```

