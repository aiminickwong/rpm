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
$config_array = get_SQL_array("SELECT value FROM config WHERE parameter='rpm_state'");
$reply = array();
$reply['state'] = $config_array[0]['value'];
if ($reply['state'] == 1)
    $reply['status_text'] = _("Service is enabled");
else
    $reply['status_text'] = _("Service is disabled");
echo json_encode($reply);;
