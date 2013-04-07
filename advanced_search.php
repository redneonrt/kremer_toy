<?php
/**********************************************
		File Name:	
		Date:	01-24-2008
		Author:	Chris Reed	
***********************************************/
require 'include/functions.php';
$title = "Search";
//$nav = nav_buttons(array("Main" => "index.php"));
if(isset($_SERVER['HTTP_REFERER'])){
	$referer = $_SERVER['HTTP_REFERER'];
	$nav = nav_buttons(array("Main" => "index.php",
							 "Review Orders" => "tracking.php",
							 "Receiving" => "receiving.php",
							 "Will call cleanup"	=> "will_call_cleanup.php",
							 "Report a problem" => "../index.php/incident_report/",
							 "<--Go Back" => $referer
							 ));
}
if(!isset($nav)){
	$nav = nav_buttons(array("Main" => "index.php",
							 "Review Orders" => "tracking.php",
							 "Receiving" => "receiving.php",
							 "Will callclean up"	=> "will_call_cleanup.php",
							 "Report a problem" => "../index.php/incident_report/",
							 ));
}
$hs_onclick_attr = 'onclick="return hs.htmlExpand(this,{objectType:\'iframe\',width:750, height:500})"';
$message = "Advanced Search<br>************REMEMBER************<br>Default search only goes back 1 month if you want to seach ALL the records please choose extended search below<br>";
$form_header = "Fill out as much or as little as you would like";

if(isset($_GET['search']))
{
	$_POST['search'] = 'true';
	if(isset($_GET['type'])){
		if($_GET['type'] == 'class')
		{
			$_POST['class'] = $_GET['class'];
		}
	}
	else
	{
		$_POST['line_code'] = $_GET['line_code'];
		$_POST['part_num'] = $_GET['part_num'];
		if(isset($_GET['ext_search'])){
			$_POST['ext_search'] = $_GET['ext_search'];
		}
	}
}
//not sure if I need these yet
$build_search_table = false;
$build_mr_table = false;

if(isset($_POST['update']))
{
	if(!empty($_POST['id']) || !empty($_POST['id_mr']) )
	{
		if(!empty($_POST['id']))
		{
			$number_updated = count($_POST['id']);
			foreach($_POST['id'] as $id)
			{
				$update_query = mysql_query("UPDATE `order` SET `sold` = 'T' WHERE `id` = '$id'") or die(mysql_error());
			}
		}
		if(!empty($_POST['id_mr']))
		{
			if(isset($number_updated))
			{
				$number_updated = $number_updated + count($_POST['id_mr']);
			}
			else
			{
				$number_updated = count($_POST['id_mr']);
			}
			foreach($_POST['id_mr'] as $key => $var)
			{
				$delete_query = mysql_query("DELETE FROM `misc_receiving` WHERE `id` = '$var' LIMIT 1") or die(mysql_error());
			}
		}
		if($number_updated == 1)
		{
			$message = $number_updated." Order Updated";
		}
		else
		{
			$message = $number_updated." Orders Updated";
		}
	}
}		


//See if the user is trying to reset the form
if(isset($_POST['reset']))
{
	unset($_SESSION['save_search'],$_SESSION['save_search_fields']);	//unset the ALL the saved search $_SESSION data
	
}
//See if there is a saved search in the $_SESSION array
if(isset($_SESSION['save_search']))
{
	if($_SESSION['save_search'])
	{
		$search_fields = $_SESSION['save_search_fields'];
		$do_search = true;
	}
}
//See if the user is starting a new search
if(isset($_POST['search']))
{
	if(isset($_POST['id'])){
		$n_id = $_POST['id'];
	}
	else{
		$n_id = NULL;
	}
		if(isset($_POST['emp'])){
		$n_emp = $_POST['emp'];
	}
	else{
		$n_emp = NULL;
	}
	if(isset($_POST['location'])){
		$n_location = $_POST['location'];
	}
	else{
		$n_location = NULL;
	}
	if(isset($_POST['line_code'])){
		$n_line_code = $_POST['line_code'];
	}
	else{
		$n_line_code = NULL;
	}
	if(isset($_POST['part_num'])){
		$n_part_num = $_POST['part_num'];
	}
	else{
		$n_part_num = NULL;
	}
	if(isset($_POST['name'])){
		$n_name = $_POST['name'];
	}
	else{
		$n_name = NULL;
	}
	if(isset($_POST['number'])){
		$n_number = $_POST['number'];
	}
	else{
		$n_number = NULL;
	}
	if(isset($_POST['call_back'])){
		$n_call_back = $_POST['call_back'];
	}
	else{
		$n_call_back = NULL;
	}
	if(isset($_POST['received'])){
		$n_received = $_POST['received'];
	}
	else{
		$n_received = NULL;
	}
	if(isset($_POST['call_cust'])){
		$n_call_cust = $_POST['call_cust'];
	}
	else{
		$n_call_cust = NULL;
	}
	if(isset($_POST['sold'])){
		$n_sold = $_POST['sold'];
	}
	else{
		$n_sold = NULL;
	}
	if(isset($_POST['prebill'])){
		$n_prebill = $_POST['prebill'];
	}
	else{
		$n_prebill = NULL;
	}
	if(isset($_POST['class'])){
		$n_class = $_POST['class'];
	}
	else{
		$n_class = NULL;
	}
	if(isset($_POST['ext_search'])){
		$n_ext_search = $_POST['ext_search'];
	}
	else{
		$n_ext_search = NULL;
	}

	unset($_SESSION['save_search']); //Just in case remove any previous saved search
	//Create an array with all the search fields from $_POST
	$search_fields = array("id" => $n_id,
						   "emp" => $n_emp,
						   "location" => $n_location,
						   "line_code" => $n_line_code,
						   "part_num" => $n_part_num,
						   "name" => $n_name,
						   "number" => $n_number,
						   "call_back" => $n_call_back,
						   "received" => $n_received,
						   "call_cust" => $n_call_cust,
						   "sold" => $n_sold,
						   "prebill" => $n_prebill,
						   "class" => $n_class,
						   "ext_search" => $n_ext_search
						   );
	$do_search = true;
	$_SESSION['save_search'] = true;
	$_SESSION['save_search_fields'] = $search_fields;
}

