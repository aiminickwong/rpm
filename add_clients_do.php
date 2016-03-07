<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt

Vilnius University.
Center of Information Technology Development.


Vilnius,Lithuania.
2016-03-01
*/
include ('functions/config.php');
require_once('functions/functions.php');
if (!is_admin()){
    exit;
}
$clients = explode("\n", addslashes($_POST['clients']));
$client_count=sizeof($clients);
$x=0;
while ($x<=$client_count){
    $client_data=explode(",",$clients[$x]);
    $clientname=$client_data[0];
    $clientmac=$client_data[1];
    if (!empty($clientname)&&!empty($clientmac)){
	add_SQL_line("INSERT INTO clients (name, mac, state) SELECT * FROM (SELECT '$clientname' AS clientname, '$clientmac' AS clientmac, '0' AS pwm) AS tmp WHERE NOT EXISTS (SELECT name FROM clients WHERE name = '$clientname' OR mac='$clientmac') LIMIT 1;");
//	file_put_contents("test.txt", "INSERT INTO clients (name, mac, state) SELECT * FROM (SELECT '$clientname', '$clientmac', '0') AS tmp WHERE NOT EXISTS (SELECT name FROM clients WHERE name = '$clientname' OR mac='$clientmac') LIMIT 1;\n",FILE_APPEND);
    }
    ++$x;
}
?>