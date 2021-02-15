# Transaction Backend

## Run:
```
$ docker-compose up --build
```
- Dev mode with mounted directories (Slow on WSL2):
```
$ docker-compose -f docker-compose.dev.yml up
```

## Testing
```
$ php artisan test
```