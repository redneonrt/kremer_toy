<?php
$database = 'supplier_connect';			//Database Name
$db_username = 'supplier_connect';				//Database User Name
$db_password = 'Logan14684';				//Database Password
//Connect to Database
mysql_connect('supplier_connect.db.4003732.hostedresource.com',$db_username,$db_password) or die(mysql_error());
mysql_select_db($database)  or die(mysql_error());
?>
