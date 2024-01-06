<!Doctype html>
<html lang="eng">
<head>
	<!-- Basic page needs -->	
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<?php include('common/head.php') ?>
<style>
.details .legend_product {
    text-align: center;
    padding: 0px 19px 19px; 
    width: 330px !important;
    float: left;
}
.default-heading {
    font-family: Gadugi Bold;
    text-align: left;
    font-size: 23px;
    color: #031b26;
}
.details{
  margin-bottom: 16px;  
}

.prize_box {
    padding: 6px 18px;
    border-radius: 9px;
    border: 1px solid rgb(209 209 209);
}
.prize .prize_box h3 {
    font-family: 'Open Sans', sans-serif;
    text-align: left;
    font-size: 15px;
    color: #031b26;
    text-align: center !important;
    margin: 0px;
    margin-bottom: 2px;
    font-weight: 600;
}
.prize .prize_box p {
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    font-size: 14px !important;
    color: #1e1e1e !important;
    margin: 0px;
    text-align: center;
}
.details .prize .default-heading {
    font-family: 'Open Sans', sans-serif;
    text-align: left;
    font-size: 22px;
    color: #031b26;
    font-weight: 700;
}
.box {
    width: 8%;
    position: relative;
    top: -22px;
    left: -19px;
}
.fa-share:hover{
  color: #d12a2b !important;   
}

