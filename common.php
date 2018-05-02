<?php

function dbConnect(){

	try{
		return new PDO('mysql:host=localhost;dbname=blog-dev;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch (Exception $exception)
	{
		die( 'Erreur : ' . $exception->getMessage() );
	}
}

function logout(){
    unset($_SESSION["user"]);
    unset($_SESSION["is_admin"]);
    unset($_SESSION["user_id"]);
}

?>
