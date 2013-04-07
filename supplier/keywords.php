<?php
require_once('include/functions.php');
$sql = "SELECT * FROM `tags`";
$query = mysql_query($sql);
while($row = mysql_fetch_assoc($query)){
	$the_tags[] = strtoupper($row['tag']);
}
$remove_dup = array_unique($the_tags);
//var_dump($remove_dup);	
foreach($remove_dup as $var){
	$html[] = "<a id=\"keyword_link\" href=\"search.php?tag_search=".htmlspecialchars($var)." \">$var</a> \n  ";
}
$display_html = implode("",$html);
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tags on File</title>
<link href="include/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="modernbricksmenu">
		<ul>
			<li><a href="./">Main</a></li>
			<li><a href="supplier_form.php">Add</a></li>
			<li id="current"><a href="keywords.php">Keyword Display</a></li>
            <li><a href="search.php">Search</a></li>
			<li><a href="../synergy/">Go to -> Synergy</a></li>
		</ul>
	</div>
	<div id="modernbricksmenuline">&nbsp;</div>
   <div id="keyword_link" align="center"><p>These are the keywords already stored in the database<br />Click to see all matches to the keyword</p></div>
<div id="keyword_container" align="center"><?php   echo $display_html;?></div>
</body>
</html>
