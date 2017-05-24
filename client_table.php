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
$client_array = get_SQL_array("SELECT clients.id AS id ,clients.name AS clientname,clients.state AS state,GROUP_CONCAT(groups.name) AS groups,clients.mac AS mac FROM clients LEFT JOIN groupmap ON clients.id=groupmap.client LEFT JOIN groups ON groupmap.group=groups.id GROUP BY clientname");
$x = 0;
$admin = $_SESSION['admin'];
$data = array();
while (!empty($client_array[$x]['clientname'])){
    $client_array[$x]['mac'] = trim(preg_replace('/\s\s+/', ' ', $client_array[$x]['mac']));
    $cb="";
    if ($admin)
$delete_button = '<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-fw fa-cog"></i><span class="caret"></span></button>
    <ul class="dropdown-menu" role="menu">
      <li><a href="#" onclick="delete_client(' . $client_array[$x]['id'] . ')"><i class="fa fa-fw fa-trash"></i>' . _("Delete") .  '</a></li>
    </ul>
  </div>
';
    if ($client_array[$x]['state'])
        $cb=" checked";
    $data['data'][$x] = array($client_array[$x]['clientname'], strtoupper($client_array[$x]['mac']), $client_array[$x]['groups'], '<div class="row"><div class="col-md-6 text-right"><input type="hidden" class="clientid" name="' . $client_array[$x]['id'] . '"><input type="checkbox"' . $cb . ' class="clientid" name="' . $client_array[$x]['id']  .  '"></div><div class="col-md-6 text-left">' . $delete_button . '</div></div>');
    ++$x;
}
echo json_encode($data);
?>