<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?= base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Details
                </h1>
            </section>
            <div class="inner_pagedata">


                <section class="deals_homesec">
                    <div class="product_details">
                        <a href="" class="pd_share"><i class="icofont-share"></i></a>
                        <div class="tab_1">
                            <div class="pd_title">
                                <div class="data_prize">
                                    <?php if($prize['prize1']): ?>
                                        Cash Prize <?=$prize['prize1'];?> AED
                                    <?php endif; ?>
                                    <?php if($prize['prize2']): ?>
                                       2nd Cash Prize <?=$prize['prize2'];?> AED
                                    <?php endif; ?>
                                    <?php if($prize['prize3']): ?>
                                       3rd Cash Prize <?=$prize['prize3'];?> AED
                                    <?php endif; ?>

                                </div>

                                 <?php 

                                    $product_name = str_replace('/', '', $products['title']);
                                    $product_name = str_replace( '___',"'", $product_name);

                                ?>

                                <div class="data_product"><?=$product_name; ?> </div>
                            </div>
                            <div class="pd_img">

                                <?php if(file_get_contents(base_url().$prize['prize_image'])): ?>
                                     <img src="<?= base_url('/'.$prize['prize_image']);?>" class="data_prize"   alt="<?=$prize['prize_image_alt'];?>"/>
                                <?php else: ?>
                                    <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="data_prize img-responsive" alt="<?=$item['product_image_alt']?>">
                                <?php endif; ?>

                                <?php if(file_get_contents(base_url().$products['product_image'])): ?>
                                    <img src="<?= base_url('/'.$products['product_image']);?>" class="data_product" id="productImage" alt="<?=$products['product_image_alt'];?>"/>
                                <?php else: ?>
                                    <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="data_product img-responsive" alt="<?=$item['product_image_alt']?>">
                                <?php endif; ?>
                            </div>
                            <div class="pd_subtitle">
                                <h3>Maximium draw date <?=date('d M, Y',strtotime($products['draw_date'].' '.$products['draw_time'] )); ?></h3>
                                <p>or when the campaign sold out, whichever is earlier</p>
                            </div>
                        </div>
                            <div class="pd_tab">
                                <ul class="nav-tabss">
                                    <li ><a href="#prizedetail">PRIZE Details</a></li>
                                    <li class="active"><a  href="#productdetail">Product Details</a></li>
                                </ul>
                            </div>
                        <div class="tab_1">
                            <div class="pd_bottom">
                                <h4 class="data_prize">Win !</h4>
                                 
                                <h4 class="data_product">Buy <em>a <?=$product_name;?></em></h4>
                                <strong class="data_prize">
                                     <?php if($prize['prize1']): ?>
                                        Cash Prize <?=$prize['prize1'];?> AED
                                    <?php endif; ?>
                                    <?php if($prize['prize2']): ?>
                                       2nd Cash Prize <?=$prize['prize2'];?> AED
                                    <?php endif; ?>
                                    <?php if($prize['prize3']): ?>
                                       3rd Cash Prize <?=$prize['prize3'];?> AED
                                    <?php endif; ?>

                                     <?php 
                                        $string = $prize['prize_image_alt'];
                                                    // Extract the number from the string
                                        $number = preg_replace('/[^0-9]/', '', $string);

                                                    // Convert the number to a formatted string with commas
                                        $formattedNumber = number_format($number);

                                                    // Replace the original number in the string with the formatted number
                                        $result = str_replace($number, $formattedNumber, $string);

                                    ?>

                                    <?=$result;?>

                                </strong>

                             <?php if($prize['prize1']): ?>
                                <strong class="data_product">and join for cash prize 

                                    <?php if($prize['prize1']): ?>
                                         ,<?=$prize['prize1'];?>
                                    <?php endif; ?>
                                    <?php if($prize['prize2']): ?>
                                       ,<?=$prize['prize2'];?>
                                    <?php endif; ?>
                                    <?php if($prize['prize3']): ?>
                                        ,<?=$prize['prize3'];?> 
                                    <?php endif; ?>

                                 AED campaign</strong>
                             <?php endif; ?>
                                <span class="data_prize">
                                    <?php if($prize['prize1']): ?>
                                        Cash Prize <?=$prize['prize1'];?> AED
                                    <?php endif; ?>
                                    <?php if($prize['prize2']): ?>
                                       2nd Cash Prize <?=$prize['prize2'];?> AED
                                    <?php endif; ?>
                                    <?php if($prize['prize3']): ?>
                                       3rd Cash Prize <?=$prize['prize3'];?> AED
                                    <?php endif; ?>
                                </span>

                                <span class="data_product">Buy a <?=$product_name;?></span>


                                 <!-- Color Option -->
                                <div class="color_para_one">
                                    <p style="text-align:center;"><?=stripslashes(stripslashes($products['description']))?></p>
                                    <?php if(count($products['color_size_details']) != 0):   ?>
                                    <p style="margin:0px;">Select color:</p>
                                    <?php endif; ?>
                                </div>
                                <?php if(count($products['color_size_details']) != 0):   ?>
                                <div class="colors">
                                    <ul style="padding:0px;margin:0px;">
                                        <?php $i=0; foreach ($products['color_size_details'] as $key => $value): ?>
                                        <li class="colorCode" data-color="<?=$value->color?>" data-img="<?=base_url().$value->image?>" data-count="<?=count($products['color_size_details'])?>" data-size="<?=$i?>" style="background-color: <?=$value->color?>; <?php if($i == 0): ?> border: 3px solid black; <?php endif; ?>" data-firstsize="<?php if($value->S == 'Y'): ?>S<?php elseif($value->M == 'Y'): ?>M<?php elseif($value->L == 'Y'): ?>L<?php elseif($value->XL == 'Y'): ?>XL<?php elseif($value->XXL == 'Y'): ?>XXL<?php endif; ?>"
                                            data-html="<p style='margin:0px;'> Select Size</p>
                                            <ul class='select_size' id='colorSize<?=$i?>'>
                                                <?php $f=0; if($value->S == 'Y') : ?>
                                                <a onclick='S()' class='S <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='S' id='size<?=$i?>' >
                                                <label for='cat' class='small'>S</label>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($value->M == 'Y') : ?>
                                                <a onclick='M()' class='M <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='M' id='size<?=$i?>' >
                                                <label for='cat' class='small'>M</label>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($value->L == 'Y') : ?>
                                                <a onclick='L()' class='L <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='L' id='size<?=$i?>' >
                                                <label for='cat' class='small'>L</label>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($value->XL == 'Y') : ?>
                                                <a onclick='XL()' class='XL <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='XL' id='size<?=$i?>' >
                                                <label for='cat' class='small'>XL</label>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($value->XXL == 'Y') : ?>
                                                <a onclick='XXL()' class='XXL <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='XXL' id='size<?=$i?>' >
                                                <label for='cat' class='small'>XXL</label>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($value->FRS == 'Y') : ?>
                                                <a onclick='FRS()' class='FRS <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='FRS' id='size<?=$i?>' >
                                                <label for='cat' class='small'>Free Size</label>
                                                </a>
                                                <?php endif; ?>
                                            </ul>
                                            "
                                            ></li>
                                        <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <!-- Size Option -->
                                <div class="color_para_one colorsize">
                                    <?php if($products['color_size_details'][0]->S == 'Y' || $products['color_size_details'][0]->M == 'Y' || $products['color_size_details'][0]->L == 'Y' || $products['color_size_details'][0]->XL == 'Y' || $products['color_size_details'][0]->XXL == 'Y') : ?>
                                        <p style="margin:0px;"> Select Size</p>
                                    <?php endif; ?>
                                    <?php $i=0; $j=0; $f=0;  $f=0; ?>
                                        <ul class="select_size <?php if($j != 0): ?> hidden <?php endif; ?>" id="colorSize<?=$j?>" >
                                            <?php if($products['color_size_details'][0]->S == 'Y') : ?>
                                            <a onclick="S()" class="S <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="S" id="S" >
                                                <label for="cat" class="small">S</label>
                                            </a>
                                            <?php endif; ?>
                                            <?php if($products['color_size_details'][0]->M == 'Y') : ?>
                                            <a onclick="M()" class="M <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="M" id="M">
                                                <label for="cat" class="small">M</label>
                                            </a>
                                            <?php endif; ?>
                                            <?php if($products['color_size_details'][0]->L == 'Y') : ?>
                                            <a onclick="L()" class="L <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="L" id="L">
                                                <label for="cat" class="small">L</label>
                                            </a>
                                            <?php endif; ?>
                                            <?php if($products['color_size_details'][0]->XL == 'Y') : ?>
                                            <a onclick="XL()" class="XL <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="XL" id="XL">
                                                <label for="cat" class="small">XL</label>
                                            </a>
                                            <?php endif; ?>
                                            <?php if($products['color_size_details'][0]->XXL == 'Y') : ?>
                                            <a onclick="XXL()" class="XXL <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="XXL" id="XXL">
                                                <label for="cat" class="small">XXL</label>
                                            </a>
                                            <?php endif; ?>
                                            <?php if($products['color_size_details'][0]->FRS == 'Y') : ?>
                                            <a onclick="FRS()" class="FRS <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="FRS" id="FRS">
                                                <label for="cat" class="small">Free Size</label>
                                            </a>
                                            <?php endif; ?>

                                        </ul>
                                    <?php $j++;  ?>
                                </div>
                                <?php endif; ?>


                                
                                


                                


                                <p>Price (Inclusive of VAT)<small>AED <?=$products['adepoints'];?></small></p>

                              <?php if($products['color_size_details'][0]->color || $products['color_size_details'][0]->S ||$products['color_size_details'][0]->M || $products['color_size_details'][0]->L || $products['color_size_details'][0]->XL || $products['color_size_details'][0]->XXL): ?>
                                <a href="<?=base_url('product-details/'.$products['title_slug'])?>" data-id="<?=$products['products_id']?>" class="addcart_btn default-btn addremove add_cart pd_adcart">Add To Cart</a>
                              <?php elseif($products['commingSoon'] == 'N'): ?>
                                <a href="javascript:void(0);" class="addcart_btn default-btn addremove add_cart pd_adcart" data-id="<?=$products['products_id']?>" >Add To Cart</a>
                              <?php endif; ?>

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

