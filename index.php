<?php

use Core\Request;
use Core\Router;

session_start();


require 'vendor/autoload.php';

require_once 'core/bootstrap.php';



Router::load('app/routes.php')//require file & put class object variable inside
->direct(Request::url(),Request::method());












