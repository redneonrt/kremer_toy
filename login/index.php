<?php
session_cache_expire(1440);
session_name('synergy_cores');
session_start();
require_once 'class/class.db.php';
require_once 'class/class.user.php';
if(isset($_POST['process_logout'])){
	$user->logout();
}	
if(!$_SESSION['logged_in']){
	header('location:login.php');
}
else if($_SESSION['logged_in']){
	var_dump($_SESSION);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cottens</title>
</head>

<body>
<form action="" method="post">
<input name="process_logout" type="hidden" value="true" />
<input name="logout_button" type="submit" value="Log Out" />
</form>
</body>
</html>