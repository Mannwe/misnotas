<?php

// Script donde se actualizará la estructura de nodos con la de la vista

include_once 'SessionManager.inc.php';

header('ContentType: application/json; charset=UTF-8');

if(SessionManager::sessionStarted()){
    $_SESSION['nodesStructure'] = isset($_POST['nodesStructure']) ? $_POST['nodesStructure'] : '';
}

?>