<script>
    $(document).ready(function(){
        $('.colorCode').click(function(){
           


            var size = $(this).data('size');
            var img = $(this).data('img');
            var count = $(this).data('count');
            var fsize = $(this).data('firstsize');
            var fcolor = $(this).data('color');
            var ur = '<?=base_url()?>'
            var size_html = $(this).data('html');
            
            $('.colorsize').empty().html(size_html);
            
            $('#productImage').attr('src', img);
            $('#fcolor').val('');
            $('#fcolor').val(fcolor);
            $('#fsize').val();
            $('#fsize').val(fsize)

        });
        
    });

    function S(){
        $('#fsize').val();
        $('#fsize').val('S')
        $('.S').addClass('size_active');
        $('.M').removeClass('size_active');
        $('.L').removeClass('size_active');
        $('.XL').removeClass('size_active');
        $('.XXL').removeClass('size_active');
        $('.FRS').removeClass('size_active');
        
    }

    function M(){
        $('#fsize').val();
        $('#fsize').val('M')
        $('.S').removeClass('size_active');
        $('.M').addClass('size_active');
        $('.L').removeClass('size_active');
        $('.XL').removeClass('size_active');
        $('.XXL').removeClass('size_active');
        $('.FRS').removeClass('size_active');

    }

    function L(){
        $('#fsize').val();
        $('#fsize').val('L')
        $('.S').removeClass('size_active');
        $('.M').removeClass('size_active');
        $('.L').addClass('size_active');
        $('.XL').removeClass('size_active');
        $('.XXL').removeClass('size_active');
        $('.FRS').removeClass('size_active');

    }

    function XL(){
        $('#fsize').val();
        $('#fsize').val('XL')
        $('.S').removeClass('size_active');
        $('.M').removeClass('size_active');
        $('.L').removeClass('size_active');
        $('.XL').addClass('size_active');
        $('.XXL').removeClass('size_active');
        $('.FRS').removeClass('size_active');

    }

    function XXL(){
        $('#fsize').val();
        $('#fsize').val('XXL')
        $('.S').removeClass('size_active');
        $('.M').removeClass('size_active');
        $('.L').removeClass('size_active');
        $('.XL').removeClass('size_active');
        $('.XXL').addClass('size_active');
        $('.FRS').removeClass('size_active');
    }

    function FRS(){
        $('#fsize').val();
        $('#fsize').val('FRS')
        $('.S').removeClass('size_active');
        $('.M').removeClass('size_active');
        $('.L').removeClass('size_active');
        $('.XL').removeClass('size_active');
        $('.XXL').removeClass('size_active');
        $('.FRS').addClass('size_active');
    }
</script>

<script>
    $(document).ready(function(){

        $(".add_cart").on('click' , function(e){
            e.preventDefault();
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
                }
            });

        });


});
</script>
  