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
$parameter = addslashes($_POST['parameter']);
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