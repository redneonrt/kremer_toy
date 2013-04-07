<?php
require 'include/db.php';
$password = '14684';
//var_dump($_SERVER);
$_GET['type'] = NULL;
if(isset($_POST['delete'])){
	echo "OK To Delete";

}
$display_html = "<div class=\"remove_checkbox\" >Nothing to see here</div>";
if(isset($_GET['id'])){
	if(!empty($_GET['id'])){
		$sql = "SELECT * FROM `contacts` WHERE `id` = '$_GET[id]' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			$html[] = "<div class=\"remove_checkbox\">".$row['name']."</div>";
		}
		//Add Remove Button
		$html[] = "<p><input name=\"button\" type=\"submit\" value=\"Remove Entry\" /></p>\n";
		//Add Hidden field "delete"
		$html[] = "<input name=\"delete\" type=\"hidden\" value=\"0\" />";
		$display_html = implode("\n",$html);
	}
}
else if($_GET['type'] == 'MasterList'){
	$sql = "SELECT * FROM `contacts` ORDER BY `name` ASC";
	$query = mysql_query($sql);
	while($row = mysql_fetch_assoc($query)){
		$html[] = "<div class=\"remove_checkbox\"><p><label>\n<input name=\"checkbox\" type=\"checkbox\" id=\"checkbox\" value=\"".$row['id']."\" />".$row['name']."\n</label></p></div>";
	}
	$display_html = implode("\n",$html);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Remove Entries</title>
<link href="include/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="" method="post">
<?php
	echo $display_html;
?>
</form>
<a onclick="parent.parent.GB_hide();parent.parent.location.href='index.php'; ">Close & Redirect</a>
</body>
</html>