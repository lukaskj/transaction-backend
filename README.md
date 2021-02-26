# Transaction Backend
## Dependencies
- Docker for complete environment\
`or`
- PHP 7.3+
- Composer
- [Any Laravel supported databases](https://laravel.com/docs/8.x/database#introduction)
## Run:
### - With Docker:
```
$ docker-compose up --build
```
- Dev mode with mounted directories ([Slow on WSL2](https://github.com/microsoft/WSL/issues/4197#issuecomment-604592340)):
```
$ docker-compose -f docker-compose.dev.yml up
```

### - Local (without Docker):
First rename .env.example to .env and then run the following commands:
```bash
$ composer install

$ php artisan migrate --seed

# first process
$ php artisan serve

# Second process (jobs)
$ php artisan queue:work
```

### **The API will be up on address `http://localhost:8000`**
<br>

## Testing
```
$ php artisan test
```

## PHP cs fixer
```
$ composer fix
```

## Documentation
* [PT-BR](./docs/Documentation-pt_BR.pdf)
* EN (TODO)
* [Flowchart (PT-BR)](./docs/fluxograma.png)