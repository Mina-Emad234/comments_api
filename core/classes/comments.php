<?php

namespace Core\Classes;


use Core\App;
use http\Env\Response;

class comments
{
    public $limit = 10;
    public function count_rows(){
        $get_total = App::get('database')->selectItems('COUNT(*) AS total',"comments");
        return $total_page = ceil($get_total['total'] / $this->limit); // Count the number of pages
    }

    public function make_pagination(){

        if($_GET['page'] < $this->count_rows()){
            $limit_start = limit_start();
            return $data = App::get('database')->selectItems("*","comments","LIMIT $limit_start,$this->limit",1);
        }

    }

    public function make_api_pagination(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $token_errors=checkToken(json_decode(file_get_contents("php://input"))->jwt,1);
        if(!empty($token_errors)){
            echo json_encode($token_errors);
        }else{
            $limit_start = limitPageStart();
            $data = App::get('database')->selectItems("*","comments","LIMIT $limit_start,$this->limit",3);
            if($this->count_rows() < commentsPage()):
                // set response code - 404 Not found
                http_response_code(410);
                // tell the user no products found
                echo json_encode(
                    array("message" => "No Comments found.")
                );
            else:
                // set response code - 200 OK
                http_response_code(210);
                // show products data in json format
                echo json_encode($data);
            endif;
        }
    }
}