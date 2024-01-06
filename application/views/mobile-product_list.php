<?php include('common/mobile/header.php') ?>
           
            <section class="home_slider">
                <div id="home_slidercarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <!-- The slideshow/carousel -->
                    <div class="carousel-inner">
                      <div class="carousel-item active" >
                        <img src="assets/img/Product-banner.jpg" alt="Banner" class="d-block w-100">
                      </div>
                    </div>
                  </div>
            </section>
            
            <section class="deals_homesec">
                <h1>Explore Our Campaigns</h1>
                <p>Pick Your Dream Gadget Campaign & Win it!.</p>
                <div class="home_deialbox">
                    

                    <?php if($products){ foreach ($products as $key => $item) {  //echo '<pre>'; print_r($products); die;//draw_date
                        $valid = $item['validuptodate'].' '.$item['validuptotime'].':0';
                        $today = date('Y-m-d H:i:s');
                        if(strtotime($valid) > strtotime($today)){
                     ?>

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


                            <!-- campign detail section start -->
                            <div class="deal_nameprice">
                                <b>Buy <?=stripslashes($item['title'])?></b>


                                <?php 
                                    $string = $item['adepoints'];
                                    // Extract the number from the string
                                    $number = preg_replace('/[^0-9]/', '', $string);

                                    // Convert the number to a formatted string with commas
                                    $formattedNumber = number_format($number);

                                    // Replace the original number in the string with the formatted number
                                    $result = str_replace($number, $formattedNumber, $string);

                                ?>

                                <span>Price: <small>AED <?=$result;?></small></span>
                            </div>
                            <div class="deal_share">
                                <a href="javascript:void(0);"><i class="icofont-share"></i></a>
                            </div>

                            <?php if($this->session->userdata('DZL_USERID')): 
                                if($this->session->userdata('DZL_USERSTYPE') == 'Users' && $this->session->userdata('DZL_USERS_REFERRAL_CODE')):
                                 $productShareUrl  = generateProductShareUrl($item['products_id'],$this->session->userdata('DZL_USERID'),$this->session->userdata('DZL_USERS_REFERRAL_CODE'));
                            ?>
                                    <!--Share button popup-->
                                    <div class="share_media">
                                        <div class="share_fade"></div>
                                        <div class="share_block">
                                            <h3>Share <a href="javascript:void(0);" class="close_sharepop"><i class="icofont-close-line"></i></a></h3>

                                            <div class="share_nav sociallinks">
                                                <a href="javascript:void(0)" class="social-share facebook">
                                                    <i class="icofont-facebook"></i> Share on Facebook
                                                </a>
                                                <a href="javascript:void(0)" class="social-share twitter">
                                                    <i class="icofont-twitter"></i> Share on Twitter
                                                </a>
                                                <a href="javascript:void(0)" class="social-share linkedin">
                                                    <i class="icofont-linkedin"></i> Share on Linkedin
                                                </a>
                                                <a href="javascript:void(0)" class="social-share google">
                                                    <i class="icofont-google-plus"></i> Share on Google
                                                </a>
                                                <a href="javascript:void(0)" class="social-share whatsapp">
                                                    <i class="icofont-brand-whatsapp"></i> Share on Whatsapp
                                                </a>
                                            </div>
                                            <input type="hidden" id="copyShareInput<?php echo $item['products_id']; ?>" class="copyShareUrlInput" value="<?php echo $productShareUrl; ?>" style="width:100%">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                        <!---->
                        <div class="deailboxrow_2">
                            <div class="deal_prod">
                                <a href="javascript:void(0)">

                                    <?php $data2 = $this->geneal_model->getParticularDataByParticularField('prize_image','da_prize', 'product_id', $item['products_id']);  ?>
                                    
                                    <?php if($item['product_image']): ?>
                                        <img src="<?=base_url().$item['product_image']?>" class="img-responsive"  onclick="show_product(<?=$key;?>)" alt="<?=$item['product_image_alt']?>">
                                    <?php else: ?>
                                        <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-responsive" alt="<?=$item['product_image_alt']?>">
                                    <?php endif; ?>

                                </a>
                            </div>

                            <!-- prize section start -->
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
                            </div>
                            
                            <div class="deal_cash">
                                <a href="javascript:void(0)">
                                    <?php if($item['prizeDetails'][0]['prize_image']): ?>
                                        <img src="<?=base_url().$item['prizeDetails'][0]['prize_image']?>" class="dolleres " onclick="show_prize(<?=$key;?>)"  alt="product_img">
                                    <?php else: ?>
                                        <img src="<?=base_url()?>/assets/productsImage/doller.png" class="dolleres"  alt="product_img">
                                    <?php endif; ?>
                                </a>
                            </div>
                            <!-- prize section end -->

                        </div>
                        <!---->
                        <div class="deailboxrow_3">
                            <div class="deailboxbtns">
                              
                              <?php if($item['color_size_details'][0]['color'] || $item['color_size_details'][0]['S'] ||$item['color_size_details'][0]['M'] || $item['color_size_details'][0]['L'] || $item['color_size_details'][0]['XL'] || $item['color_size_details'][0]['XXL']): ?>
                                <a href="<?=base_url('product-details/'.$item['title_slug'])?>" data-id="<?=$item['products_id']?>" class="addcart_btn default-btn addremove">Add To Cart</a>
                              <?php elseif($item['commingSoon'] == 'N'): ?>
                                <a href="javascript:void(0);" class="addcart_btn default-btn addremove add_cart" data-id="<?=$item['products_id']?>" >Add To Cart</a>
                              <?php endif; ?>  
                                <a href="<?=base_url('product-details/'.$item['title_slug'])?>" class="prize_btn">PRIZE Details</a>
                            </div>
                        </div>
                        <!---->
                        <div class="deailboxrow_4">
                                <div class="deal_drawon">
                                    <?php if($item['commingSoon'] == 'N' && $item['is_show_closing'] == 'Show'): ?>
                                        Draw on <br>
                                        <?=date('M d, Y', strtotime($item['draw_date']))?>
                                    <?php endif; ?>
                                </div>

                            <?php if ( !empty($item['draw_date']) && !empty($item['draw_time']) && $item['countdown_status'] == 'Y' ): ?>
                                <div class="deal_lefttime">
                                    <div class="countdown" data-Date='<?=date('Y-m-d H:i:s', strtotime($item['draw_date'].' '.$item['draw_time']))?>'>
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
                        <!---->
                    </div>

                    <?php } } } ?>
                    
                </div>
            </section>

    <?php include('common/mobile/footer.php'); ?>
    <?php include('common/mobile/menu.php'); ?>
        

        <div class="pod_imgpop">
            <div class="pod_imgpopbox">
                <img src="img/pen.jpg" alt="pen" />
            </div>
        </div>

        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>
   

<script>
    $(document).ready(function(){

        $(".add_cart").on('click' , function(){

            var product_id = $(this).attr('data-id');
            var color = $(this).attr('data-color');
            var size = $(this).attr('data-size');
            
            var curobj = $(this);
            curobj.html('Added To Cart');

            curobj.addClass('edit_cart');
            curobj.removeClass('add_cart');

            var ur = '<?=base_url()?>';
            $.ajax({
                url : ur+ "shopping_cart/add",
                method: "POST", 
                data: {product_id: product_id, fcolor: color, fsize:size},
                success: function(data){
                    

                    $('.cart-button').empty();
                    var A = '<small>'+data+'</small><i class="icofont-cart-alt"></i>Cart';
                    $('.cart-button').empty().append(A);
                }
            });

        });


});
</script>
<script>
    $(document).on('click','.copyShareUralToClipBoard',function(){
        /* Get the text field */
        var copyText = $(this).parent('.share_block').find('.copyShareUrlInput');

        /* Select the text field */
        copyText.select();
        //copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText.val());

        /* Alert the copied text */
        //alert("Copied the text: " + copyText.val());
    });
     
</script>

<script type="text/javascript">
    function socialWindow(pageUrl,url) {
      
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