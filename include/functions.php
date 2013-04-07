<?php
/**********************************************
		File Name:	functions.php
		Date:	10-5-2007
		Date Modified: 2012-10-31
		Author:	Chris Reed	
***********************************************/
//Set Time zone to cetral us time
//date_default_timezone_set('US/Central');
//Change Session lifetime to 8 hrs
//ini_set("session.gc_maxlifetime", "28800");
//MySQL Connection Properties
mysql_connect('localhost','cottens_mysql','cottens!napa') or die(mysql_error());
$db = 'cottens_synergy';
//$db = 'cottens_synergy_test';  //Test db ONLY!!!
mysql_select_db($db) or die(mysql_error());
//Creates the nav tablle + buttons
function nav_buttons($buttons){
	$table[] = "<table class=\"nav\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	foreach($buttons as $key => $var)
	{
		$table[] = "<tr>\n	<td><input class=\"nav_button\" value=\"".$key."\" type=\"button\" onclick=\"window.location='".$var."'\"/></td>\n</tr>";
	}
	$table[] = "</table>";
	return implode("\n",$table);
}

//Builds and parses the given template for display
function template($template, $values)
{
	$tags = array();
	$rep = array();

	foreach ($values as $key => $value) {
		$tags[] = '{' . $key . '}';
		$rep[] = $value;
	}
	$template = file_get_contents($template);
	$page = str_replace($tags, $rep, $template);
	return $page;
}
//User Location V2 Based on user login//////////**********************
/*
-------------------------------IMPORTANT-------------------------------
-=====================================================================-
If you are adding a new store, make sure to update the interstore QUERY 
on the Shuttle manager parts_page.php

===================IMPORTANT===========================================
*/
function user_location($user_name){
	
		/*********************************************************************

			Order location array
			Master list of all local places to order parts from
			***IMPORTANT*** DO NOT CHANGE THE ORDER OF ENTRIES - peoeple are starting to memorize them

		**********************************************************************/
		$master_order_from_list = array("MIN",
										"Anoka",
										"Osseo",
										"SLP",
										"Prior Lake",
										"Buffalo",
										"Rogers",
										"St. Michael",
										"Main Counter(MIN)",
										"MIN OVERNIGHT",
										"MIN DC ANEX",
										"OWA",
										"SP",
										"Apple Valley",
										"Bloomington",
										"Brooklyn Center",
										"Burnsville",
										"Cambridge",
										"Chaska",
										"Cokato",
										"Columbia Heights - NE",
										"Corcoran",
										"Delano",
										"Eagan",
										"Eden Prairie",
										"Elk River",
										"Forest Lake",
										"Hudson WI",
										"Jordan",
										"Little Canada",
										"Long Lake",
										"Maplewood",
										"Minneapolis - lake st.",
										"Minneapolis - Lyndale",
										"Monticello",
										"Plymouth",
										"Shakopee",
										"Spring Park",
										"St. Paul - Kasota Ave.",
										"St. Paul - Midway",
										"St. Paul - Suburban Ave.",
										"Stillwater",
										"West St. Paul",
										"White Bear Lake",
										"1 800 Radiator",
										"Brake and Equipment",
										"CertiFit",
										"Delegard Tool",
										"Dixie Electric",
										"Factory Motor Parts",
										"Jobbers Wharehouse",
										"Metric Auto Parts",
										"Motors by Gosh",
										"Pioneer Wheel and Rim",
										"Proven Force",
										"Rigid Hitch",
										"US Autoforce",
										"Wilson (BBB)",
										"OTHER"
										);
										
										
	if($user_name == 'anoka'){
			return array("name" => "Anoka","number" => "228","address" => "733 East River Rd.<br>Anoka, MN 55303","phone" => "763-421-3300","select" => $master_order_from_list);
	}
	else if($user_name == 'osseo'){
			return array("name" => "Osseo","number" => "229","address" => "224 County Rd. 81<br>Osseo, MN 55362","phone" => "763-425-4488","select" => $master_order_from_list);
	}
	else if($user_name == 'slp'){
			return array("name" => "SLP","number" => "230","address" => "8462 Center Dr. NE<br>Spring Lake Park, MN 55432","phone" => "763-784-1162","select" => $master_order_from_list);
	}
	else if($user_name == 'priorlake'){
		return array("name" => "Prior Lake","number" => "341","address" => "5137 Gateway St. NE<br>Prior Lake, MN 55372","phone" => "952-447-2196","select" => $master_order_from_list);
	}
	else if($user_name == 'buffalo'){
		return array("name" => "Buffalo","number" => "231","addres" => "111 Division St. E<br>Buffalo, MN 55313","phone" => "763-682-3077","select" => $master_order_from_list);
	}
	else if($user_name == 'rogers'){
		return array("name" => "Rogers","number" => "232","addres" => "21075 Diamond Lake Road South, Rogers, MN 55374","phone" => "763-428-6272","select" => $master_order_from_list);
	}
	else if($user_name == 'stmichael'){
			return array("name" => "St. Michael","number" => "233","addres" => "400 Central Ave St. Michael, MN 55376","phone" => "763-497-2400","select" => $master_order_from_list);
	}
	else if($user_name == 'redneonrt'|| $user_name == 'pr'|| $user_name == 'allstores'){
			//Default to anoka
			return array("name" => "Anoka","number" => "228","address" => "733 East River Rd.<br>Anoka, MN 55303","phone" => "763-421-3300","select" => $master_order_from_list);
	}
	else{
		echo "INVALID USER LOGIN";
		die();
	}
}
//Used to get the total number of orders being tracked
function db_records($type,$store_number)
{
	switch($type)
	{
		case "local":
			$query = mysql_query("SELECT `id` FROM `order` WHERE `type` = 'local' AND `store` = '$store_number' AND `qty` != '0' ");
			$number = mysql_num_rows($query);
			break;
		case "other":
			$query = mysql_query("SELECT `id` FROM `order` WHERE `type` = 'other' AND `store` = '$store_number' AND `qty` != '0' ");
			$number = mysql_num_rows($query);
			break;
		case "special":
			$query = mysql_query("SELECT `id` FROM `order` WHERE `type` = 'special' AND `store` = '$store_number' AND `qty` != '0' ");
			$number = mysql_num_rows($query);
			break;
	}
	if($number > 0)
	{
		return $number;
	}
	else
	{
		return 0;
	}
}
//This will create the date for the shuttle run query, current day + previous day
function shuttle_search()
{
	$hours72 = strtotime("-3 days");
	$yesterday = strtotime("-2 days");
	$today = time();
	return array("yesterday" => date('Y-m-d-H:i:s',$yesterday),"now" => date('Y-m-d-H:i:s'),"hours72" => date('Y-m-d-H:i:s',$hours72));
}
//Advanced Search Query, this will clean up the submited data and return a viable MySQL statement
function adv_search($array,$store_info,$time_limit)
{
	if($time_limit){
		$time_limit = date("Y-m-d H:i:s",strtotime("-1 month"));
	}
	else{
		$time_limit = date("Y-m-d H:i:s",strtotime("-10 years"));
		unset($array['ext_search']);
	}	

	$search_array = array("-","/","'","\\");
	foreach($array as $var)
	{
		if(empty($var))
		{
			$var = false;
		}
	}
	foreach($array as $key => &$var)
	{
		$var = str_replace($search_array,"",$var);
	}
	unset($array['search']);
	$search_array = array_filter($array);
	if(!empty($search_array))
	{
		foreach($search_array as $key => $value)
		{
			$new_array[] = "AND `".$key."` LIKE '%".$value."%'";
		}
		$search_string = implode(" ",$new_array);
		$sql = "SELECT * FROM `order` WHERE `time` > '$time_limit' AND `store` ='".$store_info."' ".$search_string." ORDER BY `time` DESC";
		return array("sql" =>$sql,"error" => false);
	}
	else
	{
		return array("error" => true);
	}
}
//advanced search for misc receiving table
function adv_search_mr($array,$store_info)
{
	$time_limit = date("Y-m-d H:i:s",strtotime("-1 month"));
	$search_array = array("-","/","'","\\");
	foreach($array as $var)
	{
		if(empty($var))
		{
			$var = false;
		}
	}
	foreach($array as $key => &$var)
	{
		$var = str_replace($search_array,"",$var);
	}
	unset($array['search']);
	$search_array = array_filter($array);
	if(!empty($search_array))
	{
		foreach($search_array as $key => $value)
		{
			$new_array[] = "AND `".$key."` LIKE '%".$value."%'";
		}
		$search_string = implode(" ",$new_array);
		$sql = "SELECT * FROM `misc_receiving` WHERE `time` > '$time_limit' ".$search_string." ORDER BY `time` DESC";
		return array("sql" =>$sql,"error" => false);
	}
	else
	{
		return array("error" => true);
	}
}
//This creates a properly formated insert into query
function insert_into($array,$table)
{
	foreach($array as $var => $num)
	{
		if(empty($var))
		{
			$var = false;
		}
	}
	//Begin Character Filter***************************************************************************
	$search_array = array("-","/","'","\\");
	//Save time, so it does not get filtered
	if(isset($array['time']))
	{
		if(!empty($array['time']))
		{
			$time = $array['time'];
			$save_time = true;
		}
	}	
	foreach($array as $key => &$var)
	{
		$var = str_replace($search_array,"",$var);
	}
	//Fill the time slot back in with the untouched value
	if(isset($save_time)){
		if($save_time)
		{
			$array['time'] = $time;
		}
	}
	//End Character Filter*****************************************************************************
	$query_array = array_filter($array);
	if(!empty($query_array))
	{
		foreach($query_array as $key => $value)
		{
			$field[] = $key;
			$string[] = strtoupper($value);
		}
	}
	//var_dump($field,$string);
	$field = implode(",",$field);
	foreach($string as &$var)
	{
		$num = strlen($var);
		$var = str_pad($var,$num + 2,"'",STR_PAD_BOTH);
	}
	$string = implode(",",$string);
	$sql = "INSERT INTO `$table` ($field) VALUES ($string)";
	return $sql;	
}
function pre_bill_checkbox($_POST)
{
	if(isset($_POST['prebill'])){
		if($_POST['prebill'] == 'T')
		{
			return $prebill = "<input class\"enter\"  name=\"prebill\" type=\"checkbox\" id=\"prebill\" value=\"T\" checked=\"checked\" />";
		}else{
			return $prebill = "<input class\"enter\" name=\"prebill\" type=\"checkbox\" id=\"prebill\" value=\"T\"/>";
		}
	}
	else
	{
		return $prebill = "<input class\"enter\" name=\"prebill\" type=\"checkbox\" id=\"prebill\" value=\"T\"/>";
	}
	
}
function select_option($_POST,$store_number,$type)
{	
	//type -- single- just displays the defgault selection box
	//type -- remember - remembers the users choice of store selection
	if($store_number == 341 || $store_number == 231)
	{
		if($type == "single")
		{
			$select_options = "
			<option></option>\n
			<option value=\"MIN\">MIN</option>\n
			<option value=\"OWA\">OWA</option>\n
			<option value=\"ANOKA\">Anoka</option>\n
            <option value=\"OSSEO\">Osseo</option>\n
            <option value=\"SLP\">SLP</option>\n
            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
            <option value=\"BUFFALO\">Buffalo</option>\n
			";
		}
		elseif($type == "remember")
		{
			if($_POST['location'] == "MIN")
			{
				$select_options = "
				<option selected=\"selected\">MIN</option>\n
				<option value=\"OWA\">OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($_POST['location'] == "OWA")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option value=\"OWA\" selected=\"selected\">OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($_POST['location'] == "ANOKA")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option value=\"OWA\">OWA</option>\n
				<option value=\"ANOKA\"selected=\"selected\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($_POST['location'] == "OSSEO")
			{
				$select_options = "
				<option>MIN</option>\n
				<option>OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option selected=\"selected\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($_POST['location'] == "SLP")
			{
				$select_options = "
				<option>MIN</option>\n
				<option>OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option selected=\"selected\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($_POST['location'] == "PRIOR LAKE")
			{
				$select_options = "
				<option>MIN</option>\n
				<option>OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\" selected=\"selected\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($_POST['location'] == "BUFFALO")
			{
				$select_options = "
				<option>MIN</option>\n
				<option>OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\"> selected=\"selected\"Buffalo</option>\n
				";
			}
			elseif(empty($_POST['location']))
			{
				$select_options = "
				<option></option>\n
				<option>MIN</option>\n
				<option>OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
		}
	}
	else
	{
		if($type == "single")
		{
			$select_options = "
			<option></option>\n
			<option>MIN</option>\n
			<option value=\"ANOKA\">Anoka</option>\n
            <option value=\"OSSEO\">Osseo</option>\n
            <option value=\"SLP\">SLP</option>\n
            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
            <option value=\"BUFFALO\">Buffalo</option>\n
			";
		}
		elseif($type == "remember")
		{
			if($_POST['location'] == "MIN")
			{
				$select_options = "
				<option selected=\"selected\">MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
	            <option value=\"OSSEO\">Osseo</option>\n
	            <option value=\"SLP\">SLP</option>\n
	            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
	            <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($_POST['location'] == "ANOKA")
			{
				$select_options = "
				<option>MIN</option>\n
				<option value=\"ANOKA\" selected=\"selected\">Anoka</option>\n
	            <option value=\"OSSEO\">Osseo</option>\n
	            <option value=\"SLP\">SLP</option>\n
	            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
	            <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($_POST['location'] == "OSSEO")
			{
				$select_options = "
				<option>MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
	            <option value=\"OSSEO\"selected=\"selected\">Osseo</option>\n
	            <option value=\"SLP\">SLP</option>\n
	            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
	            <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($_POST['location'] == "SLP")
			{
				$select_options = "
				<option>MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
	            <option value=\"OSSEO\">Osseo</option>\n
	            <option selected=\"selected\">SLP</option>\n
	            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
	            <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($_POST['location'] == "PRIOR LAKE")
			{
				$select_options = "
				<option>MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
	            <option value=\"OSSEO\">Osseo</option>\n
	            <option value=\"SLP\">SLP</option>\n
	            <option selected=\"selected\">Prior Lake</option>\n
	            <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($_POST['location'] == "BUFFALO")
			{
				$select_options = "
				<option>MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
	            <option value=\"OSSEO\">Osseo</option>\n
	            <option value=\"SLP\">SLP</option>\n
	            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
	            <option selected=\"selected\">Buffalo</option>\n
				";
			}
			elseif(empty($_POST['location']))
			{
				$select_options = "
				<option></option>\n
				<option>MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
		}
	}
	return $select_options;			
}
function account_name($customer)
{
	if(is_numeric($customer))
	{
		$lookup = mysql_query("SELECT * FROM `customer_list` WHERE `cust_number` = '$customer'");
		if($lookup)
		{
			if(mysql_num_rows($lookup) == 1)
			{
				while($row = mysql_fetch_assoc($lookup))
				{
					$customer_name = $row['cust_name'];
					$customer_number = $row['cust_number'];
				}
				return $customer_number." ".$customer_name;
			}
			else
			{
				return $customer;
			}
		}
		else
		{
			return $customer;
		}
	}
	else
	{
		return $customer;
	}
}
function edit_local_option($location,$store_number)
{	
	if($store_number == 341 || $store_number == 231)
	{
			if($location == "MIN")
			{
				$select_options = "
				<option selected=\"selected\" value=\"MIN\">*MIN</option>\n
				<option value=\"OWA\">OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($location == "OWA")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option selected=\"selected\" value=\"OWA\">*OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($location == "ANOKA")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option value=\"OWA\">OWA</option>\n
				<option selected=\"selected\" value=\"ANOKA\">*Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($location == "OSSEO")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option value=\"OWA\">OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option selected=\"selected\" value=\"OSSEO\">*Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($location == "SLP")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option value=\"OWA\">OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option selected=\"selected\" value=\"SLP\">*SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($location == "PRIOR LAKE")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option value=\"OWA\">OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option selected=\"selected\" value=\"PRIOR LAKE\">*Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($location == "BUFFALO")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option value=\"OWA\">OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option selected=\"selected\" value=\"BUFFALO\">*Buffalo</option>\n
				";
			}
			elseif(empty($location))
			{
				$select_options = "
				<option></option>\n
				<option value=\"MIN\">MIN</option>\n
				<option value=\"OWA\">OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			else
			{
				$select_options = "
				<option></option>\n
				<option value=\"MIN\">MIN</option>\n
				<option value=\"OWA\">OWA</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
	}
	else
	{
			if($location == "MIN")
			{
				$select_options = "
				<option selected=\"selected\" value=\"MIN\">*MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
	            <option value=\"OSSEO\">Osseo</option>\n
	            <option value=\"SLP\">SLP</option>\n
	            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
	            <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($location == "ANOKA")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option selected=\"selected\" value=\"ANOKA\">*Anoka</option>\n
	            <option value=\"OSSEO\">Osseo</option>\n
	            <option value=\"SLP\">SLP</option>\n
	            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
	            <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($location == "OSSEO")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
	            <option selected=\"selected\" value=\"OSSEO\">*Osseo</option>\n
	            <option value=\"SLP\">SLP</option>\n
	            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
	            <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($location == "SLP")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
	            <option value=\"OSSEO\">Osseo</option>\n
	            <option selected=\"selected\" value=\"SLP\">*SLP</option>\n
	            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
	            <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($location == "PRIOR LAKE")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
	            <option value=\"OSSEO\">Osseo</option>\n
	            <option value=\"SLP\">SLP</option>\n
	            <option selected=\"selected\" value=\"PRIOR LAKE\">*Prior Lake</option>\n
	            <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			elseif($location == "BUFFALO")
			{
				$select_options = "
				<option value=\"MIN\">MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
	            <option value=\"OSSEO\">Osseo</option>\n
	            <option value=\"SLP\">SLP</option>\n
	            <option value=\"PRIOR LAKE\">Prior Lake</option>\n
	            <option selected=\"selected\" value=\"BUFFALO\">*Buffalo</option>\n
				";
			}
			elseif(empty($location))
			{
				$select_options = "
				<option></option>\n
				<option value=\"MIN\">MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
			else
			{
				$select_options = "
				<option></option>\n
				<option value=\"MIN\">MIN</option>\n
				<option value=\"ANOKA\">Anoka</option>\n
                <option value=\"OSSEO\">Osseo</option>\n
                <option value=\"SLP\">SLP</option>\n
                <option value=\"PRIOR LAKE\">Prior Lake</option>\n
                <option value=\"BUFFALO\">Buffalo</option>\n
				";
			}
	}
	return $select_options;			
}
function update_query($array)
{
	unset($array['save_changes']); //dont want to have this processed
	
	//remove id from array and create new variable
	$id = $array['id']; 
	unset($array['id']);
/*	foreach($array as $key => $var)
	{
		if(empty($key))
		{
			$key = false;
		}
	}
*/	//Start Char Filter*************************************************
	$search_array = array("-","/","'","\\");
	if(isset($array['qty']))
	{
		if($array['qty'] == 0)
		{
			$qty = 0;
			$save_qty = true;
		}
	}
	if(isset($array['time']))
	{
		if(!empty($array['time']))
		{
			$time = $array['time'];
			$save_time = true;
		}
	}
	foreach($array as $key => $var)
	{
		$clean_array[$key] = str_replace($search_array,"",$var);
	}
	if(isset($save_qty)){
		if($save_qty)
		{
			$clean_array['qty'] = $qty;
		}
	}
	if(isset($save_time)){
		if($save_time)
		{
			$clean_array['time'] = $time;
		}
	}
	//End Char Filter***************************************************
	//$update_array = array_filter($clean_array);
	if(!empty($clean_array))
	{
		foreach($clean_array as $key => $var)
		{
			$field[] = $key." = '".strtoupper(trim($var))."'";
		}
	}
	$field = implode(",",$field);
	$sql = "UPDATE `order` SET ".$field." WHERE id = ".$id." LIMIT 1";
	//echo $sql;
	return $sql;
}
function shuttle_buttons($store)
{
	if($store == 228)
	{
		$b1 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"MIN DC\" />";
		$b2 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Osseo\" />";
		$b3 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"SLP\" />";
		$b4 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Prior Lake\" />";
		$b5 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Buffalo\" />";
		$b6 = "&nbsp;";
	}
	elseif($store == 229)
	{
		$b1 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"MIN DC\" />";
		$b2 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Anoka\" />";
		$b3 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"SLP\" />";
		$b4 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Prior Lake\" />";
		$b5 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Buffalo\" />";
		$b6 = "&nbsp;";
	}
	elseif($store == 230)
	{
		$b1 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"MIN DC\" />";
		$b2 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Anoka\" />";
		$b3 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Osseo\" />";
		$b4 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Prior Lake\" />";
		$b5 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Buffalo\" />";
		$b6 = "&nbsp;";
	}
	elseif($store == 231)
	{
		$b1 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"MIN DC\" />";
		$b2 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"OWA DC\" />";
		$b3 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Anoka\" />";
		$b4 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Osseo\" />";
		$b5 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"SLP\" />";
		$b6 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Prior Lake\" />";
	}
	elseif($store == 341)
	{
		$b1 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"MIN DC\" />";
		$b2 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"OWA DC\" />";
		$b3 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Anoka\" />";
		$b4 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Osseo\" />";
		$b5 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"SLP\" />";
		$b6 = "<input name=\"location\" type=\"submit\" class=\"shuttle_button\" id=\"location\" value=\"Buffalo\" />";
	}
	return array("b1" => $b1,"b2" => $b2, "b3" => $b3, "b4" => $b4, "b5" => $b5, "b6" => $b6);
}
//Outputs a phone number in the proper format
function phone_number($num)
{
	if(strlen($num) > 10)
	{
		return $num;
	}
	if(!is_numeric($num))
	{
		return $num;
	}
	if(strlen($num) == 7)
	{
		$prefix = substr($num,0,3);
		$number = substr($num,3,4);
		$format = $prefix."-".$number;
		return $format;
	}
	elseif(strlen($num) == 10)
	{
		$sArea = substr($num,0,3);
		$sPrefix = substr($num,3,3);
		$sNumber = substr($num,6,4);
		$sPhone = $sArea."-".$sPrefix."-".$sNumber;
		return($sPhone);
	}
	else
	{
		return $num;
	}
}
//retreive the class of a part number
function class_lookup($line_code,$part_number)
{
	$search_array = array("-","/","'","\\");
	$c_line_code = str_replace($search_array,"",$line_code);
	$c_part_number = str_replace($search_array,"",$part_number);

	$class_query = mysql_query("SELECT `class` FROM `parts` WHERE `line_code` = '$c_line_code' AND `part_number` = '$c_part_number'");
	if($class_query)
	{
		if(mysql_num_rows($class_query) == 1)
		{
			$class = mysql_fetch_assoc($class_query);
			trim($class['class']);
			if(empty($class['class']))
			{
				$return = "NO DATA";
			}
			else
			{
				$return = trim($class['class']);
			}
		}
		else
		{
			$return = "NO DATA";
		}
	}
	else
	{
		$return = "NO DATA";
	}
	return $return;
}
//Part number description
function part_desc($line_code,$part_number)
{
	$query = mysql_query("SELECT `desc` FROM `parts` WHERE `line_code` = '$line_code' AND `part_number` = '$part_number' ");
	if($query)
	{
		if(mysql_num_rows($query) == 1)
		{	
			$desc = mysql_fetch_assoc($query);
			return $desc['desc'];
		}
		else
		{
			return $desc['desc'] = "NO INFO";
		}
	}
	else
	{
		return $desc['desc'] = "NO INFO";
	}
}
//Estimate shipping time based on DC
function dc_check($dc,$type)
{
	$lookup = mysql_query("SELECT * FROM `napa_dc` WHERE `code` = '$dc' ");
	//type 1 = validate dc entry
	if($type == 1)
	{
		if($lookup)
		{
			return true;
		}
	}
	//type 2 is for dc information lookup and return details
	if($type == 2)
	{
		if($lookup)
		{
			while($row = mysql_fetch_assoc($lookup))
			{
				$dc_code = $row['code'];
				$dc_name = $row['name'];
				$dc_state = $row['state'];
				$ship_time= $row['ship_time'];
			}
			return array("code" => $dc_code,"name" => $dc_name,"state" => $dc_state,"ship_time" => $ship_time);
		}
	}
}
//Select Menu
function create_select($name_array,$selected)
{
	$num = 0;
	if($selected == false)
	{
		$html[] = "<option selected=\"selected\"></option>";
	}
	foreach($name_array as $var)
	{
		if(strtoupper($selected) == strtoupper($var))
		{
			$html[] = "<option selected=\"selected\" value=\"".strtoupper($var)."\">*".$num.' '.$var."</option>";
		}
		else
		{
			$html[] = "<option value=\"".strtoupper($var)."\">".$num.' '.$var."</option>";
		}
		$num++;
	}
	$menu = implode("\n",$html);
	return $menu;
}
/*
Create_select
Used to create the store select menu for the phoneroom

*/
function create_select_synergy_20($store_number,$selected){
	/* Decide which stores to load will show up in the list */
	if($store_number == "228"){
		$stores = ANOKA_LOCAL;
	}
	elseif($store_number == "229"){
		$stores = OSSEO_LOCAL;
	}
	elseif($store_number == "230"){
		$stores = SLP_LOCAL;
	}
	elseif($store_number == "231"){
		$stores = BUFFALO_LOCAL;
	}
	elseif($store_number == "341"){
		$stores = PL_LOCAL;
	}
	$name_array = explode("&",$stores);
	/* If $selected is false then we will have nothing initialy selected */	
	if($selected == false)
	{
		$html[] = "<option selected=\"selected\"></option>";
	}
	/* start building the list and if $selected matches one of the choices it will be initialy selected */ 
	foreach($name_array as $var)
	{
		if(strtoupper($selected) == strtoupper($var))
		{
			$html[] = "<option selected=\"selected\" value=\"".strtoupper($var)."\">*".$var."</option>";
		}
		else
		{
			$html[] = "<option value=\"".strtoupper($var)."\">".$var."</option>";
		}
	}
	$menu = implode("\n",$html);
	return $menu;
}


function shuttle_delivery_status($id)
{
	$sql = "SELECT * FROM `ci_shuttle_part_log` WHERE `order_id` = '$id' LIMIT 1";
	$query = mysql_query($sql);
	if(mysql_num_rows($query) == 0)
	{
		//Cant find anything in the new shutlle table so...
		//check the old order table to the call back/receiving status
		$get_receiving_info = mysql_query("SELECT * FROM `order` WHERE `id` = '$id' LIMIT 1");
		$o_info = mysql_fetch_assoc($get_receiving_info);
		if($o_info['call_back'] == 'T' && $o_info['received'] == 'T')
		{
			return array('status' => '<font color="green">Received</font>','status_message' => 'Part has been received');
		}
		elseif($o_info['call_back'] == 'T')
		{
			return array('status' => '<font color="orange">Called Back</font>','status_message' => 'Part has been called back and should be on its way to the store');
		}
		elseif($o_info['received'] == 'T')
		{
			return array('status' => '<font color="green">Received</font>','status_message' => 'Part has been received');
		}
		else
		{
			return array('status' => '<font color="red">Awaiting pickup</font>','status_message' => 'No further details available');
		}
	}
	else
	{
		while($row = mysql_fetch_array($query))
		{
			//Array to translate shuttle number to name
			$shuttle_name_array = array('1' => 'Shuttle 1 (Big Blue)','2' => 'Shuttle 2 (Little Blue)', '3' => 'Shuttle 3 (Western)' ,'4' => 'Shuttle 4 (Weekend)' , '5' => 'Shuttle 5 (3PM DC Shuttle)');
			if(array_key_exists($row['shuttle_id'],$shuttle_name_array))
			{
				$shuttle_name = $shuttle_name_array[$row['shuttle_id']];
			}
			else
			{
				$shuttle_name = 'Employee '.$row['shuttle_id'];
			}
			if($row['status'] == 0)
			{
				return array('status' => '<font color="red">Awaiting pickup</font>','status_message' => 'Order may have been modified, call Chris at x1641 if your not sure what this means');
			}
			else if($row['status'] == 1)
			{
				if(empty($row['last_location']))
				{
					return array('status' => '<font color="orange">In transit</font>','status_message' => 'Picked up by '.$shuttle_name.' @ '.date('g:i a \o\n n/j',strtotime($row['time_pick_up'])).' from '.$row['pick_up_location'].'' );
				}
				else
				{
					return array('status' => '<font color="orange">In transit</font>','status_message' => 'Picked up from '.$row['pick_up_location'].' @ '.date('g:i a \o\n n/j',strtotime($row['time_pick_up'])).' <br />Picked up by '.$shuttle_name.' @ '.date('g:i a \o\n n/j',strtotime($row['time_last_update'])).' from '.$row['last_location'].'' );
				}
			}
			elseif($row['status'] == 2)
			{
				return array('status' => '<font color="yellow">Awaiting transfer</font>','status_message' => 'Picked up from '.$row['pick_up_location'].' @ '.date('g:i a \o\n n/j',strtotime($row['time_pick_up'])).' <br /> Last location was '.$row['last_location'].' @ '.date('g:i a \o\n n/j',strtotime($row['time_last_update'])).' dropped of by '.$shuttle_name.'   ');
			}
			elseif($row['status'] == 3)
			{
				return array('status' => '<font color="green">Arrived at store</font>','status_message' => 'Picked up from '.$row['pick_up_location'].' @ '.date('g:i a \o\n n/j',strtotime($row['time_pick_up'])).' <br /> Delivered to '.$row['drop_off_location'].' @ '.date('g:i a \o\n n/j',strtotime($row['time_last_update'])).' by '.$shuttle_name.' ');
				
			}
			elseif($row['status'] == 4)
			{
				return array('status' => '<font color="orange">PROBABLY </font><font color="green"> Arrived at store</font>','status_message' => 'Part was marked received at store before shuttle process was completed');
			}
			elseif($row['status'] == 5)
			{
				return array('status' => '<font color="red">Order was deleted</font>','status_message' => 'ORDER WAS DELETED');
			}
		}
	}
}
//---------------------For new shuttle manager--------------------
//Create Shuttle Status Table
function draw_shuttle_status_table_new(){
	if(date("N") == 6 || date("N") == 7){
		//Weekend
		$shuttle_array = array(4,6);
		foreach ($shuttle_array as $var){
			$sql = "SELECT * FROM `ci_shuttle_times` WHERE `shuttle_id` = '$var' ORDER BY `id` DESC";
			$query = mysql_query($sql);
			if(mysql_num_rows($query) == 0 ){
				$shuttle_data[$var] = "NO DATA - COMING SOON";
			}
			$record = mysql_fetch_assoc($query);
			if(date("Ymd") == date("Ymd",strtotime($record['timestamp']))){
			if($record['type'] == 'depart'){
				$shuttle_data[$var] = " <font color=\"ORANGE\"><strong> DEPARTED </strong></font> ".strtoupper($record['location'])." @ ".date("g:i A - m\\\d",strtotime($record['timestamp']));
			}
			else{
				$shuttle_data[$var] = "<font color=\"WHITE\"><strong> ARRIVED </strong></font>in ".strtoupper($record['location'])." @ ".date("g:i A - m\\\d",strtotime($record['timestamp']));
			}
			}
			else{
				$shuttle_data[$var] = "SHUTTLE HAS NOT STARTED YET";
			}
		}			
		$html[] = "<table width=\"100%\" title=\"Auto updates\">
			        <tr>
			          <td colspan=\"2\"><div align=\"center\">Shuttle Status</div></td>
			          </tr>
			        <tr>
			          <td>Shuttle 4 (Weekend Shuttle) - </td>
			          <td>".$shuttle_data['4']."</td>
			        </tr>
			        <tr>
			          <td>Shuttle 6 (Western Weekend) - </td>
			          <td>".$shuttle_data['6']."</td>
			        </tr>
					<tr>
					  <td colspan=\"2\"><div align=\"center\"><a href=\"http://10.113.180.4/supplier/zzzzzz.php?z=113\">Need a courier? Click here</a></div></td>
					</tr>
			      </table>";
				  
		return implode("\n",$html);
	}
	else{
		//Weekday
		$shuttle_array = array(1,2,3,5); //5 is temp 4th shuttle truck(dick s)
		foreach ($shuttle_array as $var){
			$sql = "SELECT * FROM `ci_shuttle_times` WHERE `shuttle_id` = '$var' ORDER BY `id` DESC";
			$query = mysql_query($sql);
			if(mysql_num_rows($query) == 0){
				$shuttle_data[$var] = "NO DATA - COMING SOON";
			}
			else
			{
				$record = mysql_fetch_assoc($query);
				if($record['type'] == 'depart'){
					$shuttle_data[$var] = " <font color=\"ORANGE\"><strong> DEPARTED </strong></font> ".strtoupper($record['location'])." @ ".date("g:i A - m\\\d",strtotime($record['timestamp']));
				}
				else{
					$shuttle_data[$var] = "<font color=\"WHITE\"><strong> ARRIVED </strong></font>in ".strtoupper($record['location'])." @ ".date("g:i A - m\\\d",strtotime($record['timestamp']));
				}
			
			}
		}
		//var_dump($shuttle_data);
		$html[] = "<table width=\"80%\" title=\"Auto updates\">
			        <tr>
			          <td colspan=\"2\"><div align=\"center\">Shuttle Status</div></td>
			          </tr>
			        <tr>
			          <td>Shuttle 1 (Big Blue) - </td>
			          <td>".$shuttle_data['1']."</td>
			        </tr>
			        <tr>
			          <td>Shuttle 2 (Little Blue) - </td>
			          <td>".$shuttle_data['2']."</td>
			        </tr>
			        <tr>
			          <td>Shuttle 3 (Western Run) - </td>
			          <td>".$shuttle_data['3']."</td>
			        </tr>
			        <tr>
			          <td>Shuttle 5 (Dean) - </td>
			          <td>".$shuttle_data['5']."</td>
			        </tr>
					<tr>
					  <td colspan=\"2\"><div align=\"center\"><a href=\"http://10.113.180.4/supplier/zzzzzz.php?z=113\">Need it faster? Click here for a courier</a></div></td>
					</tr>
			      </table>";
		return implode("\n",$html);
	}
}

	/*========================================================
	=	
	=	Fix fuck ups! 
	=	If the part has been marked received in the ordering
	=	store, then remove the part from the shuttle part log
	=
	=	if the order was deleted then get it out of the log
	=
	==========================================================*/
	function _clean_up_shuttle_part_log()
	{
		$shuttle_part_log_query = mysql_query("SELECT * FROM `ci_shuttle_part_log` WHERE `status` IN ('0', '1', '2')");
		while($row = mysql_fetch_assoc($shuttle_part_log_query))
		{
			//check if part was received in the store
			$check_order_query = mysql_query("SELECT * FROM `order` WHERE `id` = '".$row['order_id']."' AND `received` = 'T' LIMIT 1");
			if(mysql_num_rows($check_order_query) > 0)
			{
				//change status to 4 in the part log
				mysql_query("UPDATE `ci_shuttle_part_log` SET `status` = '4' WHERE `order_id` = '".$row['order_id']."' LIMIT 1");	
			}
			else
			{
				//order must have been deleted lets confirm
				$deleted_order_check = mysql_query("SELECT * FROM `order_delete` WHERE `id` = '".$row['order_id']."' LIMIT 1");
				if(mysql_num_rows($deleted_order_check) > 0 )
				{
					//change status to 5 in the part log
					mysql_query("UPDATE `ci_shuttle_part_log` SET `status` = '5' WHERE `order_id` = '".$row['order_id']."' LIMIT 1");	
				}
			}
		}
		//Now just to be safe, if for some reason the user changes recevid back to false and we find the order in the shuttle part log with a status of 4, then set the status to 0(awaiting pickup)
		/*$change_mind_query = mysql_query("SELECT * FROM `ci_shuttle_part_log` WHERE `status` = '4' ");
		while($rows = mysql_fetch_assoc($change_mind_query))
		{
			$another_query = mysql_query("SELECT * FROM `order` WHERE `id` = '$rows[order_id]' AND `received` = 'F'");	
			if(mysql_num_rows($another_query) > 0)
			{
				$fix_query = mysql_query("UPDATE `ci_shuttle_part_log` SET `status` = '0' WHERE `order_id` = '$rows[order_id]'");
			}
		}*/
	}
	
	//Determine the server address - This is just a bandaid until we upgrade synergy to the codeignitor framwork
	function server_address($ip = ''){
			/*if(empty($ip)){
				$ip = $_SERVER['REMOTE_ADDR'];	
			}
			$exp_ip = explode('.',$ip);
			if($exp_ip[0] == 10 || $exp_ip[0] == 192){
				return '10.113.180.4';	
			}else{
				return '72.11.110.203';
			}*/
			return $_SERVER['HTTP_HOST'];	
		
	}
	
	//Convert store # to Name
	function store_num_to_name($store_num){
		$s = array(228	=> 'Anoka',
				   229	=> 'Osseo',
				   230	=> 'SLP',
				   341	=> 'Prior Lake',
				   231	=> 'Buffalo',
				   232	=> 'Rogers',
				   233	=> 'St. Michael'
				   );
				   return $s[$store_num];
	}
//***************************************************USER VARIABLES REQUIRED THROUGHOUT SITE***************************************///
//**********************************************************************************************************************************//
//session_cache_expire(1440);
session_name('synergy_core');
session_start();
if(isset($_POST['process_logout'])){
	unset($_SESSION['logged_in']);
	unset($_SESSION['user_info']);
	session_destroy();
}
//Redirect if user is not logged in
if(!$_SESSION['logged_in']){
	header('location:login/login.php');
}
//Check if multistore user is changing stores
if(isset($_POST['multi_store_select'])){
	//$store_info = user_location($_POST['multi_store_select']);
	$_SESSION['user_info']['username'] = $_POST['multi_store_select'];
}
$store_info = user_location($_SESSION['user_info']['username']);
//Multi Store Select
//Also wh are using a time saving hack to inject some extra css sheets and some .js scripts(jquery and highslide)
$cssjs_html = '
<link rel="stylesheet" type="text/css" href="synergy_v2d.css" />
<link rel="stylesheet" type="text/css" href="http://'.server_address().'/css/highslide.css" />
<link rel="stylesheet" type="text/css" href="http://'.server_address().'/css/shuttle_status.css" />
<script type="text/javascript" src="http://'.server_address().'/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="http://'.server_address().'/js/highslide-4.1.13.full.packed.js"></script>
<script language="JavaScript" type="text/javascript">
//HighSlide Options
	hs.graphicsDir = \'/images/graphics/\';
	hs.wrapperClassName = \'titlebar\';
	hs.showCredits = false;
	hs.align = \'auto\';
	hs.outlineType = \'rounded-white\';
	hs.wrapperClassName = \'draggable-header\';
</script>
';
if($_SESSION['user_info']['multi_store'] == 'T'){
	$ss_message = 'Logged into '.$store_info['name'];
	$ss_template = file_get_contents('html/store_select.html');
	$ss = str_replace('{SS_MESSAGE}',$ss_message,$ss_template);
	$ss_html = $ss . $cssjs_html;
	//echo $ss_html;	
}else{
	$ss_html = $cssjs_html;
}
///var_dump($store_info);
/*START*//*Synergy Broadcast Message*/
$synergy_message_status = file_get_contents('http://'.server_address().'/index.php/synergy_message/message_boolean');
if($synergy_message_status == 1){
	$current_message = file_get_contents('http://'.server_address().'/index.php/synergy_message/get_current_message');
	//Hack to fix the blank message when js relaods the parent window of the message form
	/***************************************************************************************************************
	IMPORTANT - if you change any of the header text messages
	make sure you also update the synergy_message.php controller also (line 175)
	*****************************************************************************************************************/
	if(empty($current_message)){
			define("HEADER_TEXT","Cottens' NAPA - ".$store_info['name']." Store #".$store_info['number'].'<br /><a id="m_window" class="synergy_message_link" href="http://'.server_address().'/index.php/synergy_message/" onclick="return hs.htmlExpand(this,{objectType:\'iframe\',width:700, height:350 ,headingText:\'Synergy Broadcast\'})">Change or remove message</a>');
	}else{
		define("HEADER_TEXT",$current_message);
	}
}else{
	define("HEADER_TEXT","Cottens' NAPA - ".$store_info['name']." Store #".$store_info['number'].'<br /><a id="m_windows" class="synergy_message_link" href="http://'.server_address().'/index.php/synergy_message/" onclick="return hs.htmlExpand(this,{objectType:\'iframe\',width:700, height:350 ,headingText:\'Synergy Broadcast\'})">Change or remove message</a>');
}
define("AJAX_RELOAD",180 * 1000);  //interval of time (seocnds) before refreshing ajax content - 5 minutes
define("MAIN_TABLE_WIDTH",900);
//define("HEADER_IMAGE","");
define("HEADER_IMAGE","images/synergy_banner1.gif"); //NOT USESD 2012-10-30
//define("HEADER_TEXT","<strong>Cottens' NAPA</strong>");
define("FOOTER","<div id=\"current_time\">".date("g:i A l F jS, Y")."</div><br /><form action=\"\" method=\"post\"><input name=\"process_logout\" type=\"hidden\" value=\"true\" /><input class=\"button_small\" name=\"logout_button\" type=\"submit\" value=\"Log Out\" /></form><br /><font size=\"1\">Synergy Ver. 2.13 @ ".$db."<br />&copy; Chris Reed 2007-".date("Y")."</font>");
define("TIMEFRAME",date('Y-m-d-H:i:s',strtotime("-2 days")));
//var_dump($_SESSION);
//var_dump($store_info);
//echo ini_get('session.gc_maxlifetime');
_clean_up_shuttle_part_log();
//var_dump($_SERVER);
?>
