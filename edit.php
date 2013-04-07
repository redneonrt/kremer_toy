<?php
/**********************************************
		File Name:	edit.php
		Date:	Created 12-20-2007
		Date Modified: 2012-10-31
		Author:	Chris Reed
***********************************************/
//echo $_SERVER['HTTP_REFERER'];
require 'include/functions.php';
if(isset($_SERVER['HTTP_REFERER'])){
	$referer = $_SERVER['HTTP_REFERER'];
	$nav = nav_buttons(array("Main" => "index.php","<--Go Back" => $referer));
}
if(!isset($nav)){
	$nav = nav_buttons(array("Main" => "index.php"));
}

//var_dump($_POST);
//*****************PREVENT UNATHURiZED ACCESS TO ORDERS******************************************************************************
if(isset($_GET['id']) || isset($_POST['id']) )
{
	if(isset($_GET['id']))
	{
		$auth_id = $_GET['id'];
	}
	else
	{
		$auth_id = $_POST['id'];
	}
	$auth_sql = "SELECT * FROM `order` WHERE `id` = '$auth_id'";
	$auth_query = mysql_query($auth_sql) or die(mysql_error());
	$auth_array = mysql_fetch_assoc($auth_query);
	if($auth_array['store'] != $store_info['number'] && $_SESSION['user_info']['multi_store'] != 'T')
	{
		echo "Access Denied";
		die();
	}
}



//***********************************************************************************************************************************

/************************************************************************************************************************************
*																																	*
*												THIS SECTION HANDLES $_POST REQUESTS ONLY		++++++++++							*									*																		   															*
*************************************************************************************************************************************/


