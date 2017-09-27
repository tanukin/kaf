<?php

if(!defined('DATALIFEENGINE'))
{
    die("Hacking attempt!");
}

class Kafedra{

    private $id;
    private $name;
    private $alt_name;
    private $since;
    private $address;
    private $phone;
    private $email;
    private $count_people;
    private $zav_id;
    private $updater_id;
    private $info;
    private $history;
    private $aspir;
    private $edu;
    private $prof;
    private $filials;
    private $science;
    private $more_cat_kafedra;
    private $site;
    private $lastUpdate;
    private $visible;
    private $dle_id_user;

    /**
     * @return mixed
     */
    public function getDleIdUser()
    {
        return $this->dle_id_user;
    }

    /**
     * @param mixed $dle_id_user
     */
    public function setDleIdUser($dle_id_user)
    {
        $this->dle_id_user = $dle_id_user;
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
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAltName()
    {
        return $this->alt_name;
    }

    /**
     * @param mixed $alt_name
     */
    public function setAltName($alt_name)
    {
        $this->alt_name = $alt_name;
    }

    /**
     * @return mixed
     */
    public function getSince()
    {
        return $this->since;
    }

    /**
     * @param mixed $since
     */
    public function setSince($since)
    {
        $this->since = $since;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getCountPeople()
    {
        return $this->count_people;
    }

    /**
     * @param mixed $count_people
     */
    public function setCountPeople($count_people)
    {
        $this->count_people = $count_people;
    }

    /**
     * @return mixed
     */
    public function getZavId()
    {
        return $this->zav_id;
    }

    /**
     * @param mixed $zav_id
     */
    public function setZavId($zav_id)
    {
        $this->zav_id = $zav_id;
    }

    /**
     * @return mixed
     */
    public function getUpdaterId()
    {
        return $this->updater_id;
    }

    /**
     * @param mixed $updater_id
     */
    public function setUpdaterId($updater_id)
    {
        $this->updater_id = $updater_id;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @return mixed
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @param mixed $history
     */
    public function setHistory($history)
    {
        $this->history = $history;
    }

    /**
     * @return mixed
     */
    public function getAspir()
    {
        return $this->aspir;
    }

    /**
     * @param mixed $aspir
     */
    public function setAspir($aspir)
    {
        $this->aspir = $aspir;
    }

    /**
     * @return mixed
     */
    public function getEdu()
    {
        return $this->edu;
    }

    /**
     * @param mixed $edu
     */
    public function setEdu($edu)
    {
        $this->edu = $edu;
    }

    /**
     * @return mixed
     */
    public function getProf()
    {
        return $this->prof;
    }

    /**
     * @param mixed $prof
     */
    public function setProf($prof)
    {
        $this->prof = $prof;
    }

    /**
     * @return mixed
     */
    public function getFilials()
    {
        return $this->filials;
    }

    /**
     * @param mixed $filials
     */
    public function setFilials($filials)
    {
        $this->filials = $filials;
    }

    /**
     * @return mixed
     */
    public function getScience()
    {
        return $this->science;
    }

    /**
     * @param mixed $science
     */
    public function setScience($science)
    {
        $this->science = $science;
    }

    /**
     * @return mixed
     */
    public function getMoreCatKafedra()
    {
        //строка вида 10:15:75:33
        //распарсить и отдать массив

        return $this->more_cat_kafedra;
    }

    /**
     * @param mixed $more_cat_kafedra
     */
    public function setMoreCatKafedra($more_cat_kafedra)
    {
        //получен массив
        //перед записью привести к строке вида 10:15:75:33
        $this->more_cat_kafedra = $more_cat_kafedra;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @param mixed $lastUpdate
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }
    public function getKafedraFromArray($array){
        $this->setId($array['id']);
        $this->setName($array['name']);
        $this->setAltName($array['alt_name']);
        $this->setSince($array['since']);
        $this->setAddress($array['address']);
        $this->setPhone($array['phone']);
        $this->setEmail($array['email']);
        $this->setZavId($array['zav_id']);
        $this->setUpdaterId($array['updater_id']);
        $this->setInfo($array['info']);
        $this->setHistory($array['history']);
        $this->setAspir($array['aspir']);
        $this->setEdu($array['edu']);
        $this->setProf($array['prof']);
        $this->setFilials($array['filials']);
        $this->setScience($array['science']);
        $this->setSite($array['site']);
        $this->setLastUpdate($array['lastUpdate']);
        $this->setMoreCatKafedra($array['more_cat_kafedra']);
        $this->setVisible($array['visible']);
    }
    public function getKafedraFromDB($id){
        global $db;
        if (!isset($id)) {
            return FALSE;
        }
        $id = intval($id);
        $row = $db->super_query("SELECT * FROM " . USERPREFIX . "_kafedra WHERE id = {$id}");
        if ($row){
            $this->setId($row['id']);
            $this->setName($row['name']);
            $this->setAltName($row['alt_name']);
            $this->setSince($row['since']);
            $this->setAddress($row['address']);
            $this->setPhone($row['phone']);
            $this->setEmail($row['email']);
            $this->setZavId($row['zav_id']);
            $this->setUpdaterId($row['updater_id']);
            $this->setInfo($row['info']);
            $this->setHistory($row['history']);
            $this->setAspir($row['aspir']);
            $this->setEdu($row['edu']);
            $this->setProf($row['prof']);
            $this->setFilials($row['filials']);
            $this->setScience($row['science']);
            $this->setSite($row['site']);
            $this->setLastUpdate($row['lastUpdate']);
            $this->setMoreCatKafedra($row['more_cat_kafedra']);
            $this->setVisible($row['visible']);
        }
    }

    public function getAllKafedraFromDB(){
        global $db;
        if($db instanceof db){
            return $db->query( "SELECT id, name FROM " . USERPREFIX . "_kafedra ORDER BY name");
        }
        return false;
    }

    public function echoSelectList($id){
        global $db;
        $r = $this->getAllKafedraFromDB();
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
