<?php

require_once('common.php');
$db = dbConnect();
session_start();

if(isset($_GET['page'])){

  if($_GET['page'] == 'article_list'){
    require_once('controllers/article_list.php');
  }
  elseif($_GET['page'] == 'article'){
    require_once('controllers/article.php');
  }
  elseif($_GET['page'] == 'connection'){
      require_once('controllers/login-register.php');
  }
  elseif($_GET['page'] == 'profile'){
      require_once('controllers/user-profile.php');
  }
  elseif($_GET['page'] == 'admin'){
      require_once('controllers/admin/index.php');
  }
  elseif($_GET['page'] == 'administration' && $_GET['admin'] == 'user') {
      require_once('controllers/admin/user-list.php');
  }
  elseif($_GET['page'] == 'administration' && $_GET['admin'] == 'article') {
      require_once('controllers/admin/article-list.php');
  }
  elseif($_GET['page'] == 'administration' && $_GET['admin'] == 'category') {
      require_once('controllers/admin/category-list.php');
  }
  elseif($_GET['page'] == 'administration' && $_GET['admin'] == 'new-user') {
      require_once('controllers/admin/user-form.php');
  }
  elseif($_GET['page'] == 'administration' && $_GET['admin'] == 'new-article') {
      require_once('controllers/admin/article-form.php');
  }
  elseif($_GET['page'] == 'administration' && $_GET['admin'] == 'new-category') {
      require_once('controllers/admin/category-form.php');
  }
  elseif($_GET['page'] == 'administration' && $_GET['admin'] == 'modify-user' && isset($_GET['user_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {
      require_once('controllers/admin/user-form.php');
  }
  elseif($_GET['page'] == 'administration' && $_GET['admin'] == 'modify-article' && isset($_GET['article_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {
      require_once('controllers/admin/article-form.php');
  }
  elseif($_GET['page'] == 'administration' && $_GET['admin'] == 'modify-category' && isset($_GET['category_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {
      require_once('controllers/admin/category-form.php');
  }
  else{
    require_once('controllers/index.php');
  }
}
else{
  require_once('controllers/index.php');
}

if(isset($_GET['logout']) && isset($_SESSION['user'])){
    logout();
}


?>
