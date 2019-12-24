<?php

    include_once 'SessionManager.inc.php';
    include_once 'Connection.inc.php';
    include_once 'Note.inc.php';
    include_once 'NoteValidation.inc.php';
    include_once 'NoteRepository.inc.php';
    include_once 'Redirection.inc.php';

    if(SessionManager::sessionStarted()){
        $username = $_SESSION['username'];
        $errors = false;
        $_SESSION['errorMessage'] = [];

        if(!isset($_POST['selectedNoteId'])){
            $_SESSION['errorMessage'][] = 'Nota no definida.';
            Connection::closeConnection();
            Redirection::redirect(ERRORS_PAGE_PATH . '/' . $username);
            return;
        }
        $noteId = $_POST['selectedNoteId'];
        if($noteId == 0){
            $_SESSION['errorMessage'][] = 'Error al obtener el identificador de la nota.';
            $errors = true;
        }
        // Nos conectamos a la base de datos
        Connection::openConnection();
        $connection = Connection::getConnection();
        $validator = new NoteValidation();
        if(isset($noteId)){
            $note = $validator->validateNoteNotExists($connection, $noteId);
            $noteNotExistsError = $validator->getNoteNotExistsError();
        }
        if(!empty($noteNotExistsError)){
            $_SESSION['errorMessage'][] = $noteNotExistsError;
            $errors = true;
        }
        if(!isset($note) || is_null($note)){
            $_SESSION['errorMessage'][] = 'Error desconocido al recuperar la nota de la base de datos.';
            $errors = true;
        }
        if($errors){
            Redirection::redirect(ERRORS_PAGE_PATH . '/' . $username);
            Connection::closeConnection();
            return;
        }
        if(!NoteRepository::deleteNote($connection, $noteId)){
            $_SESSION['errorMessage'][] = 'Error inesperado. No se ha podido eliminar la nota seleccionada.';
            Redirection::redirect(ERRORS_PAGE_PATH . '/' . $username);
            Connection::closeConnection();
            return;
        }
        Redirection::redirect(MAIN_PATH . '/' . $username);
        Connection::closeConnection();
    }
