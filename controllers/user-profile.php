<?php

require_once('models/category.php');
require_once('models/user-profile.php');

$categories = getCategories();

if(!isset($_SESSION['user'])){
    header('location:../index.php');
    exit;
}
$user = connectedUser($_SESSION['user_id']);
if(isset($_POST['update'])){

    $user = updateConnectedUser($_POST['email'], $_POST['firstname'], $_POST['lastname'], $_POST['password'], $_POST['password_confirm'], $_POST['bio'], $_SESSION['id']);
}
require_once ('views/user-profile.php');

?>