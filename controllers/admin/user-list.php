<?php

if(!isset($_SESSION['is_admin']) OR $_SESSION['is_admin'] == 0){
    header('location:index.php');
    exit;
}
require_once ('models/admin/categories.php');
require_once ('models/admin/articles.php');
require_once ('models/admin/users.php');


$categoriesAll = categoriesAll();
$articlesAll = articlesAll();
$usersAll = usersAll();
$users = userList();

if(isset($_GET['user_id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){
    deleteUser($_GET['user_id']);

}
require_once ('views/admin/user-list.php');
?>