<?php
/**********************************************
		File Name:	eta.php
		Date:	05-4-2009
		Author:	Chris Reed	
		Updated: 05-04-2009
***********************************************/

require 'include/functions.php';
$nav_buttons = nav_buttons(array("Main" => "index.php","Enter Order" => "local.php","Delivery ETA" => "eta.php","DC / Shuttle\nCall Back" => "shuttle.php","Review Orders" => "tracking.php", "Receiving" => "receiving.php", "Search" => "advanced_search.php", "Reports" => "reports.php","Suggestions" => "feedback.php"));

$eta_array = NULL;

$time = date('H:i');
$day = date('w');

//echo $time.'</br>'.$day.'</br>';

//-----------------------------------------------ANOKA-----------------------------------
if($store_info['number'] == 228){
	//echo 'Anoka</br>';
	$eta_array['store_num'] = 228;
	//Sunday
	if($day == 0){	
		$sql = "SELECT * FROM `eta_anoka` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_sun'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Osseo';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['osseo_sun'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_sun'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_sun'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_sun'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['osseo_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_ss'];
		}
	}
	//Monday
	if($day == 1){
		$sql = "SELECT * FROM `eta_anoka` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Osseo';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['osseo_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_mon'];
		}
	}
	//Tuesday
	if($day == 2){
		$sql = "SELECT * FROM `eta_anoka` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Osseo';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['osseo_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_tue'];
		}
	}
	//Wednesday & Thursday
	if($day == 3 || $day == 4){
		$sql = "SELECT * FROM `eta_anoka` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Osseo';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['osseo_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_wt'];
		}
		
	}
	//Friday
	if($day == 5){
		$sql = "SELECT * FROM `eta_anoka` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_fri'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Osseo';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['osseo_fri'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_fri'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_fri'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_fri'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['osseo_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_fri'];
		}
		
	}
	//Saturday
	if($day == 6){
		$sql = "SELECT * FROM `eta_anoka` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_sat'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Osseo';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['osseo_sat'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_sat'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_sat'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_sat'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['osseo_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_ss'];
		}
	}
}

