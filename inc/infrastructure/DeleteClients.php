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
$clientid = addslashes($_POST['clientid']);
if (!empty($clientid)){
    add_SQL_line("DELETE FROM clients WHERE id='$clientid' LIMIT 1;");
    add_SQL_line("DELETE FROM groupmap WHERE client='$clientid';");
}
?>