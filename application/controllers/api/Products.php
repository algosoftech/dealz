<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {
	
	var $postdata;
	var $user_agent;
	var $request_url; 
	var $method_name;
	
	public function  __construct() 	
	{ 
		parent:: __construct();
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);
		$this->load->model(array('sms_model','notification_model','emailtemplate_model','emailsendgrid_model'));
		$this->lang->load('statictext', 'api');
		$this->load->helper('apidata');
		$this->load->model(array('geneal_model','common_model'));

		$this->user_agent 		= 	$_SERVER['HTTP_USER_AGENT'];
		$this->request_url 		= 	$_SERVER['REDIRECT_URL'];
		$this->method_name 		= 	$_SERVER['REDIRECT_QUERY_STRING'];

		$this->load->library('generatelogs',array('type'=>'Products'));
	} 

	/* * *********************************************************************
	 * * Function name: getHomePageData
	 * * Developed By : Manoj Kumar
	 * * Purpose      : This function used for get Home Page Data
	 * * Date         : 27 JUNE 2022
	 * * Updated By   : Dilip Halder
	 * * Updated Date : 18 January 2024
	 * * **********************************************************************/
	public function getHomePageData()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):

			if($this->input->post('users_id') && $this->input->post('users_device_id')):
				$users_device_id  				=	$this->input->post('users_device_id');
				$users_lat  					=	$this->input->post('users_lat');
				$users_long  					=	$this->input->post('users_long');

				$param['device_id']				= 	$users_device_id;
				$param['latitude']				= 	$users_lat;
				$param['longitude']				= 	$users_long;
				$this->geneal_model->editData('da_users',$param,'users_id',(int)$this->input->post('users_id'));
			endif;

			// General Data Start
			$generalData 				=	$this->geneal_model->getData2('single', 'da_general_data', $HSwcon,$HSorder);
			// General Data End

			// video Slider/Home Slider Start
			if($generalData['slider_type'] == 'Image'):
				$HStbl 						=	'da_homepage_slider';
				$HSwcon['where']          	=  	['page' => 'Homeslider','status'=>'A','show_on'=>'Mobile'];//array('page'=> array('$ne'=> 'Homebanner') );
				$HSorder 					=	['slider_id' => 'asc'];

				$result = 	$this->geneal_model->getData2('multiple', $HStbl, $HSwcon,$HSorder);

				if(empty($result)):
					$result = array();
				endif;

				$result['homeSlider']		=	$result;
			else:
				$HStbl 						=	'da_homepage_slider';
				$HSwcon['where']          	=  	['page' => 'Appvideo', 'type' => 'V','status'=>'A'];//array('page'=> array('$ne'=> 'Homebanner') );
				$HSorder 					=	['slider_id' => 'asc'];
				$result						=	$this->geneal_model->getData2('single', $HStbl, $HSwcon,$HSorder);

				if(empty($result)):
					$result = array();
				endif;

				$result['videoSlider']		= 	$result;
				
			endif;
			// video Slider/Home Slider End

			// Closing Soon Start
			$closingSoon 				=	array();
			$tblName1 					=	'da_products';
			$where1['where'] 			=	array('stock'=> array('$gt'=> 0),'clossingSoon' => 'Y','status' => 'A');
			$order1 					=	['seq_order' => 1];
			$data1						=	$this->geneal_model->getProductWithPrizeDetails('multiple',$tblName1,$where1,$order1);
			if($data1):
				foreach($data1 as $info1):
					$valid1 			= 	$info1['validuptodate'].' '.$info1['validuptotime'].':0';
					$today1 			= 	date('Y-m-d H:i:s');
					if(strtotime($valid1) > strtotime($today1)):
						array_push($closingSoon,$info1);
					endif;
				endforeach;
			endif;
			$result['closingSoon'] 		=	$closingSoon;
			// Closing Soon End
 
			$selectedCampaign  			=	$this->common_model->getData('single','da_selected_campaign');
			$UserId 		   			=   $this->input->post('users_id');
			// Checking Product for login Users only..
			if($UserId):
				$tableName          = 'da_users';
				$whereCon['where']  = array('users_id'=> (int)$UserId);
				$UserDatails 		= $this->common_model->getData('single',$tableName,$whereCon);
				
				// echo count($selectedCampaign['selected_campaign_list']);
				// echo count($selectedCampaign['selected_app_campaign_list']);
				// die();

				// Pos Device sortlisted condition added...
				if(count($selectedCampaign['selected_campaign_list']) >= 1 && $UserDatails['users_type'] == 'Sales Person' 
				   || count($selectedCampaign['selected_campaign_list']) >= 1 && $UserDatails['users_type'] == 'Retailer' 
				   || count($selectedCampaign['selected_campaign_list']) >= 1 && $UserDatails['users_type'] == 'Promoter' 
				):
					$where2['where_in'] = array('0' => 'products_id' , '1' => $selectedCampaign['selected_campaign_list'] );
				elseif(count($selectedCampaign['selected_campaign_list']) >= 1 && $UserDatails['users_type'] == 'Users'):
					$where2['where_in'] = array('0' => 'products_id' , '1' => $selectedCampaign['selected_app_campaign_list'] );
				endif;
			// Checking Product for without login Users only..
			else:
				if(count($selectedCampaign['selected_app_campaign_list']) >= 1 ):
					$where2['where_in'] = array('0' => 'products_id' , '1' => $selectedCampaign['selected_app_campaign_list'] );
				endif;
			endif;

			$ourCampaigns 				=	array();
			$tblName2 					=	'da_products';
			$where2['where'] 			=	array( 
												  'stock'=> array('$gt'=> 0),
												  'clossingSoon' => 'N',
												  'status' => 'A',
												  "validuptodate" => array('$gte' => date('Y-m-d'))
											);
			$Product_shortField 		=	array('seq_order' => 1);
			// Product listing Start ...
			$ProductList = $this->geneal_model->getData2('multiple',$tblName2,$where2,$Product_shortField);
			if($ProductList):
				foreach($ProductList as $info2):
					$valid2 			= 	$info2['validuptodate'].' '.$info2['validuptotime'].':00';
					$drawDate2 			= 	$info2['draw_date'].' '.$info2['draw_time'].':00';
					$today2 			= 	date('Y-m-d H:i:s');
					if(strtotime($valid2) > strtotime($today2) && strtotime($drawDate2) > strtotime($today2)):


						// wishlist_product code start
						if($this->input->post('users_id')):
							$prowhere['where']	=	array('users_id'=>(int)$this->input->post('users_id'),'product_id'=>(int)$info2['products_id']);
							$prodData			=	$this->common_model->getData('single','da_wishlist',$prowhere);
							if($prodData):
								if($prodData['wishlist_product'] == 'Y'):
									$info2['wishlist_product']  = 'Y';
								else:
									$info2['wishlist_product']  = 'N';
								endif; 
							else:
								$info2['wishlist_product']  	= 'N';
							endif;
						else:
							$info2['wishlist_product']  		= 'N';
						endif;
						// wishlist_product code end

						if($UserDatails):
							$productShareUrl  				= 	generateProductShareUrl($info2['products_id'],$this->input->post('users_id'),$UserDatails['referral_code']);
							$info2['share_url']  			= 	$productShareUrl;
						else:	
							$info2['share_url']  			= 	'';
						endif;
 
						$product_prise_data 			= 	$this->geneal_model->getParticularDataByParticularField('prize_image','da_prize', 'product_id', $info2['products_id']);
						if($product_prise_data <> ''):
							$info2['product_prise_data']  = $product_prise_data;
						else:
							$info2['product_prise_data']  = NULL;
						endif;
						array_push($ourCampaigns,$info2);
					endif;
				endforeach;
			endif;
			$result['ourCampaigns'] 	=	$ourCampaigns;
			// Product listing End ...

			// Soldout listing Start ...
			$tblName3 					=	'da_products';
			$where3 					=	array('isSoldout'	=> 'Y');
			$order3 					=	array('creation_date' => -1);
			$data3						=	$this->geneal_model->getData($tblName3,$where3,$order3);
			$data3A = [];
			foreach ($data3 as $key => $value) {
				if($this->input->post('users_id') <> ''):
					$msg = $this->geneal_model->checkWinner((int)$this->input->post('users_id'), $value['products_id']);
					$value['status_mgs'] = $msg;
				else:
					$value['status_mgs'] = "Your are not a participant.";
				endif;
				array_push($data3A, $value);
			}
			$result['recentlySoldOut'] 	=	$data3A;
			// Soldout listing End ...

			// Recent Winners list start ...
			$recentWinners 				=	array();
			$tblName4 					=	'da_winners';
			$where4 					=	[];
			$order4 					=	['creation_date' => 'desc'];
			$data4						=	$this->geneal_model->getData($tblName4,$where4,$order4);
			if($data4):
				foreach($data4 as $info4):
					$valid4 			= 	$info4['announcedDate'].' '.$info4['announcedTime'].':0';
					$today4 			= 	date('Y-m-d H:i:s');
					if(strtotime($valid4) < strtotime($today4)):

						$tableName  = 'da_products';
						$fields     = array('product_image');
						$fieldName  = 'products_id';
						$fieldValue = $info4['product_id'];
						$products = $this->common_model->getSingleDataByParticularField($fields,$tableName,$fieldName,$fieldValue);
						$info4['product_image']  = $products['product_image'];

						array_push($recentWinners,$info4);
					endif;
				endforeach;
			endif;
			$result['recentWinners'] 	=	$recentWinners;
			// Recent Winners list End ...

			// Cart start ...
			if($this->input->post('users_id')):
				$CTwhere1['where'] 		= 	[ 'user_id'=>(int)$this->input->post('users_id') ];
				$cartItemsCount			=	$this->geneal_model->getData2('count', 'da_cartItems', $CTwhere1, []);
				$result['cartCount'] 	=	$cartItemsCount;
			else:
				$result['cartCount'] 	=	0;
			endif;
			// Cart End ...

			// Product Request Count Start ...
			if($this->input->post('users_id')):
				$tblName 					=	'da_emirate_collection_point';
				$user_id 					=	$this->input->post('users_id');
				$wcon['where']				=	array('users_id' => (int)$user_id); 
				$collectionPointList		=	$this->geneal_model->getData2('multiple', $tblName, $wcon);

				if(empty($collectionPointList)){
					$count = 1;
				}
				//$orWhere = array();
				$count = 0;
				
				foreach ($collectionPointList as $key => $items) {
					$where6['where']		=	array(
												'collection_point_id' => (string)$items['collection_point_id'],
												'collection_status' => 'Pending to collect'
											);
					$short = array('collection_status'=> -1 ,'sequence_id' => -1);
					$orderlist				=	$this->geneal_model->getproductrequestList('count','da_orders',$where6,$short);
					$count = $count + $orderlist;
				}
				$result['product_request_count'] = $count;
			endif;
			// Product Request Count End ...
			echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_MSG'),$result);
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}
	 
	 

	/* * *********************************************************************
	 * * Function name : getProductListPageData
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get Product List Page Data
	 * * Date : 27 JUNE 2022
	 * * **********************************************************************/
	public function getProductListPageData()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'GET')):
			
			$closingSoon 				=	array();
			$tblName1 					=	'da_products';
			$where1['where'] 			=	array('stock'=> array('$gt'=> 0),'clossingSoon' => 'Y','status' => 'A');
			$order1 					=	['creation_date' => -1];
			$data1						=	$this->geneal_model->getProductList('multiple',$tblName1,$where1,$order1);
			if($data1):
				foreach($data1 as $info1):
					$valid1 			= 	$info1['validuptodate'].' '.$info1['validuptotime'].':0';
					$today1 			= 	date('Y-m-d H:i:s');
					if(strtotime($valid1) > strtotime($today1)):
						array_push($closingSoon,$info1);
					endif;
				endforeach;
			endif;
			$result['closingSoon'] 		=	$closingSoon;

			$ourCampaigns 				=	array();
			$tblName2 					=	'da_products';
			$where2['where'] 			=	array( 'stock'=> array('$gt'=> 0,),'clossingSoon' => 'N','status' => 'A','remarks'=> array('$ne' => 'lotto-products'));
			$order2 					=	['creation_date' => -1];
			$data2						=	$this->geneal_model->getProductList('multiple',$tblName2,$where2,$order2);
			if($data2):
				foreach($data2 as $info2):
					$valid2 			= 	$info2['validuptodate'].' '.$info2['validuptotime'].':0';
					$today2 			= 	date('Y-m-d H:i:s');
					if(strtotime($valid2) > strtotime($today2)):
						if($this->input->get('users_id')):
							$prowhere['where']	=	array('users_id'=>(int)$this->input->get('users_id'),'product_id'=>(int)$info2['products_id']);
							$prodData			=	$this->common_model->getData('single','da_wishlist',$prowhere);
							if($prodData):
								if($prodData['wishlist_product'] == 'Y'):
									$info2['wishlist_product']  = 'Y';
								else:
									$info2['wishlist_product']  = 'N';
								endif; 
							else:
								$info2['wishlist_product']  	= 'N';
							endif;
						else:
							$info2['wishlist_product']  		= 'N';
						endif;

						if($this->input->get('users_id')):
							$USRwhere 							=	[ 'users_id' => (int)$this->input->get('users_id') ];
							$USRtblName 						=	'da_users';
							$userDetails 						=	$this->geneal_model->getOnlyOneData($USRtblName, $USRwhere);
							if($userDetails):
								$productShareUrl  				= 	generateProductShareUrl($info2['products_id'],$this->input->get('users_id'),$userDetails['referral_code']);
								$info2['share_url']  			= 	$productShareUrl;
							else:	
								$info2['share_url']  			= 	'';
							endif;
						else:
							$info2['share_url']  				= 	'';
						endif;

						array_push($ourCampaigns,$info2);
					endif;
				endforeach;
			endif;
			$result['ourCampaigns'] 	=	$ourCampaigns;

			echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_MSG'),$result);
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getProductDetails
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get Product Details
	 * * Date : 27 JUNE 2022
	 * * **********************************************************************/
	public function getProductDetails()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'GET')):
			
			if($this->input->get('products_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_ID_EMPTY'),$result);
			else:

				$tbl1 						=	'da_products';
				$where1 					=	['products_id' => (int)$this->input->get('products_id') ];
				$result['productsData']		=	$this->geneal_model->getOnlyOneData($tbl1, $where1);

				$tbl2 						=	'da_prize';
				$where2 					=	['product_id' => (int)$this->input->get('products_id') ];
				$result['productPrize']		=	$this->geneal_model->getOnlyOneData($tbl2, $where2);

				if($this->input->get('users_id')):
					$tbl3 						=	'da_wishlist';
					$where3['where']			=	array('users_id'=>(int)$this->input->get('users_id'),'product_id'=>(int)$this->input->get('products_id'));
					$productWishlist			=	$this->common_model->getData('single',$tbl3,$where3);
					if($productWishlist):
						$result['productWishlist']	=	$productWishlist;
					else:
						$result['productWishlist']	=	array();
					endif;
				else:
					$result['productWishlist']		=	array();
				endif;

				if($this->input->get('users_id')):
					$USRwhere 							=	[ 'users_id' => (int)$this->input->get('users_id') ];
					$USRtblName 						=	'da_users';
					$userDetails 						=	$this->geneal_model->getOnlyOneData($USRtblName, $USRwhere);
					if($userDetails):
						$productShareUrl  				= 	generateProductShareUrl($this->input->get('products_id'),$this->input->get('users_id'),$userDetails['referral_code']);
						$result['productsData']['share_url']  	= 	$productShareUrl;
					else:	
						$result['productsData']['share_url']  	= 	'';
					endif;
				else:
					$result['productsData']['share_url']  		= 	'';
				endif;

				echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_MSG'),$result);
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : addToCart
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add To Cart
	 * * Date : 27 JUNE 2022
	 * * Updated By : Afsar Ali
	 * * Updated Date : 12-12-2022
	 * * **********************************************************************/
	public function addToCart()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('products_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_ID_EMPTY'),$result);
			elseif($this->input->post('token') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('Empty_Token'),$result);
			else:

				// Added validation for invalid token.
				if($this->input->post('token')):
					$where 			=	array('users_id' => (int)$this->input->get('users_id') , 'token' => $this->input->post('token')  );
					$UsersDetails 	=	$this->geneal_model->getOnlyOneData('da_users', $where);

					if(empty($UsersDetails)):
						echo outPut(0,lang('SUCCESS_CODE'),lang('Invalid_Token'),$result);
						die();
					endif;
				endif;

				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):  //echo '<pre>'; print_r($userDetails); die;

						$where = ['products_id' => (int)$this->input->post('products_id') ];
						$product_data = $this->geneal_model->getOnlyOneData('da_products', $where);
						if($product_data):
							$cartdata['user_id']		=	(int)$this->input->get('users_id');
							$cartdata['id']				=	(int)$product_data['products_id'];
							$cartdata['name']			=	$product_data['title'];
							$cartdata['qty']			=	1;
							$cartdata['price']			=	$product_data['adepoints'];
							if($this->input->post('fcolor')):	
								$cartdata['color']			=	$this->input->post('fcolor');
							endif;
							if($this->input->post('fsize')):	
								$cartdata['size']			=	$this->input->post('fsize');
							endif;
							$cartdata['other']			=	array(
																'image' 		=>	$product_data['product_image'],
																'description' 	=>	$product_data['description'],
																'aed'			=>	$product_data['adepoints']
															);
							$cartdata['is_donated']		=	'N';
							$cartdata['current_ip']		=	currentIp();
							$cartdata['rowid']			=	md5(generateToken());
							$cartdata['subtotal']		=	(1*$product_data['adepoints']);
							$cartdata['curprodrowid']	=	md5(generateToken());

							$CTwhere = [ 'user_id'=>(int)$this->input->get('users_id'), 'id' =>(int)$product_data['products_id'] ];
							$cartTableProductData = $this->geneal_model->getOnlyOneData('da_cartItems', $CTwhere);
							if($cartTableProductData):
								$currentQty    		=	($cartTableProductData['qty']+1);
								$currentSubToral  	=	($currentQty*$product_data['adepoints']);
								//$CTdata = array('qty'=>$currentQty, 'subtotal'=>$currentSubToral);
								
								$CTdata['qty']		=	$currentQty;
								$CTdata['subtotal']	=	$currentSubToral;
								if($this->input->post('fcolor')):
									$CTdata['color']	=	$this->input->post('fcolor');
								endif;
								if($this->input->post('fsize')):
									$CTdata['size']		=	$this->input->post('fsize');
								endif;
								
								$this->geneal_model->editData('da_cartItems', $CTdata, 'rowid', $cartTableProductData['rowid']);
							else:
								$this->geneal_model->addData('da_cartItems', $cartdata);
							endif;
						endif;

						$CTwhere1['where'] 		= 	[ 'user_id'=>(int)$this->input->get('users_id') ];
						$cartItems  			=	$this->geneal_model->getData2('multiple', 'da_cartItems', $CTwhere1, []);
						$cartItemsCount			=	$this->geneal_model->getData2('count', 'da_cartItems', $CTwhere1, []);
						$result['cartData'] 	=	$cartItems;
						$result['cartCount'] 	=	$cartItemsCount;
						$result['vatPercentage']=	5;
						echo outPut(1,lang('SUCCESS_CODE'),lang('ADD_TO_CART'),$result);
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);
					endif;
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getCartData
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get Cart Data
	 * * Date : 27 JUNE 2022
	 * * **********************************************************************/
	public function getCartData()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'GET')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->get('token') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('Empty_Token'),$result);
			else:

				// Added validation for invalid token.
				if($this->input->get('token')):
					$where 			=	array('users_id' => (int)$this->input->get('users_id') , 'token' => $this->input->get('token')  );
					$UsersDetails 	=	$this->geneal_model->getOnlyOneData('da_users', $where);

					if(empty($UsersDetails)):
						echo outPut(0,lang('SUCCESS_CODE'),lang('Invalid_Token'),$result);
						die();
					endif;
				endif;

				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):

						$CTwhere1['where'] 			= 	[ 'user_id'=>(int)$this->input->get('users_id') ];
						$cartItems  				=	$this->geneal_model->getData2('multiple', 'da_cartItems', $CTwhere1, []);
						$cartItemsCount				=	$this->geneal_model->getData2('count', 'da_cartItems', $CTwhere1, []);
						
						//Our Campaings
						$ourCampaigns 				=	array();
						$tblName2 					=	'da_products';
						$where2['where'] 			=	array( 'stock'=> array('$gt'=> 0,),'clossingSoon' => 'N','commingSoon'=>'N','status' => 'A','remarks'=> array('$ne' => 'lotto-products'));
						$order2 					=	['creation_date' => 'desc'];
						$data2						=	$this->geneal_model->getData2('multiple',$tblName2,$where2,$order2);
						if($data2):
							foreach($data2 as $info2):
								$valid2 			= 	$info2['validuptodate'].' '.$info2['validuptotime'].':0';
								$today2 			= 	date('Y-m-d H:i:s');
								if(strtotime($valid2) > strtotime($today2)):
									if($this->input->post('users_id')):
										$prowhere['where']	=	array('users_id'=>(int)$this->input->post('users_id'),'product_id'=>(int)$info2['products_id']);
										$prodData			=	$this->common_model->getData('single','da_wishlist',$prowhere);
										if($prodData):
											if($prodData['wishlist_product'] == 'Y'):
												$info2['wishlist_product']  = 'Y';
											else:
												$info2['wishlist_product']  = 'N';
											endif; 
										else:
											$info2['wishlist_product']  	= 'N';
										endif;
									else:
										$info2['wishlist_product']  		= 'N';
									endif;

									if($this->input->post('users_id')):
										$USRwhere 							=	[ 'users_id' => (int)$this->input->post('users_id') ];
										$USRtblName 						=	'da_users';
										$userDetails 						=	$this->geneal_model->getOnlyOneData($USRtblName, $USRwhere);
										if($userDetails):
											$productShareUrl  				= 	generateProductShareUrl($info2['products_id'],$this->input->post('users_id'),$userDetails['referral_code']);
											$info2['share_url']  			= 	$productShareUrl;
										else:	
											$info2['share_url']  			= 	'';
										endif;
									else:
										$info2['share_url']  				= 	'';
									endif;

									$product_prise_data 					= 	$this->geneal_model->getParticularDataByParticularField('prize_image','da_prize','product_id', $info2['products_id']);
									if($product_prise_data <> ''):
										$info2['product_prise_data']  = $product_prise_data;
									else:
										$info2['product_prise_data']  = array();
									endif;
									

									array_push($ourCampaigns,$info2);
								endif;
							endforeach;
						endif;
						// END

						//Adding validatipon to check product is available or inactive. 
						foreach ($cartItems as $key => $cartitem):

							$product_id = $cartitem['id'];
							$product_name = $cartitem['name'];

							$CTwhere['where']	= 	array('products_id'=>(int)$product_id ,'status' => 'A');
							$shortField 		= 	array('id' => -1 );
						    $available_product  =	$this->geneal_model->getData2('multiple', 'da_products', $CTwhere, $shortField);
						    //Deleting inactive products into cart.
						    $draw_date =  strtotime($available_product['draw_date'] . ' ' .$available_product['draw_time']);
							$today_date = strtotime(date('Y-m-d h:i'));
							
							if($today_date>=$draw_date):
								$this->geneal_model->deleteData('da_cartItems', 'id', (int)$product_id);
						    	$inactive_product = 1;
							endif;

							
						    if(empty($available_product)):
						    	$this->geneal_model->deleteData('da_cartItems', 'id', (int)$product_id);
						    	$inactive_product = 1;
						    endif;
						    // end
						endforeach;

						if($inactive_product):
							$CTwhere1['where'] 			= 	[ 'user_id'=>(int)$this->input->get('users_id') ];
							$cartItems  				=	$this->geneal_model->getData2('multiple', 'da_cartItems', $CTwhere1, []);
							$cartItemsCount				=	$this->geneal_model->getData2('count', 'da_cartItems', $CTwhere1, []);
						endif;
						

						if($cartItemsCount):
							$result['cartData'] 	=	$cartItems;
							$result['cartCount'] 	=	$cartItemsCount;
							$result['vatPercentage']=	5;
							$result['ourCampaigns'] 	=	$ourCampaigns;
							echo outPut(1,lang('SUCCESS_CODE'),lang('GET_CART_DATA'),$result);
						else:
							$result['cartData'] 	=	array();
							$result['cartCount'] 	=	0;
							$result['vatPercentage']=	5;
							$result['ourCampaigns'] 	=	$ourCampaigns;
							echo outPut(1,lang('SUCCESS_CODE'),lang('CART_EMPTY'),$result);
						endif;
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);
					endif;
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : removeFromCart
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for remove From Cart
	 * * Date : 27 JUNE 2022
	 * * **********************************************************************/
	public function removeFromCart()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'DELETE')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->get('rowid') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('ROW_ID_EMPTY'),$result);
			else:

				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):

						// delete product in cart table
						$this->geneal_model->deleteData('da_cartItems', 'rowid', $this->input->get('rowid') );

						$CTwhere1['where'] 		= 	[ 'user_id'=>(int)$this->input->get('users_id') ];
						$cartItems  			=	$this->geneal_model->getData2('multiple', 'da_cartItems', $CTwhere1, []);
						$cartItemsCount			=	$this->geneal_model->getData2('count', 'da_cartItems', $CTwhere1, []);
						$result['cartData'] 	=	$cartItems;
						$result['cartCount'] 	=	$cartItemsCount;
						$result['vatPercentage']=	5;
						echo outPut(1,lang('SUCCESS_CODE'),lang('DELETE_FROM_CART'),$result);
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);
					endif;
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : quantityIncreaseToCart
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for quantity Increase To Cart
	 * * Date : 27 JUNE 2022
	 * * Updated By : Dilip Halder
	 * * Updated Date  : 17 October 2023


	 * * **********************************************************************/
	public function quantityIncreaseToCart()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('rowid') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('ROW_ID_EMPTY'),$result);
			elseif($this->input->post('quantity') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('QUANTITY_EMPTY'),$result);
			else:

				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):

						//Getting current cart item
						$Checkwhere1['where'] = array('rowid' => $this->input->post('rowid'));
						$cartIData   =	$this->geneal_model->getData2('single', 'da_cartItems', $Checkwhere1, []);
						$subtotal 	 =  (float)$cartIData['price']*$this->input->post('quantity');
						
						//update quantity to cart table
						$CTdata = array( 'qty'=> (int)$this->input->post('quantity') ,'subtotal' => (float)$subtotal );
						$this->geneal_model->editData('da_cartItems', $CTdata, 'rowid', $this->input->post('rowid'));

						$CTwhere1['where'] 		= 	[ 'user_id'=>(int)$this->input->get('users_id') ];
						$cartItems  			=	$this->geneal_model->getData2('multiple', 'da_cartItems', $CTwhere1, []);
						$cartItemsCount			=	$this->geneal_model->getData2('count', 'da_cartItems', $CTwhere1, []);
						$result['cartData'] 	=	$cartItems;
						$result['cartCount'] 	=	$cartItemsCount;
						$result['vatPercentage']=	5;
						echo outPut(1,lang('SUCCESS_CODE'),lang('INCREASE_QTY_TO_CART'),$result);
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);
					endif;
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : addRemoveToDonate
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add Remove To Donate
	 * * Date : 27 JUNE 2022
	 * * **********************************************************************/
	public function addRemoveToDonate()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('rowid') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('ROW_ID_EMPTY'),$result);
			elseif($this->input->post('is_donate') == ''): 				//Y/N
				echo outPut(0,lang('SUCCESS_CODE'),lang('ISDONATE_EMPTY'),$result);
			else:

				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):

						//update is donate to cart table
						$CTdata = array( 'is_donated'=> $this->input->post('is_donate') );
						$this->geneal_model->editData('da_cartItems', $CTdata, 'rowid', $this->input->post('rowid'));

						$CTwhere1['where'] 		= 	[ 'user_id'=>(int)$this->input->get('users_id') ];
						$cartItems  			=	$this->geneal_model->getData2('multiple', 'da_cartItems', $CTwhere1, []);
						$cartItemsCount			=	$this->geneal_model->getData2('count', 'da_cartItems', $CTwhere1, []);
						$result['cartData'] 	=	$cartItems;
						$result['cartCount'] 	=	$cartItemsCount;
						$result['vatPercentage']=	5;
						echo outPut(1,lang('SUCCESS_CODE'),lang('UPDATE_DONATE_STATUS'),$result);
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);
					endif;
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getCollectionPointsData
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get Collection Points Data
	 * * Date : 14 JULY 2022
	 * * Updated By : AFSAR ALI
	 * * Updated Date : 15 NOV 2022
	 * * **********************************************************************/
	public function getCollectionPointsData()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'GET')):
			
			$collectionPoints   		=	array();
			$tblName1 					=	'da_emirate';
			$where1['where'] 			=	array( 'status' => 'A');
			$order1 					=	['emirate_name' => 'ASC'];
			$data1						=	$this->geneal_model->getData2('multiple',$tblName1,$where1,$order1);
			if($data1):
				foreach($data1 as $info1):
					$tblName2 					=	'da_area';
					$where2['where'] 			=	array( 'emirate_id'=>(int)$info1['emirate_id'], 'status' => 'A');
					$order2 					=	['area_name' => 'ASC'];
					$data2						=	$this->geneal_model->getData2('multiple',$tblName2,$where2,$order2);
					if($data2):
						$i=0;
						foreach ($data2 as $key => $info2) {
							$info1['area'][$i]	=	$info2;
							$tblName3 					=	'da_emirate_collection_point';
							$where3['where'] 			=	array( 'emirate_id'=>(int)$info1['emirate_id'], 'status' => 'A', 'area_id' => (int)$info2['area_id'] );
							$order3 					=	['collection_point_name' => 'ASC'];
							$data3						=	$this->geneal_model->getData2('multiple',$tblName3,$where3,$order3);
							if($data3):
								$info1['area'][$i]['collectionPoint']	=	$data3;
							endif;	
							$i++;
						}
					endif;
					array_push($collectionPoints,$info1);
				endforeach;
			endif;

			$result['collectionList']    	=   $collectionPoints;
			echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_MSG'),$result);
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : purchageByArabianPoint
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for purchage By Arabian Point
	 * * Date : 21 JULY 2022
	 * * **********************************************************************/
	public function purchageByArabianPoint()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('user_type') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_TYPE_EMPTY'),$result);
			// elseif($this->input->post('user_email') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('USER_EMAIL_EMPTY'),$result);
			elseif($this->input->post('user_phone') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_PHONE_EMPTY'),$result);
			elseif($this->input->post('product_is_donate') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_IN_DONATE_EMPTY'),$result);
			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == ''): 	
				echo outPut(0,lang('SUCCESS_CODE'),lang('SHIPPINT_METHOD_EMPTY'),$result);	


			//elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'ship' && $this->input->post('address') == ''): 
			//	echo outPut(0,lang('SUCCESS_CODE'),lang('SHIPPING_ADDRESS_EMPTY'),$result);
			
			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('emirate_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMIRATE_ID_EMPTY'),$result);
			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('emirate_name') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMIRATE_NAME_EMPTY'),$result);

			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('area_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('AREA_ID_EMPTY'),$result);
			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('area_name') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('AREA_NAME_EMPTY'),$result);

			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('collection_point_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('COLLECTION_POINT_ID_EMPTY'),$result);
			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('collection_point_name') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('COLLECTION_POINT_NAME_EMPTY'),$result);


			elseif($this->input->post('shipping_charge') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('SHIPPING_CHARGE_EMPTY'),$result);
			elseif($this->input->post('inclusice_of_vat') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('INCLUSICE_OF_VAT_EMPTY'),$result);
			elseif($this->input->post('inclusice_of_vat') == 0): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('ORDER_ERROR'),$result);
			elseif($this->input->post('subtotal') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('SUBTOTAL_EMPTY'),$result);
			elseif($this->input->post('vat_amount') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('VAT_AMOUNT_EMPTY'),$result);
			else:
				
				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):

						$wcon['where']		=	[ 'user_id' => (int)$this->input->get('users_id') ];
						$cartItems          =	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);
						if($cartItems == 0):
		                    echo outPut(0,lang('SUCCESS_CODE'),lang('CART_EMPTY'),$result);die();
		                endif;

		                $productCount       =	$this->geneal_model->getData2('count', 'da_cartItems', $wcon);
						
						foreach($cartItems as $CA):
							$finalPrice += $CA['qty'] * $CA['price'];
							//check Stock
							$stockCheck = $this->geneal_model->getStock($CA['id'], $CA['qty']);
							
							// Available prodcut and soldout product details..
							$tblName = 'da_tickets_sequence';
							$whereCon2['where']		 			= 	array('product_id' => (int)$CA['id'] , 'status' => 'A');	
							$shortField 						= 	array('tickets_seq_id'=>'DESC');
							$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

							$tblName = 'da_quickcoupons_totallist';
							$whereCon3['where']		 			= 	array('product_id' => (int)$CA['id'] , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id'] );	
							$shortField3 						= 	array('tickets_seq_id'=>'DESC');
							$SoldoutTicketList					= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

							$available_ticket =	$CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
							
							if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
								$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
							endif;

							if( $this->input->post('product_is_donate') == "Y"):
								$check_availblity = $CA['qty'] * 2;
							elseif( $this->input->post('product_is_donate') == "N"):
								$check_availblity = $CA['qty'];
							endif;
							
							$left_ticket = $available_ticket - ($coupon_sold_number + $check_availblity);  

							// echo 'available_ticket == '.$available_ticket."<br>";
							// echo 'SoldoutTicketList == '.$coupon_sold_number."<br>";
							// echo  'left_ticket == '. $left_ticket."<br><br>";
							// die();


							if($left_ticket < 0):
				        		echo outPut(0,lang('SUCCESS_CODE'),lang('TICKET_NOT_AVAILABLE'). " for " .$CA['name'],$result);die();
								die();
							endif;


							if($CA['is_donated'] == 'N'):
								$data['product_is_in_donate'] =  'N';
							endif;
						endforeach;

						if($cartItems):
							// foreach($cartItems as $CA):
							// 	$inventoryStock = $this->geneal_model->getInventoryStock($this->input->post('collection_point_id'), $CA['id'], $CA['qty']);
							// 	if($inventoryStock == 'error'):
							// 		echo outPut(0,lang('SUCCESS_CODE'),lang('OUT_OF_STOCK'),$result);die();
							// 	endif;
							// endforeach;
							/* Order Place Table */
					        $ORparam["sequence_id"]		    	=	(int)$this->geneal_model->getNextSequence('da_orders');
					        $ORparam["order_id"]		        =	$this->geneal_model->getNextOrderId();
					        $ORparam["user_id"] 				=	(int)$this->input->get('users_id');
					        $ORparam["user_type"] 				=	$this->input->post('user_type');
							if($this->input->post('user_email')):
					        	$ORparam["user_email"] 				=	$this->input->post('user_email');
							else:
								$ORparam["user_email"] 				=	'';
							endif;
					        $ORparam["user_phone"] 				=	(int)$this->input->post('user_phone');
					        $ORparam["product_is_donate"] 		=	$this->input->post('product_is_donate');
					        if($this->input->post('product_is_donate') == 'N'):
					        	$ORparam["shipping_method"] 		=	$this->input->post('shipping_method');
					      //   	if($this->input->post('shipping_method') == 'ship'):
					      //   		$shippingAddress			=	explode('_____', $this->input->post('address'));
							    //     $ORparam["shipping_address"]=	$shippingAddress[1];
							    // else:
							    // 	$ORparam["shipping_address"]=	'';
							    // endif;
					        	$ORparam["emirate_id"] 				=	$this->input->post('emirate_id');
					        	$ORparam["emirate_name"] 			=	$this->input->post('emirate_name');
								$ORparam["area_id"] 				=	$this->input->post('area_id');
					        	$ORparam["area_name"] 				=	$this->input->post('area_name');
					        	$ORparam["collection_point_id"] 	=	$this->input->post('collection_point_id');
					        	$ORparam["collection_point_name"] 	=	$this->input->post('collection_point_name');

							    $ORparam["shipping_address"]	=	'';
						    else:
						    	$ORparam["shipping_method"] 	=	'';

						    	$ORparam["emirate_id"] 				=	'';
					        	$ORparam["emirate_name"] 			=	'';
								$ORparam["area_id"] 				=	'';
					        	$ORparam["area_name"] 				=	'';
					        	$ORparam["collection_point_id"] 	=	'';
					        	$ORparam["collection_point_name"] 	=	'';

						    	$ORparam["shipping_address"] 	=	'';
						    endif;
					        $ORparam["shipping_charge"] 		=	(float)$this->input->post('shipping_charge');
					        $ORparam["inclusice_of_vat"] 		=	(float)$this->input->post('inclusice_of_vat');
					        $ORparam["subtotal"] 				=	(float)$this->input->post('subtotal');
					        $ORparam["vat_amount"] 				=	(float)$this->input->post('vat_amount');
					        $ORparam["total_price"] 			=	(float)$ORparam["inclusice_of_vat"];
						    $ORparam["payment_mode"] 			=	'Arabian Points';
						    $ORparam["payment_from"] 			=	'App';
						    $ORparam["device_type"] 			=	$this->input->post('device_type');
						    $ORparam["app_version"] 			=	$this->input->post('app_version');
						    $ORparam["order_status"] 			=	"Process";
						    $ORparam["creation_ip"] 			=	$this->input->ip_address();
						    $ORparam["created_at"] 				=	date('Y-m-d H:i');

						    $orderInsertID 						=	$this->geneal_model->addData('da_orders', $ORparam);
							
						    if($orderInsertID && $cartItems):
						        foreach($cartItems as $CA):	
									//Manage Inventory
									if($this->input->post('product_is_donate') == ' N'):
										$where['where']		=	array(
																	'products_id'			=>	(int)$CA['id'],
																	'collection_point_id' 	=>	(int)$ORparam["collection_point_id"]
																);
										$INVcheck	=	$this->geneal_model->getData2('single','da_inventory',$where);
										if($INVcheck <> ''):
											$orqty = $INVcheck['order_request_qty'] + (int)$CA['qty'];
											$INVUpdate['order_request_qty']		=	$orqty;
											$this->geneal_model->editDataByMultipleCondition('da_inventory',$INVUpdate,$where['where']);
										else:
											$INVparam['products_id']				= 	(int)$CA['id'];
											$INVparam['qty']						=	(int)0;
											$INVparam['available_qty']				=	(int)0;
											$INVparam['order_request_qty']			=	(int)$CA['qty'];
											$INVparam['collection_point_id']		=	(int)$ORparam["collection_point_id"];

											$INVparam['inventory_id']				=	(int)$this->common_model->getNextSequence('da_inventory');
											
											$INVparam['creation_ip']				=	currentIp();
											$INVparam['creation_date']				=	(int)$this->timezone->utc_time();//currentDateTime();
											$INVparam['status']						=	'A';
											$this->geneal_model->addData('da_inventory', $INVparam);
										endif;
									endif;
									//END
									$ORDparam["order_details_id"] 	=	(int)$this->geneal_model->getNextSequence('da_orders_details');
									$ORDparam["order_sequence_id"]	=	(int)$ORparam["sequence_id"];
									$ORDparam["order_id"]			=	$ORparam["order_id"];
									$ORDparam["user_id"]			=	(int)$CA['user_id'];
									$ORDparam["product_id"] 		=	(int)$CA['id'];
									$ORDparam["product_name"] 		=	$CA['name'];
									$ORDparam["quantity"] 		    =	(int)$CA['qty'];
									if($CA['color']):
										$ORDparam["color"] 		    =	$CA['color'];
									endif;
									if($CA['size']):
										$ORDparam["size"] 		    =	$CA['size'];
									endif;
									$ORDparam["price"] 		        =	(float)$CA['price'];
									$ORDparam["tax"] 		        =	(float)0;
									$ORDparam["subtotal"] 		    =	(float)$CA['subtotal'];
									if($this->input->post('product_is_donate') == 'Y'):
										$ORDparam["is_donated"] 		=	'Y';
									else:
										$ORDparam["is_donated"] 		=	$CA['is_donated'];
									endif;
									$ORDparam["other"] 		        =	array(
																				'image' 		=>	$CA['other']->image,
																				'description' 	=>	$CA['other']->description,
																				'aed'			=>	$CA['other']->aed
																			);
									$ORDparam["current_ip"] 		=	$CA['current_ip'];
									$ORDparam["rowid"] 				=	$CA['rowid'];
									$ORDparam["curprodrowid"] 		=	$CA['curprodrowid'];

									$this->geneal_model->addData('da_orders_details', $ORDparam);
								endforeach;
							endif;

							// if($this->input->post('shared_user_id') && $this->input->post('shared_user_referral_code') && $this->input->post('shared_product_id')):
							// 	$ORparam['SHARED_USER_ID']				=	$this->input->post('shared_user_id');
							// 	$ORparam['SHARED_USER_REFERRAL_CODE']	=	$this->input->post('shared_user_referral_code');
							// 	$ORparam['SHARED_PRODUCT_ID']			=	$this->input->post('shared_product_id');
							// endif;

							$result['successData']					=	$this->successPaymentByArabiyanPoints($ORparam);

							echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_SUCCESS'),$result);
						else:
							echo outPut(0,lang('SUCCESS_CODE'),lang('CART_EMPTY'),$result);
						endif;
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);
					endif;
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;
			endif;
		else:	
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : successPaymentByArabiyanPoints
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for success Payment By Arabiyan Points
	 * * Date : 21 JULY 2022
	 * * **********************************************************************/
	public function successPaymentByArabiyanPoints($ORparam=array())
	{	
		//Get current order of user.
		$wcon['where']					=	[ 'order_id' => $ORparam["order_id"] ];
		$data['orderData'] 				=	$this->geneal_model->getData2('single', 'da_orders', $wcon);
		
		//Get current order details of user.
		$wcon2['where']					=	[ 'order_id' => $ORparam["order_id"] ];
		//$data['orderDetails']         	=	$this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);			//Old code 21-09-2022
		$OrderorderDetails        		=	$this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);				//New code 21-09-2022

		$where 			=	[ 'users_id' => (int)$ORparam["user_id"] ];
		$tblName 		=	'da_users';
		$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

		$wcon['where']		=	[ 'user_id' => (int)$ORparam["user_id"] ];
		$cartItems          =	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);

		$cartItemsCheck     =	$this->geneal_model->getData2('count', 'da_cartItems', $wcon);
		if($cartItemsCheck == 0):
            echo outPut(0,lang('SUCCESS_CODE'),lang('CART_EMPTY'),$result);die();
        endif;

		//update order status
		if($ORparam["product_is_donate"] == 'Y'){																				
			$updateParams 					=	array( 'order_status' => 'Success', 'collection_status' => 'Donated' ,'availableArabianPoints'  => (float)$userDetails["availableArabianPoints"]  , 'end_balance' => (float)$userDetails["availableArabianPoints"] - (float)$ORparam["inclusice_of_vat"]  );	
		}else{
			$expairyData 					=	date('Y-m-d H:i', strtotime($ORparam['created_at']. ' 14 Days'));
			$collectionCode 				=	base64_encode(rand(1000 , 9999));//base64_encode(strtoupper(uniqid(8))); 
			$updateParams 					=	array('order_status' => 'Success', 'collection_code' => $collectionCode, 'collection_status' => 'Pending to collect', 'expairy_data' => $expairyData , 'availableArabianPoints'  => (float)$userDetails["availableArabianPoints"]  , 'end_balance' => (float)$userDetails["availableArabianPoints"] - (float)$ORparam["inclusice_of_vat"] );	
		}
		
		$updateorderstatus 				= 	$this->geneal_model->editData('da_orders', $updateParams, 'order_id', $ORparam["order_id"]);

		//Generate coupon 
		$currentOrderDetails 			=	array();																			//New code 21-09-2022
		//foreach($data['orderDetails'] as $CA):	

		$cashbackcount = 0;
		$ref1count = 0;																			//Old code 21-09-2022
		foreach($OrderorderDetails as $CA):																						//New code 21-09-2022

			$CPwcon['where']					=	[ 'products_id' => (int)$CA["product_id"] ];										//New code 21-09-2022
			$CPData								=	$this->geneal_model->getData2('single', 'da_products', $CPwcon);				//New code 21-09-2022
			$CA['actual_product_name'] 			=	$CPData['title'];															//New code 21-09-2022
			array_push($currentOrderDetails,$CA);																				//New code 21-09-2022
			$this->geneal_model->updateStock($CA['product_id'],$CA['quantity']);
			// if($ORparam["product_is_donate"] == 'N'):																			
			// 	$this->geneal_model->updateInventoryStock($ORparam['collection_point_id'],$CA['product_id'],$CA['quantity']);	
			// endif;

			$productIdPrice[$CA['product_id']] 		=	($CA['quantity'] * $CA['price']);

			// //Get current Ticket order sequence from admin panel.
			$tblName = 'da_tickets_sequence';
			$whereCon2['where']		 			= 	array('product_id' => (int)$CA['product_id'] , 'status' => 'A');	
			$shortField 						= 	array('tickets_seq_id'=>'DESC');
			$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

			$tblName = 'da_quickcoupons_totallist';
			$whereCon3['where']		 			= 	array('product_id' => (int)$CA['product_id'] , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id']  );	
			$shortField3 						= 	array('coupon_id'=>'DESC');
			$SoldoutTicketList					= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

			$available_ticket =	$CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
			
			if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
				$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
			endif;

			if($this->input->post('product_is_donate') == 'Y'):
				$check_availblity = $this->input->post('product_qty') * 2;
			endif;

			$left_ticket = $available_ticket - ($coupon_sold_number + $check_availblity); 

			if($CA['is_donated'] == 'N' ):
				$soldOutQty = $CA['quantity'] ;
			elseif($CA['is_donated'] == "Y"):
			 	$soldOutQty = $CA['quantity']*2 ;
			endif;

			// Created 1st ticket sequence record.
			if(empty($SoldoutTicketList['coupon_sold_number'])):
				
				//Storing new ticket sequence in da_quickcoupons_totallist
				$quickcoupons["id"]					=	(int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
				$quickcoupons["product_id"] 		=	(int)$CA['product_id'];
			    $quickcoupons["tickets_seq_id"] 	=	(int)$CurrentTicketSequence['tickets_seq_id'];
			    $quickcoupons["coupon_sold_number"] =	'';//$soldOutQty;
			    $quickcoupons["creation_ip"] 		=	$this->input->ip_address();
			    $quickcoupons["created_at"] 		=	date('Y-m-d H:i');

			    //Saving quick coupons number  
			    $orderInsertID 						=	$this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons);

			else:
				// checking existing ticket sequence record.
				if($SoldoutTicketList['tickets_seq_id'] != $CurrentTicketSequence['tickets_seq_id'] ):
				//Storing new ticket sequence in da_quickcoupons_totallist
				$quickcoupons["id"]					=	(int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
				$quickcoupons["product_id"] 		=	(int)$CA['product_id'];
			    $quickcoupons["tickets_seq_id"] =		(int)$CurrentTicketSequence['tickets_seq_id'];
			    $quickcoupons["coupon_sold_number"] =	''; //$soldOutQty;
			    $quickcoupons["creation_ip"] 		=	$this->input->ip_address();
			    $quickcoupons["created_at"] 		=	date('Y-m-d H:i');

			    //Saving quick coupons number  
			    $orderInsertID 						=	$this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons);
					
				endif;

			endif;

			//Admin announced ticket available number.
			$available_ticket =  $CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'];
			
			if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
				$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
			endif;


			//defining varibale to store coupon list
			$couponList = array();

			if ($available_ticket > $coupon_sold_number ):

				// Getting product details
				$wcon2['where']					=	array('products_id' => (int)$CA['product_id'] );
				$ProductData        			=	$this->geneal_model->getData2('single', 'da_products', $wcon2);

				//Get current sponsored coupon count from product.
				$wconsponsoredCount['where'] 	=	array('products_id' => (int)$CA['product_id'] );
				$productDetails 				=	$this->geneal_model->getData2('single', 'da_products', $wconsponsoredCount);

				if($productDetails['sponsored_coupon']):
					$sponsored_coupon = $productDetails['sponsored_coupon'];
				else:
					$sponsored_coupon = 1;
				endif;
				//END

				if($CA['is_donated'] == 'N'):

					//Start Create Coupons for simple product
					for($i=1; $i <= $CA['quantity']*$sponsored_coupon; $i++){
						
						if($CurrentTicketSequence['tickets_sequence_start']):

							// generating ticket order..
							if(!empty($SoldoutTicketList['coupon_sold_number'])):
								 $TicketSequence = $CurrentTicketSequence['tickets_sequence_start']+ $SoldoutTicketList['coupon_sold_number'] +$i;
								 $totalsoldqty = $SoldoutTicketList['coupon_sold_number'] +$i;
							else:
								$TicketSequence = $CurrentTicketSequence['tickets_sequence_start']+ $i;
								$totalsoldqty = $i;
							endif;
							$coupon_code = $CurrentTicketSequence['tickets_prefix'].$TicketSequence;

							array_push($couponList,$coupon_code);
							
							//Storing new ticket sequence in da_quickcoupons_totallist start
							// $quickcoupons["product_id"] 		=	(int)$ORparam['product_id'];
						 	$quickcoupons1["coupon_sold_number"] =	(int)$totalsoldqty;
						    $quickcoupons1["creation_ip"] 		=	$this->input->ip_address();
						    $quickcoupons1["updated_at"] 		=	date('Y-m-d H:i');

						    //Saving quick coupons number  
							// echo $totalsoldqty.'<br>';
							$this->geneal_model->editData('da_quickcoupons_totallist',$quickcoupons1,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);
						    //End
						    
						endif;

						$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
						$couponData['users_id']			= 	(int)$ORparam["user_id"];
						$couponData['users_email']		= 	$ORparam["user_email"];
						$couponData['order_id']			= 	$ORparam["order_id"];
						$couponData['product_id']		= 	$CA['product_id'];
						$couponData['product_title']	= 	$CA['product_name'];
						$couponData['is_donated'] 		=	'N';
						$couponData['coupon_status'] 	=	'Live';
						$couponData['coupon_code'] 		= 	$coupon_code;
						$couponData['draw_date'] 		= 	$ProductData['draw_date'];
						$couponData['draw_time'] 		= 	$ProductData['draw_time'];
						$couponData['coupon_type'] 		= 	'Simple';
						$couponData['created_at']		=	date('Y-m-d H:i');

						//creating coupon for secific product.
						$this->geneal_model->addData('da_coupons',$couponData);
					}//End Create Coupons

				endif;

				
				
				if($CA['is_donated'] == 'Y'):
					//Start Create Coupons for donate product
					for($i=1; $i <= $CA['quantity']*$sponsored_coupon*2; $i++){

						// generating ticket order..
						if(!empty($SoldoutTicketList['coupon_sold_number'])):
							 $TicketSequence = $CurrentTicketSequence['tickets_sequence_start']+ $SoldoutTicketList['coupon_sold_number'] +$i;
							 $totalsoldqty = $SoldoutTicketList['coupon_sold_number'] +$i;
						else:
							$TicketSequence = $CurrentTicketSequence['tickets_sequence_start']+ $i;
							$totalsoldqty = $i;
						endif;
						$coupon_code = $CurrentTicketSequence['tickets_prefix'].$TicketSequence;

						array_push($couponList,$coupon_code);
						
						//Storing new ticket sequence in da_quickcoupons_totallist start
						// $quickcoupons["product_id"] 		=	(int)$ORparam['product_id'];
					 	$quickcoupons1["coupon_sold_number"] =	(int)$totalsoldqty;
					    $quickcoupons1["creation_ip"] 		=	$this->input->ip_address();
					    $quickcoupons1["updated_at"] 		=	date('Y-m-d H:i');

					    //Saving quick coupons number  
						// echo $totalsoldqty.'<br>';
						$this->geneal_model->editData('da_quickcoupons_totallist',$quickcoupons1,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);
					    //End

						$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
						$couponData['users_id']			= 	(int)$ORparam["user_id"];
						$couponData['users_email']		= 	$ORparam["user_email"];
						$couponData['order_id']			= 	$ORparam["order_id"];
						$couponData['product_id']		= 	$CA['product_id'];
						$couponData['product_title']	= 	$CA['product_name'];
						$couponData['is_donated'] 		=	'Y';
						$couponData['coupon_status'] 	=	'Live';
						$couponData['coupon_code'] 		= 	$coupon_code;
						$couponData['draw_date'] 		= 	$ProductData['draw_date'];
						$couponData['draw_time'] 		= 	$ProductData['draw_time'];
						$couponData['coupon_type'] 		= 	'Donated';
						$couponData['created_at']		=	date('Y-m-d H:i');
						
						$this->geneal_model->addData('da_coupons',$couponData);
					}//End Create Coupons

				endif;

			endif;

			//Start Create Coupons for simple product
			// for($i=0; $i < $CA['quantity']; $i++){
			// 	A:
			// 	$whereCon['coupon_code']	=	'';
			// 	$whereCon['coupon_code']	=	strtoupper(uniqid(16));
			// 	$check 	= 	$this->geneal_model->checkDuplicate('da_coupons',$whereCon);
			// 	if($check == 0){
			// 		$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
			// 		$couponData['users_id']			= 	(int)$ORparam["user_id"];
			// 		$couponData['users_email']		= 	$ORparam["user_email"];
			// 		$couponData['order_id']			= 	$ORparam["order_id"];
			// 		$couponData['product_id']		= 	$CA['product_id'];
			// 		$couponData['product_title']	= 	$CA['product_name'];
			// 		$couponData['is_donated'] 		=	'N';
			// 		$couponData['coupon_status'] 	=	'Live';
			// 		$couponData['coupon_code'] 		= 	$whereCon['coupon_code'];
			// 		$couponData['coupon_type'] 		= 	'Simple';
			// 		$couponData['created_at']		=	date('Y-m-d H:i');

			// 		$this->geneal_model->addData('da_coupons',$couponData);
			// 	}else{
			// 		goto A;
			// 	}
			// }
			//End Create Coupons 

			//Start Create Coupons for donate product
			// if($CA['is_donated'] == 'Y'):
			// 	for($i=0; $i < $CA['quantity']; $i++){
			// 		B:
			// 		$whereCon['coupon_code']	=	'';
			// 		$whereCon['coupon_code']	=	strtoupper(uniqid(16));
			// 		$check 	= 	$this->geneal_model->checkDuplicate('da_coupons',$whereCon);
			// 		if($check == 0){
			// 			$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
			// 			$couponData['users_id']			= 	(int)$ORparam["user_id"];
			// 			$couponData['users_email']		= 	$ORparam["user_email"];
			// 			$couponData['order_id']			= 	$ORparam["order_id"];
			// 			$couponData['product_id']		= 	$CA['product_id'];
			// 			$couponData['product_title']	= 	$CA['product_name'];
			// 			$couponData['is_donated'] 		=	'Y';
			// 			$couponData['coupon_status'] 	=	'Live';
			// 			$couponData['coupon_code'] 		= 	$whereCon['coupon_code'];
			// 			$couponData['coupon_type'] 		= 	'Donated';
			// 			$couponData['created_at']		=	date('Y-m-d H:i');

			// 			$this->geneal_model->addData('da_coupons',$couponData);
			// 		}else{
			// 			goto B;
			// 		}
			// 	}
			// endif;
			//End Create Coupons

			$data['finalPrice'] 			= 	$ORparam["inclusice_of_vat"];

			$wcon['where'] 					=	array('order_id' => $ORparam["order_id"]);
			$data['couponDetails']			=	$this->geneal_model->getData2('multiple', 'da_coupons', $wcon);

			// Deduct the purchesed points and get available arabian points of user.
			$currentBal 					= 	$this->geneal_model->debitPointsByAPI($data['finalPrice'],$ORparam["user_id"]); 

			/* Load Balance Table -- after buy product*/
		    $Buyparam["load_balance_id"]	=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
			$Buyparam["user_id_cred"] 		=	(int)$ORparam["user_id"];
			$Buyparam["user_id_deb"]		=	(int)$ORparam["user_id"];
			$Buyparam["arabian_points"] 	=	(float)$data['finalPrice'];
			$Buyparam["order_id"] 			= 	$ORparam["order_id"];
		    $Buyparam["record_type"] 		=	'Debit';
		    $Buyparam["arabian_points_from"]=	'Purchase';
		    $Buyparam["creation_ip"] 		=	$this->input->ip_address();
		    $Buyparam["created_at"] 		=	date('Y-m-d H:i');
		    $Buyparam["created_by"] 		=	$ORparam["user_type"];
		    $Buyparam["status"] 			=	"A";
		    
		    $this->geneal_model->addData('da_loadBalance', $Buyparam);
		    /* End */

		    $where 			=	[ 'users_id' => (int)$ORparam["user_id"] ];
			$tblName 		=	'da_users';
			$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);
		    
			$membershipData = $this->geneal_model->getMembership((int)$userDetails['totalArabianPoints']);

			// Enabled cashback for once at a time.
			if($cashbackcount == 0):
				if($membershipData):
					$cashback 			=	$data['finalPrice'] * $membershipData['benifit'] /100;
					$data['cashback'] 	= 	$cashback;
					if($cashback):
						$insertCashback = array(
							'cashback_id'	=>	(int)$this->geneal_model->getNextSequence('da_cashback'),
							'user_id'		=>	(int)$ORparam["user_id"],
							'order_id'		=>	$ORparam["order_id"],
							'cashback'		=>	(float)$cashback,
							'created_at'	=>	date('Y-m-d H:i'),
						);
						$this->geneal_model->addData('da_cashback',$insertCashback);

						$where2 					=	['users_id' => (int)$ORparam["user_id"] ];
						$UserData					=	$this->geneal_model->getOnlyOneData('da_users', $where2);

						/* Load Balance Table -- after buy product*/
					    $Cashbparam["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
						$Cashbparam["user_id_cred"] 		=	(int)$ORparam["user_id"];
						$Cashbparam["user_id_deb"]			=	(int)$ORparam["user_id"];
						$Cashbparam["arabian_points"] 		=	(float)$cashback;
						$Cashbparam["order_id"] 				=	$ORparam["order_id"];
						$Cashbparam["availableArabianPoints"] 	=	(float)$UserData["availableArabianPoints"];
			        	$Cashbparam["end_balance"] 				=	(float)$UserData["availableArabianPoints"] + (float)$cashback ;
					    $Cashbparam["record_type"] 			=	'Credit';
					    $Cashbparam["arabian_points_from"] 	=	'Membership Cashback';
					    $Cashbparam["creation_ip"] 			=	$this->input->ip_address();
					    $Cashbparam["created_at"] 			=	date('Y-m-d H:i');
					    $Cashbparam["created_by"] 			=	$ORparam["user_type"];
					    $Cashbparam["status"] 				=	"A";
					    
					    $this->geneal_model->addData('da_loadBalance', $Cashbparam);
					    /* End */

					    // Credit the purchesed points and get available arabian points of user.
						$this->geneal_model->creaditPoints($cashback,$ORparam["user_id"]); 
						
					     $cashbackcount++;
					endif;
				endif;

			endif;


			//Add Referral Point
			$SPwhereCon['where']		=	array('BUY_USER_ID' => (int)$ORparam["user_id"], 'SHARED_PRODUCT_ID' => (int)$CA['product_id']);
			$shared_details 			=	$this->geneal_model->getData2('single','da_deep_link',$SPwhereCon);
			
			if($shared_details['SHARED_USER_ID'] && $shared_details['SHARED_USER_REFERRAL_CODE'] && $shared_details['SHARED_PRODUCT_ID']):
				if(isset($productIdPrice[$shared_details['SHARED_PRODUCT_ID']])):

					$prowhere['where']	=	array('products_id'=>(int)$shared_details['SHARED_PRODUCT_ID']);
					$prodData			=	$this->geneal_model->getData2('single','da_products',$prowhere);
					
					$sharewhere['where']=	array('users_id'=>(int)$shared_details['SHARED_USER_ID'],'products_id'=>(int)$shared_details['SHARED_PRODUCT_ID']);
					$shareCount			=	$this->geneal_model->getData2('count','da_product_share',$sharewhere);

					if($shareCount == NULL):
						$shareCount = 0;
					endif;

					if(isset($prodData['share_limit']) && $shareCount < $prodData['share_limit']):

						$param['share_id']					=	(int)$this->common_model->getNextSequence('da_product_share');
						$param['users_id']					=	(int)$shared_details['SHARED_USER_ID'];
						$param['products_id']				=	(int)$shared_details['SHARED_PRODUCT_ID'];
						$param['creation_date']				=   date('Y-m-d H:i');
						$param['creation_ip']				=   $this->input->ip_address();
						$this->common_model->addData('da_product_share',$param);

						$productCartAmount  		=	$productIdPrice[$shared_details['SHARED_PRODUCT_ID']];
						//First label referal amount Credit
						// $ref1tbl 					=	'referral_percentage';
						// $ref1where 					=	['referral_lebel' => (int)1 ];
						// $referal1Data				=	$this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
						$ref1tbl 					=	'da_products';
						$ref1where 					=	['products_id' => (int)$shared_details['SHARED_PRODUCT_ID'] ];
						$referal1Data				=	$this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
						if($referal1Data && $referal1Data['share_percentage_first'] > 0):
							$referal1Amount  		=	(($productCartAmount*$referal1Data['share_percentage_first'])/100);

							/* Referal Product Table -- after buy product*/
							$ref1Amtparam["referral_id"]			=	(int)$this->geneal_model->getNextSequence('referral_product');
							$ref1Amtparam["referral_user_code"] 	=	(int)$shared_details['SHARED_USER_REFERRAL_CODE'];
							$ref1Amtparam["referral_from_id"] 		=	(int)$shared_details['SHARED_USER_ID'];
							$ref1Amtparam["referral_to_id"]			=	(int)$ORparam["user_id"];
							$ref1Amtparam["referral_percent"] 		=	(float)$referal1Data['share_percentage_first'];
							$ref1Amtparam["referral_amount"] 		=	(float)$referal1Amount;
							$ref1Amtparam["referral_cart_amount"] 	=	(float)$productCartAmount;
							$ref1Amtparam["referral_product_id"] 	=	(int)$shared_details['SHARED_PRODUCT_ID'];
							$ref1Amtparam["creation_ip"] 			=	$this->input->ip_address();
							$ref1Amtparam["created_at"] 			=	date('Y-m-d H:i');
							$ref1Amtparam["created_by"] 			=	(int)$ORparam["user_id"];
							$ref1Amtparam["status"] 				=	"A";
							
							$this->geneal_model->addData('referral_product', $ref1Amtparam);
							/* End */



							if($ref1count == 0):

								/* Load Balance Table -- after buy product*/
								$ref1param["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
								$ref1param["user_id_cred"] 			=	(int)$shared_details['SHARED_USER_ID'];
								$ref1param["user_id_deb"]			=	(int)$shared_details['SHARED_USER_ID'];
								$ref1param["arabian_points"] 		=	(float)$referal1Amount;
								$ref1param["record_type"] 			=	'Credit';
								$ref1param["arabian_points_from"] 	=	'Referral';
								$ref1param["creation_ip"] 			=	$this->input->ip_address();
								$ref1param["created_at"] 			=	date('Y-m-d H:i');
								$ref1param["created_by"] 			=	(int)$ORparam["user_id"];
								$ref1param["status"] 				=	"A";
								
								$this->geneal_model->addData('da_loadBalance', $ref1param);

								$ref1count++;

							endif;

							$where25['where'] 				=	[ 'users_id' => (int)$shared_details['SHARED_USER_ID'] ];
							$sharedUserdata 				=	$this->geneal_model->getData2('single','da_users', $where25);
							$this->geneal_model->addData('da_test', $sharedUserdata);

							$userWhecrCon['where']		=	array('users_id' => (int)$sharedUserdata['users_id']);
							$totalArabianPoints 		= 	$sharedUserdata['totalArabianPoints'] + $ref1param["arabian_points"];
							$availableArabianPoints 	=	$sharedUserdata['availableArabianPoints'] + $ref1param["arabian_points"];
							
							$updateArabianPoints['totalArabianPoints']		=	(float)$totalArabianPoints;
							$updateArabianPoints['availableArabianPoints']	=	(float)$availableArabianPoints;

							$this->geneal_model->editDataByMultipleCondition('da_users',$updateArabianPoints,$userWhecrCon['where']);
							/* End */
						endif;

						//Second label referal amount Credit
						// $ref2checktbl 				=	'referral_product';
						// $ref2checkwhere 			=	['referral_to_id' => (int)$shared_details['SHARED_USER_ID'], 'referral_product_id' => (int)$shared_details['SHARED_PRODUCT_ID']];
						// $referal2checkData			=	$this->geneal_model->getOnlyOneData($ref2checktbl, $ref2checkwhere);
						// if($referal2checkData):
						// 	//$ref2tbl 					=	'referral_percentage';
						// 	//$ref2where 					=	['referral_lebel' => (int)2 ];
						// 	//$referal2Data				=	$this->geneal_model->getOnlyOneData($ref2tbl, $ref2where);

						// 	$ref2tbl 					=	'da_products';
						// 	$ref2where 					=	['products_id' => (int)$shared_details['SHARED_PRODUCT_ID'] ];
						// 	$referal2Data				=	$this->geneal_model->getOnlyOneData($ref2tbl, $ref2where);
						// 	if($referal2Data && $referal2Data['share_percentage_second'] > 0):
						// 		$referal2Amount  		=	(($productCartAmount*$referal2Data['share_percentage_second'])/100);

						// 		/* Load Balance Table -- after buy product*/
						// 		$ref1param["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
						// 		$ref1param["user_id_cred"] 			=	(int)$referal2checkData['referral_from_id'];
						// 		$ref1param["user_id_deb"]			=	(int)0;
						// 		$ref1param["product_id"] 			=	(int)$referal2checkData["referral_product_id"];
						// 		$ref1param["arabian_points"] 		=	(float)$referal2Amount;
						// 		$ref1param["record_type"] 			=	'Credit';
						// 		$ref1param["arabian_points_from"] 	=	'Referral';
						// 		$ref1param["creation_ip"] 			=	$this->input->ip_address();
						// 		$ref1param["created_at"] 			=	date('Y-m-d H:i');
						// 		$ref1param["created_by"] 			=	(int)$ORparam["user_id"];
						// 		$ref1param["status"] 				=	"A";

						// 		$this->geneal_model->addData('da_loadBalance', $ref1param);

						// 		$where25['where'] 				=	[ 'users_id' => (int)$shared_details['SHARED_USER_ID'] ];
						// 		$sharedUserdata 				=	$this->geneal_model->getData2('single','da_users', $where25);

						// 		$this->geneal_model->addData('da_test', $sharedUserdata);

						// 		$userWhecrCon['where']		=	array('users_id' => (int)$sharedUserdata['users_id']);
						// 		$totalArabianPoints 		= 	$sharedUserdata['totalArabianPoints'] + $ref1param["arabian_points"];
						// 		$availableArabianPoints 	=	$sharedUserdata['availableArabianPoints'] + $ref1param["arabian_points"];
								
						// 		$updateArabianPoints['totalArabianPoints']		=	(float)$totalArabianPoints;
						// 		$updateArabianPoints['availableArabianPoints']	=	(float)$availableArabianPoints;

						// 		$this->geneal_model->editDataByMultipleCondition('da_users',$updateArabianPoints,$userWhecrCon['where']);
						// 		/* End */
						// 	endif;
						// endif;
					endif;
				endif;
				$this->geneal_model->deleteData('da_deep_link', 'seq_id', (int)$shared_details['seq_id']);
			endif;
			//END

		endforeach;
		
		//Delete cart items.
		$this->geneal_model->deleteData('da_cartItems', 'user_id',(int)$ORparam["user_id"]); 

		$data['orderDetails']         		=	$currentOrderDetails;																//New code 21-09-2022
		$this->emailsendgrid_model->sendOrderMailToUser($ORparam["order_id"]);
		$this->sms_model->sendTicketDetails($ORparam['order_id']);
		return $data;
	}

	/* * *********************************************************************
	 * * Function name : stripePaymentIntent
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for stripe Payment Intent
	 * * Date : 21 JULY 2022
	 * * **********************************************************************/
	public function stripePaymentIntent()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('user_type') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_TYPE_EMPTY'),$result);
			// elseif($this->input->post('user_email') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('USER_EMAIL_EMPTY'),$result);
			elseif($this->input->post('user_phone') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_PHONE_EMPTY'),$result);
			elseif($this->input->post('product_is_donate') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_IN_DONATE_EMPTY'),$result);
			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('SHIPPINT_METHOD_EMPTY'),$result);


			//elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'ship' && $this->input->post('address') == ''): 
			//	echo outPut(0,lang('SUCCESS_CODE'),lang('SHIPPING_ADDRESS_EMPTY'),$result);

			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('emirate_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMIRATE_ID_EMPTY'),$result);
			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('emirate_name') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMIRATE_NAME_EMPTY'),$result);

			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('area_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('AREA_ID_EMPTY'),$result);
			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('area_name') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('AREA_NAME_EMPTY'),$result);

			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('collection_point_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('COLLECTION_POINT_ID_EMPTY'),$result);
			elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'pickup' && $this->input->post('collection_point_name') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('COLLECTION_POINT_NAME_EMPTY'),$result);


			elseif($this->input->post('shipping_charge') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('SHIPPING_CHARGE_EMPTY'),$result);
			elseif($this->input->post('inclusice_of_vat') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('INCLUSICE_OF_VAT_EMPTY'),$result);
			elseif($this->input->post('subtotal') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('SUBTOTAL_EMPTY'),$result);
			elseif($this->input->post('vat_amount') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('VAT_AMOUNT_EMPTY'),$result);
			else:
				
				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):

						$wcon['where']		=	[ 'user_id' => (int)$this->input->get('users_id') ];
						$cartItems          =	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);
						$productCount       =	$this->geneal_model->getData2('count', 'da_cartItems', $wcon);
						
						foreach($cartItems as $CA):
							// Available prodcut and soldout product details..
							$tblName = 'da_tickets_sequence';
							$whereCon2['where']		 			= 	array('product_id' => (int)$CA['id'] , 'status' => 'A');	
							$shortField 						= 	array('tickets_seq_id'=>'DESC');
							$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

							$tblName = 'da_quickcoupons_totallist';
							$whereCon3['where']		 			= 	array('product_id' => (int)$CA['id'] , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id'] );	
							$shortField3 						= 	array('tickets_seq_id'=>'DESC');
							$SoldoutTicketList					= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

							$available_ticket =	$CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
							
							if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
								$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
							endif;

							if( $this->input->post('product_is_donate') == "Y"):
								$check_availblity = $CA['qty'] * 2;
							elseif( $this->input->post('product_is_donate') == "N"):
								$check_availblity = $CA['qty'];
							endif;
							
							$left_ticket = $available_ticket - ($coupon_sold_number + $check_availblity);  

							// echo 'available_ticket == '.$available_ticket."<br>";
							// echo 'SoldoutTicketList == '.$coupon_sold_number."<br>";
							// echo  'left_ticket == '. $left_ticket."<br><br>";
							// die();

							if($left_ticket < 0):
				        		echo outPut(0,lang('SUCCESS_CODE'),lang('TICKET_NOT_AVAILABLE'). " for " .$CA['name'],$result);die();
								die();
							endif;
						
						endforeach;

						if($cartItems):
							// foreach($cartItems as $CA):
							// 	$inventoryStock = $this->geneal_model->getInventoryStock($this->input->post('collection_point_id'), $CA['id'], $CA['qty']);
							// 	if($inventoryStock == 'error'):
							// 		echo outPut(0,lang('SUCCESS_CODE'),lang('OUT_OF_STOCK'),$result);die();
							// 	endif;
							// endforeach;
							$inclusice_of_vat 				=	(int)(trim($this->input->post('inclusice_of_vat'))*100);
							$ORparam["order_id"]		    =	$this->geneal_model->getNextOrderId();
							$paymentIntent 					= 	createStripePaymentIntent($inclusice_of_vat,$this->input->post('user_email'),$ORparam["order_id"]);
							if($paymentIntent['status'] == 'success'):	
								$result['clientSecret']		=	$paymentIntent['client_secret'];

								/* Order Place Table */
						        $ORparam["sequence_id"]		    	=	(int)$this->geneal_model->getNextSequence('da_orders');
						        //$ORparam["order_id"]		        =	$this->geneal_model->getNextOrderId();
						        $ORparam["user_id"] 				=	(int)$this->input->get('users_id');
						        $ORparam["user_type"] 				=	$this->input->post('user_type');
								if($this->input->post('user_email')):
						        	$ORparam["user_email"] 				=	$this->input->post('user_email');
								else:
									$ORparam["user_email"] 				=	"";
								endif;
						        $ORparam["user_phone"] 				=	(int)$this->input->post('user_phone');

						        $ORparam["product_is_donate"] 		=	$this->input->post('product_is_donate');
						        if($ORparam["product_is_donate"] == 'N'):
						        	$ORparam["shipping_method"] 	=	$this->input->post('shipping_method');
						      //   	if($ORparam["shipping_method"] == 'ship'):
								    //     $shippingAddress			=	explode('_____', $this->input->post('address'));
							     //    	$ORparam["shipping_address"]=	$shippingAddress[1];
								    // else:
								    // 	$ORparam["shipping_address"]=	'';
								    // endif;

								    $ORparam["emirate_id"] 				=	$this->input->post('emirate_id');
						        	$ORparam["emirate_name"] 			=	$this->input->post('emirate_name');
									$ORparam["area_id"] 				=	$this->input->post('area_id');
					        		$ORparam["area_name"] 				=	$this->input->post('area_name');
						        	$ORparam["collection_point_id"] 	=	$this->input->post('collection_point_id');
						        	$ORparam["collection_point_name"] 	=	$this->input->post('collection_point_name');

								    $ORparam["shipping_address"]	=	'';

							    else:
							    	$ORparam["shipping_method"] 	=	'';

							    	$ORparam["emirate_id"] 				=	'';
						        	$ORparam["emirate_name"] 			=	'';
									$ORparam["area_id"] 				=	'';
					        		$ORparam["area_name"] 				=	'';
						        	$ORparam["collection_point_id"] 	=	'';
						        	$ORparam["collection_point_name"] 	=	'';

							    	$ORparam["shipping_address"] 	=	'';
							    endif;
								$ORparam["product_count"] 			=	(int)$productCount;
						        $ORparam["shipping_charge"] 		=	(float)$this->input->post('shipping_charge');
						        $ORparam["inclusice_of_vat"] 		=	(float)$this->input->post('inclusice_of_vat');
						        $ORparam["subtotal"] 				=	(float)$this->input->post('subtotal');
						        $ORparam["vat_amount"] 				=	(float)$this->input->post('vat_amount');
						        $ORparam["total_price"] 			=	(float)$ORparam["inclusice_of_vat"];
							    $ORparam["payment_mode"] 			=	'Stripe';
							    $ORparam["payment_from"] 			=	'App';
							    $ORparam["order_status"] 			=	"Initialize";
							    $ORparam["client_secret"] 			=	$paymentIntent['client_secret'];
							    $ORparam["creation_ip"] 			=	$this->input->ip_address();
							    $ORparam["created_at"] 				=	date('Y-m-d H:i');

							    $orderInsertID 						=	$this->geneal_model->addData('da_orders', $ORparam);

							    if($orderInsertID && $cartItems):
							        foreach($cartItems as $CA):	
										//Manage Inventory
										if($this->input->post('product_is_donate') == 'N'):
											$where['where']		=	array(
																		'products_id'			=>	(int)$CA['id'],
																		'collection_point_id' 	=>	(int)$ORparam["collection_point_id"]
																	);
											$INVcheck	=	$this->geneal_model->getData2('single','da_inventory',$where);
											if($INVcheck <> ''):
												$orqty = $INVcheck['order_request_qty'] + (int)$CA['qty'];
												$INVUpdate['order_request_qty']		=	$orqty;
												$this->geneal_model->editDataByMultipleCondition('da_inventory',$INVUpdate,$where['where']);
											else:
												$INVparam['products_id']				= 	(int)$CA['id'];
												$INVparam['qty']						=	(int)0;
												$INVparam['available_qty']				=	(int)0;
												$INVparam['order_request_qty']			=	(int)$CA['qty'];
												$INVparam['collection_point_id']		=	(int)$ORparam["collection_point_id"];

												$INVparam['inventory_id']				=	(int)$this->common_model->getNextSequence('da_inventory');
												
												$INVparam['creation_ip']				=	currentIp();
												$INVparam['creation_date']				=	(int)$this->timezone->utc_time();//currentDateTime();
												$INVparam['status']						=	'A';
												$this->geneal_model->addData('da_inventory', $INVparam);
											endif;
										endif;
										//END
										$ORDparam["order_details_id"] 	=	(int)$this->geneal_model->getNextSequence('da_orders_details');
										$ORDparam["order_sequence_id"]	=	(int)$ORparam["sequence_id"];
										$ORDparam["order_id"]			=	$ORparam["order_id"];
										$ORDparam["user_id"]			=	(int)$CA['user_id'];
										$ORDparam["product_id"] 		=	(int)$CA['id'];
										$ORDparam["product_name"] 		=	$CA['name'];
										$ORDparam["quantity"] 		    =	(int)$CA['qty'];
										if($CA['color']):
											$ORDparam["color"] 		    =	$CA['color'];
										endif;
										if($CA['size']):
											$ORDparam["size"] 		    =	$CA['size'];
										endif;
										$ORDparam["price"] 		        =	(float)$CA['price'];
										$ORDparam["tax"] 		        =	(float)0;
										$ORDparam["subtotal"] 		    =	(float)$CA['subtotal'];
										if($this->input->post('product_is_donate') == 'Y'):
											$ORDparam["is_donated"] 		=	'Y';
										else:
											$ORDparam["is_donated"] 		=	$CA['is_donated'];
										endif;
										//$ORDparam["is_donated"] 		=	$CA['is_donated'];
										$ORDparam["other"] 		        =	array(
																					'image' 		=>	$CA['other']->image,
																					'description' 	=>	$CA['other']->description,
																					'aed'			=>	$CA['other']->aed
																				);
										$ORDparam["current_ip"] 		=	$CA['current_ip'];
										$ORDparam["rowid"] 				=	$CA['rowid'];
										$ORDparam["curprodrowid"] 		=	$CA['curprodrowid'];

										$this->geneal_model->addData('da_orders_details', $ORDparam);
									endforeach;
								endif;
								
								//Get current order of user.
								$wcon1['where']					=	[ 'order_id' => $ORparam["order_id"] ];
								$result['orderData'] 			=	$this->geneal_model->getData2('single', 'da_orders', $wcon1);
								
								//Get current order details of user.
								$wcon2['where']					=	[ 'order_id' => $ORparam["order_id"] ];
								$result['orderDetails']         =	$this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);

								echo outPut(1,lang('SUCCESS_CODE'),lang('STRIPE_PAYMENT_INTENT_SUCCESS'),$result);
							else:
								echo outPut(1,lang('SUCCESS_CODE'),lang('STRIPE_PAYMENT_INTENT_FAILED'),$result);
							endif;
						else:
							echo outPut(0,lang('SUCCESS_CODE'),lang('CART_EMPTY'),$result);
						endif;
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);
					endif;
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : stripePaymentFinal
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for stripe Payment Final
	 * * Date : 21 JULY 2022
	 * * **********************************************************************/
	public function stripePaymentFinal()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('order_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('ORDER_ID_EMPTY'),$result);
			elseif($this->input->post('stripeToken') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('STRIPE_TOKEN_EMPTY'),$result);
			elseif($this->input->post('customerId') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('CUSTOMER_ID_EMPTY'),$result);
			elseif($this->input->post('captureAmount') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('CAPTURE_AMOUNT_EMPTY'),$result);
			elseif($this->input->post('stripeChargeId') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('STRIPE_CHARGE_ID_EMPTY'),$result);
			else:
				
				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):

						$wcon['where']		=	[ 'order_id' => $this->input->post('order_id') ];
						$orderData          =	$this->geneal_model->getData2('single', 'da_orders', $wcon);
						if($orderData):

							//update order status
							$updateStatus['stripe_token'] 		=	$this->input->post('stripeToken');
							$updateStatus['customer_id'] 		=	$this->input->post('customerId');
							$updateStatus['capture_amount'] 	=	$this->input->post('captureAmount');
							$updateStatus['stripe_charge_id'] 	=	$this->input->post('stripeChargeId');
							$updateStatus['order_status'] 		=	"Process";
							$this->geneal_model->editData('da_orders', $updateStatus, 'order_id', $this->input->post('order_id'));
							
							$paymentData['user_id'] 			=	(int)$this->input->get('users_id');
							$paymentData['user_email'] 			=	$userDetails['users_email'];
							$paymentData['inclusice_of_vat'] 	=	$updateStatus['capture_amount'];
							$paymentData['stripe_token'] 		=	$updateStatus['stripe_token'];
							$paymentData['user_type'] 			=	$userDetails['users_type'];

							$paymentData['order_id'] 			=	$this->input->post('order_id');
							$paymentData['stripeToken'] 		=	$this->input->post('stripeToken');
							$paymentData['customerId'] 			=	$this->input->post('customerId');
							$paymentData['captureAmount'] 		=	$this->input->post('captureAmount');
							$paymentData['stripeChargeId'] 		=	$this->input->post('stripeChargeId');
							$result['successData']				=	$this->successPaymentByStripe($paymentData);

							//Delete cart items.
							$this->geneal_model->deleteData('da_cartItems', 'user_id',(int)$this->input->get('users_id')); 

							$this->emailsendgrid_model->sendOrderMailToUser($this->input->post('order_id'));
							$this->sms_model->sendTicketDetails($this->input->post('order_id'));

							echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_SUCCESS'),$result);
						else:
							echo outPut(0,lang('SUCCESS_CODE'),lang('ORDET_ID_INVALID'),$result);
						endif;
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);
					endif;
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : successPaymentByStripe
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for success Payment By Stripe
	 * * Date : 21 JULY 2022
	 * * **********************************************************************/
	public function successPaymentByStripe($ORparam=array())
	{	
		$data['user_id'] 				=	$ORparam['user_id'];
		$data['user_email'] 			=	$ORparam['user_email'];
		$data['inclusice_of_vat'] 		=	$ORparam['inclusice_of_vat'];
		$data['stripe_token'] 			=	$ORparam['stripe_token'];
		$data['user_type'] 				=	$ORparam['user_type'];

		$data['order_id'] 				=	$ORparam['order_id'];
		$data['stripeToken'] 			=	$ORparam['stripeToken'];
		$data['customerId'] 			=	$ORparam['customerId'];
		$data['captureAmount'] 			=	$ORparam['captureAmount'];
		$data['stripeChargeId'] 		=	$ORparam['stripeChargeId'];

		//Get current order of user.
		$wcon['where']					=	[ 'order_id' => $ORparam["order_id"] ];
		$data['orderData'] 				=	$this->geneal_model->getData2('single', 'da_orders', $wcon);
		
		//Get current order details of user.
		$wcon2['where']					=	[ 'order_id' => $ORparam["order_id"] ];
		//$data['orderDetails']         	=	$this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);			//Old code 21-09-2022
		$OrderorderDetails        		=	$this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);				//New code 21-09-2022
		
		//update order status
		if($data['orderData']["product_is_donate"] == 'Y'){																				
			$updateParams 					=	array( 'order_status' => 'Success', 'collection_status' => 'Donated');	
		}else{
			$expairyData 					=	date('Y-m-d H:i', strtotime($data['orderData']['created_at']. ' 14 Days'));
			$collectionCode                 =   base64_encode(rand(1000,9999)); 
			$updateParams 					=	array('order_status' => 'Success', 'collection_code' => $collectionCode, 'collection_status' => 'Pending to collect', 'expairy_data' => $expairyData);	
		}
		$this->geneal_model->addData('da_test',$updateParams);
		//$updateStatus 					=	[ 'order_status' => 'Success' ];
		$updateorderstatus 				= 	$this->geneal_model->editData('da_orders', $updateParams, 'order_id', $ORparam["order_id"]);

		$currentOrderDetails 			=	array();																			//New code 21-09-2022
		//Generate coupon 
		//foreach($data['orderDetails'] as $CA):																				//Old code 21-09-2022
		$cashbackcount = 0;
		$ref1count = 0;

		foreach($OrderorderDetails as $CA):																						//New code 21-09-2022

			$CPwcon['where']					=	[ 'product_id' => $CA["product_id"] ];										//New code 21-09-2022
			$CPData								=	$this->geneal_model->getData2('single', 'da_prize', $CPwcon);				//New code 21-09-2022
			$CA['actual_product_name'] 			=	$CPData['title'];															//New code 21-09-2022
			array_push($currentOrderDetails,$CA);																				//New code 21-09-2022
			
			$this->geneal_model->updateStock($CA['product_id'],$CA['quantity']);
			// if($data['orderData']["product_is_donate"] == 'Y'):
			// 	$this->geneal_model->updateInventoryStock($data['orderData']['collection_point_id'],$CA['product_id'],$CA['quantity']);
			// endif;

			$productIdPrice[$CA['product_id']] 		=	($CA['quantity'] * $CA['price']);

			// //Get current Ticket order sequence from admin panel.
			$tblName = 'da_tickets_sequence';
			$whereCon2['where']		 			= 	array('product_id' => (int)$CA['product_id'] , 'status' => 'A');	
			$shortField 						= 	array('tickets_seq_id'=>'DESC');
			$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

			$tblName = 'da_quickcoupons_totallist';
			$whereCon3['where']		 			= 	array('product_id' => (int)$CA['product_id'] , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id']  );	
			$shortField3 						= 	array('coupon_id'=>'DESC');
			$SoldoutTicketList					= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

			$available_ticket =	$CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
			
			if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
				$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
			endif;

			if($this->input->post('product_is_donate') == 'Y'):
				$check_availblity = $this->input->post('product_qty') * 2;
			endif;

			$left_ticket = $available_ticket - ($coupon_sold_number + $check_availblity); 

			if($CA['is_donated'] == 'N' ):
				$soldOutQty = $CA['quantity'] ;
			elseif($CA['is_donated'] == "Y"):
			 	$soldOutQty = $CA['quantity']*2 ;
			endif;

			// Created 1st ticket sequence record.
			if(empty($SoldoutTicketList['coupon_sold_number'])):
				
				//Storing new ticket sequence in da_quickcoupons_totallist
				$quickcoupons["id"]					=	(int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
				$quickcoupons["product_id"] 		=	(int)$CA['product_id'];
			    $quickcoupons["tickets_seq_id"] 	=	(int)$CurrentTicketSequence['tickets_seq_id'];
			    $quickcoupons["coupon_sold_number"] =	'';//$soldOutQty;
			    $quickcoupons["creation_ip"] 		=	$this->input->ip_address();
			    $quickcoupons["created_at"] 		=	date('Y-m-d H:i');

			    //Saving quick coupons number  
			    $orderInsertID 						=	$this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons);

			else:
				// checking existing ticket sequence record.
				if($SoldoutTicketList['tickets_seq_id'] != $CurrentTicketSequence['tickets_seq_id'] ):
				//Storing new ticket sequence in da_quickcoupons_totallist
				$quickcoupons["id"]					=	(int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
				$quickcoupons["product_id"] 		=	(int)$CA['product_id'];
			    $quickcoupons["tickets_seq_id"] =		(int)$CurrentTicketSequence['tickets_seq_id'];
			    $quickcoupons["coupon_sold_number"] =	''; //$soldOutQty;
			    $quickcoupons["creation_ip"] 		=	$this->input->ip_address();
			    $quickcoupons["created_at"] 		=	date('Y-m-d H:i');

			    //Saving quick coupons number  
			    $orderInsertID 						=	$this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons);
					
				endif;

			endif;

			//Admin announced ticket available number.
			$available_ticket =  $CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'];
			
			if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
				$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
			endif;
			

			//defining varibale to store coupon list
			$couponList = array();

			if ($available_ticket > $coupon_sold_number ):

				// Getting product details
				$wcon2['where']					=	array('products_id' => (int)$CA['product_id'] );
				$ProductData        			=	$this->geneal_model->getData2('single', 'da_products', $wcon2);

				if($CA['is_donated'] == 'N'):

					//Start Create Coupons for simple product
					for($i=1; $i <= $CA['quantity']; $i++){
						
						if($CurrentTicketSequence['tickets_sequence_start']):

							// generating ticket order..
							if(!empty($SoldoutTicketList['coupon_sold_number'])):
								 $TicketSequence = $CurrentTicketSequence['tickets_sequence_start']+ $SoldoutTicketList['coupon_sold_number'] +$i;
								 $totalsoldqty = $SoldoutTicketList['coupon_sold_number'] +$i;
							else:
								$TicketSequence = $CurrentTicketSequence['tickets_sequence_start']+ $i;
								$totalsoldqty = $i;
							endif;
							$coupon_code = $CurrentTicketSequence['tickets_prefix'].$TicketSequence;

							array_push($couponList,$coupon_code);
							
							//Storing new ticket sequence in da_quickcoupons_totallist start
							// $quickcoupons["product_id"] 		=	(int)$ORparam['product_id'];
						 	$quickcoupons1["coupon_sold_number"] =	(int)$totalsoldqty;
						    $quickcoupons1["creation_ip"] 		=	$this->input->ip_address();
						    $quickcoupons1["updated_at"] 		=	date('Y-m-d H:i');

						    //Saving quick coupons number  
							// echo $totalsoldqty.'<br>';
							$this->geneal_model->editData('da_quickcoupons_totallist',$quickcoupons1,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);
						    //End
						    
						endif;

						$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
						$couponData['users_id']			= 	(int)$ORparam["user_id"];
						$couponData['users_email']		= 	$ORparam["user_email"];
						$couponData['order_id']			= 	$ORparam["order_id"];
						$couponData['product_id']		= 	$CA['product_id'];
						$couponData['product_title']	= 	$CA['product_name'];
						$couponData['is_donated'] 		=	'N';
						$couponData['coupon_status'] 	=	'Live';
						$couponData['coupon_code'] 		= 	$coupon_code;
						$couponData['draw_date'] 		= 	$ProductData['draw_date'];
						$couponData['draw_time'] 		= 	$ProductData['draw_time'];
						$couponData['coupon_type'] 		= 	'Simple';
						$couponData['created_at']		=	date('Y-m-d H:i');

						//creating coupon for secific product.
						$this->geneal_model->addData('da_coupons',$couponData);
					}//End Create Coupons

				endif;
				
				if($CA['is_donated'] == 'Y'):
					
					//Start Create Coupons for donate product
					for($i=1; $i <= $CA['quantity']*2; $i++){

						// generating ticket order..
						if(!empty($SoldoutTicketList['coupon_sold_number'])):
							 $TicketSequence = $CurrentTicketSequence['tickets_sequence_start']+ $SoldoutTicketList['coupon_sold_number'] +$i;
							 $totalsoldqty = $SoldoutTicketList['coupon_sold_number'] +$i;
						else:
							$TicketSequence = $CurrentTicketSequence['tickets_sequence_start']+ $i;
							$totalsoldqty = $i;
						endif;
						$coupon_code = $CurrentTicketSequence['tickets_prefix'].$TicketSequence;

						array_push($couponList,$coupon_code);
						
						//Storing new ticket sequence in da_quickcoupons_totallist start
						// $quickcoupons["product_id"] 		=	(int)$ORparam['product_id'];
					 	$quickcoupons1["coupon_sold_number"] =	(int)$totalsoldqty;
					    $quickcoupons1["creation_ip"] 		=	$this->input->ip_address();
					    $quickcoupons1["updated_at"] 		=	date('Y-m-d H:i');

					    //Saving quick coupons number  
						// echo $totalsoldqty.'<br>';
						$this->geneal_model->editData('da_quickcoupons_totallist',$quickcoupons1,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);
					    //End

						$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
						$couponData['users_id']			= 	(int)$ORparam["user_id"];
						$couponData['users_email']		= 	$ORparam["user_email"];
						$couponData['order_id']			= 	$ORparam["order_id"];
						$couponData['product_id']		= 	$CA['product_id'];
						$couponData['product_title']	= 	$CA['product_name'];
						$couponData['is_donated'] 		=	'Y';
						$couponData['coupon_status'] 	=	'Live';
						$couponData['coupon_code'] 		= 	$coupon_code;
						$couponData['draw_date'] 		= 	$ProductData['draw_date'];
						$couponData['draw_time'] 		= 	$ProductData['draw_time'];
						$couponData['coupon_type'] 		= 	'Donated';
						$couponData['created_at']		=	date('Y-m-d H:i');
						
						$this->geneal_model->addData('da_coupons',$couponData);
					}//End Create Coupons

				endif;

			endif;

			//Start Create Coupons for simple product
			// for($i=0; $i < $CA['quantity']; $i++){
			// 	A:
			// 	$whereCon['coupon_code']	=	'';
			// 	$whereCon['coupon_code']	=	strtoupper(uniqid(16));
			// 	$check 	= 	$this->geneal_model->checkDuplicate('da_coupons',$whereCon);
			// 	if($check == 0){
			// 		$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
			// 		$couponData['users_id']			= 	(int)$ORparam["user_id"];
			// 		$couponData['users_email']		= 	$ORparam["user_email"];
			// 		$couponData['order_id']			= 	$ORparam["order_id"];
			// 		$couponData['product_id']		= 	$CA['product_id'];
			// 		$couponData['product_title']	= 	$CA['product_name'];
			// 		$couponData['is_donated'] 		=	'N';
			// 		$couponData['coupon_status'] 	=	'Live';
			// 		$couponData['coupon_code'] 		= 	$whereCon['coupon_code'];
			// 		$couponData['coupon_type'] 		= 	'Simple';
			// 		$couponData['created_at']		=	date('Y-m-d H:i');

			// 		$this->geneal_model->addData('da_coupons',$couponData);
			// 	}else{
			// 		goto A;
			// 	}
			// }
			//End Create Coupons 

			//Start Create Coupons for donate product
			// if($CA['is_donated'] == 'Y'):
			// 	for($i=0; $i < $CA['quantity']; $i++){
			// 		B:
			// 		$whereCon['coupon_code']	=	'';
			// 		$whereCon['coupon_code']	=	strtoupper(uniqid(16));
			// 		$check 	= 	$this->geneal_model->checkDuplicate('da_coupons',$whereCon);
			// 		if($check == 0){
			// 			$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
			// 			$couponData['users_id']			= 	(int)$ORparam["user_id"];
			// 			$couponData['users_email']		= 	$ORparam["user_email"];
			// 			$couponData['order_id']			= 	$ORparam["order_id"];
			// 			$couponData['product_id']		= 	$CA['product_id'];
			// 			$couponData['product_title']	= 	$CA['product_name'];
			// 			$couponData['is_donated'] 		=	'Y';
			// 			$couponData['coupon_status'] 	=	'Live';
			// 			$couponData['coupon_code'] 		= 	$whereCon['coupon_code'];
			// 			$couponData['coupon_type'] 		= 	'Donated';
			// 			$couponData['created_at']		=	date('Y-m-d H:i');

			// 			$this->geneal_model->addData('da_coupons',$couponData);
			// 		}else{
			// 			goto B;
			// 		}
			// 	}
			// endif;
			//End Create Coupons

			$data['finalPrice'] 			= 	$data['orderData']['inclusice_of_vat'];
			$data['stripe_token'] 			= 	$ORparam["stripe_token"];

			$wcon['where'] 					=	array('order_id' => $ORparam["order_id"]);
			$data['couponDetails']			=	$this->geneal_model->getData2('multiple', 'da_coupons', $wcon);

			// Deduct the purchesed points and get available arabian points of user.
			// $currentBal 					= 	$this->geneal_model->debitPointsByAPI($data['finalPrice'],$ORparam["user_id"]); 

		    $where 			=	[ 'users_id' => (int)$ORparam["user_id"] ];
			$tblName 		=	'da_users';
			$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);
		    
			$membershipData = $this->geneal_model->getMembership((int)$userDetails['totalArabianPoints']);
			
			// Enabled cashback for once at a time.
			if($cashbackcount == 0):
				if($membershipData):
					$cashback 			=	$data['finalPrice'] * $membershipData['benifit'] /100;
					$data['cashback'] 	= 	$cashback;
					if($cashback):
						$insertCashback = array(
							'cashback_id'	=>	(int)$this->geneal_model->getNextSequence('da_cashback'),
							'user_id'		=>	(int)$ORparam["user_id"],
							'order_id'		=>	$ORparam["order_id"],
							'cashback'		=>	(float)$cashback,
							'created_at'	=>	date('Y-m-d H:i'),
						);
						$this->geneal_model->addData('da_cashback',$insertCashback);

						$where2 					=	['users_id' => (int)$ORparam["user_id"] ];
						$UserData					=	$this->geneal_model->getOnlyOneData('da_users', $where2);

						/* Load Balance Table -- after buy product*/
					    $Cashbparam["load_balance_id"]			=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
						$Cashbparam["user_id_cred"] 			=	(int)$ORparam["user_id"];
						$Cashbparam["user_id_deb"]				=	(int)$ORparam["user_id"];
						$Cashbparam["arabian_points"] 			=	(float)$cashback;
						$Cashbparam["order_id"] 				=	$ORparam["order_id"];
						$Cashbparam["availableArabianPoints"] 	=	(float)$UserData["availableArabianPoints"];
			        	$Cashbparam["end_balance"] 				=	(float)$UserData["availableArabianPoints"] + (float)$cashback ;
					    $Cashbparam["record_type"] 				=	'Credit';
					    $Cashbparam["arabian_points_from"] 		=	'Membership Cashback';
					    $Cashbparam["creation_ip"] 				=	$this->input->ip_address();
					    $Cashbparam["created_at"] 				=	date('Y-m-d H:i');
					    $Cashbparam["created_by"] 				=	$ORparam["user_type"];
					    $Cashbparam["status"] 					=	"A";
					    
					    $this->geneal_model->addData('da_loadBalance', $Cashbparam);

					    // Credit the purchesed points and get available arabian points of user.
						$this->geneal_model->creaditPoints($cashback,$ORparam["user_id"]); 
					    /* End */
					    $cashbackcount++;
					endif;
				endif;
			
			endif;

			//Add Referral Point
			$SPwhereCon['where']		=	array('BUY_USER_ID' => (int)$ORparam["user_id"], 'SHARED_PRODUCT_ID' => (int)$CA['product_id']);
			$shared_details 			=	$this->geneal_model->getData2('single','da_deep_link',$SPwhereCon);
			
			if($shared_details['SHARED_USER_ID'] && $shared_details['SHARED_USER_REFERRAL_CODE'] && $shared_details['SHARED_PRODUCT_ID']):
				if(isset($productIdPrice[$shared_details['SHARED_PRODUCT_ID']])):

					$prowhere['where']	=	array('products_id'=>(int)$shared_details['SHARED_PRODUCT_ID']);
					$prodData			=	$this->geneal_model->getData2('single','da_products',$prowhere);
					
					$sharewhere['where']=	array('users_id'=>(int)$shared_details['SHARED_USER_ID'],'products_id'=>(int)$shared_details['SHARED_PRODUCT_ID']);
					$shareCount			=	$this->geneal_model->getData2('count','da_product_share',$sharewhere);

					if($shareCount == NULL):
						$shareCount = 0;
					endif;

					if(isset($prodData['share_limit']) && $shareCount < $prodData['share_limit']):

						$param['share_id']					=	(int)$this->common_model->getNextSequence('da_product_share');
						$param['users_id']					=	(int)$shared_details['SHARED_USER_ID'];
						$param['products_id']				=	(int)$shared_details['SHARED_PRODUCT_ID'];
						$param['creation_date']				=   date('Y-m-d H:i');
						$param['creation_ip']				=   $this->input->ip_address();
						$this->common_model->addData('da_product_share',$param);

						$productCartAmount  		=	$productIdPrice[$shared_details['SHARED_PRODUCT_ID']];
						//First label referal amount Credit
						// $ref1tbl 					=	'referral_percentage';
						// $ref1where 					=	['referral_lebel' => (int)1 ];
						// $referal1Data				=	$this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
						$ref1tbl 					=	'da_products';
						$ref1where 					=	['products_id' => (int)$shared_details['SHARED_PRODUCT_ID'] ];
						$referal1Data				=	$this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
						if($referal1Data && $referal1Data['share_percentage_first'] > 0):
							$referal1Amount  		=	(($productCartAmount*$referal1Data['share_percentage_first'])/100);

							/* Referal Product Table -- after buy product*/
							$ref1Amtparam["referral_id"]			=	(int)$this->geneal_model->getNextSequence('referral_product');
							$ref1Amtparam["referral_user_code"] 	=	(int)$shared_details['SHARED_USER_REFERRAL_CODE'];
							$ref1Amtparam["referral_from_id"] 		=	(int)$shared_details['SHARED_USER_ID'];
							$ref1Amtparam["referral_to_id"]			=	(int)$ORparam["user_id"];
							$ref1Amtparam["referral_percent"] 		=	(float)$referal1Data['share_percentage_first'];
							$ref1Amtparam["referral_amount"] 		=	(float)$referal1Amount;
							$ref1Amtparam["referral_cart_amount"] 	=	(float)$productCartAmount;
							$ref1Amtparam["referral_product_id"] 	=	(int)$shared_details['SHARED_PRODUCT_ID'];
							$ref1Amtparam["creation_ip"] 			=	$this->input->ip_address();
							$ref1Amtparam["created_at"] 			=	date('Y-m-d H:i');
							$ref1Amtparam["created_by"] 			=	(int)$ORparam["user_id"];
							$ref1Amtparam["status"] 				=	"A";
							
							$this->geneal_model->addData('referral_product', $ref1Amtparam);
							/* End */

							if($ref1count == 0):

								/* Load Balance Table -- after buy product*/
								$ref1param["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
								$ref1param["user_id_cred"] 			=	(int)$shared_details['SHARED_USER_ID'];
								$ref1param["user_id_deb"]			=	(int)0;
								$ref1param["product_id"] 			=	(int)$ref1Amtparam["referral_product_id"];
								$ref1param["arabian_points"] 		=	(float)$referal1Amount;
								$ref1param["record_type"] 			=	'Credit';
								$ref1param["arabian_points_from"] 	=	'Referral';
								$ref1param["creation_ip"] 			=	$this->input->ip_address();
								$ref1param["created_at"] 			=	date('Y-m-d H:i');
								$ref1param["created_by"] 			=	(int)$ORparam["user_id"];
								$ref1param["status"] 				=	"A";
								
								$this->geneal_model->addData('da_loadBalance', $ref1param);
									
								$ref1count++;

							endif;

							$where25['where'] 				=	[ 'users_id' => (int)$shared_details['SHARED_USER_ID'] ];
							$sharedUserdata 				=	$this->geneal_model->getData2('single','da_users', $where25);
							$this->geneal_model->addData('da_test', $sharedUserdata);

							$userWhecrCon['where']		=	array('users_id' => (int)$sharedUserdata['users_id']);
							$totalArabianPoints 		= 	$sharedUserdata['totalArabianPoints'] + $ref1param["arabian_points"];
							$availableArabianPoints 	=	$sharedUserdata['availableArabianPoints'] + $ref1param["arabian_points"];
							
							$updateArabianPoints['totalArabianPoints']		=	(float)$totalArabianPoints;
							$updateArabianPoints['availableArabianPoints']	=	(float)$availableArabianPoints;

							$this->geneal_model->editDataByMultipleCondition('da_users',$updateArabianPoints,$userWhecrCon['where']);
							/* End */
						endif;

						//Second label referal amount Credit
						// $ref2checktbl 				=	'referral_product';
						// $ref2checkwhere 			=	['referral_to_id' => (int)$shared_details['SHARED_USER_ID'], 'referral_product_id' => (int)$shared_details['SHARED_PRODUCT_ID']];
						// $referal2checkData			=	$this->geneal_model->getOnlyOneData($ref2checktbl, $ref2checkwhere);
						// if($referal2checkData):
						// 	//$ref2tbl 					=	'referral_percentage';
						// 	//$ref2where 					=	['referral_lebel' => (int)2 ];
						// 	//$referal2Data				=	$this->geneal_model->getOnlyOneData($ref2tbl, $ref2where);

						// 	$ref2tbl 					=	'da_products';
						// 	$ref2where 					=	['products_id' => (int)$shared_details['SHARED_PRODUCT_ID'] ];
						// 	$referal2Data				=	$this->geneal_model->getOnlyOneData($ref2tbl, $ref2where);
						// 	if($referal2Data && $referal2Data['share_percentage_second'] > 0):
						// 		$referal2Amount  		=	(($productCartAmount*$referal2Data['share_percentage_second'])/100);

						// 		/* Load Balance Table -- after buy product*/
						// 		$ref1param["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
						// 		$ref1param["user_id_cred"] 			=	(int)$referal2checkData['referral_from_id'];
						// 		$ref1param["user_id_deb"]			=	(int)0;
						// 		$ref1param["product_id"] 			=	(int)$referal2checkData["referral_product_id"];
						// 		$ref1param["arabian_points"] 		=	(float)$referal2Amount;
						// 		$ref1param["record_type"] 			=	'Credit';
						// 		$ref1param["arabian_points_from"] 	=	'Referral';
						// 		$ref1param["creation_ip"] 			=	$this->input->ip_address();
						// 		$ref1param["created_at"] 			=	date('Y-m-d H:i');
						// 		$ref1param["created_by"] 			=	(int)$ORparam["user_id"];
						// 		$ref1param["status"] 				=	"A";

						// 		$this->geneal_model->addData('da_loadBalance', $ref1param);

						// 		$where25['where'] 				=	[ 'users_id' => (int)$shared_details['SHARED_USER_ID'] ];
						// 		$sharedUserdata 				=	$this->geneal_model->getData2('single','da_users', $where25);

						// 		$this->geneal_model->addData('da_test', $sharedUserdata);

						// 		$userWhecrCon['where']		=	array('users_id' => (int)$sharedUserdata['users_id']);
						// 		$totalArabianPoints 		= 	$sharedUserdata['totalArabianPoints'] + $ref1param["arabian_points"];
						// 		$availableArabianPoints 	=	$sharedUserdata['availableArabianPoints'] + $ref1param["arabian_points"];
								
						// 		$updateArabianPoints['totalArabianPoints']		=	(float)$totalArabianPoints;
						// 		$updateArabianPoints['availableArabianPoints']	=	(float)$availableArabianPoints;

						// 		$this->geneal_model->editDataByMultipleCondition('da_users',$updateArabianPoints,$userWhecrCon['where']);
						// 		/* End */
						// 	endif;
						// endif;
					endif;
				endif;
				$this->geneal_model->deleteData('da_deep_link', 'seq_id', (int)$shared_details['seq_id']);
			endif;
			//END

		endforeach;
		$data['orderDetails']         	=	$currentOrderDetails;																//New code 21-09-2022
		return $data;
	}

	/* * *********************************************************************
	 * * Function name : getWinnerList
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get Winner List
	 * * Date : 11 JULY 2022
	 * * Updated By  : Dilip Halder
	 * * Updated Date: 6 December 2023.
	 * * **********************************************************************/
	public function getWinnerList()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'GET')):

			// $recentWinners 				=	array();
			// $tblName4 					=	'da_winners';
			// $where4 					=	[];
			// $order4 					=	['creation_date' => 'desc'];
			// $data4						=	$this->geneal_model->getData($tblName4,$where4,$order4);
			// if($data4):
			// 	foreach($data4 as $info4):
			// 		$valid4 			= 	$info4['announcedDate'].' '.$info4['announcedTime'].':0';
			// 		$today4 			= 	date('Y-m-d H:i:s');
			// 		if(strtotime($valid4) > strtotime($today4)):
			// 			array_push($ourCampaigns,$info4);
			// 		endif;
			// 	endforeach;
			// endif;
			//$result['recentWinners'] 	=	$recentWinners;

			$tbl 							=	'da_winners';
			$order 							=	['creation_date' => 'desc'];
			// $result['recentWinners']		=	$this->geneal_model->getData($tbl, [],$order);
			$recentWinners		=	$this->geneal_model->getData($tbl, [],$order);


			foreach ($recentWinners as $key => $item) {
				$tableName  = 'da_products';
				$fields     = array('product_image');
				$fieldName  = 'products_id';
				$fieldValue = $item['product_id'];
				$products = $this->common_model->getSingleDataByParticularField($fields,$tableName,$fieldName,$fieldValue);
				$recentWinners[$key]['product_image']  = $products['product_image'];
			}
			
			$result['recentWinners'] = $recentWinners;
			echo outPut(1,lang('SUCCESS_CODE'),lang('GET_winner_DATA'),$result);
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}


	/* * *********************************************************************
	 * * Function name : searchProduct
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for search Product
	 * * Date : 11 JULY 2022
	 * * **********************************************************************/
	public function searchProduct()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->post('search_text') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('SEARCH_TEXT_EMPTY'),$result);
			else:

				$ourCampaigns 				=	array();
				$tblName2 					=	'da_products';
				$where2['where'] 			=	array( 'stock'=> array('$gt'=> 0,),'clossingSoon' => 'N','status' => 'A');
				$where2['like'] 			=	array('0'=>"title",'1'=>trim($this->input->post('search_text')));
				$order2 					=	array('creation_date'=>-1);
				$data2						=	$this->common_model->getData('multiple',$tblName2,$where2,$order2);
				if($data2): 
					foreach($data2 as $info2):
						$valid2 			= 	$info2['validuptodate'].' '.$info2['validuptotime'].':0';
						$today2 			= 	date('Y-m-d H:i:s');
						if(strtotime($valid2) > strtotime($today2)):
							if($this->input->post('users_id')):
								$prowhere['where']	=	array('users_id'=>(int)$this->input->post('users_id'),'product_id'=>(int)$info2['products_id']);
								$prodData			=	$this->common_model->getData('single','da_wishlist',$prowhere);
								if($prodData):
									if($prodData['wishlist_product'] <> 'Y'):
										$info2['wishlist_product']  = 'Y';
									else:
										$info2['wishlist_product']  = 'N';
									endif; 
								else:
									$info2['wishlist_product']  	= 'N';
								endif;
							else:
								$info2['wishlist_product']  		= 'N';
							endif;

							if($this->input->post('users_id')):
								$USRwhere 							=	[ 'users_id' => (int)$this->input->post('users_id') ];
								$USRtblName 						=	'da_users';
								$userDetails 						=	$this->geneal_model->getOnlyOneData($USRtblName, $USRwhere);
								if($userDetails):
									$productShareUrl  				= 	generateProductShareUrl($info2['products_id'],$this->input->post('users_id'),$userDetails['referral_code']);
									$info2['share_url']  			= 	$productShareUrl;
								else:	
									$info2['share_url']  			= 	'';
								endif;
							else:
								$info2['share_url']  				= 	'';
							endif;

							array_push($ourCampaigns,$info2);
						endif;
					endforeach;
				endif;
				if($ourCampaigns):
					$result['ourCampaigns'] 	=	$ourCampaigns;
					echo outPut(1,lang('SUCCESS_CODE'),lang('Search_success'),$result);
				else:
					$result['ourCampaigns'] 	=	array();
					echo outPut(1,lang('SUCCESS_CODE'),lang('Product_not_found'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	} // END OF FUNCTION
	/* * *********************************************************************
	 * * Function name : changeCollectionStatus
	 * * Developed By : Afsar Ali
	 * * Purpose  : This function used for search Product
	 * * Date : 15 NOV 2022
	 * * **********************************************************************/
	public function changeCollectionStatus()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			if($this->input->post('order_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('ORDER_ID_EMPTY'),$result);
			elseif($this->input->post('status') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('STATUS_EMPTY'),$result);
			elseif($this->input->post('collection_code') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('COLLECTION_CODE_EMPTY'),$result);
			else:
				$tblName 					=	'da_orders';
				$orderID 					=	$this->input->post('order_id');
				$collectionCode				=	base64_encode(strtoupper($this->input->post('collection_code')));
				$wcon['where']				=	[ 'order_id' => $orderID ];
				$orderData 					=	$this->geneal_model->getordersList('single', 'da_orders',$wcon,['sequence_id'=>-1]);
				$collection_id 				=	(int)$orderData['collection_point_id'];
				if($orderData['collection_code'] == $collectionCode):
					$error = 'YES';
					foreach($orderData['order_details'] as $CA): //Check inventory quantity
						$whereCon['where']		=	array(
														'collection_point_id' 	=> (int)$collection_id,
														'products_id'			=> (int)$CA['product_id']
													);
						$inventory 	=	$this->geneal_model->getData2('single','da_inventory',$whereCon);
						if($CA['is_donated'] == 'N'):
							if($inventory['available_qty'] >= $CA['quantity']):  
								$error = 'NO';
							endif;
						endif;
					endforeach;
					if($error == 'NO'):
						//Check inventory quantity and update
						foreach($orderData['order_details'] as $CA):
							$whereCon['where']		=	array(
															'collection_point_id' 	=> (int)$collection_id,
															'products_id'			=> (int)$CA['product_id']
														);
							$inventory 	=	$this->geneal_model->getData2('single','da_inventory',$whereCon);
							if($CA['is_donated'] == 'N'):
								if($inventory['available_qty'] >= $CA['quantity']): 
									$this->geneal_model->updateInventoryStock($collection_id,$CA['product_id'],$CA['quantity']);
								endif;
							endif;
						endforeach;
						//END
						$updateStatus				=	array('collection_status' => $this->input->post('status'));
					 	$this->geneal_model->editData($tblName,$updateStatus,'order_id',$orderID);
					 	$result = $this->geneal_model->getData2('single', 'da_orders', $wcon);
						echo outPut(1,lang('SUCCESS_CODE'),lang('PRODUCT_COLLECTED'),$result);
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_OUT_OF_STOCK'),$result);
					endif;
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	} //END OF FUNCTION
	/* * *********************************************************************
	 * * Function name : productRequest
	 * * Developed By : Afsar Ali
	 * * Purpose  : This function used for search Product
	 * * Date : 17 NOV 2022
	 * * **********************************************************************/
	public function productRequest()
	{
		
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			if($this->input->post('user_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:
				$tblName 					=	'da_emirate_collection_point';
				$user_id 					=	$this->input->post('user_id');
				$wcon['where']				=	[ 'users_id' => (int)$user_id ];
				$collectionPointList		=	$this->geneal_model->getData2('multiple', $tblName, $wcon);

				if(empty($collectionPointList)):
					echo outPut(0,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$result);die();
				else:
					$orWhere = array();
					foreach ($collectionPointList as $key => $items) {
						$where2['where']		=	array('collection_point_id' => (string)$items['collection_point_id']);
						$short = array('collection_status'=> -1 ,'sequence_id' => -1);
						$orderlist				=	$this->geneal_model->getproductrequestList('multiple','da_orders',$where2,$short);
						array_push($orWhere, $orderlist);
					}
					$result = $orWhere;
					if(!empty($result)){
						echo outPut(1,lang('SUCCESS_CODE'),lang('DATA_FOUND'),$result);
					}else{
						echo outPut(1,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$result);
					}
					
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	} //END OF FUNCTION.
	/* * *********************************************************************
	 * * Function name : stockReport
	 * * Developed By : Afsar Ali
	 * * Purpose  : This function used for search Product
	 * * Date : 17 NOV 2022
	 * * **********************************************************************/
	public function stockReport()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			if($this->input->post('user_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:
				$tblName 					=	'da_emirate_collection_point';
				$user_id 					=	$this->input->post('user_id');
				$wcon['where']				=	[ 'users_id' => (int)$user_id ];
				$collectionPointList		=	$this->geneal_model->getData2('multiple', $tblName, $wcon);
				if(empty($collectionPointList)){
					echo outPut(0,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$result);die();
				}
				$orWhere = array();
				foreach ($collectionPointList as $key => $items) {
					$where2['where']		=	array('collection_point_id' => (int)$items['collection_point_id']);
					$short = array('creation_date' => -1);
					$orderlist				=	$this->common_model->getInventoryList('multiple','da_inventory',$where2,$short);
					foreach ($orderlist as $key => $value) {
						$where3['where']	=	array('collection_point_id' => (int)$value['collection_point_id'], 'product_id' => (int)$value['products_id']);
						$prData 			=	 $this->geneal_model->getData2('multiple', 'da_product_request',$where3);
						$total_request_qty 	= 0;
						foreach ($prData as $key => $items) {
							$total_request_qty = $total_request_qty + $items['request_qty'];
						}
						$value['total_request_qty'] = $total_request_qty;
						array_push($orWhere, $value);	
					}
				}
				$result = $orWhere;
				echo outPut(1,lang('SUCCESS_CODE'),lang('DATA_FOUND'),$result);
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	} //END OF FUNCTION
	/* * *********************************************************************
	 * * Function name : addDeepLink
	 * * Developed By : Afsar Ali
	 * * Purpose  : This function used for search Product
	 * * Date : 28 NOV 2022
	 * * **********************************************************************/
	public function addDeepLink()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			if($this->input->post('user_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('SHARED_USER_ID') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('SHARED_USER_ID_EMPTY'),$result);
			elseif($this->input->post('SHARED_PRODUCT_ID') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('SHARED_PRODUCT_ID_EMPTY'),$result);
			elseif($this->input->post('link') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('LINK_EMPTY'),$result);
			else:
				$whereCon['where']					=	array('users_id' => (int)$this->input->post('user_id'));
				$userData							=	$this->geneal_model->getData2('single', 'da_users', $whereCon);

				$whereCon1['where']					=	array('BUY_USER_ID' => (int)$this->input->post('user_id'), 'SHARED_PRODUCT_ID' => (int)$this->input->post('SHARED_PRODUCT_ID'));
				$deepLink							=	$this->geneal_model->getData2('single', 'da_deep_link', $whereCon1);

				if($userData <> ''):
					$whereCon3['where']		=	array(
						'user_id' => (int)$this->input->post('SHARED_USER_ID'),
						'product_id' => (int)$this->input->post('SHARED_PRODUCT_ID'),
						'link'		=> base64_encode($this->input->post('link'))
								);
					$getMobileDeeplink		=	$this->geneal_model->getData2('single', 'da_shared_product_by_mobile', $whereCon3);

					$expDate = strtotime(date('Y-m-d H:i:s',strtotime($getMobileDeeplink['link_expired'].'+5 hour')));
					$currentTime = strtotime(date('Y-m-d H:i:s'));
					if($currentTime < $expDate):	
						if($deepLink <> ''):
							$param['SHARED_USER_ID']			=	(int)$this->input->post('SHARED_USER_ID');
							$param['SHARED_USER_REFERRAL_CODE']	=	$userData['referral_code'];
							$param['SHARED_PRODUCT_ID']			=	(int)$this->input->post('SHARED_PRODUCT_ID');
							$param['BUY_USER_ID']				=	(int)$this->input->post('user_id');
							$param['updated_at']				=	date('Y-m-d H:i');
							$this->geneal_model->editData('da_deep_link',$param,'seq_id',(int)$deepLink['seq_id']);
						else:
							$param['seq_id']					=	(int)$this->common_model->getNextSequence('da_deep_link');
							$param['SHARED_USER_ID']			=	(int)$this->input->post('SHARED_USER_ID');
							$param['SHARED_USER_REFERRAL_CODE']	=	$userData['referral_code'];
							$param['SHARED_PRODUCT_ID']			=	(int)$this->input->post('SHARED_PRODUCT_ID');
							$param['BUY_USER_ID']				=	(int)$this->input->post('user_id');
							$param['created_at']				=	date('Y-m-d H:i');
							$this->geneal_model->addData('da_deep_link',$param);
						endif;
						$result = $param;
						echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_MSG'),$result);
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('LINK_EXPIRE'),$result);
					endif;
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	} //END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : requestProductToAdmin
	 * * Developed By : Afsar Ali
	 * * Purpose  : This function used for search Product
	 * * Date : 29 NOV 2022
	 * * **********************************************************************/
	public function requestProductToAdmin()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			if($this->input->post('user_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('collection_point_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('COLLECTION_POINT_ID_EMPTY'),$result);
			elseif($this->input->post('product_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_ID_EMPTY'),$result);
			elseif($this->input->post('inventory_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('INVENTORY_ID_EMPTY'),$result);
			elseif($this->input->post('request_qty') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('REQUEST_QTY_EMPTY'),$result);
			else:
				$params['request_id']				=	(int)$this->geneal_model->getNextSequence('da_product_request');
				$params['collection_point_id']		=	(int)addslashes($this->input->post('collection_point_id'));
				$params['inventory_id']				=	(int)addslashes($this->input->post('inventory_id'));
				$params['product_id']				=	(int)addslashes($this->input->post('product_id'));
				$params['users_id']					=	(int)$this->input->post('user_id');
				$params['request_qty']				=	(int)addslashes($this->input->post('request_qty'));
				$params['sent_qty']					=	0;
				$params['status']					=	'P';
				$params['creation_date']			=	strtotime(date('Y-m-d h:i'));
				$params['creation_ip']				=	$this->input->ip_address();
				$params['created_by']				=	(int)$this->input->post('user_id');

				$isInsert 	=	$this->geneal_model->addData('da_product_request', $params);

				$result = $isInsert;
				echo outPut(1,lang('SUCCESS_CODE'),lang('PRODUCT_REQUESTED'),$result);
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	} //END OF FUNCTION
	/* * *********************************************************************
	 * * Function name 	: donateallproducts
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for change is donate status of cart items
	 * * Date 			: 21 DEC 2022
	 * * **********************************************************************/
	public function donateallproducts()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			if($this->input->post('user_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:
				$whereCon['where']					=	array('users_id' => (int)$this->input->post('user_id'));
				$userData							=	$this->geneal_model->getData2('single', 'da_users', $whereCon);
				if($userData <> ''):
					$wcon['where']		=	array('user_id' => (int)$this->input->post('user_id'));
					$cartItems          =	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);
					$updateParams['is_donated']		=	'Y';
					foreach ($cartItems as $key => $value) {
						$this->geneal_model->editData('da_cartItems', $updateParams, 'rowid', $value['rowid']);
					}
					$result = "Donate status updated.";
					echo outPut(1,lang('SUCCESS_CODE'),lang('DATA_ADDED'),$result);
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	} //END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : addMobileShearedLink
	 * * Developed By : Afsar Ali
	 * * Purpose  : This function used for add sheared link from mobile
	 * * Date : 31 DEC 2022
	 * * **********************************************************************/
	public function addMobileShearedLink()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			if($this->input->post('user_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('SHARED_PRODUCT_ID') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('SHARED_PRODUCT_ID_EMPTY'),$result);
			elseif($this->input->post('link') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('LINK_EMPTY'),$result);
			else:
				$whereCon['where']					=	array('users_id' => (int)$this->input->post('user_id'));
				$userData							=	$this->geneal_model->getData2('single', 'da_users', $whereCon);

				if($userData <> ''):
					$param['seq_id']				=	(int)$this->common_model->getNextSequence('da_shared_product_by_mobile');
					$param['user_id']				=	(int)$this->input->post('user_id');
					$param['product_id']			=	(int)$this->input->post('SHARED_PRODUCT_ID');
					$param['link']					=	base64_encode($this->input->post('link'));
					$param['link_expired']			=	date('Y-m-d H:i:s', strtotime('+5 hour'));
					$param['created_at']			=	date('Y-m-d H:i:s');

					$result = $this->geneal_model->addData('da_shared_product_by_mobile',$param);
					echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_MSG'),$result);
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	} //END OF FUNCTION

	/* * *********************************************************************
	 * * Function name 	: getLottoProductListPageData
	 * * Developed By 	: Dilip Halder
	 * * Purpose  		: This function used for get Product List Page Data
	 * * Date 			: 23 October 2023
	 * * **********************************************************************/
	public function getLottoProductListPageData()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'GET')):
			
			$ourCampaigns 				=	array();
			$tblName2 					=	'da_lotto_products';
			$where2['where'] 			=	array( 'stock'=> array('$gt'=> 0,),'clossingSoon' => 'N','status' => 'A','remarks'=> 'lotto-products');
			$order2 					=	['seq_order' => 1];
			$data2						=	$this->geneal_model->getData2('multiple',$tblName2,$where2,$order2);
			if($data2):
				foreach($data2 as $info2):
					$info2['product_name'] = $info2['title'];
					
					$valid2 			= 	$info2['validuptodate'].' '.$info2['validuptotime'].':0';
					$drawDate2 			= 	$info2['draw_date'].' '.$info2['draw_time'].':0';
					$today2 			= 	date('Y-m-d H:i:s');
					if(strtotime($valid2) > strtotime($today2) && strtotime($drawDate2) > strtotime($today2)):
						if($this->input->post('users_id')):
							$prowhere['where']	=	array('users_id'=>(int)$this->input->post('users_id'),'product_id'=>(int)$info2['products_id']);
							$prodData			=	$this->common_model->getData('single','da_wishlist',$prowhere);
							if($prodData):
								if($prodData['wishlist_product'] == 'Y'):
									$info2['wishlist_product']  = 'Y';
								else:
									$info2['wishlist_product']  = 'N';
								endif; 
							else:
								$info2['wishlist_product']  	= 'N';
							endif;
						else:
							$info2['wishlist_product']  		= 'N';
						endif;

						if($this->input->post('users_id')):
							$USRwhere 							=	[ 'users_id' => (int)$this->input->post('users_id') ];
							$USRtblName 						=	'da_users';
							$userDetails 						=	$this->geneal_model->getOnlyOneData($USRtblName, $USRwhere);
							if($userDetails):
								$productShareUrl  				= 	generateProductShareUrl($info2['products_id'],$this->input->post('users_id'),$userDetails['referral_code']);
								$info2['share_url']  			= 	$productShareUrl;
							else:	
								$info2['share_url']  			= 	'';
							endif;
						else:
							$info2['share_url']  				= 	'';
						endif;

						$product_prise_data 			= 	$this->geneal_model->getParticularDataByParticularField('prize_image','da_lotto_prize', 'product_id', $info2['products_id']);




						if($product_prise_data <> ''):
							$info2['product_prise_data']  = $product_prise_data;
						else:
							$info2['product_prise_data']  = '';
						endif;

						array_push($ourCampaigns,$info2);
					endif;
				endforeach;
			endif;

			 
			$NewourCampaigns = array();
			foreach ($ourCampaigns as $key => $items):
				 if(!empty($items['product_prise_data'])):
			 		$NewourCampaigns[] = $items;
				 endif;
			endforeach;
			$ourCampaigns = $NewourCampaigns;
			$result['ourCampaigns'] 	=	$ourCampaigns;

			echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_MSG'),$result);
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : testAPI
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get Home Page Data
	 * * Date : 27 JUNE 2022
	 * * **********************************************************************/
	public function testAPI()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		$result['head_api'] = json_encode(requestAuthenticate_test(APIKEY,'POST'));
		$result['post_api'] = json_encode(APIKEY);
		// $result['POST_API_KEY'] = APIKEY;
		// $result['SERVER'] = $_SERVER;
		// echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_MSG'),$result);

		if(requestAuthenticate_test(APIKEY,'POST')):
			echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_MSG'),$result);
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	
}