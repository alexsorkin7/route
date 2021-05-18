<?php
namespace Also;
include_once '../env.php';

Route::$p404 = include_once ROOT.'public/404.php';
Route::get('/',fn() => mount('welcome'));


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