<?php
###################api routes #######################

$router->get('consume','CommentsController@consume_api');

$router->get(get_id(),'CommentsController@getOneComment');

$router->post('create','CommentsController@createComment');

$router->put('update','CommentsController@updateComment');

$router->delete('delete','CommentsController@deleteComment');

$router->get(commentsPage(),'CommentsController@consumeAll');

$router->post('register','CommentsController@createUser');

$router->post('login','CommentsController@login');

$router->put('update_user','CommentsController@update_user');

$router->post('logout','CommentsController@logout');
###################wep routes #######################
$router->get(page(),'CommentsController@index');

$router->get('login_user','CommentsController@login_page');

$router->post('sign-in','CommentsController@user_login');

$router->get('logout','CommentsController@user_logout');