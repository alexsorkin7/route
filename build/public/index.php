<?php
namespace Also;
include_once '../env.php';

Route::$p404 = include_once ROOT.'public/404.php';
Route::get('/',fn() => mount('welcome'));

Route::get('/test',fn($req) => mount('test',['req' => $req,'hello' => 'super']));
Route::get('/output',fn() => mount('output'));
Route::get('/test1','controller.method');
Route::get('/test2','controller.method1');
Route::get(
    '/users/{user}/posts/{post}',
    fn($req) => "hello ".$req['data']['user'].
    '. Your post is:'.$req['data']['post']
);
Route::get('/get','controller.checkGet');


// public routes
    Route::public('/bind.js','node_modules/als-bind/bind.js');
    Route::public('/simple.js','node_modules/als-simple-css/simple.js');
    Route::public('/sketch.js','node_modules/als-simple-css/sketch.js');

// auth routes
    Route::get('/login','auth.loginGet');
    Route::get('/register','auth.registerGet');
    Route::post('/login','auth.login');
    Route::post('/register','auth.register');
    Route::get('/logout','auth.logout');

// home routes
    Route::get('/home','auth.home');
    Route::get('/settings','auth.settingsGet');
    Route::post('/settings','auth.settings');

    
$app = new Route();
