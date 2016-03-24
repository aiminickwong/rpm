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
if (!empty($parameter))
    add_SQL_line("INSERT INTO config (parameter,value) VALUES ('$parameter','$value') ON DUPLICATE KEY UPDATE value='$value'");

?>