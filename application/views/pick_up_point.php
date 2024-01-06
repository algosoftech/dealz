<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
    <style>
		  .error{
            color:red;
        }
    	.select_points h1 {
		    font-weight: 600;
		    margin-block-end: 0.5em;
		    font-size: 25px;
		    background: #c42828;
		    border: 1px solid #ec2d2f;
		    padding: 10px 25px;
		    border-radius: 8px 8px 8px 8px;
		    color: #fff;
		    font-family: 'Open Sans', sans-serif;
		    font-size: 15px;
		    font-weight: 400;
		    margin-top: 0px;
		    cursor: pointer;I
		}

		.select_location li {
		    background-color: #eeeeee;
		    color: #000;
		    font-size: 15px;
		    padding: 10px;
		    margin: 9px;
		    border: 1px solid #f4f4f4;
		    text-align: left;
		    border-radius: 8px;
		    list-style-type: none;
		}

		.select_location li a {
			font-family: 'Open Sans', sans-serif;
			word-wrap: break-word;
			font-size: 13px;
			font-weight: 600;
			text-align: left;
			color: #181818;
			text-decoration: none;
			display: block;
		}

		@media only screen and (min-width:300px) and (max-width:600px){
		.user_profile, .my-profile .content p{
			display: none !important;
		}

		}

	</style>
</head>
<body>
<?php include('common/header.php') ?>
<div class="my-profile">
	<div class="container">
		<div class="row">
		<?php include ('common/profile/menu.php') ?>
			<div class="col-md-9">
				<?php include ('common/profile/head.php') ?>
				<div class="users_form">
					<div class="profiles">
						<h4 class="information" >Pickup Points</h4>
					</div>
					<?php
					   if ($this->session->flashdata('error')) { ?>
						<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
						<?php } ?>
						<?php if ($this->session->flashdata('success')) { ?>
						<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
					<?php  } ?>
					<div class="row form_user">

						<div class="col-sm-4 col-md-4 col-lg-4 px-0 user_label">
							<fieldset class="step" id="step1">
			                        <!-- <label id="collection_points-error" class="error" for="collection_points"> Pickup Points </label> -->
				                      <div class="select_points  step-container" >
				                          <h1 class="step_1" data-step_id="step1">Select Pickup Points</h1>
				                      </div>
				            </fieldset>
						</div>

					</div>

			</div>
		</div>
	</div>
</div>

<?php include('common/footer.php') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/aos@2.3.0/dist/aos.js"></script>
<script src="<?=base_url('assets/')?>js/bootstrap.min.js"></script>	
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" ></script>
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
	$(document).ready(function(){
		<?php if($error == "YES"): ?>
			$("#BlockUIConfirm").show();
		<?php endif; ?>
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
  $('.step_1').on('click', function(){
    let step_number = $(this).data('step_id'); 
    let  showdata = ''; 

    $.ajax({
      url:'checkout-step-data',
      type:'post',
      data:{step_number:step_number},
      success:function(data){

        data.forEach( item => {

          showdata +='<li><a onclick="step2('+item.emirate_id+')" href="javascript:void(0);">'+item.emirate_name+'</a></li>';

        });
        
         $('.step-container').empty().append("<div class='select_points'> <h1>Select Pickup Points</h1> </div><ul class='select_location'>"+showdata);

      }
    });
  })

function step2(emirate_id){
  let step2 = 'step2';
  let  showdata = '';
  let backButton ='';

  $.ajax({
      url:'checkout-step-data',
      type:'post',
      data:{emirate_id:emirate_id,step_number:step2 },
      success:function(data){

        data.forEach( item => {
          showdata +='<li><a onclick="step3('+item.area_id+')" href="javascript:void(0);">'+item.area_name+'</a></li>';

          backButton ='<div class="backbutton" onclick="prevStep(1,'+item.emirate_id+')">Back</div>';
        });
        
         $('.step-container').empty().append("<div class='select_points'> <h1>Select Area</h1> </div><ul class='select_location'>"+showdata+"</ul>"+backButton);

      }
    });

}

function step3(area_id){
  let step = 'step3';
  let  showdata = '';
  let backButton ='';

  $.ajax({
      url:'checkout-step-data',
      type:'post',
      data:{area_id:area_id,step_number:step },
      success:function(data){

        data.forEach( item => {
          showdata +='<li><a onclick="step4('+item.collection_point_id+')" href="javascript:void(0);">'+item.collection_point_name+'</a></li>';
          
          backButton ='<div class="backbutton" onclick="prevStep(2,'+item.emirate_id+')">Back</div>';
        });
        
         $('.step-container').empty().append("<div class='select_points'> <h1>Select your nearest pickup points</h1> </div><ul class='select_location'>"+showdata+"</ul>"+backButton);

      }


    });


}


function step4(collection_point_id){
  let step = 'step4';
  let  showdata = '';

  $.ajax({
      url:'checkout-step-data',
      type:'post',
      data:{collection_point_id:collection_point_id,step_number:step },
      success:function(data){
        
        data.forEach( item => {


         let url = $.trim(item.emirate_id)+'_____'+$.trim(item.emirate_slug)+'_____'+$.trim(item.collection_point_id)+'_____'+$.trim(item.collection_point_slug)+'_____'+$.trim(item.area_id)+'_____'+$.trim(item.area_slug);
                  // item.emirate_id+'_____'+item.emirate_name+'_____'+item.collection_point_id+'_____'+item.collection_point_name+'_____'+item.area_id+'_____'+item.area_name;
          showdata +='<select name="collection_points" id="collection_points" style="width: 100%;"> <option value='+url+'>'+item.collection_point_name+'</option> </select>';
        });
        
         $('.step-container').empty().append(showdata);

      }


    });
}



function prevStep(step='',id=''){
	var step_number;
	if(step == 1){
 		var step_number = 'step1'; 
	}
	if(step == 2){
 		var step_number = 'step2'; 
	}
     let  showdata = ''; 

    $.ajax({
      url:'checkout-step-data',
      type:'post',
       data:{emirate_id:id,step_number:step_number },
      success:function(data){


      	if(step == 1){
	
	        data.forEach( item => {

	          showdata +='<li><a onclick="step2('+item.emirate_id+')" href="javascript:void(0);">'+item.emirate_name+'</a></li>';

	        });
	        
	         $('.step-container').empty().append("<div class='select_points'> <h1>Select Pickup Points</h1> </div><ul class='select_location'>"+showdata);

        }

        if(step == 2){
	
	       data.forEach( item => {
          showdata +='<li><a onclick="step3('+item.area_id+')" href="javascript:void(0);">'+item.area_name+'</a></li>';

          backButton ='<div class="backbutton" onclick="prevStep(1,'+item.emirate_id+')">Back</div>';
        });
        
         $('.step-container').empty().append("<div class='select_points'> <h1>Select Area</h1> </div><ul class='select_location'>"+showdata+"</ul>"+backButton);

        }
      }
    });
}

</script>



</body>
</html>
