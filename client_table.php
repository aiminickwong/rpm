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
if (!check_session()){
    header ("Location: $serviceurl/?error=1");
    exit;
}
$client_array=get_SQL_array("SELECT clients.id AS id ,clients.name AS clientname,clients.state AS state,GROUP_CONCAT(groups.name) AS groups,clients.mac AS mac FROM clients LEFT JOIN groupmap ON clients.id=groupmap.client LEFT JOIN groups ON groupmap.group=groups.id GROUP BY clientname");
$x=0;
$admin=$_SESSION['admin'];
$json_data='{
		  "data": [';
while (!empty($client_array[$x]['clientname'])){
    $client_array[$x]['mac'] = trim(preg_replace('/\s\s+/', ' ', $client_array[$x]['mac']));
    $cb="";
    if ($admin)
	$delete_button='<button onclick=\"delete_client(' . $client_array[$x]['id'] . ')\" type=\"button\" class=\"btn btn-danger btn-xs btn_del\" value=\"' . $client_array[$x]['id']  . '\"><i class=\"fa fa-times\"></i></button>';
    if ($client_array[$x]['state'])
	$cb=" checked";
	if ($x!=0)
	    $json_data=$json_data.','; //add comma after ], unless it's a last data chunk
	$json_data=$json_data.'
		    [
		    "' . $client_array[$x]['clientname'] . '",
		    "' . $client_array[$x]['mac'] . '",
		    "' . $client_array[$x]['groups'] . '",
		    "<input type=\"hidden\" class=\"clientid\" name=\"' . $client_array[$x]['id']  .  '\"><input type=\"checkbox\"' . $cb . ' class=\"clientid\" name=\"' . $client_array[$x]['id']  .  '\">' . $delete_button .'"
		    ]';

    ++$x;
    }
$json_data=$json_data.'	
    ]
}';

echo $json_data;
?>