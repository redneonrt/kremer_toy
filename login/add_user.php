<?php
//session_cache_expire(30);
session_name('synergy_core');
session_start();
require_once 'class/class.db.php';
$error_array;
if(isset($_POST['submit_form'])){
	//Check for empty fields
	if(!empty($_POST['username']) || !empty($_POST['password']) ){
		//check that password is long enough
		if(strlen(trim($_POST['primary_password'])) >= 3){
			//check that both password match
			if($_POST['primary_password'] === $_POST['secondary_password']){
					//Check for existing username
					$username_table = 'users';
					$username_row = 'username';
					$username_row_match = $_POST['username'];
					if(!$db->db_check_duplicate($username_table,$username_row,$username_row_match)){
						//completed checks now process the addition
						$user_info = array('username' => strtolower($_POST['username']),
										   'password' => $_POST['primary_password'],
										   'email' => $_POST['primary_email'],
										   'user_level' => 1);
						$add_this_user = $db->create_new_user($user_info);
						if($add_this_user['result']){
							//send to the main page and ask to login
							$_SESSION['message'] = 'Registration Success, Please Login below';
							header('location:login.php');
						}
						else{
							//db entry failed
							$error_array['error'] = true;
							$error_array['message']  = $add_this_user['error_message'];
							$_SESSION['error_array'] = $error_array;
						}
					}
			}
			else{
				//Passwords dont match
				$error_array['error'] = true;
				$error_array['message'] = 'Passwords do not match, please re-enter';
				//$_POST['primary_password'] = '';
				//$_POST['secondary_password'] = '';
				$error_array['data'] = $_POST;
				$_SESSION['error_array'] = $error_array;
			}
		}
		else{
			//Password is not long enough
				$error_array['error'] = true;
				$error_array['message'] = 'Password must be at least 3 letters or numbers';
				$_POST['password'] = '';
				$error_array['data'] = $_POST;
				$_SESSION['error_array'] = $error_array;
		}
	}
	else{
		//found fields that were empty
		$error_array['error'] = true;
		$error_array['message'] = 'Please fill in all fields';
		$error_array['data'] = $_POST;
		$_SESSION['error_array'] = $error_array;
	}
}
else{
	//Show the empty signup form
				
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New User Registration</title>
</head>
<body>
<form action="" method="post">
  <div>Please fill out the form</div>
  <div>
    <div>Username</div>
    <input name="username" type="text" />
  </div>
  <div>
    <div>Password</div>
    <input name="primary_password" type="password" />
  </div>
  <div>
    <div>Retype Password</div>
    <input name="secondary_password" type="password" />
  </div>
  <div>
    <input name="submit_form" type="submit" value="Register" />
  </div>
</form>
<div><a href="login.php">Already registered? Click here to login</a></div>
</body>
</html>
<?php
//var_dump($_SESSION);<hr />

} //end of blank signup form
if(isset($_SESSION['error_array']['error']) && $_SESSION['error_array']['error']){
	//show the signin form with previous values
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New User Registration</title>
</head>
<body>
<form action="" method="post">
  <div><?php echo $_SESSION['error_array']['message'] ;?></div>
  <div>
    <div>Username</div>
    <input name="username" type="text" value="<?php echo trim($_SESSION['error_array']['data']['username']); ?>" />
  </div>
  <div>
    <div>Password</div>
    <input name="primary_password" type="password" value="<?php  echo trim($_SESSION['error_array']['data']['primary_password']); ?>" />
  </div>
  <div>
    <div>Retype Password</div>
    <input name="secondary_password" type="password"  value="<?php echo trim($_SESSION['error_array']['data']['secondary_password']); ?>"/>
  </div>
  <div>
    <input name="submit_form" type="submit" value="Register" />
  </div>
</form>
</body>
</html>
<?php
//var_dump($_SESSION);
unset($_SESSION['error_array']);
//var_dump($_SESSION);
}//end signup form with previous values
?>