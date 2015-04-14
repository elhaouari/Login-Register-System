<?php

include 'Controllers/Login.php';

$login = new Login();


if ($login->isLogin())
{
    $login->view('dashbord', ['login' => $login]);
}
else
{
    $login->view('index');
}
