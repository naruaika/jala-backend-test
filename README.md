# Technical Test - Backed Engineer

???

## Requirements

- GNU/Linux: [Docker Compose](https://docs.docker.com/compose/install/)
- macOS: [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- Windows: [WSL2](https://docs.docker.com/desktop/windows/wsl/) and [Docker Desktop](https://www.docker.com/products/docker-desktop/)

## Setup

To run the application, execute below commands in terminal:

```sh
# Clone this repository
git clone git@github.com:naruaika/jala-backend-test.git

cd jala-backend-test

# Setup the project
cp .env.example .env
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer install

# Run the project
vendor/bin/sail up
```

Open the terminal of the Laravel application container and execute above commands to setup the application:

```sh
php artisan key:generate
php artisan migrate
```

To run the application, you can execute below command in terminal:

```sh
# Run in development mode
npm run dev

# Or build to run in production
npm run build
```

In case you want to seed the database with dummy data, you can execute below command in terminal:

```sh
php artisan migrate:fresh --seed
```

To run the application tests, you can execute below command in terminal:

```sh
php artisan test
```
