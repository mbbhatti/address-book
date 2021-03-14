# Address-Book
Build an address book to perform for CRUD operations with symfony/website-skeleton.

## Requirements
- PHP >= 7.4.X
- symfony >= 3.4.x
- Doctrine with SQLite
- Twig

## Installation 
Symfony utilizes composer to manage its dependencies. So, before using symfony, make sure you have composer installed on your machine. To download all required packages run a following commands or you can download [Composer](https://getcomposer.org/doc/00-intro.md).
- composer install `OR` COMPOSER_MEMORY_LIMIT=-1 composer install

## Database
Run these commands to build SQLite scheme object in var to use for data.
```
- php bin/console make:migration
- php bin/console doctrine:migrations:migrate
```

## Run
Use below command to start the project.
```
symfony server:start 
OR 
php bin/console server:run
OR 
php -S 127.0.0.1:8000 -t public public/index.php
```

## Environment Variable

Application setting
```
- APP_ENV = Used to setup application environment like dev or test
- APP_SECRET = An application key based on 32 character alpha numeric string to secure the app
```

Database setting
```
- DATABASE_URL = A database connection URL for SQLite.
```

Miscellaneous
```
- UPLOAD_PATH = Use to upload user images directory path.
- LIMIT = Use for address book record limit as pagination. 
 ```

## API ENDPOINTS

- / => To show address book list.
- /create => To add address
- /update/{id} => To update address
- /delete/{id} => To delete address 
