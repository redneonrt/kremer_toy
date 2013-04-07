<?php
/**********************************************
		File Name:	reports.php
		Date:	1-26-2008
		Author:	Chris Reed	
***********************************************/
require 'include/functions.php';
$nav_buttons = nav_buttons(array("Main" => "index.php"));
if(isset($_GET['report']))
{
	//Code to generate Part Number ordering frequency
	if($_GET['report'] == 'order_freq')
	{
		if(isset($_POST['frequency']) && isset($_POST['interval']) ){
			if(!empty($_POST['frequency']) && !empty($_POST['interval'])){
				$frequency = $_POST['frequency'];
				$timeframe = date("Y-m-d",strtotime($_POST['interval']));
			}else{
				$frequency = 4;
				$timeframe = date("Y-m-d",strtotime("-180 days"));
			}
		}else{
			$frequency = 4;
			$timeframe = date("Y-m-d",strtotime("-180 days"));
		}
		$time_start = microtime();
		$report_msg = "Parts ordered more than <font color=\"orange\" >".$frequency."</font> times since <font color=\"orange\" >".$timeframe."</font>";
		$sql = "select *,count(id) as n from `order` WHERE `store` = '$store_info[number]' and TIME >= '$timeframe' group by part_num having  n > '$frequency'  ORDER BY `n` DESC";
		$sql;
		$query_freq = mysql_query($sql);
		if(mysql_num_rows($query_freq) > 0)
		{
			$html[] =   '<table width="60%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td colspan="4" class="report_table_header">'.$report_msg.'</td>
					    </tr>
						<tr>
						<td colspan="4" class="report_table_header">
						<div align="center">
						  <form action="" method="post">
							<label for="select">Frequency</label>
							<select name="frequency" id="select">
							  <option></option>
							  <option value="4">x4</option>
							  <option value="6">x6</option>
							  <option value="8">x8</option>
							  <option value="10">x10</option>
							  <option value="12">x12</option>
							</select>
							<label for="interval">interval</label>
							<select name="interval">
							  <option></option>
							  <option value="-1 month">1 Month</option>
							  <option value="-3 months">3 Months</option>
							  <option value="-6 months">6 Months</option>
							  <option value="-9 months">9 Months</option>
							  <option value="-1 year">1 Year</option>
							</select>
							<input name="ext_search" type="hidden" id="ext_search_0" value="T" />
							<input name="update_button" type="submit" value="Update">
						  </form>
						  <div align="center"><font color="red" size="-1">* You need to set both of the fields before pressing update</font></div>						
						  </div>
						</td>
					    </tr>
	 					 <tr>
						    <td class="report_table_header">Line Code </td>
						    <td class="report_table_header">Part Number </td>
						    <td class="report_table_header">Number of orders</td>
						    <td class="report_table_header">Search</td>
						 </tr>';
			while($row = mysql_fetch_array($query_freq))
			{
				$html[] = "<tr>\n
						    <td class=\"report_table\">".$row['line_code']."</td>\n
						    <td class=\"report_table\">".$row['part_num']."</td>\n
						    <td class=\"report_table\">".$row['n']."</td>\n
						    <td class=\"report_table\"><a href=\"advanced_search.php?search=true&part_num=".$row['part_num']."&line_code=".$row['line_code']."&ext_search=T\">Go--></a></td>\n
						  </tr>\n";
			}
			$time_stop = microtime();
			$gen = round($time_stop - $time_start,6);
			$html[] = "<tr>\n
						<td colspan=\"4\" class=\"report_table\"><div align=\"center\">Report generated in ".$gen." seconds</div></td>\n
					  </tr>\n";
			$html[] = "</table><br>";
			$content = implode("\n",$html);
		}
		else
		{
			$content = "No duplicate orders";
		}
	}
	//Code to generate Synergy Statistics 
	if($_GET['report'] == 'stats')
	{
		$start_time = microtime(true);
		//Months
		$mo1 = array("2012-07-01 00:00:00","2012-07-31 23:59:59");
		$mo2 = array("2012-08-01 00:00:00","2012-08-31 23:59:59");
		$mo3 = array("2012-09-01 00:00:00","2012-09-31 23:59:59");
		$mo4 = array("2012-10-01 00:00:00","2012-10-31 23:59:59");
	
		$query_all = mysql_query("SELECT * FROM `order`");
		
		$query_228 = mysql_query("SELECT * FROM `order` WHERE `store` = '228' ");
		$query_229 = mysql_query("SELECT * FROM `order` WHERE `store` = '229' ");
		$query_230 = mysql_query("SELECT * FROM `order` WHERE `store` = '230' ");
		$query_341 = mysql_query("SELECT * FROM `order` WHERE `store` = '341' ");
		$query_231 = mysql_query("SELECT * FROM `order` WHERE `store` = '231' ");
		$query_232 = mysql_query("SELECT * FROM `order` WHERE `store` = '232' ");
		$query_233 = mysql_query("SELECT * FROM `order` WHERE `store` = '233' ");
		
		$query_228_mo1 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo1[0]' AND `time` <= '$mo1[1]' AND `store` = 228");
		$query_229_mo1 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo1[0]' AND `time` <= '$mo1[1]' AND `store` = 229");
		$query_230_mo1 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo1[0]' AND `time` <= '$mo1[1]' AND `store` = 230");
		$query_341_mo1 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo1[0]' AND `time` <= '$mo1[1]' AND `store` = 341");
		$query_231_mo1 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo1[0]' AND `time` <= '$mo1[1]' AND `store` = 231");
		$query_232_mo1 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo1[0]' AND `time` <= '$mo1[1]' AND `store` = 232");
		$query_233_mo1 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo1[0]' AND `time` <= '$mo1[1]' AND `store` = 233");
			
		$query_228_mo2 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo2[0]' AND `time` <= '$mo2[1]' AND `store` = 228");
		$query_229_mo2 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo2[0]' AND `time` <= '$mo2[1]' AND `store` = 229");
		$query_230_mo2 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo2[0]' AND `time` <= '$mo2[1]' AND `store` = 230");
		$query_341_mo2 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo2[0]' AND `time` <= '$mo2[1]' AND `store` = 341");
		$query_231_mo2 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo2[0]' AND `time` <= '$mo2[1]' AND `store` = 231");
		$query_232_mo2 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo2[0]' AND `time` <= '$mo2[1]' AND `store` = 232");
		$query_233_mo2 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo2[0]' AND `time` <= '$mo2[1]' AND `store` = 233");
		
		$query_228_mo3 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo3[0]' AND `time` <= '$mo3[1]' AND `store` = 228");
		$query_229_mo3 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo3[0]' AND `time` <= '$mo3[1]' AND `store` = 229");
		$query_230_mo3 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo3[0]' AND `time` <= '$mo3[1]' AND `store` = 230");
		$query_341_mo3 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo3[0]' AND `time` <= '$mo3[1]' AND `store` = 341");
		$query_231_mo3 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo3[0]' AND `time` <= '$mo3[1]' AND `store` = 231");
		$query_232_mo3 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo3[0]' AND `time` <= '$mo3[1]' AND `store` = 232");
		$query_233_mo3 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo3[0]' AND `time` <= '$mo3[1]' AND `store` = 233");
		
		$query_228_mo4 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo4[0]' AND `time` <= '$mo4[1]' AND `store` = 228");
		$query_229_mo4 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo4[0]' AND `time` <= '$mo4[1]' AND `store` = 229");
		$query_230_mo4 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo4[0]' AND `time` <= '$mo4[1]' AND `store` = 230");
		$query_341_mo4 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo4[0]' AND `time` <= '$mo4[1]' AND `store` = 341");
		$query_231_mo4 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo4[0]' AND `time` <= '$mo4[1]' AND `store` = 231");
		$query_232_mo4 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo4[0]' AND `time` <= '$mo4[1]' AND `store` = 232");
		$query_233_mo4 = mysql_query("SELECT * FROM `order` WHERE `time` >= '$mo4[0]' AND `time` <= '$mo4[1]' AND `store` = 233");
		
		$records_all = mysql_num_rows($query_all);
		
		$records_228 = mysql_num_rows($query_228);
		$records_229 = mysql_num_rows($query_229);
		$records_230 = mysql_num_rows($query_230);
		$records_341 = mysql_num_rows($query_341);
		$records_231 = mysql_num_rows($query_231);
		$records_232 = mysql_num_rows($query_232);
		$records_233 = mysql_num_rows($query_233);

		$percent_228 = round(($records_228 * 100) / $records_all);
		$percent_229 = round(($records_229 * 100) / $records_all);
		$percent_230 = round(($records_230 * 100) / $records_all);
		$percent_341 = round(($records_341 * 100) / $records_all);
		$percent_231 = round(($records_231 * 100) / $records_all);
		$percent_232 = round(($records_232 * 100) / $records_all);
		$percent_233 = round(($records_233 * 100) / $records_all);
		
		$records_228_mo1 = mysql_num_rows($query_228_mo1);
		$records_229_mo1 = mysql_num_rows($query_229_mo1);
		$records_230_mo1 = mysql_num_rows($query_230_mo1);
		$records_341_mo1 = mysql_num_rows($query_341_mo1);
		$records_231_mo1 = mysql_num_rows($query_231_mo1);
		$records_232_mo1 = mysql_num_rows($query_232_mo1);
		$records_233_mo1 = mysql_num_rows($query_233_mo1);
		
		$records_228_mo2 = mysql_num_rows($query_228_mo2);
		$records_229_mo2 = mysql_num_rows($query_229_mo2);
		$records_230_mo2 = mysql_num_rows($query_230_mo2);
		$records_341_mo2 = mysql_num_rows($query_341_mo2);
		$records_231_mo2 = mysql_num_rows($query_231_mo2);
		$records_232_mo2 = mysql_num_rows($query_232_mo2);
		$records_233_mo2 = mysql_num_rows($query_233_mo2);
		
		$records_228_mo3 = mysql_num_rows($query_228_mo3);
		$records_229_mo3 = mysql_num_rows($query_229_mo3);
		$records_230_mo3 = mysql_num_rows($query_230_mo3);
		$records_341_mo3 = mysql_num_rows($query_341_mo3);
		$records_231_mo3 = mysql_num_rows($query_231_mo3);
		$records_232_mo3 = mysql_num_rows($query_232_mo3);
		$records_233_mo3 = mysql_num_rows($query_233_mo3);
		
		$records_228_mo4 = mysql_num_rows($query_228_mo4);
		$records_229_mo4 = mysql_num_rows($query_229_mo4);
		$records_230_mo4 = mysql_num_rows($query_230_mo4);
		$records_341_mo4 = mysql_num_rows($query_341_mo4);
		$records_231_mo4 = mysql_num_rows($query_231_mo4);
		$records_232_mo4 = mysql_num_rows($query_232_mo4);
		$records_233_mo4 = mysql_num_rows($query_233_mo4);
		
		if($records_228 > 0)
		{
			$anoka_total = $records_228."(".$percent_228."%)";
		}
		else
		{
			$anoka_total = "No Data";
		}
		if($records_229 > 0)
		{
			$osseo_total = $records_229."(".$percent_229."%)";
		}
		else
		{
			$osseo_total = "No Data";
		}
		if($records_230 > 0)
		{
			$slp_total = $records_230."(".$percent_230."%)";
		}
		else
		{
			$slp_total = "No Data";
		}
		if($records_341 > 0)
		{
			$pl_total = $records_341."(".$percent_341."%)";
		}
		else
		{
			$pl_total = "No Data";
		}
		if($records_231 > 0)
		{
			$buffalo_total = $records_231."(".$percent_231."%)";
		}
		else
		{
			$buffalo_total = "No Data";
		}
		if($records_232 > 0)
		{
			$rogers_total = $records_232."(".$percent_232."%)";
		}
		else
		{
			$rogers_total = "No Data";
		}
		if($records_233 > 0)
		{
			$sm_total = $records_233."(".$percent_233."%)";
		}
		else
		{
			$sm_total = "No Data";
		}
		
		$stop_time = microtime(true);
		$gen = round($stop_time - $start_time,2);
		$content_vars = array("STORE_SELECT" => $ss_html,
							  "ANOKA_TOTAL" => $anoka_total,
						  	  "OSSEO_TOTAL" => $osseo_total,
						  	  "SLP_TOTAL" => $slp_total,
							  "PL_TOTAL" => $pl_total,
							  "BUFFALO_TOTAL" => $buffalo_total,
							  "ROGERS_TOTAL" => $rogers_total,
							  "SM_TOTAL" => $sm_total,
							  "ANOKA_MO1" => $records_228_mo1,
							  "ANOKA_MO2" => $records_228_mo2,
							  "ANOKA_MO3" => $records_228_mo3,
							  "ANOKA_MO4" => $records_228_mo4,
							  "OSSEO_MO1" => $records_229_mo1,
							  "OSSEO_MO2" => $records_229_mo2,
							  "OSSEO_MO3" => $records_229_mo3,
							  "OSSEO_MO4" => $records_229_mo4,
							  "SLP_MO1" => $records_230_mo1,
							  "SLP_MO2" => $records_230_mo2,
							  "SLP_MO3" => $records_230_mo3,
							  "SLP_MO4" => $records_230_mo4,
							  "PL_MO1" => $records_341_mo1,
							  "PL_MO2" => $records_341_mo2,
							  "PL_MO3" => $records_341_mo3,
							  "PL_MO4" => $records_341_mo4,
							  "BUFFALO_MO1" => $records_231_mo1,
							  "BUFFALO_MO2" => $records_231_mo2,
							  "BUFFALO_MO3" => $records_231_mo3,
							  "BUFFALO_MO4" => $records_231_mo4,
							  "ROGERS_MO1" => $records_232_mo1,
							  "ROGERS_MO2" => $records_232_mo2,
							  "ROGERS_MO3" => $records_232_mo3,
							  "ROGERS_MO4" => $records_232_mo4,
							  "SM_MO1" => $records_233_mo1,
							  "SM_MO2" => $records_233_mo2,
							  "SM_MO3" => $records_233_mo3,
							  "SM_MO4" => $records_233_mo4,
							  "MO1" => "July 2012",
							  "MO2" => "August 2012",
							  "MO3" => "September 2012",
							  "MO4" => "October 2012",
							  "GEN" => $gen
							 );
		$content_template = 'html/stats.html';
		$content = template($content_template,$content_vars);				  
	}
	//Number of orders by part number by class
	if($_GET['report'] == 'class')
	{
		$time_start = microtime();
		$query = mysql_query("SELECT `class` FROM `order` WHERE `store` = '$store_info[number]'");
		$class_array = array('W' => 0,'D' => 0,'NO DATA' => 0,'A' => 0,'XD' => 0,'B' => 0,'S' => 0,'XA' => 0,'C' => 0,'XB' => 0,'XC' => 0,'TD' => 0,'NL' => 0,'TW' => 0, 'TA' => 0,'O' => 0,'WW' => 0,'TB' => 0,'TC' => 0,'HD' => 0,'HW' => 0,'H' => 0, 'HZ' => 0, 'HC' => 0, 'HA' => 0, 'Z' => 0);
		while($row = mysql_fetch_array($query))
		{
			$class_array[$row['class']] = $class_array[$row['class']] + 1;
		}
		$html[] =   "<table width=\"60%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n
	 					 <tr>\n
						    <td class=\"report_table_header\">Class</td>\n
						    <td class=\"report_table_header\">Number of orders</td>\n
						    <td class=\"report_table_header\">Search</td>\n
						 </tr>\n";	
		arsort($class_array);	
		foreach($class_array as $key => $var)
		{
			$html[] = "<tr>\n
					    <td class=\"report_table\">".$key."</td>\n
					    <td class=\"report_table\">".$var."</td>\n
					    <td class=\"report_table\"><a href=\"advanced_search.php?search=true&type=class&class=".$key."\">Go--></a></td>\n
					 </tr>\n";
		}
		$time_stop = microtime();
		$gen = round($time_stop - $time_start,6);
		$html[] = "<tr>\n
					<td class=\"report_table\" colspan=\"3\"><div align=\"center\">Report Generated in ".$gen." seconds</div></td>\n
				  </tr>\n
				</table>\n<br>";
		$content = implode("\n",$html);
	}
	//Orders by location
	/*if($_GET['report'] == 'by_loc')
	{
		$start_time = microtime();
		$query = mysql_query("SELECT `id`,`location` FROM `order` WHERE `store` = '$store_info[number]'");
		if(mysql_num_rows($query) > 0)
		{
			$html[] =   "<table width=\"60%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n
	 					 <tr>\n
						    <td class=\"report_table_header\">Location</td>\n
						    <td class=\"report_table_header\">Number of orders</td>\n
						 </tr>\n";
			while($row = mysql_fetch_array($query))
			{
				$loc_array[$row['location']] = $loc_array[$row['location']] + 1;
			}
			arsort($loc_array);
			foreach($loc_array as $key => $var)
			{
				$html[] = "<tr>\n
						    <td class=\"report_table\">".$key."</td>\n
						    <td class=\"report_table\">".$var."</td>\n
						  </tr>\n";
			}	
			$stop_time = microtime();
			$gen = round($stop_time - $start_time,6);
			$html[] = "<tr>\n
						<td colspan=\"2\" class=\"report_table\"><div align=\"center\">Report generated in ".$gen." seconds</div></td>\n
						</tr>\n";
			$html[] = "</table><br>";
			$content = implode("\n",$html);
		}
		else
		{
			$content = "NO DATA";
		}	
	}*/
	if($_GET['report'] == 'class_by_loc')
	{
		$time_start = microtime();
		$query = mysql_query("SELECT `location`,`class` FROM `order` WHERE `store` = '$store_info[number]' ");
		while($row = mysql_fetch_assoc($query))
		{	
			$array[$row['location']][$row['class']] = $array[$row['location']][$row['class']] + 1;
		}
		arsort($array);
		$html[] = "<table width=\"60%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		foreach($array as $key => $var)
		{
			arsort($var);
			$html[] =   "<tr>\n
						    <td colspan=\"2\" class=\"report_table_header\">".$key."</td>\n
						 </tr>\n";
			foreach($var as $key2 => $var2)
			{
				$html[] = "<tr>\n
						    <td class=\"report_table\">".$key2."</td>\n
						    <td class=\"report_table\">".$var2."</td>\n
						  </tr>\n";
			}
		}
		$time_stop = microtime();
		$gen = round($time_stop - $time_start,6);
		$html[] = "<tr>\n
					  <td colspan=\"2\" class=\"report_table\"><div align=\"center\">Report generated in ".$gen." seconds</div></td>\n
				 </tr>\n";
				 
		$html[] = "</table><br>";
		$content = implode("\n",$html);
	}
	$nav_buttons = nav_buttons(array("Main" => "index.php", "Report Selection" => "reports.php")); //Should be the last item in the if(isset($_get[report])) loop
}




