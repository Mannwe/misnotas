<?php

include_once 'NodeRepository.inc.php';
include_once 'Connection.inc.php';
include_once 'SessionManager.inc.php';

class NodesStructureLoad{

    public function initializeStructure(){
        $this->nodes = [];
    }

    public function load(){
        Connection::openConnection();
        $connection = Connection::getConnection();

        if(SessionManager::sessionStarted()){
            $tmpNodes = NodeRepository::getAllNodesByUser($connection, $_SESSION['username']);

            if(isset($tmpNodes)){
                // Recorremos el array y determinamos si los nodos tienen hijos
                foreach($tmpNodes as $node){

                    $hasChildren = NodeRepository::nodeHasChildren($connection, $node);

                    $node->setHasChildren($hasChildren);

                    // Inicialmente están contraídos
                    $node->setExpanded(false);

                    $this->nodes[] = $node;
                }
            }

            /* Cargaremos los nodos comprimidos existentes en la base de datos dentro de la
               variable de sesión nodesStructure */
            $_SESSION['nodesStructure'] = json_encode($this->nodes);
        }

        Connection::closeConnection();
    }  
    
    // Devolvemos el array con elementos Node (en lugar de StdClass)
    public function getNodesStructureArray(){

        foreach($this->nodes as $stdObject){
            // Transformamos los Std Objects en Nodes
            $node = Node::castingFromStdClass($stdObject);
            $databaseStructure[] = $node;
        }

        if(isset($databaseStructure)) return $databaseStructure;
        else                          return null;
    }
}