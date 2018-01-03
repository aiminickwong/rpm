<?php
/*
Remote Power Management
Tadas Ustinavičius

Vilnius,Lithuania.
2017-05-25
*/
include dirname(__FILE__) . '/../../functions/config.php';
require_once(dirname(__FILE__) . '/../../functions/functions.php');
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
    if (!empty($clientid))
        add_SQL_line("INSERT INTO groupmap (`group`, client) SELECT * FROM (SELECT '$groupid' AS groupid, '$clientid' AS clientid) AS tmp WHERE NOT EXISTS (SELECT client FROM groupmap WHERE `group` = '$groupid' AND client='$clientid') LIMIT 1;");
    ++$x;
}
