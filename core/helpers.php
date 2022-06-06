<?php

use Core\App;
use Firebase\JWT\JWT;

function view($name, $data=[]) //return view('viewPageName')

{

    extract($data);//extract make key of array as a variables - pass it to the view file

    return require "views/{$name}.view.php";

}

function redirect($path)//return Redirect url

{

    header("location:{$path}");

}

function get_id()
{
    if (isset($_GET['id']) && !empty($_GET['id'])){
        return $_GET['id'];
    }
}

function headersAndPostedData($method){
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: {$method}");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // get posted data --->body-->raw
    return json_decode(file_get_contents("php://input"));
}

function page(){
    if(isset($_GET['page'])):
        return ((int)$_GET['page']);
    endif;
}

function commentsPage(){
    if(isset($_GET['comments_page'])):
        return ((int)$_GET['comments_page']);
    endif;
}

function limit_start(){
    $limit = 10;
    return (page() - 1) * $limit;//0 5 10
}

function limitPageStart(){
    $limit = 10;
    return (commentsPage() - 1) * $limit;//0 5 10
}

function makeSession($total_page){
    if (page() > $total_page){
        return $_SESSION['not_found'] = "Page Not found";
    }
}

function validation($data,$id_exist=null):array{
    $errors=[];
    if ($id_exist == 1){
        if(empty($data->id)){
            // tell the user data is incomplete
            // set response code - 400 bad request
            http_response_code(415);
            // tell the user
            $errors['id_error']= "ID is required.";
        }
    }
    if(empty($data->name)){
        // tell the user data is incomplete
        // set response code - 400 bad request
        http_response_code(415);
        // tell the user
        $errors['name_error']= "Name is required.";
    }
    if(empty($data->email)){
        // tell the user data is incomplete
        // set response code - 400 bad request
        http_response_code(415);
        // tell the user
        $errors['email_error']= "Email is required.";

    }
    if(empty($data->body)) {
        // tell the user data is incomplete
        // set response code - 400 bad request
        http_response_code(415);
        // tell the user
        $errors['body_error'] = "Body is required.";
    }
    return $errors;
}

function userValidation($user,$id_exists=null,$name=null):array{
    $errors=[];
    if($id_exists == 1) {
        if (empty($user->id)) {
            // tell the user data is incomplete
            // set response code - 400 bad request
            http_response_code(416);
            // tell the user
            $errors['id_error'] = "ID is required.";
        }
    }

    if($id_exists == 1) {
        if (empty($user->name)) {
            // tell the user data is incomplete
            // set response code - 400 bad request
            http_response_code(416);
            // tell the user
            $errors['name_error'] = "Name of user is required.";
        }
    }



    if(empty($user->email) || !filter_var($user->email,FILTER_VALIDATE_EMAIL)){
        // tell the user data is incomplete
        // set response code - 400 bad request
        http_response_code(416);
        // tell the user
        $errors['email_error']= "Email user is required or Invalid Email.";

    }
    if(empty($user->password) || strlen($user->password) < 8) {
        // tell the user data is incomplete
        // set response code - 400 bad request
        http_response_code(416);
        // tell the user
        $errors['password_error'] = "Password is required or must be more than 8 characters.";
    }
    return $errors;
}

function checkToken($data,$get=null){
    if(isset($_SESSION['email']) && isset($_SESSION['id'])){
        global $key;
        $token_errors=[];
        // get jwt

        if ($get == 1){
            if(isset($data)){
                $jwt = $data;
            }else{
                $jwt='';
            }
        }else{
            if(isset($data->jwt)){
                $jwt = $data->jwt;
            }else{
                $jwt='';
            }
        }

        // decode jwt here
        // if jwt is not empty
        if($jwt){

            // if decode succeed, show user details
            try {
                // decode jwt
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                // set user property values here
            }  catch (Exception $e){
                // error message if jwt is empty will be here
                // if decode fails, it means jwt is invalid
                // set response code
                http_response_code(401);

                $token_errors['message']="Access denied.";
                $token_errors['error']=$e->getMessage();
            }

        // catch failed decoding will be here
        }// show error message if jwt is empty
        else{

            // set response code
            http_response_code(401);

            // tell the user access denied
            $token_errors['message']="Access denied.";
        }
    }else{
        $token_errors['login_error']="login required.";
    }
    return $token_errors;
}

function decode($data){
    global $key;
    if(isset($data->jwt)){
        return JWT::decode($data->jwt, $key, array('HS256'));
    }
}

