<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt

Vilnius University.
Center of Information Technology Development.


Vilnius,Lithuania.
2016-03-01
*/
include ('functions/config.php');
require_once('functions/functions.php');
if (!is_admin()){
    exit;
}
$groupid = addslashes($_POST['groupid']);
$clientlist = $_POST['clientlist'];
$clientlist=explode(",",$clientlist);
$client_count=sizeof($clientlist);
$x=0;
$clientid=addslashes($clientlist[0]);
file_put_contents("test.txt","");
file_put_contents("test.txt",$groupid,FILE_APPEND);
if ($client_count&&$groupid){
    add_SQL_line("DELETE FROM groupmap WHERE `group`='$groupid'");
}
while ($client_count>=$x){
    $clientid=addslashes($clientlist[$x]);
    if (!empty($clientid)){
	add_SQL_line("INSERT INTO groupmap (`group`, client) SELECT * FROM (SELECT '$groupid' AS groupid, '$clientid' AS clientid) AS tmp WHERE NOT EXISTS (SELECT client FROM groupmap WHERE `group` = '$groupid' AND client='$clientid') LIMIT 1;");
	}
    ++$x;
}
