<?php

function usersAll(){
    $db = dbConnect();
    $query = $db->query("SELECT COUNT(*) FROM user")->fetchColumn();
    return $query;
}

function userList(){
    $db = dbConnect();
    $query = $db->query('SELECT * FROM user');
    return $query->fetchAll();
}

function newUser($firstname, $lastname, $password, $email, $is_admin, $bio){
    $db = dbConnect();
    $query = $db->prepare('INSERT INTO user (firstname, lastname, password, email, is_admin, bio) VALUES (?, ?, ?, ?, ?, ?)');
    $newUser = $query->execute(
        [
            $firstname,
            $lastname,
            hash('md5', $password),
            $email,
            $is_admin,
            $bio,
        ]
    );
    if($newUser){
        header('location:index.php?page=administration&admin=user');
        exit;
    }
    else{ //si pas $newUser => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
        $message = "Impossible d'enregistrer le nouvel utilisateur...";
    }
}
function modifyUser($firstname, $lastname, $email, $bio, $user_id , $password = false){
    $db = dbConnect();
    $queryString = 'UPDATE user SET firstname = :firstname, lastname = :lastname, email = :email, bio = :bio ';
    $queryParameters = [ 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'bio' => $bio, 'id' => $user_id ];
    if( !empty($password)) {
        $queryString .= ', password = :password ';
        $queryParameters['password'] = hash('md5', $password);
    }
    $queryString .= 'WHERE id = :id';
    $query = $db->prepare($queryString);
    $result = $query->execute($queryParameters);
    if($result){
        header('location:index.php?page=administration&admin=user');
        exit;
    }
    else{
        $message = 'Erreur.';
    }
}
function deleteUser($user_id){
    $db = dbConnect();
    $query = $db->prepare('DELETE FROM user WHERE id = ?');
    $result = $query->execute(
        [
            $user_id
        ]
    );
    if($result){
        $message = "Suppression efféctuée.";
        header('location:index.php?page=administration&admin=user');
        exit;
    }
    else{
        $message = "Impossible de supprimer la seléction.";
    }
}
function informationUser($user_id){
    $db = dbConnect();
    $query = $db->prepare('SELECT * FROM user WHERE id = ?');
    $query->execute(array($user_id));
    return $query->fetch();
}


