<?php
    $this->session->set_userdata('Quick_category_id',$products['category_id']);
    $this->session->set_userdata('Quick_products_id',$products['products_id']);
    $this->session->set_userdata('Quick_title',$products['title']);
    $this->session->set_userdata('Quick_adepoints',$products['adepoints']);
?>



<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Quick Buy
                </h1>
            </section>
            <div class="inner_pagedata">
                <div class="inner_forms login_tab qucikbuy_form">
                    <h3>
                        <span>Enter the Number</span>
                        <small>Enter your valid registered phone number</small>
                    </h3>
                    <form id="sendotp">
                        <div class="form-group row">
                            <div class="col-3 ps-0">
                                <?php if(set_value('country_code')): $countryCode = set_value('country_code'); else: $countryCode = '+971'; endif; ?>
                                <input type="tel" class="form-control" name="country_code" id="country_code"  value="+971">
                            </div>
                            <div class="col-5 p-0">
                                <input type="tel" name="mobile" id="mobile" class="form-control" placeholder="0000 0000 00" autocomplete="off" value="<?php if(set_value('mobile')): echo set_value('mobile'); endif; ?>" class="form-control" placeholder="Mobile Number" autocomplete="off">
                                <label id="mobile-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
                            </div>

                            <div class="col-4 pe-0">
                                <input type="submit" class="login_button submit-button" id="login_button2" value="Submit">
                            </div>
                        </div>
                    </form>
                    <hr class="mt-4">
                    <h3>
                        <span>Enter OTP</span>
                        <small>Please enter the code we just send to +971 XXXX 789</small>
                    </h3>
                        <form id="verifyOTP">
                            <div class="form-group row">
                                <div class="col p-0 arabian_percent">
                                    <input type="text" name="otp1" id="otp1" class="form-control" placeholder="0" autocomplete="off" maxlength="1" >
                                    <input type="text" name="otp2" id="otp2" class="form-control" placeholder="0" autocomplete="off" maxlength="1" >
                                    <input type="text" name="otp3" id="otp3" class="form-control" placeholder="0" autocomplete="off" maxlength="1" >
                                    <input type="text" name="otp4" id="otp4" class="form-control" placeholder="0" autocomplete="off" maxlength="1" >
                                    <input type="submit" class="login_button" id="login_button2" value="Verify">
                                </div>
                            </div>
                            <div class="form-group row opttext mb-0">
                                <p class="m-0">Dint't receive OTP? <a href="javascript:void(0)" id="resendOtp">Resend Code</a></p>
                            </div>
                        </form>
                        <form id="cart"  method="post" action="<?=base_url('/quick-buy/checkout')?>">
                            <hr class="mt-4">
                            <h3>
                                <span>Quantity</span>
                            </h3>
                            <div class="deal_repeatrow">
                                <!---->
                                <div class="deailboxrow_2">
                                    <div class="deal_prod">
                                        <a href="javascript:void(0);">
                                            <?php if(file_get_contents(base_url().$products['product_image'])): ?>
                                                <img src="<?= base_url('/'.$products['product_image']);?>" class="data_product" id="productImage" alt="<?=$products['product_image_alt'];?>"/>
                                            <?php else: ?>
                                                <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="data_product img-responsive" alt="<?=$item['product_image_alt']?>">
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="buy_quantity">
                                        <div class="deal_win">
                                            <?php 
                                            $product_name = str_replace('/', '', $products['title']);
                                            $product_name = str_replace( '___',"'", $product_name);
                                        ?>
                                            <p id="productName" data-productName="<?=$product_name.'_____'.$products['products_id'] ; ?>" >Buy <?=$product_name; ?></p>
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
                                            <a href="javascript:void(0)" id="price"  data-price="<?=$products['adepoints'];?>" >AED <?= number_format($products['adepoints'],2) ?></a>
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

                            </div>
                            <h3>
                                <span>Enter your Details</span>
                            </h3>
                        
                            <div class="form-group row">
                                <div class="col ps-0 pe-1">
                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" autocomplete="off">
                                </div>
                                <div class="col pe-0 ps-1">
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12 p-0 text-center">
                                    <input type="submit" class="login_button" id="login_button2" value="Proceed">
                                </div>
                            </div>
                        </form>
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



