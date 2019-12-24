<?php
    
    include_once 'config.inc.php';
    include_once 'SessionManager.inc.php';
    include_once 'Connection.inc.php';
    include_once 'Note.inc.php';
    include_once 'NoteValidation.inc.php';
    include_once 'NoteRepository.inc.php';
    include_once 'Redirection.inc.php';

if(SessionManager::sessionStarted()){

    $errors = false;
    $_SESSION['errorMessage'] = [];

    if(!isset($_POST['currentNode'])){
        $_SESSION['errorMessage']['currentNode'] = 'No se ha podido recuperar el nodo actual.';
        $errors = true;
    }else{
        $_SESSION['errorMessage']['currentNode'] = '';
    }
    
    // Aplicamos las validaciones
    $descriptionError = '';
    $textError = '';
    $noteExistsError = '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $text = isset($_POST['text']) ? $_POST['text'] : '';
    $currentNodeObject = isset($_POST['currentNode']) ? $_POST['currentNode'] : null;

    if(!is_null($currentNodeObject)){
        $currentNode = Note::castingFromStdClass($currentNodeObject);
        
        $newNote = new Note(0,
                            $currentNode->getId(),
                            $description,
                            $text,
                            null,
                            false);
    }

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
    if(isset($newNote)){
        $validator->validateNoteExists($connection, $newNote);
        $noteExistsError = $validator->getNoteExistsError();
    }
    
    if(!empty($noteExistsError)){
        $_SESSION['errorMessage']['noteExists'] = $noteExistsError;
        $errors = true;
    }else{
        $_SESSION['errorMessage']['noteExists'] = '';
    }

    $_SESSION['errorMessage']['unknown'] = '';
    if(!$errors){
        $newNoteId = NoteRepository::createNote($connection, $newNote);
        if(!$newNoteId){
            $_SESSION['errorMessage']['unknown'] = 'Error desconocido al crear la nota.';
            
        }
    }
    echo json_encode($_SESSION['errorMessage']);

    Connection::closeConnection();
}