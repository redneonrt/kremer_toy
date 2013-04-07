<?php
/**********************************************
		File Name:	markup.php
		Date:	10-29-2007
		Author:	Chris Reed	
***********************************************/
$title = "Markup";
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
if(isset($_POST['markup']))
{
	$cost = $_POST['cost'];
	$markup = $_POST['markup']/100;
	$sell = $cost*(1/(1-$markup));
	
	//echo $cost."<br>".$markup."<br>".$sell;
	$template_vars = array("STORE_SELECT" => $ss_html,
							"TITLE" => $title,
							"MESSAGE" => "MarkUp Calculator",
							"COST" => $cost,
							"MARKUP" => $markup * 100,
							"SELL" => number_format(round($sell,2),2)
							);
	$template = 'html/markup_b.html';
	echo $page = template($template,$template_vars);
}
else
{
	$template_vars = array("STORE_SELECT" => $ss_html,
							"TITLE" => $title,
							"MESSAGE" => "MarkUp Calculator",
							);
	$template = 'html/markup.html';
	echo $page = template($template,$template_vars);
}
?>