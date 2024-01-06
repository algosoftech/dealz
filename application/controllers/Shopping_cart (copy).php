<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shopping_cart extends CI_Controller {
public function  __construct() 
{ 
	parent:: __construct();
	$this->load->model('geneal_model');
} 
/***********************************************************************
** Function name 	: index
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for show to cart items
** Date 			: 20 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function index()
{  
	$data  = array();
	if($this->session->userdata('DZL_USERID')){
		//$data['cartItems']  =	$this->geneal_model->getData2('multiple', 'da_cartItems', []);
		redirect('user-cart');
	}else{
		$data['cartItems'] 	= 	$this->cart->contents();
	}

	if(empty($data['cartItems'])){
		redirect('home');
	}

	$wcon['where']          =  array('stock'=> array('$ne'=> 0));
	$data['products']  =	$this->geneal_model->getData2('multiple', 'da_products', $wcon, [], 3);

	//print_r($data['products']);die();


	$this->load->view('cart', $data);
} // END OF FUNCTION

/***********************************************************************
** Function name 	: userCartItems
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for show to user cart items
** Date 			: 21 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function userCartItems()
{  
	$data  = array();
	if($this->session->userdata('DZL_USERID')){
		$wcon['where']		=	[ 'user_id' => (int)$this->session->userdata('DZL_USERID') ];
		$data['cartItems']  =	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);
		

	}else{
		redirect('login');
	}

	$this->load->view('user_cart', $data);

} // END OF FUNCTION

/***********************************************************************
** Function name 	: add
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for add product to cart
** Date 			: 21 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function add()
{  	
	if($this->session->userdata('DZL_USERID')){

		$wcon1['where'] = [	'user_id' 	=> 	(int)$this->session->userdata('DZL_USERID'),
							'id' =>	(int)$this->input->post('product_id') ];

		$check = $this->geneal_model->getData2('count','da_cartItems',$wcon1);

		//echo $check; die();
		if($check == 0){
			$where = ['products_id' => (int)$this->input->post('product_id') ];
			$product_data = $this->geneal_model->getOnlyOneData('da_products', $where);
			//print_r($product_data);
			$data = array(
				'user_id'	=>	(int)$this->session->userdata('DZL_USERID'),
				'rowid'		=>	$this->geneal_model->getNextSequence(),
				'id'		=>	$product_data['products_id'],
				'name'		=>	$product_data['title'],
				'qty'		=>	1,
				'price' 	=>	$product_data['adepoints'],
				'other' 	=>	array(
									'image' 		=>	$product_data['product_image'],
									'description' 	=>	$product_data['description'],
									'aed'			=>	$product_data['adepoints'],
								),
			);
			$tbl 			=	'da_cartItems';
			$isInsert		=	$this->geneal_model->addData($tbl, $data);
		}
		$wcon2['where'] = ['user_id' => (int)$this->session->userdata('DZL_USERID')];
		$rows = $this->geneal_model->getData2('count','da_cartItems',$wcon2);

	}else{
		$where = ['products_id' => (int)$this->input->post('product_id') ];
		$product_data = $this->geneal_model->getOnlyOneData('da_products', $where);
		$data = array(
		    'user_id'	=>	(int)$this->session->userdata('DZL_USERID'),
			'id'		=>	$product_data['products_id'],
			'name'		=>	$product_data['title'],
			'qty'		=>	1,
			'price' 	=>	$product_data['adepoints'],
			'other' 	=>	array(
								'image' 		=>	$product_data['product_image'],
								'description' 	=>	$product_data['description'],
								'aed'			=>	$product_data['adepoints'],
							),
		);
		$this->cart->insert($data);
		$rows = count($this->cart->contents());
	}
	echo $rows;
} // END OF FUNCTION

/***********************************************************************
** Function name 	: remove_items
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for remove product to cart
** Date 			: 20 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function remove_items($id='')
{  
	if($this->session->userdata('DZL_USERID')){
		//$where = [ 'rowid' => (int)$id ];
		$isDelete = $this->geneal_model->deleteData('da_cartItems', 'rowid', (int)$id );
		redirect('user-cart');
	}else{
		$data = array(
		'rowid'	=>	$id,
		'qty'	=>	0,
		);
	$this->cart->update($data);
	redirect('shopping-cart');	
	}
} // END OF FUNCTION

/***********************************************************************
** Function name 	: addqty
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for all product qty
** Date 			: 20 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function addqty()
{  	
	$port_data 	=	explode('___', $_POST['data']);
	$aed 		=	(int)$port_data[0];
	$rowid 		=	$port_data[1];
	if($this->session->userdata('DZL_USERID')){
		$where 	=	[ 'rowid' => (int)$rowid ];
		$product = $this->geneal_model->getOnlyOneData('da_cartItems', $where);
		$getQty = $product['qty'];
		if($port_data[2] == 'I'){ $qty  = ++$getQty; }
		else{ 
			$qty = ($getQty == 1) ? 1 : --$getQty;
			//$qty  = --$getQty; 
		}		
		$data = array( 'qty'			=>	$qty );
		$isUpdate		=	$this->geneal_model->editData('da_cartItems', $data, 'rowid', (int)$rowid);
		$cartData[1] 		= $qty * $aed;

		$wcon['where']		=	[ 'user_id' => (int)$this->session->userdata('DZL_USERID') ];
		$cartItems  		=	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);

		$totalAmt = 0;
		foreach ($cartItems as $items) {
			$totalAmt =  $totalAmt + $items['qty'] * $items['other']->aed;
		}
		$cartData[2] = $totalAmt;
		$cartData[3] = $totalAmt * 5/100;
		$cartData[4] = $cartData[2] + $cartData[3];
		//echo $cartData[1].'__'.$cartData[2].'__'.$cartData[3].'__'.$cartData[4];
		echo number_format($cartData[1],2).'__'.number_format($cartData[2],2).'__'.number_format($cartData[3],2).'__'.number_format($cartData[4],2);
	}else{
		foreach ($this->cart->contents() as $items) {
			if($items['rowid'] == $rowid){
				$getQty = $items['qty'];
				break;
			}
		}
		if($port_data[2] == 'I'){ $qty  = ++$getQty; }
		else{ 
			$qty = ($getQty == 1) ? 1 : --$getQty;
			//$qty  = --$getQty; 
		}
		$data = array(
		'rowid'			=>	$rowid,
		'qty'			=>	$qty,
		);
		$this->cart->update($data);
		$cartData[1] 			= $qty * $aed;	

		$totalAmt = 0;

		foreach ($this->cart->contents() as $items) {
			$totalAmt =  $totalAmt + $items['qty'] * $items['other']['aed'];
		}
		$cartData[2] = $totalAmt;
		$cartData[3] = $totalAmt * 5/100;
		$cartData[4] = $cartData[2] + $cartData[3];
		//print_r($cartData); die();

		//echo $cartData[1].'__'.$cartData[2].'__'.$cartData[3].'__'.$cartData[4];
		echo number_format($cartData[1],2).'__'.number_format($cartData[2],2).'__'.number_format($cartData[3],2).'__'.number_format($cartData[4],2);
	}	
	
} // END OF FUNCTION

/***********************************************************************
** Function name 	: donate
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for add donate tag in cart
** Date 			: 18 MAY 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 
public function donate(){
	$donate =	$_POST['donate'];
	$rowid 	=	$_POST['rowid'];
	$where =	['rowid' => (int)$rowid ];

	$cartItem = $this->geneal_model->getOnlyOneData('da_cartItems', $where);
	$param['other']['image']	=	$cartItem['other']->image;
	$param['other']['aed']		=	$cartItem['other']->aed;
	$param['other']['description']		=	$cartItem['other']->description;

	if($donate == 'Y'){ $param['other']['donate'] = 'Y'; }
	else{ $param['other']['donate'] = 'N'; }
	$idEdit = $this->geneal_model->editData('da_cartItems', $param, 'rowid', (int)$rowid);
	echo $donate;
}// END OF FUNCTION

}
