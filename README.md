# Transaction Backend
## Dependencies
- Docker
- `or`
- PHP 7.3+
- Composer
- [Any Laravel supported databases](https://laravel.com/docs/8.x/database#introduction)
## Run:
### - With Docker:
```
$ docker-compose up --build
```
- Dev mode with mounted directories (Slow on WSL2):
```
$ docker-compose -f docker-compose.dev.yml up
```

### - Local (without Docker):
First rename .env.example to .env and then run the following commands:
```bash
$ php artisan migrate --seed

# first process
$ php artisan serve

# Second process (jobs)
$ php artisan queue:work
```

### **The API will be up on address `http://localhost:3000`**
<br>

## Testing
```
$ php artisan test
```

## Documentation
* [PT-BR](https://docs.google.com/document/d/1daxXkKQ8koJ4_T-Km-NEQJxvaPK9q5x-P2VyT0tQmvk/edit)
* EN (TODO)
* [Flowchart (PT-BR)](./docs/fluxograma.png)