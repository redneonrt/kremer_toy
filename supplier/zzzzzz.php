<?php
require_once('include/functions.php');
//var_dump($_POST);
$display_notes = NULL;
if(isset($_GET['z'])){
	if(isset($_POST['new_note'])){
		if(!empty($_POST['new_note'])){
			$add_br = str_replace("\n","<br />",$_POST['new_note']);
			$new_note = mysql_real_escape_string($add_br);
			$save_sql = "INSERT INTO `notes` (`contact_id`,`note`) VALUES ('$_GET[z]','$new_note')";
			$note_query = mysql_query($save_sql);
			if(!$note_query){
				echo 'MySQL Error';
			}
		}
	}
	if(isset($_POST['add_tag'])){
		if(!empty($_POST['add_tag'])){
			$add_new_tag = $_POST['add_tag'];
			$add_tag_sql = "INSERT INTO `tags` (`entry_id`,`tag`) VALUES ('$_GET[z]','$add_new_tag')";
			$add_tag_query = mysql_query($add_tag_sql);
		}
	}
	if(isset($_POST['remove_tag_id'])){
		if(!empty($_POST['remove_tag_id'])){
			$delete_tag_sql = "DELETE FROM `tags` WHERE `id` = '$_POST[remove_tag_id]' LIMIT 1";
			mysql_query($delete_tag_sql);
		}
	}
	if(isset($_POST['delete_note'])){
		$delete_sql = "DELETE FROM `notes` WHERE `id` = '$_POST[note_id]' LIMIT 1";
		mysql_query($delete_sql);
	}
	if(isset($_POST['update_note'])){
		if(!empty($_POST['note'])){
			$update_note = mysql_real_escape_string(str_replace("\n","<br />",$_POST['note']));
			$update_sql = "UPDATE `notes` SET `time` = CURRENT_TIMESTAMP , `note` = '$update_note' WHERE `id` = '$_POST[note_id]' LIMIT 1";
			mysql_query($update_sql);
		}
		else{
			$delete_sql = "DELETE FROM `notes` WHERE `id` = '$_POST[note_id]' LIMIT 1";
			mysql_query($delete_sql);
		}
	}
	$sql = "SELECT * FROM `contacts` WHERE `id` = '$_GET[z]' LIMIT 1";
	$query = mysql_query($sql);
	if($query){
		if(mysql_num_rows($query) == 1){
			$contact_details = mysql_fetch_assoc($query);
			$notes_query = mysql_query("SELECT * FROM `notes` WHERE `contact_id` = '$_GET[z]'");
			if(mysql_num_rows($notes_query) > 0 ){
				//display notes
				while($row = mysql_fetch_assoc($notes_query)){
					if(isset($_POST['edit_id'])){
						$edit_id = $_POST['edit_id'];
					}
					else{
						$edit_id = NULL;
					}
					if($edit_id == $row['id']){
						$note_html[] = "
						<fieldset>
							<form action=\"zzzzzz.php?z=".$_GET['z']."\" method=\"post\">
								<legend>".date("F jS, Y",strtotime($row['time']))."</legend>
								<div>
									<input name=\"note_id\" type=\"hidden\" value=\"".$row['id']."\" />
									<textarea id=\"add_note_field\" name=\"note\" class=\"notes\">".str_replace("<br />","\n",$row['note'])."</textarea>
								</div>
								<input id=\"save_button\" name=\"update_note\" type=\"submit\" value=\"Save Changes\" />
							</form>
						</fieldset>";
					}
					else{
						$note_html[] = "
						<fieldset>
							<legend>".date("F jS, Y",strtotime($row['time']))."</legend>
							<div id=\"note_controls\">
								<form action=\"zzzzzz.php?z=".$_GET['z']."\" method=\"post\">
									<input name=\"note_id\" type=\"hidden\" value=\"".$row['id']."\" />
							     	<input type=\"image\"  name=\"delete_note\" src=\"include/images/30px_delete.png\" value=\"delete_note\" >
								</form>
								<form action=\"zzzzzz.php?z=".$_GET['z']."\" method=\"post\">
									<input name=\"edit_id\" type=\"hidden\" value=\"".$row['id']."\" />
							     	<input type=\"image\" src=\"include/images/30px_editpad.png\" >
								</form>
							</div>
							<div>".$row['note']."</div>
						</fieldset>";
					}
				}
				$display_notes = implode("\n",$note_html);
			}
		}
	}
	//Create tag links
	$tag_sql = "SELECT * FROM `tags` WHERE(`entry_id` = '$_GET[z]')";
	$tag_fetch_sql = mysql_query($tag_sql);
	if(mysql_num_rows($tag_fetch_sql) > 0){
		$tag_html = NULL;
		while($row = mysql_fetch_assoc($tag_fetch_sql)){
			$indv_tags[] = "<div id=\"tag_box\"><form class=\"inline\" action=\"\" method=\"post\"><a href=\"search.php?tag_search=".$row['tag']."\">".$row['tag']."</a> <input type=\"image\" src=\"include/images/11px_delete.gif\" ><input name=\"remove_tag_id\" type=\"hidden\" value=\"".$row['id']."\" /></form></div>";
		}
		$tag_html = implode(' ',$indv_tags);
	}
	else{
		$tag_html = NULL;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="include/js/prototype/prototype-1.6.0.2.js"></script>
<script type="text/javascript" src="include/js/scriptaculous/scriptaculous.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Supplier Detail</title>
<link href="include/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
document.observe('dom:loaded', function() { 
		$('add_note_form').hide();
	});
function show_form(){
	//$('add_note_form').show();
	Effect.Grow('add_note_form',{direction: 'top-left'});
	$('part_b_button').remove();
}
<!-- Start Greybox Code-->
var GB_ROOT_DIR = "include/greybox/";
</script>
<script type="text/javascript" src="include/greybox/AJS.js"></script>
<script type="text/javascript" src="include/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="include/greybox/gb_scripts.js"></script>
<link href="include/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<!--End greybox Code-->
</head>
<body>
<div id="main">
  <div id="modernbricksmenu">
    <ul>
      <li><a href="./">Main</a></li>
      <li><a href="supplier_form.php">Add</a></li>
      <li><a href="keywords.php">Keyword Display</a></li>
      <li><a href="search.php">Search</a></li>
      <li><a href="../synergy/">Go to -> Synergy</a></li>
    </ul>
  </div>
  <div id="modernbricksmenuline">&nbsp;</div>
  <div id="contact_form">
    <fieldset id="details">
      <legend>Contact Details</legend>
      <div id="ajax_edit_name" class="contact_form_field"><?php echo $contact_details['name']; ?></div>
      <script type="text/javascript">
		new Ajax.InPlaceEditor('ajax_edit_name','ajax_edit.php',{
			okText: '',
			cancelText: '',
			cancelControl:'button',
			rows: 1,
			highlightcolor:'#666666',
			highlightendcolor: '#0066CC',
			formClassName: 'ajax_edit_form',
			savingClassName: 'ajax_saving',
			callback: function(form, value){return 'field=name&id=<?php echo $contact_details['id'];?>&value='+encodeURIComponent(value) } 
		});
	</script>
      <?php
	if(!empty($contact_details['pri_phone'])){
		echo '<span id="ajax_edit_pri_phone" class="contact_form_field">'.phone_num_format($contact_details['pri_phone']).'</span><span class="label" id="pri_phone_label"> (Primary Phone)</span>';
		?>
      <script type="text/javascript">
			new Ajax.InPlaceEditor('ajax_edit_pri_phone','ajax_edit.php',{
				okText: '',
				cancelText: '',
				cancelControl:'button',
				rows: 1,
				highlightcolor:'#666666',
				highlightendcolor: '#0066CC',
				formClassName: 'ajax_edit_form',
				savingClassName: 'ajax_saving',
				callback: function(form, value){
					return 'field=pri_phone&id=<?php echo $contact_details['id'];?>&value='+encodeURIComponent(value);
				},
				onComplete:function(){
					if($('ajax_edit_pri_phone').empty()){
						$('pri_phone_label').hide();
					}
				}
			});
		</script>
      <?php
	}
	if(!empty($contact_details['alt_phone'])){
		echo '<br /><span id="ajax_edit_alt_phone" class="contact_form_field">'.phone_num_format($contact_details['alt_phone']).'</span><span id="alt_phone_label" class="label"> (Alternate Phone)</span>';
		?>
      <script type="text/javascript">
			new Ajax.InPlaceEditor('ajax_edit_alt_phone','ajax_edit.php',{
				okText: '',
				cancelText: '',
				cancelControl:'button',
				rows: 1,
				highlightcolor:'#666666',
				highlightendcolor: '#0066CC',
				formClassName: 'ajax_edit_form',
				savingClassName: 'ajax_saving',
				callback: function(form, value){
					return 'field=alt_phone&id=<?php echo $contact_details['id'];?>&value='+encodeURIComponent(value);
				},
				onComplete: function(transport,element){
					if($('ajax_edit_alt_phone').empty()){
						$('alt_phone_label').hide();
					}						
				}
			});
		</script>
      <?php
	}
	if(!empty($contact_details['web_address'])){
		echo '<div id="ajax_edit_web_address_container" class="contact_form_field"><a id="ajax_edit_web_address" href="http://'.$contact_details['web_address'].'" target="_blank">http://'.$contact_details['web_address'].'</a><span class="label" id="web_edit"> Click to edit</span></div>';
		?>
      <script type="text/javascript">
			new Ajax.InPlaceEditor('ajax_edit_web_address','ajax_edit.php',{
				okText: '',
				cancelText: '',
				cancelControl:'button',
				rows: 3,
				highlightcolor:'#666666',
				highlightendcolor: '#0066CC',
				formClassName: 'ajax_edit_form',
				savingClassName: 'ajax_saving',
				externalControl: 'web_edit',
				externalControlOnly: true,
				callback: function(form, value){
					return 'field=web_address&id=<?php echo $contact_details['id'];?>&value='+encodeURIComponent(value);
				},
				onComplete: function(transport,element){
					$('ajax_edit_web_address').writeAttribute({href: this.element.innerHTML});
					if($('ajax_edit_web_address').empty()){
						$('ajax_edit_web_address_container').hide();
					}						
				}
			});
		</script>
      <?php
	}
	if(!empty($contact_details['comments'])){
		echo '<fieldset id="comments_box">
		<legend>Addition Comments</legend>
		<div id="ajax_edit_comments" class="contact_form_field">'.trim($contact_details['comments']).'</div>
		</fieldset>';
		?>
      <script type="text/javascript">
				new Ajax.InPlaceEditor('ajax_edit_comments','ajax_edit.php',{
					okText: '',
					cancelText: '',
					cancelControl:'button',
					rows: 4,
					highlightcolor:'#666666',
					highlightendcolor: '#0066CC',
					formClassName: 'ajax_edit_form',
					savingClassName: 'ajax_saving',
					callback: function(form, value){
						return 'field=comments&id=<?php echo $contact_details['id'];?>&value='+encodeURIComponent(value);
					},
					onComplete: function(){
						if($('ajax_edit_comments').empty()){
							$('comments_box').hide();
						};
					}
				});
		</script>
      <?php	
	}
	if(!empty($contact_details['street_address']) || !empty($contact_details['city']) || !empty($contact_details['state']) || !empty($contact_details['zip_code'])){
		echo '<fieldset id="address_information">
    			<legend>Address information</legend>';
		if(!empty($contact_details['street_address'])){
			echo '<div class="contact_form_field"><span id="ajax_edit_street_address">'.$contact_details['street_address'].'</span></div>';
			?>
      <script type="text/javascript">
				new Ajax.InPlaceEditor('ajax_edit_street_address','ajax_edit.php',{
					okText: '',
					cancelText: '',
					cancelControl:'button',
					rows: 1,
					highlightcolor:'#666666',
					highlightendcolor: '#0066CC',
					formClassName: 'ajax_edit_form',
					savingClassName: 'ajax_saving',
					callback: function(form, value){
						return 'field=street_address&id=<?php echo $contact_details['id'];?>&value='+encodeURIComponent(value);
					},
					onComplete: function(){
						if($('ajax_edit_street_address').empty()){
							$('ajax_edit_street_address').hide();
						};
						if($('ajax_edit_street_address').empty() && $('ajax_edit_city').empty() && $('ajax_edit_state').empty() && $('ajax_edit_zip_code').empty()){
							$('address_information').hide();
						}
					}
				});
			</script>
      <?php
		}
		if(!empty($contact_details['city']) && !empty($contact_details['state']) && !empty($contact_details['zip_code'])){	
			echo '<div class="contact_form_field"><span id="ajax_edit_city">'.$contact_details['city'].'</span> <span id="ajax_edit_state">'.$contact_details['state'].'</span>, <span id="ajax_edit_zip_code">'.$contact_details['zip_code'].'</span></div>';
		}
		elseif(!empty($contact_details['city']) || !empty($contact_details['state']) || !empty($contact_details['zip_code'])){
			echo '<div class="contact_form_field"><span id="ajax_edit_city">'.$contact_details['city'].'</span> <span id="ajax_edit_state">'.$contact_details['state'].'</span> <span id="ajax_edit_zip_code">'.$contact_details['zip_code'].'</span></div>';
		}
		echo '</fieldset>';
	}
	?>
      <script type="text/javascript">
			<?php
			if(!empty($contact_details['city'])){
			?>
			new Ajax.InPlaceEditor('ajax_edit_city','ajax_edit.php',{
				okText: '',
				cancelText: '',
				cancelControl:'button',
				rows: 1,
				highlightcolor:'#666666',
				highlightendcolor: '#0066CC',
				formClassName: 'ajax_edit_form',
				savingClassName: 'ajax_saving',
				callback: function(form, value){
					return 'field=city&id=<?php echo $contact_details['id'];?>&value='+encodeURIComponent(value);
				},
				onComplete: function(){
					if($('ajax_edit_city').empty()){
						$('ajax_edit_city').hide();
					};
					if($('ajax_edit_street_address').empty() && $('ajax_edit_city').empty() && $('ajax_edit_state').empty() && $('ajax_edit_zip_code').empty()){
						$('address_information').hide();
					}
				}
			});
			<?php
			}
			if(!empty($contact_details['state'])){
			?>
			new Ajax.InPlaceEditor('ajax_edit_state','ajax_edit.php',{
				okText: '',
				cancelText: '',
				cancelControl:'button',
				rows: 1,
				highlightcolor:'#666666',
				highlightendcolor: '#0066CC',
				formClassName: 'ajax_edit_form',
				savingClassName: 'ajax_saving',
				callback: function(form, value){
					return 'field=state&id=<?php echo $contact_details['id'];?>&value='+encodeURIComponent(value);
				},
				onComplete: function(){
						if($('ajax_edit_state').empty()){
							$('ajax_edit_state').hide();
						};
					if($('ajax_edit_street_address').empty() && $('ajax_edit_city').empty() && $('ajax_edit_state').empty() && $('ajax_edit_zip_code').empty()){
						$('address_information').hide();
					}
				}
			});
			<?php
			}
			if(!empty($contact_details['zip_code'])){
			?>
			new Ajax.InPlaceEditor('ajax_edit_zip_code','ajax_edit.php',{
				okText: '',
				cancelText: '',
				cancelControl:'button',
				rows: 1,
				highlightcolor:'#666666',
				highlightendcolor: '#0066CC',
				formClassName: 'ajax_edit_form',
				savingClassName: 'ajax_saving',
				callback: function(form, value){
					return 'field=zip_code&id=<?php echo $contact_details['id'];?>&value='+encodeURIComponent(value);
				},
				onComplete: function(){
						if($('ajax_edit_zip_code').empty()){
							$('ajax_edit_zip_code').hide();
						};
					if($('ajax_edit_street_address').empty() && $('ajax_edit_city').empty() && $('ajax_edit_state').empty() && $('ajax_edit_zip_code').empty()){
						$('address_information').hide();
					}
				}
			});
			<?php
			}
			?>
		</script>
      <fieldset>
        <legend>Keywords</legend>
        <form action="" method="post">
          <div class="contact_form_field"><?php echo $tag_html; ?></div>
        </form>
        <div>
          <form action="" method="post">
            <br />
            <input class="text_field_small" name="add_tag" type="text" />
            <input id="save_button" name="add_tag_button" type="submit" value="Add Keyword" />
          </form>
        </div>
      </fieldset>
      <fieldset>
        <legend>*Note*</legend>
        <div class="contact_form_field">
          <p>Further  editing will be available in the future but for now just call Chris at extension 641 to request any changes.</p>
          <p> If there is no answer please leave a message
          <p>All other fields can be changed, just click to edit</p>
          </p>
        </div>
      </fieldset>
      <fieldset>
        <legend>Additional Options</legend>
        <div><a href="delete_contact.php?id=<?php echo $_GET['z'] ?>" rel="gb_page[225,150]" title="Remove Entry">Remove Entry</a></div>
      </fieldset>
    </fieldset>
    <fieldset id="notes">
      <legend>User Notes</legend>
      <div class="contact_form_field"><?php echo $display_notes; ?></div>
      <input name="add_note" type="button" id="part_b_button" onClick="javascript:show_form()" value="+ New Note" />
      <div id="add_note_form">
        <div>
          <form action="zzzzzz.php?z=<?php echo $_GET['z']; ?>" method="post">
            <textarea id="add_note_field" name="new_note" cols="" rows="" class="notes"></textarea>
            <div>
              <input id="save_button" name="save_note" type="submit" value="Save Note" />
            </div>
          </form>
        </div>
      </div>
    </fieldset>
  </div>
</div>
</body>
</html>
<?php
}
else{
	header('location:index.php');
}
?>
