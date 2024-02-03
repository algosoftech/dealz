<?php include('common/mobile/header.php') ?>

<style>

  * {
    padding: 0;
    margin: 0;
    font-size: 13px;
  }
  .statement-type {
    text-align: center;
    border-radius: 0px 0px 15px 15px;
    padding: 10px;
    color: #ffffff;
    font-weight: 700;
    font-size: 16px;
}
.green{
    background: #0d9e51;
}
.red{
  background: #c9261f;
}

.statement-section {
    border: 1px solid #e0e0e0;
    border-radius: 15px;
    margin-bottom: 20px;
    padding: 0px;
}

.balance-section {
    display: flex;
    justify-content: space-between;
    padding: 12px 15px;
    border-top: 1px solid #e0e0e0;
}

.user-details ,.date-section ,.arabian-point,.order-id,.product-details{
    padding: 0px 15px;
    margin: 5px 0px;
    width: 100%;
}

.text-right{
    text-align: right;
}

.order-id {
    display: flex;
    justify-content: space-between;
}
.focus-text {
    color: #882726;
    font-weight: 600;
}

.filters-section {
    display: flex;
    justify-content: space-around;
    margin-bottom: 10px;
    max-width: 768px;
}

/* radio button css start */
[type="radio"]:checked,
[type="radio"]:not(:checked) {
    position: absolute;
    left: -9999px;
}
[type="radio"]:checked + label,
[type="radio"]:not(:checked) + label
{
    position: relative;
    padding-left: 28px;
    cursor: pointer;
    line-height: 20px;
    display: inline-block;
    color: #666;
}
[type="radio"]:checked + label:before,
[type="radio"]:not(:checked) + label:before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 18px;
    height: 18px;
    border: 1px solid #ddd;
    border-radius: 100%;
    background: #fff;
}
[type="radio"]:checked + label:after,
[type="radio"]:not(:checked) + label:after {
    content: '';
    width: 12px;
    height: 12px;
    background: #882726;
    position: absolute;
    top: 3px;
    left: 3px;
    border-radius: 100%;
    -webkit-transition: all 0.2s ease;
    transition: all 0.2s ease;
}
[type="radio"]:not(:checked) + label:after {
    opacity: 0;
    -webkit-transform: scale(0);
    transform: scale(0);
}
[type="radio"]:checked + label:after {
    opacity: 1;
    -webkit-transform: scale(1);
    transform: scale(1);
}
/*radio button css end */
</style>


    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?= base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Wallet Statements
                </h1>
            </section>
            <div class="inner_pagedata">
                <?php include('common/mobile/membership-details.php') ?>

                <?php if ($this->session->flashdata('error')) : ?>
                    <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                <?php endif; ?>
                
                <?php if ($this->session->flashdata('success')) : ?>
                    <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                <?php endif; ?>

                <?php
                    $id =  base64_encode($profileDetails['users_id']);  
                ?>
                
                <div class="inner_forms">

                  <!-- filter start -->
                  <div class="inner_forms login_tab">
                      <form method="post">
                          <div class="form-section mb-2">
                            <div class="form-group row">
                              <div class="col-sm-12 col-md-12 col-lg-12 p-0">
                                <input type="date" name="start_date" id="start_date" class="form-control"  autocomplete="off" value="<?=set_value('start_date');?>">
                              </div>
                            </div>
                          </div>
                          <div class="form-section mb-2">
                            <div class="form-group row">
                              <div class="col-sm-12 col-md-12 col-lg-12  p-0">
                                <input type="date" name="end_date" id="end_date" class="form-control"  autocomplete="off" value="<?=set_value('end_date');?>">
                                <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                                <input type="submit" class="login_button mt-3" id="login_button2" value="Search">
                              </div>
                            </div>
                          </div>
                      </form>
                    </div>
                    <div class="filters-section">
                      <div class="">
                        <input type="radio" name="filters" value="all" id="all" checked>
                        <label for="all" >All</label>
                      </div>
                      <div class="">
                        <input type="radio" name="filters" value="sent" id="sent">
                        <label for="sent">Sent</label>
                      </div>
                      <div class="">
                        <input type="radio" name="filters" value="received" id="received">
                        <label for="received">Received</label>
                      </div>
                      <div class=""> 
                        <input type="radio" name="filters" value="order" id="order">
                        <label for="order">Order</label>
                      </div>
                    </div>
                    <!-- filter end -->


                    <!-- wallet custom html start -->
                     <?php  if($Walletstatement): ?>
                          <?php foreach($Walletstatement as $ALLDATAINFO): ?>

                            <?php if($ALLDATAINFO['statement_type'] == 'cashback' ): ?>
                              <div class="row cashback-section">
                                <div class="col-sm-6 cpl-md-6 col-lg-6">
                                  <div class="statement-section">
                                    <div class="text-right arabian-point">
                                      <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['cashback_amount'];?>
                                    </div>
                                    <div class="date-section">
                                      Date : <?=date('d M, Y h:i A', strtotime($ALLDATAINFO['created_at'])); ?>
                                    </div>
                                    <div class="balance-section">
                                      <div class="balance">
                                        <label>Opening bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['availableArabianPoints'] ? $ALLDATAINFO['availableArabianPoints'] : '--' ; ?> </label>
                                      </div>
                                      <div class="balance">
                                        <label>Ending bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['end_balance'] ? $ALLDATAINFO['end_balance'] : '--' ; ?>  </label>
                                      </div>
                                    </div>  

                                    <div class="statement-type  green">
                                      <span>Cashback</span>
                                    </div>  

                                  </div>
                                </div>
                              </div>
                            <?php endif; ?>

                           

                            <?php if($ALLDATAINFO['statement_type'] == 'purchase' ): ?>
                              <div class="row purchase-section">
                                <div class="col-sm-6 cpl-md-6 col-lg-6">
                                  <div class="statement-section">
                                    <div class="text-right arabian-point">
                                      <!-- Fetching and calculating all data in loop. -->
                                      <?php $total_price =0;  foreach ($ALLDATAINFO['product_details'] as $key => $item_details): ?>
                                          <?php $total_price = $total_price+ $item_details['price']  ?>
                                      <?php endforeach; ?>

                                        <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$total_price;?>
                                    </div>

                                    <div class="user-details">
                                      <div class="user_email">
                                       Name : <span class="focus-text"> <?=$ALLDATAINFO['users_name'].' '.$ALLDATAINFO['last_name'] ; ?> </span> </br>
                                       Email: <?=$ALLDATAINFO['user_email']; ?></br>
                                       Mobile: <?=$ALLDATAINFO['user_phone']; ?></br>
                                      </div>
                                    </div>

                                    <div class="order-id">
                                     <span> Order ID : </span>  <span class="focus-text"> <?=$ALLDATAINFO['order_id']; ?> </span>
                                    </div>

                                    <div class="product-details">
                                      
                                      Product Name : 
                                      <!-- Fetching and calculating all data in loop. -->
                                      <?php foreach ($ALLDATAINFO['product_details'] as $key => $item_details): ?>
                                            <?= $item_details['product_name'].' * '.$item_details['quantity'] .'<br>'; ?>
                                      <?php endforeach; ?>

                                    </div>


                                    <div class="date-section">
                                      Date : <?=date('d M, Y h:i A', strtotime($ALLDATAINFO['created_at'])); ?>
                                    </div>


                                    <div class="balance-section">
                                      <div class="balance">
                                        <label>Opening bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['availableArabianPoints'] ? $ALLDATAINFO['availableArabianPoints'] : '--' ; ?> </label>
                                      </div>
                                      <div class="balance">
                                        <label>Ending bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['end_balance'] ? $ALLDATAINFO['end_balance'] : '--' ; ?> </label>
                                      </div>
                                    </div>  

                                    <div class="statement-type  red">
                                      <span>Purchase</span>
                                    </div>  

                                  </div>
                                </div>
                              </div>
                            <?php endif; ?>

                            <?php if($ALLDATAINFO['statement_type'] == 'quick_buy' ): ?>
                              <div class="row quickbuy-section">
                                <div class="col-sm-6 cpl-md-6 col-lg-6">
                                  <div class="statement-section">
                                    <div class="text-right arabian-point">
                                      <!-- Fetching and calculating all data in loop. -->
                                        <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['price'];?>
                                    </div>

                                    <div class="user-details">
                                      <div class="user_email">
                                       Name : <span class="focus-text"> <?=$ALLDATAINFO['users_name'].' '.$ALLDATAINFO['last_name'] ; ?></span> </br>
                                       Email: <?=$ALLDATAINFO['user_email']; ?></br>
                                       Mobile: <?=$ALLDATAINFO['user_phone']; ?></br>
                                      </div>
                                    </div>

                                    <div class="order-id">
                                     <span> Order ID : </span> <span class="focus-text"><?=$ALLDATAINFO['order_id']; ?></span>
                                    </div>

                                    <div class="product-details">
                                      Product Name : <?= $ALLDATAINFO['product_name'].' * '.$ALLDATAINFO['quantity']; ?>
                                    </div>
                                    
                                    <div class="date-section">
                                      Date : <?=date('d M, Y h:i A', strtotime($ALLDATAINFO['created_at'])); ?>
                                    </div>

                                    <div class="balance-section">
                                      <div class="balance">
                                        <label>Opening bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen">
                                          <?php if($users_type == 'Users'): ?>
                                             -- 
                                          <?php else: ?>
                                            <?=$ALLDATAINFO['availableArabianPoints'] ? $ALLDATAINFO['availableArabianPoints'] : '--' ; ?> 
                                          <?php endif; ?>
                                       </label>
                                      </div>
                                      <div class="balance">
                                        <label>Ending bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> 
                                           <?php if($users_type == 'Users'): ?>
                                             -- 
                                          <?php else: ?>
                                            <?=$ALLDATAINFO['end_balance'] ? $ALLDATAINFO['end_balance'] : '--' ; ?> 
                                          <?php endif; ?>
                                        </label>
                                      </div>
                                    </div>  

                                    <div class="statement-type  red">
                                      <span>Quick Ticket</span>
                                    </div>  

                                  </div>
                                </div>
                              </div>
                            <?php endif; ?>

                            <?php if($ALLDATAINFO['statement_type'] == 'reverse_amount' ): ?>
                              <div class="row quickbuy-section">
                                <div class="col-sm-6 cpl-md-6 col-lg-6">
                                  <div class="statement-section">
                                    <div class="text-right arabian-point">
                                      <!-- Fetching and calculating all data in loop. -->
                                        <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['price'];?>
                                    </div>

                                    <div class="product-details">
                                      Amount :  <?=$ALLDATAINFO['arabian_points']; ?>
                                    </div>
                                    <div class="user-details">
                                      <div class="user_email">
                                         Amount terminated. 
                                      </div>
                                    </div>
                                    
                                    <div class="date-section">
                                      Date : <?=date('d M, Y h:i A', strtotime($ALLDATAINFO['created_at'])); ?>
                                    </div>

                                    <div class="balance-section">
                                      <div class="balance">
                                        <label>Opening bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['availableArabianPoints'] ? $ALLDATAINFO['availableArabianPoints'] : '--' ; ?> </label>
                                      </div>
                                      <div class="balance">
                                        <label>Ending bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['end_balance'] ? $ALLDATAINFO['end_balance'] : '--' ; ?> </label>
                                      </div>
                                    </div>  

                                    <div class="statement-type  red">
                                      <span>Reversed Amount</span>
                                    </div>  

                                  </div>
                                </div>
                              </div>
                            <?php endif; ?>
                             

                            <?php if($ALLDATAINFO['statement_type'] == 'refund' ): ?>
                                 <div class="row refund-section">
                                  <div class="col-sm-6 cpl-md-6 col-lg-6">
                                    <div class="statement-section">
                                      <div class="text-right arabian-point">
                                        <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['cashback_amount'];?>
                                      </div>
                                      <div class="date-section">
                                        Date : <?=date('d M, Y h:i A', strtotime($ALLDATAINFO['created_at'])); ?>
                                      </div>
                                      <div class="balance-section">
                                        <div class="balance">
                                          <label>Opening bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['availableArabianPoints'] ? $ALLDATAINFO['availableArabianPoints'] : '--' ; ?> </label>
                                        </div>
                                        <div class="balance">
                                          <label>Ending bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['end_balance'] ? $ALLDATAINFO['end_balance'] : '--' ; ?> </label>
                                        </div>
                                      </div>  
                                      <div class="statement-type  green">
                                        <span>Quick buy ( Refund )</span>
                                      </div>  
                                    </div>
                                  </div>
                                </div>
                            <?php endif; ?>

                            <?php if($ALLDATAINFO['statement_type'] == 'referral' ): ?>
                                 <div class="row refund-section">
                                  <div class="col-sm-6 cpl-md-6 col-lg-6">
                                    <div class="statement-section">
                                      <div class="text-right arabian-point">
                                        <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['cashback_amount'];?>
                                      </div>

                                      <div class="product-details">
                                        Product Name : <?=$ALLDATAINFO['product_title'];?> <br>                                      
                                      </div>

                                      <div class="date-section">
                                        Date : <?=date('d M, Y h:i A', strtotime($ALLDATAINFO['created_at'])); ?>
                                      </div>
                                      <div class="balance-section">
                                        <div class="balance">
                                          <label>Opening bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['availableArabianPoints'] ? $ALLDATAINFO['availableArabianPoints'] : '--' ; ?> </label>
                                        </div>
                                        <div class="balance">
                                          <label>Ending bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['end_balance'] ? $ALLDATAINFO['end_balance'] : '--' ; ?> </label>
                                        </div>
                                      </div>  
                                      <div class="statement-type  green">
                                        <span> Referral Amount </span>
                                      </div>  
                                    </div>
                                  </div>
                                </div>
                            <?php endif; ?>

                            <?php if($ALLDATAINFO['statement_type'] == 'quick_vouchers' ): ?>
                                 <div class="row refund-section">
                                  <div class="col-sm-6 cpl-md-6 col-lg-6">
                                    <div class="statement-section">
                                      <div class="text-right arabian-point">
                                        <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['cashback_amount'];?>
                                      </div>

                                      <div class="product-details">
                                        Order ID : <?=$ALLDATAINFO['order_id'].'<br>';?>  
                                        Voucher Code : <?=$ALLDATAINFO['voucher_code'].'<br>';?>                                     
                                      </div>

                                      <div class="date-section">
                                        Date : <?=date('d M, Y h:i A', strtotime($ALLDATAINFO['created_at'])); ?>
                                      </div>
                                      <div class="balance-section">
                                        <div class="balance">
                                          <label>Opening bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['availableArabianPoints'] ? $ALLDATAINFO['availableArabianPoints'] : '--' ; ?> </label>
                                        </div>
                                        <div class="balance">
                                          <label>Ending bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['end_balance'] ? $ALLDATAINFO['end_balance'] : '--' ; ?> </label>
                                        </div>
                                      </div>  
                                      <div class="statement-type  green">
                                        <span> Quick Vocher </span>
                                      </div>  
                                    </div>
                                  </div>
                                </div>
                            <?php endif; ?>

                            <?php if($ALLDATAINFO['statement_type'] == 'coupon' ): ?>
                              
                                <div class="row coupon-section">
                                  <div class="col-sm-6 cpl-md-6 col-lg-6">
                                    <div class="statement-section">
                                      <div class="text-right arabian-point">
                                        <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['coupon_code_amount'];?>
                                      </div>

                                      <div class="order-id">
                                        <span>Coupon Code :</span> <span class="focus-text"><?=$ALLDATAINFO['coupon_code']; ?></span>
                                      </div>

                                      <div class="date-section">
                                        Date : <?=date('d M, Y h:i A', strtotime($ALLDATAINFO['created_at'])); ?>
                                      </div>
                                      <div class="balance-section">
                                        <div class="balance">
                                          <label>Opening bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['availableArabianPoints'] ? $ALLDATAINFO['availableArabianPoints'] : '--' ; ?> </label>
                                        </div>
                                        <div class="balance">
                                          <label>Ending bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['end_balance'] ? $ALLDATAINFO['end_balance'] : '--' ; ?> </label>
                                        </div>
                                      </div>  
                                      <div class="statement-type  green">
                                        <span>Coupon </span>
                                      </div>  
                                    </div>
                                  </div>
                                </div>
                            <?php endif; ?>

                            <?php if($ALLDATAINFO['statement_type'] == 'recharge' ): ?>
                              <div class="row <?php if($ALLDATAINFO['record_type'] == 'Credit' ):?>  recharge-received-section  <?php else: ?>  recharge-sent-section <?php endif;?> ">
                                <div class="col-sm-6 cpl-md-6 col-lg-6">
                                  <div class="statement-section">
                                    <div class="text-right arabian-point">
                                      <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['sum_arabian_points'];?>
                                    </div>

                                    <div class="user-details">
                                      <div class="user_email">
                                       Name : <?=$ALLDATAINFO['users_name'].' '.$ALLDATAINFO['last_name'] ; ?></br>
                                       Email: <?=$ALLDATAINFO['users_email']; ?></br>
                                       Mobile: <?=$ALLDATAINFO['users_mobile']; ?></br>
                                      </div>
                                    </div>


                                    <div class="date-section">
                                      Date : <?=date('d M, Y h:i A', strtotime($ALLDATAINFO['created_at'])); ?>
                                    </div>
                                    
                                    <div class="order-id">
                                      <span>Margin Percentage :</span> 
                                      <span> <?php if($ALLDATAINFO['rechargeDetails']->percentage): echo  $ALLDATAINFO['rechargeDetails']->percentage.'%'; else: echo '--'; endif ; ?> </span>
                                    </div>
                                    <div class="balance-section">
                                      <div class="balance">
                                        <label>Opening bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen">  <?=$ALLDATAINFO['availableArabianPoints'] ? $ALLDATAINFO['availableArabianPoints'] : '--' ; ?> </label>
                                      </div>
                                      <div class="balance">
                                        <label>Ending bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['end_balance'] ? $ALLDATAINFO['end_balance'] : '--' ; ?> </label>
                                      </div>
                                    </div>  
                                    <div class="statement-type <?php if($ALLDATAINFO['record_type'] == 'Credit' ):?>  green <?php else: ?> red <?php endif;?>   ">
                                      <span> 
                                        <?php if($ALLDATAINFO['record_type'] == 'Credit' ):?>  
                                          Recharge - Received 
                                        <?php else: ?>
                                         Recharge - Sent 
 
                                       <?php endif;?>
                                      </span>
                                    </div>  
                                  </div>
                                </div>
                              </div>

                            <?php endif; ?>

                            <?php if($ALLDATAINFO['statement_type'] == 'signup_bonus' ): ?>
                              <div class="row cashback-section">
                                <div class="col-sm-6 cpl-md-6 col-lg-6">
                                  <div class="statement-section">
                                    <div class="text-right arabian-point">
                                      <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['cashback_amount'];?>
                                    </div>
                                    <div class="date-section">
                                      Date : <?=date('d M, Y h:i A', strtotime($ALLDATAINFO['created_at'])); ?>
                                    </div>
                                    <div class="balance-section">
                                      <div class="balance">
                                        <label>Opening bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['availableArabianPoints'] ? $ALLDATAINFO['availableArabianPoints'] : '0' ; ?> </label>
                                      </div>
                                      <div class="balance">
                                        <label>Ending bal. : <img src="<?=base_url('assets/AP-GREEN.png');?>" width="20px" alt="appgreen"> <?=$ALLDATAINFO['end_balance'] ? $ALLDATAINFO['end_balance'] : $ALLDATAINFO['cashback_amount'] ; ?>  </label>
                                      </div>
                                    </div>  

                                    <div class="statement-type  green">
                                      <span>Signup Bonus</span>
                                    </div>  

                                  </div>
                                </div>
                              </div>
                            <?php endif; ?>

                            <?php endforeach;?>
                      <?php else: ?>
                        <h3><span>Wallet statement not found</span></h3>
                      <?php endif; ?>
                    <!-- wallet custom html end -->
                </div>
                      
            </div>
        </div>
   
        <?php include('common/mobile/footer.php'); ?>
        <?php include('common/mobile/menu.php'); ?>
        </div>
    </div>

<?php include('common/mobile/footer_script.php'); ?>


<script type="text/javascript">
    
    $('.notification').on('click' ,function(){

        var fieldName = $(this).attr('data-type');
        var ur = '<?=base_url()?>';
        $.ajax({
            url : ur+ "my-profile/notification",
            method: "POST", 
            data: { fieldName: fieldName },
            success: function(data){
                if(data == 1 ){
                    location.reload();
                }
            }
        });
    });


    $(document).ready(function(){

      $('input[name="filters"]').on('click', function(){
        
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