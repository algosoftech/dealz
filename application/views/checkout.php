
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style>

    @use postcss-preset-env {
    stage: 0;
  }

  :root {
    --color-background: #fae3ea;
    --color-primary: #fc8080;
    --font-family-base: Poppin, sans-serif;
    --font-size-h1: 1.25rem;
    --font-size-h2: 1rem;
  }
#collection_points {
    padding: 20px;
    height: 60px;
    color: black;
}

  * {
    box-sizing: inherit; 
  }

  html {
    box-sizing: border-box;
  }

  address {
    font-style: normal;
  }

  button {
    border: 0;
    color: inherit;
    cursor: pointer;
    font: inherit;
  }

  fieldset {
    border: 0;
    margin: 0;
    padding: 0;
  }

  h1 {
    font-size: var(--font-size-h1);
    line-height: 1.2;
    margin-block: 0 1.5em;
  }

  h2 {
    font-size: var(--font-size-h2);
    line-height: 1.2;
    margin-block: 0 0.5em;
    font-size: 19px;
  }

  legend {
    font-weight: 600;
    margin-block-end: 0.5em;
    padding: 0;
  }

  input {
    border: 0;
    color: inherit;
    font: inherit;
  }

  input[type="radio"] {
    accent-color: var(--color-primary);
  }

  table {
    border-collapse: collapse;
    inline-size: 100%;
  }

  tbody {
    color: #b4b4b4;
  }

  td {
    padding-block: 0.125em;
  }

  tfoot {
    border-top: 1px solid #b4b4b4;
    font-weight: 600;
  }

  .align {
    display: grid;
    place-items: center;
  }

  .button {
    align-items: center;
    background-color: #d10013;
    border-radius: 11px;
    color: #fff;
    display: flex;
    gap: 0.5em;
    justify-content: center;
    padding-block: 0.75em;
    padding-inline: 1em;
    transition: 0.3s;
  }

  .button:focus,
  .button:hover {
    background-color: #e96363;
  }

  .button--full {
    inline-size: 100%;
  }

  .card {
    border-radius: 1em;
/*    background-color: var(--color-primary);*/
    color: #fff;
/*    padding: 1em;*/
  }

  .form {
    display: grid;
    gap: 2em;
  }

  .form__radios {
    display: grid;
    gap: 1em;
  }

  .form__radio {
    align-items: center;
    background-color: #fefdfe;
    border-radius: 1em;
    box-shadow: 0 0 1em rgba(0, 0, 0, 0.0625);
    display: flex;
    padding: 1em;
  }

  .form__radio label {
    align-items: center;
    display: flex;
    flex: 1;
    gap: 1em;
  }

  .header {
    display: flex;
    justify-content: center;
    padding-block: 0.5em;
    padding-inline: 1em;
  }
.iphone{
   width: 30%;
    margin: auto; 
    padding: 30px;
    border-radius: 20px;
    background-color: #fff;

}

label#collection_points-error {
    color: red;
}

@media only screen and (max-widt: 600px){
  .iphone{
     width: 50%;
      margin: auto; 
      padding: 20px;
      border-radius: 20px;
      background-color: #fff;

  }

}
  .icon {
    block-size: 1em;
    display: inline-block;
    fill: currentColor;
    inline-size: 1em;
    vertical-align: middle;
  }


/* mobile view css for collection points start */
.select_location{
    padding: 0px;
    list-style: none;
} 

.select_location_user{
padding: 0px;
    list-style: none;
}
.select_location li a {
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    text-align: left;
    color: #181818;
    text-decoration: none;
    display: block;
}
.select_location_user li{ 
background-color: #eeeeee;
    color: #000;
    font-size: 6px;
    padding: 10px;
    margin: 9px;
    border: 1px solid #f4f4f4;
    text-align: left;
    border-radius: 8px;
  display:flex;
}
.select_points h1{
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
}
.select_points ul{
    padding: 0px;
    list-style: none;
} 
.select_location li {
    background-color: #eeeeee;
    color: #000;
    font-size: 6px;
    padding: 10px;
    margin: 9px;
    border: 1px solid #f4f4f4;
    text-align: left;
    border-radius: 8px;
    display: flex;
}
 #multi_step_form {
   padding-bottom: 75px;
}
 #multi_step_form .container #multistep_nav {
   display: flex;
   justify-content: space-between;
}
 #multi_step_form .container #multistep_nav .progress_holder {
   padding: 20px;
   width: 25%;
   text-align: center;
}
 #multi_step_form .container #multistep_nav .activated_step {
   background-color: red;
   color: white;
}
 #multi_step_form .container fieldset.step {
   position: relative;
     padding: 54px 44px;
   text-align: center;
   align-items: center;
   justify-content: center;
}
 #multi_step_form .container fieldset.step .nextStep {
    position: absolute;
    right: 17px;
    bottom: -47px;
    padding: 7px;
    width: 87%;
    border: 1px solid #0000001c;
    margin: auto;
    background-color: #c42828;
    color: #fff;
}
 #multi_step_form .container fieldset.step .prevStep {
    position: absolute;
    right: 17px;
    bottom: -47px;
    padding: 7px;
    width: 87%;
    border: 1px solid #0000001c;
    margin: auto;
    background-color: #c42828;
    color: #fff;
}
 #multi_step_form .container fieldset.step:not(:first-of-type) {
   display: none;
}
body{
background-color: var(--color-background);
  
  
}
}
 img{
 width: 43px;
    height: 43px;
 }

