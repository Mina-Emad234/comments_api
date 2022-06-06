<?php

namespace Core;


use Exception;

class App
{

    protected static $registry = [];

    public static function bind($key,$value)//assign value to key

    {

        static::$registry[$key] = $value;//assign value to array

    }

    public static function get($key)//get key

    {
        if (! array_key_exists($key,static::$registry)){

            throw new Exception("No {$key} is bound in this container");

        }

       return static::$registry[$key];

    }

}