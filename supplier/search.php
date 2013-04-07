<?php
require_once('include/functions.php');
$results = NULL;
if(isset($_GET['tag_search'])){
	$tag_search = $_GET['tag_search'];
	if(!empty($tag_search)){
		$tag_sql = mysql_query("SELECT * FROM `tags` WHERE(`tag` LIKE '%$tag_search%')");
		if(mysql_num_rows($tag_sql) > 0){
			$html[] = '<div id="search_result" align="center">Keyword Matches</div>';
			while($row = mysql_fetch_assoc($tag_sql)){
				$get_matches = mysql_query("SELECT * FROM `contacts` WHERE `id` = '$row[entry_id]'");
				while($row2 = mysql_fetch_assoc($get_matches)){
					$html[] = "<div id=\"search_result\">
									<div id=\"contact\"><a href=\"zzzzzz.php?z=".$row2['id']."\">".$row2['name']."</a>
									<div id=\"search_result_phone\">".phone_num_format($row2['pri_phone'])."</div></div>
								</div>";
				
				}
			}		
		}
		if(count($html) > 0){
			$results = implode(" ", $html);
		}
	}
}
if(isset($_POST['search_field'])){
	if(!empty($_POST['search_field'])){
		
		$search_term = NULL;
		$search_term = $_POST['search_field'];
		$sql = "SELECT * FROM `contacts` WHERE(`name` LIKE '%$search_term%' OR `web_address` LIKE '%$search_term%') ";
		$search_sql = mysql_query($sql);
		if(mysql_num_rows($search_sql) > 0){
			$html[] = '<div id="search_result" align="center">Name & Web Address Matches</div>';
			while($row = mysql_fetch_assoc($search_sql)){
				//var_dump($row);
				$html[] = "<div id=\"search_result\">
								<div id=\"contact\"><a href=\"zzzzzz.php?z=".$row['id']."\">".$row['name']."</a>
								<div id=\"search_result_phone\">".phone_num_format($row['pri_phone'])."</div></div>
						  </div>";
				
			}
			$results = implode(" ",$html);
		}
		
		//TAG Code
			$tag_sql = "SELECT * FROM `tags` WHERE `tag` LIKE '%$search_term%'";
			//echo $tag_sql;
			$tag_search_sql = mysql_query($tag_sql);
			if(mysql_num_rows($tag_search_sql) > 0){
				$tag_html[] = '<div id="search_result" align="center">Keyword Matches</div>';
				while($row = mysql_fetch_assoc($tag_search_sql)){
					$get_matches = mysql_query("SELECT * FROM `contacts` WHERE `id` = '$row[entry_id]' ");
					while($row2 = mysql_fetch_assoc($get_matches)){
						$tag_html[] = "<div id=\"search_result\">
										<div id=\"contact\"><a href=\"zzzzzz.php?z=".$row2['id']."\">".$row2['name']."</a>
										<div id=\"search_result_phone\">".phone_num_format($row2['pri_phone'])."</div></div>
						  			  </div>";
					}
				}
			}
			if(count($tag_html) > 0){
				$tag_results = implode(" ",$tag_html);
			}
			//END TAG CODE
			
			
		if(!isset($results) && !isset($tag_results)){
			$results = "<div id=\"search_no_match\">Sorry, no matches were found</div>";
		}
	}
	else{
		$results = "<div id=\"search_no_match\">Please enter a search term</div>";
	}
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search</title>
<link href="include/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="modernbricksmenu">
		<ul>
			<li><a href="./">Main</a></li>
			<li><a href="supplier_form.php">Add</a></li>
			<li><a href="keywords.php">keyword Display</a></li>
            <li id="current"><a href="search.php">Search</a></li>
			<li><a href="../synergy/">Go to -> Synergy</a></li>
		</ul>
	</div>
	<div id="modernbricksmenuline">&nbsp;</div>
<div align="center"><form action="search.php" method="post">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p><div id="search_label">Search:</div> 
    <input class="text_field" name="search_field" type="text" size="30" />
    <input id="search_button" name="submit" type="submit" value="Submit" />
    <input name="submit" type="reset" id="search_button" value="Reset" />
  </p>
</form></div>
<br />
<div><?php echo $results?></div>
<br />
<div><?php if(isset($tag_results)){ echo $tag_results; }?></div>
</body>
</html>
