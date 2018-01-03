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
$clients = explode("\n", addslashes($_POST['clients']));
$client_count=sizeof($clients);
$x=0;
while ($x<=$client_count){
    $client_data=explode(",",$clients[$x]);
    $clientname=$client_data[0];
    $clientmac=str_replace(" ","",$client_data[1]);
    if (!empty($clientname)&&!empty($clientmac))
        add_SQL_line("INSERT INTO clients (name, mac, state) SELECT * FROM (SELECT '$clientname' AS clientname, '$clientmac' AS clientmac, '0' AS pwm) AS tmp WHERE NOT EXISTS (SELECT name FROM clients WHERE name = '$clientname' OR mac='$clientmac') LIMIT 1;");
    ++$x;
}
?>