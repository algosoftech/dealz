<!Doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> -->
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<?php include('common/head.php') ?>
<style>
.winner-btn {
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

.winner-btn:hover {
    background: #e72d2e;
    color: #ffffff;
}
</style>
</head>
<body>
<?php include('common/header.php') ?>
<?php if($homeSlider): ?>
    <div class="index">
<div class="main-slider">
    <div class="full-container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 padding-zero">
                <div class="owl-slider">
                    <div id="main-carousel" class="owl-carousel">
                        <?php if($homeSlider): foreach ($homeSlider as $key => $slider) { ?>
                        <div class="item">
                            <img src="<?=base_url().$slider['image']?>" class="img-fluid" alt="main-slider">
                            <div class="slider-text">
                                <div class="container">
                                    <h1><?=@$slider['slider_description']?></h1>
                                </div>
                            </div>
                        </div>
                        <?php } endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <?php if($videoSlider): ?>
        <video preload="" autoplay="" muted="" loop="" style="width:100%">
            <source src="<?=base_url()?>/admin/<?=$videoSlider['video_url']?>" type="video/mp4" >   
        </video>
    <?php endif; ?>
<?php endif; ?>


<!-- <div class="coming-soonhome">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h3 class="default-heading" style="text-align:center;">Closing soon</h3>
                <p style="text-align:center;">Your Lucky Coupon is waiting, let’s have a try</p>
                <div class="owl-slider">
                    
                    <div id="closing-soon" class="owl-carousel">
                        <?php if($closing_soon){ foreach ($closing_soon as $key => $items) { ?>
                            <?php 
                            $valid = $items['validuptodate'].' '.$items['validuptotime'].':0';
                            $today = date('Y-m-d H:i:s');
                            if(strtotime($valid) > strtotime($today)){
                         ?>
                         
                        <div class="item">
                        
                            <div class="slide-product-box">
                                <?php if($items['prizeDetails'][0]['prize_image'] <> ''): ?>
                                    <img src="<?=base_url().$items['prizeDetails'][0]['prize_image']?>" style="width:25%;border: 1px solid #e1e1e1;padding: 4px;border-radius: 7px;">
                                <?php else: ?>
                                    <img src="<?=base_url()?>assets/productsImage/doller.png" style="width:25%;border: 1px solid #e1e1e1;padding: 4px;border-radius: 7px;">
                                <?php endif; ?>
                                <a href="<?=base_url('product-details/'.$items['title_slug'])?>">
                                <?php if(isset($items['soldout_status']) && $items['soldout_status'] == 'Y'){ ?> 
                                <div class="coupen-img">
                                    <div class="">
                                    <?php
                                    $avlstock = $items['stock'] * 100 / $items['totalStock'];
                                    // $remStick = 100 - $avlstock;
                                    if($item['sale_percentage']):
                                        $remStick = $item['sale_percentage'];
                                    else:
                                         $remStick = 100 - $avlstock;
                                    endif;
                                    ?>
                                    <div class="box">
                                        <div class="chart1" data-percent="<?=number_format($remStick,0)?>" ><?=number_format($remStick,0)?>%</div>
                                    </div>
                                </div>
                                <h2 class="textes">Sold</h2>
                                </div>
                                <?php }else{ ?>
                                    <div class="coupen-img">
                                        <div class="">
                                            <div class="box">
                                            <div class="chartdemo easyPieChart" style="width: 60px; height: 60px; line-height: 70px;"></div>
                                            </div>
                                        </div>
                                        <h2 class="textes">&nbsp;</h2>
                                    </div>
                                <?php } ?>
                                
                                <div class="product-img">
                                    <?php 
                                    //$data2 = $this->geneal_model->getParticularDataByParticularField('prize_image','da_prize', 'product_id', $items['products_id']);
                                     /* if($data2['prize_image']){ ?>
                                    <img src="<?=base_url().$data2['prize_image']?>" class="img-fluid" alt="<?=$items['product_image_alt']?>">
                                    <?php }else{ ?>
                                        <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-fluid" alt="<?=$items['product_image_alt']?>">
                                    <?php } */ ?>
                                    <?php
                                    if($items['product_image']){ ?>
                                        <img src="<?=base_url().$items['product_image']?>" class="img-fluid" alt="<?=$items['product_image_alt']?>">
                                    <?php }else{ ?>
                                            <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-fluid" alt="<?=$items['product_image_alt']?>">
                                    <?php } ?>
                                </div>
                                </a>
                                <div class="product-des">
                                    <p style="color: #181818;">Buy <?=stripslashes($items['title'])?></p>
                                    <h2><i style="color: #d12a2b;font-weight: 900;">Win!</i></h2>
                                    <h4><?=stripslashes($items['prizeDetails'][0]['title'])?></h4>
                                <!--    <h4><?=stripslashes($items['soldout_status'][0]['title'])?></h4>
                                    <input type="hidden" name="products_id" id="products_id" value="<?=$items['products_id']?>">
                                
                                    <h5>AED<?=$items['adepoints']?>.00</h5>
                                        -->
                                    <p>Max Draw Date : <?=date('M d, Y', strtotime($items['draw_date']))?></p>
                                    <?php
                                    if($this->session->userdata('DZL_USERID')){
                                        $wcon1['where'] = [ 
                                            'user_id'   =>  (int)$this->session->userdata('DZL_USERID'),
                                            'id' => (int)$items['products_id'] 
                                        ];
                                        $check = $this->geneal_model->getData2('count','da_cartItems',$wcon1);
                                        if($check == 0){ ?>
                                            <?php if($items['commingSoon'] == 'N'){ ?>
                                            <button class="default-btn addremove add_cart" data-id='<?=$items['products_id']?>' <?php if($items['color_size_details'][0]['color']){ ?> data-color="<?=$items['color_size_details'][0]['color']?>" <?php } ?> 
                                                <?php if($items['color_size_details'][0]['S'] == 'Y'){ ?> data-size="S" 
                                                <?php }elseif($items['color_size_details'][0]['M'] == 'Y'){ ?> data-size="M"  
                                                <?php }elseif($items['color_size_details'][0]['L'] == 'Y'){ ?> data-size="L"
                                                <?php }elseif($items['color_size_details'][0]['XL'] == 'Y'){ ?> data-size="XL"    
                                                <?php }elseif($items['color_size_details'][0]['XXL'] == 'Y'){ ?> data-size="XXL"  
                                                <?php } ?> id="add_cart">Add <i class="fas fa-shopping-cart" aria-hidden="true"></i></button>
                                                <?php } ?>
                                        <?php }else{ ?>
                                            <?php if($items['commingSoon'] == 'N'){ ?>
                                            <button class=" addremove edit_cart" data-id='<?=$items['products_id']?>' <?php if($items['color_size_details'][0]['color']){ ?> data-color="<?=$items['color_size_details'][0]['color']?>" <?php } ?> 
                                                <?php if($items['color_size_details'][0]['S'] == 'Y'){ ?> data-size="S" 
                                                <?php }elseif($items['color_size_details'][0]['M'] == 'Y'){ ?> data-size="M"  
                                                <?php }elseif($items['color_size_details'][0]['L'] == 'Y'){ ?> data-size="L"
                                                <?php }elseif($items['color_size_details'][0]['XL'] == 'Y'){ ?> data-size="XL"    
                                                <?php }elseif($items['color_size_details'][0]['XXL'] == 'Y'){ ?> data-size="XXL"  
                                                <?php } ?> id="add_cart">Added To Cart <i class="fas fa-shopping-cart" aria-hidden="true"></i></button>
                                                <?php } ?>
                                        <?php }
                                    }else{ 
                                        if(!empty($this->cart->contents())){
                                            $check = $this->geneal_model->checkCartItems($items['products_id']);    
                                        }else{
                                            $check = 0;
                                        }
                                        if($check == 1){ //echo $check; ?>
                                        <?php if($items['commingSoon'] == 'N'){ ?>
                                            <button class="addremove edit_cart" data-id='<?=$items['products_id']?>' <?php if($items['color_size_details'][0]['color']){ ?> data-color="<?=$items['color_size_details'][0]['color']?>" <?php } ?> 
                                                <?php if($items['color_size_details'][0]['S'] == 'Y'){ ?> data-size="S" 
                                                <?php }elseif($items['color_size_details'][0]['M'] == 'Y'){ ?> data-size="M"  
                                                <?php }elseif($items['color_size_details'][0]['L'] == 'Y'){ ?> data-size="L"
                                                <?php }elseif($items['color_size_details'][0]['XL'] == 'Y'){ ?> data-size="XL"    
                                                <?php }elseif($items['color_size_details'][0]['XXL'] == 'Y'){ ?> data-size="XXL"  
                                                <?php } ?> id="add_cart"> Added To Cart <i class="fas fa-shopping-cart" aria-hidden="true"></i></button> 
                                                <?php } ?>
                                        <?php }else{ //echo $check; ?>
                                            <?php if($items['commingSoon'] == 'N'){ ?>
                                            <button class="default-btn addremove add_cart" data-id='<?=$items['products_id']?>' <?php if($items['color_size_details'][0]['color']){ ?> data-color="<?=$items['color_size_details'][0]['color']?>" <?php } ?> 
                                                <?php if($items['color_size_details'][0]['S'] == 'Y'){ ?> data-size="S" 
                                                <?php }elseif($items['color_size_details'][0]['M'] == 'Y'){ ?> data-size="M"  
                                                <?php }elseif($items['color_size_details'][0]['L'] == 'Y'){ ?> data-size="L"
                                                <?php }elseif($items['color_size_details'][0]['XL'] == 'Y'){ ?> data-size="XL"    
                                                <?php }elseif($items['color_size_details'][0]['XXL'] == 'Y'){ ?> data-size="XXL"  
                                                <?php } ?> id="add_cart"> Add <i class="fas fa-shopping-cart" aria-hidden="true"></i></button>
                                                <?php } ?>
                                    <?php } }   ?>

                                </div>
                            </div>
                        </div>
                        <?php } } } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="home-addblock">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="ad-img">
                    <!-- <img src="<?=base_url().$homeBanner[0]['image']?>" class="img-fluid" alt=""> -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="explore-campaign">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h3 class="default-heading">Explore Our Campaigns </h3>
                <p>Pick Your Dream Gadget Campaign & Win it!.</p>
                
                <?php if ($this->session->flashdata('error')): ?>
                    <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success')): ?>
                    <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                <?php  endif; ?>
                
            </div>
        </div>



        <div class="row">
            <?php if($products){ foreach ($products as $key => $item) {  //echo '<pre>'; print_r($products); die;//draw_date
                $valid = $item['validuptodate'].' '.$item['validuptotime'].':0';
                $today = date('Y-m-d H:i:s');
                if(strtotime($valid) > strtotime($today)){
             ?>

        
                <div class="col-lg-6 col-md-12 col-12 ">
                    <div class="explore-product-box bexplore_boxess">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-2 col-12 ">

                                    <?php if($item['commingSoon'] != 'N'){ ?>
                                        <timer>
                                            <div class="coming_soon_product">
                                            COMING SOON 
                                            </div>
                                        
                                        </timer>
                                    <?php } ?>


                                <div class="product-img">
                                    <?php if(isset($item['soldout_status']) && $item['soldout_status'] == 'Y'){ ?> 
                                
                                        <div class="">
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
                                            <div class="box explore_boxes">
                                                <div class="chart" data-percent="<?=number_format($soldout,0)?>" ><?=number_format($soldout,0)?>%</div>
                                            </div>
                                        </div>
                                        <h2 class="textes heading_big" >Sold</h2>
                                    <?php } ?>
                                    <!-- <img src="<?=base_url().$item['product_image']?>" class="img-fluid" alt=""> -->
                                </div>

                                <?php $data2 = $this->geneal_model->getParticularDataByParticularField('prize_image','da_prize', 'product_id', $item['products_id']); ?>

                                <?php if($item['product_image']){  ?>
                                    <img src="<?=base_url().$item['product_image']?>" class="img-fluid product_img my_img" onclick="show_product(<?=$key;?>)"   alt="<?=$item['product_image_alt']?>">
                                <?php }else{ ?>
                                    <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-fluid" alt="<?=$item['product_image_alt']?>">
                                <?php } ?>
                                
                                <div id="show_product_<?=$key?>" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                           
                                            <div class="modal-body">
                                                <?php if($item['product_image']){  ?>
                                                    <img src="<?=base_url().$item['product_image']?>" class="img-fluid product_img my_img" onclick="show_product(<?=$key;?>)"   alt="<?=$item['product_image_alt']?>">
                                                <?php }else{ ?>
                                                    <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-fluid" alt="<?=$item['product_image_alt']?>">
                                                <?php } ?>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-8 col-12 text-align">
                                <div class="product-des">
                                    <?php if ($item['title']): ?>
                                        <h5  class="product__content">Buy<span class="buy_text" style="font-weight:700;"> <?=stripslashes($item['title'])?></span> </h5>
                                    <?php endif ?>
                                    <h2><i class="winner__text">Win!</i></h2>
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
                                        <h5  class="product__content"><span>1<sup>st</sup> Prize - <?=$result;?> AED </span></h5>

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
                                            <h5  class="product__content"><span>2<sup>nd</sup> Prize - <?=$result?> AED </span></h5>
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

                                            <h5  class="product__content"><span>3<sup>rd</sup> Prize - <?=$result;?> AED
                                        </span></h5>
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

                                        <h5 class="product__content"><span><?=$result;?></span></h5>
                                    <?php endif; ?>
                                    <h5  class="product__content"><span >Price : </span><span style="color: #0d9e51; font-weight: 700;" class="price___content">AED <?=$item['adepoints']?>.00</span></h5>
                                    <?php /* ?><p>Max Draw Date : <?=date('d-M-Y', strtotime($item['draw_date']))?></p>
                                    <div class="countdown" data-Date='<?=date('Y-m-d H:i:s', strtotime($item['draw_date']))?>'><?php */ ?>
                                
                                </div>
                                
                                <div class="countdown" data-Date='<?=date('Y-m-d H:i:s', strtotime($item['validuptodate'].' '.$item['validuptotime']))?>'>
                                     <!-- <p><?=date('F d, Y', strtotime($item['draw_date']))?></p> -->
                                </div>

                                <div class="qucikbuy">
                                    <a href="<?=base_url('/quick-buy/'.$item['title_slug'])?>">
                                        Quick Buy
                                    </a>
                                </div>
                                   
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-2 col-12 align_leftes">
                                <div class="hearts">
                                    <!-- <a href="javascript:void();" name="wishlist" data-id="<?=$item['products_id']?>" class="heart_icon">
                                        <?php 
                                            $prowhere['where']              =   array('users_id'=>(int)$this->session->userdata('DZL_USERID'),'product_id'=>(int)$item['products_id']);
                                            $prodData                       =   $this->common_model->getData('single','da_wishlist',$prowhere);
                                            if($prodData):if($prodData['wishlist_product'] <> 'Y'):?>
                                                <i class="far fa-heart heart_icon" title="Mark Favourite"></i>
                                            <?php else:?>
                                                <i class="far fa-heart heart_icon active" title="Remove from Favourite"></i>
                                            <?php endif;?>
                                        <?php else:?>
                                            <i class="far fa-heart heart_icon " title="Mark Favourite"></i>
                                        <?php endif;?>
                                    </a> -->
                                    <?php 
                                        if($this->session->userdata('DZL_USERID')): 
                                           if($this->session->userdata('DZL_USERSTYPE') == 'Users' && $this->session->userdata('DZL_USERS_REFERRAL_CODE')):
                                            $productShareUrl  = generateProductShareUrl($item['products_id'],$this->session->userdata('DZL_USERID'),$this->session->userdata('DZL_USERS_REFERRAL_CODE'));
                                        ?>
                                            <a style="margin-top: 0%;" href="javascript:void(0);" class="share" data-toggle="modal" data-target="#shareModal<?php echo $item['products_id']; ?>"><i class="fa fa-share-alt" title="Share Campaigns" aria-hidden="true"></i></a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="shareModal<?php echo $item['products_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999;margin-top: 10%;">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel"><?php echo lang('SHARE_POPUP_HEADING'); ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <div class="sociallinks">
                                                        <ul>
                                                            <li class="social-share facebook"><i class="fab fa-facebook-f" aria-hidden="true" alt="Share on Facebook"></i><span style="padding-left:10px;">Share on Facebook</span></li>
                                                            <li class="social-share twitter"><i class="fab fa-twitter" aria-hidden="true"  alt="Share on Twitter"></i><span style="padding-left:10px;">Share on Twitter</span></li>
                                                            <li class="social-share linkedin"><i class="fab fa-linkedin" aria-hidden="true"  alt="Share on LinkedIn"></i><span style="padding-left:10px;">Share on LinkedIn</span></li>
                                                            <li class="social-share google"><i class="fab fa-google"></i><span style="padding-left:10px;"  alt="Share on Google">Share on Google</span></li>
                                                            <li class="social-share whatsapp"><i class="fab fa-whatsapp"></i><span style="padding-left:10px;"  alt="Share on Whatsapp">Share on Whatsapp</span></li>
                                                        </ul>
                                                    </div>
                                                    <input type="hidden" id="copyShareInput<?php echo $item['products_id']; ?>" class="copyShareUrlInput" value="<?php echo $productShareUrl; ?>" style="width:100%">
                                                    <?php /* ?>
                                                    <input type="text" id="copyShareInput<?php echo $item['products_id']; ?>" class="copyShareUrlInput" value="<?php echo $productShareUrl; ?>" style="width:100%">
                                                    <br>
                                                    <button class="copyShareUralToClipBoard">Copy url</button>
                                                    <?php */ ?>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                        <?php else: ?>
                                            <a style="margin-top: 10%;" href="javascript:void(0);" class="share"><i class="fa fa-share-alt" title="Share Campaigns" aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a style="margin-top: 10%;" href="javascript:void(0);" class="share userLoginError"><i class="fa fa-share-alt" title="Share Campaigns" aria-hidden="true"></i></a>
                                    <?php endif; ?>


                                    <?php if($item['prizeDetails'][0]['prize_image']): ?>
                                        <img src=   "<?=base_url().$item['prizeDetails'][0]['prize_image']?>" class="dolleres " onclick="show_prize(<?=$key;?>)"  alt="product_img">
                                         
                                    <?php else: ?>
                                        <img src=   "<?=base_url('/assets/img/NO_IMAGE.jpg')?>" class="dolleres"  alt="prize">
                                    <?php endif; ?>
                                    
                                    <div id="show_prize_<?=$key?>" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                               
                                                <div class="modal-body">
                                                    <?php if($item['prizeDetails'][0]['prize_image']): ?>
                                                        <img src=   "<?=base_url().$item['prizeDetails'][0]['prize_image']?>" class="dolleres " onclick="show_prize(<?=$key;?>)"  alt="product_img">
                                                         
                                                    <?php else: ?>
                                                        <img src=   "<?=base_url('/assets/img/NO_IMAGE.jpg')?>" class="dolleres"  alt="prize">
                                                    <?php endif; ?>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            
                            </div>

                        <div class="row calendar_view">
                          <div class="col-md-6 col-6">
                              <?php if($item['commingSoon'] == 'N' && $item['is_show_closing'] == 'Show'): ?>
                                <div class="calendar_timer">
                                    <img src="assets/img/schedule.png" alt="calendar">
                                    <div class="calendar">
                                    <p>Draw on:</p>
                                    <h1><?=date('d M, Y', strtotime($item['draw_date']))?></h1>
                                    </div>
                                </div>
                              <?php endif; ?>
                          </div>

                          <div class="col-md-6 col-6">
                            <ul class="explore_button">
                                <li>
                                    <?php
                                if($this->session->userdata('DZL_USERID')){
                                    $wcon1['where'] = [ 
                                        'user_id'   =>  (int)$this->session->userdata('DZL_USERID'),
                                        'id' => (int)$item['products_id'] 
                                    ];
                                    $check = $this->geneal_model->getData2('count','da_cartItems',$wcon1);
                                    if($check == 0){ ?>
                                        <?php if($item['color_size_details'][0]['color'] || $item['color_size_details'][0]['S'] ||$item['color_size_details'][0]['M'] || $item['color_size_details'][0]['L'] || $item['color_size_details'][0]['XL'] || $item['color_size_details'][0]['XXL']){ ?>
                                            <?php if($item['commingSoon'] == 'N'){ ?>
                                            <a class="default-btn addremove add_cart" href="<?=base_url('product-details/'.$item['title_slug'])?>" > Add To Cart 
                                                <!-- <i class="fas fa-shopping-cart" aria-hidden="true"></i> -->
                                            </a>
                                            <?php } ?>
                                        <?php }else{ ?> 
                                            <?php if($item['commingSoon'] == 'N'){ ?>
                                            <button class="default-btn addremove add_cart" data-id='<?=$item['products_id']?>' id="add_cart"> Add To Cart 
                                                <!-- <i class="fas fa-shopping-cart" aria-hidden="true"></i> -->
                                            </button>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php }else{ ?>
                                        <?php if($item['commingSoon'] == 'N'){ ?>
                                            <a href="<?=base_url('shopping-cart')?>">
                                        <button class=" addremove edit_cart" data-id='<?=$item['products_id']?>' id="add_cart"> Added To<i class="fas fa-shopping-cart" aria-hidden="true"></i></button>
                                        </a><?php } ?>
                                    <?php }
                                }else{ 
                                    if(!empty($this->cart->contents())){
                                        $check = $this->geneal_model->checkCartItems($item['products_id']); 
                                    }else{
                                        $check = 0;
                                    }
                                    if($check == 1){ //echo $check; ?>
                                    <?php if($item['commingSoon'] == 'N'){ ?>
                                        <button class="addremove edit_cart" data-id='<?=$item['products_id']?>' id="add_cart"> Added To <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                         <!-- <i class="fas fa-shopping-cart" aria-hidden="true"></i> -->
                                        </button>
                                        <?php } ?> 
                                    <?php }else{ //echo $check; ?>
                                        <?php if($item['color_size_details'][0]['color'] || $item['color_size_details'][0]['S'] ||$item['color_size_details'][0]['M'] || $item['color_size_details'][0]['L'] || $item['color_size_details'][0]['XL'] || $item['color_size_details'][0]['XXL']){ ?>
                                            <?php if($item['commingSoon'] == 'N'){ ?>
                                            <a class="default-btn addremove add_cart" href="<?=base_url('product-details/'.$item['title_slug'])?>" > Add To Cart 
                                                <!-- <i class="fas fa-shopping-cart" aria-hidden="true"></i> -->
                                            </a>
                                            <?php } ?>
                                        <?php }else{ ?> 
                                            <?php if($item['commingSoon'] == 'N'){ ?>
                                            <button class="default-btn addremove add_cart" data-id='<?=$item['products_id']?>' id="add_cart"> Add To Cart 
                                                <!-- <i class="fas fa-shopping-cart" aria-hidden="true"></i> -->
                                            </button>
                                            <?php } ?>
                                        <?php } ?>

                                        
                                <?php } }   ?>
                                </li>
                                <li>
                                    <a href="<?=base_url('product-details/'.$item['title_slug'])?>" class="white-btn  <?php echo $item['commingSoon'] != 'N' ? 'only-prize-detail':''; ?>    ">Prize Details</a>
                                </li>
                            </ul>
                          </div>
                            <?php if ( !empty($item['draw_date']) && !empty($item['draw_time']) && $item['countdown_status'] == 'Y' ): ?>
                              <div class="col-md-6 col-6">
                                    <div class="countdown" data-Date='<?=date('d M, Y', strtotime($item['draw_date'].' '.$item['draw_time']))?>'>
                                        <div class="running timer-hours"> 
                                        <timer>
                                            <div>
                                                <p class="days"></p>
                                                <h1>Days</h1>
                                            </div>

                                            <div>
                                                <p class="hours"></p>
                                                <h1>Hours</h1>
                                            </div>
                                            <div>
                                                <p class="minutes"></p>
                                                <h1>Mins</h1>
                                            </div>
                                            <div>
                                                <p class="seconds"></p>
                                                <h1>Secs</h1>
                                            </div>
                                        </timer>
                                        </div>
                                    </div>
                              </div>
                            <?php endif; ?>
                        </div>

                        
                        </div>
                        <div class="action-block">
                            <div class="row">
                                <div class="col-lg-9 col-md-9 col-12">
                                    
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            
        <?php } } } ?>
        
    </div>
    <!-- <div class="load-more-product">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <a href="<?=base_url('our-products')?>" class="color-white-btn">Load More Products</a>
                </div>
            </div>
    </div> -->
</div>
<div class="home-addblock">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="ad-img">
                    <!-- <img src="<?=base_url().$homeBanner[1]['image']?>" class="img-fluid" alt=""> -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php if($outOfStock){  ?>
    <div class="coming-soonhome sold-out">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h3 class="default-heading">Recently Sold Out </h3>
                <p>Wishing a good luck to All Our customers who Participated in our Weekly Draw.</p>
                <div class="owl-slider">
                    <div id="sold-out" class="owl-carousel">
                        <?php foreach ($outOfStock as $key => $oos) { ?>
                        <div class="item">
                            <div class="sold-out-box">

                                <div class="slide-product-box">
                                    <div class="img">
                                        <div class="sold_div">
                                            <img src="<?=base_url().$oos['prizeDetails'][0]['prize_image']?>"
                                                style="width:25%;border: 1px solid #e1e1e1;padding: 4px;border-radius: 7px;" alt="prizes_img">
                                        </div>
                                        <div class="product-img">
                                            <img src="<?=base_url().$oos['product_image']?>"
                                                class="img-fluid" alt="product_img">
                                        </div>
                                    </div>
                                    <div class="product-des">
                                        <p style="color: #181818;"><?=$oos['title']?></p>
                                        <h2><i>Win !</i></h2>

                                        <h4><?=$oos['prizeDetails'][0]['title']?></h4>
                                        <p>Draw Date:
                                            <?=isset($oos['draw_date'])?date('d M, Y', strtotime($oos['draw_date'])):''?>
                                        </p>
                                        <p style="height: 2px"></p>
                                        <?php
                            if(empty($this->session->userdata('DZL_USERID'))){ ?>
                                        <a href="<?=base_url('login/').base64_encode('check')?>"
                                            class="soldout-btn">Check your participation.
                                        </a>
                                        <?php }else{
                                $msg = $this->geneal_model->checkWinner($this->session->userdata('DZL_USERID'), $oos['products_id']); ?>
                                        <a href="#" class="soldout-btn"><?=$msg?></a>
                                        <?php } ?>
                                        <!-- <a href="#" class="soldout-btn">You are not a participant in this.</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }  ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php }  ?>
<?php if(!empty($winners)){ 
    $showWinners   = 'No';
    foreach ($winners as $key => $valuew) { 
        $validw = strtotime($valuew['announcedDate'].' '.$valuew['announcedTime'].':0');
        $todayw = strtotime(date('Y-m-d H:i:s'));
        if($validw < $todayw){
            $showWinners   = 'Yes';
        }
    }
    if($showWinners == 'Yes'){
?>
<div class="testimonial-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <h3 class="default-heading">Recent Winners</h3>
                <p class="text-center">Congratulation!!!, if you are not in this list? don't worry keep on try our next campaign, Best of luck !!!</p>
                <div class="text-right mt-2">
                    <a href="<?=base_url('winners-list');?>" class="text-cneter winner-btn" >View All</a>
                </div>
                <div>
                    <div id="testimonial" class="owl-carousel">
                        <?php 
                         foreach ($winners as $key => $value) { 
                            $valid = $value['announcedDate'].' '.$value['announcedTime'].':0';
                            $today = date('d M, Y H:i:s');
                            //if(strtotime($valid) < strtotime($today)){
                            if($key <=7):
                            ?>
                        <div class="testimonial-box">
                            <h4 style="margin-bottom: 2%;">Congratulations</h4>
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-12">
                                    <div class="product-img">

                                        <?php if(base_url($value['winners_image'])):  ?>
                                            <img src="<?=base_url().$value['winners_image']?>" class="img-fluid" alt="winner_img">
                                        <?php else: ?>
                                            <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-fluid" alt="winner"/>
                                        <?php endif ?>

                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-7 col-12">
                                    <div class="product-des">
                                        <h3 style="font-size: 22px;"><?=$value['name']?></h3>

                                        <?php 
                                                $string = $value['title'];;
                                                // Extract the number from the string
                                                $number = preg_replace('/[^0-9]/', '', $string);

                                                // Convert the number to a formatted string with commas
                                                $formattedNumber = number_format($number);

                                                // Replace the original number in the string with the formatted number
                                                $result = str_replace($number, $formattedNumber, $string);

                                        ?>

                                        <h5>Winner of <?=$result;?></h5>
                                        <?php if($value['country']): ?>
                                            <p>Country : <?=$value['country']?></p>
                                        <?php endif; ?>
                                        <p>Coupon no: <?=$value['coupon']?></p>
                                        <?php  
                                        $announced = $value['announcedDate'].' '.$value['announcedTime']
                                        ?>
                                        <p>Announced: <?=@date('d M, Y', strtotime($announced))?></p>
                                        <!-- <h6>AED <?=@number_format($value['adepoints'],2)?></h6> -->
                                    </div>


                                </div>
                            </div>
                        </div>
                        <?php endif; } /*}*/ ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } } ?>
</div>
<?php include('common/testimonial.php') ?>

<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>

<script>
    
$('#carouselExample').on('slide.bs.carousel', function (e) {

  
var $e = $(e.relatedTarget);
var idx = $e.index();
var itemsPerSlide = 4;
var totalItems = $('.carousel-item').length;

if (idx >= totalItems-(itemsPerSlide-1)) {
    var it = itemsPerSlide - (totalItems - idx);
    for (var i=0; i<it; i++) {
        // append slides to end
        if (e.direction=="left") {
            $('.carousel-item').eq(i).appendTo('.carousel-inner');
        }
        else {
            $('.carousel-item').eq(0).appendTo('.carousel-inner');
        }
    }
}
});


$('#carouselExample').carousel({ 
            interval: 2000
    });


$(document).ready(function() {
/* show lightbox when clicking a thumbnail */
$('a.thumb').click(function(event){
  event.preventDefault();
  var content = $('.modal-body');
  content.empty();
    var title = $(this).attr("title");
    $('.modal-title').html(title);        
    content.html($(this).html());
    $(".modal-profile").modal({show:true});
});

});

</script>
<!-- Animation Js -->
<script type="text/javascript" src="<?=base_url('assets/')?>countdownTimer/multi-countdown.js"></script>
<script>
    AOS.init({
      duration: 1200,
    })
</script>
<script>
    /* TOP Menu Stick
    --------------------- */
    var s = $("#sticker");
    var pos = s.position();                    
    $(window).scroll(function() {
        var windowpos = $(window).scrollTop();
        if (windowpos > pos.top) {
        s.addClass("stick");
        } else {
            s.removeClass("stick"); 
        }
    });
</script>
<!-- Main Slider Js -->
<script>
    jQuery("#main-carousel").owlCarousel({
      autoplay: true,
      loop: true,
      margin: 0,
      transitionStyle : "goDown",
      responsiveClass: true,
      autoHeight: true,
      autoplayTimeout: 7000,
      smartSpeed: 800,
      lazyLoad: false,
      nav: false,
      dots:true,
      responsive: {
        0: {
          items: 1
        },

        600: {
          items: 1
        },

        1024: {
          items: 1
        },

        1366: {
          items: 1
        }
      }
    });
</script>
<!-- Home Closing Soon Slider -->
<script>
//  $('#closing-soon').owlCarousel({
//     loop:true,
//     margin:10,
//  autoplay:true,
//     autoplayTimeout:1000,
//     autoplayHoverPause:true,
//     autoplaySpeed:5000,  
// stopOnHover : false,
     
//     responsiveClass:true,
//  nav:true,
//  dots:true,
//     responsive:{
//         0:{
//             items:1,
//             nav:true
//         },
//         600:{
//             items:3,
          
//         },
//         1024: {
//        items: 4,
//      },

//      1366: {
//        items: 4
//      }
//     }
// })
// $(document).ready(function(){
//     var owl = $(".owl-carousel");
//     owl.owlCarousel({
//         items: 1,
//         autoplay: true,
//         autoPlaySpeed: 5000,
//         autoPlayTimeout: 5000,
//         autoplayHoverPause: true
//     });
// });
$("#closing-soon").owlCarousel({
      autoPlay: true,
      lazyLoad: true,
      loop: true,
      margin: 20,
      autoPlayTimeout: 5000,
      smartSpeed: 800,
      dots: true,
      nav: true,
      responsive: {
        0: {
          items: 1
        },

        600: {
          items: 3
        },

        1024: {
          items: 4
        },

        1366: {
          items: 4
        }
      }
    });
    
</script>
<!-- Home Sold Out Slider -->
<script>
     $(document).ready(function(){
    jQuery("#sold-out").owlCarousel({
        autoplay: true,
      lazyLoad: true,
      loop: true,
      margin: 20,
      responsiveClass: true,
      autoHeight: true,
      autoplayTimeout: 5000,
      smartSpeed: 800,
      dots: true,
      nav: true,
      responsive: {
        0: {
          items: 1
        },

        600: {
          items: 3
        },

        1024: {
          items: 4
        },

        1366: {
          items: 4
        }
      }
    });
    });
</script>
<!-- Testimonial Slider -->
<script>
    var $owl = $('#testimonial');
    var $owl1 = $('#testimonial1');

    $owl.children().each( function( index ) {
      $(this).attr( 'data-position', index ); // NB: .attr() instead of .data()
    });

    $owl.owlCarousel({
      center: true,
      loop: true,
      dots:true,
      nav:true,
      responsive: {
        0: {
          items: 1
        },

        600: {
          items: 1
        },

        800: {
          items: 3
        },

        1366: {
          items: 3
        }
      }
    });

     $owl1.owlCarousel({
      center: true,
      loop: true,
      dots:true,
      nav:true,
      responsive: {
        0: {
          items: 1
        },

        600: {
          items: 1
        },

        800: {
          items: 5
        },

        1366: {
          items: 5
        }
      }
    });

    $(document).on('click', '.owl-item>div', function() {
      // see https://owlcarousel2.github.io/OwlCarousel2/docs/api-events.html#to-owl-carousel
      var $speed = 300;  // in ms
      $owl.trigger('to.owl.carousel', [$(this).data( 'position' ), $speed] );
    });
</script>
<!-- Header Dropdown -->
<script>
     $('.dropdown > .caption').on('click', function() {
        $(this).parent().toggleClass('open');
    });

    // $('.price').attr('data-currency', 'RUB');

    $('.dropdown > .list > .item').on('click', function() {
        $('.dropdown > .list > .item').removeClass('selected');
        $(this).addClass('selected').parent().parent().removeClass('open').children('.caption').html($(this).html());

        if ($(this).data("item") == "RUB") {
            console.log('RUB');
        } else if ($(this).data("item") == "UAH") {
            console.log('UAH');
        } else {
            console.log('USD');
        }
    });

    $(document).on('keyup', function(evt) {
        if ((evt.keyCode || evt.which) === 27) {
            $('.dropdown').removeClass('open');
        }
    });

    $(document).on('click', function(evt) {
        if ($(evt.target).closest(".dropdown > .caption").length === 0) {
            $('.dropdown').removeClass('open');
        }
    });
</script>
<script>
         $('.dropdown > .caption').on('click', function() {
            $(this).parent().toggleClass('open');
        });

        // $('.price').attr('data-currency', 'RUB');

        $('.dropdown > .list > .item').on('click', function() {
            $('.dropdown > .list > .item').removeClass('selected');
            $(this).addClass('selected').parent().parent().removeClass('open').children('.caption').html($(this).html());

            if ($(this).data("item") == "RUB") {
                console.log('RUB');
            } else if ($(this).data("item") == "UAH") {
                console.log('UAH');
            } else {
                console.log('USD');
            }         
        });

        $(document).on('keyup', function(evt) {
            if ((evt.keyCode || evt.which) === 27) {
                $('.dropdown').removeClass('open');
            }
        });

        $(document).on('click', function(evt) {
            if ($(evt.target).closest(".dropdown > .caption").length === 0) {
                $('.dropdown').removeClass('open');
            }
        });
</script>
<script>
    function makesvg(percentage, inner_text=""){

      var abs_percentage = Math.abs(percentage).toString();
      var percentage_str = percentage.toString();
      var classes = ""

      if(percentage < 0){
        classes = "danger-stroke circle-chart__circle--negative";
      } else if(percentage > 0 && percentage <= 30){
        classes = "warning-stroke";
      } else{
        classes = "success-stroke";
      }

     var svg = '<svg class="circle-chart" viewbox="0 0 33.83098862 33.83098862" xmlns="http://www.w3.org/2000/svg">'
         + '<circle class="circle-chart__background" cx="16.9" cy="16.9" r="15.9" />'
         + '<circle class="circle-chart__circle '+classes+'"'
         + 'stroke-dasharray="'+ abs_percentage+',100"    cx="16.9" cy="16.9" r="15.9" />'
         + '<g class="circle-chart__info">'
         + '   <text class="circle-chart__percent" x="17.9" y="15.5">'+percentage_str+'%</text>';

      if(inner_text){
        svg += '<text class="circle-chart__subline" x="16.91549431" y="22">'+inner_text+'</text>'
      }
      
      svg += ' </g></svg>';
      
      return svg
    }

    (function( $ ) {

        $.fn.circlechart = function() {
            this.each(function() {
                var percentage = $(this).data("percentage");
                var inner_text = $(this).text();
                $(this).html(makesvg(percentage, inner_text));
            });
            return this;
        };

    }( jQuery ));

    $(function () {
         $('.circlechart').circlechart();
    });
</script>

<!-- <script>
    $(document).ready(function(){
        $(document).on("click", "button[id='add_cart']", function () {
            //alert('add');
            var product_id = $(this).attr('data-id');
            var curobj = $(this);alert(product_id)
            curobj.html("Added to cart");
            //var product_id = $(this).data('id');

      //$('#'+product_id).addClass('hidden');
      
            var ur = '<?=base_url()?>';
            //alert(product_id);
            $.ajax({
                url : ur+ "shopping_cart/add",
                method: "POST", 
                data: {product_id: product_id},
                success: function(data){
                    //alert(data);
                    var A = '<b><i class="fas fa-shopping-cart"></i>('+data+')</b>'
                    //var B = '<button class="default-btn white-btn">Added to cart </button>'
                    $('#cartA').empty().append(A)
                    //$('#'+product_id).empty().append(B)
                }
            });

        });
    });
    
</script> -->

<script>
    $(document).ready(function(){
        $(document).on("click", "button[id='add_cart']", function () {
            var product_id = $(this).attr('data-id');
            var color = $(this).attr('data-color');
            var size = $(this).attr('data-size');
            
            var curobj = $(this);
            curobj.html('Added To <i class="fas fa-shopping-cart" aria-hidden="true"></i>');

            curobj.addClass('edit_cart');
            curobj.removeClass('add_cart');
      
            var ur = '<?=base_url()?>';
            $.ajax({
                url : ur+ "shopping_cart/add",
                method: "POST", 
                data: {product_id: product_id, fcolor: color, fsize:size},
                success: function(data){
                    //alert(data);
                    var A = '<b><i class="fas fa-shopping-cart"></i>('+data+')</b>'
                    //var B = '<button class="default-btn white-btn">Added to cart </button>'
                    $('#cartA').empty().append(A)
                    $('#cart-footer').empty().append('('+data+')')
                    //$('#'+product_id).empty().append(B)
                    $("#cart-footer").css("color", "#e72d2e");
                    alertMessageModelPopup('Added To Cart','success');

                }
            });
        });


    
        
});
</script>
<script type="text/javascript">

    function alertMessageModelPopup(message,type){  
    $.notify({
        message: message
    }, {
        type: type,
        allow_dismiss: false,
        label: 'Cancel',
        className: 'btn-xs btn-inverse',
        placement: {
            from: 'bottom',
            align: 'center'
        },
        delay: 2000,
        animate: {
            enter: 'animated fadeInRight',
            exit: 'animated fadeOutRight'
        },
        offset: {
            x: 30,
            y: 30
        }
    });
}
</script>
<script type="text/javascript">
  $(function() {
      $('.chart').easyPieChart({
        size: 70,
        barColor: "#c02728",
        
        lineWidth: 6,
        trackColor: "#98fb987d",
        lineCap: "circle",
        animate: 2000,
      });
    });
    $(function() {
      $('.chart1').easyPieChart({
        size: 70,
        barColor: "#c02728",
        
        lineWidth: 6,
        trackColor: "#98fb987d",
        lineCap: "circle",
        animate: 2000,
        backgroundColor: ['#fff']
      });
    });
</script>
<script>
    $(document).on('click','.copyShareUralToClipBoard',function(){
        /* Get the text field */
        var copyText = $(this).parent('.modal-body').find('.copyShareUrlInput');

        /* Select the text field */
        copyText.select();
        //copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText.val());

        /* Alert the copied text */
        //alert("Copied the text: " + copyText.val());
    });
    $(document).on('click','.userLoginError',function(){
        //alert('<?php echo lang('SHARE_LOGIN_ERROR'); ?>');
        window.location.href = '<?php echo base_url('login'); ?>';
    });
</script>
<script>
    $(document).ready(function () {
        $(document).on("click", "a[name='wishlist']", function () {
            var userId = '<?php echo $this->session->userdata('DZL_USERID')?>';
            if(userId == '')
            {
                //alert('Please login in order to add to wishlist');
                window.location.href = '<?php echo base_url('login'); ?>';
            }
            var product_id = $(this).attr('data-id');
            var curobj = $(this);
            var ur = '<?=base_url()?>';
            $.ajax({
                url: ur + 'add-to-wishlist',
                type: "POST",
                data: {
                    'product_id': product_id
                },
                cache: false,
                success: function (result) {
                    if (result == 'addedtowishlist') {
                        curobj.html('<i class="far fa-heart heart_icon active" title="Remove from Favourite"></i>');
                    } else if (result == 'removedfromwishlist') {
                        curobj.html('<i class="far fa-heart heart_icon" title="Mark Favourite"></i>');
                    }
                }
            });
        });
    });
</script>
<script type="text/javascript">
    function socialWindow(pageUrl,url) {
        /*
        $.ajax({
            type: 'post',
            url: FULLSITEURL+'check-share-limit',
            data: {shareurl:pageUrl},
            success: function(response){ 
                if(response == 'success'){
                    var left = (screen.width -570) / 2;
                    var top = (screen.height -570) / 2;
                    var params = "menubar=no,toolbar=no,status=no,width=570,height=570,top=" + top + ",left=" + left;  window.open(url,"NewWindow",params);
                } else {
                    alert('You have consumed your share limit for the product');
                }
            }
        });
        */
        var left = (screen.width -570) / 2;
        var top = (screen.height -570) / 2;
        var params = "menubar=no,toolbar=no,status=no,width=570,height=570,top=" + top + ",left=" + left;  window.open(url,"NewWindow",params);
    }
    $(document).on('click','.social-share.facebook',function(){
        var shareUrl =  $(this).closest('.sociallinks').next('.copyShareUrlInput').val();
        var pageUrl = encodeURIComponent(shareUrl);
        var url = "https://www.facebook.com/sharer.php?u=" + pageUrl;
        socialWindow(shareUrl,url);
    });
    $(document).on('click','.social-share.twitter',function(){
        var shareUrl =  $(this).closest('.sociallinks').next('.copyShareUrlInput').val();
        var pageUrl = encodeURIComponent(shareUrl);
        var tweet = encodeURIComponent($("meta[property='og:description']").attr("Dealzarabia product share"));
        var url = "https://twitter.com/intent/tweet?url=" + pageUrl + "&text=" + tweet;
        socialWindow(shareUrl,url);
    });
    $(document).on('click','.social-share.linkedin',function(){
        var shareUrl =  $(this).closest('.sociallinks').next('.copyShareUrlInput').val();
        var pageUrl = encodeURIComponent(shareUrl);
        var url = "https://www.linkedin.com/shareArticle?mini=true&url=" + pageUrl;
        socialWindow(shareUrl,url);
    });
    $(document).on('click','.social-share.google',function(){
        var shareUrl =  $(this).closest('.sociallinks').next('.copyShareUrlInput').val();
        var pageUrl = encodeURIComponent(shareUrl);
        var url = 'https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=&su=Dealzarabia+product+share&body='+pageUrl+'&ui=2&tf=1&pli=1';
        socialWindow(shareUrl,url);
    });
    $(document).on('click','.social-share.whatsapp',function(){
        var shareUrl =  $(this).closest('.sociallinks').next('.copyShareUrlInput').val();
        var pageUrl = encodeURIComponent(shareUrl);
        var url = "https://api.whatsapp.com/send?text=" + pageUrl;
        socialWindow(shareUrl,url);
    });
</script>
<script>

$(document).ready(function () {
    //add library for it  $('.picZoomer').picZoomer();
    $('.piclist li').on('click', function (event) {
        var $pic = $(this).find('img');
        $('.picZoomer-pic').attr('src', $pic.attr('src'));
    });
   
  var owl = $('#recent_post');
              owl.owlCarousel({
                margin:20,
                dots:false,
                nav: true,
                navText: [
                  "<i class='fa fa-chevron-left'></i>",
                  "<i class='fa fa-chevron-right'></i>"
                ],
                autoplay: true,
                autoplayHoverPause: true,
                responsive: {
                  0: {
                    items: 2
                  },
                  600: {
                    items:3
                  },
                  1000: {
                    items:5
                  },
                  1200: {
                    items:4
                  }
                }
  });    
  
        $('.decrease_').click(function () {
            decreaseValue(this);
        });
        $('.increase_').click(function () {
            increaseValue(this);
        });
        function increaseValue(_this) {
            var value = parseInt($(_this).siblings('input#number').val(), 10);
            value = isNaN(value) ? 0 : value;
            value++;
            $(_this).siblings('input#number').val(value);
        }

        function decreaseValue(_this) {
            var value = parseInt($(_this).siblings('input#number').val(), 10);
            value = isNaN(value) ? 0 : value;
            value < 1 ? value = 1 : '';
            value--;
            $(_this).siblings('input#number').val(value);
        }
    });
    
    
    function show_prize(key){
        
        $("#show_prize_"+key).modal('show');
        
    }
    
    function show_product(key){
        
        $("#show_product_"+key).modal('show');
        
    }
    
    

</script>

</body>
</html>
