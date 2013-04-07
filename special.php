<?php
/**********************************************
		File Name:	special.php
		Date:	10-6-2007
		Author:	Chris Reed	
***********************************************/

require 'include/functions.php';
$nav = nav_buttons(array("Main" => "index.php", "Local Order" => "local.php", "Other DC" => "other.php", "Factory Order" => "special.php","Search" => "advanced_search.php",));
$message = "Special Orders Only";
$form_header = "&nbsp;";
//$db_records = db_records("special",$store_info['number']);
$error = false;
$continue = false;
$title = "Factory Order";


//Enters an order
if(isset($_POST['submit']))
{
	if($_POST['submit'] == "Continue")
	{
		$continue = true;
	}
	if(empty($_POST['line_code']) or empty($_POST['part_num']) or empty($_POST['qty']) or empty($_POST['emp']) or empty($_POST['name']) or empty($_POST['tracking']))
	{
		$form_header = "<font color=\"red\">Please Fill Out Form</font>";
		$form_array = array("line_code" => $_POST['line_code'],"part_num" => $_POST['part_num'],"qty" => $_POST['qty'],"emp" => $_POST['emp'],"name" => $_POST['name'],"number" => $_POST['number'],"notes" => $_POST['notes'],"tracking" => $_POST['tracking'],"cost" => $_POST['cost'],"sell" => $_POST['sell'],"freight" => $_POST['freight']);
		$error = true;
	}
	elseif(empty($_POST['number']))
	{
		$form_header = "P.O./Phone Number cant be blank OR 0";
		$form_array = array("line_code" => $_POST['line_code'],"part_num" => $_POST['part_num'],"qty" => $_POST['qty'],"emp" => $_POST['emp'],"name" => $_POST['name'],"number" => $_POST['number0'],"notes" => $_POST['notes'],"tracking" => $_POST['tracking'],"cost" => $_POST['cost'],"sell" => $_POST['sell'],"freight" => $_POST['freight']);
		$error = true;
	}
	elseif(!is_numeric($_POST['emp']))
	{
		$form_headere = "Employee number must be numeric!";
		$form_array = array("line_code" => $_POST['line_code'],"part_num" => $_POST['part_num'],"qty" => $_POST['qty'],"emp" => $_POST['emp0'],"name" => $_POST['name'],"number" => $_POST['number'],"notes" => $_POST['notes'],"tracking" => $_POST['tracking'],"cost" => $_POST['cost'],"sell" => $_POST['sell'],"freight" => $_POST['freight']);
		$error = true;
	}
	elseif(!is_numeric($_POST['qty']))
	{
		$form_header = "Quantity must be numeric!";
		$form_array = array("line_code" => $_POST['line_code'],"part_num" => $_POST['part_num'],"qty" => $_POST['qty0'],"emp" => $_POST['emp'],"name" => $_POST['name'],"number" => $_POST['number'],"notes" => $_POST['notes'],"tracking" => $_POST['tracking'],"cost" => $_POST['cost'],"sell" => $_POST['sell'],"freight" => $_POST['freight']);
		$error = true;		
	}
	elseif(empty($_POST['freight']))
	{
		$form_header = "You need to enter a freight amount!";
		$form_array = array("line_code" => $_POST['line_code'],"part_num" => $_POST['part_num'],"qty" => $_POST['qty'],"emp" => $_POST['emp'],"name" => $_POST['name'],"number" => $_POST['number'],"notes" => $_POST['notes'],"tracking" => $_POST['tracking'],"cost" => $_POST['cost'],"sell" => $_POST['sell'],"freight" => $_POST['freight0']);
		$error = true;		
	}
	else
	{
	unset($_POST['submit']);
	//prebill function here
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
//Lets start building the page here
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
			"LINE_CODE_VALUE" => "",
			"PART_NUMBER_VALUE" => "",
			"QTY_VALUE" => "",
			"EMP_VALUE" => $_POST['emp'],
			"NAME_VALUE" => $_POST['name'],
			"NUM_VALUE" => $_POST['number'],
			"TRACKING_VALUE" => "",
			"COST_VALUE" => "",
			"SELL_VALUE" => "",
			"FREIGHT_VALUE" => "",
			"NOTES_VALUE" => "",
/*			"DB_RECORDS" => $db_records,
*/			"TYPE" => "special",
			"STORE" => $store_info['number'],
			"PREBILL" => $prebill,
			"LOCATION" => "FACTORY",
			"FOOTER" => FOOTER
				);
		$template = 'html/special.html';
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
			"LINE_CODE_VALUE" => "",
			"PART_NUMBER_VALUE" => "",
			"QTY_VALUE" => "",
			"EMP_VALUE" => "",
			"NAME_VALUE" => "",
			"NUM_VALUE" => "",
			"TRACKING_VALUE" => "",
			"COST_VALUE" => "",
			"SELL_VALUE" => "",
			"FREIGHT_VALUE" => "",
			"NOTES_VALUE" => "",
/*			"DB_RECORDS" => $db_records,
*/			"TYPE" => "special",
			"STORE" => $store_info['number'],
			"PREBILL" => "<input name=\"prebill\" type=\"checkbox\" id=\"prebill\" value=\"T\"/>",
			"LOCATION" => "FACTORY",
			"FOOTER" => FOOTER
				);
		$template = 'html/special.html';
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
		"LINE_CODE_VALUE" => $form_array['line_code'],
		"PART_NUMBER_VALUE" => $form_array['part_num'],
		"QTY_VALUE" => $form_array['qty'],
		"EMP_VALUE" => $form_array['emp'],
		"NAME_VALUE" => $form_array['name'],
		"NUM_VALUE" => $form_array['number'],
		"TRACKING_VALUE" => $form_array['tracking'],
		"COST_VALUE" => $form_array['cost'],
		"SELL_VALUE" => $form_array['sell'],
		"FREIGHT_VALUE" => $form_array['freight'],
		"NOTES_VALUE" => $form_array['notes'],
/*		"DB_RECORDS" => $db_records,
*/		"TYPE" => "special",
		"STORE" => $store_info['number'],
		"PREBILL" => $prebill,
		"LOCATION" => "FACTORY",
			"FOOTER" => FOOTER
			);
	$template = 'html/special.html';
	echo $page = template($template,$template_vars);
}
?>