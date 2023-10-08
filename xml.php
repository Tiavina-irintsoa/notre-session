<?php
    function getIdsessionname(){
        $xml = simplexml_load_file('config.xml');
    
        $idessionValue = (string)$xml->idsession;

        return trim($idessionValue) ;
    }
?>
