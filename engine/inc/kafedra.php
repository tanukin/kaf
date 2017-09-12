<?php
if(!defined('DATALIFEENGINE')){
    echo '[Datalife Engine Kafedra] You tried to gain unauthorized access to the module, or system DataLife Engine is not licensed.';
    exit();
}

if($config['version_id']==12) {
    require_once 'kafedra/kafedra_12.php';
}else{
    echo '[Datalife Engine Kafedra] You are using a kernel version that does not conform to the version of the module.';
    exit();
}
?>