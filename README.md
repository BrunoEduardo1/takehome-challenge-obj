# Laravel

### Requirements

- [GIT](https://git-scm.com/)
- [Docker](https://docs.docker.com/engine/install/ubuntu/) or [Docker Desktop](https://www.docker.com/products/docker-desktop/)

### Running the app

```
git clone git@github.com:BrunoEduardo1/objective-laravel.git

cd objective-laravel

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

cp .env.example .env

alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'

sail up -d

sail artisan key:generate

sail artisan migrate

```

### Configuring A Shell Alias for sail (optional)
Instead of repeatedly typing `./vendor/bin/sail` to execute Sail commands, add the line bellow to your shell configuration file in your home directory, such as `~/.zshrc` or `~/.bashrc`, and then restart your shell.
```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

## Routes

[Postman Collection](https://www.postman.com/bruno-dev1/workspace/objective/collection/28578263-21dcaddf-e4b1-43bb-b688-3dc3f53fa372?action=share&creator=28578263)

## Tests

```
sail artisan test
```

## About laravel
[Laravel](https://laravel.com) is a web application framework with expressive, elegant syntax. A web framework provides a structure and starting point for creating your application, allowing you to focus on creating something amazing while we sweat the details.

## License

[MIT license](https://opensource.org/licenses/MIT).