/**SAVE AND UPDATE ORDERS**************************************************************************************************************/
if(isset($_POST['save_changes']))
{
	$loc_check_query = mysql_query("SELECT * FROM `order` WHERE `id` = '$_POST[id]' ");
	while($row = mysql_fetch_array($loc_check_query))
	{
		$location_check = $row['location'];
	}
	//Saves changes and updates timestamp	
	if(strtoupper($_POST['location']) != strtoupper($location_check))
	{
		//Check for empty field, that are not allowed to be empty
		if($_POST['type'] == 'local')
		{
			if(empty($_POST['location']) || empty($_POST['emp']) || empty($_POST['name']) || empty($_POST['line_code']) || empty($_POST['part_num']) )
			{
				//$content = "You can not erase required fields<br><br><a href=\"edit.php?id=".$_POST[id]."&action=edit\">Return to order</a>";
				$_GET['id'] = $_POST['id'];
				$_GET['action'] = 'edit';
				$message = "You can not erase required fields";
			}
			//make sure the user is not changing the location to their own store
			if(strtoupper($store_info['name']) == strtoupper($_POST['location']))
			{
				$_GET['id'] = $_POST['id'];
				$_GET['action'] = 'edit';
				$message = "You can not change the order location to your own store";
			}
			
			//Make sure the user isnt changeing the store #(the store that generated the order `store`) to the same location that the order is shipping from(`location`)
			$store_array = array(228 => 'Anoka',229 => 'Osseo',230 => 'SLP',341 => 'Prior Lake',231 => 'Buffalo', 232 => 'Rogers',233 => 'St. Michael');
			if(strtoupper($_POST['location']) == strtoupper($store_array[$_POST['multi_store_change']]))
			{
				$ms_error = 'Store(db field `store`) can NOT match order location(`location`)';
				//echo $ms_error;
				//die();
				$_GET['id'] = $_POST['id'];
				$_GET['action'] = 'edit';
				$message = $ms_error;

			}
			else
			{
				//var_dump($_POST);
				//Check if the user is changing the receiving status to FALSE if they are then look for the order id in the shuttle_part_log, if we find it there then change its status to 0(awating pickup)
				if($_POST['received'] == 'F')
				{
					$check_shuttle_part_log = mysql_query("SELECT * FROM `ci_shuttle_part_log` WHERE `order_id` = '".$_POST['id']."' AND `status` = '4' ");
					if(mysql_num_rows($check_shuttle_part_log) > 0)
					{
						$fix_query = mysql_query("UPDATE `ci_shuttle_part_log` SET `status` = '0' WHERE `order_id` = '".$_POST['id']."'");
					}
				}
				//Update the timestamp on current order
				$_POST['time'] = date("Y-m-d H:i:s");
				
				/***************************************/
				//Added next line so phoneroom can change the store id if they enter the order into the wrong store
				if(isset($_POST['multi_store_change'])){
					$multi_store_sql = "UPDATE `order` SET `store` = '$_POST[multi_store_change]' WHERE `id` = $_POST[id] ";
					$multi_store_update = mysql_query($multi_store_sql) or die(mysql_error());
					if($multi_store_update){
						unset($_POST['multi_store_change']);
					}
				}
				//end phoneroom addition

				
				$sql = update_query($_POST);
				$query = mysql_query($sql) or die(mysql_error());
				if($query)
				{
					//$content = "Update Success<br><br><br><font color=\"#ffea00\">Location changed<br>*ORDER TIME HAS BEEN UPDATED*</font><br><br><a href=\"edit.php?id=".$_POST[id]."\">Return to order</a><br><br>";
					$_GET[id] = $_POST[id];
					$message = "Update Success<br>Location was changed<br />*ORDER TIME HAS BEEN UPDATED*";
				}
				else
				{
					//$content = "MySQL Error";
					$_GET['id'] = $_POST['id'];
					$message = "MySQL Error";
				}
			}
		}
		if($_POST['type'] == 'other')
		{
			if(empty($_POST['location']) || empty($_POST['emp']) || empty($_POST['name']) || empty($_POST['number']) || empty($_POST['line_code']) || empty($_POST['part_num']) || empty($_POST['freight']) || empty($_POST['tracking']) )
			{
				//$content = "You can not erase required fields<br><br><a href=\"edit.php?id=".$_POST[id]."&action=edit\">Return to order</a>";
				$_GET['id'] = $_POST['id'];
				$_GET['action'] = 'edit';
				$message = "You can not erase required fields";
			}
			else
			{
				//Update the timestamp on current order
				$_POST['time'] = date("Y-m-d H:i:s");
				$sql = update_query($_POST);
				$query = mysql_query($sql) or die(mysql_error());
				if($query)
				{
					//$content = "Update Success<br><br><br><font color=\"#ffea00\">Location changed<br>*ORDER TIME HAS BEEN UPDATED*</font><br><br><a href=\"edit.php?id=".$_POST[id]."\">Return to order</a><br><br>";
					$_GET['id'] = $_POST['id'];
					$message = "Update Success<br>Location was changed<br>*ORDER TIME HAS BEEN UPDATED*";
				}
				else
				{
					//$content = "MySQL Error";
					$_GET['id'] = $_POST['id'];
					$message = "MySQL Error";
				}
			}
		}
		if($_POST['type'] == 'special')
		{
			if(empty($_POST['emp']) || empty($_POST['name']) || empty($_POST['number']) || empty($_POST['line_code']) || empty($_POST['part_num']) || empty($_POST['freight']) || empty($_POST['tracking']) )
			{
				//$content = "You can not erase required fields<br><br><a href=\"edit.php?id=".$_POST[id]."&action=edit\">Return to order</a>";
				$_GET['id'] = $_POST['id'];
				$_GET['action'] = 'edit';
				$message = "You can not erase required fields";
			}
			else
			{
				//Update the timestamp on current order
				$_POST['time'] = date("Y-m-d H:i:s");
				$sql = update_query($_POST);
				$query = mysql_query($sql) or die(mysql_error());
				if($query)
				{
					//$content = "Update Success<br><br><br><font color=\"#ffea00\">Location changed<br>*ORDER TIME HAS BEEN UPDATED*</font><br><br><a href=\"edit.php?id=".$_POST[id]."\">Return to order</a><br><br>";
					$_GET['id'] = $_POST['id'];
					$message = "Update Success<br>Location was changed<br>*ORDER TIME HAS BEEN UPDATED*";
				}
				else
				{
					//$content = "MySQL Error";
					$_GET['id'] = $_POST['id'];
					$message = "MySQL Error";
				}
			}
		}
	}
	else //This just saves the order DOES NOT update timestamp
	{
		//Check for empty field, that are not allowed to be empty
		if($_POST['type'] == 'local')
		{
			if(empty($_POST['location']) || empty($_POST['emp']) || empty($_POST['name']) || empty($_POST['line_code']) || empty($_POST['part_num']) )
			{
				//$content = "You can not erase required fields<br><br><a href=\"edit.php?id=".$_POST[id]."&action=edit\">Return to order</a>";
				$_GET['id'] = $_POST['id'];
				$_GET['action'] = 'edit';
				$message = "You can not erase required fields";
			}
			
			//Make sure the user isnt changeing the store #(the store that generated the order `store`) to the same location that the order is shipping from(`location`)
			$store_array = array(228 => 'Anoka',229 => 'Osseo',230 => 'SLP',341 => 'Prior Lake',231 => 'Buffalo', 232 => 'Rogers',233 => 'St. Michael');
			if(strtoupper($_POST['location']) == strtoupper($store_array[$_POST['multi_store_change']]))
			{
				$ms_error = 'Store(db field `store`) can NOT match order location(`location`) - time not updated loop';
				//echo $ms_error;
				//die();
				$_GET['id'] = $_POST['id'];
				$_GET['action'] = 'edit';
				$message = $ms_error;

			}

			else
			{
				//var_dump($_POST);
				//Check if the user is changing the receiving status to FALSE if they are then look for the order id in the shuttle_part_log, if we find it there then change its status to 0(awating pickup)
				if($_POST['received'] == 'F')
				{
					$check_shuttle_part_log = mysql_query("SELECT * FROM `ci_shuttle_part_log` WHERE `order_id` = '".$_POST['id']."' AND `status` = '4' ");
					if(mysql_num_rows($check_shuttle_part_log) > 0)
					{
						$fix_query = mysql_query("UPDATE `ci_shuttle_part_log` SET `status` = '0' WHERE `order_id` = '".$_POST['id']."'");
					}
				}
				//Added next line so phoneroom can change the store id if they enter the order into the wrong store
				if(isset($_POST['multi_store_change'])){
					$multi_store_sql = "UPDATE `order` SET `store` = '$_POST[multi_store_change]' WHERE `id` = $_POST[id] ";
					$multi_store_update = mysql_query($multi_store_sql) or die(mysql_error());
					if($multi_store_update){
						unset($_POST['multi_store_change']);
					}
				}
				//end phoneroom addition
				$sql = update_query($_POST);
				$query = mysql_query($sql) or die(mysql_error());
				if($query)
				{
					//$content = "Update Success<br><br><a href=\"edit.php?id=".$_POST[id]."\">Return to order</a>";
					$_GET['id'] = $_POST['id'];
					$message = "Update Success";
				}
				else
				{
					//$content = "MySQL Error";
					$_GET['id'] = $_POST['id'];
					$message = "MySQL Error";
				}
			}
		}
		if($_POST['type'] == 'other')
		{
			if(empty($_POST['location']) || empty($_POST['emp']) || empty($_POST['name']) || empty($_POST['number']) || empty($_POST['line_code']) || empty($_POST['part_num']) || empty($_POST['freight']) || empty($_POST['tracking']) )
			{
				//$content = "You can not erase required fields<br><br><a href=\"edit.php?id=".$_POST[id]."&action=edit\">Return to order</a>";
				$_GET['id'] = $_POST['id'];
				$_GET['action'] = 'edit';
				$message = "You can not erase required fields";
			}
			else
			{
				$sql = update_query($_POST);
				$query = mysql_query($sql) or die(mysql_error());
				if($query)
				{
					//$content = "Update Success<br><br><a href=\"edit.php?id=".$_POST[id]."\">Return to order</a>";
					$_GET['id'] = $_POST['id'];
					$message = "Update Success";
				}
				else
				{
					//$content = "MySQL Error";
					$_GET['id'] = $_POST['id'];
					$message = "MySQL Error";
				}
			}
		}
		if($_POST['type'] == 'special')
		{
			if(empty($_POST['emp']) || empty($_POST['name']) || empty($_POST['number']) || empty($_POST['line_code']) || empty($_POST['part_num']) || empty($_POST['freight']) || empty($_POST['tracking']) )
			{
				//$content = "You can not erase required fields<br><br><a href=\"edit.php?id=".$_POST[id]."&action=edit\">Return to order</a>";
				$_GET['id'] = $_POST['id'];
				$_GET['action'] = 'edit';
				$message = "You can not erase required fields";
			}
			else
			{
				$sql = update_query($_POST);
				$query = mysql_query($sql) or die(mysql_error());
				if($query)
				{
					//$content = "Update Success<br><br><a href=\"edit.php?id=".$_POST[id]."\">Return to order</a>";
					$_GET[id] = $_POST[id];
					$message = "Update Success";
				}
				else
				{
					//$content = "MySQL Error";
					$_GET['id'] = $_POST['id'];
					$message = "MySQL Error";
				}
			}
		}
	}
}
/**CONFIRM DELETE ORDERS***************************************************************************************************************/
elseif(isset($_POST['delete']))
{
	if(!empty($_POST['reason']) && isset($_POST['reason']) )
	{
		if(isset($_POST['passwd_0']))
		{
			$passwd_sql = "SELECT * FROM delete_users WHERE name = '$store_info[number]' ";
			$passwd_query = mysql_query($passwd_sql) or die(mysql_error());
			while($row = mysql_fetch_assoc($passwd_query))
			{
				$passwd_from_db = $row['passwd'];
			}
			if(strtolower($_POST['passwd_0']) == strtolower($passwd_from_db))
			{
				$trash_query = mysql_query("SELECT * FROM `order` WHERE `id` = '$_POST[id]' ");
				while($row = mysql_fetch_array($trash_query))
				{
					$trash_array['id'] = $row['id'];
					$trash_array['type'] = $row['type'];
					$trash_array['time'] = $row['time'];
					$trash_array['store'] = $row['store'];
					$trash_array['emp'] = $row['emp'];
					$trash_array['location'] = $row['location'];
					$trash_array['line_code'] = $row['line_code'];
					$trash_array['part_num'] = $row['part_num'];
					$trash_array['qty'] = $row['qty'];
					$trash_array['name'] = $row['name'];
					$trash_array['number'] = $row['number'];
					$trash_array['tracking'] = $row['tracking'];
					$trash_array['cost'] = $row['cost'];
					$trash_array['sell'] = $row['sell'];
					$trash_array['freight'] = $row['freight'];
					$trash_array['notes'] = $row['notes'];
					$trash_array['call_back'] = $row['call_back'];
					$trash_array['received'] = $row['received'];
					$trash_array['call_cust'] = $row['call_cust'];
					$trash_array['sold'] = $row['sold'];
					$trash_array['prebill'] = $row['prebill'];
				}
				foreach($trash_array as $key => &$var)
				{
					$var = trim($var);
					if(empty($var))
					{
						$var = 'NULL';
					}				
				}
				$delete_time = date("Y-m-d H:i:s");
				$trash_query_2 = mysql_query("INSERT INTO `order_delete` (id,type,time,store,emp,location,line_code,part_num,qty,name,number,tracking,cost,sell,freight,notes,call_back,received,call_cust,sold,prebill,reason,delete_time) VALUES ('$trash_array[id]','$trash_array[type]','$trash_array[time]','$trash_array[store]','$trash_array[emp]','$trash_array[location]','$trash_array[line_code]','$trash_array[part_num]','$trash_array[qty]','$trash_array[name]','$trash_array[number]','$trash_array[tracking]','$trash_array[cost]','$trash_array[sell]','$trash_array[freight]','$trash_array[notes]','$trash_array[call_back]','$trash_array[received]','$trash_array[call_cust]','$trash_array[sold]','$trash_array[prebill]','$_POST[reason]','$delete_time') ") or die(mysql_error());			
			
				$sql = "DELETE FROM `order` WHERE `id` = '$_POST[id]' LIMIT 1";
				$query = mysql_query($sql) or die(mysql_error());
				if($query)
				{
					$content = "ORDER DELETED<br><br><a href=\"index.php\">HOME</a>";
					//QUICK CHANGE TO THE NAVIGATION BUTTONS,
					//DONT LET USER "GO BACK" IF THEY ARE DELETEING SOMETHING
					$nav = nav_buttons(array("Main" => "index.php"));
				}
				else
				{
					$content = "MySQL Error";
				}				
			}
			else
			{
				$content = "Incorrect Password<br><br>Please see your Manager<br><a href=\"".$_SERVER['HTTP_REFERER']."\">RETURN</a>";
			}
		}
	}
	else
	{
		$content = "You must enter a reason<br><a href=\"".$_SERVER['HTTP_REFERER']."\">TRY AGAIN</a>";
	}
}
/***FINISH CONVERTING ORDER************************************************************************************************************/
elseif(isset($_POST['convert']))
{
	//Characters to prevent from getting into db
	$search_array = array("-","/","'","\\");
	
	//Finish Converting Other DC Orders***************************************************
	if($_POST['type'] == 'other')
	{
		$error = false;
		//This Loop will check for any empty fields, returns error = true if there are;
		if(empty($_POST['number']))
		{
			$error = true;
		}
		if(empty($_POST['tracking']))
		{
			$error = true;
		}
		if(empty($_POST['freight']))
		{
			$error = true;
		}
		if(empty($_POST['location']))
		{
			$error = true;
		}
		if($error)
		{
			$order_template = 'html/edit_convert_to_other.html';
			$order_template_vars = array("PART_NUM" => $_POST['part_num'],
										 "LINE_CODE" => $_POST['line_code'],
										 "QTY" => $_POST['qty'],
										 "LOCATION" => $_POST['location'],
										 "DISPLAY_NAME" => account_name($_POST['name']),
										 "NAME" => $_POST['name'],
										 "NUMBER" => $_POST['number'],
										 "NOTES" => $_POST['notes'],
										 "CALL_BACK" => $_POST['call_back'],
										 "RECEIVED" => $_POST['received'],
										 "PREBILL" => $_POST['prebill'],
										 "ID" => $_POST['id'],
										 "STORE" => $_POST['store'],
										 "TIME" => $_POST['time'],
										 "EMP" => $_POST['emp'],
										 "TRACKING" => $_POST['tracking'],
										 "FREIGHT" => $_POST['freight'],
										 "SOLD" => $_POST['sold'],
										 "MESSAGE" => "<strong><font color=\"#ffb20f\">Please fill in all the required fields before you continue</font></strong>",
										 "TYPE" => $_POST['type']
										  );
			$content = template($order_template,$order_template_vars);
		}
		elseif(!$error)
		{	
			foreach($_POST as $key => &$var)
			{
				$var = str_replace($search_array,"",strtoupper($var));
			}
			$time = date("Y-m-d H:i:s");
			$convert_query = "UPDATE `order` SET `type` = 'other' ,`number` = '$_POST[number]', `tracking` = '$_POST[tracking]',`freight` = '$_POST[freight]',`call_back` = 'F', `received` = 'F', `time` = '$time',`location` = '$_POST[location]',`cost` = 'NULL' ,`sold` = 'F' WHERE `id` = '$_POST[id]' ";
			$c_query = mysql_query($convert_query) or die(mysql_error());
			if($c_query)
			{
				//$content = "Succesfully Converted Order<br><br><a href=\"edit.php?id=".$_POST[id]."\">Return to order </a>";
				$_GET['id'] = $_POST['id'];
				$message = "Successfully Converted Order";
			}
			else
			{
				//$content = "MySQL Error";
				$_GET['id'] = $_POST['id'];
				$message = "MySQL Error";
			}
		}	
	}
	//Finish converting Factory Orders***************************
	if($_POST['type'] == 'special')
	{
		$error = false;
		//This Loop will check for any empty fields, returns error = true if there are;
		if(empty($_POST['number']))
		{
			$error = true;
		}
		if(empty($_POST['tracking']))
		{
			$error = true;
		}
		if(empty($_POST['freight']))
		{
			$error = true;
		}
		if($error)
		{
			$order_template = 'html/edit_convert_to_special.html';
			$order_template_vars = array("PART_NUM" => $_POST['part_num'],
										 "LINE_CODE" => $_POST['line_code'],
										 "QTY" => $_POST['qty'],
										 "LOCATION" => $_POST['location'],
										 "DISPLAY_NAME" => account_name($_POST['name']),
										 "NAME" => $_POST['name'],
										 "NUMBER" => $_POST['number'],
										 "NOTES" => $_POST['notes'],
										 "CALL_BACK" => $_POST['call_back'],
										 "RECEIVED" => $_POST['received'],
										 "PREBILL" => $_POST['prebill'],
										 "ID" => $_POST['id'],
										 "STORE" => $_POST['store'],
										 "TIME" => $_POST['time'],
										 "EMP" => $_POST['emp'],
										 "TRACKING" => $_POST['tracking'],
										 "FREIGHT" => $_POST['freight'],
										 "MESSAGE" => "<strong><font color=\"#ffb20f\">Please fill in all the required fields before you continue</font></strong>",
										 "TYPE" => $_POST['type'],
										 "COST" => $_POST['cost'],
										 "SELL" => $_POST['sell'],
										 "SOLD" => $_POST['sold']
										  );
			$content = template($order_template,$order_template_vars);
		}
		elseif(!$error)
		{	
			foreach($_POST as $key => &$var)
			{
				$var = str_replace($search_array,"",strtoupper($var));
			}
			$time = date("Y-m-d H:i:s");
			$convert_query = "UPDATE `order` SET `type` = 'special' ,`number` = '$_POST[number]', `tracking` = '$_POST[tracking]',`freight` = '$_POST[freight]',`call_back` = 'F', `received` = 'F', `time` = '$time',`location` = 'FACTORY',`cost` = '$_POST[cost]' , `sell` = '$_POST[sell]' ,`sold` = 'F' WHERE `id` = '$_POST[id]' ";
			$c_query = mysql_query($convert_query) or die(mysql_error());
			if($c_query)
			{
				//$content = "Succesfully Converted Order<br><br><a href=\"edit.php?id=".$_POST[id]."\">Return to order </a>";
				$_GET['id'] = $_POST['id'];
				$message = "Succesfully Converted Order";
			}
			else
			{
				//$content = "MySQL Error";
				$_GET['id'] = $_POST['id'];
				$message = "MySQL Error";
			}
		}	
	}
	//Finish converting LOCAL Orders***************************
	if($_POST['type'] == 'local')
	{
		$error = false;
		//This Loop will check for any empty fields, returns error = true if there are;
		if(empty($_POST['location']))
		{
			$error = true;
		}
		if($error)
		{
			$order_template = 'html/edit_convert_to_local.html';
			$order_template_vars = array("PART_NUM" => $_POST['part_num'],
										 "LINE_CODE" => $_POST['line_code'],
										 "QTY" => $_POST['qty'],
										 "LOCATION" => edit_local_option($_POST['location'],$_POST['store']),
										 "DISPLAY_LOCATION" => $_POST['location'],
										 "DISPLAY_NAME" => account_name($_POST['name']),
										 "NAME" => $_POST['name'],
										 "NUMBER" => $_POST['number'],
										 "NOTES" => $_POST['notes'],
										 "CALL_BACK" => $_POST['call_back'],
										 "RECEIVED" => $_POST['received'],
										 "PREBILL" => $_POST['prebill'],
										 "ID" => $_POST['id'],
										 "STORE" => $_POST['store'],
										 "TIME" => $_POST['time'],
										 "EMP" => $_POST['emp'],
										 "FREIGHT" => $_POST['freight'],
										 "MESSAGE" => "<strong><font color=\"#ffb20f\">Please fill in all the required fields before you continue</font></strong>",
										 "TYPE" => $_POST['type'],
										 "SOLD" => $_POST['sold']
										  );
			$content = template($order_template,$order_template_vars);
		}
		elseif(!$error)
		{	
			foreach($_POST as $key => &$var)
			{
				$var = str_replace($search_array,"",strtoupper($var));
			}
			$time = date("Y-m-d H:i:s");
			$convert_query = "UPDATE `order` SET `type` = 'local' ,`number` = '$_POST[number]', `tracking` = 'NULL',`freight` = 'NULL',`call_back` = 'F', `received` = 'F', `time` = '$time',`location` = '$_POST[location]',`cost` = 'NULL' , `sell` = 'NULL'  ,`sold` = 'F' WHERE `id` = '$_POST[id]' ";
			$c_query = mysql_query($convert_query) or die(mysql_error());
			if($c_query)
			{
				//$content = "Succesfully Converted Order<br><br><a href=\"edit.php?id=".$_POST[id]."\">Return to order </a>";
				$_GET['id'] = $_POST['id'];
				$message = "Succesfully Converted Order";
			}
			else
			{
				//$content = "MySQL Error";
				$_GET['id'] = $_POST['id'];
				$message = "MySQL Error";
			}
		}	
	}	
}
//END POST ****************************************END POST***************************************************************************

