# Media-tech

The purpose of this project is to provide a management tool for a library. This project uses Apache, MySQL, and PHP.

## Requirements

- [Docker](https://www.docker.com/)
- [Make](https://www.gnu.org/software/make/manual/make.html) (optional — [install `make` for Windows](https://stackoverflow.com/questions/2532234/how-to-run-a-makefile-in-windows))

## Usage

You can run the following commands if you have `make` installed.

### Build the Containers

First, build the containers:

```bash
make build # or `docker-compose build` if you don't have `make`
```

An image with [PHP](https://www.php.net), [Apache](https://httpd.apache.org), [Composer](https://getcomposer.org), and [Symfony CLI](https://symfony.com/download) ready to use will be built.

### Start the Containers

Then, start the containers:

```bash
make up # or `docker-compose up -d` if you don't have `make`
```

Apache should now be ready to serve the application.

### Access the Container

To access the container and run Symfony or Composer commands, use:

```bash
make exec # or `docker-compose exec apache bash` if you don't have `make`
# This will open a bash session inside the container
```

Remember: every time you want to run a Symfony or Composer command, you should run it inside the container using `make exec`.

### Install Dependencies

```bash
composer install 
# This will install all the dependencies required for this project
```

```bash
symfony console importmap:install
```

### Create and Populate the Database

Create your database:

```bash
symfony console d:m:m
# This will create all the tables
```

Load the fixtures:

```bash
symfony console doctrine:fixtures:load -n
# This will populate the tables with some data
```

## Access the Application

You can now access the project at [http://localhost:8000](http://localhost:8000).

An admin account is ready to use. You can log in with the following credentials:

- Email: `admin@example.com`
- Password: `admin`

### Troubleshooting

If you encounter a `CSRF` error when logging in, try the following steps:

1. Restart Docker.
2. Run `symfony console cache:clear`.
3. Reopen your browser.

This should fix the issue.

If you encounter other bugs, check if the problematic file is the same as the one on GitHub.

## Tests


Run all the tests :

```bash
php bin/phpunit
```

Run one test : 

```bash
php bin/phpunit tests/filePath.php
```

## Notes

- Ensure that your environment meets the requirements listed above before proceeding.
- Use the provided `make` commands for convenience, or the equivalent `docker-compose` commands if not using `make`.

