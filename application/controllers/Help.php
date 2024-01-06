<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/core/My_Head.php");
class Help extends My_Head {
public function  __construct() 
{ 
	parent:: __construct();
	error_reporting(0);
	$this->load->model('geneal_model');
} 
/***********************************************************************
** Function name 	: index
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for index
** Date 			: 13 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function index()
{  
	$data 					=	array();
	$data['page']			=	'Helps';

	$tbl 					=	'da_cms';
	$where 					=	['page_name' => 'Help'];
	$data['Help']		=	$this->geneal_model->getData($tbl, $where,[]);
//	print_r($data);die;

	$this->load->view('help',$data);
} // END OF FUNCTION

}
