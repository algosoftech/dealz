<?php //echo"workig"; ?>
<!Doctype html>
<html lang="eng">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<?php include('common/head.php') ?>

<style>
.error{
		color:red;
	}	
.coupon .about-coupen div {
	margin: 0 15px;
	padding: 12px 0px;
}
.my-profile .btn:hover{
background: #e72d2e;
    color: #ffffff;
}
.my-profile .btn {
    background: #ffffff;
    border: 1px solid #e72d2e;
    padding: 8px 13px;
    border-radius: 8px;
    color: #e72d2e;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    margin-top: 0px;
}
.pagination {
    margin: 0;
    padding: 0;
    text-align: center;
    margin-bottom: 18px !important;
    margin-top: 12px;
    margin-left: 10px !important;
}
.add_user {
    background-color: #d12a2b;
    border: none;
    padding: 8px 16px;
    border-radius: 12px;
    color: white;
}
.pagination .active {
    display: inline;
    background-color: #eeeeee9e !important;
    border-radius: 7px;
    margin-right: 10px !important;
}
.add_user:hiver {
    background-color: #d12a2b;
    border: none;
    padding: 8px 16px;
    border-radius: 12px;
    color: white;
}
.pagination li a {
    display: inline-block;
    text-decoration: none;
    padding: 5px 10px;
    color: #000;
}
.pagination {
    margin: 0;
    padding: 0;
    text-align: center;
    margin-bottom: 18px !important;
    margin-top: 12px;
}
.pagination li span {
    display: inline-block;
    text-decoration: none;
    padding: 5px 10px;
    color: #000;
}
.pagination .active {
    display: inline;
    background-color: #eeeeee9e !important;
    border-radius: 7px;
}
    tr{
    border: 1px solid #ddd;
}
table td {
    border: 1px solid #eee;
    border-top: 0;
    font-weight: 400;
    text-align:center;
    padding: 0.75rem;
    vertical-align: top;
    color:#00000087;
}

 table th {
    padding: 0.75rem;
    vertical-align: top;
    border: 1px solid #dee2e6;
    text-align: center;
    font-weight: 500;
}
table {
    border-collapse: collapse;
}
.user_list{
   padding-top: 19px; 
}
.ordered_list {
    display: flex;
    justify-content: space-around;
    margin: 0px;
    padding: 0px;
}
.ordered_list li{
 padding:0px 5px;   
}
.ordered_list a{
 color: #d12a2b;  
}
.coupon .coupen-footer {
    background: #b12021;
    color: #fff;
    padding: 4px;
}
.coupon .about-coupen p span {
    color: #6c757d;
    font-weight: 400;
}
.coupen_list {
    padding-top: 15px;
}


.green {
    background: #0d9e51;
    padding: 6px 10px;
    border-radius: 5px;
    font-size: 12px;
    color: #ffffff !important;
    font-weight: 900 !important;
}
.red{
  background: #c9261f;
  padding: 6px 10px;
  border-radius: 5px;
  font-size: 12px;
  color: #ffffff !important;
  font-weight: 900 !important;
}

.deopdown {
    width: 67%;
    border: 2px solid #f1f1f1;
    border-radius: 4px;
    margin: -6px 0;
    outline: none;
    padding: 5px 3px 5px;
    box-sizing: border-box;
    transition: 0.3s;
}

</style>
</head>