.fa-share:hover{
  color: #d12a2b !important;   
}
.share i:hover {
    color: #d12a2b !important;
}
.heart_icon{
   color: rgb(209 209 209);
   font-size: 20px !important;
}
.fa-share:hover{
  color: #d12a2b !important;   
}
.share i:hover {
    color: #d12a2b !important;
}
.heart_icon{
   color: rgb(209 209 209);
   font-size: 20px;
}
.fa-heart:hover {
    color: #d12a2b !important;
}
.box {
    width: 8%;
    position: relative;
    top: -30px;
}

 .box h2 {
  display: block;
  text-align: center;
  color: black;
  position: relative;
top: -96px;
    left: 13px;

}
.hearts {
    float: left;
    width: 20px;
    padding-top: 15px;
    position: relative;
    left: -20px;
}
.textes {
    position: relative;
    top: -86px;
    left: -5px;
    font-family: 'Open Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #031b26;
    left: 12px;
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
    top: 13px;
    left: 6px;
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

.box canvas {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  width: 100%;
}
    .details .legend_product {
text-align: center;
padding: 0px 19px 19px;
width: 378px;
float: left;
}
.details .cart_1 {
    background: #e72d2e00;
    border: 2px solid #e72d2e;
    padding: 6px 8px !important;
    border-radius: 8px;
    color: #212529e6;
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    font-weight: 400;
    margin-right: 10px !important;
}
.details .date_item3 {
    margin: 0px;
    margin: 0px;
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    font-weight: 400;
    margin-top: 30px;
    text-align: center;
}
.details .cart_2:hover {
    transition: 0.3s;
    background: #e72d2e;
    color: #fff;
}
.details .edit_cart {
   background: #c42828 !important;
    border: 1px solid #ec2d2f !important;
    padding: 6px 25px;
    border-radius: 8px;
    color: #fff !important;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    margin-top: 0px;
    width: 100%;
}
.edit_cart:hover {
    background: #ffffff;
    color: #e70909;
}
.details .cart_2 {
    background: #e72d2e00;
    border: 2px solid #e72d2e;
    padding: 6px 8px !important;
    border-radius: 8px;
    color: #212529e6;
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    font-weight: 400;
    margin-right: 10px !important;
}
.pencil{
    max-width: 100%;
    max-height: 100%;
    padding-top: 34px;
    padding-bottom: 22px;
}
.details .vl {
    border-left: 1px solid rgb(209 209 209);
    height: 338px !important;
    position: relative;
  top: 19px !important;
    left: 9px!important;
}
.details .legend_product h2 {
    margin: 0px;
    margin: 0px;
    font-family: 'Open Sans', sans-serif;
    font-weight: 700;
    font-size: 16px;
    /* padding-left: 22px; */
    max-width: 290px;
    margin: auto;
    text-align: center;
}
.details .vl_img {
    position: absolute;
    top: 46%;
    left: 44%;
}
.details .fa-heart:before {
    content: "\f004";
    padding-right: 5px;
    font-size: 26px;
    color: #d12a2b !important;
    font-weight: 400;
}
.share {
    content: "\f1e0";
    color: rgb(209 209 209 / 53%);
    font-size: 18px;
    padding-left: 5px;
}
.details .fa-share-alt:before {
    content: "\f1e0";
    padding-right: 0px;
    font-size: 18px;
}
.details .vl {
    border-left: 1px solid rgb(209 209 209);
    height: 362px !important;
    position: relative;
    top: 19px !important;
    left: 25px!important;
}
.edit_cart {
   background:#c42828;
    border: 1px solid #ec2d2f;
    padding: 6px 25px;
    border-radius: 8px;
    color: #fff;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    margin-top: 10px;
    width: 100%;
}
.details .legend_product p{
    text-align: center;
    padding: 0px 19px 19px;
    width: 330px;
    float: left;
	display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
.edit_cart:hover {
    background: #ffffff;
    color: #e70909;
}
.details .prize .prize_box p {
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    font-size: 14px !important;
    color: #1e1e1e !important;
    margin: 0px;
    text-align: center;
	
}
.white-btn:hover{
    background: #d12a2b !important;
     color: #fff !important;
}
.white-btn {
    padding: 6px 15px;
    border: 1px solid #e0e0e0 !important;
    background: #fff !important;
    color: #363636;
    border-radius: 8px;
    color: #363636;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
}
.default-btn {
    background: #e72d2e00 !important;
    border: 1px solid #d12a2b !important;
    padding: 4px 25px !important;
    border-radius: 8px;
    color: #d12a2b !important;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
}
.default-btn:hover{
 background:#d12a2b !important;
    color:#fff !important;  
}
.fa-heart.active {
  color: #d12a2b !important;
}
.sociallinks ul { list-style-type: none; }
.sociallinks ul li { cursor: pointer; }
.legend_product p img{
width:9% !important;
}
</style>
</head>
<body>
<?php include('common/header.php') ?>
<!-- details-->
<div class="details">
	<div class="container">
		<div class="row" >
			<div class="col-md-8">
				<div class="row  border">
					<div class="legend_product">
						<img src="<?=base_url().@$products['product_image']?>" class="pencil">
						<h2><?=@$products['title']?></h2>
						<p class="prdocuts_img"> <?=@stripslashes($products['description'])?></p>
					</div>
					<?php if(isset($products['soldout_status']) && $products['soldout_status'] == 'Y'){ ?> 
      				<div class="legend_product1">
						<div class="vl"></div>
						<div class="vl_img">
							<?php
						    $avlstock = $products['stock'] * 100 / $products['totalStock'];
						    $remStick = 100 - $avlstock;
							?>
							<div class="box">
    							<div class="chart" data-percent="<?=number_format($remStick,0)?>" ><?=number_format($remStick,0)?>%</div>
  							</div>
						</div>
						<h2 class="textes" style="left:12px;">Sold</h2>
          			</div>
          			<?php }?>
					<div class="legend_product">
						<?php if(!empty($prize['prize_image'])){ ?>
						<img src="<?=base_url().@$prize['prize_image']?>" class="pencil">
						<?php }else{ ?>
						<img src="<?=base_url().'/assets/img/NO_IMAGE.jpg'?>" class="pencil">
						<?php } ?>
						
						<h2>Get a chance to win: <?=@$prize['title']?></h2>
						<p><?=@stripslashes($prize['description'])?></p>
					</div>
						<div class="hearts">
							<a href="javascript:void();" name="wishlist" data-id="<?=$products['products_id']?>" class="heart_icon">
								<?php if($prodData):if($prodData['wishlist_product'] <> 'Y'):?>
										<i class="fa fa-heart heart_icon" title="Mark Favourite"></i>
									<?php else:?>
										<i class="fa fa-heart heart_icon active" title="Remove from Favourite"></i>
									<?php endif;?>
								<?php else:?>
									<i class="fa fa-heart heart_icon" title="Mark Favourite"></i>
								<?php endif;?>
							</a>
							<?php 
							if($this->session->userdata('DZL_USERID')):
							   if($this->session->userdata('DZL_USERSTYPE') == 'Users' && $this->session->userdata('DZL_USERS_REFERRAL_CODE')):
								$productShareUrl  = generateProductShareUrl($products['products_id'],$this->session->userdata('DZL_USERID'),$this->session->userdata('DZL_USERS_REFERRAL_CODE'));
							?>
								<a href="javascript:void(0);" class="share" data-toggle="modal" data-target="#shareModal"><i class="fas fa-share" title="Share Campaigns" ></i></a>
								<!-- Modal -->
								<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999;margin-top: 10%;">
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
											    <li class="social-share facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i><span style="padding-left:10px;">Share on Facebook</span></li>
											    <li class="social-share twitter"><i class="fab fa-twitter" aria-hidden="true"></i><span style="padding-left:10px;">Share on Twitter</span></li>
											    <li class="social-share linkedin"><i class="fab fa-linkedin" aria-hidden="true"></i><span style="padding-left:10px;">Share on LinkedIn</span></li>
											    <li class="social-share google"><i class="fab fa-google"></i><span style="padding-left:10px;">Share on Google</span></li>
											    <li class="social-share whatsapp"><i class="fab fa-whatsapp"></i><span style="padding-left:10px;">Share on Whatsapp</span></li>
										    </ul>
										</div>
										<?php /* ?>
								        <input type="text" id="copyShareInput" class="copyShareUrlInput" value="<?php echo $productShareUrl; ?>" style="width:100%">
								        <br>
										<button class="copyShareUralToClipBoard">Copy url</button>
										<?php */ ?>
								      </div>
								    </div>
								  </div>
								</div>
							<?php else: ?>
								<a href="javascript:void(0);" class="share"><i class="fas fa-share" title="Share Campaigns" ></i></a>
							<?php endif; ?>
						<?php else: ?>
							<a href="javascript:void(0);" class="share userLoginError"><i class="fas fa-share" title="Share Campaigns" ></i></a>
						<?php endif; ?>
						</div>
				</div>
		</div>	
	<div class="col-md-4 ">
<div class="product_points">
<div class="date_item">
	<h2>Max draw date <?=isset($products['draw_date'])?date('d M, Y', strtotime($products['draw_date'])):''?></h2>
	<p>or when the campaign is sold out, Whichever is earlier</p>
</div>
<div class="date_item1">
	<div class="items_2">
		<h2>Price</h2>
		<p>inclusive Of VAT</p>
	</div>
	<div class="items_2">
		<h3>AED <?=@number_format($products['adepoints'],2)?></h3>
	</div>
</div>

<?php
if($this->session->userdata('DZL_USERID')){
	$wcon1['where'] = [	
		'user_id' 	=> 	(int)$this->session->userdata('DZL_USERID'),
		'id' =>	(int)$products['products_id'] 
	];
	$check = $this->geneal_model->getData2('count','da_cartItems',$wcon1);
	if($check == 0){ ?>
		<button class="default-btn addremove add_cart" data-id='<?=$products['products_id']?>' id="add_cart">Add To Cart</button>
	<?php }else{ ?>
		<button class=" addremove edit_cart" data-id='<?=$products['products_id']?>' id="add_cart">Add To Cart</button>
	<?php }
}else{ 
	if(!empty($this->cart->contents())){
		$check = $this->geneal_model->checkCartItems($products['products_id']);	
	}else{
		$check = 0;
	}
	if($check == 1){ //echo $check; ?>
		<button class=" addremove edit_cart" data-id='<?=$products['products_id']?>' id="add_cart">Add To Cart</button> 
	<?php }else{ //echo $check; ?>
		<button class="default-btn  addremove add_cart" data-id='<?=$products['products_id']?>' id="add_cart">Add To Cart</button>
<?php } }	?>


</div>
</div>
</div>
</div>
	
</div>

<?php if(!empty($prize['prize1']) | !empty($prize['prize2']) | !empty($prize['prize3'])){ ?>


<div class="prize">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-12 px-0">
				<h3 class="default-heading">Prize Detail<br>For First Three Winners.</h3>	
			</div>
		<?php if(!empty($prize['prize1'])){ ?>
		<div class="col-lg-3 col-12">
			<div class="prize_box">
				<h3 class="default-heading">Prize 1</h3>
				<p>AED <?=@number_format($prize['prize1'],2)?> Cash</p>
			</div>
		</div>
		<?php } ?>
		<?php if(!empty($prize['prize2'])){ ?>
		<div class="col-lg-3 col-12">
			<div class="prize_box">
				<h3 class="default-heading">Prize 2</h3>
				<p>AED <?=@number_format($prize['prize2'],2)?> Cash</p>
			</div>
		</div>
		<?php } ?>
		<?php if(!empty($prize['prize3'])){ ?>
		<div class="col-lg-3 col-12">
      		<div class="prize_box">
				<h3 class="default-heading">Prize 3</h3>
				<p>AED <?=@number_format($prize['prize3'],2)?> Cash</p>
			</div>
		</div>
		<?php } ?>
		</div>
	</div>
</div>
<?php }?>
<!-- 
<div class="product">
	<div class="container">
	<div class="row">
			<div class="col-lg-12 col-12">
				<h3 class="default-heading">Buy a Blanco Pencil and get a chance to win<br>MacBook Air Bundle</h3>
				
			</div>
		</div>
	</div>
</div>

	<div class="tab">
	<div class="container">
	<div class="row">
			<div class="col-lg-12 col-12 px-0">
				<div class="tabs_content">
				  <button class="tablinks" id="defaultOpen" onclick="openCity(event, 'London')">Prize Details</button>
				  <button class="tablinks" onclick="openCity(event, 'Paris')">Product Details</button>
             </div>

			<div id="London" class="tabcontent">
			  <p>This unique watch symbolizes a fusion of time, culture and development through its combination of composite concrete and actual desert sand. Limited to just 100 pieces, the “Desert Fusion” watch was produced in celebration of the UAE’s 50th anniversary of its founding. The concrete of the case and the bezel recalls the futuristic architecture of UAE cities, while the orange-tinted sand on the dial symbolizes its vast expanses of desert. Together they form a composition of contrasting and complementary colours, prolonged by a camel leather strap. As for the sapphire crystal, it bears a special “Year of the Fiftieth” inscription.</p>
			</div>

			<div id="Paris" class="tabcontent">
			  <p>This unique watch symbolizes a fusion of time, culture and development through its combination of composite concrete and actual desert sand. Limited to just 100 pieces, the “Desert Fusion” watch was produced in celebration of the UAE’s 50th anniversary of its founding. The concrete of the case and the bezel recalls the futuristic architecture of UAE cities, while the orange-tinted sand on the dial symbolizes its vast expanses of desert. Together they form a composition of contrasting and complementary colours, prolonged by a camel leather strap. As for the sapphire crystal, it bears a special “Year of the Fiftieth” inscription.</p> 
			</div>
          </div>
		</div>
	</div>
</div>
		<div class="sepcific">
	<div class="container px-0">
	<div class="row ">
		<div class="col-md-12 ">
		<div class="profiles">
			<h4 class="information" >Specifications</h4>
			</div>
		</div>
		</div>
		<div class="row user">
		
		<div class="col-md-3 px-0">
			
			<div class="sidenav">
              
				<ul>
					
					<li><a href="#">Case</a></li>
					<li><a href="#">Diameter</a></li>
					<li><a href="#">Dial</a></li>
					<li><a href="#">Functions</a></li>
					<li><a href="#">Strap</a></li>
					<li><a href="#">Power</a></li>
					<li><a href="#">Limited</a></li>
					<li><a href="#">Resistance</a></li>
					
				</ul>
			</div>
		
		</div>
			<div class="col-md-9 px-0">
			
		<div class="sidenav1">
				<ul>
					<li><a href="#">Composite concrete & sapphire back</a></li>
					<li><a href="#">45mm</a></li>
					<li><a href="#">Desert sand with sapphire</a></li>
					<li><a href="#">Hours, minutes, chronograph</a></li>
					<li><a href="#">Camel leather and black rubber</a></li>
					<li><a href="#">42 hours reserve</a></li>
					<li><a href="#">100 pieces</a></li>
					<li><a href="#">Water-resistant to 50 metres</a></li>
				</ul>
			</div>
		
					
				</div>
			</div>
		</div>
	</div> -->
</div>
<!--footer-->
<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>
<!-- Animation Js -->
<script>
	AOS.init({
	  duration: 1200,
	})
</script>
<script type="text/javascript">
	function openCity(evt, cityName) {
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
	tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
	tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(cityName).style.display = "block";
	evt.currentTarget.className += " active";
	}
	document.getElementById("defaultOpen").click();
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
	0: { items: 1 },
	600: { items: 1 },
	1024: { items: 1 },
	1366: { items: 1 }
	}
	});
