<?php
/*
Remote Power Management
Tadas Ustinavičius

Vilnius,Lithuania.
2017-05-25
*/
include dirname(__FILE__) . '/../../functions/config.php';
require_once(dirname(__FILE__) . '/../../functions/functions.php');
if (!check_session()){
    exit;
}
$locale = addslashes($_POST['locale']);
if (!empty($locale)){
    $username=$_SESSION['username'];
    add_SQL_line("INSERT INTO userconf (username,locale) VALUES ('$username','$locale') ON DUPLICATE KEY UPDATE locale='$locale'");
}
?>