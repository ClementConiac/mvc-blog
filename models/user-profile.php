<?php
function connectedUser ($user_id){
    $db = dbConnect();
    $query = $db->prepare('SELECT * FROM user WHERE id = ?');
    $query->execute(array($user_id));
    return  $query->fetch();
}

function updateConnectedUser ($email, $firstname, $lastname, $password, $password_confirm, $bio, $user_id ){
    $db = dbConnect();

    $query = $db->prepare('SELECT email FROM user WHERE email = ?');
    $query->execute(array($email));


    $emailAlreadyExists = $query->fetch();


    if($emailAlreadyExists && $emailAlreadyExists['email'] != $user_id['email']){
        $updateMessage = "Adresse email déjà utilisée";
    }
    elseif(empty($firstname) OR empty($email)){

        $updateMessage = "Merci de remplir tous les champs obligatoires (*)";
    }

    elseif( !empty($password) AND ($password != $password_confirm)) {

        $updateMessage = "Les mots de passe ne sont pas identiques";
    }
    else {


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

            $_SESSION['user'] = $firstname;
            $updateMessage = "Informations mises à jour avec succès !";


            $query = $db->prepare('SELECT * FROM user WHERE id = ?');
            $query->execute(array($user_id));
            $user = $query->fetch();
        }
        else{
            $updateMessage = "Erreur";
        }
    }
    return $query->fetch();
}
?>