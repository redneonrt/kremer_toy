<?php
/**********************************************
		File Name:	print.php
		Date:	10-17-2007
		Author:	Chris Reed	
***********************************************/

require 'include/functions.php';
/**********************************************
Type 1 is for customer copy of order
Type 2 Is for internal use only

***********************************************/
if(isset($_GET['id']))
{
	$query = mysql_query("SELECT * FROM `order` WHERE `id` = '$_GET[id]'");
	if($query)
	{
		
		while($row = mysql_fetch_assoc($query))
		{
		foreach($row as $key => &$var)
		{
			if(empty($var))
			{
				if($key == "qty")
				{
					$var = 0;
				}
				else
				{
					$var = "&nbsp;";
				}
			}
		}
			$id = $row['id'];
			$type = $row['type'];
			$time = date("h:i a m/d",strtotime($row['time']));
			$store = $row['store'];
			$emp = $row['emp'];
			$location = $row['location'];
			$line_code = $row['line_code'];
			$part_num = $row['part_num'];
			$qty = $row['qty'];
			$name = account_name($row['name']);
			$number = $row['number'];
			$tracking = $row['tracking'];
			$cost = $row['cost'];
			$sell = $row['sell'];
			$freight = $row['freight'];
			$notes = $row['notes'];
			if($row['call_back'] == 'T')
			{
				$call_back = "Yes";
			}
			else
			{
				$call_back = "No";
			}
			if($row['received'] == 'T')
			{
				$received = "Yes";
			}
			else
			{
				$received = "No";
			}
			if($row['call_cust'] == 'T')
			{
				$call_cust = "Yes";
			}
			else
			{
				$call_cust = "No";
			}
			if($row['sold'] == 'T')
			{
				$sold = "Yes";
			}
			else
			{
				$sold = "No";
			}
			if($row['prebill'] == 'T')
			{
				$prebill = "Yes";
			}
			else
			{
				$prebill = "No";
			}
			$title = "Print";
		}
		if($_GET['type'] == 1)
		{
			$message = "Cottens' NAPA";
			$table_header = $store_info['address']."<br>".$store_info['phone'];
			$template_vars = array(
									"TITLE" => $title,
									"MESSAGE" => $message,
									"TABLE_HEADER" => $table_header,
									"ID" => $id,
									"TIME" => $time,
									"LOCATION" => $location,
									"EMP" => $emp,
									"LINE_CODE" => $line_code,
									"PART_NUM" => $part_num,
									"QTY" => $qty,
									"NAME" => $name,
									"NUMBER" => $number,
									"FREIGHT" => $freight,
									"NOTES" => $notes,
									"PREBILL" => $prebill
								);
			$template = 'html/print_a.html';
			echo $page = template($template,$template_vars);
		}
		elseif($_GET['type'] == 2)
		{
			$shuttle_delivery_status = shuttle_delivery_status($id);
			$message = "Cottens' NAPA";
			$table_header = $store_info['address']."<br>".$store_info['phone'];
			$template_vars = array(
									"TITLE" => $title,
									"MESSAGE" => $message,
									"TABLE_HEADER" => $table_header,
									"ID" => $id,
									"TYPE" => $type,
									"TIME" => $time,
									"STORE_NUMBER" => $store,
									"LOCATION" => $location,
									"EMP" => $emp,
									"LINE_CODE" => $line_code,
									"PART_NUM" => $part_num,
									"QTY" => $qty,
									"NAME" => $name,
									"NUMBER" => $number,
									"NOTES" => $notes,
									"TRACKING" => $tracking,
									"COST" => $cost,
									"SELL" => $sell,
									"FREIGHT" => $freight,
									"CALL_BACK" => $call_back,
									"RECEIVED" => $received,
									"CALL_CUST" => $call_cust,
									"SOLD" => $sold,
									"PREBILL" => $prebill,
									"CURRENT_STATUS" => $shuttle_delivery_status['status'],
									"CURRENT_STATUS_MESSAGE" => $shuttle_delivery_status['status_message']
								);
			$template = 'html/print_b.html';
			echo $page = template($template,$template_vars);
		}
	}
	else
	{
		$error = true;
	}
}

?>