<body>
<?php include('common/header.php') ?>
<div class="my-profile">
	<div class="container">
		<div class="row">
			<?php include ('common/profile/menu.php') ?>
			<div class="col-md-9">
				<?php include ('common/profile/head.php') ?>
				<div class="row">
				    <div class="col-md-12 coupen_list">
				    	
				    	<form class="form-inline" id="rechargeForm" method="post">
							<div class="col-md-4 px-0"> 
							 	<input type="date" name="start_date" id="start_date" class="form-control"  autocomplete="off" value="<?=set_value('start_date');?>">
							</div>
							&nbsp;
							<div class="col-md-4 px-0">
								<input type="date" name="end_date" id="end_date" class="form-control"  autocomplete="off" value="<?=set_value('end_date');?>">
							</div>
							&nbsp;
							<div class="col-md-2 px-0"> 
								<select class="deopdown" name="filters" id="filters">
									<option value="all" <?php if($filters == 'all'): echo 'selected'; endif; ?> >All</option>
									<option value="sent" <?php if($filters == 'sent'): echo 'selected'; endif; ?> >Sent</option>
									<option value="received" <?php if($filters == 'received'): echo 'selected'; endif; ?> >Received</option>
									<option value="order" <?php if($filters == 'order'): echo 'selected'; endif; ?> >Order</option>
								</select>
							</div>
							&nbsp;

							<div class="col-md-1 px-0"> 
								<input type="submit" class="btn show_record" style="padding: 6px 20px!important;" value="Show">
							</div>
						</form>		
						


				    	<!-- wallet statement start -->
							<div class="user_list" style="overflow-x: auto;">
							  	<table>
							    <tr>
	                              <th style="text-align:center;">S.No</th>
	                              <th style="text-align:center;">Record Type</th>
	                              <th style="text-align:center;">Narration</th>
	                              <th style="text-align:center;">created</th>
	                              <th style="text-align:center;">Opening Balance</th>
	                              <th style="text-align:center;">Closing Balance</th>
	                              <th style="text-align:center;">Credit</th>
	                              <th style="text-align:center;">Debit</th>
	                              <th style="text-align:center;">Status</th>
	                            </tr>

						     	<?php if($Walletstatement): ?>
	                            <?php $i=1; foreach ($Walletstatement as $key => $items):?>
	                          
	                            <!-- coupon statement end -->
	                            <?php if($items['statement_type'] == 'coupon'): ?>
	                              <tr class="coupon-section">
	                                  <td width="10%"><?=$i?></td>
	                                  <td width="100%">Coupon</td>
	                                  <td width="100%">Coupon Code : <?=$items['coupon_code']; ?> </td>
	                                  <td width="100%"><?=date('d M, Y h:i A', strtotime($items['created_at'])); ?></td>
	                                  <td width="10%"><?=$items['availableArabianPoints'];?></td>
	                                  <td width="10%"><?=$items['end_balance'];?></td>
	                                  <td width="10%"><?=$items['coupon_code_amount'];?></td>
	                                  <td width="10%">--</td>
	                                  <td width="10%"> <span class="green">  Credit </span> </td>
	                              </tr>
	                            <?php endif;?>
	                            <!-- coupon statement end -->

	                            <!-- cashback statement start -->
	                            <?php if($items['statement_type'] == 'cashback' ): ?>
	                              <tr class="cashback-section">
	                                  <td width="10%"><?=$i?></td>
	                                  <td width="100%">Cashback</td>
	                                  <td width="100%"> Order ID - <?=$items['order_id'];?> </td>
	                                  <td width="100%"><?=date('d M, Y h:i A', strtotime($items['created_at'])); ?></td>
	                                  <td width="10%"><?=$items['availableArabianPoints'];?></td>
	                                  <td width="10%"><?=$items['end_balance'];?></td>
	                                  <td width="10%"><?=$items['cashback_amount'];?></td>
	                                  <td width="10%">--</td>
	                                  <td width="10%"> <span class="green">  Credit </span> </td>
	                              </tr>
	                            <?php endif; ?>
	                            <!-- cashback statement end -->

	                            <!-- Purchase statement start -->
	                            <?php if($items['statement_type'] == 'purchase' ): ?>
	                              <tr class="purchase-section">
	                                  <td width="10%"><?=$i?></td>
	                                  <td width="100%">Purchase</td>
	                                  <td width="100%"> 
	                                    Order ID - <?=$items['order_id'].'<br>';?> 
	                                    <!-- Fetching and calculating all data in loop. -->
	                                    Product Name :
	                                                    <?php foreach ($items['product_details'] as $key => $item_details): ?>
	                                                         <?= '<br>'.$item_details['product_name'].' X '.$item_details['quantity'].'<br>'; ?>
	                                                    <?php endforeach; ?>
	                                  </td>

	                                  <td width="100%"><?=date('d M, Y h:i A', strtotime($items['created_at'])); ?></td>
	                                  <td width="10%"><?=$items['availableArabianPoints'];?></td>
	                                  <td width="10%"><?=$items['end_balance'];?></td>

	                                  <td width="10%">--</td>

	                                  <td width="10%">
	                                    <?php $total_price =0;  foreach ($items['product_details'] as $key => $item_details): ?>
	                                                      <?php $total_price = $total_price+ $item_details['price']*$item_details['quantity'];  ?>
	                                                    <?php endforeach; ?>
	                                                    <?=$total_price;?>
	                                  </td>
	                                  <td width="10%"> <span class="red">  Debit </span> </td>
	                                </tr>
	                            <?php endif; ?>
	                            <!-- Purchase statement end -->

	                            <!-- Quick buy statement start -->
	                            <?php if($items['statement_type'] == 'quick_buy' ): ?>
	                              <tr class="quickbuy-section">
	                                  <td width="10%"><?=$i?></td>
	                                  <td width="100%">Quick Buy</td>
	                                  <td width="100%"> 
	                                    
	                                    Order ID : <?=$items['order_id'].'<br>';?>  
	                                    Name : <?=$items['users_name'].' '.$items['last_name'] ; ?></br>
	                                    Email: <?=$items['user_email'] ? $items['user_email'] : '--'; ?></br>
	                                    Mobile: <?=$items['user_phone']; ?></br>

	                                    <!-- Fetching and calculating all data in loop. -->
	                                    <?php if($items['product_name']):  ?>
	                                         Product Name : <?= '<br>'.$items['product_name'].' X '.$item_details['quantity'].'<br>'; ?>
	                                    <?php endif; ?>

	                                  </td>

	                                  <td width="100%"><?=date('d M, Y h:i A', strtotime($items['created_at'])); ?></td>

	                                  <?php if($users_type == 'Users'): ?>
                                        <td width="10%">--</td>
                                        <td width="10%">--</td>
                                      <?php else: ?>
                                        <td width="10%"><?=$items['availableArabianPoints'];?></td>
                                        <td width="10%"><?=$items['end_balance'];?></td>  
                                      <?php endif; ?>

	                                  <td width="10%">--</td>

	                                  <td width="10%">
	                                    <?=$items['price']; ?>
	                                  </td>
	                                  <td width="10%"> <span class="red">  Debit </span> </td>
	                                </tr>
	                            <?php endif; ?>
	                            <!-- Purchase statement end -->

	                            <!-- Quick Vocher statement start -->
	                            <?php if($items['statement_type'] == 'quick_vouchers' ): ?>
	                              <tr class="quickbuy-section">
	                                  <td width="10%"><?=$i?></td>
	                                  <td width="100%">Quick Vocher</td>
	                                  <td width="100%"> 
	                                    Order ID : <?=$items['order_id'].'<br>';?>  
	                                    Voucher Code : <?=$items['voucher_code'].'<br>';?>  
	                                  </td>

	                                  <td width="100%"><?=date('d M, Y h:i A', strtotime($items['created_at'])); ?></td>

	                                  <td width="10%"><?=$items['availableArabianPoints'];?></td>
	                                  <td width="10%"><?=$items['end_balance'];?></td>

	                                  <td width="10%">
	                                    <?=$items['price']; ?>
	                                  </td>
	                                  <td width="10%">--</td>

	                                  <td width="10%"> <span class="green">  Credit </span> </td>
	                                </tr>
	                            <?php endif; ?>
	                            <!-- Purchase statement end -->

	                            <!-- cashback statement start -->
	                            <?php if($items['statement_type'] == 'refund' ): ?>
	                              <tr class="refund-section">
	                                  <td width="10%"><?=$i?></td>
	                                  <td width="100%">
	                                    <?php
	                                        $where['where'] = array('order_id' => $items['order_id']);
	                                        $Order = $this->common_model->getData('count','da_orders', $where );
	                                    
	                                        if($Order == 1){
	                                          echo "Online Purchase (Refund)";
	                                        }else{
	                                          echo "Quick Buy  (Refund)";
	                                        }
	                                    ?>
	                                  </td>
	                                  <td width="100%"> Order ID : <?=$items['order_id'];?> </td>
	                                  <td width="100%"><?=date('d M, Y h:i A', strtotime($items['created_at'])); ?></td>
	                                  <td width="10%"><?=$items['availableArabianPoints'];?></td>
	                                  <td width="10%"><?=$items['end_balance'];?></td>
	                                  <td width="10%"><?=$items['cashback_amount'];?></td>
	                                  <td width="10%">--</td>
	                                  <td width="10%"> <span class="green">  Credit </span> </td>
	                                </tr>
	                            <?php endif; ?>
	                            <!-- cashback statement end -->

	                            <!-- Recharge statement start -->
	                            <?php if($items['statement_type'] == 'recharge' ): ?>
	                                <tr class="<?php if($items['record_type'] == 'Credit' ):?>  recharge-received-section  <?php else: ?> recharge-sent-section <?php endif;?> ">
	                                  <td width="10%"><?=$i?></td>
	                                  <td width="100%"> Recharge </td>
	                                  <td width="100%"> 
	                                    Recharge Amount : <?=$items['sum_arabian_points']?> </br>
	                                    Name : <?=$items['users_name'].' '.$items['last_name'] ; ?></br>
	                                                    Email: <?=$items['users_email']; ?></br>
	                                                    Mobile: <?=$items['users_mobile']; ?></br>
	                                  </td>
	                                  <td width="100%"><?=date('d M, Y h:i A', strtotime($items['created_at'])); ?></td>
	                                  <td width="10%"><?=$items['availableArabianPoints'];?></td>
	                                  <td width="10%"><?=$items['end_balance'];?></td>
	                                  <td width="10%">
	                                    <?php if($items['record_type'] == 'Credit' ):?>  
	                                                      <?=$items['sum_arabian_points'];?>
	                                                    <?php else: ?>
	                                                      --
	                                                    <?php endif;?>
	                                  </td>
	                                  <td width="10%">
	                                    <?php if($items['record_type'] == 'Debit' ):?>  
	                                                      <?=$items['sum_arabian_points'];?>
	                                                    <?php else: ?>
	                                                      --
	                                                    <?php endif;?>
	                                  </td>
	                                  <td width="10%"> 
	                                    <span class="<?php if($items['record_type'] == 'Credit' ):?> green <?php else: ?>  red <?php endif;?>"> <?=$items['record_type'];?> </span>
	                                  </td>
	                                </tr>
                              	<?php endif; ?>
	                            <!-- Recharge statement end -->

	                            <!-- Reverse Amount statement start -->
	                            <?php if($items['statement_type'] == 'reverse_amount' ): ?>
	                              	<tr class="<?php if($items['record_type'] == 'Credit' ):?>  recharge-received-section  <?php else: ?>  recharge-sent-section <?php endif;?> ">
	                                  <td width="10%"><?=$i?></td>
	                                  <td width="100%"> Reversed Amount </td>
	                                  <td width="100%"> 
	                                    Amount terminated. 
	                                  </td>
	                                  <td width="100%"><?=date('d M, Y h:i A', strtotime($items['created_at'])); ?></td>
	                                  <td width="10%"><?=$items['arabian_points'];?></td>
	                                  <td width="10%"><?=$items['end_balance'];?></td>
	                                  <td width="10%">
	                                    
	                                    <?php if($items['record_type'] == 'Credit' ):?>  
	                                                      <?=$items['arabian_points'];?>
	                                                    <?php else: ?>
	                                                      --
	                                                    <?php endif;?>
	                                  </td>
	                                  <td width="10%">
	                                    <?php if($items['record_type'] == 'Debit' ):?>  
	                                                      <?=$items['arabian_points'];?>
	                                                    <?php else: ?>
	                                                      --
	                                                    <?php endif;?>
	                                  </td>
	                                  <td width="10%"> 
	                                    <span class="<?php if($items['arabian_points'] == 'Credit' ):?> green <?php else: ?>  red <?php endif;?>">  <?=$items['record_type'];?> </span>
	                                  </td>
	                                  </td>
	                                </tr>
	                            <?php endif; ?>
	                            <!-- Reverse statement end -->


	                            <!-- referral Amount statement start -->
	                            <?php if($items['statement_type'] == 'referral'): ?>

	                              	<tr class="<?php if($items['record_type'] == 'Credit' ):?>  recharge-received-section  <?php else: ?>  recharge-sent-section <?php endif;?> ">
	                                  <td width="10%"><?=$i?></td>
	                                  <td width="100%"> Referral Amount </td>
	                                  <td width="100%"> 
	                                    Product : <?=$items['product_title'];?>
	                                  </td>
	                                  <td width="100%"><?=date('d M, Y h:i A', strtotime($items['created_at'])); ?></td>
	                                  <td width="10%"><?=$items['arabian_points'];?></td>
	                                  <td width="10%"><?=$items['end_balance'];?></td>
	                                  <td width="10%">
	                                    
	                                    <?php if($items['record_type'] == 'Credit' ):?>  
	                                                      <?=$items['cashback_amount'];?>
	                                                    <?php else: ?>
	                                                      --
	                                                    <?php endif;?>
	                                  </td>
	                                  <td width="10%">
	                                    <?php if($items['record_type'] == 'Debit' ):?>  
	                                                      <?=$items['cashback_amount'];?>
	                                                    <?php else: ?>
	                                                      --
	                                                    <?php endif;?>
	                                  </td>
	                                  <td width="10%"> 
	                                    <span class="<?php if($items['record_type'] == 'Credit' ):?> green <?php else: ?>  red <?php endif;?>">  <?=$items['record_type'];?> </span>
	                                  </td>
	                                  </td>
	                                </tr>
	                            <?php endif; ?>
	                            <!-- referral statement end -->

	                             <!-- Signup Bonus statement start -->
	                            <?php if($items['statement_type'] == 'signup_bonus'): ?>

	                              	<tr class="<?php if($items['record_type'] == 'Credit' ):?> recharge-received-section  <?php else: ?>  recharge-sent-section <?php endif;?> ">
	                                  <td width="10%"><?=$i?></td>
	                                  <td width="100%"> Signup Bonus </td>
	                                  <td width="100%"> 
	                                     Signup Bonus
	                                  </td>
	                                  <td width="100%"><?=date('d M, Y h:i A', strtotime($items['created_at'])); ?></td>
	                                  <td width="10%"><?=$items['arabian_points'];?></td>
	                                  <td width="10%"><?=$items['end_balance'];?></td>
	                                  <td width="10%">
	                                    
	                                    <?php if($items['record_type'] == 'Credit' ):?>  
	                                                      <?=$items['cashback_amount'];?>
	                                                    <?php else: ?>
	                                                      --
	                                                    <?php endif;?>
	                                  </td>
	                                  <td width="10%">
	                                    <?php if($items['record_type'] == 'Debit' ):?>  
	                                                      <?=$items['cashback_amount'];?>
	                                                    <?php else: ?>
	                                                      --
	                                                    <?php endif;?>
	                                  </td>
	                                  <td width="10%"> 
	                                    <span class="<?php if($items['record_type'] == 'Credit' ):?> green <?php else: ?>  red <?php endif;?>">  <?=$items['record_type'];?> </span>
	                                  </td>
	                                  </td>
	                                </tr>
	                            <?php endif; ?>
	                            <!-- Signup Bonus statement end -->

	                            <?php $i++; endforeach; ?>
	                          	<?php else: ?>
		                            <tr>
		                              <td colspan="9">
		                                Wallet statement not found
		                              </td>
		                            </tr>
	                          	<?php endif; ?>
							  </table>
								  <?= $this->pagination->create_links(); ?>
								</div>
					    		<!-- wallet statement end -->
				    	 
				    </div>
				    <?= $this->pagination->create_links(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>
 

<script>
	function ConfirmForm() {
	$("#BlockUIConfirm").show();
	}

	 $(document).ready(function(){
      	
      	$('select[name="filters"]').on('change', function(){
        
         filter = $(this).val();


         if(filter == "all"){
          $('.purchase-section').show();
          $('.cashback-section').show();
          $('.quickbuy-section').show();
          $('.refund-section').show();
          $('.coupon-section').show();
          $('.recharge-sent-section').show();
          $('.recharge-received-section').show();


         }else if(filter == "sent"){
          
          $('.purchase-section').hide();
          $('.cashback-section').hide();
          $('.quickbuy-section').show();
          $('.refund-section').hide();
          $('.coupon-section').hide();
          $('.recharge-sent-section').show();
          $('.recharge-received-section').hide();


         }else if(filter == "received"){
          
          $('.purchase-section').hide();
          $('.cashback-section').hide();
          $('.quickbuy-section').hide();
          $('.refund-section').hide();
          $('.coupon-section').hide();
          $('.recharge-sent-section').hide();
          $('.recharge-received-section').show();


         }else if(filter == "order"){

          $('.purchase-section').show();
          $('.cashback-section').hide();
          $('.quickbuy-section').show();
          $('.refund-section').hide();
          $('.coupon-section').hide();
          $('.recharge-sent-section').hide();
          $('.recharge-received-section').hide();

         }

      });


    });


</script>
</body>

</html>