<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt

Vilnius University.
Center of Information Technology Development.


Vilnius,Lithuania.
2016-04-19
*/
include ('functions/config.php');
require_once('functions/functions.php');
if (!check_session()){
    exit;
}
foreach ($_POST as $key => $value){
    $clientid=addslashes($key);
    $state=addslashes($value);
    if ($state=='on')
	$state=1;
    else
	$state=0;
    add_SQL_line("UPDATE clients SET state='$state' WHERE id='$clientid' LIMIT 1");
//    file_put_contents("test.txt", "UPDATE clients SET state='$state' WHERE id='$clientid' LIMIT 1 \n",FILE_APPEND);

}
log_event("Power state changed by " . $_SESSION['fullname'], "PM_STATE_CHANGE");
?>
