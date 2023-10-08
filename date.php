<?php
// Establece la zona horaria predeterminada a "America/New_York"
date_default_timezone_set('Indian/Antananarivo');

// Ahora puedes realizar operaciones de fecha y hora en la zona horaria configurada
$currentTime = date('Y-m-d H:i:s');
echo "La hora actual en Nueva York es: $currentTime";

require_once('connexion.php');
require_once('session.php');

function getSessiontime(){
    // Lire le contenu du fichier JSON en tant que chaîne
    $jsonData = file_get_contents('ralph.json');

    // Décoder la chaîne JSON en un tableau associatif
    $data = json_decode($jsonData, true);

    return $data['session-time'];
}

function updatecookie(){
    // Nom du cookie à supprimer
    $nomDuCookie = 'idsession';

    // Supprimer le cookie en lui attribuant une valeur vide et une date d'expiration passée
    setcookie($nomDuCookie, '', time() - 3600);

    // Vous pouvez également unset() la variable associée au cookie si nécessaire
    unset($_COOKIE[$nomDuCookie]);
    // start_session();
}
// start_session();
// verifier_session();
function verifier_session(){
    date_default_timezone_set('Indian/Antananarivo');
    $idsession = $_COOKIE["idsession"];
    $session_time = getSessiontime();
    $time =  isOut($idsession);

    $heure1 = new DateTime($session_time);
    
    $heure2 = new DateTime($time);

    echo "</br>".$session_time."  ". $time;
    // Comparaison
    var_dump($heure1);
    var_dump( $heure1 < $heure2 );
    var_dump( $heure1 > $heure2 );
    var_dump( $heure1 == $heure2 );

    if ($heure1 < $heure2) {
        echo "miditra";
        updatecookie(); 
    } 
}
function isOut( $idsession ){
    $pdo = connect();
    $query =  "select ((now() - start)::interval ) as time from session_value where idsession = '%s'"; 
    $query = sprintf($query , $idsession);
    echo $query.'</br>';
    $result = $pdo->query($query);
    if ($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $result = $row['time'];
        return $result;
    }
    return null;
}


?>