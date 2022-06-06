<?php


use Core\App;

require "helpers.php";

// generate json web token

include_once 'config/core.php';
include_once 'vendor/firebase/php-jwt/src/BeforeValidException.php';
include_once 'vendor/firebase/php-jwt/src/ExpiredException.php';
include_once 'vendor/firebase/php-jwt/src/SignatureInvalidException.php';
include_once 'vendor/firebase/php-jwt/src/JWT.php';
App::bind('config', require 'database.php');

App::bind('database', new QueryBuilder(//assign pdo connection to key

    connection::make(App::get('config')['database'])//get value from array inside database.php

));

App::bind('user_database', new UserDatabase(//assign pdo connection to key

    connection::make(App::get('config')['database'])//get value from array inside database.php

));

const COOKIE_TIME_OUT = 1; //specify cookie timeout in days

