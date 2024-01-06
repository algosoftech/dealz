        <style>
            @media only screen and (min-width:768px){
                footer{
                    display: none;
                }

                .toggleswitch {
                    position: relative;
                    display: inline-block;
                    width: 35px;
                    height: 10px;
                    right: -13px;
                }
            }
        </style>
        <?php
            $url_save = $this->uri->segment(1);
            if($url_save == ''):
                $home = 'active';

            elseif($url_save == 'shopping-cart'):
                $cart = 'active';
            endif;

            $useragent=$_SERVER['HTTP_USER_AGENT'];

            if(stripos($useragent, 'Android')):
                $device_type = 'Android';
                // $link = base_url('/assets/apk/dealz.apk');
                $link = "https://play.google.com/store/apps/details?id=ae.arabianplus";

            elseif(stripos($useragent, 'iPhone')):
                $device_type = 'iPhone';
                $link = 'https://apps.apple.com/in/app/dealzarabia-buy-and-win/id1636112391';
            elseif(stripos($useragent, 'iPad')):
                $device_type = 'iPad';
                $link = 'https://apps.apple.com/in/app/dealzarabia-buy-and-win/id1636112391';
            endif;

        ?>
            <!--Footer navbar start-->
            <footer>
                 <div class="marquee-container">
                    <div class="marqueeimg-section">
                        <img class="marquee-arrow" src="<?=base_url('/assets/mobile/img/right-arrow.gif')?>" >
                    </div>
                    <div class="marqueelink-section">
                         <a class="marqueelink" target="_blank" href="<?=$link;?>" class="<?=$home;?>">
                             <div class="marquee-section">
                               <div class="marquee" onmouseover="stopMarquee()" onmouseout="startMarquee()">
                                    Click here to download latest app.
                               </div>
                             </div>
                          </a>  
                    </div>
                </div>
                <nav>
                    <a href="<?=base_url('/');?>" class="<?=$home;?>">
                        <i class="icofont-ui-home"></i>
                        Home
                    </a>

                     <a href="<?=base_url('/shopping-cart');?>" class="cart-button <?=$cart;?>">
                        <?php if( count($this->cart->contents()) ):?> 
                            <small><?=@count($this->cart->contents())?></small>
                        <?php endif;?>
                        <i class="icofont-cart-alt"></i>
                        Cart
                    </a>

                    <a href="https://api.whatsapp.com/send?phone=<?=$general_details['whatsapp_no']?>&amp;text=Hello, I have a question about https://dealzarabia.com" target="_bl
                    ">
                        <i class="icofont-brand-whatsapp"></i>
                        Chat Now
                    </a>
                    <a href="javascript:void(0);" id="moremenu">
                        <i class="icofont-layers"></i>
                        More
                    </a>
                </nav>
            </footer>