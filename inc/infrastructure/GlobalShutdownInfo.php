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
$shutdown_info = get_SQL_array("SELECT * FROM config WHERE parameter = 'global_shutdown' OR parameter = 'global_shutdown_time'");
$shutdown_array = array();
if (empty($shutdown_info)){
    $shutdown_array['global_shutdown'] = 0;
    $shutdown_array['global_shutdown_time'] = '23:59';
}
else {
    $x=0;
    while ($x < sizeof($shutdown_info)){
        if ($shutdown_info[$x]['parameter'] == 'global_shutdown')
            $shutdown_array['global_shutdown'] = $shutdown_info[$x]['value'];
        if ($shutdown_info[$x]['parameter'] == 'global_shutdown_time')
            $shutdown_array['global_shutdown_time'] = $shutdown_info[$x]['value'];
        ++$x;
    }

}
echo json_encode($shutdown_array);