<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_strike = "mysql.webfeathers.com";
$database_strike = "strikeapp";
$username_strike = "strikeapp";
$password_strike = "P@ssw0rd";
$strike = mysql_pconnect($hostname_strike, $username_strike, $password_strike) or trigger_error(mysql_error(),E_USER_ERROR); 
?>