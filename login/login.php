<?php
session_cache_expire(1440);
session_name('synergy_core');
session_start();
require_once 'class/class.db.php';
require_once 'class/class.user.php';
if(isset($_POST['submit_login'])){
	if(!empty($_POST['username']) && !empty($_POST['password']) ){
		$verify = $db->check_user_credentials(strtolower($_POST['username']),$_POST['password']);
		//var_dump($verify);
		if($verify['result']){
			//Register the users session---------------
			//Get the users info from the databse
			$username = strtolower($_POST['username']);
			$get_user_info = $user->register_user_session($username);
			//var_dump($get_user_info);
			//Register details to the session variable
			$_SESSION['logged_in'] = true;
			$_SESSION['user_info'] = array('username' => $get_user_info['username'],'id' => $get_user_info['id'],'multi_store' => $get_user_info['multi_store'] );
			//redirect to index.php
			header('location:/synergy/');
		}
		else{
			$_SESSION['error_array']['error'] = true;
			$_SESSION['error_array']['message'] = $verify['reason'];
		}
	}
	else{
		$_SESSION['error_array']['error'] = true;
		$_SESSION['error_array']['message'] = 'Please fill in all fields';
	}
}
if(isset($_SESSION['message'])){
	$message = $_SESSION['message'];
	unset($_SESSION['message']);
}
if(isset($_SESSION['error_array']['message'])){
	$message = $_SESSION['error_array']['message'];
	unset($_SESSION['error_array']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<!--<link href="../napa.css" rel="stylesheet" type="text/css" />-->
<link href="../synergy_v2c.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center">
  <div>
    <font color="#FF0000"><?php if(isset($message)){ echo $message; }?></font>
  </div>
  <form action="" method="post">
    <div>
      <p>Cottens' Synergy</p>
    </div>
    <div>
      <div>Username:</div>
      <input name="username" type="text" />
    </div>
    <div>
      <div>Password:</div>
      <input name="password" type="password" />
    </div>
    <div> <br />
      <input name="submit_login" type="submit" value="Log In" />
    </div>
  </form>
</div>
</body>
</html>
<?php
//For Testing Only
//var_dump($_SESSION);
?>