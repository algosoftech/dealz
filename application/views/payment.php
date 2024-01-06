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


  * {
    box-sizing: inherit;
  } 

  html {
    box-sizing: border-box;
  }

  body {
    background-color: var(--color-background);
    display: grid;
    font-family: var(--font-family-base);
    line-height: 1.5;
    margin: 0;
    min-block-size: 100vh;
    padding: 5vmin;
    place-items: center;
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
    background-color: var(--color-primary);
    border-radius: 999em;
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
    background-color: var(--color-primary);
    color: #fff;
    padding: 1em;
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

  .icon {
    block-size: 1em;
    display: inline-block;
    fill: currentColor;
    inline-size: 1em;
    vertical-align: middle;
  }

  .iphone {
    background-color: #fbf6f7;
    background-image: linear-gradient(to bottom, #fbf6f7, #fff);
    border-radius: 2em;
    block-size: 812px;
    box-shadow: 0 0 1em rgba(0, 0, 0, 0.0625);
    inline-size: 375px;
    overflow: auto;
    padding: 2em;
  }
  .required {color:red;}
  input[type="text"], input[type="password"] {
    width: 100%;
    border: 2px solid #f1f1f1;
    border-radius: 4px;
    margin: 8px 0;
    outline: none;
    padding: 5px 28px 5px;
      padding-left: 28px;
    box-sizing: border-box;
    transition: 0.3s;
  }
</style>
<div class="iphone">
  <div class="promo-code" style="float:right;">
      <a href="<?=base_url()?>" class="color-default-btn float-right" style="text-decoration: none;">X</a>
    </div>
  <header class="header">
    <h1>Payment</h1>
  </header>

  <form class="form" method="POST">
    
    <div>
      <h2>Shopping Bill</h2>
      <table>
        <tbody>
          <tr>
            <td>Shipping fee</td>
            <td align="right">AED<?php echo number_format($shipping,2); ?></td>
            <input type="hidden" name="shipping_charge" id="shipping_charge" value="<?php echo number_format($shipping,2); ?>">
          </tr>
        <?php
          $inclusiveOfVat =    ($finaltotal+$shipping);
          $totalVat   = ($finaltotal * 5/100);
          $subTotal     = ($inclusiveOfVat-$totalVat);
        ?>
		</table>
        </tbody>
		<table style="margin-top:10px;">
        <tbody>
		<td><h2 style="color: black;">Total</h2></td>
          <tr style="border-top: 1px solid #b4b4b4;">  
          <td style="padding: 17px 0px;">  Inclusive of VAT</td>
            <input type="hidden" name="inclusice_of_vat" id="inclusice_of_vat" value="<?php echo number_format($inclusiveOfVat,2); ?>">
            <td align="right" style="padding: 17px 0px;"><h2 align="right">AED<?php echo number_format($inclusiveOfVat,2); ?></h2></td>
          </tr>
          <tr style="border-top: 1px solid #b4b4b4;">
            <td style="padding: 17px 0px;">Subtotal</td>
            <input type="hidden" name="subtotal" id="subtotal" value="<?php echo number_format($subTotal,2); ?>">
            <td align="right" style="padding: 17px 0px;">AED<?php echo number_format($subTotal,2); ?></td> 
          </tr>
          <tr style="border-top: 1px solid #b4b4b4;">
            <td style="padding: 17px 0px;">VAT</td>
            <input type="hidden" name="vat_amount" id="vat_amount" value="<?php echo number_format($totalVat,2); ?>">
            <td align="right"style="padding: 17px 0px;">AED<?php echo number_format($totalVat,2); ?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="payment-card-block">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12  col-12">
                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="tab-pane active" id="credit-card">
                    <img src="<?=base_url('assets')?>/img/payment_method.png" alt="Payment Method" style="width:100%;" alt="payment">
                    <div class="form-group">
                      <input type="text" name="cardholder_name" id="cardholder_name" class="cardholder_name" placeholder="Cardholder Name" value="<?php if(set_value('cardholder_name')):echo set_value('cardholder_name');endif;?>">
                      <?php if(form_error('cardholder_name')):?>
                        <span class="error" for="cardholder_name" generated="true"><?php echo form_error('cardholder_name');?></span>
                      <?php endif;?>
                    </div>
                    <div class="form-group">
                      <input type="text" name="card_number" id="card_number" class="card_number cardnumberinput cardnumberformat" maxlength="19"  placeholder="Card Number" value="<?php if(set_value('card_number')):echo set_value('card_number');endif;?>">
                      <?php if((form_error('card_number')) || ($card_number_error)):?>
                        <span class="error" for="card_number" generated="true"><?php echo form_error('card_number')?form_error('card_number'):$card_number_error;?></span>
                      <?php endif;?>
                    </div>
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-6  col-12">
                        <div class="form-group">
                          <input type="text" name="expiry_date" id="expiry_date" class="expiry_date cardnumberinput cardexpdateformat"  maxlength="5" placeholder="Expiry Date" value="<?php if(set_value('expiry_date')):echo set_value('expiry_date');endif;?>">
                          <?php if((form_error('expiry_date')) || ($expiry_date_error)):?>
                            <span class="error" for="expiry_date" generated="true"><?php echo form_error('expiry_date')?form_error('expiry_date'):$expiry_date_error;?></span>
                          <?php endif;?>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6  col-12">
                        <div class="form-group">
                          <input type="password" name="cvv_number" id="cvv_number" class="cvv_number numberinput" maxlength="3" placeholder="CVV Number" value="<?php if(set_value('cvv_number')):echo set_value('cvv_number');endif;?>">
                          <?php if((form_error('cvv_number')) || ($cvv_number_error)):?>
                            <span class="error" for="cvv_number" generated="true"><?php echo form_error('cvv_number')?form_error('cvv_number'):$cvv_number_error;?></span>
                          <?php endif;?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    <div>
      <input type="hidden" name="makePayment" id="makePayment" value="YES">
      <button class="button button--full" type="submit"><svg class="icon">
          <use xlink:href="#icon-shopping-bag" />
        </svg>Make Payment</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).on("keydown", ".numberinput", function (e) {
    var curObj = $(this);
    var key = e.which || e.keyCode;
    if (
      (!e.shiftKey &&
        !e.altKey &&
        !e.ctrlKey &&
        // numbers
        key >= 48 &&
        key <= 57) ||
      // Numeric keypad
      (key >= 96 && key <= 105) ||
      // Backspace ans Tab
      key == 8 ||
      key == 9 ||
      // Home and End
      key == 35 ||
      key == 36 ||
      // left and right arrows
      key == 37 ||
      key == 39 ||
      // Del and Ins
      key == 46 ||
      key == 45
    ) {
      return true;
    } else {
      return false;
    }
  });
  $(document).on("keyup", ".numberinput", function (e) {
    var curObj = $(this);
    var curValue = curObj.val();
    var min = curObj.attr("min");
    var max = curObj.attr("max");
    if (curValue && min && max) {
      if (parseInt(curValue) < parseInt(min) || isNaN(parseInt(curValue)))
        curObj.val(min);
      else if (parseInt(curValue) > parseInt(max)) curObj.val(max);
      else curObj.val(curValue);
    }
  });

  $(document).on("keydown", ".cardnumberinput", function (e) {
    var curObj = $(this);
    var key = e.which || e.keyCode;
    if (
      (!e.shiftKey &&
        !e.altKey &&
        !e.ctrlKey &&
        // numbers
        key >= 48 &&
        key <= 57) ||
      // Numeric keypad
      (key >= 96 && key <= 105) ||
      // Backspace ans Tab
      key == 8 ||
      key == 9 ||
      // Home and End
      key == 35 ||
      key == 36 ||
      // dash(-) and space  and /
      key == 173 ||
      key == 32 ||
      key == 191 ||
      // left and right arrows
      key == 37 ||
      key == 39 ||
      // Del and Ins
      key == 46 ||
      key == 45
    ) {
      return true;
    } else {
      return false;
    }
  });

  $(document).on("keyup", ".cardnumberformat", function (e) {
    var cardStartDigit = [3, 4, 5, 6];
    var curObj = $(this);
    var curValue = curObj.val();
    var firstDigit = parseInt(curValue.substr(0, 1));
    if (cardStartDigit.indexOf(firstDigit) >= 0) {
      if (firstDigit == 3) {
        curObj.attr("maxlength", "17");
        curValue = curValue.replace(/(\d{4})(\d{6})(\d{5})/, "$1 $2 $3");
        if (curValue.length > 17) {
          curValue = curValue.substr(0, 17);
        }
      }
      if (firstDigit == 4) {
        curObj.attr("maxlength", "19");
        curValue = curValue.replace(
          /(\d{4})(\d{4})(\d{4})(\d{4})/,
          "$1 $2 $3 $4"
        );
        if (curValue.length > 19) {
          curValue = curValue.substr(0, 19);
        }
      }
      if (firstDigit == 5) {
        curObj.attr("maxlength", "19");
        curValue = curValue.replace(
          /(\d{4})(\d{4})(\d{4})(\d{4})/,
          "$1 $2 $3 $4"
        );
        if (curValue.length > 19) {
          curValue = curValue.substr(0, 19);
        }
      }
      if (firstDigit == 6) {
        curObj.attr("maxlength", "19");
        curValue = curValue.replace(
          /(\d{4})(\d{4})(\d{4})(\d{4})/,
          "$1 $2 $3 $4"
        );
        if (curValue.length > 19) {
          curValue = curValue.substr(0, 19);
        }
      }
      curObj.val(curValue);
    } else {
      curObj.val("");
    }
  });

  $(document).on("keyup", ".cardexpdateformat", function (e) {
    var curObj = $(this);
    var curValue = curObj.val();
    if (curValue.length == 4) {
      curValue = curValue.replace(/(\d{2})(\d{2})/, "$1/$2");
      if (parseInt(curValue.substr(0, 2)) > 12) {
        curValue = "0" + curValue.substr(1, 1) + curValue.substr(2, 3);
      }
    }
    curObj.val(curValue);
  });
</script>