<script>
    $('#sendotp').on('submit' , function(e) {
        e.preventDefault();
        let country_code  = $('#country_code').val();
        let users_mobile  = $('#mobile').val();

        if(country_code != '' && users_mobile != '' )
        $.ajax({
            type : "Post",
            url :  "<?=base_url('quick-buy/verifyUser')?>",
            data : {country_code: country_code, users_mobile:users_mobile} ,
            success:function(data){  
                alert( data); 
            } 

        });

    });

    $('#verifyOTP').on('submit' , function(e) {

        e.preventDefault();
        
        let otp1  = $("#otp1").val();
        let otp2  = $("#otp2").val();
        let otp3  = $("#otp3").val();
        let otp4  = $("#otp4").val();

        let complteOTP = otp1+otp2+otp3+otp4;
        
        if(otp1!="" && otp2 !="" && otp3 != "" && otp4 != "")
        $.ajax({
            type : "Post",
            url :  "<?=base_url('quick-buy/verifyUserOTP')?>",
            data : {otp :complteOTP } ,
            success:function(data){  
                let responce = jQuery.parseJSON(data );
                alert( responce.message);
            } 

        });

    });

    $('#resendOtp').on('click' , function(e){
        e.preventDefault();

        var country_code  = $('#country_code').val();
        var users_mobile  = $('#mobile').val();
        
        if(country_code != ''){
             var country_code = country_code;
        }else{
            var country_code = "<?=$this->session->userdata('Quick_country_code');?>";
        }

        if(users_mobile != ''){
             var users_mobile = users_mobile;
        }else{
            var users_mobile = "<?=$this->session->userdata('Quick_users_mobile');?>";
        }

        $.ajax({
            type : "Post",
            url :  "<?=base_url('quick-buy/verifyUser')?>",
            data : {country_code: country_code, users_mobile:users_mobile} ,
            success:function(data){  
                alert( data); 
            } 

        });
    });

   
    $(document).ready(function(){

        $('#quantity').on('keyup', function(){

            var quantity = parseInt($('#quantity').val());
            if(quantity > 0){
                console.log(quantity);
                var newPrice = parseFloat($("#price").data("price"))* quantity ;
                $('#quantity').val(quantity);
                $("#price").attr("data-price", newPrice);
                $("#price").text("AED " + newPrice);
            }
        });


        $('.quantity-right-plus').on('click', function(){
            var quantity = parseInt($('#quantity').val());
            var qty = quantity + 1;
            var newPrice = parseFloat($("#price").data("price"))* qty ;
            $('#quantity').val(qty);
            $("#price").attr("data-price", newPrice);
            $("#price").text("AED " + newPrice);


        });
   
        $('.quantity-left-minus').on('click', function(){
            var quantity = parseInt($('#quantity').val());
            var qty = quantity - 1;
            var newPrice = parseFloat($("#price").data("price"))* qty ;

            if(qty>0){
               $('#quantity').val(qty);
               $("#price").attr("data-price", newPrice);
               $("#price").text("AED " + newPrice);
            }
        });

        $('.icofont-delete-alt').on('click', function(){
            var qty = 1;
            if(qty>0){
                $('#quantity').val(qty);
            }
        });

        //Enter otp fields focus start
        $('#otp1').on('keyup',function(){
            if($(this).val() != '')
            $("#otp2").focus();
        });
        $('#otp2').on('keyup',function(){
            if($(this).val() != ''){
                $("#otp3").focus();
            }else{
                $("#otp1").focus();
            }
        });
        $('#otp3').on('keyup',function(){
            
            if($(this).val() != ''){
                $("#otp4").focus();
            }else{
                $("#otp2").focus();
            }
        });

        $('#otp4').on('keyup',function(){
            if($(this).val() == ''){
                $("#otp3").focus();
            }
        });
        //Enter otp fields focus end





    });



    // $("#cart").on('submit' , function(e) {
       

    //     let productName     = $("#productName").data("productname");
    //     let price           = $("#price").data("price");
    //     let quantity        = $("#quantity").val();
    //     let first_name      = $("#first_name").val();
    //     let last_name       = $("#last_name").val();
    //     let country_code    = $('#country_code').val();
    //     let users_mobile    = $('#mobile').val();

    //     if(productName =='' || price =='' || quantity =='' || first_name =='' || last_name =='' || last_name ==''  || country_code == '' ||  users_mobile == '' ){
    //         e.preventDefault();
    //         alert('please enter all fields');

    //     }



    // });

     $("#cart").on('submit', function(e) {
        let productName = $("#productName").data("productname");
        let price = $("#price").data("price");
        let quantity = $("#quantity").val();
        let first_name = $("#first_name").val();
        let last_name = $("#last_name").val();
        let country_code = $('#country_code').val();
        let users_mobile = $('#mobile').val();
        let valid = true;  // Assume the form is initially valid.



        // Additional validation for names (letters only)
        const namePattern = /^[A-Za-z\s]+$/; // Allow letters and spaces

        if (productName == '' || price == '' || quantity == '' || country_code == '' || users_mobile == '') {
            valid = false;
            alert('Please enter all fields.');
        }else if(first_name.trim() == '') {
            valid = false;
            alert('Please enter your first name.');
        }else if (first_name.trim().length < 2 || first_name.trim().length > 50) {
            valid = false;
            alert('First name should be between 2 and 50 characters.');
        }
        else if (!namePattern.test(first_name.trim())) {
            valid = false;
            alert('First name should contain letters and spaces only.');
        }else if (last_name.trim() == '') {
            valid = false;
            alert('Please enter your last name.');
        }
        else if (last_name.trim().length < 2 || last_name.trim().length > 50) {
            valid = false;
            alert('Last name should be between 2 and 50 characters.');
        }else if (!namePattern.test(last_name.trim())) {
            valid = false;
            alert('Last name should contain letters and spaces only.');
        }

        if (!valid) {
            e.preventDefault(); // Prevent form submission if validation fails.
        }

    });


</script>