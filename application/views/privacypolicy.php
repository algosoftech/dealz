<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
</head>
<body>
<?php include('common/header.php') ?>
<div class="about-section">
	<div class="container">
	
		<div class="row">
		<?php foreach ($Privacy as $key => $item) { ?>
			<div class="col-lg-12 col-md-12 col-12" style="text-align: left;">
				<h3 class="default-heading"><?=$item['title']?>  <!-- <img src="<?=base_url('assets/')?>img/logo.png" class="img-fluid" alt=""> --></h3>
				<p><?=$item['description']?></p>
				
			</div>
			<?php } ?>
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
</body>
</html>
