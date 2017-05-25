<?php
include dirname(__FILE__) . '/../../functions/config.php';
require_once(dirname(__FILE__) . '/../../functions/functions.php');
if (!is_admin()){
    exit;
}
$side=addslashes($_GET['side']);
$groupid=addslashes($_GET['groupid']);
$client_array=get_SQL_array("SELECT clients.id,clients.name FROM `clients` LEFT JOIN groupmap ON clients.id=groupmap.client WHERE groupmap.group='$groupid' ORDER BY id");
if ($side=="from"){
    $client_array_full=get_SQL_array("SELECT clients.id,clients.name FROM `clients` ORDER BY id");
    if (!empty ($client_array)){
        foreach($client_array_full as $aV){
            $aTmp1[] = $aV['id'];
            $aTmp1[] = $aV['name'];
        }
        foreach($client_array as $aV){
            $aTmp2[] = $aV['id'];
            $aTmp2[] = $aV['name'];
            }
        $tmp_array = array_diff($aTmp1,$aTmp2);
        $tmp=$mode = current($tmp_array);
        $x=0;
        $client_array=array();
        while ($tmp){
            $client_array[$x]['id']=$tmp;
            $tmp = next($tmp_array);
            $client_array[$x]['name']=$tmp;
            $tmp = next($tmp_array);
            ++$x;
        }
    }
    else 
        $client_array=$client_array_full;//if no users has a group, then give out full userlist
}
$x=0;
$json_data='[';
while ($x < sizeof($client_array)){
    if ($x!=0)
            $json_data=$json_data.','; //add comma after ], unless it's a last data chunk
    $json_data=$json_data . '{"text": "' . $client_array[$x]['name'] . '",' . "\n" . '"val": "' . $client_array[$x]['id'] . '"' . "\n}"; 
    ++$x;
}
$json_data=$json_data.']';
echo $json_data;
?>