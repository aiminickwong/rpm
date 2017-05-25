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
foreach ($_POST as $key => $value){
    $clientid=addslashes($key);
    $state=addslashes($value);
    if ($state=='on')
        $state=1;
    else
        $state=0;
    add_SQL_line("UPDATE clients SET state='$state' WHERE id='$clientid' LIMIT 1");
}
log_event("Power state changed by " . $_SESSION['fullname'], "PM_STATE_CHANGE");
?>
