<?php

include_once 'config.inc.php';
include_once 'Connection.inc.php';
include_once 'Node.inc.php';
include_once 'UserRepository.inc.php';


class NodeRepository{

    public static function getAllNodesByUser($connection, $username){
        if(isset($connection)){
            try{
                $user = UserRepository::getUserByUsername($connection, $username);
                if(!is_null($user)){
                    $userId = $user->getId();

                    $sql = 'SELECT * FROM nodos WHERE id_usuario = :userid';
                    $statement = $connection->prepare($sql);

                    $statement->bindParam('userid', $userId);
                    $statement->execute();
                    $result = $statement->fetchAll();

                    foreach($result as $record){
                        $node = new Node($record['id_usuario'],
                                         $record['id'],
                                         $record['id_padre'],
                                         $record['nombre'],
                                         false,
                                         false,
                                         false,
                                         0,
                                         0);
                        $arrayNodes[] = $node;
                    }

                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $arrayNodes;
    }

    public static function nodeHasChildren($connection, $node){

        $hasChildren = false;

        if(isset($connection)){
            try{
                $sql = 'SELECT * FROM nodos WHERE id_usuario = :userid AND id_padre = :current';
                $statement = $connection->prepare($sql);

                $userId = $node->getUserId();
                $id = $node->getId();

                $statement->bindParam('userid', $userId, PDO::PARAM_STR);
                $statement->bindParam('current', $id, PDO::PARAM_STR);
                $statement->execute();
                $result = $statement->fetchAll();

                if($result){
                    $hasChildren = true;
                }else{
                    $hasChildren = false;
                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
            return $hasChildren;
        }
    }

    public static function nodenameExists($connection, $node){

        $nodeExists = true;
        if(isset($connection)){
            try{
                if(isset($node)){
                    $userId = $node->getUserId();
                    $parentId = $node->getParentId();
                    $nodeName = $node->getName();

                    $sql = 'SELECT * FROM nodos WHERE id_usuario = :userid AND nombre = :nodename AND id_padre = :parentId';
                    $statement = $connection->prepare($sql);

                    $statement->bindParam(':userid', $userId, PDO::PARAM_STR);
                    $statement->bindParam(':parentId', $parentId, PDO::PARAM_STR);
                    $statement->bindParam(':nodename', $nodeName, PDO::PARAM_STR);
                    $statement->execute();

                    $result = $statement->fetchAll();

                    if(count($result)) {
                        $nodeExists = true;
                    }else{
                        $nodeExists = false;
                    }
                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $nodeExists;
    }

    public static function createNode($connection, $node){

        $newNodeId = 0;

        if(isset($connection)){
            try{
                if(isset($node)){
                    // Preparamos la sentencia
                    $sql = 'INSERT INTO nodos (id_usuario, id_padre, nombre) VALUES (:userid, :parentid, :nodename);';
                    $statement = $connection->prepare($sql);

                    // Vinculamos los campos necesarios
                    $userId = $node->getUserId();
                    $parentId = $node->getParentId();
                    $nodeName = $node->getName();

                    $statement->bindParam(':userid', $userId, PDO::PARAM_STR);
                    $statement->bindParam(':parentid', $parentId, PDO::PARAM_STR);
                    $statement->bindParam(':nodename', $nodeName, PDO::PARAM_STR);
                    $ok = $statement->execute();

                    // Recuperamos el id del nodo recién creado
                    if($ok){
                        $sql = 'SELECT LAST_INSERT_ID()';
                        $statement = $connection->prepare($sql);
                        $statement->execute();

                        $result = $statement->fetch();
                        $newNodeId = $result[0];
                    }
                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $newNodeId;
    }

    public static function nodeExists($connection, $node){
        $nodeExists = false;

        if(isset($connection)){
            try{
                if(isset($node)){
                    $nodeId = $node->getId();
                    $sql = 'SELECT * FROM nodos WHERE id = :nodeid;';
                    $statement = $connection->prepare($sql);

                    $statement->bindParam(':nodeid', $nodeId, PDO::PARAM_STR);
                    $statement->execute();
                    $result = $statement->fetchAll();

                    if(count($result)) {
                        $nodeExists = true;
                    }else{
                        $nodeExists = false;
                    }
                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $nodeExists;
    }

    public static function deleteNode($connection, $node){

        if(isset($connection)){
            try{
                if(isset($node)){
                    $nodeId = $node->getId();

                    $sql = 'DELETE FROM nodos WHERE id = :nodeid;';
                    $statement = $connection->prepare($sql);

                    $statement->bindParam(':nodeid', $nodeId, PDO::PARAM_STR);
                    $statement->execute();
                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
    }

    public static function updateNode($connection, $node){
        $ok = false;
        if(isset($connection)){
            try{
                if(isset($node)){
                    $nodeId = $node->getId();
                    $nodeName = $node->getName();
                    $parentId = $node->getParentId();

                    $sql = 'UPDATE nodos SET nombre = :nodename, id_padre = :parentid WHERE id = :nodeid';
                    $statement = $connection->prepare($sql);

                    $statement->bindParam(':nodeid', $nodeId, PDO::PARAM_STR);
                    $statement->bindParam(':nodename', $nodeName, PDO::PARAM_STR);
                    $statement->bindParam(':parentid', $parentId, PDO::PARAM_STR);
                    $statement->execute();

                    $ok = $statement->rowCount() > 0;
                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $ok;
    }
}
