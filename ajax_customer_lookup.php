<?php
include 'include/functions.php';
//$_POST['name'] = '14999';
//var_dump($_POST);
if(isset($_POST['name'])){
	if(is_numeric($_POST['name'])){
		$sql = "SELECT * FROM `customer_list` WHERE `cust_number` = '$_POST[name]' ";
		$query = mysql_query($sql);
		if(mysql_num_rows($query) > 0){
			//echo 'hi';
			$row = mysql_fetch_row($query);
			//var_dump($row);
			echo $row[2];
		}else{
			//echo '0';
		}
	}else{
		//echo 'not numeric';
	}
}
?>