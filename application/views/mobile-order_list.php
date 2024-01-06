<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    My Orders
                </h1>
            </section>
            <div class="inner_pagedata">
                <section class="deals_homesec">
                   <div class="myorder_table">
                        <?php if($orderData): ?>
                            <?php foreach($orderData as $key => $items ) :  ?>
                                <div class="cardbox">
                                    <ul>
                                        <li class="red_txt">
                                            <strong>Order Id</strong>
                                            <span><?=$items['order_id'];?></span>
                                        </li>
                                        <li class="youbuy">
                                            <strong>Product</strong>
                                            <span class="ordshow"><i class="icofont-rounded-down"></i></span>
                                            <div class="youorde_list">
                                                 <div>
                                                    <span>
                                                        <?php 
                                                        $j=1;
                                                        foreach ($items['order_details'] as $key => $v): ?>
                                                          <?= $j.'. '.stripslashes($v['product_name']);   ?>
                                                           <em>x <?=$v['quantity'] ?></em><br>
                                                            
                                                        <?php $j++; endforeach; ?>
                                  
                              </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <strong>Amount</strong>
                                            <span>AED    <?= number_format($items['total_price'],2); ?>  </span>
                                        </li>
                                        <li>
                                            <strong>Pick Up Points</strong>
                                            <span> 

                                                  <?php

                                                    if($items['collection_point_name']):
                                                        $collection_point =   explode("https", $items['collection_point_name']); 
                                                        $address = $collection_point[0];

                                                        if($collection_point[1]):
                                                            $google_link = 'https'.$collection_point[1];
                                                        endif;
                                                    endif;

                                                ?>

                                                <?php if ($google_link): ?>
                                                    <a href=" <?=$google_link?>">
                                                        <img src="<?=base_url().'assets/google_link.png'?>" width="50" alt="orderlist_googlelink">
                                                    </a>
                                                <?php endif ?> 

                                            </span>
                                        </li>
                                        <li>
                                            <strong>Purchase Date</strong>
                                            <span>
                                                <?=date('d M, Y h:i a', strtotime($items['created_at']))?>
                                            </span>
                                        </li>
                                    </ul>
                                    <div class="donat_nrecipt">
                                        <span>
                                        <?php  if($items['product_is_donate'] == "Y"): echo 'Donated'; endif; ?>
                                        </span>
                                        <a href="<?=base_url('/order/download-invoice/'.$items['order_id']);?>">Download Receipt</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                   </div>
                </section>
            </div>

    <?php include('common/mobile/footer.php'); ?>
    <?php include('common/mobile/menu.php'); ?>

        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>