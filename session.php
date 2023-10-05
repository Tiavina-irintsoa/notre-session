// les fonctions ici 
<?php

    require_once('connexion.php');
    function session_invalidate(){
        $idsession = $_COOKIE["idsession"];
        $query = "update session_value set invalide = now() where idsession = '%s'"; 
        $query = sprintf($query, $idsession); 
        $pdo = connect();
        $pdo->exec($sql);
        $pdo = null; 
        unset($_COOKIE['idsession'])
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
?>