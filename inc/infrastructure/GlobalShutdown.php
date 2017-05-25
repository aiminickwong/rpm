<?php
/*
Remote Power Management
Tadas Ustinavičius

Vilnius,Lithuania.
2017-05-25
*/
include dirname(__FILE__) . '/../../functions/config.php';
require_once(dirname(__FILE__) . '/../../functions/functions.php');
if (!check_session())
    exit;
if (!isset($_POST['change_to']) || !isset($_POST['shutdown_time'])){
    exit;
}
$change_to = addslashes($_POST['change_to']);
$shutdown_time = addslashes($_POST['shutdown_time']);
add_SQL_line("INSERT INTO config (parameter,value) VALUES ('global_shutdown','$change_to') ON DUPLICATE KEY UPDATE value = '$change_to'");
add_SQL_line("INSERT INTO config (parameter,value) VALUES ('global_shutdown_time','$shutdown_time') ON DUPLICATE KEY UPDATE value = '$shutdown_time'");
