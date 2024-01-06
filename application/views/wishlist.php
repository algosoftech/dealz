<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
    <style>
    .my-profile .change_password {
    
    display: flex;
    justify-content: space-between;
    padding: 20px 0px;
}
    .error{
        font-family: 'Open Sans', sans-serif;
    font-size: 14px;
    line-height: 22px;
    font-weight: 400;
    text-align: left;
    color: #e22c2d;
    margin-bottom: 0;
    }
    .my-profile .btn:hover{
background: #e72d2e;
    color: #ffffff;
}
.my-profile .btn {
    background: #ffffff;
    border: 1px solid #e72d2e;
    padding: 4px 13px !important;
    border-radius: 8px;
    color: #e72d2e;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    margin-top: 0px;
}      
.my-profile .confirm_password .change_passworded {
    text-align: end!important;
    padding: 28px 28px 17px 36px;
}
.recharge .users_form {
    width: 100%;
}
.my-profile .form-inline {
    padding: 0px; 
}
.my-profile input[type="number"] {
    width: 100%;
    border: 2px solid #f1f1f1;
    border-radius: 4px;
    margin: 8px 0;
    outline: none;
    padding: 5px 35px 5px;
    box-sizing: border-box;
    transition: 0.3s;
}
  tr{
    border: 1px solid #ddd;
}
table td {
   border: 1px solid #eee;
    border-top: 0;
    font-weight: 400;
    text-align:center;
    padding: 0.75rem;
    vertical-align: top;
    color: #6c757d;
    font-size: 14px;
    line-height: 22px;
  
    text-align: center;
}
.my-profile .inputWithIcon input::placeholder {
    color: rgb(209 209 209) !important;
    font-size: 15px;
}
 table th {
     padding: 0.75rem;
    vertical-align: top;
    border: 1px solid #dee2e6;
    font-size: 14px;
    line-height: 22px;
    font-weight: 600;
    text-align: center;
    color: #6c757d;
}
.form_user {
    padding: 0px 35px;
}
table {
    border-collapse: collapse;
}
.user_list{
   padding-top: 19px; 
}
.cart-box{ margin: 10px 0px; }
.whislist .user-cart{
    background: #f5fcff00;
    border-radius: 8px;
    position: relative;
    margin-top: 32px;
    border: 1px solid #ebebeb;
    text-align: left;
    margin-bottom: 0px;
}
.my-profile .users_form {
    background: #f5fcff00;
    border-radius: 8px;
    position: relative;
    margin-top: 32px;
    border: 1px solid #ebebeb;
    text-align: left;
    margin-bottom: 36px;
}
.box .chart {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    font-size: 12px;
    line-height: 108px;
    height: 160px;
    color: #300808;
    left: 37px;
}
.box .chart1 {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    font-size: 12px;
    line-height: 108px;
    height: 160px;
    color: #300808;
    top: 18px;
}

.box .chartdemo {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    font-size: 12px;
    line-height: 108px;
    height: 160px;
    color: #300808;
    top: 18px;
}

.box canvas {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  width: 100%;
}
.textes {
    font-size: 11px;
    position: relative;
    top: 0px;
    left: 26px !important;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: #031b26;
}
    </style>
