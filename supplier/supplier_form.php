<?php
session_name('Supplier_directory');
session_start();
require_once('include/functions.php');
if(isset($_POST['send'])){
	//Preserve User submited Data
	unset($_POST['send']);  //This is not part of the form data so lets remove it now 
	unset($_POST['button']); //Not part of form data so lets remove it
	$_SESSION['preserve_data'] = $_POST;
	//make sure name is not empty
	$field = "name";
	if(empty($_POST[$field])){
		$_SESSION['error'][$field]['field'] = $field;
		$_SESSION['error'][$field]['message'] = "Please choose a name for this contact";
		$_SESSION['error'][$field]['js'] = "$('field_container_".$field."').setStyle({backgroundColor:'#E19380'});\n";
	}
	//$field = NULL;
	//Check to see that either primary phone or website are entered
/*	if(empty($_POST['pri_phone']) && empty($_POST['website'])){
			$_SESSION['error']['pri_phone']['field'] = 'pri_phone';
			$_SESSION['error']['pri_phone']['message'] = "Primary Phone and/or Website  are Required";
			$_SESSION['error']['pri_phone']['js'] = "$('field_container_pri_phone').setStyle({rbackgroundColor:'#E19380'});\n";
			$_SESSION['error']['website']['field'] = 'website';
			$_SESSION['error']['website']['message'] = "Primary Phone and/or Website  are Required";
			$_SESSION['error']['website']['js'] = "$('field_container_website').setStyle({backgroundColor:'#E19380'});\n";
	}
*/	//make sure tags is not empty
	$field = 'tags';
	if(empty($_POST[$field])){
		$_SESSION['error'][$field]['field'] = $field;
		$_SESSION['error'][$field]['message'] = "At least one keyword is required";
		$_SESSION['error'][$field]['js'] = "$('field_container_".$field."').setStyle({backgroundColor:'#E19380'});\n";
	}
	//Check the Primary phone number format
	$field = "pri_phone";
	if(!empty($_POST[$field])){
		$phone_exp = '/^(1?(-?\d{3})-?)?(\d{3})(-?\d{4})$/i';
		if(preg_match($phone_exp,$_POST[$field]) == 0 || !preg_match($phone_exp,$_POST[$field])){
			$_SESSION['error'][$field]['field'] = $field;
			$_SESSION['error'][$field]['message'] = "Invalid phone number, format: xxx-xxx-xxxx";
			$_SESSION['error'][$field]['js'] = "$('field_container_".$field."').setStyle({backgroundColor:'#E19380'});\n";
	 	}
	}
	$field = "website";
	if(!empty($_POST[$field])){
		$website_exp = '/(((ht|f)tp(s?):\/\/)|(www\.[^ \[\]\(\)\n\r\t]+)|(([012]?[0-9]{1,2}\.){3}[012]?[0-9]{1,2})\/)([^ \[\]\(\),;&quot;\'&lt;&gt;\n\r\t]+)([^\. \[\]\(\),;&quot;\'&lt;&gt;\n\r\t])|(([012]?[0-9]{1,2}\.){3}[012]?[0-9]{1,2})/i';
		if(preg_match($website_exp,$_POST[$field]) == 0 || !preg_match($website_exp,$_POST[$field])){
			$_SESSION['error'][$field]['field'] = $field;
			$_SESSION['error'][$field]['message'] = "Invalild web address format: www.example.com";
			$_SESSION['error'][$field]['js'] = "$('field_container_".$field."').setStyle({backgroundColor:'#E19380'});\n";
	 	}
	}
	$field = "alt_phone";
	if(!empty($_POST[$field])){
		$phone_exp = '/^(1?(-?\d{3})-?)?(\d{3})(-?\d{4})$/i';
		if(preg_match($phone_exp,$_POST[$field]) == 0 || !preg_match($phone_exp,$_POST[$field])){
			$_SESSION['error'][$field]['field'] = $field;
			$_SESSION['error'][$field]['message'] = "Invalid phone number, format: xxx-xxx-xxxx";
			$_SESSION['error'][$field]['js'] = "$('field_container_".$field."').setStyle({backgroundColor:'#E19380'});\n";
 		}
	}	
	$field = "state";
	if(!empty($_POST[$field])){
		if(strlen($_POST[$field]) != 2){
			$_SESSION['error'][$field]['field'] = $field;
			$_SESSION['error'][$field]['message'] = "Make sure you are using the 2 letter abbreviation";
			$_SESSION['error'][$field]['js'] = "$('field_container_".$field."').setStyle({backgroundColor:'#E19380'});\n";
	 	}
	}
	$field = "zip_code";
	if(!empty($_POST[$field])){
		$zip_code_regexp = '/^\d{5}$/i';
		if(preg_match($zip_code_regexp,$_POST[$field]) == 0 || !preg_match($zip_code_regexp,$_POST[$field])){
			$_SESSION['error'][$field]['field'] = $field;
			$_SESSION['error'][$field]['message'] = "Invalid Zip Code";
			$_SESSION['error'][$field]['js'] = "$('field_container_".$field."').setStyle({backgroundColor:'#E19380'});\n";
		}	
	}	
	if(isset($_SESSION['error'])){
		$error_array = $_SESSION['error'];
		unset($_SESSION['error']);
		$preserve_data = $_SESSION['preserve_data'];
		unset($_SESSION['preserve_data']);
		$return_message = 'document.getElementById("message").innerHTML = "Errors Found - Please fix the highlighted items below";';
	}
	else{
		 $add = add_new_contact($_POST);
		 if($add['result']){
		 	$return_message = 'document.getElementById("message").innerHTML = "<div align=\"center\"><font color=\"orange\" ><p>CONTACT SAVED</p></font></div>";';
			//$return_js = "$('message').setStyle({backgroundColor:'#00FF00'});\n";
		}
		else{
			$return_message = 'document.getElementById("message").innerHTML = "ERROR - '.$add['message'].'";';
		}
			
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="include/js/prototype/prototype-1.6.0.2.js"></script>
<script type="text/javascript" src="include/js/scriptaculous/scriptaculous.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
	//Sets up the accordions when the page loads
	Event.observe(window, 'load', loadEvents, false);	
	function loadEvents(){
		$('part_b').hide();
		//Display the message and javascript		
		<?php
		if(isset($return_message)){
			echo $return_message;
		}
		if(isset($return_js)){
			echo $return_js;
		}
		?>
		//Next its time to assign all return values to their respective forms
		<?php
			//If the user filled out any inputs in the additional info section--expand that section
			if(!empty($preserve_data['alt_phone']) || !empty($preserve_data['street_address']) || !empty($preserve_data['city']) || !empty($preserve_data['state']) || !empty($preserve_data['zip_code'])){
				//If part_b was open, then reopen it and change the button label to close
				echo "$('part_b').show();\n";
				echo "$('part_b_button').replace('<input name=\"button\" type=\"button\" id=\"part_b_button\" onClick=\"close_part_b();\" value=\"- Close panel\" />');\n
";
			}	
			//Fill in all the <span> tags with the generated error messages		
			if(isset($error_array)){
				foreach($error_array as $key => $var){
					echo "document.getElementById(\"".$var['field']."_error_message\").innerHTML = \"".$var['message']."\";\n";
					echo $var['js'];
				}
				foreach($preserve_data as $key => $var){
					echo "document.getElementById(\"".$key."\").value = \"".$var."\";\n";
				}
			}		
		?>
	}
	function open_part_b(){
		$('part_b_button').replace('<input name="button" type="button" id="part_b_button" onClick="close_part_b();" value="- Close panel" />');
		//If you change the text of the button dont forget to change the code on line 126 to match
		Effect.SlideDown('part_b');

	}
	function close_part_b(){
		$('part_b_button').replace('<input name="button" type="button" id="part_b_button" onClick="open_part_b();" value="+ more details" />');
		Effect.SlideUp('part_b');
	}
	function check_form(){
		document.primary_info.submit();				  
	}
</script>
<title>Add Supplier</title>
<link href="include/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="main">
  <div id="modernbricksmenu">
    <ul>
		<li><a href="./">Main</a></li>
		<li id="current"><a href="supplier_form.php">Add</a></li>
		<li><a href="keywords.php">Keyword Display</a></li>
        <li><a href="search.php">Search</a></li>
		<li><a href="../synergy/">Go to -> Synergy</a></li>
    </ul>
  </div>
  <div id="modernbricksmenuline">&nbsp;</div>
  <div id="message">&nbsp;</div>
  <form action="supplier_form.php" method="post" name="primary_info" id="primary_info">
    <fieldset>
    <legend>Add new supplier</legend>
    <ul id="reminder">
      <li><img src="include/images/info_20x20.png" />Required fields are marked with a *</li>
      <li><img src="include/images/info_20x20.png" />Keep keywords simple (HD, brakes, electrical ) are a few examples</li>
    </ul>
    <div class="field_container" id="field_container_name">
      <label id="field_label" for="name">Supplier/Vendor</label>
      <input name="name" type="text" class="text_field" id="name" value="" /><span id="required"> *</span>
      <span class="error_message" id="name_error_message"></span>
      <div id="name_format">&nbsp;</div>
    </div>
    <div class="field_container" id="field_container_pri_phone">
      <label id="field_label" for="pri_phone">Primary Phone Number</label>
      <input id="pri_phone" class="text_field" name="pri_phone" type="text" value="" />
      <span class="error_message" id="pri_phone_error_message"></span>
      <div id="pri_phone_format">xxx-xxx-xxxx</div>
    </div>
    <div class="field_container" id="field_container_website">
      <label id="field_label" for="website"> Website URL</label>
      <input id="website" class="text_field" name="website" type="text" value="" />
      <div id="website_format">www.example.com</div>
    </div>
    <div class="field_container" id="field_container_tags">
      <label id="field_label" for="tags"> Keywords</label>
      <input name="tags" type="text" class="text_field" id="tags" /><span id="required"> *</span>
      <span class="error_message" id="tags_error_message"></span>
      <div id="tags_format">Seperate multiple keywords with a comma</div>
    </div>
    <div class="field_container" id="field_container_notes">
      <label id="field_label"  for="notes"> Additional Comments</label>
      <textarea name="comments" cols="30" rows="3" wrap="virtual" class="notes" id="notes"></textarea>
      <div id="notes_format">Account Number / Specialty</div>
    </div>
    </fieldset>
    <p>
      <input name="button" type="button" id="part_b_button" onClick="open_part_b();" value="+ more details" />
    </p>
    <div id="part_b">
      <div id="part_b_content">
        <fieldset>
        <legend>Additinal Information</legend>
        <div class="field_container" id="field_container_alt_phone">
          <label id="field_label" for="alt_phone"> Alternate Phone Number</label>
          <input id="alt_phone" class="text_field" name="alt_phone" type="text" value="" />
          <span class="error_message" id="alt_phone_error_message"></span>
          <div id="alt_phone_format">xxx-xxx-xxxx</div>
        </div>
        <div class="field_container" id="field_container_street_address">
          <label id="field_label" for="street_address"> Street Address</label>
          <input id="street_address" class="text_field" name="street_address" type="text" value="" />
          <span class="error_message" id="street_address_error_message"></span>
          <div id="street_address_format">eg. 733 East River Rd.</div>
        </div>
        <div class="field_container" id="field_container_city">
          <label id="field_label" for="city"> City</label>
          <input id="city" class="text_field" name="city" type="text" value="" />
          <span class="error_message" id="city_error_message"></span>
          <div id="city_format">eg. Anoka</div>
        </div>
        <div class="field_container" id="field_container_state">
          <label id="field_label" for="state"> State</label>
          <input name="state" type="text" class="text_field" id="state" maxlength="2"/>
          <span class="error_message" id="state_error_message"></span>
          <div id="state_format">Valid 2 letter abbreviation please</div>
        </div>
        <div class="field_container" id="field_container_zip_code">
          <label id="field_label" for="zip_code"> Zip Code</label>
          <input name="zip_code" type="text" class="text_field" id="zip_code" maxlength="5" />
          <span class="error_message" id="zip_code_error_message"></span>
          <div id="zip_code_format">Valid 5 digit Zip Code please</div>
          <div id="field_container"> </div>
        </div>
        </fieldset>
      </div>
    </div>
    <div id="part_c">
      <input name="send" type="hidden" value="send" />
      <br />
      <input name="button" type="button" id="button" onclick="javascript:check_form();" value="Save Contact" />
      <input name="reset" type="reset" id="button" value="Reset Form" />
    </div>
  </form>
</div>
</body>
</html>
