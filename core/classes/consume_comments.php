<?php

namespace Core\Classes;


use JetBrains\PhpStorm\NoReturn;

class consume_comments
{
    protected $comment;
    public function __construct($comment)
    {
        $this->comment=$comment;
    }

    public function insert_into_api(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://jsonplaceholder.typicode.com/comments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $response = json_decode($response, true);
        //insert api into db
        foreach ($response as $comment){
            $this->comment->id = $comment['id'];
            $this->comment->name = $comment['name'];
            $this->comment->email = $comment['email'];
            $this->comment->body = $comment['body'];
            $this->comment->insertCommentsApi();
                if ($this->comment->id == 100) {
                    break;
                }
        }
        if($this->comment->insertCommentsApi() > 0){
            http_response_code(210);
            echo json_encode(array("message" => "Comments were inserted."));
        }else{
            http_response_code(510);
            echo json_encode(array("message" => "Unable to insert Comments"));
            }
    }


}