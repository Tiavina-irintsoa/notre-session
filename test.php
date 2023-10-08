<?php 
    require_once("session.php");

    //session start
    start_session();

    //ajouter une valeur dans une session
        //string
    // set_session( "nom" , "John" );

        //int
    // set_session( "age" , 10 );

        //tableau
    // set_session( "tableau" , array( 0 => 1 ) );

        //dictionnaire
    // set_session( "dico" , array( "dtn" => '2022-01-01' , " json" => 1000 ) );

    //prendre les valeurs
    // echo ( get_session( "nom" ) ) ;
    // echo ( get_session( "age" ) ) ;
    // var_dump ( get_session( "tableau" ) ) ;
    // var_dump ( get_session( "dico" ) ) ;

    //changer la valeur
    // set_session( "dico" , "test" );
    // set_session( "nom" , "Doe" );

    //enlever valeur
    // removeAttribute("nom");


    //tout effacer
    // session_invalidate();
?>