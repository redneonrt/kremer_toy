<?php
class database {
	var $connection = 'localhost';
	var $db_name = 'cottens_synergy';
	var $db_user_name = 'cottens_mysql';
	var $db_pass_word = 'cottens!napa';
	var $user_table = 'users'; //Table where user  data is stored
	
/*	var $connection = 'cottens_synergy.db.4597965.hostedresource.com';
	var $db_name = 'cottens_synergy';
	var $db_user_name = 'cottens_synergy';
	var $db_pass_word = 'Cottens!733';
	var $user_table = 'users'; //Table where user  data is stored
	
*/	
	
	//PHP5+ function to autostart everytime class is called
	function __construct(){
		$connect_to = mysql_connect($this->connection,$this->db_user_name,$this->db_pass_word);
		$select_me = mysql_select_db($this->db_name);
	}
	
//-------------------------Generic Functions--------------------------------------------------------------------------//	

	//Select a row and return an array with all the data
	function select_row($username){
		$sql = 'SELECT * FROM `'.$this->db_name.'`.`'.$this->user_table.'` WHERE `username` = \''.$username.'\' LIMIT 1';
		$query = mysql_query($sql);
		if($query){
			$data = mysql_fetch_assoc($query);
			return array('result' => true,'data' => $data);
		}
		else{
			return array('result' => false, 'reason' => 'FAILURE on function SELECT_ROW(class.db.php)');
		}		
	}

	//Insert entry into db 
	//input is an $array['field]-[0][1]ect and $array['value']-[0][1] ect and $table is the table to insert into 
	function db_insert($table, $array){
		$statement[] = 'INSERT INTO `'.$this->db_name.'`.`'.$table.'` (';
		foreach($array['field'] as $field){
			$fields[] = '`'.$field.'`';
		}
		$field_join = implode(',',$fields);
		$statement[] = $field_join;
		$statement[] = ') VALUES (';
		foreach($array['value'] as $value){
			$values[] = '\''.$value.'\'';
		}
		$value_join = implode(',',$values);
		$statement[] = $value_join;
		
		$statement[] = ');';
		$join_statement = implode(' ',$statement);
		return $result = mysql_query($join_statement);
	}
	
	//Update Entry--------------------------------------------------------	
	function db_update($table,$field,$new_value,$row,$row_value,$limit){
		mysql_real_escape_string($new_value);
		$statement = 'UPDATE `'.$this->db_name.'`.`'.$table.'` SET `'.$field.'` = \''.$new_value.'\' WHERE `'.$table.'`.`'.$row.'` = '.$row_value.' LIMIT '.$limit.'';
		return $result = $query = mysql_query($statement);
	}
	//Remove Entry--------------------------------------------------------
	function db_delete($table,$row,$match,$limit){
		$statement = 'DELETE FROM `'.$this->db_name.'`.`'.$table.'` WHERE `'.$table.'`.`'.$row.'` = '.$match.' LIMIT '.$limit.'';
		return $result = $query = mysql_query($statement);
	}
	//check for duplicate returns true if match is found and false otherwise
	function db_check_duplicate($table,$row,$row_match){
		$statement = 'SELECT * FROM `'.$this->db_name.'`.`'.$table.'` WHERE `'.$row.'` = \''.$row_match.'\' ';
		$query = mysql_query($statement);
		$num_of_matches = mysql_num_rows($query);
		if($num_of_matches > 0){
			return true;
		}
		else{
			return false;
		}		
	}
//-------------------Application Specific Functions----------------------------------------------------------------//

	//Create a new user , $array is assumed to be from $_POST and a variable for user level
	function create_new_user($array){
		//var_dump($array);
		//$error_array;
		foreach($array as $var){
			mysql_real_escape_string($var);
		}
		$fields = array('username','password');
			
		$values = array('username' => $array['username'],
						'password' => sha1($array['password'])
						);
		$table = 'users';
		$data['field'] = $fields;
		$data['value'] = $values;
		$insert = $this->db_insert($table,$data);
		if($insert){
			return array('result' => true);
		}
		else{
			return array('result' => false,'error' => true,'error_message' => 'MySQL INSERT FAILED');
		}
	}
	
	//check user credential
	function check_user_credentials($username,$password){
		$statement = 'SELECT * FROM `'.$this->db_name.'`.`'.$this->user_table.'` WHERE `username` = \''.mysql_real_escape_string($username).'\' ';
		$get_user_data = mysql_query($statement);
		if(mysql_num_rows($get_user_data) == 1){
			//found user
			$data = mysql_fetch_assoc($get_user_data);
			//var_dump($data);
			if($data['password'] === sha1($password)){
				//Password Match
				return array('result' => true);
			}
			else{
				//Password is incorrect
				return array('result' => false,'reason' => 'Password Incorrect');
			}												
		}
		else{
			//Username doesnt exist in DB
			return array('result' => false,'reason' => 'Username does not exist');
		}
	}
	//Update the users last activity Time
	function update_last_activity($id){
	}
		
	
	
}
//create instance of class
$db = new database;
?>
