<?php
namespace Also;
include_once 'vendor/autoload.php';
define('ROOT',__DIR__.'/');


$env = (object) [
    'publicFolder'=>'public',
    'appName' => 'Simple',
    'username' => 'admin', // admin username
    'email' => 'admin@mail.com', // admin email
    'password' => 'qwerty', // admin password
    'secret' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9', // for generating csrf token
    'mountPath' => ROOT.'views', // path for mount(view) files
    'showErrors' => true, // false - for disable all errors
    'con' => ROOT.'db/data.db', // DataBase for sqlite
    // 'con' => array("localhost","root","", "simple"), // DataBase for mySql
    'tablePath' => ROOT.'db/tables', // Place for tables for migration
    'sessionPath' => ROOT.'/session', // folder for session files
    'sessionLifeTime' => 3600*24*7, // session life time in secconds
    'passwordHash' => PASSWORD_DEFAULT, // or PASSWORD_BCRYPT 
    // mail options
];


// Set model
$model = new Model($env->con);
$model->hash = $env->passwordHash;
$migrate = new Migration($env->tablePath,$model);
$migrate->cli();

function model($tableName) {
    global $env;
    $model = new Model($env->con);
    return $model->table($tableName);
}

// "gmail" for gmail + set "Allow less secure apps: ON" - https://myaccount.google.com/lesssecureapps

// php env.php serve port
    if(isset($argv[1]) && $argv[1] == 'serve') {
        $port = 8000;
        if(isset($argv[2]) && is_numeric($argv[2])) $port = $argv[2];
        shell_exec("cd $env->publicFolder && php -S localhost:$port");
    }

// Set session path and lifetime
    if(isset($env->sessionPath)) {
        $path = $env->sessionPath;
        if (!file_exists($path)) mkdir($path, 0777, true);
        ini_set('session.save_path',$path);
    }
    ini_set('session.cookie_lifetime', $env->sessionLifeTime);

// Enable or disable errors by env->showErrors
    if($env->showErrors) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else if($env->showErrors == false) {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }

include_once 'mw/before.php';
