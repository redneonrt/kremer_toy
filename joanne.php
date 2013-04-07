<?php
/**********************************************
		File Name:	joanne.php
		Date:	04-23-2010
		Author:	Chris Reed	
***********************************************/

require 'include/functions.php';
$title = "Review Orders";
$nav = nav_buttons(array("Main" => "index.php","Search" => "advanced_search.php"));
$message = "Message goes here";
$form_header = "Form Header Goes Here";

$timeframe = date('Y-m-d-H:i:s',strtotime("-1 day"));
$query = mysql_query("SELECT * FROM `order` WHERE  `time` > '$timeframe' ORDER BY `store` ASC, `line_code` ASC");
$message = "Displaying ALL orders from past 48 hours<br />To find orders prior to this <a href=\"advanced_search.php\">click here</a>";
if(mysql_num_rows($query) > 0)
{
	$number = 0;
	while($row = mysql_fetch_assoc($query))
	{
		//START color switch code**********************************
		if(isset($row_date))
		{
			if($row_date != date("m-d",strtotime($row['time'])) )
			{
				$number++;
				$row_date = date("m-d",strtotime($row['time']));
				
			}
		}
		else
		{
			$row_date = date("m-d",strtotime($row['time']));
		}
		if(($number % 2) == 0)
		{
			$table_class = "tracking_table_a";
		}
		else
		{
			$table_class = "tracking_table_a";
		}
		//END color switch code*************************************
		foreach($row as $key => &$var)
		{
			if(empty($var))
			{
				$var = "&nbsp;";
			}
		}
		if($row['call_back'] == 'T')
		{
			$call_back = "<font class=\"yes\">YES</font>";
		}
		else
		{
			$call_back = "<font class=\"no\">NO</font>";
		}
		$strings[]= "<tr>\n
						<td class=\"".$table_class."\">".$row['store']."</td>\n
						<td class=\"".$table_class."\">".$row['line_code']."</td>\n
						<td class=\"".$table_class."\">".$row['part_num']."</td>\n
						<td class=\"".$table_class."\">".$row['qty']."</td>\n
						<td class=\"".$table_class."\">".$row['location']."</td>\n
						<td class=\"".$table_class."\">".date("h:i a m/d",strtotime($row['time']))."</td>\n
						<td class=\"".$table_class."\">".account_name($row['name'])."</td>\n
					</tr>\n";
	}
	$output = implode("\n",$strings);
	$template_vars = array( "STORE_SELECT" => $ss_html,
							"TITLE" => $title,
							"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
							"IMAGE" => HEADER_IMAGE,
							"IMAGE_TEXT" => HEADER_TEXT,
							"NAV_LINKS" => $nav,
							"STORE_NAME" => $store_info['name'],
							"STORE_NUMBER" => $store_info['number'],
							"FORM_HEADER" => $form_header,
							"OUTPUT" => $output,
							"TYPE" => "CB?",
							"FOOTER" => FOOTER
						);
	$template = 'html/joanne.html';
	echo $page = template($template,$template_vars);
}
?>
