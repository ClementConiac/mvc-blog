<?php
function Connection($email, $password) {
    $db = dbConnect();
    if(empty($email) OR empty($password)){
        $loginMessage = "Merci de remplir tous les champs";
    }
    else{
        $query = $db->prepare('SELECT * FROM user WHERE email = ? AND password = ?');
        $query->execute( array( $email, hash('md5', $password), ) );
        $user = $query->fetch();
        if($user){
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['user'] = $user['firstname'];
            $_SESSION['user_id'] = $user['id'];
        }
        else{
            $loginMessage = "Mauvais identifiants";
        }
        return $query->fetch();
    }

}
function newUser($firstname, $lastname, $email, $password, $bio, $password_confirm, $user_id){
    $db = dbConnect();
        $query = $db->prepare('SELECT email FROM user WHERE email = ?');
        $query->execute(array($_POST['email']));
        $userAlreadyExists = $query->fetch();
        if($userAlreadyExists){
            $registerMessage = "Adresse email déjà enregistrée";
        }
        elseif(empty($firstname) OR empty($password) OR empty($email)){
            $registerMessage = "Merci de remplir tous les champs obligatoires (*)";
        }
        elseif($password != $password_confirm) {
            $registerMessage = "Les mots de passe ne sont pas identiques";
        }
        else {
            $query = $db->prepare('INSERT INTO user (firstname,lastname,email,password,bio) VALUES (?, ?, ?, ?, ?)');
            $newUser = $query->execute(
                [
                    $firstname,
                    $lastname,
                    $email,
                    hash('md5', $password),
                    $bio
                ]
            );
            $_SESSION['is_admin'] = 0;
            $_SESSION['user'] = $firstname;
            $_SESSION['id'] = $user_id;
        }
        return $query->fetch();
}