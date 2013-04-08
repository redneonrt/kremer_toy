<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_management extends CI_Controller {

	
	function index()
	{
		
		$template_data = array('TITLE'		=>	'Order Managment'
							   
							   );
		$this->parser->parse('order_management/index_view',$template_data);
		$this->output->enable_profiler(TRUE);
	}//EOF
	
	
	function enter_order(){
		$template_data = array('TITLE'		=>	'Order Managment'
							   
							   );
		$this->parser->parse('order_management/enter_order_view',$template_data);
		//$this->output->enable_profiler(TRUE);
	}//EOF
}//EOC

