<?php


namespace Core\Classes;

use Exception;
use \Firebase\JWT\JWT;

class users
{
    protected $users;
    const EXAMPLE_JWT_SECRET_KEY = '********';
    const EXAMPLE_JWT_ENCODE_ALG = 'HS256';
    public function __construct($users)
    {
        $this->users=$users;
    }
    public function createUser(){
        $user = headersAndPostedData('post');
        $errors = userValidation($user,null,1);
        if(!empty($errors)){
            echo json_encode($errors);
        }
        if (!empty($user->name) && !empty($user->email) && !empty($user->password)) {
            $this->users->name=htmlspecialchars(strip_tags($user->name));
            $this->users->email=htmlspecialchars(strip_tags($user->email));
            $this->users->password=htmlspecialchars(strip_tags($user->password));
                $rowCount = $this->users->addUser();
                if ($rowCount > 0) {
                    // set response code - 201 created
                    http_response_code(201);
                    // tell the user
                    $messages['success_msg'] = "user was created.";
                } else {// if unable to create the product, tell the user
                    // set response code - 503 service unavailable
                    http_response_code(503);
                    // tell the user
                    $messages['error_msg'] = "Unable to create user.";
                }
                echo json_encode($messages);
            }
        }


    public function login(){
        global $issued_at;
        global $expiration_time;
        global $issuer;
        global $key;
        $user = headersAndPostedData('post');
        $errors = userValidation($user);
        if(!empty($errors)){
            echo json_encode($errors);
        }
        if (!empty($user->email) && !empty($user->password)) {
                $this->users->email=htmlspecialchars(strip_tags($user->email));
                $this->users->password=htmlspecialchars(strip_tags($user->password));
                    $login = $this->users->login();
                    if ($login) {
                        $_SESSION['email'] = $this->users->email;
                        $_SESSION['id']  = $this->users->id;
                        $token = array(
                            "iat" => $issued_at,
                            "exp" => $expiration_time,
                            "iss" => $issuer,
                            "data" => array(
                                "id" => $this->users->id,
                                "name" => $this->users->name,
                                "email" => $this->users->email
                            )
                        );
                        http_response_code(250);
                        $jwt = JWT::encode($token,$key);
                        $messages['success_msg'] = "success login.";
                        $messages['jwt'] = $jwt;
                    } else {// if unable to create the product, tell the user
//                         set response code - 503 service unavailable
                        http_response_code(550);
                        $messages['error_msg'] = "Unable to login.";
                    }
                    echo json_encode($messages);


        }

        }

    public function updateUser(){
        global $issued_at;
        global $expiration_time;
        global $issuer;
        global $key;
        $user = headersAndPostedData('post');
        $errors = userValidation($user);
        $token_errors=checkToken($user);
        $decoded = decode($user);//to get id
        if(!empty($token_errors)){
            echo json_encode($token_errors);
        }else if(!empty($errors)){
            echo json_encode($errors);
        }elseif(!empty($user->name) && !empty($user->email) && !empty($user->password)) {
            $this->users->id=htmlspecialchars(strip_tags($decoded->data->id));
            $this->users->name=htmlspecialchars(strip_tags($user->name));
            $this->users->email=htmlspecialchars(strip_tags($user->email));
            $this->users->password=htmlspecialchars(strip_tags($user->password));
            $updated = $this->users->updateUser();
            if ($updated) {
                $token = array(
                    "iat" => $issued_at,
                    "exp" => $expiration_time,
                    "iss" => $issuer,
                    "data" => array(
                        "id" => $this->users->id,
                        "name" => $this->users->name,
                        "email" => $this->users->email
                    )
                );
                http_response_code(250);
                $jwt = JWT::encode($token,$key);
                $messages['success_msg'] = "User updated successfully.";
                $messages['jwt'] = $jwt;
            } else {// if unable to create the product, tell the user
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                $messages['error_msg'] = "Unable to update user.";
            }
            echo json_encode($messages);
        }
    }

    public function logout(){
        $user = headersAndPostedData('post');
        $token_errors=checkToken($user);
        if(!empty($token_errors)){
            echo json_encode($token_errors);;
        }else {
            $_SESSION = array(); // reset session array
            session_destroy();   // destroy session.
            $messages['success_msg'] = "User logout successfully.";
            echo json_encode($messages);
        }


    }


}