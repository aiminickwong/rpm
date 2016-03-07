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
include ('functions/config.php');
require_once('functions/functions.php');
if (!check_session()){
    exit;
}
$locale = addslashes($_POST['locale']);
if (!empty($locale)){
    $username=$_SESSION['username'];
    add_SQL_line("INSERT INTO userconf (username,locale) VALUES ('$username','$locale') ON DUPLICATE KEY UPDATE locale='$locale'");
//    file_put_contents("test.txt", "INSERT INTO userconf (username,locale) VALUES ('$username','$locale') ON DUPLICATE KEY UPDATE locale=VALUES('$locale')\n",FILE_APPEND);
}
?>