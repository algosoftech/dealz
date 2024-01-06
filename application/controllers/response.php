<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/core/My_Head.php");
class response extends CI_Controller {
public function  __construct() 
{ 
	parent:: __construct();
	error_reporting(0);
	$this->load->model('geneal_model');
	$this->lang->load('statictext','front');
} 
/***********************************************************************
** Function name 	: index
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for show to cart items
** Date 			: 28 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function index()
{  
	$data  = array();
	$data['page'] 			=	'Payment Response';

	$where = ['order_id' => $this->session->userdata('insertID')];
	$data['oderDetails'] = $this->geneal_model->getOnlyOneData('da_orders', $where);

	$this->load->view('payment-response', $data);
} // END OF FUNCTION


}
