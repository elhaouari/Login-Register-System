<?php
include 'Controllers/Login.php';

$login = new Login();

$login->logout();

header('Location: index.php');