if(!isset($_GET['report']))
{
	$report_buttons = array("a" => "<input name=\"report_choice\" type=\"button\" class=\"button\" onClick=\"window.location='reports.php?report=stats'\" value=\"Statistics\" />",
							"b" => "<input name=\"report_choice\" type=\"button\" class=\"button\" onClick=\"window.location='reports.php?report=order_freq'\" value=\"Order Frequency\" />",
							"c" => "<input name=\"report_choice\" type=\"button\" class=\"button\" onClick=\"window.location='reports.php?report=class'\" value=\"# of orders by class\" />",
							"d" => "&nbsp;",
							//"d" => "<input name=\"report_choice\" type=\"button\" class=\"button\" onClick=\"window.location='reports.php?report=by_loc'\" value=\"# of orders by\n location\" />",
							//"e" => "<input name=\"report_choice\" type=\"button\" class=\"button\" onClick=\"window.location='reports.php?report=class_by_loc'\" value=\"Class by Location\" />",
							"e" => "&nbsp;",
							"f" => "&nbsp;",
							"g" => "&nbsp;",
							"h" => "&nbsp;",
							"i" => "&nbsp;"
							);
	$content_vars = array("A" => $report_buttons['a'],
						  "B" => $report_buttons['b'],
						  "C" => $report_buttons['c'],
						  "D" => $report_buttons['d'],
						  "E" => $report_buttons['e'],
						  "F" => $report_buttons['f'],
						  "G" => $report_buttons['g'],
						  "H" => $report_buttons['h'],
						  "I" => $report_buttons['i']
						   );
	$content_template = 'html/report_choice.html';
	$content = template($content_template,$content_vars);				  
} 
//****************Draws the page outline****************************//

$template_vars = array("STORE_SELECT" => $ss_html,
						"TITLE" => "Main",
					   "TABLE_WIDTH" => MAIN_TABLE_WIDTH,
					   "IMAGE" => HEADER_IMAGE,
					   "IMAGE_TEXT" => HEADER_TEXT,
					   "NAV_LINKS" => $nav_buttons,
					   "CONTENT" => $content,
					   "FOOTER" => FOOTER
					   );
$template = 'html/report_page.html';
echo $page = template($template,$template_vars);				  
