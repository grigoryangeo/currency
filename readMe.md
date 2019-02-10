REST service allows you to convert currencies

### requirements

* php7.1||php7.2
* postgreSQL|MySql
* apache|nginx

### install

>cp .env .env.local

>edit db parametrs .env file

>cp .env.test .env.test.local

>edit db parametrs .env file

>cp phpunit.xml.dist phpunit.xml

>composer install

>php ./bin/console d:s:u --force --env=prod

### console commands

Import data
>./bin/console app:currency:import

### use rest api

http://localhost/api/v1/currency/convert?from=usd&to=rub&value=100
http://localhost/api/v1/currency/convert.xml?from=usd&to=rub&value=100
http://localhost/api/v1/currency/convert.json?from=usd&to=rub&value=100

### run tests

>php ./bin/console d:s:u --force --env=test

>php ./bin/console d:f:l --env=test

>php ./bin/phpunit