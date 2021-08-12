<?php
namespace Also;
include_once '../env.php';

Route::get('/',fn() => mount('welcome'));

Route::$p404 = include_once ROOT.$env->publicFolder.'/404.php';
// public routes
    Route::public('/ajax/js','node_modules/als-ajax/ajax.js');
    Route::public('/bind/js','node_modules/als-bind/bind.js');
    Route::public('/simple/js','node_modules/als-simple-css/simple.js');
    Route::public('/sketch/js','node_modules/als-simple-css/sketch.js');
    Route::public('/validate/js','node_modules/als-validator/validator.js');
// auth routes
    Route::get('/login','auth.loginGet');
    Route::get('/register','auth.registerGet');
    Route::post('/login','auth.login');
    Route::post('/register','auth.register');
    Route::get('/logout','auth.logout');
// home routes
    Route::get('/home','home.home');
    Route::get('/settings','home.settingsGet');
    Route::post('/settings','home.settings');

$app = new Route();