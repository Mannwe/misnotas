<?php

class Node implements JsonSerializable{
    private $userId;
    private $id;
    private $name;
    private $parentId;
    private $hasChildren;
    private $expanded;
    private $selected;
    // Posiciones para el drag and drop
    private $top;
    private $left;

    public function __construct($userId, $id, $parentId, $name, $hasChildren, $expanded, $selected, $top, $left){
        $this->userId = $userId;
        $this->id = $id;
        $this->name = $name;
        $this->parentId = $parentId;
        $this->hasChildren = $hasChildren;
        $this->expanded = $expanded;
        $this->selected = $selected;
        $this->top = $top;
        $this->left = $left;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function getId(){
        return $this->id;
    }

    public function getParentId(){
        return $this->parentId;
    }

    public function getName(){
        return $this->name;
    }

    public function hasChildren(){
        return $this->hasChildren;
    }

    public function isExpanded(){
        return $this->expanded;
    }

    public function isSelected(){
        return $this->selected;
    }

    public function getTop(){
        return $this->top;
    }

    public function getLeft(){
        return $this->left;
    }

    public function setUserId($userId){
        $this->userId = $userId;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setParentId($parentId){
        $this->parentId = $parentId;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setHasChildren($hasChildren){
        $this->hasChildren = $hasChildren;
    }

    public function setExpanded($expanded){
        $this->expanded = $expanded;
    }

    public function setSelected($selected){
        $this->selected = $selected;
    }

    public function jsonSerialize(){
        return 
        [   
            'userId' => $this->getUserId(),
            'id' => $this->getId(),
            'parentId'  => $this->getParentId(),
            'name' => $this->getName(),
            'hasChildren'  => $this->hasChildren(),
            'expanded'  => $this->isExpanded(),
            'selected'  => $this->isSelected(),
            'top'  => $this->getTop(),
            'left'  => $this->getLeft()
        ];
    }

    public static function castingFromStdClass($stdClassObject){

        $node = New Node(0,0,'','',false,false,false,0,0);
        foreach($stdClassObject as $property => $value) { 
            $node->$property = $value; 
        }

        return $node;
    }
}