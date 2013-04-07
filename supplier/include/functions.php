<?php
require_once('db.php');
function a_zIndex(){
	$query = mysql_query("SELECT `name` FROM `contacts` ORDER BY `name` ASC");
	if(mysql_num_rows($query) > 0){
		while($row = mysql_fetch_assoc($query)){
			$sqlArray[] = $row['name'];
		}	
		foreach($sqlArray as $key => $var){
			$convertToArray = str_split($var);
			//var_dump($convertToArray);
			$letterArrayDup[] = $convertToArray[0];
		}
		$letterArray = array_unique($letterArrayDup);
		foreach($letterArray as $key1 => $var1){
			$return_array['html'][] = '<li><a href="index.php?q='.$var1.'" > '.$var1.' </a></li>';
			$return_array['letter'][] = $var1;
		}
		$collapse = implode("",$return_array['html']);
		return $collapse;
	}
	else{
		return "<li>NO DATA TO BUILD INDEX</li>";
	}
}

//Enter a new contact to the database and returns true on success and false
//on failure.  ALso if a false is returned and error message is supplied with the error.
//the format would be an foo['result'] = false and foo['message'] = some error message
function add_new_contact($array){
	foreach($array as $var){
		$var = trim($var);
	}
	
	//Compare to find entry by same name
	$input_name = mysql_real_escape_string($array['name']);
	$compare_query = mysql_query("SELECT * FROM `contacts` WHERE `name` = '$input_name'");
	if(mysql_num_rows($compare_query) > 0 ){
		return array("result" => false,"message" => "Duplicate Entry, if you have additinal changes to be made please edit the original contact");
	}	
	//Remove '-' from phone numbers
	$remove = array('-');
	$replace = array('');
	$array['pri_phone'] = str_replace($remove,$replace,$array['pri_phone']);
	$array['alt_phone'] = str_replace($remove,$replace,$array['alt_phone']);
	//remove http:// from web addresses
	$array['website'] = str_replace('http://','',$array['website']);
	$array['comments'] = str_replace("\n","<br />",$array['comments']);	
	//Split tags into array
	$tags = explode(',',$array['tags']);
	//SQL Statement
	$sql = sprintf("INSERT INTO `contacts` (`name`, `pri_phone`,`alt_phone`,`comments`,`web_address`,`street_address`,`city`,`state`,`zip_code`) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s')",mysql_real_escape_string($array['name']),mysql_real_escape_string($array['pri_phone']),mysql_real_escape_string($array['alt_phone']),mysql_real_escape_string($array['comments']),mysql_real_escape_string($array['website']),mysql_real_escape_string($array['street_address']),mysql_real_escape_string($array['city']),mysql_real_escape_string($array['state']),mysql_real_escape_string($array['zip_code']));
	
	$query = mysql_query($sql);
	$get_insert_id = mysql_insert_id();
	if(!$query){
		return array("result" => false,"message" => "MySQL Error");
	}
	else{
		$num = 0;
		while($num < count($tags)){
			mysql_query("INSERT INTO `tags` (`entry_id`, `tag` ) VALUES('$get_insert_id','$tags[$num]')");
			$num++;
		}
		return array("result" => true);
	}
}
//format a phone numner (xxx)  xxx-xxxx or similar
function phone_num_format($number){
	if(strlen($number) == 11){
		$string = str_split($number);
		return $string[0]." (".$string[1].$string[2].$string[3].") ".$string[4].$string[5].$string[6]."-".$string[7].$string[8].$string[9].$string[10];
	}
	else if(strlen($number) == 10){
		$string = str_split($number);
		return "(".$string[0].$string[1].$string[2].") ".$string[3].$string[4].$string[5]."-".$string[6].$string[7].$string[8].$string[9];
	}
	else if(strlen($number) == 7){
		$string = str_split($number);
		return $string[0].$string[1].$string[2]."-".$string[3].$string[4].$string[5].$string[6];
	}
}











?>
