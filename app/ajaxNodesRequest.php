<?php

// Script donde se guardará un json con la estructura de nodos

include_once 'SessionManager.inc.php';

header('ContentType: application/json; charset=UTF-8');

if(SessionManager::sessionStarted()){
    echo $_SESSION['nodesStructure'];
}

?>