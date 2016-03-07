<?php
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
        $_SESSION['id']=$sqlpw[0]['id'];
        $_SESSION['admin']=$sqlpw[0]['admin'];
	$_SESSION['ad_user']='no';
	$_SESSION['username']=$username;
        $ip = $_SERVER['REMOTE_ADDR'];
        $tmpid=$sqlpw[0]['id'];
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
    // Active Directory DN, base path for our querying user                                                                                                                                                                      
    $query_user =$username."@".$ad_name;                                                                                                                                                                                       
    // Connect to AD                                                                                                                                                                                                             
    $ldap_login_err=0;                                                                                                                                                                                                           
    $ldap = ldap_connect($ad_host) or $ldap_login_err=1;                                                                                                                                                                       
    ldap_bind($ldap,$query_user,$password) or  $ldap_login_err=1;                                                                                                                                                                                                              
    if ($ldap_login_err){                                                                                                                                                                                                                                                      
        //echo "$username,$ip,$data,failure";              
	log_event("User $username login failure", "LOGIN_FAILURE");                                                                                                                                                                                                              
        header ("Location: index.php?error=1");
        exit;                                                                                                                                                                                                                                                                  
    }                                                                                                                                                                                                                                                                          
    // Active Directory server                                                                                                                                                                                                                                                 
    // Search AD                                                                                                                                                                                                                                                               
    $results = ldap_search($ldap,$ldap_dn,"(samaccountname=$username)",array("memberof","primarygroupid","displayname"));                                                                                                                                                          
    $entries = ldap_get_entries($ldap, $results);
    // No information found, bad user                                                                                                                                                                                                                                          
    if($entries['count'] == 0) {                                                                                                                                                                                                                                               
        //  echo "$username,$ip,$data,failure1"; 
	log_event("User $username login failure", "LOGIN_FAILURE");
        header ("Location: index.php?error=1");                                                                                                                                                                                                                                
        exit;                                                                                                                                                                                                                                                                  
    }
    // Get groups and primary group token                                                                                                                                                                                                                                      
    $output = $entries[0]['memberof'];                                                                                                                                                                                                                                         
    $token = $entries[0]['primarygroupid'][0];
    $fullname= $entries[0]['displayname'][0];
    // Remove extraneous first entry
    array_shift($output);
    // We need to look up the primary group, get list of all groups
    $results2 = ldap_search($ldap,$ldap_dn,"(objectcategory=group)",array("distinguishedname","primarygrouptoken"));
    $entries2 = ldap_get_entries($ldap, $results2);
    // Remove extraneous first entry
    array_shift($entries2);
    // Loop through and find group with a matching primary group token
    foreach($entries2 as $e) {
        if($e['primarygrouptoken'][0] == $token) {
    	    // Primary group found, add it to output array
    	    $output[] = $e['distinguishedname'][0];
    	    // Break loop
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
//	echo mb_detect_encoding($fullname, "auto").$fullname;
        header ("Location: dashboard.php");
        exit;
    }
}
log_event("User $username login failure", "LOGIN_FAILURE");
header ("Location: index.php?error=1");
exit;

?>