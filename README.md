# Laravel

### Requirements

- [GIT](https://git-scm.com/)
- [Docker](https://docs.docker.com/engine/install/ubuntu/) or [Docker Desktop](https://www.docker.com/products/docker-desktop/)

### Running the app

```
git clone git@github.com:BrunoEduardo1/takehome-challenge-obj.git

cd takehome-challenge-obj

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

## Postman Collection

[API Testing Collection](https://www.postman.com/bruno-dev1/workspace/public-projects-and-challenges/collection/28578263-10ab5a0a-bebb-4db7-8682-8bb8214ad7dc?action=share&creator=28578263)

## Tests

```
sail artisan migrate --env=testing

sail artisan test
```

## Directory Structure

- `app/Contracts/Repositories`: Contains interfaces for repositories.
- `app/Models`: Houses the project's data models.
- `app/Repositories`: Contains implementations of data repositories.
- `app/Http/Requests`: Includes validation logic for incoming requests.
- `app/Http/Resources`: Handles the formatting of output data.
- `app/Http/Controllers`: Manages request handling and validations.
- `app/Services`: Houses the project's core business logic.
- `tests`: Contains feature and unit tests.
- `.github`: GitHub Actions workflows for automating tests.

## About laravel
[Laravel](https://laravel.com) is a web application framework with expressive, elegant syntax. A web framework provides a structure and starting point for creating your application, allowing you to focus on creating something amazing while we sweat the details.

## License

[MIT license](https://opensource.org/licenses/MIT).