</script>
<!-- Home Closing Soon Slider -->
<script>
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
	0: { items: 1 },
	600: { items: 3 },
	1024: { items: 4 },
	1366: { items: 4 }
	}
	});
</script>
<!-- Home Sold Out Slider -->
<script>
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
	0: { items: 1 },
	600: { items: 3	}, 
	1024: { items: 4 },
	1366: { items: 4 }
	}
	});
</script>
<!-- Testimonial Slider -->
<script>
	var $owl = $('#testimonial');

	$owl.children().each( function( index ) {
	$(this).attr( 'data-position', index ); // NB: .attr() instead of .data()
	});

	$owl.owlCarousel({
	center: true,
	loop: true,
	dots:true,
	nav:true,
	responsive: {
	0: { items: 1 },
	600: { items: 1 },
	800: { items: 3	},
	1366: { items: 3 }
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
	$(document).ready(function(){
	$(document).on("click", "button[id='add_cart']", function () {
	var product_id = $(this).attr('data-id');
	var curobj = $(this);
	curobj.html("Added To Cart");

	curobj.addClass('edit_cart');
	curobj.removeClass('add_cart');

	var ur = '<?=base_url()?>';
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
						curobj.html('<i class="fa fa-heart heart_icon active" title="Remove from Favourite"></i>');
					} else if (result == 'removedfromwishlist') {
						curobj.html('<i class="fa fa-heart heart_icon" title="Mark Favourite"></i>');
					}
				}
			});
		});
	});
