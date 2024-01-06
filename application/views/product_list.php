<?php //print_r($products); die(); ?>

<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
    <link href="<?=base_url()?>/assets/css/index.css" rel="stylesheet">
    </style>
</head>
<body>
<?php include('common/header.php') ?>
<div class="product_list">
	<div class="container">
	    <div class="row">
			<div class="col-lg-12 col-md-12 col-12">
			<img src="assets/img/Product-banner.jpg" class="product" alt="product_banner">
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12 col-md-12 col-12">
				<h3 class="default-heading heading_product">Our Products</h3>
			</div>
		</div>
		
		<div class="row">
			<?php if($products){
			 foreach ($products as $key => $item) {
			 	$valid = $item['validuptodate'].' '.$item['validuptotime'].':0';
			$today = date('Y-m-d H:i:s');
			if(strtotime($valid) > strtotime($today)){
			 ?>
			
			<div class="col-lg-6 col-md-6 col-12">
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
										<h2 class="textes heading_big" style="">Sold</h2>
									<?php } ?>
									<!-- <img src="<?=base_url().$item['product_image']?>" class="img-fluid" alt=""> -->
								</div>

								<?php $data2 = $this->geneal_model->getParticularDataByParticularField('prize_image','da_prize', 'product_id', $item['products_id']); if($item['product_image']){ ?>
									<img src="<?=base_url().$item['product_image']?>" class="img-fluid product_img my_img" onclick="show_product(<?=$key;?>)"   alt="<?=$item['product_image_alt']?>">
								<?php }else{ ?>
									<img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-fluid" alt="<?=$item['product_image_alt']?>">
								<?php } ?>
								
								<div id="show_product_<?=$key?>" class="modal fade">
	                                <div class="modal-dialog">
	                                    <div class="modal-content">
	                                       
	                                        <div class="modal-body">
	                                            <img src="<?=base_url().$item['product_image']?>" class="img-fluid product_img my_img" alt="<?=$item['product_image_alt']?>">
	                                        </div>
	                                       
	                                    </div>
	                                </div>
	                            </div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-8 col-12 text-align">
								<div class="product-des">
									<?php if ($item['title']): ?>
										<h5 style="margin-top:5px;">Buy<span style="font-weight:700;"> <?=stripslashes($item['title'])?></span> &</h5>
									<?php endif ?>
									<h2><i style="color: #d12a2b;font-weight: 900;">Win!</i></h2>
									<?php if($item['prizeDetails'][0]['prize1'] != 0 && $item['prizeDetails'][0]['prize2'] != 0 ): ?>
										<h5 style="margin-top:5px;"><span>1<sup>st</sup> Prize - <?=$item['prizeDetails'][0]['prize1']?> AED</span></h5>
										<?php if($item['prizeDetails'][0]['prize2'] != 0 ): ?>
											<h5 style="margin-top:5px;"><span>2<sup>nd</sup> Prize - <?=$item['prizeDetails'][0]['prize2']?> AED</span></h5>
										<?php endif; ?>
										<?php if($item['prizeDetails'][0]['prize3'] != 0 ): ?>
											<h5 style="margin-top:5px;"><span>3<sup>rd</sup> Prize - <?=$item['prizeDetails'][0]['prize3']?> AED</span></h5>
										<?php endif; ?>
									<?php else: ?>
										<h5 style="margin-top:5px;"><span><?=substr($item['prizeDetails'][0]['title'],0,20)?></span></h5>
									<?php endif; ?>
									<h5 style="margin-top:5px;"><span >Price : </span><span style="color: #0d9e51; font-weight: 700;">AED<?=$item['adepoints']?>.00</span></h5>
									<?php /* ?><p>Max Draw Date : <?=date('d-M-Y', strtotime($item['draw_date']))?></p>
									<div class="countdown" data-Date='<?=date('Y-m-d H:i:s', strtotime($item['draw_date']))?>'><?php */ ?>
								
								</div>
								<ul class="explore_button">
										<li>
											<?php
										if($this->session->userdata('DZL_USERID')){
											$wcon1['where'] = [	
												'user_id' 	=> 	(int)$this->session->userdata('DZL_USERID'),
												'id' =>	(int)$item['products_id'] 
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
												<button class=" addremove edit_cart" data-id='<?=$item['products_id']?>' id="add_cart"> Added To<i class="fas fa-shopping-cart" aria-hidden="true"></i></button>
												<?php } ?>
											<?php }
										}else{ 
											if(!empty($this->cart->contents())){
												$check = $this->geneal_model->checkCartItems($item['products_id']);	
											}else{
												$check = 0;
											}
											if($check == 1){ //echo $check; ?>
											<?php if($item['commingSoon'] == 'N'){ ?>
												<button class="addremove edit_cart" data-id='<?=$item['products_id']?>' id="add_cart"> Added To Cart
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

												
										<?php } }	?>
										</li>
										<li>
											<a href="<?=base_url('product-details/'.$item['title_slug'])?>" class="white-btn  <?php echo $item['commingSoon'] != 'N' ? 'only-prize-detail':''; ?>    ">Prize Details</a>
										</li>
									</ul>
							        	<div class="countdown" data-Date='<?=date('Y-m-d H:i:s', strtotime($item['validuptodate'].' '.$item['validuptotime']))?>'>
						                 <!-- <p><?=date('F d, Y', strtotime($item['draw_date']))?></p> -->
						            </div>
						           
							</div>
							<div class="col-lg-3 col-md-3 col-sm-2 col-12 align_leftes">
							    <div class="hearts">
									<!-- <a href="javascript:void();" name="wishlist" data-id="<?=$item['products_id']?>" class="heart_icon">
										<?php 
											$prowhere['where']				=	array('users_id'=>(int)$this->session->userdata('DZL_USERID'),'product_id'=>(int)$item['products_id']);
											$prodData						=	$this->common_model->getData('single','da_wishlist',$prowhere);
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
														    <li class="social-share facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i><span style="padding-left:10px;">Share on Facebook</span></li>
														    <li class="social-share twitter"><i class="fab fa-twitter" aria-hidden="true"></i><span style="padding-left:10px;">Share on Twitter</span></li>
														    <li class="social-share linkedin"><i class="fab fa-linkedin" aria-hidden="true"></i><span style="padding-left:10px;">Share on LinkedIn</span></li>
														    <li class="social-share google"><i class="fab fa-google"></i><span style="padding-left:10px;">Share on Google</span></li>
														    <li class="social-share whatsapp"><i class="fab fa-whatsapp"></i><span style="padding-left:10px;">Share on Whatsapp</span></li>
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
									<?php if($item['prizeDetails'][0]['prize_image'] <> ''): ?>
										<img src=	"<?=base_url().$item['prizeDetails'][0]['prize_image']?>" class="dolleres " onclick="show_prize(<?=$key;?>)" >
										 
									<?php else: ?>
										<img src=	"<?=base_url()?>/assets/productsImage/doller.png" class="dolleres">
									<?php endif; ?>
									
									<div id="show_prize_<?=$key?>" class="modal fade">
	                                    <div class="modal-dialog">
	                                        <div class="modal-content">
	                                           
	                                            <div class="modal-body">
	                                                <img src=	"<?=base_url().$item['prizeDetails'][0]['prize_image']?>" class="show_prize_dolleres" alt="product_model">
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
								<img src="assets/img/schedule.png" alt="schedule">
								<div class="calendar">
								<p>Draw on:</p>
								<h1><?=date('d M, Y', strtotime($item['validuptodate']))?></h1>
	                            </div>
							</div>
						  <?php endif; ?>
						  </div>
						
						<?php if ( !empty($item['validuptodate']) && !empty($item['validuptotime'] && $item['countdown_status'] == 'Y' )  ): ?>
						
						  <div class="col-md-6 col-6">
								<div class="countdown" data-Date='<?=date('Y-m-d H:i:s', strtotime($item['validuptodate'].' '.$item['validuptotime']))?>'>
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

						<?php endif ?>
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
	</div>
</div>
<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>
<script type="text/javascript" src="<?=base_url('assets/')?>countdownTimer/multi-countdown.js"></script>
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
	$('.dropdown > .caption').on('click', function() {
	$(this).parent().toggleClass('open');
	});
	$('.dropdown > .list > .item').on('click', function() {
	$('.dropdown > .list > .item').removeClass('selected');
	$(this).addClass('selected').parent().parent().removeClass('open').children('.caption').html($(this).html());
	if($(this).data("item") == "RUB") {
	console.log('RUB');
	} else if($(this).data("item") == "UAH") {
	console.log('UAH');
	} else {
	console.log('USD');
	}
	});
	$(document).on('keyup', function(evt) {
	if((evt.keyCode || evt.which) === 27) {
	$('.dropdown').removeClass('open');
	}
	});
	$(document).on('click', function(evt) {
	if($(evt.target).closest(".dropdown > .caption").length === 0) {
	$('.dropdown').removeClass('open');
	}
	});
</script>
<script>
	$(document).ready(function(){
	$(document).on("click", "button[id='add_cart']", function () {
	var product_id = $(this).attr('data-id');
	var curobj = $(this);
	curobj.html('Added To Cart <i class="fas fa-shopping-cart" aria-hidden="true"></i>');

	curobj.addClass('edit_cart');
	curobj.removeClass('add_cart');

	var ur = '<?=base_url()?>';
	$.ajax({
	url : ur+ "shopping_cart/add",
	method: "POST", 
	data: {product_id: product_id},
	success: function(data){
	var A = '<b><i class="fas fa-shopping-cart"></i>('+data+')</b>'
	
	$('#cartA').empty().append(A)
	
	}
	});
	});
	});
</script>
<script>
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
<script>
	$(document).on('click','.copyShareUralToClipBoard',function(){
	  	var copyText = $(this).parent('.modal-body').find('.copyShareUrlInput');
		copyText.select();
		navigator.clipboard.writeText(copyText.val());
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
	    var left = (screen.width -570) / 2;
	    var top = (screen.height -570) / 2;
	    var params = "menubar=no,toolbar=no,status=no,width=570,height=570,top=" + top + ",left=" + left;  window.open(url,"NewWindow",params);
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
	
	function show_prize(key){
        
        $("#show_prize_"+key).modal('show');
        
    }
    
    function show_product(key){
        
        $("#show_product_"+key).modal('show');
        
    }
    
</script>
</body>
</html>

