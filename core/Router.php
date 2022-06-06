<?php

namespace Core;


class Router
{
    public $routes=[    //array of request methods arrays which contains url path

        'GET'=>[],//urls in Get array

        'PUT'=>[],//urls in put array

        'DELETE'=>[],//urls in delete array

        'POST'=>[]//urls in post array

    ];

    /** @noinspection PhpIncludeInspection */


    public static function load($file) //load file

    {

        $router = new self; //assign class inside variable $router = new Router();

        require $file;

        return $router; //return class object inside loaded file

    }

    public function define($routes)

    {
       return $this->routes = $routes;//assign url path or variable
    }

    public function get($url,$controller) // put url inside GET method array

    {
        $this->routes['GET'][$url] = $controller; //assign controller page to get url method
    }

    public function post($url,$controller) // put url inside POST method array

    {
        $this->routes['POST'][$url] = $controller; //assign controller page to post url method
    }

    public function put($url,$controller) // put url inside POST method array

    {
        $this->routes['PUT'][$url] = $controller; //assign controller page to post url method
    }

    public function delete($url,$controller) // put url inside POST method array

    {
        $this->routes['DELETE'][$url] = $controller; //assign controller page to post url method
    }


    public function direct($url, $requestType) //require controller file

    {//it need url and url request method


            if (array_key_exists($url, $this->routes[$requestType])) {//check if url exists in request method array
                    //request method known from given link path which has a place inside routes file
//                return $this->routes[$requestType][$url];

                return $this->callAction(//open controller file and call function

                    ...explode('@',$this->routes[$requestType][$url])//get controller file & function

                );//... -->return array into seperated elements

            }

            throw new \Exception('No route defined in this url '); //if not make exception

    }

    protected function callAction($controller,$action) //call function inside controller inside routes file

    {

        $controller =  "\\App\\Controllers\\$controller";
        $controller = new $controller; //prepare controller class object
        //method_exists Checks if the method exists inside class
        if(! method_exists($controller,$action)){

            throw new \Exception("{$controller} does not respond to the action {$action}");//if not make exception

        }

        return $controller->$action();//if exists call function

    }


}