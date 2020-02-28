<?php

    // Conexión a la base de datos
    /*define('HOST_NAME', 'localhost');
    define('USERNAME', 'root');
    define('PASSWORD', '');
    define('DATABASE', 'misnotas');*/

    // Conexión remota Clever Cloud
    define('HOST_NAME', 'brc366gte7yq6tbzsnwl-mysql.services.clever-cloud.com');
    define('USERNAME', 'uo7junyhxs800knb');
    define('PASSWORD', '2L5g8k702atEk0urwM59');
    define('DATABASE', 'brc366gte7yq6tbzsnwl');

    // Constantes de la aplicación
    define('HOST', 'http://localhost/misnotas');
    

    // Constantes para la redirección desde el router de index.php
    define('LOGIN_PATH', HOST . '/login');
    define('LOGOUT_PATH', HOST . '/logout');
    define('SIGNUP_PATH', HOST . '/signup');
    define('MAIN_PATH', HOST . '/main');
    define('ERRORS_PAGE_PATH', HOST . '/errorsPage');
    define('NODE_DELETION_PATH', HOST . '/nodeDeletion');
    define('NOTE_DELETION_PATH', HOST . '/noteDeletion');
    define('EDIT_NODE_PATH', HOST . '/editNode');
        
    // Páginas auxiliares que codificamos a parte (porque no se accede a través del index)
    define('NODE_REQUEST_PATH', HOST . '/app/ajaxNodesRequest.php');
    define('NOTE_REQUEST_PATH', HOST . '/app/ajaxNotesRequest.php');
    define('NODE_UPDATE_PATH', HOST . '/app/ajaxNodesUpdate.php');
    define('NODE_REPOSITORY_PATH', HOST . '/app/ajaxNodeRepository.php');
    define('NEW_NODE_FORM_PATH', HOST . '/templates/newNodeForm.inc.php');
    define('NEW_NODE_PATH', HOST . '/app/newNode.php');
    define('NEW_NOTE_FORM_PATH', HOST . '/templates/newNoteForm.inc.php');
    define('NEW_NOTE_PATH', HOST . '/app/newNote.php');
    define('EDIT_NOTE_FORM_PATH', HOST . '/templates/editNoteForm.inc.php');
    define('EDIT_NOTE_PATH', HOST . '/app/editNote.php');    
    
    define('PATH_CSS', HOST . '/css/');
    define('PATH_JS', HOST . '/js/');

?>