<?php
namespace Core\Classes;


class logout
{
    function user_logout()
    {
        $_SESSION = array(); // reset session array
        session_destroy();   // destroy session.
        //delete from cookie if expires
        setcookie("cook_email", '', time() - 60 * 60 * 24 * COOKIE_TIME_OUT);
        setcookie("cook_pass", '', time() - 60 * 60 * 24 * COOKIE_TIME_OUT);
        setcookie("cook_rem", '', time() - 60 * 60 * 24 * COOKIE_TIME_OUT);
    }
}