<?php


class UchZvan
{
    private $id;
    private $zvanie;



    public function __construct($id = 0)
    {
        if($id != 0){
            $this->getUchZvanieFromDB($id);
        }
    }

    /**
     * @return mixed
     */
    public function getZvanie()
    {
        return $this->zvanie;
    }

    /**
     * @param mixed $zvanie
     */
    public function setZvanie($zvanie)
    {
        $this->zvanie = trim(htmlspecialchars(strip_tags($zvanie)));
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



    private function getUchZvanieFromDB($id = 0){
        global $db;
        $id = intval($id);
        $row = $db->super_query("SELECT * FROM " . USERPREFIX . "_people_uzvan WHERE id = {$id}  LIMIT 1");
        if ($row) {
            $this->getUchZvanieFromRow($row);
        }
    }
    private function getUchZvanieFromRow($row = null){
        if ($row) {
            $this->setId($row[id]);
            $this->setStepen($row[zvanie]);
        }
    }
    public function getAllUchZvanie(){
        global $db;
        if($db instanceof db){
            return $db->query("SELECT id, zvanie FROM " . USERPREFIX . "_people_uzvan ORDER BY zvanie");
        }
        return false;
    }

}