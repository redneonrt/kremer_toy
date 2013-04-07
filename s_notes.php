<?php
/**********************************************
		File Name:	s_notes.php
		Date:	10-10-2007
		Author:	Chris Reed	
***********************************************/
require 'include/functions.php';
// Setup main page variables
$title = "Shuttle Notes";
$nav = nav_buttons(array("Main" =>"index.php","Shuttle Call" => "shuttle.php"));
if(isset($_POST['purge']))
{
	$purge_sql = "DELETE FROM `shuttle_notes` WHERE `store` = '$store_info[number]' LIMIT 10 ";
	$purge_query = mysql_query($purge_sql) or die(mysql_error());	
}
$table_header = "Shuttle Notes";
$query = mysql_query("SELECT * FROM `shuttle_notes` WHERE `store` = '$store_info[number]' ORDER BY `time_stamp` DESC");
if($query)
{
	if(mysql_num_rows($query) > 0)
	{
		while($row = mysql_fetch_array($query))
		{
			$time = $row['time_stamp'];
			$note = $row['note'];
			if(empty($note))
			{
				$note = "&nbsp;";
			}
			$strings[] = "<tr>\n
							<td class=\"shuttle_notes\">".date("m/d",strtotime($time))."</td>\n
							<td  class=\"shuttle_notes\">".date("h:i a",strtotime($time))."</td>\n
							<td  class=\"shuttle_notes\">".$note."</td>\n
						 </tr>\n";
		}
		$output = NULL;
		$output = implode("\n",$strings);
	}
	else
	{
		$output = NULL;
		$message = "No saved shuttle notes";
	}
}
else
{
	$output = NULL;
	$message = "MySQL Error";
}

$message = NULL;
$template_vars = array("STORE_SELECT" => $ss_html,
						"TITLE" => $title,
						"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
						"IMAGE" => HEADER_IMAGE,
						"IMAGE_TEXT" => HEADER_TEXT,
						"NAV_LINKS" => $nav,
						"STORE_NAME" => $store_info['name'],
						"STORE_NUMBER" => $store_info['number'],
						"MESSAGE" => $message,
						"TABLE_HEADER" => $table_header,
						"OUTPUT" => $output,
						"FOOTER" => FOOTER
						);
$template = 'html/shuttle_notes.html';
echo $page = template($template,$template_vars);
?>		


