<?php


// Connexion à la base de données

try {

    $bdd = new PDO('mysql:host=localhost;dbname=mini_chat_Mo;charset=utf8', 'root', '');   
}

catch(Exception $e) {

        die('Erreur : '.$e->getMessage());
}


// Récupération des 10 derniers messages

 // $reponse = $bdd->query('SELECT pseudo, message, date FROM messages ORDER BY ID DESC LIMIT 0, 10');
 $reponse = $bdd->query('SELECT messages.*, users.color FROM messages LEFT OUTER JOIN users ON messages.pseudo = users.pseudo ORDER BY ID DESC LIMIT 0, 10');


// Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)

foreach (array_reverse($reponse->fetchAll()) as $message) {

   // echo '<div class="col-7 offset-1"> <strong>' . htmlspecialchars($donnees['pseudo']) . '</strong> : ' . htmlspecialchars($donnees['message']) . '</div> <div class="col-4"> ' . ($donnees['date']) . '</div>';
   echo '<div class="col-8 list-group-item list-group-item-dark">
             <strong style="color:'.$message["color"].'">' . htmlspecialchars($message['pseudo']) . '</strong>
              : ' . htmlspecialchars($message['message']) . 
        '</div> 
        <div class="col-3 list-group-item list-group-item-success">
             ' . ($message['date']) . 
        '</div>';
}

?>