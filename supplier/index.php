<?php
require_once('include/functions.php');
$all_query = mysql_query("SELECT * FROM `contacts` ORDER BY `name` ASC");
if(mysql_num_rows($all_query) > 0){
	while($row = mysql_fetch_assoc($all_query)){
		$div_html[] = "<div id=\"contact\">
							<a href=\"zzzzzz.php?z=".$row['id']."\">".$row['name']."</a>
						<span id=\"hidden\">".$row['tags']."</span>
						<div id=\"phone\">".phone_num_format($row['pri_phone'])."</div>
						</div>";
	}
	$div_items = implode("\n",$div_html);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="include/js/prototype/prototype-1.6.0.2.js"></script>
<script type="text/javascript" src="include/js/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="include/js/quicksilver_search/quicksilver.js"></script>
<script type="text/javascript" src="include/js/quicksilver_search/livesearch.js"></script>
<script type="text/javascript" charset="utf-8">
	function show_help(){
		$('help_button').replace('<input name="help_button" type="button" class="help_button" id="help_button" onClick="javascript:hide_help()" value="Hide Help"/>');
		//$('help').show();
		Effect.SlideDown('help');
	}
	function hide_help(){
		$('help_button').replace('<input name="help_button" type="button" class="help_button" id="help_button" onClick="javascript:show_help()" value="Show Help"/>');
		//$('help').hide();
		Effect.SlideUp('help');
	}
	function clear_form(){
		document.getElementById("filter").value = "";
			new QuicksilverLiveSearch('filter', 'contacts');
			$('filter').activate();
	}
	document.observe('dom:loaded', function() { 
			new QuicksilverLiveSearch('filter', 'contacts');
			$('filter').activate();
			$('help').hide();
		});
		
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Index of Suppliers</title>
<link href="include/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="main">
	<div id="modernbricksmenu">
		<ul>
			<li id="current"><a href="./">Main</a></li>
			<li><a href="supplier_form.php">Add</a></li>
			<li><a href="keywords.php">Keyword Display</a></li>
            <li><a href="search.php">Search</a></li>
			<li><a href="../synergy/">Go to -> Synergy</a></li>
		</ul>
		<form action="" method="get" id="myform">
			<input name="help_button" type="button" class="help_button" id="help_button" onClick="javascript:show_help()" value="Show Help"/>
			<label for="filter">Filter:
			<input name="filter" type="text" class="filter" id="filter" />
			</label>
            <input name="reset" type="button" id="clear_button" onClick="javascript:clear_form();" value="Clear" />
		</form>
	</div>
	<div id="modernbricksmenuline">&nbsp;</div>
	<div id="help">
		<ul>
			<li><img src="include/images/info_20x20.png" /><span>Use the filter field at the top right to search through the list.</span></li>
			<li><img src="include/images/info_20x20.png" /><span>You can click on any of the entries on the list to bring up all the information regarding that contact.</span></li>
			<li><img src="include/images/info_20x20.png" /><span>From there you can add your own note or edit the contact details details.</span></li>
			<li><img src="include/images/info_20x20.png" /><span>If you would like to create a new contact just click the add button on the menu above.</span></li>
			<li id="last"><img src="include/images/info_20x20.png" /><span>To return to Synergy(Order Tracking System) at any time just click Synergy on the menu.</span></li>

		</ul>
	</div>
	<div id="search_container">
		<div id="contacts"> <?php echo $div_items; ?> </div>
	</div>
</div>
</body>
</html>
