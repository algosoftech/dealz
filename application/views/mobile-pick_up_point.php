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
            font-size: 14px;
            padding: 10px;
            margin: 9px;
            border: 1px solid #f4f4f4;
            border-radius: 8px;
            list-style-type: none;
            display: block;
            text-align: center;
            text-decoration: none;
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
            text-align: center;
        }

        @media only screen and (min-width:300px) and (max-width:600px){
        .user_profile, .my-profile .content p{
            display: none !important;
        }

        }

        ol, ul {
            padding-left: 0px !important;
        }

        .backbutton{
                text-align: center;
                width: 140px;
                border-radius: 8px;
                border: 0;
                outline: none;
                padding: 8px;
                font-size: 14px;
                font-weight: 500;
                margin: 0 auto;
                background: #c9261f;
                color: #fff;
                display: block;
        }

    </style>
<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Pick Up Points
                </h1>
            </section>
            <div class="inner_pagedata">
               <section class="pickupsec">
                    <div class="pickupbond">
                         
                         <fieldset class="step" id="step1">
                                    <!-- <label id="collection_points-error" class="error" for="collection_points"> Pickup Points </label> -->
                                      <div class="select_points  step-container" >
                                          <h1 class="step_1" data-step_id="step1">Select Pickup Points</h1>
                                      </div>
                            </fieldset>
                        
                    </div>

                    
               </section>
            </div> 

    <?php include('common/mobile/footer.php'); ?>
    <?php include('common/mobile/menu.php'); ?>

        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>


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