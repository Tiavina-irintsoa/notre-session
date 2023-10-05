<?php
function connect(){
    $host = "localhost"; // Adresse de la base de données PostgreSQL
    $port = "5432"; // Port PostgreSQL (généralement 5432)
    $dbname = "session"; // Nom de la base de données
    $user = "postgres"; // Nom d'utilisateur PostgreSQL
    $password = "root"; // Mot de passe PostgreSQL

    $dbh = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
    return $dbh;
}