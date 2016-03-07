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
$groupname = addslashes($_POST['groupname']);
if (!empty($groupname)){
    add_SQL_line("INSERT INTO groups (name) SELECT * FROM (SELECT '$groupname' AS groupname) AS tmp WHERE NOT EXISTS (SELECT name FROM groups WHERE name = '$groupname') LIMIT 1;");
//    file_put_contents("test.txt", "INSERT INTO groups (name) SELECT * FROM (SELECT '$groupname' AS groupname) AS tmp WHERE NOT EXISTS (SELECT name FROM groups WHERE name = '$groupname') LIMIT 1;\n",FILE_APPEND);
}
?>