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
require_once("functions/functions.php");
include ("functions/config.php");
if (!check_session()){
    exit;
}
$total_clients_reply=get_SQL_line("SELECT COUNT(*) FROM clients");
$total_groups_reply=get_SQL_line("SELECT COUNT(*) FROM groups");
$total_clients_on_reply=get_SQL_line("SELECT COUNT(*) FROM clients WHERE state=1");
$total_clients_off_reply=get_SQL_line("SELECT COUNT(*) FROM clients WHERE state=0");
echo $total_clients_reply[0] . "\n";
echo $total_groups_reply[0] . "\n";
echo $total_clients_on_reply[0] ."\n";
echo $total_clients_off_reply[0];
?>