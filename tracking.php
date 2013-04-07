<?php
/**********************************************
		File Name:	tracking.php
		Date:	10-18-2007
		Author:	Chris Reed	
***********************************************/

require 'include/functions.php';
$title = "Review Orders";
$nav = nav_buttons(array("Main" => "index.php",
						 "Search" => "advanced_search.php",
						 "Receiving" => "receiving.php",
						 "Report a problem" => "../index.php/incident_report/"
						 ));
$message = "Message goes here";
$form_header = "Form Header Goes Here";
$get_type = "local";
$hs_onclick_attr = 'onclick="return hs.htmlExpand(this,{objectType:\'iframe\',width:750, height:500})"';
if(isset($_GET['type'])){
	$get_type = $_GET['type'];
}
if(isset($_POST['id']))
{
	foreach($_POST['id'] as $id)
	{	
		$num = 0;
		$query = mysql_query("UPDATE `order` SET `sold` = 'T' WHERE `id` = '$id' ");
		$num++;
	}
	$num_update = $num;
}
if($get_type == 'other')
{
	$query = mysql_query("SELECT * FROM `order` WHERE `type` = 'other' AND `store` = '$store_info[number]' AND `sold` = 'F' AND `qty` != '0' ");
	$message = "Displaying Pending Other DC orders";
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
				$table_class = "tracking_table_b";
			}
			//END color switch code*************************************
			
			foreach($row as $key => &$var)
			{
				if(empty($key))
				{
					$var = "&nbsp;";
				}
			}
			if($row['received'] == 'T')
			{
				$received = "<font class=\"yes\">YES</font>";
			}
			else
			{
				$received = "<font class=\"no\">NO</font>";
			}
			$strings[]= "<tr>\n
							<td class=\"".$table_class."\">".$row['line_code']."</td>\n
							<td class=\"".$table_class."\">".$row['part_num']."</td>\n
							<td class=\"".$table_class."\">".$row['qty']."</td>\n
							<td class=\"".$table_class."\">".$row['location']."</td>\n
							<td class=\"".$table_class."\">".date("h:i a m/d",strtotime($row['time']))."</td>\n
							<td class=\"".$table_class."\">".account_name($row['name'])."</td>\n
							<td class=\"".$table_class."\">".phone_number($row['number'])."</td>\n
							<td class=\"".$table_class."\">".$received."</td>\n
							<td class=\"".$table_class."\"><input name=\"id[]\" type=\"checkbox\" value=\"".$row['id']."\" /></td>\n
							<td class=\"".$table_class."\"><a href=\"edit.php?id=".$row['id']."&type=1\" ".$hs_onclick_attr." >Quick</a>/<a href=\"edit.php?id=".$row['id']."&type=1\" >Full</a></td>\n
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
								"MESSAGE" => $message,
								"FORM_HEADER" => $form_header,
								"OUTPUT" => $output,
								"TYPE" => "Rcvd?",
								"FOOTER" => FOOTER
							);
		$template = 'html/tracking_a.html';
		echo $page = template($template,$template_vars);
	}
	else
	{
		$zero = true;
	}
}
elseif($get_type == 'special')
{
	$query = mysql_query("SELECT * FROM `order` WHERE `type` = 'special' AND `store` = '$store_info[number]' AND `sold` = 'F'  AND `qty` != '0'  ");
	$message = "Displaying Pending Special/Factory orders";
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
				$table_class = "tracking_table_b";
			}
			//END color switch code*************************************
			foreach($row as $key => &$var)
			{
				if(empty($var))
				{
					$var = "&nbsp;";
				}
			}
			if($row['received'] == 'T')
			{
				$received = "<font class=\"yes\">YES</font>";
			}
			else
			{
				$received = "<font class=\"no\">NO</font>";
			}
			$strings[]= "<tr>\n
							<td class=\"".$table_class."\">".$row['line_code']."</td>\n
							<td class=\"".$table_class."\">".$row['part_num']."</td>\n
							<td class=\"".$table_class."\">".$row['qty']."</td>\n
							<td class=\"".$table_class."\">".$row['location']."</td>\n
							<td class=\"".$table_class."\">".date("h:i a m/d",strtotime($row['time']))."</td>\n
							<td class=\"".$table_class."\">".account_name($row['name'])."</td>\n
							<td class=\"".$table_class."\">".phone_number($row['number'])."</td>\n
							<td class=\"".$table_class."\">".$received."</td>\n
							<td class=\"".$table_class."\"><input name=\"id[]\" type=\"checkbox\" value=\"".$row['id']."\" /></td>\n
							<td class=\"".$table_class."\"><a href=\"edit.php?id=".$row['id']."&type=1\" ".$hs_onclick_attr. ">Quick</a>/<a href=\"edit.php?id=".$row['id']."&type=1\" >Full</a></td>\n
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
								"MESSAGE" => $message,
								"FORM_HEADER" => $form_header,
								"OUTPUT" => $output,
								"TYPE" => "Rcvd?",
								"FOOTER" => FOOTER
							);
		$template = 'html/tracking_a.html';
		echo $page = template($template,$template_vars);
	}
	else
	{
		$zero = true;
	}
}
else
{
	$timeframe = TIMEFRAME;
	$query = mysql_query("SELECT * FROM `order` WHERE `type` = 'local' AND `store` = '$store_info[number]' AND `time` > '$timeframe' AND `sold` = 'F'  AND `qty` != '0' AND `prebill` != 'T' ORDER BY `time` DESC");
	$message = "Displaying Pending Local orders from past 48 hours<br />To find orders prior to this <a href=\"advanced_search.php\">click here</a>";
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
				$table_class = "tracking_table_b";
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
							<td class=\"".$table_class."\">".$row['line_code']."</td>\n
							<td class=\"".$table_class."\">".$row['part_num']."</td>\n
							<td class=\"".$table_class."\">".$row['qty']."</td>\n
							<td class=\"".$table_class."\">".$row['location']."</td>\n
							<td class=\"".$table_class."\">".date("h:i a m/d",strtotime($row['time']))."</td>\n
							<td class=\"".$table_class."\">".account_name($row['name'])."</td>\n
							<td class=\"".$table_class."\">".phone_number($row['number'])."</td>\n
							<td class=\"".$table_class."\">".$call_back."</td>\n
							<td class=\"".$table_class."\"><input name=\"id[]\" type=\"checkbox\" value=\"".$row['id']."\" /></td>\n
							<td class=\"".$table_class."\"><a href=\"edit.php?id=".$row['id']."&type=1&referer=tracking\" ".$hs_onclick_attr. ">Quick</a>/<a href=\"edit.php?id=".$row['id']."&type=1&referer=tracking\" >Full</a></td>\n
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
								"MESSAGE" => $message,
								"FORM_HEADER" => $form_header,
								"OUTPUT" => $output,
								"TYPE" => "CB?",
								"FOOTER" => FOOTER
							);
		$template = 'html/tracking_a.html';
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
		$template_vars = array( "STORE_SELECT" => $ss_html,
								"TITLE" => $title,
								"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
								"IMAGE" => HEADER_IMAGE,
								"IMAGE_TEXT" => HEADER_TEXT,
								"NAV_LINKS" => $nav,
								"MESSAGE" => "NO RECORDS TO DISPLAY",
								"FOOTER" => FOOTER
							);
		$template = 'html/tracking_b.html';
		echo $page = template($template,$template_vars);
	}
}
?>     


