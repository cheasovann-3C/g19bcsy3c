<?php
$db_host = '127.0.0.1';   //localhost
$db_name = 'g19bcsy3c';
$db_user = 'root';
$db_pwd = '';
$db_port = '3307';

$db =new mysqli($db_host, $db_user, $db_pwd, $db_name, $db_port);

if($db->connect_errno){
    echo $db->connect_error;
    die();
}

?>