//if we have a saved search OR a new search then process the form fields and we should end up with 1 mysql query for regular search
//and maybe 1 for misc receiving
if(isset($do_search)){
	if($do_search)
	{	
		if($search_fields['ext_search'] == 'T'){
			$time_limit = false;
		}
		else{
			$time_limit = true;
		}					   
		$search = adv_search($search_fields,$store_info['number'],$time_limit);
		if($search['error'])
		{
			$build_search_table = false;
			$message = "Please fill out form";
		}
		else
		{
			$search_query = mysql_query($search['sql']);
			if($search_query)
			{	
				if(mysql_num_rows($search_query) > 0)
				{	
					$build_search_table = true;
				}
				else
				{
					$build_search_table = false;
					$message = "Sorry, Your search did not return any results";
				}
			}
			else
			{
				$build_search_table = false;
				$message = "MySQL Error";
			}
		}
		//Checks to see if any of the fields that are in the misc receiving table are in the search
		//if so search that table also
		if(!empty($search_fields['line_code']) || !empty($search_fields['part_num']) || !empty($search_fields['location']))
		{
			$search_fields_mr = array("line_code" => $search_fields['line_code'],
									  "part_num" => $search_fields['part_num'],
									  "location" => $search_fields['location']
									  );
			$search_mr = adv_search_mr($search_fields_mr,$store_info['number']);
			$misc_receiving = true; //tells the script that there is some fields to search the the misc receiving table for
			
		}
		//If there are fields to search for in the mr table then lets look for them
		if(isset($misc_receiving)){
			if($misc_receiving)
			{
				if($search_mr['error'])
				{
					$build_mr_table = false;
				}
				else
				{
					$search_mr_query = mysql_query($search_mr['sql']);
					if($search_mr_query)
					{	
						if(mysql_num_rows($search_mr_query) > 0)
						{	
							$build_mr_table = true;
							$howmany = mysql_num_rows($search_mr_query);
							//echo "Found ".$howmany." matches in the Misc Rec table";
						}
						else
						{
							$build_mr_table = false;
						}
					}
					else
					{
						$build_mr_table = false;
					}
				}
			}
		}
	}
}

