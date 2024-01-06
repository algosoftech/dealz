 <?php?>
 <!Doctype html>
<html lang="eng">
<head>	
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
</head>
<body>
<?php include('common/header.php') ?>
<div class="my-profile">
	<div class="container">
		<div class="row">
		<?php include ('common/profile/menu.php') ?>
			<div class="col-md-9">
				<?php include ('common/profile/head.php') ?>
				<div class="users_form user-cart help-block">
					<div class="profiles">
						<h4 class="information">Order Enquiry Related Help</h4> 
					</div>
					<?php $i=1; foreach ($Help as $key => $item) { ?>
					
					<div class="accordion" id="faq">
				
						<div class="card">
						
							<div class="card-header" id="<?=$i++;?>">
								<a href="#" class="btn-header-link" data-toggle="collapse" data-target="#<?=$i;?>" aria-expanded="true" aria-controls="faq1"><?=$item['question']?></a>
							</div>

							<div id="<?=$i;?>" class="collapse show<?=($i>1)?'collapse':''?>" aria-labelledby="faqhead1" data-parent="#<?=$i;?>">
								<div class="card-body">
									<?php echo strip_tags($item['answer']); ?>
								</div>
								
							 </div>
						</div>
					</div>
					<?php $i++; } ?>
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
</body>
</html>