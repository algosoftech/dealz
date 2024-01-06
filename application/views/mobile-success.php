<?php include('common/mobile/header.php') ?>

<style type="text/css">

    .invoice-text-para p {
        text-align: center;
        font-size: 14px;
        color: #545454;
    }
    
    .order-date-section{
        margin: 20px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
    }

    .invoice-details .table-responsive {
        margin: 20px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .link-list-section {
        width: 100%;
        display: inline-flex;
        justify-content: space-between;
    }

    .download-invoice, .shop-more {
        background: #c9261f;
        margin: 0px 10px;
        padding: 18px;
        height: 30px;
        font-size: 12px;
        color: #ffffff;
        line-height: 0;
        border-radius: 10px;
        text-align: center;
    }

    .login_tab .nav-tabs .nav-link {
        text-align: center;
    }

    .coupon {
        position: relative;
        margin: 10px auto;
        background-image: url(<?=base_url('assets/img/coupen-img.png')?>);
        background-size: 100% 100%;
        background-position: 0 0, 200px 0;
        background-repeat-x: no-repeat;
        color: #fff;
        overflow: hidden;
    }

    .coupon .about-coupen{
        display: flex;
    }

    .coupon .about-coupen div {
        margin: 0 15px;
        padding: 12px 10px;
    }

    .coupen-box .invoice-details p span, .coupon .about-coupen h4, .coupon .about-coupen p  {
        color: #000;
        font-weight: 600;
        font-size: 13px;
    }

    .coupon .coupen-footer {
        background: #b12021;
        color: #fff;
        padding: 8px;
        text-align: center;
    }

    .coupen-footer p {
        margin: unset;
    }

    .about-coupen .coupen-img img {
        height: 135px;
        width: 135px;
    }

</style>

    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/');?>"><i class="icofont-rounded-left"></i></a>
               
            </section>
            <div class="inner_pagedata">
               <section class="info_pages">

                            <div class="section">
                                
                                <div class="text-center">
                                    <img src="<?=base_url('/assets/img/right.png')?>" class="img-fluid d-block mx-auto" alt="right_img">
                                </div>

                                <h2>Thank you</h2>
                                <div class="invoice-text-para">
                                    <p>Please find below your Invoice and order details.</p>
                                </div>

                                
                               <div class="login_tab">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#Invoice">Invoice</a>
                                      </li>
                                    <li class="nav-item ">
                                      <a class="nav-link" data-bs-toggle="tab" href="#Coupons">Coupons</a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                      <div class="tab-content">
                        <div class="tab-pane active" id="Invoice">
                            <div id="Invoice" class="tab-pane fade show active">
                                <div class="invoice-details">
                                    <p class="text-heading">Tax Invoice</p>
                                   
                                    <div class="order-date-section">
                                        <p>Order Id <span><?php echo $order_id;?></span></p>
                                        <?php if($stripe_token): ?>
                                            <p>Transaction Id <span><?php echo $stripe_token;?></span></p>
                                        <?php endif; ?>
                                        <p>Purchased on : <span><?php echo date('d M, Y h:i A' ,strtotime($orderData['created_at']))?></span></p>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th>Product</th>    
                                                <th>Quantity</th>   
                                                <th>Subtotal</th>   
                                            </tr>
                                            <?php foreach($orderDetails as $OD):
                                                $CPwcon['where']                    =   [ 'product_id' => $OD["product_id"] ];                                  
                                                $CPData                             =   $this->geneal_model->getData2('single', 'da_prize', $CPwcon);           
                                            ?>
                                            <tr>
                                                <td><?php echo stripslashes($OD['product_name']);?></td> 
                                                <td> AED  <?= $OD['price'].'x'. $OD['quantity']?></td>  
                                                <td>AED <?php echo number_format($OD['subtotal'],2);?></td>
                                            </tr>

                                            <tr >
                                                <td colspan="2">Including VAT</td>  
                                                <td>AED <?=@number_format($orderData['total_price'],2)?></td>   
                                            </tr>
                                            <?php endforeach;?>
                                        </table>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td>Discount</td>   
                                                <td class="text-right">AED  <?=@number_format($cashback,2)?> </td> 
                                            </tr>
                                            <?php if($orderData['payment_mode'] == 'arabianpoint' || $orderData['payment_mode'] == 'Arabian Points'): ?>
                                                <tr>
                                                    <td>Paid Using Arabian Points</td>  
                                                    <td class="text-right">AED <?=@number_format($orderData['total_price'],2)?></td>    
                                                </tr>
                                                
                                            <?php else: ?>
                                                <tr>
                                                    <td>Paid Using Card</td>    
                                                    <td class="text-right">AED <?=@number_format($orderData['total_price'],2)?></td>    
                                                </tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                       


                        <div class="tab-pane fade" id="Coupons">
                           


                        <?php foreach ($couponDetails as $coupon): ?>
                        <div class="coupon">
                            <div class="about-coupen">
                                <?php 
                                    $SPwhereCon['where']    =   array('products_id' => (int)$coupon['product_id'] );
                                    $ProductData            =   $this->geneal_model->getData2('single','da_products',$SPwhereCon);
                                ?>
                                <div class="coupen-img">
                                    <?php if(file_get_contents(base_url($ProductData['product_image'])) ):  ?>
                                        <img src="<?=base_url($ProductData['product_image']);?>" class="img-fluid" alt="<?=$ProductData['product_image_alt'];?>">
                                    <?php else: ?>
                                        <img src="<?=base_url('assets/img/NO_IMAGE.jpg');?>" class="img-fluid" alt="<?=$ProductData['product_image_alt'];?>">
                                    <?php endif;?>
                                </div>
                                <div class="coupen-info">
                                    
                                    <p>Products: <span><?=$coupon['product_title']?></span></p>
                                    <p>Purchased on: <span><?=date('d M, Y h:i A' ,strtotime($coupon['created_at']))?></span></p>
                                    <p>Draw Date : <span><?=date('d M, Y' ,strtotime($ProductData['draw_date'].' '.$ProductData['draw_time']))?></span></p>
                                </div>
                            </div>
                            <div class="coupen-footer">
                                <p>Coupon No: <?=$coupon['coupon_code']?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>



                        </div>
                      </div>
                    </div>
                      
                    <div class="link-list-section">
                        <div class="link-list">
                            <a  href="<?=base_url().'order/download-invoice/'.$order_id ;?>" class="download-invoice nav-link"> Download Invoice </a>
                        </div>

                        <div class="link-list">
                            <a href="<?=base_url()?>" class="shop-more nav-link">Shop More</a>
                        </div>
                        
                    </div>

                  
                    </div>
                </section>
            </div>

<?php include('common/mobile/footer.php'); ?>
<?php include('common/mobile/menu.php'); ?>

        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>