//-------------------------------------------------------OSSEO-------------------------------------------------------------
if($store_info['number'] == 229){
	//echo 'Osseo</br>';
	$eta_array['store_num'] = 229;
	//Sunday
	if($day == 0){	
		$sql = "SELECT * FROM `eta_osseo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_sun'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_sun'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_sun'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_sun'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_sun'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_ss'];
		}
	}
	//Monday
	if($day == 1){
		$sql = "SELECT * FROM `eta_osseo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_mon'];
		}
	}
	//Tuesday
	if($day == 2){
		$sql = "SELECT * FROM `eta_osseo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_tue'];
		}
		
	}
	//Wednesday & Thursday
	if($day == 3 || $day == 4){
		$sql = "SELECT * FROM `eta_osseo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_wt'];
		}
		
	}
	//Friday
	if($day == 5){
		$sql = "SELECT * FROM `eta_osseo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_fri'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['osseo_fri'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_fri'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_fri'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_fri'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_fri'];
		}
		
	}
	//Saturday
	if($day == 6){
		$sql = "SELECT * FROM `eta_osseo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_sat'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_sat'];
			$eta_array['interstore']['arrive']['store_2'] = $row['slp_sat'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_sat'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_sat'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['slp_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_ss'];
		}
	}
}
//-----------------------------------------------SPRING LAKE PARK-----------------------------------
if($store_info['number'] == 230){
	//echo 'SLP</br>';
	$eta_array['store_num'] = 230;
	//Sunday
	if($day == 0){	
		$sql = "SELECT * FROM `eta_slp` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_sun'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_sun'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_sun'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_sun'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_sun'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_ss'];
		}
	}
	//Monday
	if($day == 1){
		$sql = "SELECT * FROM `eta_slp` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_mon'];
		}
	}
	//Tuesday
	if($day == 2){
		$sql = "SELECT * FROM `eta_slp` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_tue'];
		}
		
	}
	//Wednesday & Thursday
	if($day == 3 || $day == 4){
		$sql = "SELECT * FROM `eta_slp` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_wt'];
		}
		
	}
	//Friday
	if($day == 5){
		$sql = "SELECT * FROM `eta_slp` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_fri'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_fri'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_fri'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_fri'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_fri'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_fri'];
		}
		
	}
	//Saturday
	if($day == 6){
		$sql = "SELECT * FROM `eta_slp` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_sat'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Prior Lake';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_sat'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_sat'];
			$eta_array['interstore']['arrive']['store_3'] = $row['pl_sat'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_sat'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Prior Lake';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['pl_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_ss'];
		}
	}
}
//-----------------------------------------------PRIOR LAKE-----------------------------------
if($store_info['number'] == 341){
	//echo 'PL</br>';
	$eta_array['store_num'] = 341;
	//Sunday
	if($day == 0){	
		$sql = "SELECT * FROM `eta_pl` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_sun'];
			$eta_array['owa_dc'] = $row['owa_sun'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_sun'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_sun'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_sun'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_sun'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_ss'];
		}
	}
	//Monday
	if($day == 1){
		$sql = "SELECT * FROM `eta_pl` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			$eta_array['owa_dc'] = $row['owa_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_mon'];
		}
	}
	//Tuesday
	if($day == 2){
		$sql = "SELECT * FROM `eta_pl` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			$eta_array['owa_dc'] = $row['owa_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_tue'];
		}
		
	}
	//Wednesday & Thursday
	if($day == 3 || $day == 4){
		$sql = "SELECT * FROM `eta_pl` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			$eta_array['owa_dc'] = $row['owa_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Sring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_wt'];
		}
		
	}
	//Friday
	if($day == 5){
		$sql = "SELECT * FROM `eta_pl` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_fri'];
			$eta_array['owa_dc'] = $row['owa_fri'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_fri'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_fri'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_fri'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_fri'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_fri'];
		}
		
	}
	//Saturday
	if($day == 6){
		$sql = "SELECT * FROM `eta_pl` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_sat'];
			$eta_array['owa_dc'] = $row['owa_sun'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Buffalo';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_sat'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_sat'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_sat'];
			$eta_array['interstore']['arrive']['store_4'] = $row['buffalo_sat'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Buffalo';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['buffalo_stock_ss'];
		}
	}
}


