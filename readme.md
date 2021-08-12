## About
Php framewok. On development. 

## Install
```composer require also/route```
```php vendor\also\route\build.php```


## Requiments
php 7.4+ for using arrow functions

## Basics
After installing also/route, you need to build it with ```php vendor\also\route\build.php```. 
This operation will copy the content of build folder. Now you have structure of files and folder. 

**env.php** - has the environment variables and settings. 
**public/index.php** - has routes for your application
**controllers** - Here are controllers stored by default
**db** - folder with sqlite database and tables for migration
**mw** - before.php - runs before routes, after.php runs before gowing to controller
**public** - folder for public files
**views** - folder for views and mount files




## Routes

Public routes, has to be without ``.``. Route with dot, will look for file inside public folder. 
```php
// file available on /p/js
Route::public('/p/js','node_modules/als-jpretty/jpretty.js'); 
Route::public('(/p/js)','node_modules/als-jpretty/jpretty.js'); 
// the js file will output inside(function () {...the file})();
```

Route with variables:
```php
Route::get('/users/{user}',fn($r) => "Hello ".$r['data']['user']);
// request: http://localhost:8000//users/Alex
// response:hello Alex

Route::get('!/get','controller.checkGet'); // Without checking csrf (starts with !)
```



## Ajax requests

You can do ajax request, but on every request you need to renew csrf token. 

To do thata, you need to send request with `ajax:ajax` header. 
The response header will include ``Etag:newToken``. Now you need to update all input fields with ``[name=token]``.

You can use als-ajax which allready include all necessary. 


## Controllers

Controller is a php file with functions in namespace Also. 

Adding ``$ifAuth = true;`` on controller checks if user loged in. If not, page redirect to /login.

```php
<?php
namespace Also;
$ifAuth = true;
```

redirect('route') - without first /

## ClI

php env.php server portNumber

