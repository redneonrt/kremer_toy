<?php
/**********************************************
		File Name:	local.php
		Date:	10-6-2007
		Date Modified: 2012-10-31
		Author:	Chris Reed	
		Updated: 1-25-2008
***********************************************/

require 'include/functions.php';
/*Get the contents of the shuttle status page*/
$server_ip = server_address();
$shuttle_status = file_get_contents('http://'.$server_ip.'/index.php/shuttle_status');
//$shuttle_status = file_get_contents('http://cottensinc.com/index.php/shuttle_status');
$nav = nav_buttons(array("Main" => "index.php",
						 "Local Order" => "local.php",
						 "Other DC" => "other.php",
						 "Factory Order" => "special.php",
						 "Search" => "advanced_search.php",
						 "Review Orders" => "tracking.php",
						 "Receiving" => "receiving.php",
						 "Report a problem" => "../index.php/incident_report/"
						  ));
$message = "Local Orders";
$form_header = "&nbsp;";
$select_options = select_option($_POST,$store_info['number'],"single");
//$db_records = db_records("local",$store_info['number']);
$error = false;
$continue = false;
$title = "Local Order";

if(isset($_POST['submit']))
{	
	if($_POST['submit'] == "Continue")
	{
		$continue = true;
	}
	
	if(empty($_POST['location']))
	{
		$form_header = "<font color=\"red\">Where are you ordering it from?</font>";
		$form_array = array("line_code" => $_POST['line_code'],"part_num" => $_POST['part_num'],"qty" => $_POST['qty'],"emp" => $_POST['emp'],"name" => $_POST['name'],"number" => $_POST['number'],"notes" => $_POST['notes'],"time_h" => $_POST['time_h'],"time_m" => $_POST['time_m'], "time_ampm" => $_POST['time_ampm']);
		$error = true;
	}
	if(empty($_POST['line_code']) or empty($_POST['part_num']) or empty($_POST['qty']) or empty($_POST['emp']) or empty($_POST['name']) )
	{
		$form_header = '<font color="red">Please Fill Out Form</font>';
		$form_array = array("line_code" => $_POST['line_code'],"part_num" => $_POST['part_num'],"qty" => $_POST['qty'],"emp" => $_POST['emp'],"name" => $_POST['name'],"number" => $_POST['number'],"notes" => $_POST['notes'],"time_h" => $_POST['time_h'],"time_m" => $_POST['time_m'], "time_ampm" => $_POST['time_ampm']);
		$error = true;
	}
	elseif(!is_numeric($_POST['emp']))
	{
		$form_header = "<font color=\"red\">Employee number must be numeric!</font>";
		$form_array = array("line_code" => $_POST['line_code'],"part_num" => $_POST['part_num'],"qty" => $_POST['qty'],"emp" => $_POST['delete'],"name" => $_POST['name'],"number" => $_POST['number'],"notes" => $_POST['notes'],"time_h" => $_POST['time_h'],"time_m" => $_POST['time_m'], "time_ampm" => $_POST['time_ampm']);
		$error = true;
	}
	elseif(!is_numeric($_POST['qty']))
	{
		$form_header = "<font color=\"red\">Quantity must be numeric!</font>";
		$form_array = array("line_code" => $_POST['line_code'],"part_num" => $_POST['part_num'],"qty" => $_POST['delete'],"emp" => $_POST['emp'],"name" => $_POST['name'],"number" => $_POST['number'],"notes" => $_POST['notes'],"time_h" => $_POST['time_h'],"time_m" => $_POST['time_m'], "time_ampm" => $_POST['time_ampm']);
		$error = true;		
	}
	elseif($_POST['location'] == strtoupper($store_info['name']))
	{
		$form_header = "<font color=\"red\">Why would you order a part from yourself?</font>";
		$form_array = array("line_code" => $_POST['line_code'],"part_num" => $_POST['part_num'],"qty" => $_POST['qty'],"emp" => $_POST['emp'],"name" => $_POST['name'],"number" => $_POST['number'],"notes" => $_POST['notes'],"time_h" => $_POST['time_h'],"time_m" => $_POST['time_m'], "time_ampm" => $_POST['time_ampm']);
		$error = true;		
	}
	elseif(!$error)
	{
		unset($_POST['submit']);
		if(empty($_POST['prebill']))
		{
			$_POST['prebill'] = 'F';
		}
		//make all entries uppercase letters	
		foreach($_POST as $key => $var)
		{
			$var = strtoupper($var);
		}
		//Check to see if the time was set, otherwise set time to now
		if(empty($_POST['time_h']) || empty($_POST['time_m']) || empty($_POST['time_ampm']) )
		{
			$_POST['time'] = date("Y-m-d H:i:s");
			unset($_POST['time_h'],$_POST['time_m'],$_POST['time_ampm']);
		}
		else
		{
			$check_time = strtotime($_POST['time_h'].":".$_POST['time_m']." ".$_POST['time_ampm']);
			if($check_time)
			{
				$_POST['time'] = date("Y-m-d H:i:s",$check_time);
				unset($_POST['time_h'],$_POST['time_m'],$_POST['time_ampm']);
			}
			else
			{
				$_POST['time'] = date("Y-m-d H:i:s");
				unset($_POST['time_h'],$_POST['time_m'],$_POST['time_ampm']);
			}
		}
		//Get the class of the part number you are trying to insert
		$_POST['class'] = class_lookup($_POST['line_code'],$_POST['part_num']);
		//insert order into DB
		$sql = insert_into($_POST,"order");
		$order = mysql_query($sql) or die(mysql_error());
		if(!$order)
		{
			$form_header = "<font color=\"red\">There has been a MySQL error<br>Contact Chris Reed</font>";
		}
		else
		{
			$order_id = mysql_insert_id();
			//$form_header = "<a href=\"javascript:popUp('print.php?id=".$order_id."&type=1')\">Order has been saved click here to print<a>";
			//$form_header = "<a href=\"javascript:popUp('edit.php?id=".$order_id."')\"><p>Order has been saved click here to REVIEW</p><a>";
			$form_header = '<a href="edit.php?id='.$order_id.'" onclick="return hs.htmlExpand(this,{objectType:\'iframe\',width:900, height:650 })"><font color="yellow">Order has been saved click here to review</font></a>';
		}
	}
}
if($error)
{
	//$select_options = select_option($_POST,$store_info['number'],"remember");
	$select_options = create_select($store_info['select'],$_POST['location']);
	$prebill = pre_bill_checkbox($_POST);
	$template_vars = array("STORE_SELECT" => $ss_html,
		"TITLE" => $title,
		"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
		"IMAGE" => HEADER_IMAGE,
		"IMAGE_TEXT" => HEADER_TEXT,
		"NAV_LINKS" => $nav,
		"MESSAGE" => $message,
		"FORM_HEADER" => $form_header,
		"SELECT_OPTIONS" => $select_options,
		"LINE_CODE_VALUE" => $form_array['line_code'],
		"PART_NUMBER_VALUE" => $form_array['part_num'],
		"QTY_VALUE" => $form_array['qty'],
		"EMP_VALUE" => $form_array['emp'],
		"NAME_VALUE" => $form_array['name'],
		"NUM_VALUE" => $form_array['number'],
		"NOTES_VALUE" => $form_array['notes'],
		"H" => $form_array['time_h'],
		"M" => $form_array['time_m'],
		"AMPM" => $form_array['time_ampm'],
		"TYPE" => "local",
		"STORE" => $store_info['number'],
/*		"DB_RECORDS" => $db_records,
*/		"FOOTER" => FOOTER,
		"PREBILL" => $prebill,
		"SHUTTLE_STATUS_TABLE" => $shuttle_status,
		"AJAX:RELOAD" => AJAX_RELOAD
		);
	$template = 'html/local.html';
	echo $page = template($template,$template_vars);
}
if(!$error)
{
	if($continue)	
	{	
		if(isset($_POST['time_h'])){
				$time_h = $_POST['time_h'];
				$time_m = $_POST['time_m'];
				$time_ampm = $_POST['time_ampm'];
		}
			
		$prebill = pre_bill_checkbox($_POST);
		//$select_options = select_option($_POST,$store_info['number'],"remember");
		$select_options = create_select($store_info['select'],$_POST['location']);
		$template_vars = array("STORE_SELECT" => $ss_html,
			"TITLE" => $title,
			"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
			"IMAGE" => HEADER_IMAGE,
			"IMAGE_TEXT" => HEADER_TEXT,
			"NAV_LINKS" => $nav,
			"MESSAGE" => $message,
			"FORM_HEADER" => $form_header,
			"SELECT_OPTIONS" => $select_options,
			"LINE_CODE_VALUE" => "",
			"PART_NUMBER_VALUE" => "",
			"QTY_VALUE" => "",
			"EMP_VALUE" => $_POST['emp'],
			"NAME_VALUE" => $_POST['name'],
			"NUM_VALUE" => $_POST['number'],
			"NOTES_VALUE" => $_POST['notes'],
			"H" => $time_h,
			"M" => $time_m,
			"AMPM" => $time_ampm,
			"TYPE" => "local",
			"STORE" => $store_info['number'],
/*			"DB_RECORDS" => $db_records,
*/			"FOOTER" => FOOTER,
			"PREBILL" => $prebill,
			"SHUTTLE_STATUS_TABLE" => $shuttle_status,
			"AJAX:RELOAD" => AJAX_RELOAD
			);
		$template = 'html/local.html';
		
		echo $page = template($template,$template_vars);
	}
	else
	{
	//variables for the first time the page loads or the form is reset or the order is processed successfully
	
	//Set the inital time
	$h = "";//date("h");
	$m = "";//date("i");
	$ampm = "";//date("a");
	$select_options = select_option($_POST,$store_info['number'],"single");
	$select_options = create_select($store_info['select'],false);
	$template_vars = array("STORE_SELECT" => $ss_html,
		"TITLE" => $title,
		"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
		"IMAGE" => HEADER_IMAGE,
		"IMAGE_TEXT" => HEADER_TEXT,
		"NAV_LINKS" => $nav,
		"MESSAGE" => $message,
		"FORM_HEADER" => $form_header,
		"SELECT_OPTIONS" => $select_options,
		"LINE_CODE_VALUE" => "",
		"PART_NUMBER_VALUE" => "",
		"QTY_VALUE" => "",
		"EMP_VALUE" => "",
		"NAME_VALUE" => "",
		"NUM_VALUE" => "",
		"NOTES_VALUE" => "",
		"TIME" => "",
		"H" => $h,
		"M" => $m,
		"AMPM" => $ampm,
		"TYPE" => "local",
		"STORE" => $store_info['number'],
/*		"DB_RECORDS" => $db_records,
*/		"FOOTER" => FOOTER,
		"PREBILL" => "<input class=\"enter\" name=\"prebill\" type=\"checkbox\" id=\"prebill\" value=\"T\"/>",
		"SHUTTLE_STATUS_TABLE" => $shuttle_status,
		"AJAX:RELOAD" => AJAX_RELOAD
						);
	$template = 'html/local.html';
	echo $page = template($template,$template_vars);
	}
}
//	var_dump($_POST);
//var_dump($_SESSION);
//var_dump($store_info);
?>