.emirate_name_div ,#step2 ,#step3{
  display:none;
 }
 @media only screen and (min-width: 360px) and (max-width: 600px){
    
    .iphone {
/*        display: none;*/
        background-color: #fbf6f7;
        background-image: linear-gradient(to bottom, #fbf6f7, #fff);
        border-radius: 2em;
        /* block-size: 631px; */
        box-shadow: 0 0 1em rgb(0 0 0 / 6%);
        inline-size: 86%;
        overflow: auto;
        padding: 2em;
        margin: auto;
    }
    body {
        background-color: var(--color-background);
        /* display: grid; */
        font-family: var(--font-family-base);
        line-height: 1.5;
        margin: 0;
        
        padding: 5px;
      
    }
}

.step0{
    background-color: #eeeeee;
    padding: 10px;
    margin: 9px;
    border: 1px solid #f4f4f4;
    text-align: left;
    border-radius: 8px;

    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    text-align: left;
    color: #181818;
    text-decoration: none;
    display: flex;
}

/*label.step_1::after {
    content: "▼";
    color: #a7a4a4;
    position: relative;
    right: -2%;
    font-weight: 900;
}*/


.step0::after {
    content: "▼";
    color: #a7a4a4;
    position: relative;
    right: 0%;
    top: 15px;
}

.backbutton {
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
    cursor: pointer;
    width:100px;
}


/* mobile view css for collection points end */
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<div class="iphone">
  <div class="promo-code" style="float:right;">
      <a href="<?=base_url()?>" class="color-default-btn float-right" style="text-decoration: none;">X</a>
    </div>
  <header class="header">
    <h1>Checkout</h1>
  </header>
  <?php
    $inclusiveOfVat =   ($finaltotal+$shipping);
    $totalVat   = 0;//($finaltotal * 5/100);
    $subTotal     = ($inclusiveOfVat-$totalVat);
  ?>


  <form class="form" method="POST">
    <fieldset>
        <?php if ($this->session->flashdata('error')) { ?>
          <label class="alert alert-danger" style="width:100%;color:red;"><?=$this->session->flashdata('error')?></label>
        <?php } ?> 
        <input type="hidden" name="product_is_donate" id="product_is_donate" value="<?php echo $product_is_in_donate; ?>">
      <?php if($product_is_in_donate == 'N'): ?>
        <!-- <legend>Shipping</legend> -->
       
        <input id="pickup" value="pickup" name="shipping_method" type="hidden" />
        <div id="pickupPointDiv" style="margin-top: 10px;">
          <h2>Pickup Point </h2>
            

          <div class="card">
            <?php if($collectionList): ?>
                <fieldset class="step" id="step1">
                  <div class="select_points  step-container">
                      <div class="step0">


                        <?php $collection_point = $collectionList[0]['collectionPoint'][0]['collection_point_name']; 
                              if($collection_point):
                                  $collection_point =   explode("https", $collection_point); 

                                  $defalut_collection_point = $collection_point[0];
                                  if($collection_point[1]):
                                      $google_link = 'https'.$collection_point[1];
                                  endif;
                              endif;
                        ?>

                          <label class="step_1" data-step_id="step1" ><?= trim($defalut_collection_point); ?></label>
                          <a href="<?=$google_link;?>" target="_blank" >  
                           <img src="<?=base_url('/assets/google_link.png');?>" width="50">
                          </a>
                          <input type="hidden" name="collection_points" value="<?=$collectionList[0]['emirate_id'].'_____'.$collectionList[0]['emirate_slug'].'_____'.$collectionList[0]['collectionPoint'][0]['collection_point_id'].'_____'.$collectionList[0]['collectionPoint'][0]['collection_point_slug'].'_____'.$collectionList[0]['collectionPoint'][0]['area_id'].'_____'.$collectionList[0]['collectionPoint'][0]['area_slug'];?>">
                      </div>
                  </div>
                </fieldset>


            <?php endif; ?>

        </div>
        
      <?php else: ?>
        <input type="hidden" name="collection_points" value = "donated"/>
      <?php endif; ?>
    </fieldset>
    <fieldset>
      <legend>Payment Method</legend>
      <div class="form__radios">
        <?php $count = 1; ?>
        <!-- Updaten on 01-03-2023 : Credit card through purchase we need to set minimal purchase value 6 AED-->
         <?php  if($enabledPayment['stripe'] == 'enable'): ?>
          <div class="form__radio">
            <label for="visa">
              <!-- <svg class="icon"> <use xlink:href="#icon-visa" /> </svg> -->
              <?=$count++?>. <?=$enabledPayment['title_stripe']?> </label>
            <input checked id="stripe" value="stripe" name="payment_method" type="radio" />
          </div>
         <?php endif; ?>
         
         <?php  if($enabledPayment['telr'] == 'enable'): ?>
          <div class="form__radio">
            <label for="paypal">
              <!-- <div style="width:16px;">
              
              <img src="<?=base_url('assets/telr.png')?>" style="width: 100%;margin-left:0px;">
              </div> -->
              <?=$count++?>. <?=$enabledPayment['title_telr']?> </label>
            <input id="telr" value="telr" name="payment_method" type="radio"/>
          </div>
        <?php endif; ?>

        
            <?php  if($enabledPayment['noon'] == 'enable'): ?>
             <div class="form__radio">
                <label for="noon">
                  <?=$count++?>. <?=$enabledPayment['title_noon']?> </label>
                <input id="noon" value="Noon" name="payment_method" type="radio"/>
              </div>
            <?php endif; ?>


            <?php  //if($enabledPayment['noon'] == 'enable'): 
             if($this->session->userdata('DZL_USERID') == '100000000001983' || $this->session->userdata('DZL_USERID') == '100000000013118' ): ?>
             <div class="form__radio">
                <label for="noon">
                  <?=$count++?>. Ngenius </label>
                <input id="Ngenius" value="Ngenius" name="payment_method" type="radio"/>
             </div>
            <?php endif; ?>
        

          <div class="form__radio">
            <label for="paypal"><div style="width:16px;">
              <img src="<?=base_url('assets/AP-GREEN.png')?>" style="width: 100%;margin-left:0px;" >
                <use xlink:href="#icon-paypal" />
              </div >Arabian Points (<?php echo $ToatlPoints;?>)</label>
            <input id="arabianpoint" value="arabianpoint" name="payment_method" type="radio" <?php if($post_sufficient_point_error) { ?>checked<?php } ?>  />
          </div>
          <label id="arab" style="color: red;"><?=$post_sufficient_point_error?></label>
        
      </div>
    </fieldset>
    <script>
      $(document).ready(function(){
        $('input[type=radio][name=payment_method]').change(function() {
          var arabinPointError   = '<?php if($post_sufficient_point_error): echo 'Y'; else: echo 'N'; endif; ?>';
          if (this.value == 'arabianpoint' && arabinPointError == 'Y') {
            $("#arab").attr("style", "display:block; color:red")
          } else if (this.value == 'arabianpoint') {
            $("#arab").attr("style", "display:block; color:red ");
          } else {
            $("#arab").attr("style", "display:none");
          }
        });
      });
    </script>
    <div>
      
      <input type="hidden" name="shipping_charge" id="shipping_charge" value="0">
      <table style="margin-top:10px;">
        <tbody>
          
          <tr style="border-top: 1px solid #b4b4b4;border-bottom: 1px solid #b4b4b4;">  
          <td style="padding: 17px 0px;">  Total</td>
            <input type="hidden" name="inclusice_of_vat" id="inclusice_of_vat" value="<?php echo number_format($inclusiveOfVat,2); ?>">
            <td align="right" style="padding: 17px 0px;"><h2 align="right" id="inclusice_of_vat_html">AED<?php echo number_format($inclusiveOfVat,2); ?></h2></td>
          </tr>
          
        </tbody>
      </table>
    </div>
    <div>
      <input type="hidden" name="placeOrder" id="placeOrder" value="YES">
      <button class="button button--full" type="submit"><svg class="icon">
          <use xlink:href="#icon-shopping-bag" />
        </svg>Place Order</button>
    </div>
  </form>
</div>


<svg xmlns="http://www.w3.org/2000/svg" style="display: none">

  <symbol id="icon-shopping-bag" viewBox="0 0 24 24">
    <path d="M20 7h-4v-3c0-2.209-1.791-4-4-4s-4 1.791-4 4v3h-4l-2 17h20l-2-17zm-11-3c0-1.654 1.346-3 3-3s3 1.346 3 3v3h-6v-3zm-4.751 18l1.529-13h2.222v1.5c0 .276.224.5.5.5s.5-.224.5-.5v-1.5h6v1.5c0 .276.224.5.5.5s.5-.224.5-.5v-1.5h2.222l1.529 13h-15.502z" />
  </symbol>

  <symbol id="icon-mastercard" viewBox="0 0 504 504">
    <path d="m504 252c0 83.2-67.2 151.2-151.2 151.2-83.2 0-151.2-68-151.2-151.2 0-83.2 67.2-151.2 150.4-151.2 84.8 0 152 68 152 151.2z" fill="#ffb600" />
    <path d="m352.8 100.8c83.2 0 151.2 68 151.2 151.2 0 83.2-67.2 151.2-151.2 151.2-83.2 0-151.2-68-151.2-151.2" fill="#f7981d" />
    <path d="m352.8 100.8c83.2 0 151.2 68 151.2 151.2 0 83.2-67.2 151.2-151.2 151.2" fill="#ff8500" />
    <path d="m149.6 100.8c-82.4.8-149.6 68-149.6 151.2s67.2 151.2 151.2 151.2c39.2 0 74.4-15.2 101.6-39.2 5.6-4.8 10.4-10.4 15.2-16h-31.2c-4-4.8-8-10.4-11.2-15.2h53.6c3.2-4.8 6.4-10.4 8.8-16h-71.2c-2.4-4.8-4.8-10.4-6.4-16h83.2c4.8-15.2 8-31.2 8-48 0-11.2-1.6-21.6-3.2-32h-92.8c.8-5.6 2.4-10.4 4-16h83.2c-1.6-5.6-4-11.2-6.4-16h-70.4c2.4-5.6 5.6-10.4 8.8-16h53.6c-3.2-5.6-7.2-11.2-12-16h-29.6c4.8-5.6 9.6-10.4 15.2-15.2-26.4-24.8-62.4-39.2-101.6-39.2 0-1.6 0-1.6-.8-1.6z" fill="#ff5050" />
    <path d="m0 252c0 83.2 67.2 151.2 151.2 151.2 39.2 0 74.4-15.2 101.6-39.2 5.6-4.8 10.4-10.4 15.2-16h-31.2c-4-4.8-8-10.4-11.2-15.2h53.6c3.2-4.8 6.4-10.4 8.8-16h-71.2c-2.4-4.8-4.8-10.4-6.4-16h83.2c4.8-15.2 8-31.2 8-48 0-11.2-1.6-21.6-3.2-32h-92.8c.8-5.6 2.4-10.4 4-16h83.2c-1.6-5.6-4-11.2-6.4-16h-70.4c2.4-5.6 5.6-10.4 8.8-16h53.6c-3.2-5.6-7.2-11.2-12-16h-29.6c4.8-5.6 9.6-10.4 15.2-15.2-26.4-24.8-62.4-39.2-101.6-39.2h-.8" fill="#e52836" />
    <path d="m151.2 403.2c39.2 0 74.4-15.2 101.6-39.2 5.6-4.8 10.4-10.4 15.2-16h-31.2c-4-4.8-8-10.4-11.2-15.2h53.6c3.2-4.8 6.4-10.4 8.8-16h-71.2c-2.4-4.8-4.8-10.4-6.4-16h83.2c4.8-15.2 8-31.2 8-48 0-11.2-1.6-21.6-3.2-32h-92.8c.8-5.6 2.4-10.4 4-16h83.2c-1.6-5.6-4-11.2-6.4-16h-70.4c2.4-5.6 5.6-10.4 8.8-16h53.6c-3.2-5.6-7.2-11.2-12-16h-29.6c4.8-5.6 9.6-10.4 15.2-15.2-26.4-24.8-62.4-39.2-101.6-39.2h-.8" fill="#cb2026" />
    <g fill="#fff">
      <path d="m204.8 290.4 2.4-13.6c-.8 0-2.4.8-4 .8-5.6 0-6.4-3.2-5.6-4.8l4.8-28h8.8l2.4-15.2h-8l1.6-9.6h-16s-9.6 52.8-9.6 59.2c0 9.6 5.6 13.6 12.8 13.6 4.8 0 8.8-1.6 10.4-2.4z" />
      <path d="m210.4 264.8c0 22.4 15.2 28 28 28 12 0 16.8-2.4 16.8-2.4l3.2-15.2s-8.8 4-16.8 4c-17.6 0-14.4-12.8-14.4-12.8h32.8s2.4-10.4 2.4-14.4c0-10.4-5.6-23.2-23.2-23.2-16.8-1.6-28.8 16-28.8 36zm28-23.2c8.8 0 7.2 10.4 7.2 11.2h-17.6c0-.8 1.6-11.2 10.4-11.2z" />
      <path d="m340 290.4 3.2-17.6s-8 4-13.6 4c-11.2 0-16-8.8-16-18.4 0-19.2 9.6-29.6 20.8-29.6 8 0 14.4 4.8 14.4 4.8l2.4-16.8s-9.6-4-18.4-4c-18.4 0-36.8 16-36.8 46.4 0 20 9.6 33.6 28.8 33.6 6.4 0 15.2-2.4 15.2-2.4z" />
      <path d="m116.8 227.2c-11.2 0-19.2 3.2-19.2 3.2l-2.4 13.6s7.2-3.2 17.6-3.2c5.6 0 10.4.8 10.4 5.6 0 3.2-.8 4-.8 4s-4.8 0-7.2 0c-13.6 0-28.8 5.6-28.8 24 0 14.4 9.6 17.6 15.2 17.6 11.2 0 16-7.2 16.8-7.2l-.8 6.4h14.4l6.4-44c0-19.2-16-20-21.6-20zm3.2 36c0 2.4-1.6 15.2-11.2 15.2-4.8 0-6.4-4-6.4-6.4 0-4 2.4-9.6 14.4-9.6 2.4.8 3.2.8 3.2.8z" />
      <path d="m153.6 292c4 0 24 .8 24-20.8 0-20-19.2-16-19.2-24 0-4 3.2-5.6 8.8-5.6 2.4 0 11.2.8 11.2.8l2.4-14.4s-5.6-1.6-15.2-1.6c-12 0-24 4.8-24 20.8 0 18.4 20 16.8 20 24 0 4.8-5.6 5.6-9.6 5.6-7.2 0-14.4-2.4-14.4-2.4l-2.4 14.4c.8 1.6 4.8 3.2 18.4 3.2z" />
      <path d="m472.8 214.4-3.2 21.6s-6.4-8-15.2-8c-14.4 0-27.2 17.6-27.2 38.4 0 12.8 6.4 26.4 20 26.4 9.6 0 15.2-6.4 15.2-6.4l-.8 5.6h16l12-76.8zm-7.2 42.4c0 8.8-4 20-12.8 20-5.6 0-8.8-4.8-8.8-12.8 0-12.8 5.6-20.8 12.8-20.8 5.6 0 8.8 4 8.8 13.6z" />
      <path d="m29.6 291.2 9.6-57.6 1.6 57.6h11.2l20.8-57.6-8.8 57.6h16.8l12.8-76.8h-26.4l-16 47.2-.8-47.2h-23.2l-12.8 76.8z" />
      <path d="m277.6 291.2c4.8-26.4 5.6-48 16.8-44 1.6-10.4 4-14.4 5.6-18.4 0 0-.8 0-3.2 0-7.2 0-12.8 9.6-12.8 9.6l1.6-8.8h-15.2l-10.4 62.4h17.6z" />
      <path d="m376.8 227.2c-11.2 0-19.2 3.2-19.2 3.2l-2.4 13.6s7.2-3.2 17.6-3.2c5.6 0 10.4.8 10.4 5.6 0 3.2-.8 4-.8 4s-4.8 0-7.2 0c-13.6 0-28.8 5.6-28.8 24 0 14.4 9.6 17.6 15.2 17.6 11.2 0 16-7.2 16.8-7.2l-.8 6.4h14.4l6.4-44c.8-19.2-16-20-21.6-20zm4 36c0 2.4-1.6 15.2-11.2 15.2-4.8 0-6.4-4-6.4-6.4 0-4 2.4-9.6 14.4-9.6 2.4.8 2.4.8 3.2.8z" />
      <path d="m412 291.2c4.8-26.4 5.6-48 16.8-44 1.6-10.4 4-14.4 5.6-18.4 0 0-.8 0-3.2 0-7.2 0-12.8 9.6-12.8 9.6l1.6-8.8h-15.2l-10.4 62.4h17.6z" />
    </g>
    <path d="m180 279.2c0 9.6 5.6 13.6 12.8 13.6 5.6 0 10.4-1.6 12-2.4l2.4-13.6c-.8 0-2.4.8-4 .8-5.6 0-6.4-3.2-5.6-4.8l4.8-28h8.8l2.4-15.2h-8l1.6-9.6" fill="#dce5e5" />
    <path d="m218.4 264.8c0 22.4 7.2 28 20 28 12 0 16.8-2.4 16.8-2.4l3.2-15.2s-8.8 4-16.8 4c-17.6 0-14.4-12.8-14.4-12.8h32.8s2.4-10.4 2.4-14.4c0-10.4-5.6-23.2-23.2-23.2-16.8-1.6-20.8 16-20.8 36zm20-23.2c8.8 0 10.4 10.4 10.4 11.2h-20.8c0-.8 1.6-11.2 10.4-11.2z" fill="#dce5e5" />
    <path d="m340 290.4 3.2-17.6s-8 4-13.6 4c-11.2 0-16-8.8-16-18.4 0-19.2 9.6-29.6 20.8-29.6 8 0 14.4 4.8 14.4 4.8l2.4-16.8s-9.6-4-18.4-4c-18.4 0-28.8 16-28.8 46.4 0 20 1.6 33.6 20.8 33.6 6.4 0 15.2-2.4 15.2-2.4z" fill="#dce5e5" />
    <path d="m95.2 244.8s7.2-3.2 17.6-3.2c5.6 0 10.4.8 10.4 5.6 0 3.2-.8 4-.8 4s-4.8 0-7.2 0c-13.6 0-28.8 5.6-28.8 24 0 14.4 9.6 17.6 15.2 17.6 11.2 0 16-7.2 16.8-7.2l-.8 6.4h14.4l6.4-44c0-18.4-16-19.2-22.4-19.2m12 34.4c0 2.4-9.6 15.2-19.2 15.2-4.8 0-6.4-4-6.4-6.4 0-4 2.4-9.6 14.4-9.6 2.4.8 11.2.8 11.2.8z" fill="#dce5e5" />
    <path d="m136 290.4s4.8 1.6 18.4 1.6c4 0 24 .8 24-20.8 0-20-19.2-16-19.2-24 0-4 3.2-5.6 8.8-5.6 2.4 0 11.2.8 11.2.8l2.4-14.4s-5.6-1.6-15.2-1.6c-12 0-16 4.8-16 20.8 0 18.4 12 16.8 12 24 0 4.8-5.6 5.6-9.6 5.6" fill="#dce5e5" />
    <path d="m469.6 236s-6.4-8-15.2-8c-14.4 0-19.2 17.6-19.2 38.4 0 12.8-1.6 26.4 12 26.4 9.6 0 15.2-6.4 15.2-6.4l-.8 5.6h16l12-76.8m-20.8 41.6c0 8.8-7.2 20-16 20-5.6 0-8.8-4.8-8.8-12.8 0-12.8 5.6-20.8 12.8-20.8 5.6 0 12 4 12 13.6z" fill="#dce5e5" />
    <path d="m29.6 291.2 9.6-57.6 1.6 57.6h11.2l20.8-57.6-8.8 57.6h16.8l12.8-76.8h-20l-22.4 47.2-.8-47.2h-8.8l-27.2 76.8z" fill="#dce5e5" />
    <path d="m260.8 291.2h16.8c4.8-26.4 5.6-48 16.8-44 1.6-10.4 4-14.4 5.6-18.4 0 0-.8 0-3.2 0-7.2 0-12.8 9.6-12.8 9.6l1.6-8.8" fill="#dce5e5" />
    <path d="m355.2 244.8s7.2-3.2 17.6-3.2c5.6 0 10.4.8 10.4 5.6 0 3.2-.8 4-.8 4s-4.8 0-7.2 0c-13.6 0-28.8 5.6-28.8 24 0 14.4 9.6 17.6 15.2 17.6 11.2 0 16-7.2 16.8-7.2l-.8 6.4h14.4l6.4-44c0-18.4-16-19.2-22.4-19.2m12 34.4c0 2.4-9.6 15.2-19.2 15.2-4.8 0-6.4-4-6.4-6.4 0-4 2.4-9.6 14.4-9.6 3.2.8 11.2.8 11.2.8z" fill="#dce5e5" />
    <path d="m395.2 291.2h16.8c4.8-26.4 5.6-48 16.8-44 1.6-10.4 4-14.4 5.6-18.4 0 0-.8 0-3.2 0-7.2 0-12.8 9.6-12.8 9.6l1.6-8.8" fill="#dce5e5" />
  </symbol>

  <symbol id="icon-paypal" viewBox="0 0 491.2 491.2">
    <path d="m392.049 36.8c-22.4-25.6-64-36.8-116-36.8h-152.8c-10.4 0-20 8-21.6 18.4l-64 403.2c-1.6 8 4.8 15.2 12.8 15.2h94.4l24-150.4-.8 4.8c1.6-10.4 10.4-18.4 21.6-18.4h44.8c88 0 156.8-36 176.8-139.2.8-3.2.8-6.4 1.6-8.8-2.4-1.6-2.4-1.6 0 0 5.6-38.4 0-64-20.8-88" fill="#263b80" />
    <path d="m412.849 124.8c-.8 3.2-.8 5.6-1.6 8.8-20 103.2-88.8 139.2-176.8 139.2h-44.8c-10.4 0-20 8-21.6 18.4l-29.6 186.4c-.8 7.2 4 13.6 11.2 13.6h79.2c9.6 0 17.6-7.2 19.2-16l.8-4 15.2-94.4.8-5.6c1.6-9.6 9.6-16 19.2-16h12c76.8 0 136.8-31.2 154.4-121.6 7.2-37.6 3.2-69.6-16-91.2-6.4-7.2-13.6-12.8-21.6-17.6" fill="#139ad6" />
    <path d="m391.249 116.8c-3.2-.8-6.4-1.6-9.6-2.4s-6.4-1.6-10.4-1.6c-12-2.4-25.6-3.2-39.2-3.2h-119.2c-3.2 0-5.6.8-8 1.6-5.6 2.4-9.6 8-10.4 14.4l-25.6 160.8-.8 4.8c1.6-10.4 10.4-18.4 21.6-18.4h44.8c88 0 156.8-36 176.8-139.2.8-3.2.8-6.4 1.6-8.8-4.8-2.4-10.4-4.8-16.8-7.2-1.6 0-3.2-.8-4.8-.8" fill="#232c65" />
    <path d="m275.249 0h-152c-10.4 0-20 8-21.6 18.4l-36.8 230.4 246.4-246.4c-11.2-1.6-23.2-2.4-36-2.4z" fill="#2a4dad" />
    <path d="m441.649 153.6c-2.4-4-4-8-7.2-12-5.6-6.4-13.6-12-21.6-16.8-.8 3.2-.8 5.6-1.6 8.8-20 103.2-88.8 139.2-176.8 139.2h-44.8c-10.4 0-20 8-21.6 18.4l-25.6 161.6z" fill="#0d7dbc" />
    <path d="m50.449 436.8h94.4l23.2-145.6c0-2.4.8-4 1.6-5.6l-131.2 131.2-.8 4.8c-.8 8 4.8 15.2 12.8 15.2z" fill="#232c65" />
    <path d="m246.449 0h-123.2c-3.2 0-5.6.8-8 1.6l-12 12c-.8 1.6-1.6 3.2-1.6 4.8l-24 150.4z" fill="#436bc4" />
    <path d="m450.449 232.8c2.4-12 3.2-23.2 3.2-34.4l-156 156c76-.8 135.2-32 152.8-121.6z" fill="#0cb2ed" />
    <path d="m248.849 471.2 12.8-80-100 100h68c9.6 0 17.6-7.2 19.2-16z" fill="#0cb2ed" />
    <g fill="#33e2ff" opacity=".6">
      <path d="m408.049 146.4 45.6 45.6c0-5.6-1.6-11.2-2.4-16.8l-40-40c-1.6 4-2.4 7.2-3.2 11.2z" />
      <path d="m396.849 180c-1.6 3.2-3.2 6.4-4.8 9.6l55.2 55.2c.8-4 1.6-8 2.4-12z" />
      <path d="m431.249 287.2c1.6-3.2 3.2-6.4 4.8-9.6l-60.8-60.8c-2.4 2.4-4 5.6-6.4 8z" />
      <path d="m335.249 250.4 69.6 69.6 7.2-7.2-68-68c-3.2 1.6-5.6 3.2-8.8 5.6z" />
      <path d="m292.849 266.4 76 76c3.2-1.6 6.4-3.2 9.6-4.8l-74.4-74.4c-4 .8-7.2 2.4-11.2 3.2z" />
      <path d="m320.849 353.6c4-.8 8.8-.8 12.8-1.6l-80-80c-4.8 0-8.8.8-13.6.8z" />
      <path d="m196.049 272.8h-6.4c-2.4 0-4.8.8-6.4.8l86.4 87.2c2.4-2.4 5.6-4.8 8.8-5.6z" />
      <path d="m164.049 314.4 94.4 94.4 2.4-12.8-94.4-94.4z" />
      <path d="m156.049 364.8 94.4 94.4 2.4-12-94.4-94.4z" />
      <path d="m150.449 403.2-1.6 12.8 75.2 75.2h5.6c2.4 0 4.8-.8 7.2-1.6z" />
      <path d="m140.049 466.4 24.8 24.8h14.4l-36.8-36.8z" />
    </g>
  </symbol>

  <symbol id="icon-visa" viewBox="0 0 504 504">
    <path d="m184.8 324.4 25.6-144h40l-24.8 144z" fill="#3c58bf" />
    <path d="m184.8 324.4 32.8-144h32.8l-24.8 144z" fill="#293688" />
    <path d="m370.4 182c-8-3.2-20.8-6.4-36.8-6.4-40 0-68.8 20-68.8 48.8 0 21.6 20 32.8 36 40s20.8 12 20.8 18.4c0 9.6-12.8 14.4-24 14.4-16 0-24.8-2.4-38.4-8l-5.6-2.4-5.6 32.8c9.6 4 27.2 8 45.6 8 42.4 0 70.4-20 70.4-50.4 0-16.8-10.4-29.6-34.4-40-14.4-7.2-23.2-11.2-23.2-18.4 0-6.4 7.2-12.8 23.2-12.8 13.6 0 23.2 2.4 30.4 5.6l4 1.6z" fill="#3c58bf" />
    <path d="m370.4 182c-8-3.2-20.8-6.4-36.8-6.4-40 0-61.6 20-61.6 48.8 0 21.6 12.8 32.8 28.8 40s20.8 12 20.8 18.4c0 9.6-12.8 14.4-24 14.4-16 0-24.8-2.4-38.4-8l-5.6-2.4-5.6 32.8c9.6 4 27.2 8 45.6 8 42.4 0 70.4-20 70.4-50.4 0-16.8-10.4-29.6-34.4-40-14.4-7.2-23.2-11.2-23.2-18.4 0-6.4 7.2-12.8 23.2-12.8 13.6 0 23.2 2.4 30.4 5.6l4 1.6z" fill="#293688" />
    <path d="m439.2 180.4c-9.6 0-16.8.8-20.8 10.4l-60 133.6h43.2l8-24h51.2l4.8 24h38.4l-33.6-144zm-18.4 96c2.4-7.2 16-42.4 16-42.4s3.2-8.8 5.6-14.4l2.4 13.6s8 36 9.6 44h-33.6z" fill="#3c58bf" />
    <path d="m448.8 180.4c-9.6 0-16.8.8-20.8 10.4l-69.6 133.6h43.2l8-24h51.2l4.8 24h38.4l-33.6-144zm-28 96c3.2-8 16-42.4 16-42.4s3.2-8.8 5.6-14.4l2.4 13.6s8 36 9.6 44h-33.6z" fill="#293688" />
    <path d="m111.2 281.2-4-20.8c-7.2-24-30.4-50.4-56-63.2l36 128h43.2l64.8-144h-43.2z" fill="#3c58bf" />
    <path d="m111.2 281.2-4-20.8c-7.2-24-30.4-50.4-56-63.2l36 128h43.2l64.8-144h-35.2z" fill="#293688" />
    <path d="m0 180.4 7.2 1.6c51.2 12 86.4 42.4 100 78.4l-14.4-68c-2.4-9.6-9.6-12-18.4-12z" fill="#ffbc00" />
    <path d="m0 180.4c51.2 12 93.6 43.2 107.2 79.2l-13.6-56.8c-2.4-9.6-10.4-15.2-19.2-15.2z" fill="#f7981d" />
    <path d="m0 180.4c51.2 12 93.6 43.2 107.2 79.2l-9.6-31.2c-2.4-9.6-5.6-19.2-16.8-23.2z" fill="#ed7c00" />
    <g fill="#051244">
      <path d="m151.2 276.4-27.2-27.2-12.8 30.4-3.2-20c-7.2-24-30.4-50.4-56-63.2l36 128h43.2z" />
      <path d="m225.6 324.4-34.4-35.2-6.4 35.2z" />
      <path d="m317.6 274.8c3.2 3.2 4.8 5.6 4 8.8 0 9.6-12.8 14.4-24 14.4-16 0-24.8-2.4-38.4-8l-5.6-2.4-5.6 32.8c9.6 4 27.2 8 45.6 8 25.6 0 46.4-7.2 58.4-20z" />
      <path d="m364 324.4h37.6l8-24h51.2l4.8 24h38.4l-13.6-58.4-48-46.4 2.4 12.8s8 36 9.6 44h-33.6c3.2-8 16-42.4 16-42.4s3.2-8.8 5.6-14.4" />
    </g>
  </symbol>
</svg>

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
        
         $('.step-container').empty().append("<div class='select_points'> <h1>Select Pickup Points</h1> </div><ul class='select_location'>"+showdata+"</ul>");

      }
    });
  })

