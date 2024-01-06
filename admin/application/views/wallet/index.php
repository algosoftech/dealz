<style>

  * {
    padding: 0;
    margin: 0;
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

</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
$(function(){
   $("#fromDate").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
   $("#toDate").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
   
   $("#fromDate1").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
   $("#toDate1").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
});
</script>
<div class="pcoded-main-container">
    <div class="pcoded-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Welcome <?=sessionData('HCAP_ADMIN_FIRST_NAME')?></h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo getCurrentDashboardPath('dashboard/index'); ?>"><i class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Wallet Statements</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
     <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <h5>Manage Wallet Statements</h5>
                <a href="javaScriptcript:void{0}" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#exportModal">Export excel</a>
              </div>
              <div class="card-body">
                <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
                  <div class="dt-responsive table-responsive">
                    <div id="simpletable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                      
                      <div class="row">
                        <div class="col-sm-12 col-md-12">
                          <div class="dataTables_length" id="simpletable_length">
                            <label>Show 
                              <select name="showLength" id="showLength" class="custom-select custom-select-sm form-control form-control-sm">
                                <option value="2" <?php if($perpage == '2')echo 'selected="selected"'; ?>>2</option>
                                <option value="10" <?php if($perpage == '10')echo 'selected="selected"'; ?>>10</option>
                                <option value="25" <?php if($perpage == '25')echo 'selected="selected"'; ?>>25</option>
                                <option value="50" <?php if($perpage == '50')echo 'selected="selected"'; ?>>50</option>
                                <option value="100" <?php if($perpage == '100')echo 'selected="selected"'; ?>>100</option>
                                <option value="All" <?php if($perpage == 'All')echo 'selected="selected"'; ?>>All</option>
                              </select>
                              entries
                            </label>
                          </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                          <select name="searchField" id="searchField" class="custom-select custom-select-sm form-control form-control-sm">
                              <option value="">Select Field</option>
                              <!-- <option value="user_id" <?php if($searchField == 'user_id')echo 'selected="selected"'; ?>>User ID</option> -->
                              <option value="users_email" <?php if($searchField == 'users_email')echo 'selected="selected"'; ?>>User Email</option>
                              <option value="users_mobile" <?php if($searchField == 'users_mobile')echo 'selected="selected"'; ?>>User Phone</option>
                          </select>
                        </div>

                        <div class="col-sm-3 col-md-3">
                          <input type="text" name="searchValue" id="searchValue" value="<?php echo $searchValue; ?>" class="form-control form-control-sm" placeholder="Enter Search Text">
                        </div>

                        <div class="col-sm-6 col-md-6">
                          <div class="row" >
                            <div class="col-sm-12 col-md-4">
                              <input type="text" name="fromDate" id="fromDate" autocomplete="off" value="<?php echo $fromDate; ?>" class="form-control form-control-sm" placeholder="From Date">
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <input type="text" name="toDate" id="toDate" autocomplete="off" value="<?php echo $toDate; ?>" class="form-control form-control-sm" placeholder="To Date">
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <input type="submit" name="Search" value="Search" class="btn btn-sm btn-primary">
                            </div>
                          </div>
                        </div>

                      </div>
                      <?php if($userData): ?>
                      <diV class="row" style="color: blue;">
                        <div class="col-sm-6 col-md-6">
                          <lable>User Name : <b><?=$userData['users_name']?></b></lable>
                        </div>
                        <div class="col-sm-6 col-md-6">
                          <lable>User Type : <b><?=$userData['users_type']?></b></lable>
                        </div>
                      </diV>
                      <?php endif; ?>



                      <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                          <table id="simpletable" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="simpletable_info">
                            <thead style="text-align: center;">
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
                                <th style="text-align:center;">Payment From</th>
                                <th style="text-align:center;">Payment Mode</th>
                              </tr>
                            </thead>
                            <tbody style="text-align: center;">
                              <?php if($ALLDATA): ?>
                                <?php $i=1; foreach ($ALLDATA as $key => $items):?>
                              
                                <!-- coupon statement end -->
                                <?php if($items['statement_type'] == 'coupon'): ?>
                                  <tr class="coupon-section">
                                      <td width="10%"><?=$i?></td>
                                      <td width="100%">Coupon</td>
                                      <td width="100%">Coupon Code : <?=$items['coupon_code']; ?> </td>
                                      <td width="100%"><?=date('d M Y h:i:s A', strtotime($items['created_at'])); ?></td>
                                      <td width="10%"><?=$items['availableArabianPoints'];?></td>
                                      <td width="10%"><?=$items['end_balance'];?></td>
                                      <td width="10%"><?=$items['coupon_code_amount'];?></td>
                                      <td width="10%">--</td>
                                      <td width="10%"> <span class="green">  Credit </span> </td>
                                      <td width="10%"><?=$items['payment_from'];?></td>
                                      <td width="10%"><?=$items['payment_mode'];?></td>
                                  </tr>
                                <?php endif;?>
                                <!-- coupon statement end -->

                                <!-- cashback statement start -->
                                <?php if($items['statement_type'] == 'cashback' ): ?>
                                  <tr class="cashback-section">
                                      <td width="10%"><?=$i?></td>
                                      <td width="100%">Cashback</td>
                                      <td width="100%"> Order ID - <?=$items['order_id'];?> </td>
                                      <td width="100%"><?=date('d M Y h:i:s A', strtotime($items['created_at'])); ?></td>
                                      <td width="10%"><?=$items['availableArabianPoints'];?></td>
                                      <td width="10%"><?=$items['end_balance'];?></td>
                                      <td width="10%"><?=$items['cashback_amount'];?></td>
                                      <td width="10%">--</td>
                                      <td width="10%"> <span class="green">  Credit </span> </td>
                                      <td width="10%"><?=$items['payment_from'];?></td>
                                      <td width="10%"><?=$items['payment_mode'];?></td>
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

                                      <td width="100%"><?=date('d M Y h:i:s A', strtotime($items['created_at'])); ?></td>
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
                                      <td width="10%"><?=$items['payment_from'];?></td>
                                      <td width="10%"><?=$items['payment_mode'];?></td>
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

                                      <td width="100%"><?=date('d M Y h:i:s A', strtotime($items['created_at'])); ?></td>

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
                                      <td width="10%"><?=$items['payment_from'];?></td>
                                      <td width="10%"><?=$items['payment_mode'];?></td>
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

                                      <td width="100%"><?=date('d M Y h:i:s A', strtotime($items['created_at'])); ?></td>

                                      <td width="10%"><?=$items['availableArabianPoints'];?></td>
                                      <td width="10%"><?=$items['end_balance'];?></td>

                                      <td width="10%">
                                        <?=$items['price']; ?>
                                      </td>
                                      <td width="10%">--</td>

                                      <td width="10%"> <span class="green">  Credit </span> </td>
                                      <td width="10%"><?=$items['payment_from'];?></td>
                                      <td width="10%"><?=$items['payment_mode'];?></td>
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
                                      <td width="100%"><?=date('d M Y h:i:s A', strtotime($items['created_at'])); ?></td>
                                      <td width="10%"><?=$items['availableArabianPoints'];?></td>
                                      <td width="10%"><?=$items['end_balance'];?></td>
                                      <td width="10%"><?=$items['cashback_amount'];?></td>
                                      <td width="10%">--</td>
                                      <td width="10%"> <span class="green">  Credit </span> </td>
                                      <td width="10%"><?=$items['payment_from'];?></td>
                                      <td width="10%"><?=$items['payment_mode'];?></td>
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
                                      <td width="100%"><?=date('d M Y h:i:s A', strtotime($items['created_at'])); ?></td>
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
                                      <td width="10%"><?=$items['payment_from'];?></td>
                                      <td width="10%"><?=$items['payment_mode'];?></td>
                                    </tr>
                                  <?php endif; ?>
                                <!-- Recharge statement end -->

                                <!-- Reverse Amount statement start -->
                                <?php if($items['statement_type'] == 'reverse_amount' ): ?>
                                    <tr class="<?php if($items['record_type'] == 'Credit' ):?>  recharge-received-section  <?php else: ?>  recharge-sent-section <?php endif;?> ">
                                      <td width="10%"><?=$i?></td>
                                      <td width="100%"> Reversed Amount </td>
                                      <td width="100%"> 
                                        Account terminated. 
                                      </td>
                                      <td width="100%"><?=date('d M Y h:i:s A', strtotime($items['created_at'])); ?></td>
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
                                      <td width="10%"><?=$items['payment_from'];?></td>
                                      <td width="10%"><?=$items['payment_mode'];?></td>
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
                                      <td width="100%"><?=date('d M Y h:i:s A', strtotime($items['created_at'])); ?></td>
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
                                      <td width="10%"><?=$items['payment_from'];?></td>
                                      <td width="10%"><?=$items['payment_mode'];?></td>
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
                                      <td width="100%"><?=date('d M Y h:i:s A', strtotime($items['created_at'])); ?></td>
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
                                      <td width="10%"><?=$items['payment_from'];?></td>
                                      <td width="10%"><?=$items['payment_mode'];?></td>
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
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12 col-md-5">
                          <div class="dataTables_info" role="status" aria-live="polite"><?php echo $noOfContent; ?></div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                          <div class="dataTables_paginate paging_simple_numbers">
                            <?php echo $PAGINATION; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Open modal for @getbootstrap</button> -->

<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Download Wallet Statement Reports</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?=base_url('wallet/wallet_statements/exportexcel')?>" method="post" autocomplete="off">
        <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <label for="recipient-name" class="col-form-label">Start Date:</label>
              </div>
              <div class="col-sm-12 col-md-6">
                <label for="recipient-name" class="col-form-label">End Date:</label>
              </div>
              <div class="col-sm-12 col-md-6">
                <input type="text" name="fromDate" id="fromDate1" value="<?php echo $fromDate; ?>" class="form-control form-control-sm" placeholder="Start Date">
              </div>
              <div class="col-sm-12 col-md-6">
                <input type="text" name="toDate" id="toDate1" value="<?php echo $toDate; ?>" class="form-control form-control-sm" placeholder="End Date">
              </div>
            </div>

            <div class="row mt-2"  style="margin:0px;">
                <div class="col-sm-12 col-md-6">
                  <select name="searchField" id="searchField" class="custom-select custom-select-sm form-control form-control-sm">
                      <option value="">Select Field</option>
                      <!-- <option value="user_id" <?php if($searchField == 'user_id')echo 'selected="selected"'; ?>>User ID</option> -->
                      <option value="users_email" <?php if($searchField == 'users_email')echo 'selected="selected"'; ?>>User Email</option>
                      <option value="users_mobile" <?php if($searchField == 'users_mobile')echo 'selected="selected"'; ?>>User Phone</option>
                  </select>
                </div>

                <div class="col-sm-12 col-md-6">
                  <input type="text" name="searchValue" id="searchValue" value="<?php echo $searchValue; ?>" class="form-control form-control-sm" placeholder="Enter Search Text">
                </div>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Download Report</button>
        </div>
      </form>
    </div>
  </div>
</div>