<?php
require_once('include/functions.php');
$fail_validation = false;
$value = $_POST['value'];
$field = $_POST['field'];
$id = $_POST['id'];
//Get the original data about the id submited
$get_original_sql = "SELECT * FROM `contacts` WHERE `id` = '$id' LIMIT 1";
$original_mysql = mysql_query($get_original_sql);
$original_data = mysql_fetch_assoc($original_mysql);
if($field == "pri_phone" || $field == "alt_phone"){
	$original_data[$field] = phone_num_format($original_data[$field]);
}
if($field == "web_address"){
	$original_data[$field] = 'http://'.$original_data[$field];
}
//validation Section
if($field == "name"){
	$value = trim($value);
	if(empty($value)){
		$fail_validation = true;
	}
}
if($field == "pri_phone"){
	$value = trim($value);
	$replace_array = array("-","(",")"," ");
	$value = str_replace($replace_array,"",$value);
	$phone_exp = '/^(1?(-?\d{3})-?)?(\d{3})(-?\d{4})$/i';
	if(preg_match($phone_exp,$value) == 0 || !preg_match($phone_exp,$value)){
		//Invalid Phone number
		$fail_validation = true;
	}
	if(empty($value)){
		$fail_validation = false; //This set the validation to false when the value is empty  cause empty value will trigger it to fail
	}
	if(empty($value) && empty($original_data['web_address']) && empty($original_data['alt_phone']) ){
		$fail_validation = true;
		?>
		<script type="text/javascript">
			function alertme(){
				alert("You must have at least 1 phone number OR a Web Address");
			}
			alertme();
		</script>
		<?php
	}
}
if($field == "alt_phone"){
	$value = trim($value);
	$replace_array = array("-","(",")"," ");
	$value = str_replace($replace_array,"",$value);
	$phone_exp = '/^(1?(-?\d{3})-?)?(\d{3})(-?\d{4})$/i';
	if(preg_match($phone_exp,$value) == 0 || !preg_match($phone_exp,$value)){
		//Invalid Phone number
		$fail_validation = true;
	}
	if(empty($value)){
		$fail_validation = false; //This set the validation to false when the value is empty  cause empty value will trigger it to fail
	}
	if(empty($value) && empty($original_data['pri_phone']) && empty($original_data['web_address'])){
		$fail_alidation = true;
		?>
		<script type="text/javascript">
			function alertme(){
				alert("You must have at least 1 phone number OR a Web Address");
			}
			alertme();
		</script>
		<?php
	}
}
if($field == "web_address"){
	$value = trim($value);
	$website_exp = '/(((ht|f)tp(s?):\/\/)|(www\.[^ \[\]\(\)\n\r\t]+)|(([012]?[0-9]{1,2}\.){3}[012]?[0-9]{1,2})\/)([^ \[\]\(\),;&quot;\'&lt;&gt;\n\r\t]+)([^\. \[\]\(\),;&quot;\'&lt;&gt;\n\r\t])|(([012]?[0-9]{1,2}\.){3}[012]?[0-9]{1,2})/i';
	if(preg_match($website_exp,$value) == 0 || !preg_match($website_exp,$value)){
		$fail_validation = true;
	}
	if(empty($value)){
		$fail_validation = false; //This set the validation to false when the value is empty  cause empty value will trigger it to fail
	}
	$replace_array = array('http://',' ');
	$value = str_replace($replace_array,"",$value);
	if(empty($value) && empty($original_data['pri_phone']) && empty($original_data['alt_phone'])){
		$fail_validation = true;
		?>
		<script type="text/javascript">
			function alertme(){
				alert("You must have at least 1 phone number OR a Web Address");
			}
			alertme();
		</script>
		<?php
	}
}
if($field == "comments"){
	$value = trim($value);
}
if($field == "street_address"){
	
}
if($field == "city"){

}
if($field == "state"){
	if(strlen($value) > 2){
		$fail_validation = true;
	}
	$value = strtoupper($value);
}
if($field == "zip_code"){
	$zip_code_regexp = '/^\d{5}$/i';
	if(preg_match($zip_code_regexp,$value) == 0 || !preg_match($zip_code_regexp,$value)){
		$fail_validation = true;
	}
	if(empty($value)){
		$fail_validation = false;
	}
}
if($field == "tags"){

}
//End form field validation
if(!$fail_validation){
		$value = trim($value);  //Preform one more instance of the trim function just to make sure
		$sql = "UPDATE `contacts` SET `$field` = '$value' WHERE `id` = '$id' LIMIT 1";
		$do_update = mysql_query($sql);
		$get_updated_field_sql = "SELECT `$field` FROM `contacts` WHERE `id` = '$id' LIMIT 1";
		$get_update = mysql_query($get_updated_field_sql);
		$data = mysql_fetch_assoc($get_update);
		$return_value = $data[$field];
		if($field == "pri_phone" || $field == "alt_phone"){
			$return_value = phone_num_format($data[$field]);
		}
		if($field == "web_address"){
			if(empty($data[$field])){
				$return_value = $data[$field];
			}
			else{
				$return_value = 'http://'.$data[$field];
			}
		}
}
else{
	$return_value = $original_data[$field];
}
if(!empty($return_value)){
	echo $return_value;
}
else{
	echo NULL;
}
?>






















