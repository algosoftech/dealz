<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
<style>

	@media (min-width: 300px) and (max-width: 600px){
	    .sociallinks ul li i {
	        position: relative;
	        transform: none;
	    }
	    .sociallinks ul span {
	        display: none;
	    }

	    .sociallinks ul {
		    display: flex!important;
		}

	    .sociallinks ul li {
		    margin: auto !important;
		}
	}
	.img-fluid {
    max-width: 72% !important;
    height: auto;
}


	.user-earningblock .earning-table .row h5 {
		text-align: left;
		font-family: 'Open Sans', sans-serif;
		font-size: 14px;
		font-weight: 600;
		color: #6c757d;
	}

	.active{
		content: "\f004";
		font-weight: 800 !important;
		color: #d12a2b !important;
	}
	.fa-heart.active {
		color: #d12a2b !important;
		font-weight: 400;
	}
	fa-share:hover {
		content: "\f004";
		font-weight: 900 !important;
		font-family: "Font Awesome 5 Pro";
	}
	.fa-share:hover{
		color: #d12a2b !important;   
	}
	.share i:hover {
		color: #d12a2b !important;
	}
	.hearts a:hover{
		color: #d12a2b !important;
	}

	.heart_icon {
		color: rgb(197 24 24)!important;
		font-size: 26px;
	}
	.fa-heart:hover {
		color: #d12a2b !important;
	}
	.default-btn:hover{
		background:#d12a2b !important;
		color:#fff !important;  
	}
	.fa-share:before {
		content: "\f064";
		font-family: "Font Awesome 5 Pro";
	}
	.heart{
		display:flex;
	}
	#main-carousel .owl-dots {
		position: absolute;
		top: 91%;
		left: 50%;
	}
	.fa-heart.active {
		color: #d12a2b !important;
	}
	.sociallinks ul { list-style-type: none; }
	.sociallinks ul li { cursor: pointer; }


	.hearts a:hover{
		color: #d12a2b !important;
	}

	.heart_icon {
		color: rgb(209 209 209);
		font-size: 26px;
	}

	.fa-heart:hover {
		content: "\f004";
		font-weight: 800 !important;
	}
	.share {
		width: 30px;
		height: 30px;
		display: block;
		padding: 0;
		background: #0d9e51;
		border-radius: 50px;
		line-height: 30px;
		overflow: hidden;
		transition: 600ms all;
		text-align: center;
		margin-left: auto;
	}

	.share i::before {
		padding: 0;
		background: transparent;
		border-radius: unset;
		color: #fff !important;
	}

	.showInLodeMore {display: none;}

	.box .chart {
		position: relative;
		width: 100%;
		height: 100%;
		text-align: center;
		font-size: 12px;
		line-height: 108px;
		height: 160px;
		color: #300808;
		left: -5px;
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

	#exampleModalLabel{
		font-size: 20px;
	}

	.cart-table ul li:first-child{
		margin-left: auto;
	}
	@media (min-width: 360px) and (max-width: 600px) {
		.table {
			width: 100%;
			max-width: 100%;
			margin-bottom: 1rem;
			background-color: transparent;
			height: 195px;
		}
		.user-cart .cart-box table h5 {
			font-family: 'Open Sans', sans-serif;
			font-size: 13px;
			color: #242424;
			margin-bottom: 0px;
			line-height: 20px;
		}
		.table ul li{
			margin: 10px 0px;
		}
		.table td:nth-child(2){
			width:80% !important;
			padding: 10px 0px !important;
		}
		.cart-table ul li a.remove-item {
			border: 1px solid #c1172d;
			color: #c1172d;
			padding: 7px 7px !important;
			font-family: 'Open Sans', sans-serif;
			font-size: 13px;
			border-radius: 8px;
		}
		#received{
			padding: 7px 31px !important;
		}
	}
