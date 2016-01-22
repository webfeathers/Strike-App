<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_StrikeBrewingCo = "localhost";
$database_StrikeBrewingCo = "strike13_kegtracker";
$username_StrikeBrewingCo = "strike13_kegs";
$password_StrikeBrewingCo = "Str1k3";
$StrikeBrewingCo = mysql_pconnect($hostname_StrikeBrewingCo, $username_StrikeBrewingCo, $password_StrikeBrewingCo) or trigger_error(mysql_error(),E_USER_ERROR); 
?>