if(isset($_GET['id']))
{
/**********************************************************************Review Order***************************************************/
	if(!isset($_GET['action']))
	{
		$sql = "SELECT * FROM `order` WHERE `id` = '$_GET[id]'";
		$query = mysql_query($sql);
		
		while($row = mysql_fetch_array($query))
		{
			$query_array['id'] = $row['id'];
			$query_array['type'] = $row['type'];
			$query_array['time'] = $row['time'];
			$query_array['store'] = $row['store'];
			$query_array['emp'] = $row['emp'];
			$query_array['location'] = $row['location'];
			$query_array['line_code'] = $row['line_code'];
			$query_array['part_num'] = $row['part_num'];
			$query_array['qty'] = $row['qty'];
			$query_array['name'] = $row['name'];
			$query_array['number'] = phone_number($row['number']);
			$query_array['tracking'] = $row['tracking'];
			$query_array['cost'] = $row['cost'];
			$query_array['sell'] = $row['sell'];
			$query_array['freight'] = $row['freight'];
			$query_array['notes'] = $row['notes'];
			$query_array['call_back'] = $row['call_back'];
			$query_array['received'] = $row['received'];
			$query_array['call_cust'] = $row['call_cust'];
			$query_array['sold'] = $row['sold'];
			$query_array['prebill'] = $row['prebill'];
			$query_array['class'] = $row['class'];
		}
		//Handles form for Local Orders
		if($query_array['type'] == 'local')
		{
			//Variables that have had changes made to them from the query_array
			$time = date("g:i a m/d/y",strtotime($query_array['time']));
			if($query_array['received'] == 'T')
			{
				$received = "<font color=\"#ffea00\">Yes</font>";
			}
			else
			{
				$received = "No";
			}
			if($query_array['call_back'] == 'T')
			{
				$call_back = "<font color=\"#ffea00\">Yes</font>";
			}
			else
			{
				$call_back = "No";
			}
			if($query_array['prebill'] == 'T')
			{
				$prebill = 'Yes';
			}
			else
			{
				$prebill = 'No';
			}
			if($query_array['sold'] == 'T')
			{
				$sold = "Yes";
			}
			else
			{
				$sold = "No";
			}
			$name = account_name($query_array['name']);
			$print_button = "javascript:popUp('print.php?id=".$query_array['id']."&type=2')";
			$edit_button = "edit.php?id=".$query_array['id']."&action=edit";
			$delete_button = "edit.php?id=".$query_array['id']."&action=delete";
			$convert_button = "edit.php?id=".$query_array['id']."&action=convert";
			
			$order_template = 'html/edit_local.html';
			$shuttle_delivery_status = shuttle_delivery_status($query_array['id']);
			$order_template_vars = array("CURRENT_STATUS" => $shuttle_delivery_status['status'],  
										 "CURRENT_STATUS_MESSAGE" => $shuttle_delivery_status['status_message'],
										 "PART_NUM" => $query_array['part_num'],
										 "LINE_CODE" => $query_array['line_code'],
										 "QTY" => $query_array['qty'],
										 "LOCATION" => $query_array['location'],
										 "NAME" => $name,
										 "NUMBER" => $query_array['number'],
										 "NOTES" => $query_array['notes'],
										 "CALL_BACK" => $call_back,
										 "RECEIVED" => $received,
										 "PREBILL" => $prebill,
										 "ID" => $query_array['id'],
										 "STORE" => $query_array['store'],
										 "TIME" => $time,
										 "EMP" => $query_array['emp'],
										 "SOLD" => $sold,
										 "PRINT_BUTTON" => $print_button,
										 "EDIT_BUTTON" => $edit_button,
										 "DELETE_BUTTON" => $delete_button,
										 "CONVERT_BUTTON" => $convert_button,
										 "DESC" => part_desc($query_array['line_code'],$query_array['part_num']),
										 "CLASS" => $query_array['class']
										  );
			$content = template($order_template,$order_template_vars);
		}
		//Handles form for Outside DC orders
		elseif($query_array['type'] == 'other')
		{
			//Variables that have had changes made to them from the query_array
			if($query_array['received'] == 'T')
			{
				$received = "<font color=\"#ffea00\">Yes</font>";
			}
			else
			{
				$received = "No";
			}
			if($query_array['call_back'] == 'T')
			{
				$call_back = "<font color=\"#ffea00\">Yes</font>";
			}
			else
			{
				$call_back = "No";
			}
			if($query_array['prebill'] == 'T')
			{
				$prebill = 'Yes';
			}
			else
			{
				$prebill = 'No';
			}
			if($query_array['sold'] == 'T')
			{
				$sold = "Yes";
			}
			else
			{
				$sold = "No";
			}
			$name = account_name($query_array['name']);
			$time = date("g:i a m/d/y",strtotime($query_array['time']));
			$print_button = "javascript:popUp('print.php?id=".$query_array['id']."&type=2')";
			$edit_button = "edit.php?id=".$query_array['id']."&action=edit";
			$delete_button = "edit.php?id=".$query_array['id']."&action=delete";
			$convert_button = "edit.php?id=".$query_array['id']."&action=convert";
			
			$order_template = 'html/edit_other.html';
			$order_template_vars = array("PART_NUM" => $query_array['part_num'],
										 "LINE_CODE" => $query_array['line_code'],
										 "QTY" => $query_array['qty'],
										 "LOCATION" => $query_array['location'],
										 "NAME" => $name,
										 "NUMBER" => $query_array['number'],
										 "NOTES" => $query_array['notes'],
										 "CALL_BACK" => $call_back,
										 "RECEIVED" => $received,
										 "PREBILL" => $prebill,
										 "ID" => $query_array['id'],
										 "STORE" => $query_array['store'],
										 "TIME" => $time,
										 "EMP" => $query_array['emp'],
										 "SOLD" => $sold,
										 "PRINT_BUTTON" => $print_button,
										 "EDIT_BUTTON" => $edit_button,
										 "DELETE_BUTTON" => $delete_button,
										 "CONVERT_BUTTON" => $convert_button,
										 "TRACKING" => $query_array['tracking'],
										 "FREIGHT" => $query_array['freight'],
										 "DESC" => part_desc($query_array['line_code'],$query_array['part_num']),
										 "CLASS" => $query_array['class']
										  );
			$content = template($order_template,$order_template_vars);
		}
		//Handles form for Factory Orders
		elseif($query_array['type'] == 'special')
		{
			//Variables that have had changes made to them from the query_array
			if($query_array['received'] == 'T')
			{
				$received = "<font color=\"#ffea00\">Yes</font>";
			}
			else
			{
				$received = "No";
			}
			if($query_array['call_back'] == 'T')
			{
				$call_back = "<font color=\"#ffea00\">Yes</font>";
			}
			else
			{
				$call_back = "No";
			}
			if($query_array['prebill'] == 'T')
			{
				$prebill = 'Yes';
			}
			else
			{
				$prebill = 'No';
			}
			if($query_array['sold'] == 'T')
			{
				$sold = "Yes";
			}
			else
			{
				$sold = "No";
			}
			$name = account_name($query_array['name']);
			$time = date("g:i a m/d/y",strtotime($query_array['time']));
			$print_button = "javascript:popUp('print.php?id=".$query_array['id']."&type=2')";
			$edit_button = "edit.php?id=".$query_array['id']."&action=edit";
			$delete_button = "edit.php?id=".$query_array['id']."&action=delete";
			$convert_button = "edit.php?id=".$query_array['id']."&action=convert";
			
			$order_template = 'html/edit_special.html';
			$order_template_vars = array("PART_NUM" => $query_array['part_num'],
										 "LINE_CODE" => $query_array['line_code'],
										 "QTY" => $query_array['qty'],
										 "LOCATION" => $query_array['location'],
										 "NAME" => $name,
										 "NUMBER" => $query_array['number'],
										 "NOTES" => $query_array['notes'],
										 "CALL_BACK" => $call_back,
										 "RECEIVED" => $received,
										 "PREBILL" => $prebill,
										 "ID" => $query_array['id'],
										 "STORE" => $query_array['store'],
										 "TIME" => $time,
										 "EMP" => $query_array['emp'],
										 "SOLD" => $sold,
										 "PRINT_BUTTON" => $print_button,
										 "EDIT_BUTTON" => $edit_button,
										 "DELETE_BUTTON" => $delete_button,
										 "CONVERT_BUTTON" => $convert_button,
										 "TRACKING" => $query_array['tracking'],
										 "FREIGHT" => $query_array['freight'],
										 "COST" => $query_array['cost'],
										 "SELL" => $query_array['sell'],
										 "DESC" => part_desc($query_array['line_code'],$query_array['part_num']),
										 "CLASS" => $query_array['class']
										  );
			$content = template($order_template,$order_template_vars);
		}
	}
/****************EDIT ORDER************************************************************************************************************/	
	elseif($_GET['action'] == 'edit' )
	{
		$sql = "SELECT * FROM `order` WHERE `id` = '$_GET[id]'";
		$query = mysql_query($sql);
		
		while($row = mysql_fetch_array($query))
		{
			$query_array['id'] = $row['id'];
			$query_array['type'] = $row['type'];
			$query_array['time'] = $row['time'];
			$query_array['store'] = $row['store'];
			$query_array['emp'] = $row['emp'];
			$query_array['location'] = $row['location'];
			$query_array['line_code'] = $row['line_code'];
			$query_array['part_num'] = $row['part_num'];
			$query_array['qty'] = $row['qty'];
			$query_array['name'] = $row['name'];
			$query_array['number'] = phone_number($row['number']);
			$query_array['tracking'] = $row['tracking'];
			$query_array['cost'] = $row['cost'];
			$query_array['sell'] = $row['sell'];
			$query_array['freight'] = $row['freight'];
			$query_array['notes'] = $row['notes'];
			$query_array['call_back'] = $row['call_back'];
			$query_array['received'] = $row['received'];
			$query_array['call_cust'] = $row['call_cust'];
			$query_array['sold'] = $row['sold'];
			$query_array['prebill'] = $row['prebill'];
			$query_array['class'] = $row['class'];
		}
		if($query_array['type'] == 'local')
		{
			//Variables that have had changes made to them from the query_array
			$time = date("g:i a m/d/y",strtotime($query_array['time']));
			if($query_array['received'] == 'T')
			{
				$received = "<input type=\"radio\" name=\"received\" value=\"T\" checked=\"checked\"  />Yes<input name=\"received\" type=\"radio\" value=\"F\"/>No";
				//$received = "Yes (Changes have beed disabled)";
			}
			else
			{
				$received = "<input type=\"radio\" name=\"received\" value=\"T\" />Yes<input name=\"received\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
				//$received = 'No (Changes have beed disabled)';
			}
			if($query_array['call_back'] == 'T')
			{
				$call_back = "<input type=\"radio\" name=\"call_back\" value=\"T\" checked=\"checked\"  />Yes<input name=\"call_back\" type=\"radio\" value=\"F\"/>No";
				//$call_back = 'Yes (Changes have beed disabled)';
			}
			else
			{
				$call_back = "<input type=\"radio\" name=\"call_back\" value=\"T\" />Yes<input name=\"call_back\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
				//$call_back = 'No (Changes have beed disabled)';
			}
			if($query_array['prebill'] == 'T')
			{
				$prebill = "<input type=\"radio\" name=\"prebill\" value=\"T\" checked=\"checked\"  />Yes<input name=\"prebill\" type=\"radio\" value=\"F\"/>No";
			}
			else
			{
				$prebill = "<input type=\"radio\" name=\"prebill\" value=\"T\" />Yes<input name=\"prebill\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
			}
			if($query_array['sold'] == 'T')
			{
				$sold = "<input type=\"radio\" name=\"sold\" value=\"T\" checked=\"checked\"  />Yes<input name=\"sold\" type=\"radio\" value=\"F\"/>No";
			}
			else
			{
				$sold = "<input type=\"radio\" name=\"sold\" value=\"T\" />Yes<input name=\"sold\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
			}
			if($_SESSION['user_info']['multi_store'] == 'T'){
				$multi_store_edit = '<select name="multi_store_change" id="multi_store_change">
 										<option value="228">Anoka</option>
  										<option value="229">Osseo</option>
  										<option value="230">SLP</option>
									    <option value="341">Prior Lake</option>
									    <option value="231">Buffalo</option>
									    <option value="232">Rogers</option>
										<option value="233">St. Michael</option>
									 </select>';
			}else{
				$multi_store_edit = $query_array['store'];
			}
			$name = $query_array['name'];
			$save_button = "edit.php";
			$delete_button = "edit.php?id=".$query_array['id']."&action=delete";
			$convert_button = "edit.php?id=".$query_array['id']."&action=convert";
			
			//$location = edit_local_option($query_array[location],$store_info[number]);
			$location = create_select($store_info['select'],$query_array['location']);
			$order_template = 'html/edit_local_edit.html';
			$order_template_vars = array("PART_NUM" => $query_array['part_num'],
										 "LINE_CODE" => $query_array['line_code'],
										 "QTY" => $query_array['qty'],
										 "LOCATION" => $location,
										 "DISPLAY_LOCATION" => $query_array['location'],
										 "NAME" => $name,
										 "NUMBER" => $query_array['number'],
										 "NOTES" => $query_array['notes'],
										 "CALL_BACK" => $call_back,
										 "RECEIVED" => $received,
										 "PREBILL" => $prebill,
										 "ID" => $query_array['id'],
										 "STORE" => $multi_store_edit,
										 "STORE_NUM" => $query_array['store'],
										 "TIME" => $time,
										 "EMP" => $query_array['emp'],
										 "SOLD" => $sold,
										 "TYPE" => $query_array['type'],
										 "SAVE_BUTTON" => $save_button,
										 "DELETE_BUTTON" => $delete_button,
										 "CONVERT_BUTTON" => $convert_button,
										 "DESC" => part_desc($query_array['line_code'],$query_array['part_num']),
										 "CLASS" => $query_array['class']
										  );
			$content = template($order_template,$order_template_vars);
		}
		elseif($query_array['type'] == 'other')
		{
			//Variables that have had changes made to them from the query_array
			$time = date("g:i a m/d/y",strtotime($query_array['time']));
			if($query_array['received'] == 'T')
			{
				$received = "<input type=\"radio\" name=\"received\" value=\"T\" checked=\"checked\"  />Yes<input name=\"received\" type=\"radio\" value=\"F\"/>No";
			}
			else
			{
				$received = "<input type=\"radio\" name=\"received\" value=\"T\" />Yes<input name=\"received\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
			}
			if($query_array['call_back'] == 'T')
			{
				$call_back = "<input type=\"radio\" name=\"call_back\" value=\"T\" checked=\"checked\"  />Yes<input name=\"call_back\" type=\"radio\" value=\"F\"/>No";
			}
			else
			{
				$call_back = "<input type=\"radio\" name=\"call_back\" value=\"T\" />Yes<input name=\"call_back\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
			}
			if($query_array['prebill'] == 'T')
			{
				$prebill = "<input type=\"radio\" name=\"prebill\" value=\"T\" checked=\"checked\"  />Yes<input name=\"prebill\" type=\"radio\" value=\"F\"/>No";
			}
			else
			{
				$prebill = "<input type=\"radio\" name=\"prebill\" value=\"T\" />Yes<input name=\"prebill\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
			}
			if($query_array['sold'] == 'T')
			{
				$sold = "<input type=\"radio\" name=\"sold\" value=\"T\" checked=\"checked\"  />Yes<input name=\"sold\" type=\"radio\" value=\"F\"/>No";
			}
			else
			{
				$sold = "<input type=\"radio\" name=\"sold\" value=\"T\" />Yes<input name=\"sold\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
			}
			$name = $query_array['name'];
			$save_button = "edit.php";
			$delete_button = "edit.php?id=".$query_array['id']."&action=delete";
			$convert_button = "edit.php?id=".$query_array['id']."&action=convert";
			
			$order_template = 'html/edit_other_edit.html';
			$order_template_vars = array("PART_NUM" => $query_array['part_num'],
										 "LINE_CODE" => $query_array['line_code'],
										 "QTY" => $query_array['qty'],
										 "LOCATION" => $query_array['location'],
										 "NAME" => $name,
										 "NUMBER" => $query_array['number'],
										 "NOTES" => $query_array['notes'],
										 "CALL_BACK" => $call_back,
										 "RECEIVED" => $received,
										 "PREBILL" => $prebill,
										 "ID" => $query_array['id'],
										 "STORE" => $query_array['store'],
										 "TIME" => $time,
										 "EMP" => $query_array['emp'],
										 "SOLD" => $sold,
										 "TYPE" => $query_array['type'],
										 "SAVE_BUTTON" => $save_button,
										 "DELETE_BUTTON" => $delete_button,
										 "CONVERT_BUTTON" => $convert_button,
										 "TRACKING" => $query_array['tracking'],
										 "FREIGHT" => $query_array['freight'],
										 "DESC" => part_desc($query_array['line_code'],$query_array['part_num']),
										 "CLASS" => $query_array['class']
										  );
			$content = template($order_template,$order_template_vars);
		}
		elseif($query_array['type'] == 'special')
		{
			//Variables that have had changes made to them from the query_array
			$time = date("g:i a m/d/y",strtotime($query_array['time']));
			if($query_array['received'] == 'T')
			{
				$received = "<input type=\"radio\" name=\"received\" value=\"T\" checked=\"checked\"  />Yes<input name=\"received\" type=\"radio\" value=\"F\"/>No";
			}
			else
			{
				$received = "<input type=\"radio\" name=\"received\" value=\"T\" />Yes<input name=\"received\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
			}
			if($query_array['call_back'] == 'T')
			{
				$call_back = "<input type=\"radio\" name=\"call_back\" value=\"T\" checked=\"checked\"  />Yes<input name=\"call_back\" type=\"radio\" value=\"F\"/>No";
			}
			else
			{
				$call_back = "<input type=\"radio\" name=\"call_back\" value=\"T\" />Yes<input name=\"call_back\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
			}
			if($query_array['prebill'] == 'T')
			{
				$prebill = "<input type=\"radio\" name=\"prebill\" value=\"T\" checked=\"checked\"  />Yes<input name=\"prebill\" type=\"radio\" value=\"F\"/>No";
			}
			else
			{
				$prebill = "<input type=\"radio\" name=\"prebill\" value=\"T\" />Yes<input name=\"prebill\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
			}
			if($query_array['sold'] == 'T')
			{
				$sold = "<input type=\"radio\" name=\"sold\" value=\"T\" checked=\"checked\"  />Yes<input name=\"sold\" type=\"radio\" value=\"F\"/>No";
			}
			else
			{
				$sold = "<input type=\"radio\" name=\"sold\" value=\"T\" />Yes<input name=\"sold\" type=\"radio\" value=\"F\" checked=\"checked\" />No";
			}
			$name = $query_array['name'];
			$save_button = "edit.php";
			$delete_button = "edit.php?id=".$query_array['id']."&action=delete";
			$convert_button = "edit.php?id=".$query_array['id']."&action=convert";
			
			$order_template = 'html/edit_special_edit.html';
			$order_template_vars = array("PART_NUM" => $query_array['part_num'],
										 "LINE_CODE" => $query_array['line_code'],
										 "QTY" => $query_array['qty'],
										 "LOCATION" => $query_array['location'],
										 "NAME" => $name,
										 "NUMBER" => $query_array['number'],
										 "NOTES" => $query_array['notes'],
										 "CALL_BACK" => $call_back,
										 "RECEIVED" => $received,
										 "PREBILL" => $prebill,
										 "ID" => $query_array['id'],
										 "STORE" => $query_array['store'],
										 "TIME" => $time,
										 "EMP" => $query_array['emp'],
										 "TYPE" => $query_array['type'],
										 "SOLD" => $sold,
										 "SAVE_BUTTON" => $save_button,
										 "DELETE_BUTTON" => $delete_button,
										 "CONVERT_BUTTON" => $convert_button,
										 "TRACKING" => $query_array['tracking'],
										 "FREIGHT" => $query_array['freight'],
										 "COST" => $query_array['cost'],
										 "SELL" => $query_array['sell'],
										 "DESC" => part_desc($query_array['line_code'],$query_array['part_num']),
										 "CLASS" => $query_array['class']
										  );
			$content = template($order_template,$order_template_vars);
		}
	}
/****************DELETE ORDERS*********************************************************************************************************/	
	elseif($_GET['action'] == 'delete')
	{
		$sql = "SELECT * FROM `order` WHERE `id` = '$_GET[id]'";
		$query = mysql_query($sql);
		
		while($row = mysql_fetch_array($query))
		{
			$query_array['id'] = $row['id'];
			$query_array['type'] = $row['type'];
			$query_array['time'] = $row['time'];
			$query_array['store'] = $row['store'];
			$query_array['emp'] = $row['emp'];
			$query_array['location'] = $row['location'];
			$query_array['line_code'] = $row['line_code'];
			$query_array['part_num'] = $row['part_num'];
			$query_array['qty'] = $row['qty'];
			$query_array['name'] = $row['name'];
			$query_array['number'] = phone_number($row['number']);
			$query_array['tracking'] = $row['tracking'];
			$query_array['cost'] = $row['cost'];
			$query_array['sell'] = $row['sell'];
			$query_array['freight'] = $row['freight'];
			$query_array['notes'] = $row['notes'];
			$query_array['call_back'] = $row['call_back'];
			$query_array['received'] = $row['received'];
			$query_array['call_cust'] = $row['call_cust'];
			$query_array['sold'] = $row['sold'];
			$query_array['prebill'] = $row['prebill'];
		}
			//Variables that have had changes made to them from the query_array
			$time = date("g:i a m/d/y",strtotime($query_array['time']));
			$name = account_name($query_array['name']);
			$order_template = 'html/edit_delete_0.html';
			$order_template_vars = array("PART_NUM" => $query_array['part_num'],
										 "LINE_CODE" => $query_array['line_code'],
										 "QTY" => $query_array['qty'],
										 "LOCATION" => $query_array['location'],
										 "NAME" => $name,
										 "NUMBER" => $query_array['number'],
										 "ID" => $query_array['id'],
										 "STORE" => $query_array['store'],
										 "TIME" => $time,
										 "EMP" => $query_array['emp']
										  );
			$content = template($order_template,$order_template_vars);
	}
/**CONVERT ORDER***************************************************************************************************************/
	elseif($_GET['action'] == 'convert')
	{
		
		$sql = "SELECT * FROM `order` WHERE `id` = '$_GET[id]'";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query))
		{
			$query_array['id'] = $row['id'];
			$query_array['type'] = $row['type'];
			$query_array['time'] = $row['time'];
			$query_array['store'] = $row['store'];
			$query_array['emp'] = $row['emp'];
			$query_array['location'] = $row['location'];
			$query_array['line_code'] = $row['line_code'];
			$query_array['part_num'] = $row['part_num'];
			$query_array['qty'] = $row['qty'];
			$query_array['name'] = $row['name'];
			$query_array['number'] = phone_number($row['number']);
			$query_array['tracking'] = $row['tracking'];
			$query_array['cost'] = $row['cost'];
			$query_array['sell'] = $row['sell'];
			$query_array['freight'] = $row['freight'];
			$query_array['notes'] = $row['notes'];
			$query_array['call_back'] = $row['call_back'];
			$query_array['received'] = $row['received'];
			$query_array['call_cust'] = $row['call_cust'];
			$query_array['sold'] = $row['sold'];
			$query_array['prebill'] = $row['prebill'];
			$query_array['class'] = $row['class'];
		}
	//Convert to "otherDC" order****************************************************************************//
		if(isset($_GET['convert'])){
			if($_GET['convert'] == 'other')
			{
				if($query_array['type'] == 'other')
				{
					//$content = "This is already an Other DC order<br><br><a href=\"edit.php?id=".$query_array[id]."\">Return to order</a>";
					$_GET['id'] = $query_array['id'];
					$_GET['convert'] = 'other';
					$message = "This is already an Other DC order";
					
				}
					//Lets make a few changes to the query_array data for proper formatting
					if($query_array['call_back'] == 'T')
					{
						$call_back = "Yes";
					}
					else
					{
						$call_back = "No";
					}
					if($query_array['received'] == 'T')
					{
						$received = "Yes";
					}
					else
					{
						$received = "No";
					}
					if($query_array['prebill'] == 'T')
					{
						$prebill = "Yes";
					}
					else
					{
						$prebill = "No";
					}
					if($query_array['sold'] == 'T')
					{
						$sold = "Yes";
					}
					else
					{
						$sold = "No";
					}
					$date_format = date("g:i a m-d-Y",strtotime($query_array['time']));
					$order_template = 'html/edit_convert_to_other.html';
					$order_template_vars = array("PART_NUM" => $query_array['part_num'],
												 "LINE_CODE" => $query_array['line_code'],
												 "QTY" => $query_array['qty'],
												 "LOCATION" => $query_array['location'],
												 "DISPLAY_NAME" => account_name($query_array['name']),
												 "NAME" => $query_array['name'],
												 "NUMBER" => $query_array['number'],
												 "NOTES" => $query_array['notes'],
												 "CALL_BACK" => $call_back,
												 "RECEIVED" => $received,
												 "PREBILL" => $prebill,
												 "ID" => $query_array['id'],
												 "STORE" => $query_array['store'],
												 "TIME" => $date_format,
												 "EMP" => $query_array['emp'],
												 "SOLD" => $sold,
												 "TRACKING" => "",
												 "FREIGHT" => "",
												 "MESSAGE" => "Please fill in required fields",
												 "TYPE" => $query_array['type'],
												 "DESC" => part_desc($query_array['line_code'],$query_array['part_num']),
												 "CLASS" => $query_array['class']
												  );
					$content = template($order_template,$order_template_vars);
			}
		}
	//Convert to "Factory" order****************************************************************************//
	if(isset($_GET['convert'])){
		if($_GET['convert'] == 'special')
		{
			if($query_array['type'] == 'special')
			{
				//$content = "This is already an Factory order<br><br><a href=\"edit.php?id=".$query_array[id]."\">Return to order</a>";
				$_GET['id'] = $query_array['id'];
				$_GET['convert'] = 'special';
				$message = "This is already an Factory order";
			}
				//Lets make a few changes to the query_array data for proper formatting
				if($query_array['call_back'] == 'T')
				{
					$call_back = "Yes";
				}
				else
				{
					$call_back = "No";
				}
				if($query_array['received'] == 'T')
				{
					$received = "Yes";
				}
				else
				{
					$received = "No";
				}
				if($query_array['prebill'] == 'T')
				{
					$prebill = "Yes";
				}
				else
				{
					$prebill = "No";
				}
				if($query_array['sold'] == 'T')
				{
					$sold = "Yes";
				}
				else
				{
					$sold = "No";
				}
				$date_format = date("g:i a m-d-Y",strtotime($query_array['time']));
				$order_template = 'html/edit_convert_to_special.html';
				$order_template_vars = array("PART_NUM" => $query_array['part_num'],
											 "LINE_CODE" => $query_array['line_code'],
											 "QTY" => $query_array['qty'],
											 "LOCATION" => $query_array['location'],
											 "DISPLAY_NAME" => account_name($query_array['name']),
											 "NAME" => $query_array['name'],
											 "NUMBER" => $query_array['number'],
											 "NOTES" => $query_array['notes'],
											 "CALL_BACK" => $call_back,
											 "RECEIVED" => $received,
											 "PREBILL" => $prebill,
											 "ID" => $query_array['id'],
											 "STORE" => $query_array['store'],
											 "TIME" => $date_format,
											 "EMP" => $query_array['emp'],
											 "TRACKING" => "",
											 "FREIGHT" => "",
											 "COST" => "", 
											 "SELL" => "",
											 "SOLD" => $sold,
											 "MESSAGE" => "Please fill in required fields",
											 "TYPE" => $query_array['type'],
											 "DESC" => part_desc($query_array['line_code'],$query_array['part_num']),
											 "CLASS" => $query_array['class']
											  );
				$content = template($order_template,$order_template_vars);
		}
	}
	//Convert to "Local" order****************************************************************************//
	if(isset($_GET['convert'])){
		if($_GET['convert'] == 'local')
		{
			if($query_array['type'] == 'local')
			{
				//$content = "This is already an local order<br><br><a href=\"edit.php?id=".$query_array[id]."\">Return to order</a>";
				$_GET['id'] = $query_array['id'];
				$message = "This is already a Local Order";
			}
				//Lets make a few changes to the query_array data for proper formatting
				if($query_array['call_back'] == 'T')
				{
					$call_back = "Yes";
				}
				else
				{
					$call_back = "No";
				}
				if($query_array['received'] == 'T')
				{
					$received = "Yes";
				}
				else
				{
					$received = "No";
				}
				if($query_array['prebill'] == 'T')
				{
					$prebill = "Yes";
				}
				else
				{
					$prebill = "No";
				}
				if($query_array['sold'] == 'T')
				{
					$sold = "Yes";
				}
				else
				{
					$sold = "No";
				}
				$date_format = date("g:i a m-d-Y",strtotime($query_array['time']));
				$order_template = 'html/edit_convert_to_local.html';
				$order_template_vars = array("PART_NUM" => $query_array['part_num'],
											 "LINE_CODE" => $query_array['line_code'],
											 "QTY" => $query_array['qty'],
											 "LOCATION" => create_select($store_info['select'],false),
											 "DISPLAY_LOCATION" => $query_array['location'],
											 "DISPLAY_NAME" => account_name($query_array['name']),
											 "NAME" => $query_array['name'],
											 "NUMBER" => $query_array['number'],
											 "NOTES" => $query_array['notes'],
											 "CALL_BACK" => $call_back,
											 "RECEIVED" => $received,
											 "PREBILL" => $prebill,
											 "ID" => $query_array['id'],
											 "STORE" => $query_array['store'],
											 "TIME" => $date_format,
											 "EMP" => $query_array['emp'],
											 "TRACKING" => "",
											 "FREIGHT" => "",
											 "COST" => "", 
											 "SELL" => "",
											 "SOLD" => $sold,
											 "MESSAGE" => "Please fill in required fields",
											 "TYPE" => $query_array['type'],
											 "DESC" => part_desc($query_array['line_code'],$query_array['part_num']),
											 "CLASS" => $query_array['class']
											  );
				$content = template($order_template,$order_template_vars);
		}
	}
		else
		{
			//Create the proper buttons for order type choices
			if($query_array['type'] == 'local')
			{
				$convert_buttons = array('local' => "&nbsp;",'other' => "<input type=\"button\" onClick=\"window.location='edit.php?id=".$query_array['id']."&action=convert&convert=other' \" value=\"Other DC Order\" class=\"convert_button\">", 'special' => "<input type=\"button\" onClick=\"window.location='edit.php?id=".$query_array['id']."&action=convert&convert=special' \" value=\"Special Order\" class=\"convert_button\">");
				
			}
			elseif($query_array['type'] == 'other')
			{
				$convert_buttons = array('local' => "<input type=\"button\" onClick=\"window.location='edit.php?id=".$query_array['id']."&action=convert&convert=local' \" value=\"Local Order\" class=\"convert_button\">",'other' => "&nbsp;", 'special' => "<input type=\"button\" onClick=\"window.location='edit.php?id=".$query_array['id']."&action=convert&convert=special' \" value=\"Special Order\" class=\"convert_button\">");
			}
			elseif($query_array['type'] == 'special')
			{
				$convert_buttons = array('local' => "<input type=\"button\" onClick=\"window.location='edit.php?id=".$query_array['id']."&action=convert&convert=local' \" value=\"Local Order\" class=\"convert_button\">",'other' => "<input type=\"button\" onClick=\"window.location='edit.php?id=".$query_array['id']."&action=convert&convert=other' \" value=\"Other DC Order\" class=\"convert_button\">", 'special' => "&nbsp;");
			}
			
			
			//Variables that have had changes made to them from the query_array
			$order_template = 'html/edit_convert_type.html';
			$name = NULL;
			$time = NULL;
			$order_template_vars = array("PART_NUM" => $query_array['part_num'],
										 "LINE_CODE" => $query_array['line_code'],
										 "QTY" => $query_array['qty'],
										 "LOCATION" => $query_array['location'],
										 "NAME" => $name,
										 "NUMBER" => $query_array['number'],
										 "ID" => $query_array['id'],
										 "STORE" => $query_array['store'],
										 "TIME" => $time,
										 "EMP" => $query_array['emp'],
										 "LOCAL_BUTTON" => $convert_buttons['local'],
										 "OTHER_BUTTON" => $convert_buttons['other'],
										 "SPECIAL_BUTTON" => $convert_buttons['special'],
										 "ORDER_TYPE" => $query_array['type']
										  );
			$content = template($order_template,$order_template_vars);
		}
	}
}




/*if(!isset($_GET[id]))
{
	$nav = nav_buttons(array("Main" => "index.php","Search" => "advanced_search.php"));
	$content = "<br><br><input class=\"nav_button\" name=\"button\" value=\"Search for\n an Order\" type=\"button\" onClick=\"window.location='advanced_search.php'\"  />";
}
*//****************Draw main page body***************************************************************************************************/
/*            Just update $content with new data                                                                                      */
		if(!isset($message))
		{
			$message = "&nbsp;";
		}
		$template = 'html/edit_page.html';	
		$template_vars = array(
			"STORE_SELECT" => $ss_html,
			"TITLE" => "Edit",
			"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
			"IMAGE" => HEADER_IMAGE,
			"IMAGE_TEXT" => HEADER_TEXT,
			"NAV_LINKS" => $nav,
			"MESSAGE" => "<strong>".$message."</strong>",
			"CONTENT" =>	$content,
			"FOOTER" => FOOTER
			);
		echo $page = template($template,$template_vars);
?>