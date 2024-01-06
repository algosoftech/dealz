<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/core/My_Head.php");
class Test extends My_Head {
public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(0);
		$this->load->model(array('geneal_model','common_model','emailsendgrid_model','sms_model'));
		$this->lang->load('statictext','front');
	} 
	/***********************************************************************
	** Function name 	: index
	** Developed By 	: Dilip	halder
	** Purpose 			: This function used for placed order
	** Date 			: 26 December 2023
	** Updated By		: Dilip Halder
	** Updated Date 	: 26 December 2023
	************************************************************************/ 		
	public function ngenius_order_status()
	{
		$access_token = $this->session->userdata('access_token');
		$outletID = "a7b64f18-0fee-4dcd-b326-debc3c867a73";
		$orderRef    = $this->input->get('ref');
		$url = 'https://api-gateway.sandbox.ngenius-payments.com/transactions/outlets/'.$outletID.'/orders/'.$orderRef;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Bearer '.$access_token
		  ),
		));

		$response = curl_exec($curl);
		$response = json_decode($response);
		curl_close($curl);
		
		$orderID 	  	= $response->merchantAttributes->merchantOrderReference;
		$order_responce = $response->_embedded->payment[0]->authResponse->success;
		$order_status   = $response->_embedded->payment[0]->{'3ds'}->status ;
		$referance_id   = $response->reference;

		if($order_status == 'SUCCESS'):
			$updateParams 					=	array('referance_id' => $referance_id);
			$url = base_url('order-status');
		elseif($order_status == 'FAILURE'):
			$updateParams 					=	array( 'order_status' => 'Failed' , 'referance_id' => $referance_id);
			$url = base_url('/');
			$this->session->set_flashdata('error', lang('PAYMENT_FAILED'));
		else:
			$this->session->set_flashdata('error', lang('PAYMENT_CANCEL'));
			$url = base_url('/');
		endif;

		if($order_status):
			$updateorderstatus 				= 	$this->geneal_model->editData('da_orders', $updateParams, 'order_id', $orderID);
		endif;

		redirect($url);

	}
}
