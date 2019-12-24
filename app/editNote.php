<?php

    include_once 'SessionManager.inc.php';
    include_once 'Connection.inc.php';
    include_once 'Note.inc.php';
    include_once 'NoteValidation.inc.php';
    include_once 'NoteRepository.inc.php';

if(SessionManager::sessionStarted()){

    $errors = false;
    $_SESSION['errorMessage'] = [];

    if(!isset($_POST['id']) || $_POST['id'] == 0){
        $_SESSION['errorMessage']['id'] = 'No se ha podido recuperar el identificador de la nota.';
        $errors = true;
    }else{
        $id = $_POST['id'];
        $_SESSION['errorMessage']['id'] = '';
    }

    // Aplicamos las validaciones
    $descriptionError = '';
    $textError = '';
    $noteNotExistsError = '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $text = isset($_POST['text']) ? $_POST['text'] : '';

    $validator = new NoteValidation();

    $validator->validateEmptyFields($description, $text);
    $descriptionError = $validator->getDescriptionError();
    if(!empty($descriptionError)){
        $_SESSION['errorMessage']['description'] = $descriptionError;
        $errors = true;
    }else{
        $_SESSION['errorMessage']['description'] = '';
    }

    $textError = $validator->getTextError();
    if(!empty($textError)){
        $_SESSION['errorMessage']['text'] = $textError;
        $errors = true;
    }else{
        $_SESSION['errorMessage']['text'] = '';
    }

    // Nos conectamos a la base de datos
    Connection::openConnection();
    $connection = Connection::getConnection();
    if(isset($id)){
        $note = $validator->validateNoteNotExists($connection, $id);
        $noteNotExistsError = $validator->getNoteNotExistsError();

        if(!empty($noteNotExistsError)){
            $_SESSION['errorMessage']['noteNotExists'] = $noteNotExistsError;
            $errors = true;
        }else{
            $_SESSION['errorMessage']['noteNotExists'] = '';
        }

        if(!isset($note) || is_null($note)){
            $_SESSION['errorMessage']['databaseNote'] = 'Error desconocido al recuperar la nota de la base de datos.';
            $errors = true;
        }else{
            $_SESSION['errorMessage']['databaseNote'] = '';
        }

        $_SESSION['errorMessage']['unknown'] = '';
        if(!$errors){
            $note = new Note($id,
                             $note->getNodeId(),
                             $description,
                             $text,
                             $note->getCreationDate(),
                             false);
            if(!NoteRepository::updateNote($connection, $note)){
                $_SESSION['errorMessage']['unknown'] = 'Error desconocido al modificar la nota.';
                $errors = true;
            }
        }
    } // if(isset($id)){

    echo json_encode($_SESSION['errorMessage']);

    Connection::closeConnection();
}
