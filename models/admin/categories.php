<?php

function categoriesAll(){
$db = dbConnect();
$query = $db->query("SELECT COUNT(*) FROM category")->fetchColumn();
return $query;
}

function categoryList(){
    $db = dbConnect();
    $query = $db->query('SELECT * FROM category');
    return $query->fetchAll();
}

function newCategory($name, $description, $files_image, $files_image_name, $files_image_tmpName){
    $db = dbConnect();
    $query = $db->prepare('INSERT INTO category (name, description) VALUES (?, ?)');
    $newCategory = $query->execute(
        [
            $name,
            $description
        ]
    );
    if($newCategory){
        if(isset($files_image)){
            $allowed_extensions = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
            $my_file_extension = pathinfo( $files_image_name , PATHINFO_EXTENSION);
            if ( in_array($my_file_extension , $allowed_extensions) ){
                $new_file_name = md5(rand());
                $destination = 'assets/img/category/' . $new_file_name . '.' . $my_file_extension;
                $result = move_uploaded_file( $files_image_tmpName, $destination);
                $lastInsertedCategoryId = $db->lastInsertId();
                $query = $db->prepare('UPDATE category SET
					image = :image
					WHERE id = :id'
                );
                $resultUpdateImage = $query->execute(
                    [
                        'image' => $new_file_name . '.' . $my_file_extension,
                        'id' => $lastInsertedCategoryId
                    ]
                );
            }
        }
        header('location:index.php?page=administration&admin=category');
        exit;
    }
    else {
        $message = "Impossible d'enregistrer la nouvelle categorie...";
    }
}

function modifyCategory($description, $name, $id, $files_image, $files_image_name, $files_image_tmpName, $current_image){
    $db = dbConnect();
    $query = $db->prepare('UPDATE category SET
		name = :name,
		description = :description
		WHERE id = :id'
    );
    $result = $query->execute(
        [
            'description' => $description,
            'name' => $name,
            'id' => $id
        ]
    );
    if($result){
        if(isset($files_image)){
            $allowed_extensions = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
            $my_file_extension = pathinfo( $files_image_name , PATHINFO_EXTENSION);
            if ( in_array($my_file_extension , $allowed_extensions) ){
                //si un fichier est soumis lors de la mise à jour, je commence par supprimer l'ancien du serveur s'il existe
                if(isset($current_image)){
                    unlink('assets/img/category/' . $current_image);
                }
                $new_file_name = md5(rand());
                $destination = 'assets/img/category/' . $new_file_name . '.' . $my_file_extension;
                $result = move_uploaded_file( $files_image_tmpName, $destination);
                $query = $db->prepare('UPDATE category SET
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
        header('location:index.php?page=administration&admin=category');
        exit;
    }
    else {
        $message = "Impossible d'enregistrer la nouvelle categorie...";
    }
}

function deleteCategory($category_id){
    $db = dbConnect();
    $query = $db->prepare('DELETE FROM category WHERE id = ?');
    $result = $query->execute(
        [
            $category_id
        ]
    );
    if($result){
        $message = "Suppression efféctuée.";
        header('location:index.php?page=administration&admin=category');
        exit;
    }
    else{
        $message = "Impossible de supprimer la séléction.";
    }
}

function informationCategory($category_id){
    $db = dbConnect();
    $query = $db->prepare('SELECT * FROM category WHERE id = ?');
    $query->execute(array($category_id));
    return $query->fetch();
}
?>