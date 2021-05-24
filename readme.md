## About
Php framewok. On development. 


## Php version:
php 7.4+ for using arrow functions
php 7.0+ for generating csrf token


redirect('route') - without first /

## Routes

get
post
public
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
Adding ``$ifAuth = true;`` on controller checks if user loged in. If not, page redirect to /login.

```php
<?php
namespace Also;
$ifAuth = true;
```
