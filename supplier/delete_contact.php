<?php
$password = '14684';
require 'include/db.php';
//Process removal of contact and associated tags/keywords
if(isset($_POST['confirm_delete'])){
	if(!empty($_POST['delete_id'])){
		$contact_sql = "DELETE FROM `contacts` WHERE `id` = '$_POST[delete_id]' ";
		$tag_sql = "DELETE FROM `tags` WHERE `entry_id` = '$_POST[delete_id]' ";
		//First delete the Contact
		$delete_contact = mysql_query($contact_sql);
		//Now delete the tags
		$delete_tags = mysql_query($tag_sql);
		//ALL DONE!
	}
}
//Send The Login Form
if(isset($_POST['submit_password'])){
	if(!empty($_POST['pword'])){
		if($_POST['pword'] == $password){
			$display = true;
			$message = 'Access Granted';
		}
		else{
			$display = false;
			$message = 'Access Denied';
		}
	}
	else{
		$display = false;
		$message = 'Empty';
	}
}
else{
	$display = false;
	$message = 'Please enter your password';
}
//Display Login Form
if(!$display){
	if(isset($_GET['id'])){
		$send_id = "?id=".$_GET['id'];
	}
	else{
		$send_id = NULL;		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="include/css/style.css" rel="stylesheet" type="text/css" />
<title>Login</title>
<script type="text/javascript">

</script>
</head>
<body>
<div align="center">
  <p> <?php echo $message; ?> </p>
  <form name="login" method="post" action="delete_contact.php<?php echo $send_id;?>">
    <input name="pword" type="password" size="10" />
    <input name="submit_password" type="submit" value="Log In" />
  </form>
</div>
</body>
</html>
<?php
}
//Show Contact removal info
if($display){
	if(isset($_GET['id'])){
		if(!empty($_GET['id'])){
			$sql = "SELECT * FROM `contacts` WHERE `id` = '$_GET[id]' LIMIT 1";
			$query = mysql_query($sql);
			while($row = mysql_fetch_assoc($query)){
				$html[] = "<div class=\"remove_checkbox\">".$row['name']."</div>";
			}
			//Add Remove Button
			$html[] = "<p><input name=\"confirm_delete\" type=\"submit\" value=\"Remove Entry\" onclick=\"parent.parent.location.href='index.php';setTimeout(parent.parent.GB_hide(),2000);\"/></p>\n";
			//Add Hidden field "delete"
			$html[] = "<input name=\"delete_id\" type=\"hidden\" value=\"".$_GET['id']."\" />";
			$display_html = implode("\n",$html);
		}
	}
	//If no ID is specified then show a complete list
	
	/*----WIP----*/
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="include/css/style.css" rel="stylesheet" type="text/css" />
<title>Remove Contact</title>
</head>
<body>
<form action="" method="post">
  <?php
		echo "<div align=\"center\">".$display_html."</div>";
	?>
</form>
</body>
</html>
<?php
}


?>