//-----------------------------------------------BUFFALO-----------------------------------
if($store_info['number'] == 231){
	//echo 'Buffalo</br>';
	$eta_array['store_num'] = 231;
	//Sunday
	if($day == 0){	
		$sql = "SELECT * FROM `eta_buffalo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_sun'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Prior Lake';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_sun'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_sun'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_sun'];
			$eta_array['interstore']['arrive']['store_4'] = $row['pl_sun'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Prior Lake';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['pl_stock_ss'];
		}
	}
	//Monday
	if($day == 1){
		$sql = "SELECT * FROM `eta_buffalo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Prior Lake';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['pl_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Prior Lake';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_mon'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['pl_stock_mon'];
		}
	}
	//Tuesday
	if($day == 2){
		$sql = "SELECT * FROM `eta_buffalo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Prior Lake';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['pl_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Prior Lake';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_tue'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['pl_stock_tue'];
		}
		
	}
	//Wednesday & Thursday
	if($day == 3 || $day == 4){
		$sql = "SELECT * FROM `eta_buffalo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_mtwt'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Prior Lake';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_mtwt'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_mtwt'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_mtwt'];
			$eta_array['interstore']['arrive']['store_4'] = $row['pl_mtwt'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Sring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Prior Lake';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_wt'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['pl_stock_wt'];
		}
		
	}
	//Friday
	if($day == 5){
		$sql = "SELECT * FROM `eta_buffalo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_fri'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Prior Lake';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_fri'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_fri'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_fri'];
			$eta_array['interstore']['arrive']['store_4'] = $row['pl_fri'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Prior Lake';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_fri'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['pl_stock_fri'];
		}
		
	}
	//Saturday
	if($day == 6){
		$sql = "SELECT * FROM `eta_buffalo` WHERE `time` = '$time' LIMIT 1";
		$query = mysql_query($sql);
		while($row = mysql_fetch_assoc($query)){
			//MIN DC
			$eta_array['min_dc'] = $row['min_dc_sat'];
			//Interstore Truck
			$eta_array['interstore']['name']['store_1'] = 'Anoka';
			$eta_array['interstore']['name']['store_2'] = 'Osseo';
			$eta_array['interstore']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['interstore']['name']['store_4'] = 'Prior Lake';
			$eta_array['interstore']['arrive']['store_1'] = $row['anoka_sat'];
			$eta_array['interstore']['arrive']['store_2'] = $row['osseo_sat'];
			$eta_array['interstore']['arrive']['store_3'] = $row['slp_sat'];
			$eta_array['interstore']['arrive']['store_4'] = $row['pl_sat'];
			//Stock Truck (Larry)
			$eta_array['stock_truck']['name']['store_1'] = 'Anoka';
			$eta_array['stock_truck']['name']['store_2'] = 'Osseo';
			$eta_array['stock_truck']['name']['store_3'] = 'Spring Lake Park';
			$eta_array['stock_truck']['name']['store_4'] = 'Prior Lake';
			$eta_array['stock_truck']['arrive']['store_1'] = $row['anoka_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_2'] = $row['osseo_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_3'] = $row['slp_stock_ss'];
			$eta_array['stock_truck']['arrive']['store_4'] = $row['pl_stock_ss'];
		}
	}
}
if($store_info['number'] == 341){
	$owa_dc = 'OWA DC @ '.$eta_array['owa_dc'];
}
else{
	$owa_dc = NULL;
}
$template_vars = array( "STORE_SELECT" => $ss_html,
					   "TITLE" => "Arrival ETA",
					   "TABLE_WIDTH" => MAIN_TABLE_WIDTH,
					   "IMAGE" => HEADER_IMAGE,
					   "IMAGE_TEXT" => HEADER_TEXT,
					   "NAV_LINKS" => $nav_buttons,
					   "LINE1" => VER,
					   "FOOTER" => FOOTER,
					   "MY_IP" => $_SERVER['REMOTE_ADDR'],
					   "MESSAGE" => "The times shown are arrival times at your store if you were to place the order now",
					   "MIN_DC" => 'MIN DC - '.$eta_array['min_dc'],
					   "OWA_DC" => $owa_dc,
					   "BETA_MESSAGE" => 'This is a work in progress</br>Contact Chris with any issues',
					   "STORE_1" => $eta_array['interstore']['name']['store_1'],
					   "STORE_2" => $eta_array['interstore']['name']['store_2'],
					   "STORE_3" => $eta_array['interstore']['name']['store_3'],
					   "STORE_4" => $eta_array['interstore']['name']['store_4'],
					   "STORE_1_A" => $eta_array['interstore']['arrive']['store_1'],
					   "STORE_2_A" => $eta_array['interstore']['arrive']['store_2'],
					   "STORE_3_A" => $eta_array['interstore']['arrive']['store_3'],
					   "STORE_4_A" => $eta_array['interstore']['arrive']['store_4'],
					   "STOCK_STORE_1" => $eta_array['stock_truck']['name']['store_1'],
					   "STOCK_STORE_2" => $eta_array['stock_truck']['name']['store_2'],
					   "STOCK_STORE_3" => $eta_array['stock_truck']['name']['store_3'],
					   "STOCK_STORE_4" => $eta_array['stock_truck']['name']['store_4'],
					   "STOCK_STORE_1_A" => $eta_array['stock_truck']['arrive']['store_1'],
					   "STOCK_STORE_2_A" => $eta_array['stock_truck']['arrive']['store_2'],
					   "STOCK_STORE_3_A" => $eta_array['stock_truck']['arrive']['store_3'],
					   "STOCK_STORE_4_A" => $eta_array['stock_truck']['arrive']['store_4'],
					   );


$template = 'html/eta_alt.html';
echo $page = template($template,$template_vars);				  
//var_dump($eta_array);			
			
			
			
?>


