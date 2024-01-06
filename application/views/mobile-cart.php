<style>
    /*.together_buy h5 {
        font-size: 12px !important;
        font-weight: 500;
        color: #000;
    }

    .deailboxrow_4 .deal_drawon{
        text-align: left;
    }

    .slick-active {
        width: 45% !important;
        margin-right: 5px;
    }*/

    .deailboxrow_4 .deal_drawon{
        text-align: left;
    }
    .bought_togbox {
        margin-bottom: 10px;
    }

</style>

    <?php include('common/mobile/header.php') ?>

    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?= base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Cart
                </h1>
            </section>
            <div class="inner_pagedata">
                
                 <!--Cart Popup Start-->
                <?php if($cartItems): ?>
                <div class="cart_blocks mt-3">
                    
                    <?php if ($this->session->flashdata('error')) { ?>
                    <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                    <?php } ?>
                    <?php if ($this->session->flashdata('success')) { ?>
                    <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                    <?php  } ?>

                        <?php 
                            if($this->cart->contents()):
                            $totalAED=0; 
                            $seq=0; 

                            $CartProducts =array();
                            foreach ($this->cart->contents() as $keyid => $items):
                               $CartProducts[] = $items['id'];

                                $totalAED = $totalAED + (int)$items['qty'] * (int)$items['other']['aed'];
                        ?>
                        <div class="cartbox">
                            <div class="cart_deletebtn">
                                <a href="<?=base_url('remove-item/').$items['rowid']?>" id="delete"><i class="icofont-ui-delete"></i></a>
                            </div>
                            
                            <div class="cart_data">
                                <div class="cart_img">

                                    <?php if($items['other']['image']): ?>
                                        <img src="<?=base_url().$items['other']['image']?>" class="img-responsive">
                                    <?php else: ?>
                                        <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-responsive">
                                    <?php endif; ?>



                                </div>
                                <?php 
                                    $product_name = str_replace('/', '', $items['name']);
                                    $product_name = str_replace( '___',"'", $product_name);
                                ?>
                                <div class="cart_txt">
                                    <h5><?=$product_name;?></h5>
                                    
                                    <?php 
                                        $product_name = str_replace('/', '', $items['other']['description']);
                                        $product_description = str_replace( '___',"'", $product_name);
                                    ?>
                                    <p><?=$product_description;?></p>

                                    <?php  $total = $items['other']['aed'] * $items['qty']; ?>

                                    <span>AED <?=$items['price']?></span>
                                </div>
                                <div class="cart_quantity">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="quantity-right-plus btn btn-success btn-number qty_btn qtyA" data-type="plus" data-qty='<?=$items['other']['aed']?>___<?=$items['rowid']?>___I'  data-id="<?=$items['rowid']; ?>">
                                             +
                                             </button>
                                        </span>

                                        <input type="text" id="<?=$items['rowid']; ?>-quantity" name="quantity" class="form-control input-number qty_<?=$seq;?>" value="<?=$items['qty']?>" data-price="<?=$items['price']?>"  min="1" max="100">
                                         <span class="input-group-btn">
                                            <button type="button" class="quantity-left-minus btn btn-danger btn-number qty_btn qtyA"  data-type="minus" data-qty='<?=$items['other']['aed']?>___<?=$items['rowid']?>___D' data-id="<?=$items['rowid']; ?>">
                                                -
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="toggle donate_cart <?php if($items['is_donated'] == 'Y'): echo "active"; endif; ?>" data-id="<?=$items['rowid']?>" >
                                Exchange your product and double the raffle coupon
                                <label class="toggleswitch">
                                    <input type="checkbox" class="togglecheck"  id="r<?=$items['rowid']?>" name="r<?=$items['rowid']?>" <?php if($items['is_donated'] == 'Y'):?> checked="checked" <?php endif; ?> >
                                    <span class="toggleslider round"></span>
                                </label>
                            </div>
                        </div>
                        <?php $seq++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    

                    <?php if($products): ?>

                    <div class="together_buy">
                        <h5>People have also bought together</h5>
                        <div class="bought_togetherslider">
                           

                           <?php foreach($products as $items): 

                                $Date = date('d-M-Y 23:00');
                                $CurrentDate = strtotime($Date);
                                $ValiduptoDate = strtotime($items['validuptodate'].' '.$items['validuptotime']);

                                if( $CurrentDate <= $ValiduptoDate && $items['commingSoon'] == 'N' ): 
                                     if(!in_array($items['products_id'], $CartProducts)):
                               

                           
                            ?>


                            <div class="slide">
                                <div class="bought_together">
                                    <div class="bought_togbox">
                                        <div class="boughttogimg">
                                            <?php if($items['product_image']): ?>
                                                <img src="<?=base_url().$items['product_image']?>" class="img-responsive" alt="<?=$items['product_image_alt']?>">
                                            <?php else: ?>
                                                <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-responsive" alt="<?=$item['product_image_alt']?>">
                                            <?php endif; ?>

                                        </div>

                                        <div class="boughttogtxt">

                                            <?php 

                                                $product_name = str_replace('/', '', $items['title']);
                                                $product_name = str_replace( '___',"'", $product_name);

                                            ?>

                                            <h5>Buy <?=stripslashes($product_name)?></h5>
                                            <h3>Win</h3>
                                            <p>
                                                <?php if($items['prizeDetails'][0]['prize1'] != 0 && $items['prizeDetails'][0]['prize2'] != 0 ): ?>

                                                <?php 
                                                $string = $items['prizeDetails'][0]['prize1'];
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
                                                <?php if($items['prizeDetails'][0]['prize2'] != 0 ): ?>
                                                    <?php 
                                                    $string = $items['prizeDetails'][0]['prize2'];
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
                                                <?php if($items['prizeDetails'][0]['prize3'] != 0 ): ?>
                                                    <?php 
                                                    $string = $items['prizeDetails'][0]['prize3'];
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
                                                            $string = $items['prizeDetails'][0]['title'];
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


                                             

                                            <span>Price: <b>AED <?=$items['adepoints']; ?></b></span>

                                            <?php $prizeDetail = $this->geneal_model->getParticularDataByParticularField('prize_image','da_prize', 'product_id', $items['products_id']); ?>
                                             
                                        </div>
                                        <div class="boughttogimg border_img">

                                            <?php if($prizeDetail):  ?>
                                                <img src="<?=base_url('/'.$prizeDetail['prize_image']);?>" alt="<?=$prizeDetail['prize_image_alt']?>"/>
                                            <?php else: ?>
                                                 <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-fluid" alt="<?=$item['product_image_alt']?>">
                                            <?php endif; ?>


                                        </div>
                                    </div>

                                    <?php if($items['color_size_details'][0]->color || $items['color_size_details'][0]->S ||$items['color_size_details'][0]->M || $items['color_size_details'][0]->L || $items['color_size_details'][0]->XL || $items['color_size_details'][0]->XXL): ?>
                                        <a href="<?=base_url('product-details/'.$items['title_slug'])?>" data-id="<?=$items['products_id']?>" class="default-btn addremove add_cart">Add To Cart</a>
                                    <?php elseif($items['commingSoon'] == 'N'): ?>
                                        <a href="javascript:void(0);" class="addcart_btn default-btn addremove add_cart" data-id="<?=$items['products_id']?>" >Add To Cart</a>
                                    <?php endif; ?>  
                                     <!---->
                                    <div class="deailboxrow_4">
                                        <div class="deal_drawon">
                                            Draw on <br>
                                           <?=date('d M, Y', strtotime($items['draw_date']))?>
                                        </div>
                                    </div>
                                    <!---->

                                </div>
                            </div>
                           <?php endif;  endif; ?>

                          <?php endforeach; ?>

                        </div>
                    </div>

                    <?php endif; ?> 
                </div>

                <div class="cart_total">
                    <p>Total<span class="total-price">AED <?= number_format($totalAED,2) ;?></span></p>
                    
                    <?php  if(empty($this->session->userdata('DZL_USERID'))): ?>
                        <a href="<?=base_url('login')?>?referenceUrl=user-cart" class="buy_btn">Proceed To BUY</a>
                    <?php else: ?>
                        <a href="<?=base_url('/checkout');?>" class="buy_btn">Proceed To BUY</a>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                    <div class="empty-cart-section text-center">
                        <!-- <h3 class="text-center mt-5 " >Cart is empty! </h3> -->

                        <img src="<?=base_url('assets/img/cart@2x.png')?>" class="empty-shopping-cart" alt="cartusers">
                        <p class=" empty-cart-heading text-center">Your cart is empty</p>
                        <a href="<?=base_url('/');?>" class="home-redirect-link">Start shopping</a>

                    </div>

            <?php endif; ?>
            <!--Cart Popup End-->
            </div>

            <?php include('common/mobile/footer.php'); ?>
            <?php include('common/mobile/menu.php'); ?>
     
        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>

<script>

        $('.qtyA').click(function(){
            var data    = $(this).data('qty');
            var ur = '<?=base_url()?>';
            var totalID = data.split("___");
            $.ajax({
                url : ur+ "shopping_cart/addqty",
                method: "POST", 
                data: {data: data},
                success: function(data1){
                    
                    var totalVAL = data1.split("__");
                    var A = 'AED'+totalVAL[0]
                    var B = 'AED'+totalVAL[1]
                    var C = 'AED'+totalVAL[2]
                    var D = 'AED'+totalVAL[3]
                    
                    $('#'+totalID[1]).empty().append(A);
                    $('#subTotal').empty().append(B);
                    $('#vat').empty().append(C);
                    $('#subTotalAmt').empty().append(D);
        
                }
            });
        });
 

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

                    // alert(data);

               $('.cart-button').empty();
               var A = '<small>'+data+'</small><i class="icofont-cart-alt"></i>Cart';
               $('.cart-button').empty().append(A);

               location.reload();
           }
       });

       });

     });
    </script>
   