<?php
include 'include/functions.php';
//echo  $hi = draw_shuttle_status_table_new();
?>
<div id="shuttle_table"><?php echo $shuttle_status = file_get_contents('http://72.11.110.203/index.php/shuttle_status'); ?></div>
<div id="current_time"><?php echo date("g:i A l F jS, Y"); ?></div>