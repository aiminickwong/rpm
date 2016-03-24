<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt

Vilnius University.
Center of Information Technology Development.


Vilnius,Lithuania.
2016-03-24
*/
require_once('functions/functions.php');
include('functions/config.php');
$username=addslashes($_POST['username']);
$password=addslashes($_POST['password']);
if (empty($username)||empty($password)){
                 header ("Location: index.php?error=1");
                 exit;
}
$ip = $_SERVER['REMOTE_ADDR'];
$data = date("Y.m.d H:i:s");

$sql_reply=get_SQL_line("SELECT id,password,admin FROM users WHERE username LIKE '$username'");
if(!empty($sql_reply[0])){
    if (hash_equals($sql_reply[1], crypt($password, $sql_reply[1]))) {
        session_set_cookie_params(1200000); 
        session_start();
        $_SESSION['logged']='yes';
        $_SESSION['id']=$sql_reply[0]['id'];
        $_SESSION['admin']=$sql_reply[0]['admin'];
	$_SESSION['ad_user']='no';
	$_SESSION['username']=$username;
        $ip = $_SERVER['REMOTE_ADDR'];
        $tmpid=$sql_reply[0]['id'];
        $data = date("Y.m.d H:i:s");
	add_SQL_line("UPDATE users SET lastlogin='$data', ip='$ip' WHERE id='$tmpid'");
	log_event("User $username login success", "LOGIN_SUCCESS");
        header ("Location: dashboard.php");
        exit;
	}

    }
if($ad_enable){
    session_set_cookie_params(1200000);
    session_start();
    $_SESSION['logged']='';
    $_SESSION['admin']='0';
    $query_user =$username."@".$ad_name;
    $ldap_login_err=0;
    $ldap = ldap_connect($ad_host) or $ldap_login_err=1;
    ldap_bind($ldap,$query_user,$password) or  $ldap_login_err=1;
    if ($ldap_login_err){
	log_event("User $username login failure", "LOGIN_FAILURE");
        header ("Location: index.php?error=1");
        exit;
    }
    $results = ldap_search($ldap,$ldap_dn,"(samaccountname=$username)",array("memberof","primarygroupid","displayname"));
    $entries = ldap_get_entries($ldap, $results);
    if($entries['count'] == 0) {
	log_event("User $username login failure", "LOGIN_FAILURE");
        header ("Location: index.php?error=1");
        exit;
    }
    $output = $entries[0]['memberof'];
    $token = $entries[0]['primarygroupid'][0];
    $fullname= $entries[0]['displayname'][0];
    array_shift($output);
    $results2 = ldap_search($ldap,$ldap_dn,"(objectcategory=group)",array("distinguishedname","primarygrouptoken"));
    $entries2 = ldap_get_entries($ldap, $results2);
    array_shift($entries2);
    foreach($entries2 as $e) {
        if($e['primarygrouptoken'][0] == $token) {
    	    $output[] = $e['distinguishedname'][0];
    	    break;
	}
    }
    $allowed=0;
    foreach ($output as &$value) {
        if (strpos($value, $rpm_admin_group)) {$_SESSION['admin']=1;$allowed=1;}
        if (strpos($value, $rpm_user_group)) {$_SESSION['admin']=0;$allowed=1;}
    }
    $_SESSION['fullname']=$fullname;
    if ($allowed){
        $_SESSION['logged']='yes';
	$_SESSION['ad_user']='yes';
	$_SESSION['username']=$username;
    	$fullname= iconv ("CP1257","UTF-8", $fullname);
        log_event("User $fullname login success", "LOGIN_SUCCESS");
        header ("Location: dashboard.php");
        exit;
    }
}

log_event("User $username login failure", "LOGIN_FAILURE");
header ("Location: index.php?error=1");
exit;
?>