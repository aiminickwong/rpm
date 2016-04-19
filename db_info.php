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
require_once("functions/functions.php");
include ("functions/config.php");
if (!check_session()){
    exit;
}
$total_clients_reply=get_SQL_line("SELECT COUNT(*) FROM clients");
$total_groups_reply=get_SQL_line("SELECT COUNT(*) FROM groups");
$total_clients_on_reply=get_SQL_line("SELECT COUNT(*) FROM clients WHERE state=1");
$total_clients_off_reply=get_SQL_line("SELECT COUNT(*) FROM clients WHERE state=0");
$event_log_reply=get_SQL_array("SELECT event,type, date FROM eventlog ORDER BY id DESC LIMIT 10");
echo $total_clients_reply[0] . "\n";
echo $total_groups_reply[0] . "\n";
echo $total_clients_on_reply[0] ."\n";
echo $total_clients_off_reply[0] ."\n";
$x=0;
while($event_log_reply[$x]['event']){
    $event_class=draw_event($event_log_reply[$x]['type']);
    echo '<a href="#" class="list-group-item"><i class="' . $event_class . '"></i>' . $event_log_reply[$x]['event'] . '<span class="pull-right text-muted small"><em>' . $event_log_reply[$x]['date'] . '</em></span></a>';
    ++$x;
}
?>