<?php
/**********************************************
		File Name:	receiving.php
		Date:	10-18-2007
		Author:	Chris Reed	
***********************************************/
require 'include/functions.php';
$title = "Review Orders";
$nav = nav_buttons(array("Main" => "index.php",
						 "Search" => "advanced_search.php",
						 "Review Orders" => "tracking.php",
						 "Misc. Receiving" => "misc_receiving.php",
						 "Report a problem" => "../index.php/incident_report/"
						 ));
$message = "Message goes here";
$form_header = "&nbsp;";
$hs_onclick_attr = 'onclick="return hs.htmlExpand(this,{objectType:\'iframe\',width:750, height:500})"';
//this set the page to default to Local Order
$_POST['local'] = "Local Order";

if(isset($_POST['received']))
{
	foreach($_POST['received'] as $id)
	{	
		$num = 0;
		$query = mysql_query("UPDATE `order` SET `received` = 'T' WHERE `id` = '$id' ");
		$num++;
	}
	$num_update = $num;
}
if(isset($_POST['other']))
{
	$query = mysql_query("SELECT * FROM `order` WHERE `type` = 'other' AND `store` = '$store_info[number]' AND `received` != 'T' AND `qty` != 0 ");
	$message = "Other DC orders";
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
				$table_class = "receiving_table_a";
			}
			else
			{
				$table_class = "receiving_table_b";
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
				$call_back = "<font color=\"#ffea00\">YES</font>";
			}
			else
			{
				$call_back = "<font color=\"red\">NO</font>";
			}
			$strings[]= "<tr>\n
							<td class=\"".$table_class."\">".$row['line_code']."</td>\n
							<td class=\"".$table_class."\">".$row['part_num']."</td>\n
							<td class=\"".$table_class."\">".$row['qty']."</td>\n
							<td class=\"".$table_class."\">".$row['location']."</td>\n
							<td class=\"".$table_class."\">".date("h:i a m/d",strtotime($row['time']))."</td>\n
							<td class=\"".$table_class."\">".account_name($row['name'])."</td>\n
							<td class=\"".$table_class."\">".$row['number']."</td>\n
							<td class=\"".$table_class."\"><input name=\"received[]\" type=\"checkbox\" value=\"".$row['id']."\" /></td>\n
							<td class=\"".$table_class."\"><a href=\"edit.php?id=".$row['id']."&type=1\" ".$hs_onclick_attr." >Quick</a>/<a href=\"edit.php?id=".$row['id']."&type=1\">Full</a></td>\n
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
								"FORM_HEADER" => $form_header,
								"OUTPUT" => $output,
								"FOOTER" => FOOTER
							);
		$template = 'html/received_c.html';
		echo $page = template($template,$template_vars);
	}
	else
	{
		$zero = true;
	}
}
elseif(isset($_POST['special']))
{
	$query = mysql_query("SELECT * FROM `order` WHERE `type` = 'special' AND `store` = '$store_info[number]' AND `received` != 'T' AND `qty` != 0 ");
	$message = "Special/Factory orders";
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
				$table_class = "receiving_table_a";
			}
			else
			{
				$table_class = "receiving_table_b";
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
				$call_back = "<font color=\"#ffea00\">YES</font>";
			}
			else
			{
				$call_back = "<font color=\"red\">NO</font>";
			}
			$strings[]= "<tr>\n
							<td class=\"".$table_class."\">".$row['line_code']."</td>\n
							<td class=\"".$table_class."\">".$row['part_num']."</td>\n
							<td class=\"".$table_class."\">".$row['qty']."</td>\n
							<td class=\"".$table_class."\">".$row['location']."</td>\n
							<td class=\"".$table_class."\">".date("h:i a m/d",strtotime($row['time']))."</td>\n
							<td class=\"".$table_class."\">".account_name($row['name'])."</td>\n
							<td class=\"".$table_class."\">".$row['number']."</td>\n
							<td class=\"".$table_class."\"><input name=\"received[]\" type=\"checkbox\" value=\"".$row['id']."\" /></td>\n
							<td class=\"".$table_class."\"><a href=\"edit.php?id=".$row['id']."&type=1\" ".$hs_onclick_attr." >Quick</a>/<a href=\"edit.php?id=".$row['id']."&type=1\">Full</a></td>\n
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
								"FORM_HEADER" => $form_header,
								"OUTPUT" => $output,
								"FOOTER" => FOOTER
							);
		$template = 'html/received_c.html';
		echo $page = template($template,$template_vars);
	}
	else
	{
		$zero = true;
	}
}
elseif(isset($_POST['local']))
{
	$timeframe = strtotime(TIMEFRAME);
	$query = mysql_query("SELECT * FROM `order` WHERE `type` = 'local' AND `store` = '$store_info[number]' AND `received` != 'T' AND `qty` != 0");
	$message = "Local Orders";
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
				$table_class = "receiving_table_a";
			}
			else
			{
				$table_class = "receiving_table_b";
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
			
/*			if(empty($row[notes]))
			{
				$notes = "&nbsp;";
			}
			else
			{
				$notes = trim($row[notes]);
			}
*/			$notes = trim($row['notes']);
			if(empty($notes))
			{
				$notes = "&nbsp;";
			}
			if($row['prebill'] == 'T'){
				$prebilled = 'YES';
		    }
			else{
				$prebilled = 'NO';
			}
			$strings[]= "<tr class=\"clickable\">\n
							<td class=\"".$table_class."\">".$row['line_code']."</td>\n
							<td class=\"".$table_class."\">".$row['part_num']."</td>\n
							<td class=\"".$table_class."\">".$row['qty']."</td>\n
							<td class=\"".$table_class."\">".$row['location']."</td>\n
							<td class=\"".$table_class."\">".date("h:i a m/d",strtotime($row['time']))."</td>\n
							<td class=\"".$table_class."\">".account_name($row['name'])."</td>\n
							<td class=\"".$table_class."\">".$row['number']."</td>\n
							<td class=\"".$table_class."\"><marquee bgcolor=black width=\"70\">".$notes."</marquee></td>\n
							
							<td class=\"".$table_class."\">".$call_back."</td>\n
							<td class=\"".$table_class."\">".$prebilled."</td>\n
							<td class=\"".$table_class."\"><input name=\"received[]\" type=\"checkbox\" value=\"".$row['id']."\" /></td>\n
							<td class=\"".$table_class."\"><a href=\"edit.php?id=".$row['id']."&type=1&referer=receiving\" ".$hs_onclick_attr." >Quick</a>/<a href=\"edit.php?id=".$row['id']."&type=1&referer=receiving\">Full</a></td>\n
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
								"FORM_HEADER" => $form_header,
								"OUTPUT" => $output,
								"FOOTER" => FOOTER
							);
		$template = 'html/received_a.html';
		echo $page = template($template,$template_vars);
	}
	else
	{
		 $zero = true;
	}
}
if(isset($zero)){
	if($zero)
	{
		$template_vars = array("STORE_SELECT" => $ss_html,
								"TITLE" => $title,
								"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
								"IMAGE" => HEADER_IMAGE,
								"IMAGE_TEXT" => HEADER_TEXT,
								"NAV_LINKS" => $nav,
								"ZERO" => "NO RECORDS TO DISPLAY",
								"FOOTER" => date("g:i a l F jS, Y")
							);
		$template = 'html/received_b.html';
		echo $page = template($template,$template_vars);
	}
}
?>