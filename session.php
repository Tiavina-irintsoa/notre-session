<?php
// les fonctions ici 
    require_once('connexion.php');
    require_once('xml.php');

    function verifier_session(){
        date_default_timezone_set('Indian/Antananarivo');
        $idsession = $_COOKIE[getIdsessionname()];
        $session_time = getSessiontime();
        $time =  isOut($idsession);
    
        $heure1 = new DateTime($session_time);
        
        $heure2 = new DateTime($time);
    
        // echo "</br>".$session_time."  ". $time;
        // Comparaison
        // var_dump($heure1);
        // var_dump( $heure1 < $heure2 );
        // var_dump( $heure1 > $heure2 );
        // var_dump( $heure1 == $heure2 );
    
        if ($heure1 < $heure2) {
            // echo "miditra";
            updatecookie(); 
        } 
    }
    function isOut( $idsession ){
        $pdo = connect();
        $query =  "select ((now() - start)::interval ) as time from session_value where idsession = '%s'"; 
        $query = sprintf($query , $idsession);
        // echo $query.'</br>';
        $result = $pdo->query($query);
        if ($result) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $result = $row['time'];
            return $result;
        }
        return null;
    }

    function getSessiontime(){
        // Lire le contenu du fichier JSON en tant que chaîne
        $jsonData = file_get_contents('config.json');
    
        // Décoder la chaîne JSON en un tableau associatif
        $data = json_decode($jsonData, true);
    
        return $data['session-time'];
    }
    
    function updatecookie(){
        $nomDuCookie = getIdsessionname();
        setcookie($nomDuCookie, '', time() - 3600);
        unset($_COOKIE[$nomDuCookie]);
    }

    function session_invalidate(){
        $idsession = $_COOKIE[getIdsessionname()];
        $query = "UPDATE session_value
        SET valeur = '{}'::jsonb where idsession = '%s'"; 
        $query = sprintf($query, $idsession); 
        $pdo = connect();
        $pdo->exec($query);
        $pdo = null; 
        unset($_COOKIE[getIdsessionname()]);
    }

    function set_session( $key , $value ){
        if(!isset($_COOKIE[getIdsessionname()])) throw new Exception('Session non activee');
        $pdo = connect();
        save( $key , $value );
    }

    function save( $key , $value ){
        $data = array($key => $value);
        $value = json_encode($data);
        $query = "UPDATE session_value
        SET valeur = valeur::jsonb || '%s'::jsonb
        WHERE idsession = '%s'";
        $pdo = connect();
        $query = sprintf($query , $value , $_COOKIE[getIdsessionname()]);
        // echo $query;
        $result = $pdo->exec($query);
    }
    
    function get_session($key){
        if(!isset($_COOKIE[getIdsessionname()])){
            throw new Exception('Session non activee');
        }
        $idsession = $_COOKIE[getIdsessionname()];
        $pdo = connect();
        $data = get_all_session($pdo); 
        $pdo = null;
        if($data == null){
            return null; 
        }
        if($key){
            $result = $data[$key];
        }
        return $result; 
    }

    function get_all_session($pdo){       
        $query =  "select valeur from session_value where idsession = '%s' "; 
        $idsession = $_COOKIE[getIdsessionname()];
        $query = sprintf($query , $idsession);
        $result = $pdo->query($query);
        if ($result) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $jsonData = $row['valeur'];
                $parsedData = json_decode($jsonData, true);
                return $parsedData; 
            } else {
                
                return null; 
            }
        } else {
           
            return null;
        }
    }

    function removeAttribute($attribute){
        $idsession = $_COOKIE[getIdsessionname()];
        $query = "UPDATE session_value SET valeur = valeur::jsonb - '%s' where idsession ='%s'"; 
        $query = sprintf($query, $attribute, $idsession); 
        $pdo = connect();
        $pdo->exec($query);
        $pdo = null; 
        if(isset($_SESSION[$attribute])){
            unset($_SESSION[$attribute]);
        }
    }

    function start_session(){
        if( isset( $_COOKIE[getIdsessionname()] ) )
            verifier_session();
        if(!isset($_COOKIE[getIdsessionname()]) ){
            $query = "insert into session_value (idsession , valeur ) values ( ( SELECT left(md5(random()::text), 14) || nextval( 'idsession' )) , '{}' ) returning idsession ;"; 
            $pdo = connect();
            // echo $query;
            $result = $pdo->query($query);
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $lastInsertId = $row['idsession'];
            $pdo = null;
            // echo getIdsessionname();
            setcookie(getIdsessionname(), $lastInsertId, time() + 3600, '/');
       
            $_COOKIE[getIdsessionname()] = $lastInsertId;
            // echo 'session ao';
        }
    }
?>