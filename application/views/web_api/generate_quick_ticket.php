<?php 
    $base_url = base_url();
   // echo '<pre>'; print_r($orderData[0]);die();
    $coupon_code = implode(' , ', $orderData[0]["coupon_code"] ) ;

    if(@$retailerData['store_name']):
        @$retailer =  $retailerData['store_name'];
    else:
        @$retailer =  $retailerData['users_name']. ' ' . $retailerData['last_name'];
    endif;

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
        border-top: dashed;
        border-bottom: dashed;
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
    </style>
</head>
<body >

    <div style="width: 533px;margin: auto;background-color: #fff;padding: 19px 15px 19px;">
        <div class="row" style="margin: 0px 35px;">
            <div style="text-align: center;">
                <img src='.base_url('/assets/img/white-logo.png').' alt="logo">
            </div>
        </div>

        <div class="my-ticket-section">
            <h1 class="ticket-heading"> My Ticket  # <span> '.$coupon_code.' </span> </h1>
        </div>

        <table>
            <tr>
                <td class="order-title"> Draw Date </td>
                <td class="order-details"> '.date('d M, Y' ,strtotime($orderData[0]["draw_date"][0]) ).' </td>
            </tr>
            <tr>
                <td class="order-title font-bold"> Raffle Draw Prize</td>
                <td class="order-details font-bold"> '.$orderData[0]["prize_title"].' </td>
            </tr>

            <tr>
                <td class="order-title"> Product</td>
                <td class="order-details"> '. $orderData[0]["product_qty"] . ' x  '. $orderData[0]["product_title"] .' </td>
            </tr>

            <tr>
                <td class="order-title"> Price (inclusive VAT 5%)</td>
                <td class="order-details"> AED '. $orderData[0]["total_price"] .' </td>
            </tr>

            <tr>
                <td class="order-title"> Purchased By </td>
                <td class="order-details">  '. $orderData[0]["order_first_name"] .' '. $orderData[0]["order_last_name"] .'  <br> '. $orderData[0]["order_users_mobile"] .' </td>
            </tr>

            <tr>
                <td class="order-title"> Purchased On  </td>
                <td class="order-details">  '.date('d M, Y H:m A' ,strtotime($orderData[0]["created_at"]) ).' </td>
            </tr>

            <tr>
                <td class="order-title"> Merchant </td>
                <td class="order-details"> '. $retailer .' </td>
            </tr>


        </table>


        <table class="my-order-section">
            <tr>
                <td class="order-title">My Order #  </p></td>
                <td class="order-details"> '. $orderData[0]["ticket_order_id"] .'</td>
            </tr>
        </table>

        <div class="footer-container">
            <div class="heading-section">
                <h1> ARABIAN PLUS TRADING L.L.C </h1>
            </div>
            <div>
                <p class="information-section"> For more information, visit <a href='.$base_url.'> www.arabianplus.ae </a> or call us @ <a href="tel:045541927">04 554 1927</a> </p>
                <p> Email: info@dealzarabia.com</p>
            </div>

        </div>

        <div class="row" style="margin: 0px 35px;">
            <div style="text-align: center;">
                <img src='.base_url('/assets/img/ap_help_qr_code.png').' alt="QR">
            </div>
        </div>
        
       ';

     
// echo($html);die();
//echo $this->config->item("root_path");die();
@unlink($this->config->item("root_path").'/assets/orderpdf/'.$orderData[0]['ticket_order_id'].'.pdf');
  // $mpdf=new mPDF('utf-8','A4',0,'',10,10,10,24);
  $headerpdf='';
  $footerpdf='';

  $mpdf = new \Mpdf(['debug' => true]);

  $mpdf->SetHTMLHeader($headerpdf);
  $mpdf->SetHTMLFooter($footerpdf);
  $mpdf->SetDisplayMode('fullpage');
  $mpdf->AddPage();
  $mpdf->WriteHTML($html);
  $mpdf->Output($orderData[0]['ticket_order_id'].'.pdf','D');
//   echo 'end';die();
// print_r($html);die();
?>   