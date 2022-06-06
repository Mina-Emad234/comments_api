<?php

namespace Core\Classes;

class api_comments
{
    protected $comment;
    public function __construct($comment)
    {
        $this->comment=$comment;
    }
    public function get_api_comment(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $this->comment->id = get_id();
        $token_errors=checkToken(json_decode(file_get_contents("php://input"))->jwt,1);
        if(!empty($token_errors)){
            echo json_encode($token_errors);
        }else{
            $comments_arr = $this->comment->get_one_comment();
            if (empty($comments_arr['records'])):
                // set response code - 404 Not found
                http_response_code(404);
                // tell the user no products found
                echo json_encode(
                    array("message" => "No Comments found.")
                );
            else:
                // set response code - 200 OK
                http_response_code(200);
                // show products data in json format
                echo json_encode($comments_arr);
            endif;
        }
    }

    public function createComment(){
        $data = headersAndPostedData('post');
        $token_errors=checkToken($data);
        $errors = validation($data);
        if(!empty($token_errors)){
            echo json_encode($token_errors);
        }else if(!empty($errors)){
            echo json_encode($errors);
        }elseif (!empty($data->name) && !empty($data->email) && !empty($data->body)) {
            $this->comment->name = htmlspecialchars(strip_tags($data->name));
            $this->comment->email = htmlspecialchars(strip_tags($data->email));
            $this->comment->body = htmlspecialchars(strip_tags($data->body));
                $rowCount = $this->comment->insertCommentsApi();
                if ($rowCount > 0) {
                    // set response code - 201 created
                    http_response_code(201);
                    // tell the user
                    $messages['success_msg'] = "comment was created.";
                } else {// if unable to create the product, tell the user
                    // set response code - 503 service unavailable
                    http_response_code(503);
                    // tell the user
                    $messages['error_msg'] = "Unable to create comment.";
                }
                echo json_encode($messages);
            }

    }

    public function updateComment(){
        $data = headersAndPostedData("put");
        $errors = (array)validation($data,1);
        $token_errors=checkToken($data);
        $errors = validation($data);
        if(!empty($token_errors)){
            echo json_encode($token_errors);
        }else if(!empty($errors)){
            echo json_encode($errors);
        }elseif (!empty($data->name) && !empty($data->email) && !empty($data->body) && !empty($data->id)) {
            $this->comment->id=htmlspecialchars(strip_tags($data->id));
            $this->comment->name=htmlspecialchars(strip_tags($data->name));
            $this->comment->email=htmlspecialchars(strip_tags($data->email));
            $this->comment->body=htmlspecialchars(strip_tags($data->body));
                $rowCount = $this->comment->updateComment();
                if ($rowCount > 0) {
                    // set response code - 201 created
                    http_response_code(202);
                    // tell the user
                    $messages['success_msg']= "comment was Updated.";
                }else {// if unable to create the product, tell the user
                    // set response code - 503 service unavailable
                    http_response_code(503);
                    // tell the user
                    $messages['error_msg']= "Unable to update comment.";
                }
            echo json_encode($messages);

         }
    }

    public function deleteComment(){
        $data = headersAndPostedData('delete');
        $token_errors=checkToken($data);
        if(!empty($token_errors)){
            echo json_encode($token_errors);
        }elseif(empty($data->id)) {
            // tell the user data is incomplete
            // set response code - 400 bad request
            http_response_code(415);
            // tell the user
            $errors['id_error']= "ID is required.";
            echo json_encode($errors);
        }elseif (!empty($data->id)) {
            $this->comment->id=htmlspecialchars(strip_tags($data->id));
            $rowCount = $this->comment->deleteComment();
            if ($rowCount > 0) {
                // set response code - 201 created
                http_response_code(203);
                // tell the user
                $messages['success_msg']= "comment was Deleted.";
            }else {// if unable to create the product, tell the user
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                $messages['error_msg']= "Unable to Delete comment.";
            }
            echo json_encode($messages);

        }
    }
}