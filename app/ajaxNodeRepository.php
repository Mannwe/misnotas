<?php

// Script donde se guardará un objeto nodo para comunicar PHP-JS

include_once 'SessionManager.inc.php';

header('ContentType: application/json; charset=UTF-8');

if(SessionManager::sessionStarted()){
    $_SESSION['currentNode'] = isset($_POST['currentNode']) ? $_POST['currentNode'] : '';
    $_SESSION['nodesStructure'] = isset($_POST['nodesStructure']) ? $_POST['nodesStructure'] : $_SESSION['nodesStructure'];
}

?>