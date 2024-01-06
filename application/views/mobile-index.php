<?php include('common/mobile/header.php') ?>

            <?php if($homeSlider):  ?>
                <section class="home_slider">
                    <div id="home_slidercarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <!-- Indicators/dots -->
                        <div class="carousel-indicators">
                          
                                <?php foreach ($homeSlider as $key => $slider): ?>
                                    <button type="button" data-bs-target="#home_slidercarousel" data-bs-slide-to="<?=$key;?>"  class="<?php if($key == 0): echo 'active'; endif;?>"></button>
                                <?php endforeach; ?>
                        </div>
                      
                        <!-- The slideshow/carousel -->
                        <div class="carousel-inner">
                                <?php foreach ($homeSlider as $key => $slider): ?>
                                  <div class="carousel-item <?php if($key == 0): echo 'active'; endif;  ?>" data-bs-interval="1500">
                                    <img src="<?=base_url().$slider['image']?>" alt="Banner" class="d-block w-100">
                                  </div>
                                <?php endforeach; ?>
                        </div>
                      </div>
                </section>
            <?php endif; ?>

            <?php if($videoSlider):  ?>
                <section class="home_slider">
                    <div id="home_slidercarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <!-- The slideshow/carousel -->
                        <div class="carousel-inner">
                          <div class="carousel-item active" >
                            <video preload="" autoplay="" muted="" loop="" style="width:100%">
                                <source src="<?=base_url()?>/admin/<?=$videoSlider['video_url']?>" type="video/mp4" >   
                            </video>
                          </div>
                        </div>
                      </div>
                </section>
            <?php endif; ?>

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
                            <!---->
                            <div class="deailboxrow_3">
                                <div class="qucikbuy">
                                    <a href="<?=base_url('/quick-buy/'.$item['title_slug'])?>">
                                        Quick Buy
                                    </a>
                                </div>
                            </div>
                            <!---->
                            <div class="deailboxrow_4">
                                <div class="deal_drawon">
                                    Draw on <br>
                                    <?=date('d M, Y', strtotime($item['draw_date']))?>
                                </div>
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
                         </div>

                    <?php } } } ?>
                    
                </div>
            </section>

            <section class="sold_outsec">
                <h3>Recently Sold Out</h3>
                <p>Wishing a good luck to All Our customers who Participated in our Weekly Draw.</p>
                <div class="sold_slider">
                    <div class="sold-slider">
                    <?php foreach ($outOfStock as $key => $oos) { ?>
                        <div class="sold_box">
                            <div class="slide-product-box">
                                <div class="img">
                                    <div class="sold_div">
                                        <img src="<?=base_url().$oos['prizeDetails'][0]['prize_image']?>" style="width:25%;border: 1px solid #e1e1e1;padding: 4px;border-radius: 7px;" alt="prizes_img">
                                    </div>
                                    <div class="product-img">
                                        <img src="<?=base_url().$oos['product_image']?>" class="img-fluid" alt="product_img">
                                    </div>
                                </div>
                                <div class="product-des">
                                    <p style="color: #181818;"><?=$oos['title']?></p>
                                    <h2><i>Win !</i></h2>

                                    <h4><?=$oos['prizeDetails'][0]['title']?></h4>
                                    <p>Draw Date: <?=isset($oos['draw_date'])?date('d M, Y', strtotime($oos['draw_date'])):''?> </p>
                                    <p style="height: 2px"></p>
                                    <?php if(empty($this->session->userdata('DZL_USERID'))){ ?>
                                        <a href="https://dealzarabia.com/login/" class="soldout-btn">Check your participation.
                                        </a> 
                                    <?php }else{ $msg = $this->geneal_model->checkWinner($this->session->userdata('DZL_USERID'), $oos['products_id']);  ?>
                                        <a href="#" class="soldout-btn"><?=$msg?></a>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </section>


            <?php if($winners):  ?>
                    <section class="home_info winner_cards">
                    <h3>Winners</h3>
                    <p>Congratulation!!!, If you are not in this list? don't worry keep on try our next campaign, Best of luck !!!</p>
                        <div class="slick-slider winner_card_slider">
                            <?php foreach ($winners as $key => $value):  ?>
                                <?php if($key <=7): ?>
                                <div class="slide">
                                    <div class="winnercard">
                                        <div class="winner_img">
                                            <?php if(base_url($value['winners_image'])):  ?>
                                                <img src="<?=base_url().$value['winners_image']?>" class="img-responsive" alt="winner_img">
                                            <?php else: ?>
                                                <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-responsive" alt="winner"/>
                                            <?php endif ?>
                                        </div>
                                        <div class="winner_txt">
                                            <h3>Congratulations</h3>
                                            <?php 
                                                $string = $value['title'];
                                                // Extract the number from the string
                                                $number = preg_replace('/[^0-9]/', '', $string);

                                                // Convert the number to a formatted string with commas
                                                $formattedNumber = number_format($number);

                                                // Replace the original number in the string with the formatted number
                                                $result = str_replace($number, $formattedNumber, $string);

                                            ?>

                                            <b>Winner of <?=$result;?></b>
                                            <h5><?=$value['name']?></h5>
                                            <span>
                                                <?php if($value['country']): ?>
                                                     Country : <?=$value['country']?> <br>
                                                <?php endif; ?>
                                                Coupon no: <?=$value['coupon'];?> <br>
                                                <?php $announced = $value['announcedDate'].' '.$value['announcedTime']; ?>
                                                Announced:   <?=@date('d M, Y', strtotime($announced));?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </section>
            <?php endif;  ?>

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