<?php
require 'include/db.php';


$sql = "SELECT * FROM `contacts` ";
$query = mysql_query($sql);
while($row = mysql_fetch_assoc($query)){
	$extract = explode(',',$row['tags']);
	//var_dump($extract);
	
	foreach($extract as $var){
		echo $another_sql = "INSERT INTO `tags` (`entry_id`,`tag`) VALUES ('$row[id]','$var')";
		$another_query = mysql_query($another_sql);
	}	
}
//var_dump($extract);





?>