function step2(emirate_id){
  let step2 = 'step2';
  let  showdata = '';

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

  $.ajax({
      url:'checkout-step-data',
      type:'post',
      data:{area_id:area_id,step_number:step },
      success:function(data){

        data.forEach( item => {
          if(item.google_link){
            showdata +='<li><a onclick="step4('+item.collection_point_id+')" href="javascript:void(0);">'+item.collection_point_name+'</a> <a href="'+item.google_link+'" target="_blank"> <img src="<?=base_url('/assets/google_link.png');?>" width="50"> </a></li>';
          }else{
            showdata +='<li><a onclick="step4('+item.collection_point_id+')" href="javascript:void(0);">'+item.collection_point_name+'</a></li>';
          }
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

$( ".form").submit(function( event ) {
 
  var totalAmount = '<?=$inclusiveOfVat?>';
  var ToatlPoints = '<?=$ToatlPoints?>';

  var payment_type = $("input[type=radio]:checked").val();

  // if(payment_type == 'stripe'){
  //   if(totalAmount >=6){
  //     return true;
  //   }else{
  //     alert('minimum purchase value using card AED 6');
  //     return false;
  //   }
  // }
  // else if(payment_type == 'arabianpoint' && ToatlPoints < totalAmount){
  //     alert('insufficient balance');
  //     return false;
  // }

});

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
