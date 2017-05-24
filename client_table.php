<?php
/*
Remote Power Management
Tadas Ustinavičius

Vilnius,Lithuania.
2017-05-24
*/

require_once("functions/functions.php");
if (!check_session()){
    header ("Location: $serviceurl/?error=1");
    exit;
}
$client_array=get_SQL_array("SELECT clients.id AS id ,clients.name AS clientname,clients.state AS state,GROUP_CONCAT(groups.name) AS groups,clients.mac AS mac FROM clients LEFT JOIN groupmap ON clients.id=groupmap.client LEFT JOIN groups ON groupmap.group=groups.id GROUP BY clientname");
$x=0;
$admin = $_SESSION['admin'];
$data = array();
while (!empty($client_array[$x]['clientname'])){
    $client_array[$x]['mac'] = trim(preg_replace('/\s\s+/', ' ', $client_array[$x]['mac']));
    $cb="";
    if ($admin)
        $delete_button='<button onclick="delete_client(' . $client_array[$x]['id'] . ')" type="button" class="btn btn-danger btn-xs btn_del" value="' . $client_array[$x]['id']  . '"><i class="fa fa-times"></i></button>';
    if ($client_array[$x]['state'])
        $cb=" checked";
    $data['data'][$x] = array($client_array[$x]['clientname'], strtoupper($client_array[$x]['mac']), $client_array[$x]['groups'], '<input type="hidden" class="clientid" name="' . $client_array[$x]['id']  .  '"><input type="checkbox"' . $cb . ' class="clientid" name="' . $client_array[$x]['id']  .  '">' . $delete_button );
    ++$x;
}
echo json_encode($data);
?>