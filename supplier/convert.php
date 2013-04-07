<?php
$database = 'phonebook';			//Database Name
$db_username = 'supplier';				//Database User Name
$db_password = '24747';				//Database Password
//Connect to Database
mysql_connect('localhost',$db_username,$db_password) or die(mysql_error());
mysql_select_db($database)  or die(mysql_error());
$old_query = mysql_query("SELECT * FROM `contacts` WHERE(`tags` = 'HD')");
while($row = mysql_fetch_assoc($old_query)){
	$temp[] = $row['name'];
}
var_dump($temp);





?>