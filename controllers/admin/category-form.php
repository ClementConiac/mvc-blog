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
$categories = categoryList();

if(isset($_POST['save'])) {
    newCategory($_POST['name'], $_POST['description'], $_FILES['image'], $_FILES['image']['name'], $_FILES['image']['tmp_name']) ;
}
if(isset($_POST['update'])){
    modifyCategory($_POST['description'], $_POST['name'], $_POST['id'], $_FILES['image'], $_FILES['image']['name'], $_FILES['image']['tmp_name'], $_POST['current_image']);
}
if(($_GET['page'] == 'administration') && (isset($_GET['admin']) == 'modify-category') && isset($_GET['category_id']) && isset($_GET['action']) && $_GET['action'] == 'edit'){
    $category = informationCategory($_GET['category_id']);
}


require_once ('views/admin/category-form.php');
?>