<!Doctype html>
<html lang="eng">

<head>
    <!-- Basic page needs -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <?php include('common/head.php') ?>
   
<head>
<style>
    h1.ticket-heading ,.my-order-section{
        text-align: center;
        font-family: sans-serif;
        font-weight: 400;
        font-size: 25px;
        border-top: 2px dashed #808080bd;
        border-bottom: 2px dashed #808080bd;
        padding: 5px 0px;
    }

    .order-details-container {
        width:100%;
    }

    .order-deatail-section {
        width: 100%;
        display: inline-block;

    }

    table,tr {
        width:100%;
        line-height :1.6;
    }

    .order-title ,.order-details {
        width: 50%;
        font-family: sans-serif;
    }

    .order-title{
        text-align: left;
    }

    .order-details{
        text-align: right;
    }

    .my-order-section{
        margin-top: 21px;
    }

    .font-bold{
        font-weight:600;
    }


    .ticket-heading span {
        font-weight: 600;
    }


    .heading-section h1 {
        font-size: 24px;
        text-align: center;
        font-weight: 700;
        font-family: sans-serif;
    }
    .footer-container{
        text-align: center;
        font-family: sans-serif;
    }

    a:link, a:active {
        color: #000 !important;
        text-decoration: none;
    }
    a{
        color:#000 !important;
        font-weight: 600;

    }
    @media screen and (min-device-width: 360px) and (max-device-width: 600px) {
        body {
            background-color: #fff !important;
            margin: 0px;
        }
    }


    .company-heading-section {
      font-family: sans-serif;  
      text-align: center;
      line-height: 0.3;
    }

    .logo{
        min-width: 160px;
        max-width: 220px;
    }
    
    .promotional-section ,.border-top-bottom{
         text-align: center;
        font-family: sans-serif;
        font-weight: 400;
        font-size: 16px;
        border-top: 2px dashed #808080bd;
        border-bottom: 2px dashed #808080bd;
        padding: 5px 0px;
    }
    .border-bottom{
        text-align: center;
        font-family: sans-serif;
        font-weight: 400;
        font-size: 16px;
        border-bottom: 2px dashed #808080bd;
        padding: 5px 0px; 
    }
    table {
        margin: 20px auto;
    }

    .coupon-heading {
        vertical-align: baseline;
    }

    .heading-section, .country-code-section {
        display: flex;
        width: 100%;
        justify-content: center;
        cursor: pointer;
    }

    .thankyou-container {
        margin-top: 25px;
    }

    .orderInvoice-container {
        margin: 30px auto;
    }

    .color-default-btn{
        background: #e22c2d;
        padding: 5px 20px;
        border-radius: 7px;
        display: inline-block;
        font-size: 15px;
        border: 1px solid #e22c2d;
        width: 95%;
        text-align: center;
    }

	.order-success-contailler{
	    margin: auto;
	    background-color: #fff;
	    padding: 19px 15px 19px;
	    box-shadow: 1px 1px 5px 0 rgb(209 209 209);
	    border-radius: 12px;
	}

	@media only screen and (min-width:768px){
	   .order-success-contailler{ width: 670px; }
	}
</style>

