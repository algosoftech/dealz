<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <style type="text/css">
    	.heading {
		    text-transform: capitalize;
		}

		.about-section {
		    padding: 130px 0 50px;
		    min-height: 630px;
		}
    </style>

</head>
<body>
<?php include('common/header.php') ?>
<div class="about-section">
	<div class="container">

		<h2 class="text-left mb-5">FAQS</h2>

		<div class="accordion accordion-flush" id="accordionFlushExample">
			<?php if($faqs):  ?>
				<?php foreach($faqs['faq_list'] as $key => $items): ?>
				  <div class="accordion-item">
				    <h2 class="accordion-header" id="flush-heading<?=$key?>">
				      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?=$key?>" aria-expanded="false" aria-controls="flush-collapse<?=$key?>">
				         <div class="heading">
				         	<h5><?=$items->heading;?></h5>
				         </div>
				      </button>
				    </h2>
				    <div id="flush-collapse<?=$key?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?=$key?>" data-bs-parent="#accordionFlushExample">
				      <div class="accordion-body text-left">
				      	<p class="description"><?=$items->description;?></p>
				      </div>
				    </div>
				  </div>
			    <?php endforeach; ?>
		    <?php endif; ?>
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
