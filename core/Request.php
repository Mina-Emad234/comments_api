<?php

namespace Core;


use Core\Classes\comments;

class Request
{
    /**
     * @return mixed
     */
    public static function url()//get path from a given url to be compared with path inside routes file
    {
        $comments = new comments();
        if (isset($_GET['action']) && !empty($_GET['action'])) {
            return $_GET['action'];
        } elseif(isset($_GET['id']) && !empty($_GET['id'])){
            return $_GET['id'];
        }elseif (isset($_GET['page']) && $_GET['page'] >= 1) {
            return $_GET['page'];
        }elseif (isset($_GET['comments_page']) && $_GET['comments_page'] >= 1){
            return $_GET['comments_page'];
        }elseif(isset($_GET['page']) && $_GET['page'] == ''){
            return $_GET['page'] = 1;
        }elseif(isset($_GET['comments_page']) && $_GET['comments_page'] == ''){
            return $_GET['comments_page'] = 1;
        }
    }

    public static function method()//get request method from a given url

    {
        return $_SERVER["REQUEST_METHOD"];
    }
}