<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt
2016-05-11
Vilnius, Lithuania.
*/
function SQL_connect(){
    include (dirname(__FILE__).'/config.php');
    $mysql_connection=mysqli_connect($mysql_host,$mysql_user,$mysql_pass);
    mysqli_select_db($mysql_connection, $mysql_db);
    return $mysql_connection;
}
function add_SQL_line($sql_line){
    $mysql_connection=SQL_connect();
    mysqli_query($mysql_connection, $sql_line) or die (mysqli_error($mysql_connection));
    mysqli_close();
    return 0;
}
//##############################################################################
function get_SQL_line($sql_line){
    $mysql_connection=SQL_connect();
    $result = mysqli_fetch_row(mysqli_query($mysql_connection, $sql_line));
    mysqli_close();
    return $result;
}
//##############################################################################
function get_SQL_array($sql_line){
    $mysql_connection=SQL_connect();
    $q_string = mysqli_query($mysql_connection, $sql_line)or die (mysqli_error($mysql_connection));
    while ($row=mysqli_fetch_array($q_string)){
        $query_array[]=$row;
    }
    mysqli_close();
    return $query_array;
}
//##############################################################################
function check_session(){
    session_start();
    if ($_SESSION['logged'])
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
    session_unset();
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
//############################################################################
function draw_event($event){
    $event=str_replace("LOGIN_SUCCESS", "fa fa-user fa-fw text-success",$event);
    $event=str_replace("LOGIN_FAILURE", "fa fa-user fa-fw text-danger",$event);
    $event=str_replace("RPM_STATE_ENABLED", "fa fa-check-square-o fa-fw text-info",$event);
    $event=str_replace("RPM_STATE_DISABLED", "fa fa-square-o fa-fw text-info",$event);
    $event=str_replace("PM_STATE_CHANGE", "fa fa-power-off fa-fw text-info",$event);
    return $event;
}
//############################################################################
function check_db(){
    return sizeof(get_SQL_array("SHOW TABLES LIKE 'users'"));
}
//############################################################################
function populate_db(){
    $mysql_connection=SQL_connect();
    $sql_file=file_get_contents(dirname(__FILE__) . '/../sql/rpm.sql');
    $lines=explode(';', $sql_file);
    $failure=0;
    foreach($lines as $line) {
        $result=mysqli_query($mysql_connection,$line);
        if (!$result)
            $failure=1;
    }
    return $failure;
}