<?php

include 'Controllers/Register.php';

$register = new Register();

$info = null;
if( $register->isPost() )
{
    $register->hydrate($_POST);
    $info = array();

    $info['username'] = $_POST['username'];
    $info['email']    = $_POST['email'];
    $info['password'] = $_POST['password'];

    if ($register->hasError())
    {
        $info['errors']['username'] = $register->getError('username');
        $info['errors']['email']    = $register->getError('email');
        $info['errors']['password'] = $register->getError('password');
    }
    else
    {
        if ($register->save()){
            header('Location: index.php');
        }
        else
        {
            $info['errors']['username'] = $register->getError('username');
            $info['errors']['email']    = $register->getError('email');
            $info['errors']['register'] = $register->getError('register');
        }
    }
}

$register->view('register', $info);
