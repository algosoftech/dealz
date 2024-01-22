<?php 

$nameData = explode(' ',$name);
$count = count($nameData);
if($count > 2) :
    $newName = $nameData[0].' '.$nameData[$count -1];
elseif($count == 1):
    $newName = $nameData[0];
else:
    $newName = $name;
endif;

    $logo = base_url('assets/img/dealzlogo.png');

    $html = ' 
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dealzerbia | mailtemplate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        line-height :2;
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

    table {
        margin: 20px auto;
    }

    </style>
</head>
<body >

    <div style="width: 533px;margin: auto;background-color: #fff;padding: 19px 15px 19px;">
        <div class="row" style="margin: 0px 35px;">
            <div style="text-align: center;">
                <img class="logo" src='.$logo.' alt="logo">
            </div>
        </div>
        <div class="company-heading-section">
            <h3 class="ticket-heading"> DEALZARABIA TRADING L.L.C </h3>
            <div>Tel: 04 355 8533</div>
        </div>
        <table class="my-order-section">
            <tr>
                <td class="order-title">My Order # </td>
                <td class="order-details"> DFG484964684</td>
            </tr> 
        </table>
        <table>
            <tr>
                <td class="order-title">POS</td>
                <td class="order-details"> Merchant AL HILAL TRADING</td>
            </tr>
            <tr>
                <td class="order-title"> Purchased On  </td>
                <td class="order-details">  '.date('d M, Y H:m A' ,strtotime($orderData[0]["created_at"]) ).' </td>
            </tr>
            <tr>
                <td class="order-title"> Wired or Ear Phone  </td>
                <td class="order-details">10 AED</td>
            </tr>
            <tr>
                <td class="order-title"> Coupon Code</td>
                <td class="order-details">MN01011</td>
            </tr>
            <tr>
                <td class="order-title"> Total Qty.</td>
                <td class="order-details">01</td>
            </tr>
            <tr>
                <td class="order-title">NetAmount <br> <span>(inclusive Vat)</span></td>
                <td class="order-details">10 AED</td>
            </tr>
            <tr>
                <td class="order-title">Total AED<br> <span>(Cash)</span></td>
                <td class="order-details">10 AED</td>
            </tr>
        </table>
        <table class="border-top-bottom">
            <tr>
                <td class="order-title"> Purchased By </td>
                <td class="order-details">  Prinice <br> +971 50 889 5589 </td>
            </tr>
        </table>

        <div class="footer-container">
            <div class="heading-section">
                <h1>Thank you for Shopping</h1>
            </div>
            <div>
                <p> A Promotional Coupon has been issued with the Purchase</p>
            </div>
        </div>

        <table class="promotional-section">
            <tr>
                <td class="order-title">Promotional Campaign Value</td>
                <td class="order-details">80,000 AED</td>
            </tr>
            <tr>
                <td class="order-title">Promotional Campaign Validity</td>
                <td class="order-details">29.01.2024</td>
            </tr>
        </table>
        

        <div class="footer-container">
            <div class="heading-section">
                <h1>For more details</h1>
            </div>
            <div>
                <p class="information-section"> visit <a href='.$base_url.'> www.dealzarabia.com </a> <br>
                    call us @ <a href="tel:045541927">04 554 1927</a> 
                </p>
                <p> Email: info@dealzarabia.com</p>
            </div>

        </div>

        <div class="row" style="margin: 0px 35px;">
            <div style="text-align: center;">
                <img src='.base_url('/assets/img/ap_help_qr_code.png').' alt="QR">
            </div>
        </div>
        
       ';


    echo $html;

    die();