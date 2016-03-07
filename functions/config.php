<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt

Vilnius University.
Center of Information Technology Development.


Vilnius,Lithuania.
2016-03-01
*/
###################Dashboard config#########################
$serviceurl='https://somehost.tld/rpm';
############################################################

####################Active Directory logins#################
$ad_enable=1;
$ad_host="somehost.domain.tld";
$ad_name="domain.tld";
$ldap_dn = "ou=People,DC=domain,DC=tld";
$rpm_admin_group="rpm_admins";
$rpm_user_group="rpm_users";
####################Database config#########################
$mysql_host='localhost';
$mysql_db='rpm';
$mysql_user='rpm';
$mysql_pass='';
############################################################
