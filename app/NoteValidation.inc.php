<?php

class NoteValidation{

    private $descriptionError = '';
    private $textError = '';
    private $noteExistsError= '';
    private $noteNotExistsError = '';

    public function validateEmptyFields($description, $text){
        if($description == ''){
            $this->descriptionError = 'Por favor, introduzca una descripción para la nota.';
        }

        if($text == ''){
            $this->textError = 'Por favor, introduzca una texto para el contenido de la nota.';
        }
    }

    public function validateNoteExists($connection, $note){
        
        include_once 'NodeRepository.inc.php';

        if(NoteRepository::noteExistsByDescription($connection, $note)){
            $this->noteExistsError = 'Ya existe una nota con esa descripción para el nodo seleccionado.';
        }
    }

    public function validateNoteNotExists($connection, $id){
        
        $retrievedNote = null;
        include_once 'NodeRepository.inc.php';

        $retrievedNote = NoteRepository::retrieveNote($connection, $id);
        if(is_null($retrievedNote)){
            $this->noteNotExistsError = 'No existe la nota en la base de datos.';
        }
        return $retrievedNote;
    }

    public function getDescriptionError(){
        return $this->descriptionError;
    }

    public function getTextError(){
        return $this->textError;
    }

    public function getNoteExistsError(){
        return $this->noteExistsError;
    }

    public function getNoteNotExistsError(){
        return $this->noteNotExistsError;
    }

}