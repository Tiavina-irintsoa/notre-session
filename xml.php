<?php
    function getIdsessionname(){
        $jsonData = file_get_contents('config.json');

        $data = json_decode($jsonData, true);

        return $data['idsession-name'];
    }
?>
