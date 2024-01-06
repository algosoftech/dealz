<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Telr extends CI_Controller {
    public function  __construct() 
    { 
        parent:: __construct();
        error_reporting(E_ALL ^ E_NOTICE);  
        $this->load->model(array('sms_model','notification_model','emailtemplate_model','emailsendgrid_model'));
        $this->lang->load('statictext', 'api');
        $this->load->helper('apidata');
        $this->load->model(array('geneal_model','common_model'));

        $this->user_agent       =   $_SERVER['HTTP_USER_AGENT'];
        $this->request_url      =   $_SERVER['REDIRECT_URL'];
        $this->method_name      =   $_SERVER['REDIRECT_QUERY_STRING'];

        $this->load->library('generatelogs',array('type'=>'users'));
    } 

    /***********************************************************************
    ** Function name    : generateOrderId
    ** Developed By     : Dilip Halder
    ** Purpose          : This function used for generate order ID
    ** Date             : 24 APRIL 2023
    ** Updated By       :
    ** Updated Date     : 
    ************************************************************************/  
    public function generateOrderId()
    {   
        $apiHeaderData      =   getApiHeaderData();
        $this->generatelogs->putLog('APP',logOutPut($_POST));
        $result                             =   array();    
        if(requestAuthenticate(APIKEY,'POST')):

            if($this->input->post('device_type') == 'ios' && $this->input->post('app_version') == '1.51' || $this->input->post('device_type') == 'ios' && $this->input->post('app_version') == '1.53'):
                echo outPut(0,lang('SUCCESS_CODE'), 'Online purchases are stoped in latest ios application due to technical issue. visit our website ( https://dealzarabia.com/ ) for Online purchases.' ,$result);
            endif;
            
            if($this->input->get('users_id') == ''): 
                echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
            elseif($this->input->post('user_type') == ''): 
                echo outPut(0,lang('SUCCESS_CODE'),lang('USER_TYPE_EMPTY'),$result);
            // elseif($this->input->post('user_email') == ''): 
            //  echo outPut(0,lang('SUCCESS_CODE'),lang('USER_EMAIL_EMPTY'),$result);
            elseif($this->input->post('user_phone') == ''): 
                echo outPut(0,lang('SUCCESS_CODE'),lang('USER_PHONE_EMPTY'),$result);
            elseif($this->input->post('product_is_donate') == ''): 
                echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_IN_DONATE_EMPTY'),$result);
            elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == ''): 
                echo outPut(0,lang('SUCCESS_CODE'),lang('SHIPPINT_METHOD_EMPTY'),$result);


            //elseif($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'ship' && $this->input->post('address') == ''): 
            //  echo outPut(0,lang('SUCCESS_CODE'),lang('SHIPPING_ADDRESS_EMPTY'),$result);

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
                
                $where          =   [ 'users_id' => (int)$this->input->get('users_id') ];
                $tblName        =   'da_users';
                $userDetails    =   $this->geneal_model->getOnlyOneData($tblName, $where);

                // // Match the user details to db
                // if(!empty($userDetails)):
                //     //Validate Users Details
                //     $post_data['user_email']	=	$this->input->post('user_email');
                //     $post_data['user_phone']	=	$this->input->post('user_phone');
                //     $invalidUsr = 0;
                //     $usr_email      =   $post_data['user_email'];
                //     $usr_mobile     =   $post_data['user_phone'];
            
                //     if($post_data['user_email'] == ''):
                //         $usr_email  =   "dealzarabiasales1@gmail.com";     
                //     else:
                //         if($userDetails['users_email'] != $post_data['user_email']):
                //             $invalidUsr = 1;
                //         endif;
                //     endif;
            
                //     if($post_data['user_phone'] == ''):
                //         $usr_mobile  =   "555555555";     
                //     else:
                //         if($userDetails['users_mobile'] != $usr_mobile):
                //             $invalidUsr = 1;
                //         endif;
                //     endif;
            
                //     // if($invalidUsr == 1):
                //     //     // echo outPut(0,lang('SUCCESS_CODE'),'We can not proceed your request. Please login again.',$result);die();
                //     //     // echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);die();
                //     //     $userDetails = [];
                //     // endif;
                //     //End
                // endif;
                // // END


                if(!empty($userDetails)):
                    if($userDetails['status'] == 'A'):

                        $wcon['where']      =   [ 'user_id' => (int)$this->input->get('users_id') ];
                        $cartItems          =   $this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);
                        $productCount       =   $this->geneal_model->getData2('count', 'da_cartItems', $wcon);

                        foreach($cartItems as $CA):
                            // Available prodcut and soldout product details..
                            $tblName = 'da_tickets_sequence';
                            $whereCon2['where']                 =   array('product_id' => (int)$CA['id'] , 'status' => 'A');    
                            $shortField                         =   array('tickets_seq_id'=>'DESC');
                            $CurrentTicketSequence              =   $this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

                            $tblName = 'da_quickcoupons_totallist';
                            $whereCon3['where']                 =   array('product_id' => (int)$CA['id'] , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id'] ); 
                            $shortField3                        =   array('tickets_seq_id'=>'DESC');
                            $SoldoutTicketList                  =   $this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

                            $available_ticket = $CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
                            
                            if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
                                $coupon_sold_number =   $SoldoutTicketList['coupon_sold_number'];
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
                           
                            $inclusice_of_vat               =   (int)(trim($this->input->post('inclusice_of_vat'))*100);
                            $ORparam["order_id"]            =   $this->geneal_model->getNextOrderId();
                            if($ORparam["order_id"]):  

                                /* Order Place Table */
                                $ORparam["sequence_id"]             =   (int)$this->geneal_model->getNextSequence('da_orders');
                                //$ORparam["order_id"]              =   $this->geneal_model->getNextOrderId();
                                $ORparam["user_id"]                 =   (int)$this->input->get('users_id');
                                $ORparam["user_type"]               =   $this->input->post('user_type');
                                if($this->input->post('user_email')):
                                    $ORparam["user_email"]              =   $this->input->post('user_email');
                                else:
                                    $ORparam["user_email"]              =   "dealzarabiasales1@gmail.com";
                                endif;
                                $ORparam["user_phone"]              =   (int)$this->input->post('user_phone');

                                $ORparam["product_is_donate"]       =   $this->input->post('product_is_donate');
                                if($ORparam["product_is_donate"] == 'N'):
                                    $ORparam["shipping_method"]     =   $this->input->post('shipping_method');
                                    $ORparam["emirate_id"]              =   $this->input->post('emirate_id');
                                    $ORparam["emirate_name"]            =   $this->input->post('emirate_name');
                                    $ORparam["area_id"]                 =   $this->input->post('area_id');
                                    $ORparam["area_name"]               =   $this->input->post('area_name');
                                    $ORparam["collection_point_id"]     =   $this->input->post('collection_point_id');
                                    $ORparam["collection_point_name"]   =   $this->input->post('collection_point_name');

                                    $ORparam["shipping_address"]    =   '';

                                else:
                                    $ORparam["shipping_method"]     =   '';

                                    $ORparam["emirate_id"]              =   '';
                                    $ORparam["emirate_name"]            =   '';
                                    $ORparam["area_id"]                 =   '';
                                    $ORparam["area_name"]               =   '';
                                    $ORparam["collection_point_id"]     =   '';
                                    $ORparam["collection_point_name"]   =   '';

                                    $ORparam["shipping_address"]    =   '';
                                endif;
                                $ORparam["product_count"]           =   (int)$productCount;
                                $ORparam["shipping_charge"]         =   (float)$this->input->post('shipping_charge');
                                $ORparam["inclusice_of_vat"]        =   (float)$this->input->post('inclusice_of_vat');
                                $ORparam["subtotal"]                =   (float)$this->input->post('subtotal');
                                $ORparam["vat_amount"]              =   (float)$this->input->post('vat_amount');
                                $ORparam["total_price"]             =   (float)$ORparam["inclusice_of_vat"];
                                $ORparam["payment_mode"]            =   'Telr';
                                $ORparam["payment_from"]            =   'App';
                                $ORparam["device_type"]             =   $this->input->post('device_type');
                                $ORparam["app_version"]             =   $this->input->post('app_version');
                                $ORparam["order_status"]            =   "Initialize";
                                $ORparam["creation_ip"]             =   $this->input->ip_address();
                                $ORparam["created_at"]              =   date('Y-m-d H:i');

                                $orderInsertID                      =   $this->geneal_model->addData('da_orders', $ORparam);
 
                                if($orderInsertID && $cartItems):
                                    foreach($cartItems as $CA): 
                                        //Manage Inventory
                                        if($this->input->post('product_is_donate') == 'N'):
                                            $where['where']     =   array(
                                                                        'products_id'           =>  (int)$CA['id'],
                                                                        'collection_point_id'   =>  (int)$ORparam["collection_point_id"]
                                                                    );
                                            $INVcheck   =   $this->geneal_model->getData2('single','da_inventory',$where);
                                            if($INVcheck <> ''):
                                                $orqty = $INVcheck['order_request_qty'] + (int)$CA['qty'];
                                                $INVUpdate['order_request_qty']     =   $orqty;
                                                $this->geneal_model->editDataByMultipleCondition('da_inventory',$INVUpdate,$where['where']);
                                            else:
                                                $INVparam['products_id']                =   (int)$CA['id'];
                                                $INVparam['qty']                        =   (int)0;
                                                $INVparam['available_qty']              =   (int)0;
                                                $INVparam['order_request_qty']          =   (int)$CA['qty'];
                                                $INVparam['collection_point_id']        =   (int)$ORparam["collection_point_id"];

                                                $INVparam['inventory_id']               =   (int)$this->common_model->getNextSequence('da_inventory');
                                                
                                                $INVparam['creation_ip']                =   currentIp();
                                                $INVparam['creation_date']              =   (int)$this->timezone->utc_time();//currentDateTime();
                                                $INVparam['status']                     =   'A';
                                                $this->geneal_model->addData('da_inventory', $INVparam);
                                            endif;
                                        endif;
                                        //END
                                        $ORDparam["order_details_id"]   =   (int)$this->geneal_model->getNextSequence('da_orders_details');
                                        $ORDparam["order_sequence_id"]  =   (int)$ORparam["sequence_id"];
                                        $ORDparam["order_id"]           =   $ORparam["order_id"];
                                        $ORDparam["user_id"]            =   (int)$CA['user_id'];
                                        $ORDparam["product_id"]         =   (int)$CA['id'];
                                        $ORDparam["product_name"]       =   $CA['name'];
                                        $ORDparam["quantity"]           =   (int)$CA['qty'];
                                        if($CA['color']):
                                            $ORDparam["color"]          =   $CA['color'];
                                        endif;
                                        if($CA['size']):
                                            $ORDparam["size"]           =   $CA['size'];
                                        endif;
                                        $ORDparam["price"]              =   (float)$CA['price'];
                                        $ORDparam["tax"]                =   (float)0;
                                        $ORDparam["subtotal"]           =   (float)$CA['subtotal'];
                                        if($this->input->post('product_is_donate') == 'Y'):
                                            $ORDparam["is_donated"]         =   'Y';
                                        else:
                                            $ORDparam["is_donated"]         =   $CA['is_donated'];
                                        endif;
                                        //$ORDparam["is_donated"]       =   $CA['is_donated'];
                                        $ORDparam["other"]              =   array(
                                                                                    'image'         =>  $CA['other']->image,
                                                                                    'description'   =>  $CA['other']->description,
                                                                                    'aed'           =>  $CA['other']->aed
                                                                                );
                                        $ORDparam["current_ip"]         =   $CA['current_ip'];
                                        $ORDparam["rowid"]              =   $CA['rowid'];
                                        $ORDparam["curprodrowid"]       =   $CA['curprodrowid'];

                                        $this->geneal_model->addData('da_orders_details', $ORDparam);
                                    endforeach;
                                endif;

                                //Get current order of user.
                                $wcon1['where']                 =   [ 'order_id' => $ORparam["order_id"] ];
                                $result['orderData']            =   $this->geneal_model->getData2('single', 'da_orders', $wcon1);
                                
                                //Get current order details of user.
                                $wcon2['where']                 =   [ 'order_id' => $ORparam["order_id"] ];
                                $result['orderDetails']         =   $this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);
                                


                                echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_ID_GENERATED'),$result);
                            else:
                                echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_ID_GENERATION_FAILED'),$result);
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

     /***********************************************************************
    ** Function name    : telrOrderSuccess
    ** Developed By     : Dilip Halder
    ** Purpose          : This function used for generate order ID
    ** Date             : 24 APRIL 2023
    ** Updated By       :
    ** Updated Date     : 
    ************************************************************************/  
    public function telrOrderSuccess()
    {
        $apiHeaderData      =   getApiHeaderData();
        $this->generatelogs->putLog('APP',logOutPut($_POST));
        $result                             =   array();    
        if(requestAuthenticate(APIKEY,'GET')):
            
             if($this->input->get('order_id') == ''): 
                echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
            // elseif($this->input->get('users_id') == ''): 
                // echo outPut(0,lang('SUCCESS_CODE'),lang('ORDER_ID_EMPTY'),$result);
            else:

                if($this->input->get('users_id')):
                    $where          =   [ 'users_id' => (int)$this->input->get('users_id') ];
                    $tblName        =   'da_users';
                    $userDetails    =   $this->geneal_model->getOnlyOneData($tblName, $where);
                    
                    $where2         =   [ 'user_id' => (int)$this->input->get('users_id') , 'order_id' => $this->input->get('order_id') ];

                else:
                    $where2         =   [ 'user_phone' => $this->input->get('user_phone') , 'order_id' => $this->input->get('order_id') ];
                endif;

                $tblName        =   'da_orders';
                $ORparam   =   $this->geneal_model->getOnlyOneData($tblName, $where2);
                
                $orderData = array();
                if(!empty($ORparam)  && !empty($userDetails) || !empty($ORparam)  && $ORparam['user_id'] === 0 ):
                    
                    $data['user_id']                =   $ORparam['user_id'];
                    $data['user_email']             =   $ORparam['user_email'];
                    $data['inclusice_of_vat']       =   $ORparam['inclusice_of_vat'];
                    $data['stripe_token']           =   $ORparam['stripe_token'];
                    $data['user_type']              =   $ORparam['user_type'];

                    $data['order_id']               =   $ORparam['order_id'];
                    $data['stripeToken']            =   $ORparam['stripeToken'];
                    $data['customerId']             =   $ORparam['customerId'];
                    $data['captureAmount']          =   $ORparam['captureAmount'];
                    $data['stripeChargeId']         =   $ORparam['stripeChargeId'];

                    //Get current order of user.
                    $wcon['where']                  =   [ 'order_id' => $ORparam["order_id"] ];
                    $data['orderData']              =   $this->geneal_model->getData2('single', 'da_orders', $wcon);
                 
                    array_push($orderData,$data['orderData'] );
                    //Get current order details of user.
                    $wcon2['where']                 =   [ 'order_id' => $ORparam["order_id"] ];
                    $OrderorderDetails              =   $this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);

                    //update order status
                    if($data['orderData']["product_is_donate"] == 'Y'){                                                                             
                        $updateParams                   =   array('transaction_id'=> $this->input->get('transaction_id') , 'order_status' => 'Success', 'collection_status' => 'Donated');  
                    }else{
                        $expairyData                    =   date('Y-m-d H:i', strtotime($data['orderData']['created_at']. ' 14 Days'));
                        $collectionCode                 =   base64_encode(rand(1000,9999)); 
                        $updateParams                   =   array('transaction_id'=> $this->input->get('transaction_id') , 'order_status' => 'Success', 'collection_code' => $collectionCode, 'collection_status' => 'Pending to collect', 'expairy_data' => $expairyData);  
                    }
                    $this->geneal_model->addData('da_test',$updateParams);
                    //$updateStatus                     =   [ 'order_status' => 'Success' ];
                    $updateorderstatus              =   $this->geneal_model->editData('da_orders', $updateParams, 'order_id', $ORparam["order_id"]);

                    $currentOrderDetails            =   array();                                                                            //New code 21-09-2022
                    $couponDetails                  =   array();  
                    $CashbackDetails                =   array(); 
                                                                                              //New code 21-09-2022
                    //Generate coupon 
                    //foreach($data['orderDetails'] as $CA):                                                                                //Old code 21-09-2022
                    foreach($OrderorderDetails as $CA):                                                                                     //New code 21-09-2022

                        $CPwcon['where']                    =   [ 'product_id' => $CA["product_id"] ];                                      //New code 21-09-2022
                        $CPData                             =   $this->geneal_model->getData2('single', 'da_prize', $CPwcon);               //New code 21-09-2022
                        $CA['actual_product_name']          =   $CPData['title'];                                                           //New code 21-09-2022
                        array_push($currentOrderDetails,$CA);                                                                               //New code 21-09-2022
                        
                        $this->geneal_model->updateStock($CA['product_id'],$CA['quantity']);
                        // if($data['orderData']["product_is_donate"] == 'Y'):
                        //  $this->geneal_model->updateInventoryStock($data['orderData']['collection_point_id'],$CA['product_id'],$CA['quantity']);
                        // endif;

                        $productIdPrice[$CA['product_id']]      =   ($CA['quantity'] * $CA['price']);

                        // //Get current Ticket order sequence from admin panel.
                        $tblName = 'da_tickets_sequence';
                        $whereCon2['where']                 =   array('product_id' => (int)$CA['product_id'] , 'status' => 'A');    
                        $shortField                         =   array('tickets_seq_id'=>'DESC');
                        $CurrentTicketSequence              =   $this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

                        $tblName = 'da_quickcoupons_totallist';
                        $whereCon3['where']                 =   array('product_id' => (int)$CA['product_id'] , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id']  );    
                        $shortField3                        =   array('coupon_id'=>'DESC');
                        $SoldoutTicketList                  =   $this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

                        $available_ticket = $CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
                        
                        if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
                            $coupon_sold_number =   $SoldoutTicketList['coupon_sold_number'];
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
                            $quickcoupons["id"]                 =   (int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
                            $quickcoupons["product_id"]         =   (int)$CA['product_id'];
                            $quickcoupons["tickets_seq_id"]     =   (int)$CurrentTicketSequence['tickets_seq_id'];
                            $quickcoupons["coupon_sold_number"] =   '';//$soldOutQty;
                            $quickcoupons["creation_ip"]        =   $this->input->ip_address();
                            $quickcoupons["created_at"]         =   date('Y-m-d H:i');

                            //Saving quick coupons number  
                            $orderInsertID                      =   $this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons);

                        else:
                            // checking existing ticket sequence record.
                            if($SoldoutTicketList['tickets_seq_id'] != $CurrentTicketSequence['tickets_seq_id'] ):
                            //Storing new ticket sequence in da_quickcoupons_totallist
                                $quickcoupons["id"]                 =   (int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
                                $quickcoupons["product_id"]         =   (int)$CA['product_id'];
                                $quickcoupons["tickets_seq_id"] =       (int)$CurrentTicketSequence['tickets_seq_id'];
                            $quickcoupons["coupon_sold_number"] =   ''; //$soldOutQty;
                            $quickcoupons["creation_ip"]        =   $this->input->ip_address();
                            $quickcoupons["created_at"]         =   date('Y-m-d H:i');

                            //Saving quick coupons number  
                            $orderInsertID                      =   $this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons);

                        endif;

                    endif;

                    //Admin announced ticket available number.
                     $available_ticket =  $CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'];
                    
                    if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
                        $coupon_sold_number =   $SoldoutTicketList['coupon_sold_number'];
                    endif;
                    
                    //defining varibale to store coupon list
                    $couponList = array();

                    if ($available_ticket > $coupon_sold_number ):

                        // Getting product details
                        $wcon2['where']                 =   array('products_id' => (int)$CA['product_id'] );
                        $ProductData                    =   $this->geneal_model->getData2('single', 'da_products', $wcon2);

                        if($CA['is_donated'] == 'N'):

                                //Start Create Coupons for simple product
                            for($i=1; $i <= $CA['quantity']*$ProductData['sponsored_coupon']; $i++){

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
                                        // $quickcoupons["product_id"]      =   (int)$ORparam['product_id'];
                                    $quickcoupons1["coupon_sold_number"] =  (int)$totalsoldqty;
                                    $quickcoupons1["creation_ip"]       =   $this->input->ip_address();
                                    $quickcoupons1["updated_at"]        =   date('Y-m-d H:i');

                                            //Saving quick coupons number  
                                            // echo $totalsoldqty.'<br>';
                                    $this->geneal_model->editData('da_quickcoupons_totallist',$quickcoupons1,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);
                                            //End

                                endif;

                                $couponData['coupon_id']        =   (int)$this->geneal_model->getNextSequence('da_coupons');
                                $couponData['users_id']         =   (int)$ORparam["user_id"];
                                $couponData['users_email']      =   $ORparam["user_email"];
                                $couponData['order_id']         =   $ORparam["order_id"];
                                $couponData['product_id']       =   $CA['product_id'];
                                $couponData['product_title']    =   $CA['product_name'];
                                $couponData['is_donated']       =   'N';
                                $couponData['coupon_status']    =   'Live';
                                $couponData['coupon_code']      =   $coupon_code;
                                $couponData["device_type"]      =   $this->input->get('device_type');
                                $couponData["app_version"]      =   $this->input->get('app_version');
                                $couponData['draw_date']        =   $ProductData['draw_date'];
                                $couponData['draw_time']        =   $ProductData['draw_time'];
                                $couponData['coupon_type']      =   'Simple';
                                $couponData['created_at']       =   date('Y-m-d H:i');

                                array_push($couponDetails,$couponData);
                                        //creating coupon for secific product.
                                $this->geneal_model->addData('da_coupons',$couponData);
                            }//End Create Coupons

                        endif;

                         //Get current sponsored coupon count from product. Added on 25-06-2023
                         $wconsponsoredCount['where']   =   array('products_id' => (int)$CA['product_id'] );
                         $productDetails                =   $this->geneal_model->getData2('single', 'da_products', $wconsponsoredCount);

                         if($productDetails['sponsored_coupon']):
                             $sponsored_coupon = $productDetails['sponsored_coupon']*2;
                         else:
                             $sponsored_coupon = 2;
                         endif;
                         //END
                            
                            if($CA['is_donated'] == 'Y'):

                                //Start Create Coupons for donate product
                                for($i=1; $i <= $CA['quantity']*$sponsored_coupon; $i++){

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
                                    // $quickcoupons["product_id"]      =   (int)$ORparam['product_id'];
                                $quickcoupons1["coupon_sold_number"] =  (int)$totalsoldqty;
                                $quickcoupons1["creation_ip"]       =   $this->input->ip_address();
                                $quickcoupons1["updated_at"]        =   date('Y-m-d H:i');

                                //Saving quick coupons number  
                                // echo $totalsoldqty.'<br>';
                                $this->geneal_model->editData('da_quickcoupons_totallist',$quickcoupons1,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);
                                //End

                                $couponData['coupon_id']        =   (int)$this->geneal_model->getNextSequence('da_coupons');
                                $couponData['users_id']         =   (int)$ORparam["user_id"];
                                $couponData['users_email']      =   $ORparam["user_email"];
                                $couponData['order_id']         =   $ORparam["order_id"];
                                $couponData['product_id']       =   $CA['product_id'];
                                $couponData['product_title']    =   $CA['product_name'];
                                $couponData['is_donated']       =   'Y';
                                $couponData['coupon_status']    =   'Live';
                                $couponData['coupon_code']      =   $coupon_code;
                                $couponData["device_type"]      =   $this->input->get('device_type');
                                $couponData["app_version"]      =   $this->input->get('app_version');
                                $couponData['draw_date']        =   $ProductData['draw_date'];
                                $couponData['draw_time']        =   $ProductData['draw_time'];
                                $couponData['coupon_type']      =   'Donated';
                                $couponData['created_at']       =   date('Y-m-d H:i');

                                array_push($couponDetails,$couponData);

                                $this->geneal_model->addData('da_coupons',$couponData);
                                }//End Create Coupons

                            endif;

                        endif;

                            

                        //End Create Coupons

                        $data['finalPrice']             =   $ORparam["inclusice_of_vat"];
                        $data['stripe_token']           =   $ORparam["stripe_token"];

                        $wcon['where']                  =   array('order_id' => $ORparam["order_id"]);
                        $data['couponDetails']          =   $this->geneal_model->getData2('multiple', 'da_coupons', $wcon);

                        $where          =   [ 'users_id' => (int)$ORparam["user_id"] ];
                        $tblName        =   'da_users';
                        $userDetails    =   $this->geneal_model->getOnlyOneData($tblName, $where);
                        
                        $membershipData = $this->geneal_model->getMembership((int)$userDetails['totalArabianPoints']);
                        if($membershipData):
                            $cashback           =   $data['finalPrice'] * $membershipData['benifit'] /100;
                            $data['cashback']   =   $cashback;
                            if($cashback):
                                $insertCashback = array(
                                    'cashback_id'   =>  (int)$this->geneal_model->getNextSequence('da_cashback'),
                                    'user_id'       =>  (int)$ORparam["user_id"],
                                    'order_id'      =>  $ORparam["order_id"],
                                    'cashback'      =>  (float)$cashback,
                                    'created_at'    =>  date('Y-m-d H:i'),
                                );
                                $this->geneal_model->addData('da_cashback',$insertCashback);

                                $where2                     =   ['users_id' => (int)$ORparam["user_id"] ];
                                $UserData                   =   $this->geneal_model->getOnlyOneData('da_users', $where2);

                                /* Load Balance Table -- after buy product*/
                                $Cashbparam["load_balance_id"]      =   (int)$this->geneal_model->getNextSequence('da_loadBalance');
                                $Cashbparam["user_id_cred"]         =   (int)$ORparam["user_id"];
                                $Cashbparam["user_id_deb"]          =   (int)$ORparam["user_id"];
                                $Cashbparam["order_id"]             =   $ORparam["order_id"];
                                $Cashbparam["availableArabianPoints"]   =   (float)$userDetails["availableArabianPoints"];
                                $Cashbparam["end_balance"]              =   (float)$userDetails["availableArabianPoints"] + (float)$cashback ;
                                $Cashbparam["arabian_points"]       =   (float)$cashback;
                                $Cashbparam["record_type"]          =   'Credit';
                                $Cashbparam["arabian_points_from"]  =   'Membership Cashback';
                                $Cashbparam["creation_ip"]          =   $this->input->ip_address();
                                $Cashbparam["created_at"]           =   date('Y-m-d H:i');
                                $Cashbparam["created_by"]           =   $ORparam["user_type"];
                                $Cashbparam["status"]               =   "A";

                                $this->geneal_model->addData('da_loadBalance', $Cashbparam);

                                // Credit the purchesed points and get available arabian points of user.
                                $this->geneal_model->creaditPoints($cashback);
                                /* End */
                            endif;
                        endif;

                        //Add Referral Point
                        $SPwhereCon['where']        =   array('BUY_USER_ID' => (int)$ORparam["user_id"], 'SHARED_PRODUCT_ID' => (int)$CA['product_id']);
                        $shared_details             =   $this->geneal_model->getData2('single','da_deep_link',$SPwhereCon);
                        
                        if($shared_details['SHARED_USER_ID'] && $shared_details['SHARED_USER_REFERRAL_CODE'] && $shared_details['SHARED_PRODUCT_ID']):
                            if(isset($productIdPrice[$shared_details['SHARED_PRODUCT_ID']])):

                                $prowhere['where']  =   array('products_id'=>(int)$shared_details['SHARED_PRODUCT_ID']);
                                $prodData           =   $this->geneal_model->getData2('single','da_products',$prowhere);
                                
                                $sharewhere['where']=   array('users_id'=>(int)$shared_details['SHARED_USER_ID'],'products_id'=>(int)$shared_details['SHARED_PRODUCT_ID']);
                                $shareCount         =   $this->geneal_model->getData2('count','da_product_share',$sharewhere);

                                if($shareCount == NULL):
                                    $shareCount = 0;
                                endif;

                                if(isset($prodData['share_limit']) && $shareCount < $prodData['share_limit']):

                                    $param['share_id']                  =   (int)$this->common_model->getNextSequence('da_product_share');
                                    $param['users_id']                  =   (int)$shared_details['SHARED_USER_ID'];
                                    $param['products_id']               =   (int)$shared_details['SHARED_PRODUCT_ID'];
                                    $param['creation_date']             =   date('Y-m-d H:i');
                                    $param['creation_ip']               =   $this->input->ip_address();
                                    $this->common_model->addData('da_product_share',$param);

                                    $productCartAmount          =   $productIdPrice[$shared_details['SHARED_PRODUCT_ID']];
                                    //First label referal amount Credit
                                    // $ref1tbl                     =   'referral_percentage';
                                    // $ref1where                   =   ['referral_lebel' => (int)1 ];
                                    // $referal1Data                =   $this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
                                    $ref1tbl                    =   'da_products';
                                    $ref1where                  =   ['products_id' => (int)$shared_details['SHARED_PRODUCT_ID'] ];
                                    $referal1Data               =   $this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
                                    if($referal1Data && $referal1Data['share_percentage_first'] > 0):
                                        $referal1Amount         =   (($productCartAmount*$referal1Data['share_percentage_first'])/100);

                                        /* Referal Product Table -- after buy product*/
                                        $ref1Amtparam["referral_id"]            =   (int)$this->geneal_model->getNextSequence('referral_product');
                                        $ref1Amtparam["referral_user_code"]     =   (int)$shared_details['SHARED_USER_REFERRAL_CODE'];
                                        $ref1Amtparam["referral_from_id"]       =   (int)$shared_details['SHARED_USER_ID'];
                                        $ref1Amtparam["referral_to_id"]         =   (int)$ORparam["user_id"];
                                        $ref1Amtparam["referral_percent"]       =   (float)$referal1Data['share_percentage_first'];
                                        $ref1Amtparam["referral_amount"]        =   (float)$referal1Amount;
                                        $ref1Amtparam["referral_cart_amount"]   =   (float)$productCartAmount;
                                        $ref1Amtparam["referral_product_id"]    =   (int)$shared_details['SHARED_PRODUCT_ID'];
                                        $ref1Amtparam["creation_ip"]            =   $this->input->ip_address();
                                        $ref1Amtparam["created_at"]             =   date('Y-m-d H:i');
                                        $ref1Amtparam["created_by"]             =   (int)$ORparam["user_id"];
                                        $ref1Amtparam["status"]                 =   "A";
                                        
                                        array_push($ref1AmtDetails,$ref1Amtparam);


                                        $this->geneal_model->addData('referral_product', $ref1Amtparam);
                                        /* End */

                                        /* Load Balance Table -- after buy product*/
                                        $ref1param["load_balance_id"]       =   (int)$this->geneal_model->getNextSequence('da_loadBalance');
                                        $ref1param["user_id_cred"]          =   (int)$shared_details['SHARED_USER_ID'];
                                        $ref1param["user_id_deb"]           =   (int)0;
                                        $ref1param["product_id"]            =   (int)$ref1Amtparam["referral_product_id"];
                                        $ref1param["arabian_points"]        =   (float)$referal1Amount;
                                        $ref1param["record_type"]           =   'Credit';
                                        $ref1param["arabian_points_from"]   =   'Referral';
                                        $ref1param["creation_ip"]           =   $this->input->ip_address();
                                        $ref1param["created_at"]            =   date('Y-m-d H:i');
                                        $ref1param["created_by"]            =   (int)$ORparam["user_id"];
                                        $ref1param["status"]                =   "A";
                                        
                                        $this->geneal_model->addData('da_loadBalance', $ref1param);

                                        $where25['where']               =   [ 'users_id' => (int)$shared_details['SHARED_USER_ID'] ];
                                        $sharedUserdata                 =   $this->geneal_model->getData2('single','da_users', $where25);
                                        $this->geneal_model->addData('da_test', $sharedUserdata);

                                        $userWhecrCon['where']      =   array('users_id' => (int)$sharedUserdata['users_id']);
                                        $totalArabianPoints         =   $sharedUserdata['totalArabianPoints'] + $ref1param["arabian_points"];
                                        $availableArabianPoints     =   $sharedUserdata['availableArabianPoints'] + $ref1param["arabian_points"];
                                        
                                        $updateArabianPoints['totalArabianPoints']      =   (float)$totalArabianPoints;
                                        $updateArabianPoints['availableArabianPoints']  =   (float)$availableArabianPoints;

                                        $this->geneal_model->editDataByMultipleCondition('da_users',$updateArabianPoints,$userWhecrCon['where']);
                                        /* End */
                                    endif;
                                    
                                endif;
                            endif;
                            $this->geneal_model->deleteData('da_deep_link', 'seq_id', (int)$shared_details['seq_id']);
                        endif;
                        //END

                    endforeach;

                    //Delete cart items.
                    $this->geneal_model->deleteData('da_cartItems', 'user_id',(int)$this->input->get('users_id'));

                    $result['orderData']              = $data['orderData'];
                    $result['finalPrice']             = $orderData[0]['inclusice_of_vat'];
                    $result['couponDetails']          = $couponDetails;
                    $result['cashback']               = $cashback;
                    $result['orderDetails']           = $currentOrderDetails;         

                    $results['successData'] = $result;
                   
                    $this->emailsendgrid_model->sendOrderMailToUser($this->input->get('order_id'));
                    $this->sms_model->sendTicketDetails($this->input->get('order_id'));

                    echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_SUCCESS'),$results);

                else:
                    echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
                endif;
            endif;
            else:
                echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
            endif;
    }

    /***********************************************************************
    ** Function name    : telrOrderFailed
    ** Developed By     : Dilip Halder
    ** Purpose          : This function used for generate order ID
    ** Date             : 24 APRIL 2023
    ** Updated By       :
    ** Updated Date     : 
    ************************************************************************/  
    public function telrOrderFailed()
    {
        $apiHeaderData      =   getApiHeaderData();
        $this->generatelogs->putLog('APP',logOutPut($_POST));
        $result                             =   array();    
        if(requestAuthenticate(APIKEY,'GET')):
            
            if($this->input->get('users_id') == ''): 
                echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
            elseif($this->input->get('order_id') == ''): 
                echo outPut(0,lang('SUCCESS_CODE'),lang('ORDER_ID_EMPTY'),$result);
            else:

                $where          =   [ 'users_id' => (int)$this->input->get('users_id') ];
                $tblName        =   'da_users';
                $userDetails    =   $this->geneal_model->getOnlyOneData($tblName, $where);

                $where2         =   [ 'user_id' => (int)$this->input->get('users_id') , 'order_id' => $this->input->get('order_id') ];
                $tblName        =   'da_orders';
                $ORparam   =   $this->geneal_model->getOnlyOneData($tblName, $where2);

                if(!empty($ORparam)  && !empty($userDetails)):
                    
                    $data['user_id']                =   $ORparam['user_id'];
                    $data['user_email']             =   $ORparam['user_email'];
                    $data['inclusice_of_vat']       =   $ORparam['inclusice_of_vat'];
                    $data['stripe_token']           =   $ORparam['stripe_token'];
                    $data['user_type']              =   $ORparam['user_type'];

                    $data['order_id']               =   $ORparam['order_id'];
                    $data['stripeToken']            =   $ORparam['stripeToken'];
                    $data['customerId']             =   $ORparam['customerId'];
                    $data['captureAmount']          =   $ORparam['captureAmount'];
                    $data['stripeChargeId']         =   $ORparam['stripeChargeId'];

                    //Get current order of user.
                    $wcon['where']                  =   [ 'order_id' => $ORparam["order_id"] ];
                    $data['orderData']              =   $this->geneal_model->getData2('single', 'da_orders', $wcon);
                    
                    //Get current order details of user.
                    $wcon2['where']                 =   [ 'order_id' => $ORparam["order_id"] ];
                    //$data['orderDetails']             =   $this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);         //Old code 21-09-2022
                    $OrderorderDetails              =   $this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);             //New code 21-09-2022
                   
                    //update order status
                    if($data['orderData']["product_is_donate"] == 'Y'){                                                                             
                        $updateParams                   =   array( 'order_status' => 'Failed');  
                    }else{
                        $expairyData                    =   date('Y-m-d H:i', strtotime($data['orderData']['created_at']. ' 14 Days'));
                        $updateParams                   =   array( 'order_status' => 'Failed');  
                    }
                    $this->geneal_model->addData('da_test',$updateParams);
                    //$updateStatus                     =   [ 'order_status' => 'Success' ];
                    $updateorderstatus              =   $this->geneal_model->editData('da_orders', $updateParams, 'order_id', $ORparam["order_id"]);
                   
                    $result['orderDetails']           =   $updateorderstatus;                                                               //New code 21-09-2022

                    echo outPut(0,lang('SUCCESS_CODE'),lang('ORDER_FAILED'),$result);

                else:
                    echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
                endif;
            endif;
            else:
                echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
            endif;
    }


      /***********************************************************************
    ** Function name    : enablepayment
    ** Developed By     : Dilip Halder
    ** Purpose          : This function used for Enable payment
    ** Date             : 26 APRIL 2023
    ** Updated By       :
    ** Updated Date     : 
    ************************************************************************/  
    public function enablepayment()
    {
        $apiHeaderData      =   getApiHeaderData();
        $this->generatelogs->putLog('APP',logOutPut($_POST));
        $result                             =   array();    
        if(requestAuthenticate(APIKEY,'GET')):
            
            // if($this->input->get('users_id') == ''): 
            //     echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
            // else:

                $where          =   [ 'users_id' => (int)$this->input->get('users_id') ];
                $tblName        =   'da_users';
                $userDetails    =   $this->geneal_model->getOnlyOneData($tblName, $where);

                if(!empty($userDetails)):
                    
                    $tblName        =   'da_paymentmode';
                    $PaymentMode    =   $this->geneal_model->getData2('single',$tblName);

                    $result          =   $PaymentMode;                                                               //New code 21-09-2022

                    echo outPut(1,lang('SUCCESS_CODE'),lang('PAYMENTMODE'),$result);

                else:
                    // echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);

                    $tblName        =   'da_paymentmode';
                    $PaymentMode    =   $this->geneal_model->getData2('single',$tblName);

                    $result          =   $PaymentMode;                                                               //New code 21-09-2022

                    echo outPut(1,lang('SUCCESS_CODE'),lang('PAYMENTMODE'),$result);

                endif;
            // endif;
            else:
                echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
            endif;
    }


    /***********************************************************************
    ** Function name    : selectaddress
    ** Developed By     : Dilip Halder
    ** Purpose          : This function used select random address
    ** Date             : 24 June 2023
    ** Updated By       :
    ** Updated Date     : 
    ************************************************************************/  
    public function selectaddress()
    {
        $apiHeaderData      =   getApiHeaderData();
        $this->generatelogs->putLog('APP',logOutPut($_POST));
        $result                             =   array();    
        if(requestAuthenticate(APIKEY,'GET')):
            
            // if($this->input->get('users_id') == ''): 
            //     echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
            // else:

                $where          =   [ 'users_id' => (int)$this->input->get('users_id') ];
                $tblName        =   'da_users';
                $userDetails    =   $this->geneal_model->getOnlyOneData($tblName, $where);

                if(!empty($userDetails)):
                    
                    $selectaddress = $this->randomAddress();

                    $result          =   $selectaddress;                                                               //New code 21-09-2022

                    echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$result);

                else:
                    // echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);

                    $selectaddress = $this->randomAddress();

                    $result          =   $selectaddress;                                                               //New code 21-09-2022

                    echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$result);


                endif;
            // endif;
            else:
                echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
            endif;
    }


    public function randomAddress()
    {
           $addressList =array("Marina Street, Dubai Marina, Dubai, UAE, P.O. Box: 12345",
                            "Palm Street, Palm Jumeirah, Dubai, UAE, P.O. Box: 12346",
                            "Sheikh Zayed Road, Downtown, Dubai, UAE, P.O. Box: 12347",
                            "Corniche Street, Abu Dhabi, UAE, P.O. Box: 12348",
                            "Beach Street, Jumeirah, Dubai, UAE, P.O. Box: 12349",
                            "Oasis Street, Silicon Oasis, Dubai, UAE, P.O. Box: 12350",
                            "City Street, Motor City, Dubai, UAE, P.O. Box: 12351",
                            "Park Street, Zabeel, Dubai, UAE, P.O. Box: 12352",
                            "Island Street, Yas Island, Abu Dhabi, UAE, P.O. Box: 12353",
                            "Garden Street, Al Barsha, Dubai, UAE, P.O. Box: 12354",
                            "Marina View, Al Satwa, Dubai, UAE, P.O. Box: 12355",
                            "Beach Lane, JBR, Dubai, UAE, P.O. Box: 12356",
                            "City Walk, Al Safa, Dubai, UAE, P.O. Box: 12357",
                            "Park Lane, Mushrif, Abu Dhabi, UAE, P.O. Box: 12358",
                            "Desert Drive, Al Ain, UAE, P.O. Box: 12359",
                            "Palm Jumeirah Way, Dubai, UAE, P.O. Box: 12360",
                            "Creek View, Deira, Dubai, UAE, P.O. Box: 12361",
                            "Island View, Reem Island, Abu Dhabi, UAE, P.O. Box: 12362",
                            "Oasis Road, Liwa Oasis, Abu Dhabi, UAE, P.O. Box: 12363",
                            "Tower Street, Bur Dubai, Dubai, UAE, P.O. Box: 12364",
                            "Garden Street, Al Warqaa, Dubai, UAE, P.O. Box: 12375",
                            "Desert Drive, Al Madam, Sharjah, UAE, P.O. Box: 12376",
                            "Marina Way, Al Hamra, Ras Al Khaimah, UAE, P.O. Box: 12377",
                            "Creek View, Al Garhoud, Dubai, UAE, P.O. Box: 12378",
                            "Beach Road, Kite Beach, Dubai, UAE, P.O. Box: 12379",
                            "Oasis Lane, Al Awir, Dubai, UAE, P.O. Box: 12380",
                            "Tower Street, Al Nahyan, Abu Dhabi, UAE, P.O. Box: 12381",
                            "Palm Boulevard, Mina Al Arab, Ras Al Khaimah, UAE, P.O. Box: 12382",
                            "Marina Drive, Yas Marina, Abu Dhabi, UAE, P.O. Box: 12383",
                            "Island Street, The World Islands, Dubai, UAE, P.O. Box: 12384",
                            "Palm Street, Al Khan, Sharjah, UAE, P.O. Box: 12385",
                            "Marina Way, Al Maryah Island, Abu Dhabi, UAE, P.O. Box: 12386",
                            "Desert Drive, Sweihan, Abu Dhabi, UAE, P.O. Box: 12387",
                            "Beach Lane, Al Bateen, Abu Dhabi, UAE, P.O. Box: 12388",
                            "City Walk, Al Raha Beach, Abu Dhabi, UAE, P.O. Box: 12389",
                            "Creek View, Baniyas, Abu Dhabi, UAE, P.O. Box: 12390",
                            "Island Street, Al Ghadeer, Abu Dhabi, UAE, P.O. Box: 12391",
                            "Park Street, Mushrif Park, Dubai, UAE, P.O. Box: 12392",
                            "Oasis Road, Remah, Abu Dhabi, UAE, P.O. Box: 12393",
                            "Tower Lane, Al Quoz, Dubai, UAE, P.O. Box: 12394",
                            "Marina View, Al Qasba, Sharjah, UAE, P.O. Box: 12395",
                            "Desert Drive, Madinat Zayed, Abu Dhabi, UAE, P.O. Box: 12396",
                            "Beach Lane, Mamzar, Dubai, UAE, P.O. Box: 12397",
                            "City Street, Al Mizhar, Dubai, UAE, P.O. Box: 12398",
                            "Oasis Boulevard, Al Rashidiya, Dubai, UAE, P.O. Box: 12399",
                            "Palm Jumeirah Way, Al Sufouh, Dubai, UAE, P.O. Box: 12400",
                            "Creek View, Deira City Centre, Dubai, UAE, P.O. Box: 12401",
                            "Island Street, Al Mamzar, Dubai, UAE, P.O. Box: 12402",
                            "Park Lane, Al Muteena, Dubai, UAE, P.O. Box: 12403",
                            "Tower Street, Al Shindagha, Dubai, UAE, P.O. Box: 12404",
                            "Marina Promenade, Al Majaz, Sharjah, UAE, P.O. Box: 12405",
                            "Island Drive, Al Khan Lagoon, Sharjah, UAE, P.O. Box: 12406",
                            "Creek Lane, Al Qasimia, Sharjah, UAE, P.O. Box: 12407",
                            "Tower Street, Al Taawun, Sharjah, UAE, P.O. Box: 12408",
                            "Oasis Boulevard, Al Nahda, Sharjah, UAE, P.O. Box: 12409",
                            "Palm Jumeirah Way, Al Nahda, Dubai, UAE, P.O. Box: 12410",
                            "Creek View, Al Qusais, Dubai, UAE, P.O. Box: 12411",
                            "Island Street, Umm Suqeim, Dubai, UAE, P.O. Box: 12412",
                            "Park Avenue, Mirdif, Dubai, UAE, P.O. Box: 12413",
                            "Marina Drive, Jebel Ali Free Zone, Dubai, UAE, P.O. Box: 12414",
                            "Desert Lane, Al Barsha South, Dubai, UAE, P.O. Box: 12415",
                            "Marina Street, Al Furjan, Dubai, UAE, P.O. Box: 12416",
                            "Palm Boulevard, Al Khawaneej, Dubai, UAE, P.O. Box: 12417",
                            "Oasis Way, Al Jafiliya, Dubai, UAE, P.O. Box: 12418",
                            "Beach Road, Umm Ramool, Dubai, UAE, P.O. Box: 12419",
                            "City Walk, Al Safa, Dubai, UAE, P.O. Box: 12420",
                            "Tower Street, Al Manara, Dubai, UAE, P.O. Box: 12421",
                            "Park Avenue, Al Wasl, Dubai, UAE, P.O. Box: 12422",
                            "Island Drive, Al Satwa, Dubai, UAE, P.O. Box: 12423",
                            "Creek View, Al Hudaiba, Dubai, UAE, P.O. Box: 12424",
                            "Marina Promenade, Al Bada, Dubai, UAE, P.O. Box: 12425",
                            "Desert Lane, Al Quoz Industrial Area, Dubai, UAE, P.O. Box: 12426",
                            "Palm Street, Al Qusais Industrial Area, Dubai, UAE, P.O. Box: 12427",
                            "Oasis Way, Al Rashidiya, Dubai, UAE, P.O. Box: 12428",
                            "Beach Road, Al Warqa'a, Dubai, UAE, P.O. Box: 12429",
                            "City Walk, Muhaisnah, Dubai, UAE, P.O. Box: 12430",
                            "Tower Street, Nad Al Hamar, Dubai, UAE, P.O. Box: 12431",
                            "Park Avenue, Al Twar, Dubai, UAE, P.O. Box: 12432",
                            "Island Drive, Al Mizhar, Dubai, UAE, P.O. Box: 12433",
                            "Creek View, Al Nahda, Dubai, UAE, P.O. Box: 12434",
                            "Marina Drive, Al Karama, Dubai, UAE, P.O. Box: 12435",
                            "Oasis Lane, Al Rigga, Dubai, UAE, P.O. Box: 12436",
                            "Island Street, Al Muraqqabat, Dubai, UAE, P.O. Box: 12437",
                            "Beach Road, Al Garhoud, Dubai, UAE, P.O. Box: 12438",
                            "City Walk, Al Muteena, Dubai, UAE, P.O. Box: 12439",
                            "Tower Lane, Al Hamriya, Dubai, UAE, P.O. Box: 12440",
                            "Creek View, Al Dhagaya, Dubai, UAE, P.O. Box: 12441",
                            "Palm Street, Al Baraha, Dubai, UAE, P.O. Box: 12442",
                            "Marina Avenue, Al Murar, Dubai, UAE, P.O. Box: 12443",
                            "Desert Drive, Al Sabkha, Dubai, UAE, P.O. Box: 12444",
                            "Oasis Road, Al Raffa, Dubai, UAE, P.O. Box: 12445",
                            "Palm Jumeirah Way, Al Buteen, Dubai, UAE, P.O. Box: 12446",
                            "Island Boulevard, Naif, Dubai, UAE, P.O. Box: 12447",
                            "Beach Street, Port Saeed, Dubai, UAE, P.O. Box: 12448",
                            "City Avenue, Al Mamzar, Dubai, UAE, P.O. Box: 12449",
                            "Tower Street, Hor Al Anz, Dubai, UAE, P.O. Box: 12450",
                            "Creek Lane, Al Waheda, Dubai, UAE, P.O. Box: 12451",
                            "Marina Drive, Al Qadisia, Dubai, UAE, P.O. Box: 12452",
                            "Desert Way, Al Tawar, Dubai, UAE, P.O. Box: 12453",
                            "Beach Road, Al Nahda, Dubai, UAE, P.O. Box: 12454",
                            "City Walk, Al Qusais, Dubai, UAE, P.O. Box: 12455",
                            "Tower Street, Al Nahda, Sharjah, UAE, P.O. Box: 12456",
                            "Creek Avenue, Al Khan, Sharjah, UAE, P.O. Box: 12457",
                            "Marina Street, Al Majaz, Sharjah, UAE, P.O. Box: 12458",
                            "Oasis Boulevard, Al Nahda, Sharjah, UAE, P.O. Box: 12459",
                            "Palm Way, Al Qasimia, Sharjah, UAE, P.O. Box: 12460",
                            "Island Drive, Al Taawun, Sharjah, UAE, P.O. Box: 12461",
                            "Beach Lane, Al Nekhailat, Sharjah, UAE, P.O. Box: 12462",
                            "City Avenue, Al Fisht, Sharjah, UAE, P.O. Box: 12463",
                            "Tower Boulevard, Al Majaz, Sharjah, UAE, P.O. Box: 12464");
                            
                            $randomIndex = array_rand($addressList);
                            $randomAddress = $addressList[$randomIndex];

                            $address1 = explode(',' , $randomAddress);


                            $address['line1'] =  $address1[0].','.$address1[1].','.$address1[4];
                            $address['city']  =  $address1[2];

                            if($address['country'] == "UAE"):
                                $address['country']  =  $address1[3];
                            else:
                                $address['country']  =  'UAE';
                            endif;

                            return $address;
    }

    
}