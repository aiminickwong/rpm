<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt
2016-04-07
Vilnius, Lithuania.
*/
function SQL_connect(){
    include ('functions/config.php');
    mysql_connect($mysql_host,$mysql_user,$mysql_pass);
    mysql_select_db($mysql_db);
    mysql_query("set names 'utf8'");
}
//##############################################################################
function add_SQL_line($sql_line){
    SQL_connect();
    mysql_query($sql_line) or die (mysql_error());
    mysql_close();
    return 0;
}
//##############################################################################
function get_SQL_line($sql_line){
    SQL_connect();
    $result = mysql_fetch_row(mysql_query($sql_line));
    mysql_close();
    return $result;
}
//##############################################################################
function get_SQL_array($sql_line){
    SQL_connect();
    $q_string = mysql_query($sql_line)or die (mysql_error());
    while ($row=mysql_fetch_array($q_string)){
                        $query_array[]=$row;
    }
    mysql_close();
    return $query_array;
}
//##############################################################################
function check_session(){
    session_start();
    if ($_SESSION['logged']='yes')
	return $_SESSION['logged'];
    else return 0;
}
//##############################################################################
function is_admin(){
    session_start();
    if ($_SESSION['admin'])
	return 1;
    else return 0;
}
//##############################################################################
function close_session(){
    session_start();
    $_SESSION['logged']="no";
//    session_unset();
}
//##############################################################################
//check list of variables for any empty value
function check_empty(){
    foreach(func_get_args() as $arg)
        if(empty($arg))
            return 1;
    return false;
}
//#############################################################################
function set_lang(){
    $username=$_SESSION['username'];
    $locale=get_SQL_line("SELECT locale FROM userconf WHERE username='$username'");
    if (!empty($locale[0])&&$locale[0]!='en_EN'){
	$language=$locale[0];
	$domain = 'RPM';
	setlocale(LC_ALL, $language.'.UTF-8');
	putenv('LC_ALL='.$language);
	bindtextdomain($domain, 'locale/');
	bind_textdomain_codeset($domain, 'UTF-8');
	textdomain($domain);
    }
}
//#############################################################################
function log_event($event,$type){
    $ip = $_SERVER['REMOTE_ADDR'];
    add_SQL_line("INSERT INTO eventlog (event,type,ip, date) VALUES ('$event','$type','$ip',NOW())");
}