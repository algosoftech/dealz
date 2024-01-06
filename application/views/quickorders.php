<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="quick-order mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/quick-buy');?>"><i class="icofont-rounded-left"></i></a>
                <h1> Quick Orders </h1>
            </section>
            <div class="inner_pagedata">
                <section class="deals_homesec">
                   <div class="myorder_table">

                        <div class="inner_forms login_tab">
                         <h3><span>Search by Mobile No.</span></h3>
                            <form method="post" method="post">
                              <div class="form-group row">
                                <div class="col p-0">
                                    <input type="text" id="searchfield" class="form-control btn-search" name="searchfield" placeholder="Search by Mobile No." value="<?=$searchfield;?>">
                                     <i class="icofont-search search-button"></i>
                                </div>
                              </div>
                            </form>
                        </div>

                        <?php if ($this->session->flashdata('error')): ?>
                            <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                        <?php endif; ?>
                        
                        <?php if ($this->session->flashdata('success')): ?>
                            <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                        <?php  endif; ?>

                        <?php if($orderData): ?>
                            <div class="row order-section">
                               <?php foreach($orderData as $key => $items ) :  ?>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="cardbox">
                                            <ul>
                                                <li class="red_txt">
                                                    <strong>Order Id</strong>
                                                    <span><?=$items['ticket_order_id'];?></span>
                                                </li>
                                                <li class="youbuy">
                                                    <strong>Product</strong>
                                                    <span class="ordshow"><i class="icofont-rounded-down"></i></span>
                                                    <div class="youorde_list">
                                                         <div>
                                                            <span>
                                                                <?=stripslashes($items['product_title']);   ?>
                                                                <em>x <?=$items['product_qty'] ?></em><br>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <strong>Price</strong>
                                                    <span>AED <?= number_format($items['total_price'],2); ?>  </span>
                                                </li>
                                                <li>
                                                    <strong>Purchased By</strong>
                                                    <span>  <?=$items['order_first_name'].' '.$items['order_last_name'];?> <span> 
                                                    <?php if($items['order_users_email']): ?>
                                                    <span>  <?='<br>'.$items['order_users_email'];?> <span> 
                                                    <?php endif; ?> 
                                                    <?php if($items['order_users_mobile']): ?>
                                                    <span>  <?='<br>'.$items['order_users_mobile'];?> <span> 
                                                    <?php endif; ?>
                                                </li>
                                                <li>
                                                    <strong>Purchase On</strong>
                                                    <span> <?=date('d M, Y h:i a', strtotime($items['created_at']))?> </span>
                                                </li>
                                                 <li class="red_txt">
                                                    <strong>Coupon #</strong>
                                                    <span> 
                                                        <?php foreach ($items['coupon_code']  as $key => $Coupons): ?>
                                                           <?=$Coupons.'<br>';?>
                                                        <?php endforeach;?>
                                                    </span>
                                                </li>
                                            </ul>
                                            <div class="donat_nrecipt button-container">
                                                <a href="<?=base_url('/quick/resend-sms/'.$items['ticket_order_id']);?>">Resend SMS</a>
                                                <a href="<?=base_url('/quick/download-invoice/'.base64_encode($items['ticket_order_id']));?>">Download Receipt</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                           </div>
                        <?php else: ?>
                           <h6 class="text-center"><span>Data not found.</span></h6>
                        <?php endif; ?>

                        <!-- pagination start  -->
                        <div class="pagination-container">
                            <?php 
                                foreach($pagination as $item):
                                    echo $item;
                                endforeach;
                            ?>
                        </div>
                        <!-- pagination End -->

                   </div>
                </section>
            </div>

        <?php include('common/mobile/footer.php'); ?>
        <?php include('common/mobile/menu.php'); ?>
        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>