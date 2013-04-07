<?php
/*
To modify account file--
export as csv, then delete all colums except
account number, account name and "cottens sm group"
save at account_list.txt (tab delimited) and then run this script and 
it should work ok
*/
mysql_connect('localhost','cottens_mysql','cottens!napa') or die(mysql_error());
$db = 'cottens_synergy';
mysql_select_db($db);

$delete_sql = 'TRUNCATE TABLE `customer_list`';
$erase_table = mysql_query($delete_sql);
if($erase_table){
	echo 'Table succesfully cleared<br />';
}
else{
	echo 'Table delete Failed<br />';
}

$data = file_get_contents('customer_db_export_2013_02_16_cleaned.txt');
$split_by_line = explode("Group",$data);
foreach($split_by_line as $key => $var){
	$split_by_part = explode("	",$var);
	$account_number = trim($split_by_part[0]);
	$account_name = trim($split_by_part[1]);
	$remove = array("'",'"');
	$clean_name = str_replace($remove,"",$account_name);
	echo $account_number.' '.$clean_name.'<br />';
	$sql = "INSERT INTO `customer_list` (`cust_number`,`cust_name`) VALUES ('$account_number','$clean_name')";
	mysql_query($sql) or die(mysql_error());
	//var_dump($split_by_part);
}
?>