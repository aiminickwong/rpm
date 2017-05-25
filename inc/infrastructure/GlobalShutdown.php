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
$change_to = addslashes($_POST['change_to']);
$shutdown_time = addslashes($_POST['shutdown_time']);
if (empty($change_to) || empty($shutdown_time))
    exit;
if ($_POST['value']==1)
    $value=0;
else
    $value=1;
if (!empty($parameter)){
    add_SQL_line("INSERT INTO config (parameter,value) VALUES ('$parameter','$value') ON DUPLICATE KEY UPDATE value='$value'");
    if ($value==1)
        log_event("User " . $_SESSION['fullname'] . " has enabled RPM", "RPM_STATE_ENABLED");
    if ($value==0)
        log_event("User " . $_SESSION['fullname'] . " has disabled RPM", "RPM_STATE_DISABLED");
}
?>