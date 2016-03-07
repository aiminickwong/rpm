<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt

Vilnius University.
Center of Information Technology Development.


Vilnius,Lithuania.
2016-03-04
*/
require_once("functions/functions.php");
$mac=$_GET['mac'];

$type=$_GET['type'];

if (empty($mac)&&empty($type))
    exit;
$x=0;
if ($type=="show_power_on"){
    $machines_array=get_SQL_array("SELECT mac FROM clients WHERE state=1");
    while (!empty($machines_array[$x]['mac'])){
	echo $machines_array[$x]['mac']."\n";
	++$x;
    }
    exit;
}
$SQL_query="SELECT COUNT(*) FROM clients WHERE (mac='";
while(!empty($mac[$x])){
    if ($x)
	$SQL_query=$SQL_query." OR mac='";
    $SQL_query=$SQL_query.addslashes(strtoupper($mac[$x]))."'";
    ++$x;
}
$SQL_query=$SQL_query . ") AND state=0";
$pm_result=get_SQL_line($SQL_query);
if ($pm_result[0]==1)
    echo "POWER_OFF";
else 
    echo "POWER_ON"
?>