</style>
<link rel="stylesheet" type="text/css" href="<?=base_url('/assets/css/dealzarabia.css');?>">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>
<?php include('common/header.php') ?>
<div class="my-profile">
	<div class="container">
		<div class="row">
		<?php include ('common/profile/menu.php') ?>
			<div class="col-md-9">
				<?php include ('common/profile/head.php') ?>
				
				<div class="user-earningblock">
							<?php if ($this->session->flashdata('error')) { ?>
							<div class="users_members">
								<div class="userss">
								<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
								</div>
							</div>
								<?php } ?>
								<?php if ($this->session->flashdata('success')) { ?>
							<div class="users_members">
								<div class="userss">
				 				<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
				 				</div>
				 			</div>
								<?php  } ?>							
						<div class="earning-table">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-6">
									<h5>Signup Bonus</h5>
								</div>
								<div class="col-lg-6 col-md-6 col-6">
									<?php  @$signupBonus = floor(($signupBonus*100))/100;  ?>
									<h6><?=number_format($signupBonus,2)?></h6>
								</div>
							</div>
							
							<div class="row">
								<div class="col-lg-6 col-md-6 col-6">
									<h5>Earn through Topup</h5>
								</div>
								<div class="col-lg-6 col-md-6 col-6">
									<?php  @$topup = floor(($topup*100))/100;  ?>
									<h6><?=number_format($topup,2)?></h6>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-6 col-md-6 col-6">
									<h5><?php /* ?>Earn through Cashback<?php */ ?>
								        Purchase Discount</h5>
								</div>
								<div class="col-lg-6 col-md-6 col-6">
									<?php  @$cashback = floor(($cashback*100))/100;  ?>
									<h6><?=number_format($cashback,2)?></h6>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-6 col-md-6 col-6">
									<h5>Earn through Referral</h5>
								</div>
								<div class="col-lg-6 col-md-6 col-6">
									<?php  @$referral = floor(($referral*100))/100;  ?>
									<h6><?=number_format($referral,2)?></h6>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-6">
									<h5><span>Total Earned</span></h5>
								</div>
								<div class="col-lg-6 col-md-6 col-6">
									<?php  @$totalEarned = floor(($totalEarned*100))/100;  ?>
									<h6><span><?=@number_format($totalEarned,2)?></span></h6>
								</div>
							</div>
							<!-- <div class="row">
								<div class="col-lg-6 col-md-6 col-12">
									<h5><span>Total Spent</span></h5>
								</div>
								<div class="col-lg-6 col-md-6 col-12">
									<h6><span><?=number_format($totalSpend,2)?></span></h6>
								</div>
							</div> -->
						</div>
						<?php if($products){ $oCi=1; foreach ($products as $key => $item) {
							$valid = $item['validuptodate'].' '.$item['validuptotime'].':0';
							$today = date('Y-m-d H:i:s');
							if(strtotime($valid) > strtotime($today)){
								if($oCi > 3):
									$currentTabClass  = 'showInLodeMore';
								else:
									$currentTabClass  = '';
								endif;
								$sharewhere['where']=	array('users_id'=>(int)$this->session->userdata('DZL_USERID'),'products_id'=>(int)$item['products_id']);
								$shareCount			=	$this->common_model->getData('count','da_product_share',$sharewhere,'','0','0');
								$accumulatedPint    =	((($item['adepoints']*$item['share_percentage_first'])/100)*$shareCount);
						 ?>
						<div class="user-cart <?php echo $currentTabClass; ?>">
							<div class="cart-box">
								<div class="table-responsive cart-table">
									<table class="table">
										<tbody>
											<tr>
												<td width="25%">
													<div class="cart-product">
														<?php 
															$data2 = $this->geneal_model->getParticularDataByParticularField('prize_image','da_prize', 'product_id', $item['products_id']);
															if($item['product_image']){ ?>
															<img src="<?=base_url().$item['product_image']?>" class="img-fluid" alt="<?=$items['product_image_alt']?>">
															<?php }else{ ?>
																<img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-fluid" alt="<?=$items['product_image_alt']?>">
														<?php } ?>
													</div>
												</td>
												<td width="60%">
													<div class="table-hd">
														<h3><?=$item['title']?></h3>
														<h5><?=substr(strip_tags($item['description']),0,100)?>...</h5>
														<h5>AED<?=$item['adepoints']?>.00</h5>
													</div>
													<ul>
														<li>
															<a href="javascript:void(0);" class="remove-item success"> Accumulated Point <span> <?php echo $accumulatedPint; ?></span>   </a>
														</li>
														<li>
															<a href="javascript:void(0);" class="remove-item" id="received"> Est. Earnings  <span><?=$item['share_percentage_first']?>%</span> </a>
														</li>
													</ul>
												</td>
												<td width="15%">
													<!-- share Button start -->
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
																					<li class="social-share facebook"><i class="fab fa-facebook-f" aria-hidden="true" alt="Share on Facebook"></i><span style="padding-left:10px;">Share on Facebook</span></li>
																					<li class="social-share twitter"><i class="fab fa-twitter" aria-hidden="true"  alt="Share on Twitter"></i><span style="padding-left:10px;">Share on Twitter</span></li>
																					<li class="social-share linkedin"><i class="fab fa-linkedin" aria-hidden="true"  alt="Share on LinkedIn"></i><span style="padding-left:10px;">Share on LinkedIn</span></li>
																					<li class="social-share google"><i class="fab fa-google"></i><span style="padding-left:10px;"  alt="Share on Google">Share on Google</span></li>
																					<li class="social-share whatsapp"><i class="fab fa-whatsapp"></i><span style="padding-left:10px;"  alt="Share on Whatsapp">Share on Whatsapp</span></li>
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
													<!-- share Button end -->
													<?php /*if(isset($item['soldout_status']) && $item['soldout_status'] == 'Y'){ ?> 
                                                        <div class="">
                                                            <?php
                                                            $avlstock = $item['stock'] * 100 / $item['totalStock'];
                                                            $remStick = 100 - $avlstock;
                                                            ?>
                                                            <div class="box">
                                                                <div class="chart" data-percent="<?=number_format($remStick,0)?>" ><?=number_format($remStick,0)?>%</div>
                                                            </div>
                                                        </div>
                                                        <h2 class="textes" style="left:12px !important;">Sold</h2>
                                                    <?php } */ ?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<?php /* ?>
							<div class="cart-box">
								<div class="table-responsive cart-table">
									<table class="table">
										<tbody>
											<tr>
												<td width="20%">
													<div class="cart-product">
														<img src="<?=base_url('assets/')?>img/cart-img.png" class="img-fluid" alt="">
													</div>
												</td>
												<td width="50%">
													<div class="table-hd">
														<h3>iPad Air</h3>
														<h5>The all new 2022 Mercedes-AMG G63 is a legend raised to a higher power for a new era!</h5>
													</div>
													<ul>
														<li>
															<a href="#" class="remove-item success"> Accumulated Point <span>1</span>   </a>
														</li>
														<li>
															<a href="#" class="remove-item"> Est. Earnings  <span>5%</span> </a>
														</li>
													</ul>
												</td>
												<td>
													<div class="inquiry-and-buy">
														<p><a href="#"><i class="fas fa-heart"></i></a> &nbsp; &nbsp; <a href="#"><i class="fas fa-share-alt"></i></a></p>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="cart-box">
								<div class="table-responsive cart-table">
									<table class="table">
										<tbody>
											<tr>
												<td width="20%">
													<div class="cart-product">
														<img src="<?=base_url('assets/')?>img/cart-img.png" class="img-fluid" alt="">
													</div>
												</td>
												<td width="50%">
													<div class="table-hd">
														<h3>iPad Air</h3>
														<h5>The all new 2022 Mercedes-AMG G63 is a legend raised to a higher power for a new era!</h5>
													</div>
													<ul>
														<li>
															<a href="#" class="remove-item success"> Accumulated Point <span>1</span>   </a>
														</li>
														<li>
															<a href="#" class="remove-item"> Est. Earnings  <span>5%</span> </a>
														</li>
													</ul>
												</td>
												<td>
													<div class="inquiry-and-buy">
														<p><a href="#"><i class="fas fa-heart"></i></a> &nbsp; &nbsp; <a href="#"><i class="fas fa-share-alt"></i></a></p>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<?php */ ?>
						</div>
						<?php $oCi++; } } } ?>
					</div>
					<?php if($oCi >3): ?>
						<div class="load-more-product">
							<div class="row">
								<div class="col-lg-12 col-12">
									<input type="hidden" name="lodeMoreAction" id="lodeMoreAction" value="N">
									<a href="javascript:void(0);" class="color-white-btn lodeMoreProduct">Load More Products</a>
								</div>
							</div>
						</div>
					<?php endif; ?>
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
</script>
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
</script>
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
</script>
<script>
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
<script>
	function makesvg(percentage, inner_text=""){

	  var abs_percentage = Math.abs(percentage).toString();
	  var percentage_str = percentage.toString();
	  var classes = ""

	  if(percentage < 0){
	    classes = "danger-stroke circle-chart__circle--negative";
	  } else if(percentage > 0 && percentage <= 30){
	    classes = "warning-stroke";
	  } else{
	    classes = "success-stroke";
	  }

	 var svg = '<svg class="circle-chart" viewbox="0 0 33.83098862 33.83098862" xmlns="http://www.w3.org/2000/svg">'
	     + '<circle class="circle-chart__background" cx="16.9" cy="16.9" r="15.9" />'
	     + '<circle class="circle-chart__circle '+classes+'"'
	     + 'stroke-dasharray="'+ abs_percentage+',100"    cx="16.9" cy="16.9" r="15.9" />'
	     + '<g class="circle-chart__info">'
	     + '   <text class="circle-chart__percent" x="17.9" y="15.5">'+percentage_str+'%</text>';

	  if(inner_text){
	    svg += '<text class="circle-chart__subline" x="16.91549431" y="22">'+inner_text+'</text>'
	  }
	  
	  svg += ' </g></svg>';
	  
	  return svg
	}

	(function( $ ) {

	    $.fn.circlechart = function() {
	        this.each(function() {
	            var percentage = $(this).data("percentage");
	            var inner_text = $(this).text();
	            $(this).html(makesvg(percentage, inner_text));
	        });
	        return this;
	    };

	}( jQuery ));

	$(function () {
	     $('.circlechart').circlechart();
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
					var A = '<b><i class="fas fa-shopping-cart"></i>('+data+')</b>'
					$('#cartA').empty().append(A)
					
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
		var url = "https://api.whatsapp.com/send?text=" + pageUrl;
        socialWindow(shareUrl,url);
	});
</script>
<script type="text/javascript">
	$(document).on('click','.lodeMoreProduct',function(){
		var lodeMoreAction  =	$('#lodeMoreAction').val();
		if(lodeMoreAction == 'N'){
			$('#lodeMoreAction').val('Y');
			$('.lodeMoreProduct').html('Less More Products');
			$('.showInLodeMore').css('display','block');
		} else {
			$('#lodeMoreAction').val('N');
			$('.lodeMoreProduct').html('Load More Products');
			$('.showInLodeMore').css('display','none');
		}
	});
</script>
</body>
</html>