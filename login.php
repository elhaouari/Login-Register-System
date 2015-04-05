<?php

include 'Controllers/Login.php';

$login = new Login();

$login->loginByCookies();

if ($login->isLogin()){
    header('Location: index.php');
}

$info = null;
if( $login->isPost() )
{

    $info = array();

    $info['username'] = $_POST['username'];
    $info['password'] = $_POST['password'];
    $info['remember'] = isset($_POST['remember_me']);

    // set information in object from form
    $login->hydrate($info);

    if ($login->hasError())
    {
        $info['errors']['username'] = $login->getError('username');
        $info['errors']['email']    = $login->getError('email');
        $info['errors']['password'] = $login->getError('password');
    }
    else
    {
        if ($login->login()){
            header('Location: index.php');
        }
        else
        {
            $info['errors']['login'] = $login->getError('login');
        }
    }
}

$login->view('login', $info);
