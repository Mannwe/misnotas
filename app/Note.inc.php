<?php

class Note implements JsonSerializable{
    private $nodeId;
    private $id;
    private $description;
    private $text;
    private $creationDate;
    private $expanded;

    public function __construct($id, $nodeId, $description, $text, $creationDate, $expanded){
        $this->id = $id;
        $this->nodeId = $nodeId;
        $this->description = $description;
        $this->text = $text;
        $this->creationDate = $creationDate;
        $this->expanded = $expanded;
    }

    public function getId(){
        return $this->id;
    }

    public function getNodeId(){
        return $this->nodeId;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getText(){
        return $this->text;
    }

    public function getCreationDate(){
        return $this->creationDate;
    }

    public function isExpanded(){
        return $this->expanded;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setNodeId($nodeId){
        $this->userId = $nodeId;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function setText($text){
        $this->text = $text;
    }

    public function setExpanded($expanded){
        $this->expanded = $expanded;
    }

    public function jsonSerialize(){
        return 
        [   
            'id' => $this->getId(),
            'nodeId' => $this->getNodeId(),
            'description' => $this->getDescription(),
            'text' => $this->getText(),
            'creationDate' => $this->getCreationDate(),
            'expanded' => $this->isExpanded()
        ];
    }

    public static function castingFromStdClass($stdClassObject){

        $note = New Note(0,0,'','',null,false);
        foreach($stdClassObject as $property => $value) { 
            $note->$property = $value; 
        }

        return $note;
    }
}