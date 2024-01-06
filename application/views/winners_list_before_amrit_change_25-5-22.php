<?php echo ""; ?>
<!Doctype html>
<html lang="eng">
<head>
	<!-- Basic page needs -->	
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
	<style>

.winner_list .product-img:hover{
	    box-shadow: 1px 1px 5px 0px rgb(209 209 209);
	}
.winner_list{
    padding: 120px 0 50px;
    text-align: center;
}
.winner_list .default-heading{
    font-family: Gadugi Bold;
    text-align: center;
    font-size: 30px;
    color: #031b26;
	padding: 0px 0px 30px 0px;
}
.winner_list .default-heading {
    font-family: Gadugi Bold;
    text-align: center;
    font-size: 30px;
    color: #031b26;
    padding: 0px 0px 38px 0px !important;
}
.winner_list  .testimonial-box {
    padding: 30px 20px 10px;
    z-index: 22;
    min-height: 224px;
   margin: 0px 0px 27px 0px;
    opacity: 1;
    background: #fff;
    border: 0;
    border-radius: 10px;
    -webkit-box-shadow: 0px 0px 5px -1px rgb(171 169 171);
    -moz-box-shadow: 0px 0px 5px -1px rgba(171,169,171,1);
    box-shadow: 0px 0px 5px -1px rgb(171 169 171);
    transition: 0.3s;
	width:100%;
}
.winner_list .product-des h6{
	text-align: start;
}
.winner_list .testimonial-box h4 {
    text-transform: revert;
    text-align: center;
    color: #e72d2e;
    font-family: 'Open Sans', sans-serif;
    font-size: 18px;
    margin-bottom: 25px;
    font-weight: 600;
}
.winner_list .product-img img {
    display: block;
    max-width: 100% !important;
}
.winner_list  .testimonial-box h5 {
    text-align: left;
    color: #181818;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    margin: 0;
    font-weight: 600;
}
.winner_list .testimonial-box p {
    text-align: left;
    color: #363636;
    font-family: 'Open Sans', sans-serif;
    margin: 0;
    font-weight: 400;
	font-size: 14px;
    line-height: 24px;
}
.product {
    width: 100%;
    padding-bottom: 30px;
    padding-top: 13px;
}
	</style>
</head>
<body>

<?php include('common/header.php') ?>
<div class="winner_list">
	<div class="container">
	    <div class="row">
			<div class="col-lg-12 col-md-12 col-12">
			<img src="assets/img/Winner-banner.jpg" class="product">
			</div>
		</div> 
		<div class="row">
			<div class="col-lg-12 col-md-12 col-12">
				<h3 class="default-heading">Our Lucky Winners </h3>
			</div>
		</div>
		 
		<div class="row">
			<?php $i=1; foreach ($winners as $key => $items) {
				if($i%2 == 0){ 
					$active = 'active'; 
					$css = "background-color: #ebebeb73;";
				}
				else{ 
					$active = ''; 
					$css = '';
				} ?>
			<div class="col-lg-6 col-md-6 col-12">
				<div class="testimonial-box <?=$active?>" data-position="0" style="<?=$css?>" >
					<h4>Congratulations <?=$items['name']?> </h4>
					<div class="row">
						<div class="col-lg-5 col-md-5 col-12">
							<div class="product-img">
								<img src="<?=base_url().$items['winners_image']?>" class="img-fluid" alt="">
							</div>
						</div>
						<div class="col-lg-7 col-md-7 col-12">
							<div class="product-des">
								<h5><?=$items['title']?></h5>
								<p>Coupon no: <?=$items['coupon']?></p>
								<p>Announced: <?=@date('d M Y h:i A', strtotime($items['creation_date']))?></p>
								<h6>AED<?=number_format($items['adepoints'],2)?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $i++; } ?>
	</div>
</div>
<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>
<!-- Animation Js -->
<script>
	AOS.init({
	  duration: 1200,
	})
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
<!-- Header Dropdown -->
<script>
$('.dropdown > .caption').on('click', function() {
	$(this).parent().toggleClass('open');
});
// $('.price').attr('data-currency', 'RUB');
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
</body>
</html>
