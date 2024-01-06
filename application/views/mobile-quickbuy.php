<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea quick-buy-container">


            <!-- header section start -->
            <section class="inner_head">
                <a href="http://localhost/d-arabia/"><i class="icofont-rounded-left"></i></a>
                <h1>  Quick Buy </h1>
            </section>
            <!-- header section end -->


            <section class="menu_inner">
                <a class="quick-buy-link" href="<?=base_url('quick-summery-report');?>"><i class="icofont-law-document"></i> Quick Order Summery  </a>
                <a class="quick-buy-link" href="<?=base_url('quick-orders');?>"><i class="icofont-law-document"></i> Quick Orders  </a>
            </section>
            <div class="inner_pagedata">
                <section class="deals_homesec">
                    <h1>Explore Our Campaigns</h1>
                    <p>Pick Your Dream Gadget Campaign & Win it!.</p>

                    <?php if ($this->session->flashdata('error')): ?>
                        <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                    <?php endif; ?>
                    
                    <?php if ($this->session->flashdata('success')): ?>
                        <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                    <?php  endif; ?>

                    <div class="home_deialbox">
                        <div class="row">
                            <?php if($products): ?> 
                              <?php  foreach ($products as $key => $item):   //echo '<pre>'; print_r($products); die;//draw_date
                                $valid = $item['validuptodate'].' '.$item['validuptotime'].':0';
                                $today = date('Y-m-d H:i:s');
                                if(strtotime($valid) > strtotime($today)): ?>
                                     <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="deal_repeatrow">
                                                <!---->
                                                <div class="deailboxrow_1">
                                                    <div class="deal_sold">
                                                        <!-- sales percentage start -->
                                                         <?php if($item['commingSoon'] == 'N'): ?>
                                                            <?php
                                                                  $soldout_qty = $item['target_stock'] - $item['stock'];

                                                                  $soldout =  $soldout_qty * 100 / $item['target_stock'];
                                                                    // when soldout the staging sold out percentage.
                                                                  if($soldout < $item['sale_percentage']):
                                                                     $soldout = $item['sale_percentage'];
                                                                  endif;
                                                                    // when soldout the staging sold out percentage.
                                                                  if($soldout > $item['sale_percentage_final'] && !empty($item['sale_percentage_final'])):
                                                                     $soldout = $item['sale_percentage_final'];
                                                                  endif;
                                                            ?>

                                                            <div class="deal_sold">
                                                                <div class="progress blue">
                                                                    <div class="progress blue">
                                                                        <div class="chart" data-percent="<?=number_format($soldout,0)?>" ><?=number_format($soldout,0)?>%</div>
                                                                    </div>
                                                                </div>
                                                                Sold
                                                            </div>

                                                        <?php endif; ?>
                                                        <!-- sales percentage end -->


                                                        <!-- coming soon start -->
                                                        <?php if($item['commingSoon'] != 'N'): ?>
                                                          <i class="deal_coingsoon">Coming Soon</i>
                                                        <?php endif; ?>
                                                        <!-- coming soon endif -->
                                                    </div>

                                                    <div class="deal_nameprice">
                                                        <b>Buy <?=stripslashes($item['title'])?></b>
                                                    </div>
                                                    <div class="deal_share">
                                                        <a href="javascript:void(0);"><i class="icofont-share"></i></a>
                                                    </div>
                                                </div>
                                                <!---->
                                                <div class="deailboxrow_2">
                                                    <div class="deal_prod">
                                                        <a href="javascript:void(0);">
                                                            <?php $data2 = $this->geneal_model->getParticularDataByParticularField('prize_image','da_prize', 'product_id', $item['products_id']);  ?>
                                                            <?php if($item['product_image']): ?>
                                                                <img src="<?=base_url().$item['product_image']?>" class="img-responsive"  onclick="show_product(<?=$key;?>)" alt="<?=$item['product_image_alt']?>">
                                                            <?php else: ?>
                                                                <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-responsive" alt="<?=$item['product_image_alt']?>">
                                                            <?php endif; ?>
                                                        </a>
                                                    </div>
                                                    <div class="deal_win">
                                                        <h4>Win!</h4>
                                                        <p>
                                                             <?php if($item['prizeDetails'][0]['prize1'] != 0 && $item['prizeDetails'][0]['prize2'] != 0 ): ?>

                                                            <?php 
                                                            $string = $item['prizeDetails'][0]['prize1'];
                                                                        // Extract the number from the string
                                                            $number = preg_replace('/[^0-9]/', '', $string);

                                                                        // Convert the number to a formatted string with commas
                                                            $formattedNumber = number_format($number);

                                                                        // Replace the original number in the string with the formatted number
                                                            $result = str_replace($number, $formattedNumber, $string);

                                                            ?>
                                                            <p>
                                                                1<sup>st</sup> Prize - <?=$result;?> AED
                                                            </p>
                                                            <?php if($item['prizeDetails'][0]['prize2'] != 0 ): ?>
                                                                <?php 
                                                                $string = $item['prizeDetails'][0]['prize2'];
                                                                            // Extract the number from the string
                                                                $number = preg_replace('/[^0-9]/', '', $string);

                                                                            // Convert the number to a formatted string with commas
                                                                $formattedNumber = number_format($number);

                                                                            // Replace the original number in the string with the formatted number
                                                                $result = str_replace($number, $formattedNumber, $string);

                                                                ?>
                                                                <p>
                                                                    2<sup>nd</sup> Prize - <?=$result;?> AED
                                                                </p>
                                                            <?php endif; ?>
                                                            <?php if($item['prizeDetails'][0]['prize3'] != 0 ): ?>
                                                                <?php 
                                                                $string = $item['prizeDetails'][0]['prize3'];
                                                                            // Extract the number from the string
                                                                $number = preg_replace('/[^0-9]/', '', $string);

                                                                            // Convert the number to a formatted string with commas
                                                                $formattedNumber = number_format($number);

                                                                            // Replace the original number in the string with the formatted number
                                                                $result = str_replace($number, $formattedNumber, $string);

                                                                ?>
                                                                <p>
                                                                    3<sup>rd</sup> Prize - <?=$result;?> AED
                                                                </p>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                                <?php 
                                                                    $string = $item['prizeDetails'][0]['title'];
                                                                    // Extract the number from the string
                                                                    $number = preg_replace('/[^0-9]/', '', $string);

                                                                    // Convert the number to a formatted string with commas
                                                                    $formattedNumber = number_format($number);

                                                                    // Replace the original number in the string with the formatted number
                                                                    $result = str_replace($number, $formattedNumber, $string);

                                                                ?>
                                                            <p> <?=$result;?> </p>
                                                        <?php endif; ?>
                                                        </p>

                                                         <?php 
                                                            $string = $item['adepoints'];
                                                            // Extract the number from the string
                                                            $number = preg_replace('/[^0-9]/', '', $string);

                                                            // Convert the number to a formatted string with commas
                                                            $formattedNumber = number_format($number);

                                                            // Replace the original number in the string with the formatted number
                                                            $result = str_replace($number, $formattedNumber, $string);

                                                        ?>

                                                        <p>Price: <b>AED <?=$result;?></b></p>
                                                    </div>
                                                    <div class="deal_cash">
                                                        <a href="javascript:void(0);">
                                                             <?php if($item['prizeDetails'][0]['prize_image']): ?>
                                                                <img src="<?=base_url().$item['prizeDetails'][0]['prize_image']?>" class="dolleres " onclick="show_prize(<?=$key;?>)"  alt="product_img">
                                                            <?php else: ?>
                                                                <img src="<?=base_url()?>/assets/productsImage/doller.png" class="dolleres"  alt="product_img">
                                                            <?php endif; ?>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="deailboxrow_3">
                                                    <div class="deailboxbtns">
                                                        <?php $products_id = base64_encode($item['products_id']);  ?>
                                                        <a href="<?=base_url('quick-buy-ticket/?pid='.$products_id.'&type=buy_voucher')?>" class="buy_voucher">Buy Voucher</a>
                                                        <a href="<?=base_url('quick-buy-ticket/?pid='.$products_id.'&type=buy_product')?>" class="buy_product default-btn" >Buy Product</a>
                                                    </div>
                                                </div>
                                                <div class="deailboxrow_4">
                                                    <div class="deal_drawon">
                                                        Draw on <br>
                                                        <?=date('d M, Y', strtotime($item['draw_date']))?>
                                                    </div>
                                                </div>
                                        </div>
                                     </div>
                                <?php endif;  ?>
                              <?php endforeach;  ?>
                            <?php endif;  ?>
                        </div>
                    </div>
                </section>
            </div>

            <div class="pod_imgpop">
                <div class="pod_imgpopbox">
                    <img src="img/pen.jpg" alt="pen" />
                </div>
            </div>

         

        <?php include('common/mobile/footer.php'); ?>
        <?php include('common/mobile/menu.php'); ?>


        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>
    <!-- Country code popup Ui start -->
    <?php include('common/mobile/countrycode-list.php') ?>
<!-- Country code popup Ui end -->