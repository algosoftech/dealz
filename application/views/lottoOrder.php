

<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <style type="text/css">

    		.lotto-section{
				text-align: center;
				font-size: calc(24px + 10%);
    		}

    		.header-section img{
    			width:160px;
    		}

    		.lotto-type-image img{
    			    width: calc(38px + 20%);
    				margin: 30px auto; 
    		}

    		.lotto-number {
				color: #ffffff;
			    background: #3AA9AE;
			    padding: 12px;
			    margin: 10px;
			    border-radius: 73px;
			    line-height: 2.4;
			}

			.header-section{
				background: linear-gradient(168deg, #0F9BCF 0%, #E6E324 100.85%);
				padding: 20px 0;
				margin-bottom: 30px;
			}

			.header-section h3 {
			    margin: 43px auto 0px;
			    color: #ffffff;
				font-size: 24px;
				font-style: normal;
				font-weight: 700;
				line-height: 24px; /* 100% */
				letter-spacing: 0.048px;

			}

			.order-detail-section .row {
			    line-height: 40px;
			}

			.lotto-section .container{
				width: 768px;
				padding: 0px;
			}

			.order-detail-section {
			    max-width: 80%;
			    margin: auto;
			}

			.text-left{
				text-align: left;
			}
			.text-right{
				text-align: right;
			}

    </style>

</head>
<body>
	<!-- profile -->
	<div class="lotto-section">
		<div class="container">
			<div class="header-section">
				<img src="<?=base_url().'/assets/img/white-logo.png'?>">
				
				<h3>MY TICKET </h3>
			</div>
			<div class="order-detail-section">
				<div class="row">
					<div class="col-6 col-sm-6 col-lg-6 col-md-6 text-left">
						Draw Date
					</div>
					<div class="col-6 col-sm-6 col-lg-6 col-md-6 text-right">
						<?=$product['draw_date']; ?>
					</div>
				</div>

				<div class="row">
					<div class="col-6 col-sm-6 col-lg-6 col-md-6 text-left">
						Mabrool <?=$product['lotto_type'];?>
					</div>
					<div class="col-6 col-sm-6 col-lg-6 col-md-6 text-right">
						<?=$orderData['prize_title']; ?>
					</div>
				</div>

				<div class="row">
					<div class="col-6 col-sm-6 col-lg-6 col-md-6 text-left">
						Product
					</div>
					<div class="col-6 col-sm-6 col-lg-6 col-md-6 text-right">
						<?=$product['title']; ?>
					</div>
				</div>

				<div class="row">
					<div class="col-6 col-sm-6 col-lg-6 col-md-6 text-left">
						Price(inclusive VAT 5%)
					</div>
					<div class="col-6 col-sm-6 col-lg-6 col-md-6 text-right">
						<?="AED " .number_format($product['adepoints'],2); ?>
					</div>
				</div>

				<div class="row">
					<div class="col-6 col-sm-6 col-lg-6 col-md-6 text-left">
						Purchased On
					</div>
					<div class="col-6  col-sm-6 col-lg-6 col-md-6 text-right">
						<?=$orderData['created_at'];?>
					</div>
				</div>

				<div class="row">
					<div class="col-6  col-sm-6 col-lg-6 col-md-6 text-left">
						Merchant
					</div>
					<div class="col-6  col-sm-6 col-lg-6 col-md-6 text-right">
						<?=$orderData['store_name'] ?>
					</div>
				</div>
			</div>
			<div class="order-detail-section-2">
				<div class="lotto-type-image">
					<?php if( $product['lotto_type'] == 3 ): ?>
						<img src="<?=base_url().'assets/lotto-img/lotto3.png'?>" class="img-fluid" />
					<?php elseif( $product['lotto_type'] == 4 ): ?>
						<img src="<?=base_url().'assets/lotto-img/lotto4.png'?>" class="img-fluid" />
					<?php elseif( $product['lotto_type'] == 5 ): ?>
						<img src="<?=base_url().'assets/lotto-img/lotto5.png'?>" class="img-fluid" />
					<?php elseif( $product['lotto_type'] == 6 ): ?>
						<img src="<?=base_url().'assets/lotto-img/lotto6.png'?>" class="img-fluid" />
					<?php else: ?>
						<img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-fluid" />
					<?php endif; ?>
				</div>
				<div class="lotto-number-section">


					  <?php 

					  $ticket  = $orderData['ticket'];
					  $ticket  = str_replace('[', '', $ticket);
					  $ticket  = str_replace(']', '', $ticket);
					  $ticket  = str_replace(' ', '', $ticket);
					  $ticket  = explode(',', $ticket);

					 foreach($ticket as $key => $item): $count = $key+1;  ?>
					 	<span class="lotto-number"> <?=$item;?> </span>
					 <?php if(($count%$product['lotto_type'] == 0 )): echo  "<br>"; endif; ?>
					 <?php  endforeach;?>
				</div>

			</div>
			<div class="order-detail-section">
				<div class="row">
					<div class="col-6 col-sm-6 col-lg-6 col-md-6 text-left">
						My Order #
					</div>
					<div class="col-6  col-sm-6 col-lg-6 col-md-6 text-right">
						<?=$orderData['order_id']; ?>
					</div>
				</div>
				 
			</div>

			<div class="order-detail-section">
				<div>
					<h3>LOTTO STAR LLC</h3>
					<p>For more information, visit www.lottostar.com <br> Call us @ 04 355 8533 <br> Email: info@lottostar.com</p>
				</div>
			</div>


		</div>
	</div>


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
</body>
</html>
