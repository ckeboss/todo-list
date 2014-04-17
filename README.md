Todo List
=========

Setup
-------

- Download/checkout and get run composer update
- Create .env.local.php with DB config, see example below
- php artisan migrate
- profit???


```php
<?php

return array(

    'mysql_host' => 'localhost',
    'mysql_database' => 'todo-list',
    'mysql_username' => 'user',
    'mysql_password' => 'password',

);
```