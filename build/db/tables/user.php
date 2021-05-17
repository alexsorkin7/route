<?php
$table = [
    "id"=> $types->id,
    "username" => $types->text(50).$types->def(),
    "password" => $types->text("1b").$types->def(),
    "email" => $types->text("1b").$types->def(),
    "name" => $types->text(50),
    "last_name" => $types->text(50),
    "middle_name" => $types->text(50),
    "status" => $types-> num(1).$types->def("0"), // 1-active, 0-not active
    "is_admin" => $types->num(1).$types->def("0"), // 1-admin, 0-not not admin
    "timestamp" => $types->timestamp
];

function fake() {
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