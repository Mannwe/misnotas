<?php

class NodeValidation {

    private $nodenameError;
    private $nodeExistsError;
    private $nodeWithChildrenError;
    private $nodeWithNotesError;

    public function validateEmptyFields($nodeName){
        if($nodeName == ''){
            $this->nodenameError = 'Por favor, introduzca un nombre descriptivo para el nodo.';
        }
    }

    public function validateNodenameExists($connection, $node){

        if(empty($this->nodenameError)){
            include_once 'NodeRepository.inc.php';

            if(NodeRepository::nodenameExists($connection, $node)){
                $this->nodenameError = 'Ya existe un nodo con ese nombre en ese nivel.';
            }
        }
    }

    public function validateNodeExists($connection, $node){

        include_once 'NodeRepository.inc.php';

        if(!NodeRepository::nodeExists($connection, $node)){
            $this->nodeExistsError = 'El nodo seleccionado no existe.';
        }
    }

    public function validateNodeHasChildren($connection, $node){

        include_once 'NodeRepository.inc.php';

        if(NodeRepository::nodeHasChildren($connection, $node)){
            $this->nodeWithChildrenError = 'El nodo seleccionado contiene otros nodos.';
        }
    }

    public function validateNodeHasNotes($connection, $node){

        include_once 'NoteRepository.inc.php';

        if(NoteRepository::nodeHasNotes($connection, $node)){
            $this->nodeWithNotesError = 'El nodo seleccionado contiene notas.';
        }
    }

    public function getNodenameError(){
        return $this->nodenameError;
    }

    public function getNodeExistsError(){
        return $this->nodeExistsError;
    }

    public function getNodeWithChildrenError(){
        return $this->nodeWithChildrenError;
    }

    public function getNodeWithNotesError(){
        return $this->nodeWithNotesError;
    }
}
