<?php
// les fonctions ici 

    require_once('connexion.php');
    function session_invalidate(){
        $idsession = $_COOKIE["idsession"];
        $query = "update session_value set invalide = now() where idsession = '%s'"; 
        $query = sprintf($query, $idsession); 
        $pdo = connect();
        $pdo->exec($sql);
        $pdo = null; 
        unset($_COOKIE['idsession']);
    }
    function set_session( $key , $value ){
        if($isset($_COOKIE['idsession'])) throw new Exception('Session non activee');
        $pdo = connect();
        save( $key , $value );
    }

    function save( $key , $value ){
        $query = "UPDATE session_value
        SET valeur = valeur::jsonb || '{\"%s\": \"%s\"}'::jsonb
        WHERE idsession = '%s'";
        echo $query;
        $query = sprintf($query , $key , $value , $_COOKIE["idsession"]);
        $result = $pdo->execute($sql);
    }
    function get_session($key){
        if($isset($_COOKIE['idsession'])){
            throw new Exception('Session non activee');
        }
        $idsession = $_COOKIE["idsession"];
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
       
        $query =  "select valeur from session_value where idsession = %s"; 
        $query = sprintf($query , $idsession);
        $result = $pdo->query($sql);
        if ($result) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $jsonData = $row['json_data'];
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
        $idsession = $_COOKIE["idsession"];
        $query = "UPDATE session_value SET valeur = valeur::jsonb - '%s' where idsession ='%s'"; 
        $query = sprintf($query, $attribute, $idsession); 
        $pdo = connect();
        $pdo->exec($sql);
        $pdo = null; 
        if(isset($_SESSION[$attribute])){
            unset($_SESSION[$attribute]);
        }
    }
    function start_session(){
        var_dump($_COOKIE);
        if(!isset($_COOKIE['idsession']) ){
            $query = "insert into session_value (idsession) values ( ( SELECT left(md5(random()::text), 14) || nextval('idsession')) ) returning idsession ;"; 
            $pdo = connect();
            
            $result = $pdo->query($query);
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $lastInsertId = $row['idsession'];
            $pdo = null;
            // setcookie('idsession', $lastInsertId, time() + 3600, '/');
            $_COOKIE['idsession'] = $lastInsertId;
        }
    }
?>