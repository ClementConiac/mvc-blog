<?php

function articlesAll(){
    $db = dbConnect();
    $query = $db->query("SELECT COUNT(*) FROM article")->fetchColumn();
    return $query;
}

function articleList(){
    $db = dbConnect();
    $query = $db->query('SELECT * FROM article');
    return $query->fetchAll();
}

function newArticle($title, $content, $summary, $is_published, $categories, $files_image_name, $files_image_tmpName){
    $db = dbConnect();
    $query = $db->prepare('INSERT INTO article (title, content, summary, is_published, created_at) VALUES (?, ?, ?, ?, NOW())');
    $newArticle = $query->execute(
        [
            $title,
            $content,
            $summary,
            $is_published
        ]
    );

    $lastInsertedArticleId = $db->lastInsertId();
    foreach($categories as $category_id){
        $query = $db->prepare('INSERT INTO article_category (article_id, category_id) VALUES (?, ?)');
        $newArticle = $query->execute(
            [
                $lastInsertedArticleId,
                $category_id,
            ]
        );
    }

    if($newArticle){

        if(!empty($files_image_name)){

            $allowed_extensions = array( 'jpg' , 'jpeg' , 'gif' , 'png' );

            $my_file_extension = pathinfo( $files_image_name , PATHINFO_EXTENSION);

            if ( in_array($my_file_extension , $allowed_extensions) ){

                $new_file_name = md5(rand());

                $destination = 'assets/img/article/' . $new_file_name . '.' . $my_file_extension;

                $result = move_uploaded_file( $files_image_tmpName, $destination);

                $query = $db->prepare('UPDATE article SET
					image = :image
					WHERE id = :id'
                );
                $resultUpdateImage = $query->execute(
                    [
                        'image' => $new_file_name . '.' . $my_file_extension,
                        'id' => $lastInsertedArticleId
                    ]
                );
            }
        }

        header('location:index.php?page=administration&admin=article');
        exit;
    }
    else{
        $message = "Impossible d'enregistrer le nouvel article...";
    }
}
function modifyArticle($title, $content, $summary, $is_published, $id, $categories, $files_image, $files_image_name, $files_image_tmpName){
    $db = dbConnect();
    $query = $db->prepare('UPDATE article SET
		title = :title,
		content = :content,
		summary = :summary,
		is_published = :is_published
		WHERE id = :id'
    );

    $resultArticle = $query->execute(
        [
            'title' => $title,
            'content' => $content,
            'summary' => $summary,
            'is_published' => $is_published,
            'id' => $id,
        ]
    );
    $query = $db->prepare('DELETE FROM article_category WHERE article_id = ?');
    $result = $query->execute(
        [
            $id
        ]
    );
    foreach($categories as $category_id){
        $query = $db->prepare('INSERT INTO article_category (article_id, category_id) VALUES (?, ?)');
        $newArticle = $query->execute(
            [
                $id,
                $category_id,
            ]
        );
    }

    if($resultArticle){
        if(isset($files_image)){
            $allowed_extensions = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
            $my_file_extension = pathinfo( $files_image_name , PATHINFO_EXTENSION);
            if ( in_array($my_file_extension , $allowed_extensions) ){
                $new_file_name = md5(rand());
                $destination = 'assets/img/article/' . $new_file_name . '.' . $my_file_extension;
                $result = move_uploaded_file( $files_image_tmpName, $destination);
                $query = $db->prepare('UPDATE article SET
					image = :image
					WHERE id = :id'
                );
                $resultUpdateImage = $query->execute(
                    [
                        'image' => $new_file_name . '.' . $my_file_extension,
                        'id' => $id
                    ]
                );
            }
        }
        header('location:index.php?page=administration&admin=article');
        exit;
    }
    else{
        $message = 'Erreur.';
    }
}
function deleteArticle($article_id){
    $db = dbConnect();
    $queryImage = $db->prepare('SELECT image FROM article WHERE id = ?');
    $result = $queryImage->execute( [
        $article_id
    ]);
    $nameImage = $queryImage->fetch();
    if ($nameImage) {
        unlink('assets/img/article/' . $nameImage['image']);
    }
    $query = $db->prepare('DELETE FROM article_category WHERE article_id = ?');
    $result = $query->execute(
        [
            $article_id
        ]
    );
    $query = $db->prepare('DELETE FROM article WHERE id = ?');
    $result = $query->execute(
        [
            $article_id
        ]
    );
    if($result){
        $message = "Suppression effectuée.";
        header('location:index.php?page=administration&admin=article');
        exit;
    }
    else{
        $message = "Impossible de supprimer la séléction.";
    }
}
function informationArticle($article_id) {
    $db = dbConnect();
    $query = $db->prepare('SELECT * FROM article WHERE id = ?');
    $query->execute(array($article_id));
    return $query->fetch();
}

function categoriesArticle($article_id){
    $db = dbConnect();
    $query = $db->prepare('SELECT category_id FROM article_category WHERE article_id = ?');
    $query->execute(array($article_id));
    return $query->fetchAll();
}

function newImageArticle($caption, $article_id, $files_image,  $files_image_name, $files_image_tmpName){
    $db = dbConnect();
    $query = $db->prepare('INSERT INTO image (caption, article_id) VALUES (?, ?)');
    $newImage = $query->execute(
        [
            $caption,
            $article_id
        ]
    );
    $lastInsertedImageId = $db->lastInsertId();
    if($newImage){
        if(isset($files_image)){
            $allowed_extensions = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
            $my_file_extension = pathinfo( $files_image_name , PATHINFO_EXTENSION);
            if ( in_array($my_file_extension , $allowed_extensions) ) {
                $new_file_name = md5(rand());
                $destination = 'assets/img/article/' . $new_file_name . '.' . $my_file_extension;
                $result = move_uploaded_file( $files_image_tmpName, $destination);
                $query = $db->prepare('UPDATE image SET
					name = :name
					WHERE id = :id'
                );
                $resultAddImage = $query->execute(
                    [
                        'name' => $new_file_name . '.' . $my_file_extension,
                        'id' => $lastInsertedImageId
                    ]
                );
            }
        }
        header('location:index.php?page=administration&admin=article');
        exit;
    }
    else{
        $message = 'Erreur.';
    }
}
function deleteImageArticle($image_id){
    $db = dbConnect();
    $query = $db->prepare('SELECT name FROM image WHERE id = ?');
    $query->execute(array($image_id));
    $ImgToUnlink = $query ->fetch();

    unlink('assets/img/article/' . $ImgToUnlink['name']);

    $queryDelete = $db->prepare('DELETE FROM image WHERE id=?');
    $queryDelete->execute(array($image_id));

    $imgMessage = "Image Supprimée avec succès !";

}
?>