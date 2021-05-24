<?php
namespace Also;
$types = new Types();

$table = [
    "id"=> $types->id,
    "username" => $types->char(50),
    "password" => $types->varchar(),
    "email" => $types->char(),
    "name" => $types->null()->char(50),
    "last_name" => $types->null()->char(50),
    "middle_name" => $types->null()->char(50),
    "status" => $types->def(0)->int(1), // 1-active, 0-not active
    "is_admin" => $types->def(0)->int(1), // 1-admin, 0-not not admin
    "timestamp" => $types->timestamp
];
    
function fake_user() {
    $faker = \Faker\Factory::create();
    $fake = [
        "username" => $faker->userName(),
        "password" => $faker->password(),
        "email" => $faker->email(),
        "name" => $faker->name(),
        "last_name" => $faker->lastName(),
        "status" => 0,
        "is_admin" => 0
    ];
    return $fake;
}

global $env;
$data = [
    'username' => $env->username,
    'password' => $env->password,
    'email' => $env->email,
    'status' => 1,
    'is_admin' => 1
];
