<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt

Vilnius University.
Center of Information Technology Development.


Vilnius,Lithuania.
2016-03-08
*/
include ("functions/config.php");
require_once("functions/functions.php");
close_session();
header("Location: $serviceurl");
?>