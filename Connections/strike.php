<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_strike = "URL_GOES_HERE";
$database_strike = "DB_NAME";
$username_strike = "DB_USER";
$password_strike = "DB_PASSWORD";
$strike = mysql_pconnect($hostname_strike, $username_strike, $password_strike) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
