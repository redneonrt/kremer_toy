<?php
/**********************************************
		File Name:	misc_receiving.php
		Date:	11-13-2007
		Author:	Chris Reed	
***********************************************/
require 'include/functions.php';
$nav = nav_buttons(array("Main" => "index.php","Receiving" => "receiving.php"));
$message = "This is only to enter parts that are received and can't be found in synergy";
$form_header = "&nbsp;";
$error =  false;
$title = "Misc Receiving";
if(isset($_POST['submit']))
{
	if(empty($_POST['location']) or empty($_POST['emp']) or empty($_POST['line_code']) or empty($_POST['part_num']) or empty($_POST['qty']) )
	{
		$form_header = "<font color=\"#ffb20f\">Please Fill Out Form<font>";
		$form_array = array($_POST['location'],$_POST['emp'],$_POST['line_code'],$_POST['part_num'],$_POST['qty'],$_POST['qty'],$_POST['notes']);
		$error = true;
	}
	elseif(!is_numeric($_POST['emp']))
	{
		$form_header = "<font color=\"#ffb20f\">Employee numer must be a number<font>";
		$form_array = array($_POST['location'],$_POST['DELETE'],$_POST['line_code'],$_POST['part_num'],$_POST['qty'],$_POST['notes']);
		$error = true;
	}
	elseif(!is_numeric($_POST['qty']))
	{
		$form_header = "<font color=\"#ffb20f\">Quantity must be a number<font>";
		$form_array = array($_POST['location'],$_POST['emp'],$_POST['line_code'],$_POST['part_num'],$_POST['DELETE'],$_POST['notes']);
		$error = true;
	}
	else
	{
		unset($_POST['submit']);
		$sql = insert_into($_POST,"misc_receiving");
		$order = mysql_query($sql);
		if(!$order)
		{
			$form_header = "<font color=\"#ffb20f\"><strong>There has been a MySQL error<br>Contact you Administator<strong><font>";
		}
		else
		{
			$form_header = "<font color=\"#ffb20f\">Order has been saved</font>";
		}
	}	
}
//Time to build the page layout
if($error) //with error
{
	$template_vars = array("STORE_SELECT" => $ss_html,
		"TITLE" => $title,
		"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
		"IMAGE" => HEADER_IMAGE,
		"IMAGE_TEXT" => HEADER_TEXT,
		"NAV_LINKS" => $nav,
		"MESSAGE" => $message,
		"FORM_HEADER" => $form_header,
		"LINE_CODE_VALUE" => $form_array['2'],
		"PART_NUMBER_VALUE" => $form_array['3'],
		"QTY_VALUE" => $form_array['4'],
		"EMP_VALUE" => $form_array['1'],
		"NOTES_VALUE" => $form_array['5'],
		"LOCATION_VALUE" => $form_array['0'],
		"FOOTER" => FOOTER,
		"STORE_NUMBER" => $store_info['number']
		);
	$template = 'html/misc_receiving.html';
	echo $page = template($template,$template_vars);
}
else //no errors
{
	$template_vars = array("STORE_SELECT" => $ss_html,
		"TITLE" => $title,
		"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
		"IMAGE" => HEADER_IMAGE,
		"IMAGE_TEXT" => HEADER_TEXT,
		"NAV_LINKS" => $nav,
		"MESSAGE" => $message,
		"FORM_HEADER" => $form_header,
		"LINE_CODE_VALUE" => '',
		"PART_NUMBER_VALUE" => '',
		"QTY_VALUE" => '',
		"EMP_VALUE" => '',
		"NOTES_VALUE" => '',
		"LOCATION_VALUE" => '',
		"FOOTER" => FOOTER,
		"STORE_NUMBER" => $store_info['number']
		);
	$template = 'html/misc_receiving.html';
	echo $page = template($template,$template_vars);
}
?>