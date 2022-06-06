<?php

namespace App\Controllers;

use Core\App;
use Core\Classes\api_comments;
use Core\Classes\comments;
use Core\Classes\consume_comments;
use Core\Classes\login;
use Core\Classes\logout;
use Core\Classes\users;

class CommentsController
{
    public function index(){
        $comments = new comments();
        $total_page = $comments->count_rows();
        $link_prev = (page() > 1) ? page() - 1 : 1;$total_number = 3; //to Specify the link number before and after the current page
        $total_number = 3; //to Specify the link number before and after the current page
        $start_number = (page() > $total_number) ? (int)page() - $total_number : 1; // For the beginning of the member link
        $end_number = (page() < ($total_page - $total_number)) ? page() + $total_number : $total_page;
        $limit_start = limit_start();
        $no = $limit_start + 1;
        return view('index', ['no'=>$no,
            'comments'=>$comments,
            'total_page'=>$total_page,
            'link_prev'=>$link_prev,
            'total_number'=>$total_number,
            'start_number'=>$start_number,
            'end_number'=>$end_number
        ]);
    }

    public function consume_api(){
        $consume = new consume_comments(App::get('database'));
        $consume->insert_into_api();
    }

    public function getOneComment(){
        $api_comments = new api_comments(App::get('database'));
        $api_comments->get_api_comment();
    }

    public function createComment(){
        $api_comments = new api_comments(App::get('database'));
        $api_comments->createComment();
    }

    public function updateComment(){
        $api_comments = new api_comments(App::get('database'));
        $api_comments->updateComment();
    }

    public function deleteComment(){
        $api_comments = new api_comments(App::get('database'));
        $api_comments->deleteComment();
    }

    public function consumeAll(){
        $comments = new comments();
        $comments->make_api_pagination();
    }

    public function createUser(){
        $user = new users(App::get('user_database'));
        $user->createUser();
    }

    public function login(){
        $user = new users(App::get('user_database'));
        $user->login();
    }

    public function update_user(){
        $user = new users(App::get('user_database'));
        $user->updateUser();
    }

    public function logout(){
        $user = new users(App::get('user_database'));
        $user->logout();
    }

    public function login_page(){
        return view('login');
    }

    public function user_login(){
        $login = new login(App::get('user_database'));
        $errors = $login->user_login();
        if(isset($_SESSION['user_email']) && isset($_SESSION['user_id'])):
            redirect("../comments/1");
        elseif(is_array($errors)):
            return view('login',['errors'=>$errors]);
        endif;
    }

    public function user_logout(){
        $logout = new logout();
        $logout->user_logout();
        redirect('login_user');
    }
}