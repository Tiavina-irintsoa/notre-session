<?php 
    require_once("session.php");
    start_session();
    set_session( "jean" , "jean bal" );
    // set_session( "jeanne" , "jeanne balle" );
    // set_session( "test" , "test" );

    // echo get_session( "jean" );
    // removeAttribute("jean");
    // session_invalidate();
?>