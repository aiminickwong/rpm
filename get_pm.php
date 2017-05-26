<?php
/*
Remote Power Management
Tadas Ustinavičius

Vilnius,Lithuania.
2017-05-25
*/
require_once("functions/functions.php");
$config_array=get_SQL_array("SELECT value FROM config WHERE parameter='rpm_state'");
$mac = '';
$type = '';
if ($config_array[0]['value'] != 1)
    exit;
if (isset($_GET['mac']) && isset($_GET['type']))
    exit;
if (isset($_GET['mac']))
    $mac = $_GET['mac'];
if (isset($_GET['type']))
    $type = $_GET['type'];
$shutdown_info = get_SQL_array("SELECT * FROM config WHERE parameter = 'global_shutdown' OR parameter = 'global_shutdown_time'");
$shutdown_array = array();
$machines_array = array();
$x = 0;
while ($x < sizeof($shutdown_info)){
    if ($shutdown_info[$x]['parameter'] == 'global_shutdown')
        $shutdown_array['global_shutdown'] = $shutdown_info[$x]['value'];
    if ($shutdown_info[$x]['parameter'] == 'global_shutdown_time')
        $shutdown_array['global_shutdown_time'] = $shutdown_info[$x]['value'];
    ++$x;
}
if ($shutdown_array['global_shutdown'] == 1){
    $curr_time = date('H:i', strtotime("now"));
    $shutdown_time = date('H:i', strtotime($shutdown_array['global_shutdown_time']));
}
if ($type == "show_power_on"){
    if ($shutdown_array['global_shutdown'] == 1){
        if ($curr_time == $shutdown_time){
            add_SQL_line("UPDATE clients SET state = 0");
            exit;
        }
    }
    $machines_array = get_SQL_array("SELECT mac FROM clients WHERE state = 1");
    $x=0;
    while (!empty($machines_array[$x]['mac'])){
        echo $machines_array[$x]['mac']."\n";
        ++$x;
    }
    exit;
}
$SQL_query="(mac='";
$x = 0;
while(!empty($mac[$x])){
    if ($x)
        $SQL_query=$SQL_query." OR mac='";
    $SQL_query=$SQL_query.addslashes(strtoupper($mac[$x]))."'";
    ++$x;
}
if ($shutdown_array['global_shutdown'] == 1){
    if ($curr_time == $shutdown_time){
        add_SQL_line("UPDATE clients SET state = 0 WHERE " . $SQL_query . ")");
    }
}
$SQL_query = "SELECT COUNT(*) FROM clients WHERE " . $SQL_query . ") AND state=0";
$pm_result = get_SQL_line($SQL_query);
if ($pm_result[0] == 1)
    echo "POWER_OFF";
else
    echo "POWER_ON";
