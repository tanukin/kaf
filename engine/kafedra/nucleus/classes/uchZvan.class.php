<?php

require_once(KC_DIR . '/directoryKafedra.class.php');
class UchZvan extends DirectoryKafedra
{
    protected function getDirectoryFromDB($id = null){
        if(!is_null($id)){
            global $db;
            $row = $db->super_query("SELECT id, zvanie as name FROM " . USERPREFIX . "_people_uzvan WHERE id = {$id}  LIMIT 1");
            parent::getDirectoryFromDB($row);
        }
        return false;
    }


    protected function getAllDirectory($sql = null){
        $sql = "SELECT id, zvanie as name FROM " . USERPREFIX . "_people_uzvan ORDER BY zvanie";
        return parent::getAllDirectory($sql);
    }
}