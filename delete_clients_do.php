<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt

Vilnius University.
Center of Information Technology Development.


Vilnius,Lithuania.
2016-03-03
*/
include ('functions/config.php');
require_once('functions/functions.php');
if (!is_admin()){
    exit;
}
$clientid = addslashes($_POST['clientid']);
if (!empty($clientid)){
    add_SQL_line("DELETE FROM clients WHERE id='$clientid' LIMIT 1;");
    add_SQL_line("DELETE FROM groupmap WHERE client='$clientid';");
    //file_put_contents("test.txt", "INSERT INTO groups (name) SELECT * FROM (SELECT '$groupname') AS tmp WHERE NOT EXISTS (SELECT name FROM groups WHERE name = '$groupname') LIMIT 1;\n",FILE_APPEND);
}
?>