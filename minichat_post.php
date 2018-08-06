<?php

include ('vendor/autoload.php');
use \Colors\RandomColor;

// Connexion à la base de données

 try {

    $bdd = new PDO('mysql:host='.(getenv('MYSQL_HOST') ?: 'localhost').';dbname=mini_chat_Mo;charset=utf8', 'root', '');
 }

 catch(Exception $e) {
        
        die('Erreur : '.$e->getMessage());
}


// Insertion du message à l'aide d'une requête préparée

$req = $bdd->prepare('INSERT INTO messages (pseudo, message, date, IP, UserAgent) VALUES(?, ?, NOW(), ?, ?)');

 $req->execute(array($_POST['pseudo'], $_POST['message'], get_ip(), $_SERVER['HTTP_USER_AGENT']));

 function get_ip() {
        // IP si internet partagé
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        // IP derrière un proxy
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        // Sinon : IP normale
        else {
            return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
        }
 }
    
// ! Requete afin d'appliquer un Random de couleur sur le pseudo .. ! //

// 1/ Vérifier que le pseudo n'existe pas encore en base de données
// 2/ Si le pseudo n'existe pas dans la table users, on insère, autre methode prepare -> execute 
$pseudoExistsQuery = $bdd->query('SELECT * FROM users WHERE pseudo = "'.$_POST["pseudo"].'"');
$pseudoWithColor = $pseudoExistsQuery->fetchAll();


if( ! count( $pseudoWithColor )) {
        // 3 DAns afficahge des données, récuperer la couleur
        $sql = 'INSERT INTO users (pseudo, color) VALUES ("'.$_POST["pseudo"].'", "'.RandomColor::one().'" )';

        $insertQuery = $bdd->query($sql);

        if(!$insertQuery) {
                die("Erreur mysql : ".$bdd->errorInfo()[2]."\n<br>".$sql);
        }
}

// Gestion Cookies
setcookie('pseudo', $_POST['pseudo'], (time() + 365*24*3600), null, null, false, true);

// // Redirection du visiteur vers la page du minichat

header('Location: index.php#message');

?>