<?php
/**********************************************
		File Name:	shuttle.php
		Date:	01-31-2008
		Author:	Chris Reed	
***********************************************/
require 'include/functions.php';
$title = "Shuttle Call";
$nav = nav_buttons(array("Main" => "index.php","Change Location" => "shuttle.php"));
if(isset($_POST['update']))
{
	if(!empty($_POST['update']))
	{
		$number_updated = count($_POST['update']);
		if($number_updated == 1)
		{
			$message = $number_updated." Order Updated";
		}
		else
		{
			$message = $number_updated." Orders Updated";
		}
		foreach($_POST['update'] as $id)
		{
			$query = mysql_query("UPDATE `order` SET `call_back` = 'T' WHERE `id` = '$id'") or die(mysql_error());
		}
	}
}
//This checks to see if the user is adding a shuttle note
if(isset($_POST['save_order']))
{
	if($_POST['shuttle_notes'] != "Type any extra parts here")
	{
		$shuttle_notes = mysql_query("INSERT INTO `shuttle_notes` (note,store) VALUES('$_POST[shuttle_notes]','$store_info[number]')")or die(mysql_error());
	}
}
if(isset($_GET['location']))
{
	$search_time = date("Y-m-d H:i:s",strtotime("-72 hours"));
	$sql = "SELECT * FROM `order` WHERE `location` = '$_GET[location]' AND `store` = '$store_info[number]' AND `time` >= '$search_time' AND `call_back` = 'F' AND `sold` = 'F' AND `type` = 'local' AND `qty` != '0' AND `received` = 'F'";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query) > 0)
	{
		if($_GET['location'] == 'MIN')
		{
			$message = "*".$_GET['location']." DC*";
		}
		elseif($_GET['location'] == 'OWA')
		{
			$message = "*".$_GET['location']." DC*";
		}
		else
		{
			$message = "*".$_GET['location']."*";
		}
		$number = 0; //for color change code
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
				$table_class = "shuttle_results_a";
			}
			else
			{
				$table_class = "shuttle_results_b";
			}
			//END color switch code*************************************
			$id = $row['id'];
			$line_code = $row['line_code'];
			$part_num = $row['part_num'];
			$qty = $row['qty'];
			$name = $row['name'];
			$time = $row['time'];
			$notes = trim($row['notes']);
			if(empty($notes))
			{
				$notes = "&nbsp;";
			}
			$details_link = "<a href=\"edit.php?id=".$id."\">Details</a>";
			
			$strings[] = "<tr>\n
							<td class=\"".$table_class."\">".$line_code."</td>\n
							<td class=\"".$table_class."\">".$part_num."</td>\n
							<td class=\"".$table_class."\">".$qty."</td>\n
							<td class=\"".$table_class."\">".account_name($name)."</td>\n
							<td class=\"".$table_class."\">".date("h:i a m/d",strtotime($time))."</td>\n
							<td class=\"".$table_class."\"><marquee bgcolor=black width=\"70\">".$notes."</marquee></td>\n
							<td class=\"".$table_class."\">".$details_link."</td>\n
							<td class=\"".$table_class."\"><input type=\"checkbox\" name=\"update[]\" value=\"".$id."\"></td>\n
						  </tr>\n";
		}
		$output = implode("\n",$strings);
		$template_vars = array("STORE_SELECT" => $ss_html,
								"TITLE" => $title,
								"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
								"IMAGE" => HEADER_IMAGE,
								"IMAGE_TEXT" => HEADER_TEXT,
								"NAV_LINKS" => $nav,
								"STORE_NAME" => $store_info['name'],
								"STORE_NUMBER" => $store_info['number'],
								"MESSAGE" => $message,
								"OUTPUT" => $output,
								"TABLE_HEADER" => "Displaying pending orders from the last 72 hours",	
								"FOOTER" => FOOTER
							);
		$template = 'html/shuttle_b.html';
		echo $page = template($template,$template_vars);
	}
	else
	{
		$message = "No Pending Orders";
	}
}
else
{
	$table_header = "Please choose the location of your driver";
	
	$search_time = date("Y-m-d H:i:s",strtotime("-72 hours"));
	$button_query = mysql_query("SELECT DISTINCT `location` FROM `order` WHERE `type` = 'local' AND `store` = '$store_info[number]' AND `time` >= '$search_time' AND `call_back` = 'F' AND `received` = 'F' AND `qty` != '0' AND `sold` = 'F' ");
	if(mysql_num_rows($button_query) > 0)
	{
		while($row = mysql_fetch_assoc($button_query))
		{
			$button_array[] = $row['location'];
		}
		sort($button_array);
		foreach($button_array as $var)
		{
			$html[] = "<tr>\n
					<td><div align=\"center\"><input name=\"".$var."\" type=\"button\" class=\"shuttle_button\" onClick=\"window.location='shuttle.php?location=".$var."'\" value=\"".$var."\"></div></td>\n
					</tr>\n";
		}
		$buttons = '';
		$buttons = implode("\n",$html);
	}
	else
	{
		$table_header = "No Parts Pending";
		$buttons = '';
	}
	$message = '';
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
							"FOOTER" => FOOTER,
							"BUTTONS" => $buttons,
						);
	$template = 'html/shuttle_a.html';
	echo $page = template($template,$template_vars);	
}
?>	