//Check to see if there are any results to start drawing the table for
if($build_search_table || $build_mr_table)
{
	if($build_search_table)
	{
		$number_test = 0; //***********************needed for color change code
		while($row = mysql_fetch_array($search_query))
		{
			//start color change code*******************************
			if(isset($row_date))
			{
				if($row_date != date("m-d",strtotime($row['time'])) )
				{
					$number_test++;	
					$row_date = date("m-d",strtotime($row['time']));
				}
			}
			else
			{
				$row_date = date("m-d",strtotime($row['time']));
			}
			if(($number_test % 2) == 0)
			{
				$table_class = "adv_search_table_a";
			}
			else
			{
				$table_class = "adv_search_table_b";
			}
			//end color change code*********************************
			$date = date("m-d-y",strtotime($row['time']));
			foreach($row as $key  => &$var)
			{
				if(empty($var))
				{
					$var = "&nbsp;";
				}
			}
			if($row['sold'] == 'F')
			{
				$strings[] = "<tr>\n
							<td class=\"".$table_class."\">".$row['line_code']."</td>\n
							<td class=\"".$table_class."\">".$row['part_num']."</td>\n
							<td class=\"".$table_class."\">".account_name($row['name'])."</td>\n
							<td class=\"".$table_class."\">".$row['number']."</td>\n
							<td class=\"".$table_class."\">".$date."</td>\n
							<td class=\"".$table_class."\"><input type=\"checkbox\" name=\"id[]\" value=".$row['id']." /></td>\n
							<td class=\"".$table_class."\"><a href=\"edit.php?id=".$row['id']."&type=1\" ".$hs_onclick_attr. ">Quick<a>/<a href=\"edit.php?id=".$row['id']."&type=1\">Full<a></td>\n
		      			 </tr>\n";
			}
			elseif($row['sold'] == 'T')
			{
				$sold = "Yes";
				$strings[] = "<tr>\n
							<td class=\"".$table_class."\">".$row['line_code']."</td>\n
							<td class=\"".$table_class."\">".$row['part_num']."</td>\n
							<td class=\"".$table_class."\">".account_name($row['name'])."</td>\n
							<td class=\"".$table_class."\">".$row['number']."</td>\n
							<td class=\"".$table_class."\">".$date."</td>\n
							<td class=\"".$table_class."\">".$sold."</td>\n
							<td class=\"".$table_class."\"><a href=\"edit.php?id=".$row['id']."&type=1\" ".$hs_onclick_attr. ">Quick<a>/<a href=\"edit.php?id=".$row['id']."&type=1\" >Full<a></td>\n
		      			 </tr>\n";
			}
		}
	}
	else
	{
		//put code here to say no results found in reqular search
	}
	
	
	if($build_mr_table)
	{
		$strings[] = "<tr>\n
						<td class=\"adv_search_header\" colspan=\"7\"><div align=\"center\"><font size=\"5\">Unclaimed Receiving (".$howmany.")</font></div></td>\n
					 </tr>\n
					       <tr>\n
       						 <td class=\"adv_search_header\" >Line Code</td>\n
					         <td class=\"adv_search_header\" >Part Number</td>\n
					         <td class=\"adv_search_header\" >Current Location</td>\n
					         <td class=\"adv_search_header\" >Shipped From</td>\n
					         <td class=\"adv_search_header\" >Date</td>\n
					         <td class=\"adv_search_header\" >Notes</td>\n
					         <td class=\"adv_search_header\" >Remove?</td>\n
					       </tr>\n"
	  ;
		$number_test = 0; //***********************needed for color change code
		while($row = mysql_fetch_assoc($search_mr_query))
		{
			//start color change code*******************************
			if(isset($row_date))
			{
				if($row_date != date("m-d",strtotime($row['time'])) )
				{
					$number_test++;	
					$row_date = date("m-d",strtotime($row['time']));
				}
			}
			else
			{
				$row_date = date("m-d",strtotime($row['time']));
			}
			if(($number_test % 2) == 0)
			{
				$table_class = "adv_search_table_a";
			}
			else
			{
				$table_class = "adv_search_table_b";
			}
			//end color change code*********************************
			foreach($row as $key  => &$var)
			{
				if(empty($var))
				{
					$var = "&nbsp;";
				}
			}
			$date = date("m-d-y",strtotime($row['time']));
			$strings[] = "<tr>\n
							<td class=\"".$table_class."\">".$row['line_code']."</td>\n
							<td class=\"".$table_class."\">".$row['part_num']." (".$row['qty'].")</td>\n
							<td class=\"mr_highlight ".$table_class."\">".store_num_to_name($row['store'])."</td>\n
							<td class=\"".$table_class."\">".$row['location']."</td>\n
							<td class=\"".$table_class."\">".$date."</td>\n
							<td class=\"".$table_class."\">".$row['notes']."<a></td>\n
							<td class=\"".$table_class."\"><input name=\"id_mr[]\" type=\"checkbox\" id=\"id_mr[]\" value=".$row['id']." /></td>\n
	   		   			 </tr>\n";
		}		
	}
	else
	{
		//put code here to say nothing found in misc receiving
		
		//actually it should not be needed none of this code should be run if there are no results
	}
	
	//draw the template thingy
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
							"FOOTER" => FOOTER
						);
	$template = 'html/adv_search_b.html';
	echo $page = template($template,$template_vars);
}
else
{
	//draw the search form for the user
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
							"FOOTER" => FOOTER
						);
	$template = 'html/adv_search_a.html';
	echo $page = template($template,$template_vars);
}
//**************TESTING ONLY******************//
//			var_dump($_POST);
//			echo "<br><br>";
//			var_dump($_SESSION);
//			var_dump($build_search_table);
//			var_dump($build_mr_table);
?>





