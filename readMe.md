### requirements

* >php7.1
* postgreSQL|MySql
* apache|nginx

### install

>cp .env .env.local
> edit db parametrs .env file

>composer install
>php ./bin/console d:s:u --force --env=prod

### console commands

Import data
>./bin/console app:currency:import

### rest api

### run tests

>php ./bin/console d:s:u --force --env=test
>php ./bin/console d:f:l --env=test
>php ./bin/console unit