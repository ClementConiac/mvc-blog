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
$articles = articleList();

if(isset($_POST['save'])){
    newArticle($_POST['title'], $_POST['content'], $_POST['summary'], $_POST['is_published'], $_POST['categories'], $_FILES['image']['name'], $_FILES['image']['tmp_name']);
}
if(isset($_POST['update'])){
    modifyArticle($_POST['title'], $_POST['content'], $_POST['summary'], $_POST['is_published'], $_POST['id'], $_POST['categories'], $_FILES['image'], $_FILES['image']['name'], $_FILES['image']['tmp_name']);
}
if (($_GET['page'] == 'administration') && (isset($_GET['admin']) == 'modify-article') && isset($_GET['article_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {
    $article = informationArticle($_GET['article_id']);
    $articleCategories = categoriesArticle($_GET['article_id']);
}
if (isset($_POST['add_image'])) {
    newImageArticle($_POST['caption'], $_POST['article_id'], $_FILES['image'], $_FILES['image']['name'], $_FILES['image']['tmp_name']);
}
if (isset($_POST['delete_image'])) {
    deleteImageArticle($_GET['article_id']);
}
require_once ('views/admin/article-form.php');
?>