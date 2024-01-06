<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea quick-buy-container">


            <!-- header section start -->
            <section class="inner_head">
                <a href="http://localhost/d-arabia/"><i class="icofont-rounded-left"></i></a>
                <h1>  Quick Ticket </h1>
            </section>
            <!-- header section end -->

           
            <div class="inner_pagedata">
                <section class="deals_homesec">
                    

                    <div class="quick-buy-container">
                        <form id="cart"  method="post" action="">
                            <div class="quick buy-ticket-section">

                                <?php if ($this->session->flashdata('error')): ?>
                                    <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                                <?php endif; ?>
                                
                                <?php if ($this->session->flashdata('success')): ?>
                                    <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                                <?php  endif; ?>
                                
                                <div class="cardbox product-section">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                          
                                                <div class="deailboxrow_2">
                                                    <div class="deal_prod">
                                                        <?php if(file_get_contents(base_url().$products['product_image'])): ?>
                                                            <img src="<?= base_url('/'.$products['product_image']);?>" class="data_product" id="productImage" alt="<?=$products['product_image_alt'];?>"/>
                                                        <?php else: ?>
                                                            <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="data_product img-responsive" alt="<?=$item['product_image_alt']?>">
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="buy_quantity">
                                                        <div class="deal_win">
                                                            <h3 id="productName" ><?=$products['title']; ?></h3>
                                                            <p><?=$products['description'];?></p>
                                                            <span style="color: #0d9e51; font-weight: 700;" class="price___content">AED <?= number_format($products['adepoints'],2) ?></span> 
                                                        </div>
                                                        <div class="quantitycart">
                                                            <div class="cart_quantity">
                                                                <div class="input-group">
                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="">
                                                                           +
                                                                        </button>
                                                                    </span>
                                                                <?php if($this->session->userdata('DZL_USERSTYPE') == 'Admin'): ?>
                                                                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100">
                                                                <?php else: ?>
                                                                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100" readonly>
                                                                <?php endif; ?>

                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus" data-field="">
                                                                        -
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <button class="quantitycart_dlt d-none">
                                                                <i class="icofont-delete-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="toggle donate_cart ">
                                    Exchange your product and double the raffle coupon
                                    <label class="toggleswitch">
                                        <input name="is_donated" type="checkbox" class="togglecheck">
                                        <span class="toggleslider round"></span>
                                    </label>
                                </div>

                                <div class="cardbox section2">
                                   <div class="price-heading">
                                        <h3>Total</h3>
                                   </div>
                                   <div class="price___content">
                                       <span style="color: #0d9e51; font-weight: 700;" id="price" class="price___content" data-price="<?=$products['adepoints'];?>">AED <?= number_format($products['adepoints'],2) ?></span> 
                                   </div>
                                </div>
                                <div class="cardbox section2">
                                   <div class="price-heading">
                                       <input class="form-check-input" type="radio" name="available_balance" id="available_balance" value="<?=$availableArabianPoints;?>" checked> 
                                       <label class="form-check-label" for="available_balance">Arabian Points (<?= number_format($availableArabianPoints, 2) ?>)</label>
                                   </div>
                                </div>

                                <div class="inner_forms login_tab">
                                    <h3><span>Fill Details</span></h3>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" id="mail" name="mail">
                                              <label class="form-check-label" for="mail">Mail</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2 col-md-2 col-lg-2 form-group">
                                            <input type="tel" class="form-control" name="country_code" id="country_code" value="+971">
                                        </div>
                                        <div class="col-sm-10 col-md-10 col-lg-10 form-group">
                                            <input type="tel" name="users_mobile" id="users_mobile" class="form-control" placeholder="Mobile Number" autocomplete="off" required>
                                            <label id="users_mobile-error" class="error" for="users_mobile"></label>
                                        </div>

                                        <div class="col-sm-6 col-md-6 col-lg-6 form-group">
                                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" autocomplete="off" required>
                                            <label id="irst_name-error" class="error" for="first_name"></label>
                                        </div>

                                        <div class="col-sm-6 col-md-6 col-lg-6 form-group">
                                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" autocomplete="off" required>
                                            <label id="last_name-error" class="error" for="last_name"></label>
                                        </div>

                                        <div class="col-sm-6 col-md-6 col-lg-6 form-group">
                                            <input type="hidden" name="type" value="<?=$_GET['type']?>">
                                            <input type="hidden" name="SaveChanges" value="Yes">
                                            <button class="btn  btn-submit W-100">PROCEED TO PRINT</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>

                </section>
            </div>
        <?php include('common/mobile/footer.php'); ?>
        <?php include('common/mobile/menu.php'); ?>


        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>
    <!-- Country code popup Ui start -->
    <?php include('common/mobile/countrycode-list.php') ?>
<!-- Country code popup Ui end -->

<script type="text/javascript">
     $('#quantity').on('keyup', function(){

            var quantity = parseInt($('#quantity').val());
            if(quantity > 0){
                var newPrice = parseFloat($("#price").data("price"))* quantity ;
                $('#quantity').val(quantity);
                $("#price").attr("data-price", newPrice.toFixed(2));
                $("#price").text("AED " + newPrice.toFixed(2));
                APValidation();
            }
        });

        $('.quantity-right-plus').on('click', function(){
            var quantity = parseInt($('#quantity').val());
            var qty      = quantity + 1;
            var newPrice = parseFloat($("#price").data("price"))* qty ;

            $('#quantity').val(qty);
            $("#price").attr("data-price", newPrice.toFixed(2));
            $("#price").text("AED " + newPrice.toFixed(2));
            APValidation();
        });
   
        $('.quantity-left-minus').on('click', function(){
            var quantity = parseInt($('#quantity').val());
            var qty = quantity - 1;
            var newPrice = parseFloat($("#price").data("price"))* qty ;

            if(qty>0){
               $('#quantity').val(qty);
               $("#price").attr("data-price", newPrice.toFixed(2));
               $("#price").text("AED " + newPrice.toFixed(2));
               APValidation();
            }
        });

        APValidation();
        function APValidation() {
            var Available_ArabianPoints = "<?=$availableArabianPoints;?>";
            var adepoints = $('#price').attr("data-price");
            // console.log(adepoints);
            if(parseFloat(Available_ArabianPoints) < parseFloat(adepoints)){
                $('.btn-submit').attr('disabled',true);
            }else{
                $('.btn-submit').attr('disabled',false);  
            }
        }


</script>
 