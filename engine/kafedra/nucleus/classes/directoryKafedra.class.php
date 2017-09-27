<?php

class DirectoryKafedra {
    private $_id;
    private $_name;

    public function __construct($id = 0)
    {
        if(is_int($id) && $id != 0){
            $this->getDirectoryFromDB($id);
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    protected function getDirectoryFromDB($row = null){
            $this->getDirectoryFromRow($row);
    }
    private function getDirectoryFromRow($row = null){
        if ($row) {
            $this->setId($row[id]);
            $this->setName($row[name]);
        }
    }

    protected function getAllDirectory($sql=null){
        global $db;
        return  $db->query($sql);
    }

    public function echoSelectList($id){
        global $db;
        $r = $this->getAllDirectory();
        $select = "<option value='0'> </option>";
        while ($row = $db->get_row($r)) {
            $select .= "<option value='{$row['id']}' ";
            if($row['id'] == $id)
                $select .= " selected";
            $select .= ">{$row['name']}</option>";
        }
        return $select;
    }

}