<?php
class user extends database{
	//var $username = '';
	//var $password = '';
	//Place this to automaticly start here
	function __construct(){
	}	
		
	function register_user_session($username){
		$get_record = $this->select_row(strtolower($username));
		if($get_record['result']){
			return $get_record['data'];
		}
	}
	


	function logout(){
		unset($_SESSION['logged_in']);
		unset($_SESSION['user_info']);
		session_destroy();
	}
}
//Initiate class
$user = new user;
?>