<body>
    <?php include('common/header.php') ?>
    <!-- details-->
    <div class="details">
        <div class="container">
            <div class="row">
                <div class="col-sm=12 col-md-12 col-lg-12">
                    <div class="orderInvoice-container">
                        <div class='order-success-contailler'>
                            <div class='row' style='margin: 0px 35px;'>
                                <div style='text-align: center;margin:auto;'>
                                    <img class='logo' src="<?=base_url('assets/img/dealzlogo.png')?>" alt='logo'>
                                </div>
                            </div>
                            <div class='company-heading-section'>
                                <h3 class='ticket-heading'> DEALZARABIA TRADING L.L.C </h3>
                                <div>Tel: +971 4355 8533</div>

                            </div>
                            <table class='my-order-section'>
                                <tr>
                                    <td class='order-title'>My Order # </td>
                                    <td class='order-details'><?=$orderData['order_id'];?></td>
                                </tr> 
                            </table>
                            <table>
                                <tr>
                                    <td class='order-title'> Purchased On  </td>
                                    <td class='order-details'><?=date('d M, Y H:i A ', strtotime($orderData['created_at']));?></td>
                                </tr>
                            </table>
                            <table class='border-top-bottom'>
                                <tr>
                                    <td class='order-title'> Description </td>
                                    <td class='order-details'>Amount</td>
                                </tr>
                            </table>
                            
                              <?php
                                $TotalQTY   = 0;
                                $TotalPrice = 0;
                                foreach($orderDetails as $Pkey => $OD):  
                                    $SNo = ($Pkey+1) .')';
                                $CPwcon['where'] = array('product_id' => $OD["product_id"]);                                  
                                $CPData          =   $this->geneal_model->getData2('single', 'da_prize', $CPwcon);    
                                $CPwcon['where'] = array('products_id' => (int)$OD["product_id"]);                                  
                                $PData           =   $this->geneal_model->getData2('single', 'da_products', $CPwcon);
                              ?>  

                                <table class='border-bottom'>
                                 <tr>
                                    <td class='order-title'><?=$SNo. $OD['product_name'] .' x '. $OD['quantity'];?></td>  
                                    <td class='order-details'><?=$OD['subtotal']. " AED";?></td> 
                                 </tr>
                                 <tr>
                                    <td class='order-title coupon-heading'>Coupon Code:</td>    
                                    <td class='order-details'>
                                        <?php foreach($couponDetails as $Couponkey => $coupon ):  ?>
                                            <?php if($OD['product_id'] == $coupon['product_id'] ): ?>
                                                <?= $coupon['coupon_code']."<br>";?>
                                            <?php endif; ?>
                                        <?php endforeach;  ?>
                                    </td>
                                 </tr>

                                 <tr>
                                    <td class='order-title coupon-heading'>Promotional Campaign Value</td>    
                                    <td class='order-details'><?=$CPData['title'];?></td>
                                 </tr>
                                 <tr>  
                                    <td class='order-title coupon-heading'>Promotional Campaign Validity</td>    
                                    <td class='order-details'><?=$PData['draw_date'];?></td>   
                                 </tr>
                                    <?php 
                                    $TotalQTY   += $OD['quantity'];
                                    $TotalPrice += $OD['subtotal'];
                                    endforeach;  
                                    ?>
                                </table>

                                <table class='border-bottom'> 
                                    <tr>
                                        <td class='order-title'> Total Qty.</td>
                                        <td class='order-details'><?=$TotalQTY;?></td>
                                    </tr>
                                    <tr>
                                        <td class='order-title'>NetAmount <br> <span>(inclusive Vat)</span></td>
                                        <td class='order-details'><?=$TotalPrice;?> AED</td>
                                    </tr>
                                    <tr>
                                    <?php if($orderData['payment_mode'] == 'arabianpoint' || $orderData['payment_mode'] == 'Arabian Points'): ?>
                                        <td class='order-title'>Total AED<br> <span>(Paid Using Arabian Points)</span></td>
                                    <?php else: ?>  
                                        <td class='order-title'>Total AED<br> <span>(Paid Using Card)</span></td>
                                    <?php endif; ?> 
                                        <td class='order-details'><?=$TotalPrice;?> AED</td>
                                    </tr>
                                </table>

                                <div class='footer-container thankyou-container'>
                                    <div class='heading-section'>
                                        <h1>Thank you for Shopping</h1>
                                    </div>
                                    <div>
                                        <p> A Promotional Coupon has been issued with the Purchase</p>
                                    </div>
                                </div>
                                <div class='footer-container'>
                                    <div class='heading-section'>
                                        <h1>For more details</h1>
                                    </div>
                                    <div>
                                        <p class='information-section'> visit <a href='<?=base_url();?>'><?=base_url();?></a> <br>
                                            call us @ <a href='tel:+971554691351'>+971 4355 8533</a> 
                                        </p>
                                        <p> Email: info@dealzarabia.com</p>
                                    </div>
                                </div>

                                <div class='row' style='margin: 0px 35px;'>
                                    <div style='text-align: center;margin:auto;'>
                                        <img src="<?=base_url('/assets/img/ap_help_qr_code.png');?>" alt='QR'>
                                    </div>
                                </div>

                                <table> 
                                    <tr>
                                        <td class='order-title'> 
                                         <a href="<?=base_url('order/download-invoice/'.$order_id);?>" class="color-default-btn float-left">Download Invoice</a>
                                        </td>
                                        <td class='order-details'>
                                         <a href="<?=base_url('/')?>" class="color-default-btn float-right">Shop More</a>
                                        </td>
                                    </tr>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--footer-->
    <?php include('common/footer.php') ?>
    <?php include('common/footer_script.php') ?>
    <script type="text/javascript" src="<?=base_url('assets/')?>countdownTimer/multi-countdown.js"></script>
    <script>
    let listElements = document.querySelectorAll('li');
    listElements.forEach(element => {
        element.addEventListener('click', function() {
            let clr = this.getAttribute('data-color');
            //document.documentElement.style.setProperty('--color', clr);
            listElements.forEach(element => {
                element.style.border = "none";
            })
            this.style.border = "3px solid black";
        })
    });
    </script>
 
 
    
    
</body>

</html>