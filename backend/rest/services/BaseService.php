<?php
class BaseService{
    protected $dao;
    public function __construct($dao){
        $this->dao=$dao;
    }

    public function getAll(){
        return $this->dao->getAll();
    }

     public function add($entity)
    {
        return $this->dao->add($entity);
    }
}