</head>
<body>
<?php include('common/header.php') ?>
<!-- profile -->
<div class="whislist">
<div class="my-profile recharge">
	<div class="container">
		<div class="row">
		<?php include ('common/profile/menu.php') ?>
			<div class="col-md-9">
				<?php include ('common/profile/head.php') ?>
				<?php if(!empty($wishlistData)){ ?>
					<div class="user-cart">
                    <div class="profiles">
						<h4 class="information">My Whislist</h4>
					</div>
						<?php $i=1; foreach ($wishlistData as $key => $items) { 
					    	$where 			= 	['products_id'=>(int)$items['product_id'],'clossingSoon' => 'N','status'=>'A'];
					    	$productData = $this->geneal_model->getOnlyOneData('da_products', $where );
					    ?>
							<div class="cart-box">
								<div class="table-responsive cart-table">
									<table class="table">
										<tbody>
											<tr>
												<td width="20%">
													<div class="cart-product">
														<img src="<?=base_url().$productData['product_image']?>" class="img-fluid" alt="user_cart_prodcut">
													</div>
												</td>
												<td width="45%">
													<div class="table-hd">
														<a href="<?=base_url('product-details/'.base64_encode($productData['products_id']))?>"><h3><?=$productData['title']?></h3></a>
														<h5><?=substr(strip_tags($productData['description']),0,150)?>...</h5>
													</div>
												</td>
												<td width="33%">
													<div class="inquiry-and-buy">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-12">
                                                                <?php if(isset($productData['soldout_status']) && $productData['soldout_status'] == 'Y'){ ?> 
                                                                    <div class="">
                                                                        <?php
                                                                        $avlstock = $productData['stock'] * 100 / $productData['totalStock'];
                                                                        $remStick = 100 - $avlstock;
                                                                        ?>
                                                                        <div class="box">
                                                                            <div class="chart" data-percent="<?=number_format($remStick,0)?>" ><?=number_format($remStick,0)?>%</div>
                                                                        </div>
                                                                    </div>
                                                                    <h2 class="textes" style="left:20px !important;">Sold</h2>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-12">
                                                                <p>AED <?=$productData['adepoints']?>.00</p>
                                                                <a href="<?=base_url('delete-product-from-wishlist/'.manojEncript($productData['products_id']))?>" onClick="return confirm('Want to delete!');" style="float: right; color:red;"><i class="fas fa-trash" aria-hidden="true"></i></a>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-12">
                                                                <button class="default-btn addremove add_cart" data-id='<?=$productData['products_id']?>' id="add_cart" style="float: right;"> Move to <i class="fas fa-shopping-cart" aria-hidden="true"></i></button>
                                                            </div>
                                                        </div>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						<?php $i++; } ?>
						<?= $this->pagination->create_links(); ?>
					</div>
				<?php } else { ?>
                    <div class="user-cart">
                        <div class="profiles">
                            <h4 class="information">My Whislist</h4>
                        </div>
                        <div class="cart-box">
                            <div class="table-responsive cart-table">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>No product in your wishlist</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>
			</div>
		</div>
	</div>
</div>
</div>
<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>
<script>
    AOS.init({
    duration: 1200,
    })
</script>
<script>
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
 <script>
    function ConfirmForm() {
    $("#BlockUIConfirm").show();
    }
</script>
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

    jQuery("#closing-soon").owlCarousel({
    autoplay: true,
    lazyLoad: true,
    loop: true,
    margin: 20,
    responsiveClass: true,
    autoHeight: true,
    autoplayTimeout: 7000,
    smartSpeed: 800,
    dots: false,
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

    jQuery("#sold-out").owlCarousel({
    autoplay: true,
    lazyLoad: true,
    loop: true,
    margin: 20,
    responsiveClass: true,
    autoHeight: true,
    autoplayTimeout: 7000,
    smartSpeed: 800,
    dots: false,
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

    var $owl = $('#testimonial');

    $owl.children().each( function( index ) {
    $(this).attr( 'data-position', index ); 
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

    $(document).on('click', '.owl-item>div', function() {
  
    var $speed = 300;  
    $owl.trigger('to.owl.carousel', [$(this).data( 'position' ), $speed] );
    });
</script>

<script>
    $('.dropdown > .caption').on('click', function() {
    $(this).parent().toggleClass('open');
    });

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
<script type="text/javascript">
    	/*
    $("#rechargeForm").validate({
    rules: {
    email: { required: true, remote: "<?=base_url('profile/checkEmail')?>" },
    recharge_amt: { required: true, remote: "<?=base_url('profile/checkarAbianPoints')?>" },
    },
    messages:{
    email: { 	required: 'Please enter Email ID / Mobile No.', 
    			remote: 'Invalid Email ID / Mobile No.' },
    recharge_amt: { required: 'Please enter arabian points.',
    				remote: '<?php echo lang('LOW_BALANCE'); ?>'},
    },
    });
    */
</script>
<script>
    $(document).ready(function(){
        $(document).on("click", "button[id='add_cart']", function () {
            var product_id = $(this).attr('data-id');
            var curobj = $(this);
            curobj.html("Added To Cart");

            curobj.addClass('edit_cart');
            curobj.removeClass('add_cart');
      
            var ur = '<?=base_url()?>';
            $.ajax({
                url : ur+ "shopping_cart/add_from_wishlist",
                method: "POST", 
                data: {product_id: product_id},
                success: function(data){    
                    window.location.href = ur+"user-cart";
                }
            });
        });
        
});
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
      });
    });
</script>
</body>
</html>
