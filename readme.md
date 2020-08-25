# cross-origin-helpers
Enables some methods like the dd method from Laravel to work cross origin. just add a c before the function call.
Compatible with laravel 7.24 and up.

## Installation

### With Composer

```
$ composer require julesgraus/cross-origin-helpers
```

```json
{
    "require": {
        "julesgraus/cross-origin-helpers": "^0.1"
    }
}
```

```php
<?php
require 'vendor/autoload.php';

cdd('Cross origin die and dump test');
cdie('Cross origin die test');
cdump('Cross origin dump test');
```

## Configuration
The helpers provided by this package use the same cors config file as Laravel 7 provides by default.
Use Laravels cors config file ```config/cors``` to setup cors and the helpers should work too.
Also see [The laravel manual](https://laravel.com/docs/7.x/routing#cors) for more info.