</script>
<script>
	$(function() {
	  $('.chart').easyPieChart({
	    size: 70,
	    barColor: "#dc3545b0",
	    
	    lineWidth: 6,
	    trackColor: "#98fb987d",
	    lineCap: "circle",
	    animate: 2000,
	  });
	});
	    $(function() {
	  $('.chart1').easyPieChart({
	    size: 70,
	    barColor: "#dc3545b0",
	    
	    lineWidth: 6,
	    trackColor: "#98fb987d",
	    lineCap: "circle",
	    animate: 2000,
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
<script type="text/javascript">
    function socialWindow(pageUrl,url) {
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
	}
	$(document).on('click','.social-share.facebook',function(){
		var shareUrl =	$(this).closest('.sociallinks').next('.copyShareUrlInput').val();
		var pageUrl = encodeURIComponent(shareUrl);
		var url = "https://www.facebook.com/sharer.php?u=" + pageUrl;
        socialWindow(shareUrl,url);
	});
	$(document).on('click','.social-share.twitter',function(){
		var shareUrl =	$(this).closest('.sociallinks').next('.copyShareUrlInput').val();
		var pageUrl = encodeURIComponent(shareUrl);
		var tweet = encodeURIComponent($("meta[property='og:description']").attr("Dealzarabia product share"));
		var url = "https://twitter.com/intent/tweet?url=" + pageUrl + "&text=" + tweet;
        socialWindow(shareUrl,url);
	});
	$(document).on('click','.social-share.linkedin',function(){
		var shareUrl =	$(this).closest('.sociallinks').next('.copyShareUrlInput').val();
		var pageUrl = encodeURIComponent(shareUrl);
		var url = "https://www.linkedin.com/shareArticle?mini=true&url=" + pageUrl;
        socialWindow(shareUrl,url);
	});
	$(document).on('click','.social-share.google',function(){
		var shareUrl =	$(this).closest('.sociallinks').next('.copyShareUrlInput').val();
		var pageUrl = encodeURIComponent(shareUrl);
		var url = 'https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=&su=Dealzarabia+product+share&body='+pageUrl+'&ui=2&tf=1&pli=1';
        socialWindow(shareUrl,url);
	});
	$(document).on('click','.social-share.whatsapp',function(){
		var shareUrl =	$(this).closest('.sociallinks').next('.copyShareUrlInput').val();
		var pageUrl = encodeURIComponent(shareUrl);
		var url = "https://wa.me/whatsappphonenumber/?text=" + pageUrl;
        socialWindow(shareUrl,url);
	});
</script>
</body>
</html>
