Installation
* install & run docker

```
git clone .....
```

```
cd cs1ip
```

Run sail
```
./vendor/bin/sail up -d
```

Setup database table
```
./vendor/bin/sail php artisan migrate
```

Test data seeding
```
./vendor/bin/sail php artisan migrate:fresh --seed
```

Running tests
```
./vendor/bin/sail phpunit
```

Running jobs and scheduler
for jobs, emails and periodic tasks
```
./vendor/bin/sail php artisan queue:work 
```
```
./vendor/bin/sail php artisan schedule:work 
```

