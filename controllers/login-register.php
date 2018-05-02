<?php
require_once('models/article.php');
require_once('models/category.php');
require_once ('models/login-register.php');
$categories = getCategories();
if(isset($_POST['login'])) {
    $user = Connection($_POST['email'], $_POST['password']);
    if ($user) {
        header('location:index.php');
    }
}
if(isset($_POST['register'])) {
    $user = newUser($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password'], $_POST['bio'], $_POST['password_confirm'], $_SESSION['id']);
    if ($user) {
        header('location:index.php');
    }
}
require_once ('views/login-register.php');