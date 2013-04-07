<?php
/**********************************************
		File Name:	dc_zero.php
		Date:	10-25-2007
		Author:	Chris Reed	
***********************************************/
require 'include/functions.php';
$referer = $_SERVER['HTTP_REFERER'];
$nav = nav_buttons(array("Main" => "index.php","<--Go Back" => $referer));
$message = "Enter the part number you wish to update<br><br><br><strong>DONT FORGET TO CALL THE CUSTOMER</strong>";
$title = "DC Zero";
$template_vars = array( "STORE_SELECT" => $ss_html,
						"TITLE" => $title,
						"TABLE_WIDTH" => MAIN_TABLE_WIDTH,
						"IMAGE" => HEADER_IMAGE,
						"IMAGE_TEXT" => HEADER_TEXT,
						"NAV_LINKS" => $nav,
						"MESSAGE" => $message,
						"FOOTER" => FOOTER
					);
$template = 'html/dc_zero_a.html';
echo $page = template($template,$template_vars);
?>