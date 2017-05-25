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
$groupname = addslashes($_POST['groupname']);
if (!empty($groupname)){
    add_SQL_line("INSERT INTO groups (name) SELECT * FROM (SELECT '$groupname' AS groupname) AS tmp WHERE NOT EXISTS (SELECT name FROM groups WHERE name = '$groupname') LIMIT 1;");
}
?>