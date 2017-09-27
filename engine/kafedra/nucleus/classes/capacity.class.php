<?php

require_once(KC_DIR . '/directoryKafedra.class.php');
class Capacity extends DirectoryKafedra
{
    protected function getDirectoryFromDB($id = null){
        if(!is_null($id)){
            global $db;
            $row = $db->super_query("SELECT * FROM " . USERPREFIX . "_people_capacity WHERE id = {$id}  LIMIT 1");
            parent::getDirectoryFromDB($row);
        }
        return false;
    }


    protected function getAllDirectory($sql = null){
        $sql = "SELECT id, capacity as name FROM " . USERPREFIX . "_people_capacity ORDER BY capacity";
        return parent::getAllDirectory($sql);
    }
}