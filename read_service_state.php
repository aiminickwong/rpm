<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt

Vilnius University.
Center of Information Technology Development.


Vilnius,Lithuania.
2016-03-24
*/
require_once("functions/functions.php");
if (!check_session()){
    exit;
}
$config_array=get_SQL_array("SELECT value FROM config WHERE parameter='rpm_state'");
echo $config_array[0]['value'];
