<?php

// Script donde se guardará un json con la estructura de nodos

include_once 'SessionManager.inc.php';
include_once 'Note.inc.php';
include_once 'NoteRepository.inc.php';
require_once 'Connection.inc.php';

header('ContentType: application/json; charset=UTF-8');

if(SessionManager::sessionStarted()){

    $username = $_SESSION['username'];

    $errors = false;
    $_SESSION['errorMessage'] = [];

    $selectedNode = isset($_POST['selectedNode']) ? $_POST['selectedNode'] : '';

    if(empty($selectedNode)){
        $errors = true;
        $_SESSION['errorMessage'][] = 'Error desconocido al recuperar la nota seleccionado.';
    }

    // Nos conectamos a la base de datos
    Connection::openConnection();
    $connection = Connection::getConnection();

    if($errors){
        echo '<div class="card-body pt-1">';
        foreach($_SESSION['errorMessage'] as $errorMessage){
            echo '<div class="alert alert-danger mt-3" role="alert">';
            echo $errorMessage;
            echo '</div>';
        }
        echo '</div>';
    }else{
        $nodeObject = json_decode($selectedNode);
        $node = Note::castingFromStdClass($nodeObject);
        $arrayNotes = NoteRepository::getNotesByNode($connection, $node);
        if(!is_null($arrayNotes)){ // Puede que para ese nodo no haya notas
            echo json_encode($arrayNotes); // Las transformamos en JSON para traspasarlas a JS
                    }
    }
    Connection::closeConnection();
}

?>