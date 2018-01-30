<p align="center"><img src="https://api-platform.com/logo-250x250.png" alt="API Platform"></p>

This repository is a RESTful api for a local electronic store!

Installation
------------

Follow these steps to start the application:
* Clone/copy the repository on your local machine.
* Build/run containers with (with and without detached mode)
    ```bash
    $ docker-compose build
    $ docker-compose up -d
    ```
* Create JWT keys, use **store** if you want to use the default pass phrase
    ```bash
    $ docker-compose exec app mkdir -p var/jwt
    $ docker-compose exec app openssl genrsa -out var/jwt/private.pem -aes256 4096
    $ docker-compose exec app openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
    ```
* Update the database schema
    ```bash
    $ docker-compose exec app php bin/console doctrine:schema:update --force
    ```
* Import the fake data
    ```bash
    $ docker-compose exec app php bin/console app:import-seeds
    ```
* Run behat tests
    ```bash
    $ docker-compose exec app php bin/console cache:clear --env=test
    $ docker-compose exec app php vendor/bin/behat
    ```
