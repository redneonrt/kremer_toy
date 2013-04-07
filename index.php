<?php
/**********************************************
		File Name:	index.php
		Date:	10-6-2007
		Updated: 10-30-2012
		Author:	Chris Reed	
***********************************************/
require 'include/functions.php';
$nav_buttons = nav_buttons(array("Main" => "index.php",
								 "Enter Order" => "local.php",
								 "Search" => "advanced_search.php",
								 "Review Orders" => "tracking.php",
								 "Receiving" => "receiving.php",
								 "Shuttle Call Backs" => "shuttle.php",
								 "Shuttle Manager" => "../index.php/shuttle_manager/",
								 "Supplier Directory" => "../supplier",
								 "Report a problem" => "../index.php/incident_report/",
								 "Cottens' Home" => "/"));
/*Get the contents of the shuttle status page*/
$server_ip = server_address();
$shuttle_status = file_get_contents('http://'.$server_ip.'/index.php/shuttle_status');
//Vars to get ready to build the page
$template_vars = array("STORE_SELECT" => $ss_html,
					   "TITLE" => "Synergy",
					   "TABLE_WIDTH" => MAIN_TABLE_WIDTH,
					   "IMAGE" => HEADER_IMAGE,
					   "IMAGE_TEXT" => HEADER_TEXT,
					   "NAV_LINKS" => $nav_buttons,
					   "FOOTER" => FOOTER,
					   "MY_IP" => $_SERVER['REMOTE_ADDR'],
					   "SHUTTLE_STATUS_TABLE" => $shuttle_status,
					   "AJAX:RELOAD" => AJAX_RELOAD
					   );

$template = 'html/index.html';
echo $page = template($template,$template_vars);
?>