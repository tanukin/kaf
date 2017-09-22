<?php


class UchStep
{
    private $id;
    private $stepen;

    public function __construct($id = 0)
{
    if($id != 0){
        $this->getUchStepFromDB($id);
    }
}
    /**
     * @return mixed
     */
    public function getStepen()
    {
        return $this->stepen;
    }

    /**
     * @param mixed $stepen
     */
    public function setStepen($stepen)
    {
        $this->stepen = trim(htmlspecialchars(strip_tags($stepen)));
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



    private function getUchStepFromDB($id = 0){
        global $db;
        $id = intval($id);
        $row = $db->super_query("SELECT * FROM " . USERPREFIX . "_people_ustep WHERE id = {$id}  LIMIT 1");
        if ($row) {
            $this->getUchStepFromRow($row);
        }
    }
    private function getUchStepFromRow($row = null){
        if ($row) {
            $this->setId($row[id]);
            $this->setStepen($row[stepen]);
        }
    }
    public function getAllUchStep(){
        global $db;
        if($db instanceof db){
            return $db->query( "SELECT id, stepen FROM " . USERPREFIX . "_people_ustep ORDER BY stepen");
        }
        return false;
    }

}