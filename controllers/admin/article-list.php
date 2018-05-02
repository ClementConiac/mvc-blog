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

if(isset($_GET['article_id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){
    deleteArticle($_GET['article_id']);
}

require_once ('views/admin/article-list.php');
?>