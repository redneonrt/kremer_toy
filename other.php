<?php
/**********************************************
		File Name:	other.php
		Date:	10-6-2007
		Author:	Chris Reed	
***********************************************/

require 'include/functions.php';
$nav = nav_buttons(array("Main" => "index.php", "Local Order" => "local.php", "Other DC" => "other.php", "Factory Order" => "special.php","Search" => "advanced_search.php",));
$message = "Other DC's ONLY";
$form_header = "&nbsp;";
//$db_records = db_records("other",$store_info['number']);
$error = false;
$continue = false;
$title = "Other DC Order";
if(isset($_POST['submit']))
{
	if($_POST['submit'] == "Continue")
	{
		$continue = true;
	}
	if(empty($_POST['location']) or empty($_POST['line_code']) or empty($_POST['part_num']) or empty($_POST['qty']) or empty($_POST['emp']) or empty($_POST['name']) or empty($_POST['tracking']))
	{
		$form_header = "<font color=\"red\">Please Fill Out Form</font>";
		$form_array = array($_POST['location'],$_POST['line_code'],$_POST['part_num'],$_POST['qty'],$_POST['emp'],$_POST['name'],$_POST['number'],$_POST['freight'],$_POST['notes'],$_POST['tracking']);
		$error = true;
	}
	elseif(empty($_POST['number']))
	{
		$form_header = "P.O./Phone Number cant be blank OR 0";
		$form_array = array($_POST['location'],$_POST['line_code'],$_POST['part_num'],$_POST['qty'],$_POST['emp'],$_POST['name'],$_POST['number0'],$_POST['freight'],$_POST['notes'],$_POST['tracking']);
		$error = true;
	}
	elseif(!is_numeric($_POST['emp']))
	{
		$form_header = "Employee number must be numeric!";
		$form_array = array($_POST['location'],$_POST['line_code'],$_POST['part_num'],$_POST['qty'],$_POST['delete'],$_POST['name'],$_POST['number'],$_POST['freight'],$_POST['notes'],$_POST['tracking']);
		$error = true;
	}
	elseif(!is_numeric($_POST['qty']))
	{
		$form_header = "Quantity must be numeric!";
		$form_array = array($_POST['location'],$_POST['line_code'],$_POST['part_num'],$_POST['delete'],$_POST['emp'],$_POST['name'],$_POST['number'],$_POST['freight'],$_POST['notes'],$_POST['tracking']);
		$error = true;		
	}
	elseif(strlen($_POST['location']) <= 1)
	{
		$message = "Please enter a proper DC code<font>";
		$form_array = array($_POST['delete'],$_POST['line_code'],$_POST['part_num'],$_POST['qty'],$_POST['emp'],$_POST['name'],$_POST['number'],$_POST['freight'],$_POST['notes'],$_POST['tracking']);
		$error = true;		
	}
	elseif(empty($_POST['freight']))
	{
		$form_header = "Please enter a freight amount";
		$form_array = array($_POST['location'],$_POST['line_code'],$_POST['part_num'],$_POST['qty'],$_POST['emp'],$_POST['name'],$_POST['number'],$_POST['delete'],$_POST['notes'],$_POST['tracking']);
		$error = true;		
	}
	else
	{
		unset($_POST['submit']);
		if(empty($_POST['prebill']))
		{
			$_POST['prebill'] = 'F';
		}	
		foreach($_POST as $key => $var)
		{
			$var = strtoupper($var);
		}
		//Get the class of the part number you are trying to insert
		$_POST['class'] = class_lookup($_POST['line_code'],$_POST['part_num']);
		
		//GODADDY MYSQL TIMEZONE FIX
		//set time to now(php runtime has correct time)
		$_POST['time'] = date("Y-m-d H:i:s");
		$sql = insert_into($_POST,"order");
		$order = mysql_query($sql);
		if(!$order)
		{
			$message = "<font color=\"red\">There has been a MySQL error<font>";
		}
		else
		{
			$order_id = mysql_insert_id();
			$form_header = "<a href=\"javascript:popUp('print.php?id=".$order_id."&type=1')\">Order has been saved click here to print<a>";
		}
	}
}
//Output the page
if(!$error)
{
	if($continue)
	{
		$prebill = pre_bill_checkbox($_POST);
		$template_vars = array("STORE_SELECT" => $ss_html,
				"TITLE" => $title,
				"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
				"IMAGE" => HEADER_IMAGE,
				"IMAGE_TEXT" => HEADER_TEXT,
				"NAV_LINKS" => $nav,
				"MESSAGE" => $message,
				"FORM_HEADER" => $form_header,
				"LOCATION_VALUE" => "",
				"LINE_CODE_VALUE" => "",
				"PART_NUMBER_VALUE" => "",
				"QTY_VALUE" => "",
				"EMP_VALUE" => $_POST['emp'],
				"NAME_VALUE" => $_POST['name'],
				"NUM_VALUE" => $_POST['number'],
				"TRACKING_VALUE" => "",
				"FREIGHT_VALUE" => "",
				"NOTES_VALUE" => "",
				"TYPE" => "other",
				"STORE" => $store_info['number'],
/*				"DB_RECORDS" => $db_records,
*/				"PREBILL" => $prebill,
				"FOOTER" => FOOTER
						);
		$template = 'html/other.html';
		echo $page = template($template,$template_vars);
	}
	else
	{	
		$template_vars = array("STORE_SELECT" => $ss_html,
			"TITLE" => $title,
			"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
			"IMAGE" => HEADER_IMAGE,
			"IMAGE_TEXT" => HEADER_TEXT,
			"NAV_LINKS" => $nav,
			"MESSAGE" => $message,
			"FORM_HEADER" => $form_header,
			"LOCATION_VALUE" => "",
			"LINE_CODE_VALUE" => "",
			"PART_NUMBER_VALUE" => "",
			"QTY_VALUE" => "",
			"EMP_VALUE" => "",
			"NAME_VALUE" => "",
			"NUM_VALUE" => "",
			"TRACKING_VALUE" => "",
			"FREIGHT_VALUE" => "",
			"NOTES_VALUE" => "",
			"TYPE" => "other",
			"STORE" => $store_info['number'],
/*			"DB_RECORDS" => $db_records,
*/			"PREBILL" => "<input name=\"prebill\" type=\"checkbox\" id=\"prebill\" value=\"T\"/>",
			"FOOTER" => FOOTER
					);
		$template = 'html/other.html';
		echo $page = template($template,$template_vars);
	}
}
if($error)
{
	$prebill = pre_bill_checkbox($_POST);
	$template_vars = array("STORE_SELECT" => $ss_html,
		"TITLE" => $title,
		"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
		"IMAGE" => HEADER_IMAGE,
		"IMAGE_TEXT" => HEADER_TEXT,
		"NAV_LINKS" => $nav,
		"MESSAGE" => $message,
		"FORM_HEADER" => $form_header,
		"LOCATION_VALUE" => $form_array[0],
		"LINE_CODE_VALUE" => $form_array[1],
		"PART_NUMBER_VALUE" => $form_array[2],
		"TRACKING_VALUE" => $form_array[9],
		"QTY_VALUE" => $form_array[3],
		"EMP_VALUE" => $form_array[4],
		"NAME_VALUE" => $form_array[5],
		"NUM_VALUE" => $form_array[6],
		"FREIGHT_VALUE" => $form_array[7],
		"NOTES_VALUE" => $form_array[8],
		"TYPE" => "other",
		"STORE" => $store_info['number'],
/*		"DB_RECORDS" => $db_records,
*/		"PREBILL" => $prebill,
		"FOOTER" => FOOTER
							);
	$template = 'html/other.html';
	echo $page = template($template,$template_vars);
}
//echo $sql;	
?>
