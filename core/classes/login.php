<?php
namespace Core\Classes;


class login
{
    protected $users;
    public function __construct($users)
    {
        $this->users=$users;
    }
    public function user_login(){
        $errors = [];
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
            $this->users->email = trim($_POST['email']);
            $this->users->password = trim($_POST['password']);
            if(isset($_POST['remember-me'])):
                $remember_me = trim($_POST['remember-me']);
            endif;
            if (empty($this->users->email) || empty($this->users->password)) {
                $errors[] = "\nEmail or Password required.";
            }

            if (!empty($this->users->email) && !filter_var($this->users->email,FILTER_VALIDATE_EMAIL)) {
                $errors[] = "\nInvalid Email Address";
            }

            if ((!empty($this->users->password) && strlen($this->users->password) > 20)) {
                $errors[] = "\nMax length of Password:25";
            }
            if ($this->users->login() == false) { // check if login data is correct
                $errors[] = "\nAuthentication error";
            }
                    if (empty($errors)) {
                    $_SESSION['user_email'] = $this->users->email;
                    $_SESSION['password'] = $this->users->password;
                    $_SESSION['user_id']  = $this->users->id;
                    // if choose remember_me save his login in the cookie
                    if (isset($_POST['remember-me'])) {
                        $_SESSION['user_rem'] = $_POST['remember-me'];
                        setcookie("cook_mail", $_SESSION['user_email'], time() + 60 * 30 * COOKIE_TIME_OUT);
                        setcookie("cook_pass", $_SESSION['password'], time() + 60 * 30 * COOKIE_TIME_OUT);
                        setcookie("cook_rem", $_SESSION['user_rem'], time() + 60 * 30 * COOKIE_TIME_OUT);
                    } else {
                        //destroy any previously set cookie
                        setcookie("cook_mail", '', time() - 60 * 60 * 24 * COOKIE_TIME_OUT);
                        setcookie("cook_pass", '', time() - 60 * 60 * 24 * COOKIE_TIME_OUT);
                        setcookie("cook_rem", '', time() - 60 * 60 * 24 * COOKIE_TIME_OUT);
                    }
                }
                return $errors;
            }
    }
}