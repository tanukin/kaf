<?php


class Capacity
{
    private $id;
    private $capacity;

    public function __construct($id = 0)
    {
        if($id != 0){
            $this->getCapacityFromDB($id);
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = intval($id);
    }

    /**
     * @return mixed
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param mixed $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = trim(htmlspecialchars(strip_tags($capacity)));
    }

    private function getCapacityFromDB($id = 0){
        global $db;
        $id = intval($id);
        $row = $db->super_query("SELECT * FROM " . USERPREFIX . "_people_capacity WHERE id = {$id}  LIMIT 1");
        if ($row) {
            $this->getCapacityFromRow($row);
        }
    }
    private function getCapacityFromRow($row = null){
        if ($row) {
            $this->setId($row[id]);
            $this->setCapacity($row[capacity]);
        }
    }
    public function getAllCapacity(){
        global $db;
        if($db instanceof db){
            return $db->query( "SELECT id, capacity FROM " . USERPREFIX . "_people_capacity ORDER BY capacity");
        }
        return false;
    }




}