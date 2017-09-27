<?php

require_once(KC_DIR . '/directoryKafedra.class.php');

class UchStep extends DirectoryKafedra{

    protected function getDirectoryFromDB($id = null){
       if(!is_null($id)){
           global $db;
           $row = $db->super_query("SELECT id, stepen as name FROM " . USERPREFIX . "_people_ustep WHERE id = {$id}  LIMIT 1");
           parent::getDirectoryFromDB($row);
       }
       return false;
    }


    protected function getAllDirectory($sql = null){
        $sql = "SELECT id, stepen as name FROM " . USERPREFIX . "_people_ustep ORDER BY stepen";
        return parent::getAllDirectory($sql);
    }
}