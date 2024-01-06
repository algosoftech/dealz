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

    $logo = base_url('assets/img/jpeg_logo.jpg');

$html = '
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dealzerbia | mailtemplate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    a:link, a:active {
        color: #000 !important;
        text-decoration: none;
    }
    a{
        color:#000 !important;
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
            <div style="background: #fff;padding: 5px 15px 5px;border: 1px solid #d4d4d4;border-radius: 10px;margin: 0px 18px;text-align: center;">
                <img src="'.$logo.'" alt="logo">
            </div>
        </div>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;">
                    <p style="font-family: sans-serif;text-align: center;font-size: 18px;font-weight: 500;margin: 34px 0px;">Hello '.$newName.', Your OrderDetails</p>
                </td>
            </tr>
        </table>
        <table style="width: 85%;border: 1px solid #80808078;padding: 10px 22px;margin: auto;border-radius: 6px;margin-top: 10px;">
            <tr style="display:block;">
                <td style="300px;"><p style="margin: 0px;font-family:sans-serif;line-height: 2;font-weight: 700;">Order Id</p></td>
                <td ><p style="font-family:sans-serif;text-align:end;line-height: 2;"margin-left:260px;>'.$orderData['order_id'].'</p></td>
            </tr>
            <tr style="display:flex;">
                <td><p style="margin: 0px;font-family:sans-serif;line-height: 2;font-weight: 700;">Order Date</p></td>
                <td><p style="margin:0px;font-family:sans-serif;text-align: end;line-height: 2;">'.date('d M, Y H:i A ', strtotime($orderData['created_at'])).'</p></td>
            </tr>
        </table>';

    foreach ($orderData['order_details'] as $key => $value) {
    $html .= '<table style="width: 85%;border: 1px solid #80808078;padding: 10px 22px;margin: auto;border-radius: 6px;margin-top: 10px;">
                <tr style="vertical-align: top;">
                    <td></td>
                    <td><p style="margin: 0px;font-family:sans-serif;line-height: 2;font-weight: 700;text-align: right;">AED '.number_format($value['subtotal'],2).'</p></td>
                </tr>
                <tr>
                    <td>
                        <p style="margin: 0px;font-family:sans-serif;font-weight: 700;text-align:left;font-size: 15px;">'.stripslashes($value['product_name']).'</p>
                    </td>
                    <td>
                        <p style="margin: 0px;font-family: sans-serif;text-align:right;font-size: 15px;">Qty:'.$value['quantity'].'</p>
                    </td>
                </tr>
            </table>';
    }

$html .= '<table style="width: 85%;border: 1px solid #80808078;padding: 10px 22px;margin: auto;border-radius: 6px;margin-top: 10px;">
            <tr>
                <td colspan="2">
                    <h2 style="font-family:sans-serif;text-align: center;">Payment Information</h2>
                </td>
            </tr>';

if($orderData['payment_mode'] == 'Arabian Points'){
    $html .= '<tr>
                <td>
                    <p style="margin: 0px;font-family:sans-serif;line-height: 2;font-weight: 700;">Paid Using Arabian Point</p>
                </td>
                <td>
                    <p style="margin: 0px;font-family:sans-serif;text-align: end;line-height: 2;">AP '.number_format($orderData['total_price'],2).'</p>
                </td>
            </tr>';
    }else{
    $html .= '<tr>
                <td><p style="margin: 0px;font-family:sans-serif;line-height: 2;font-weight: 700;">Paid Using Card</p></td>
                <td><p style="margin: 0px;font-family:sans-serif;text-align: end;line-height: 2;">AED '.number_format($orderData['total_price'],2).'</p></td>
             </tr>';
    }
    $html .= '</table>';
    $html .='<table style="width: 85%;border: 1px solid #80808078;padding: 10px 22px;margin: auto;border-radius: 6px;margin-top: 10px;">
                <tr>
                    <td colspan="2">
                        <h2 style="font-family:sans-serif;text-align: center;">Coupon Information</h2>
                    </td>
                </tr>';
    // $count = count(($orderData['coupon_details']));
    // for($i=0; $i < $count; $i++){
    // $html.=	'<tr style="border: 1px solid gray;">
    //             <td>
    //                 <p style="margin: 0px;font-family:sans-serif;line-height: 2;font-weight: 700;">Product Name</p>
    //             </td>
    //             <td>
    //                 <p style="margin: 0px;font-family:sans-serif;text-align: end;line-height: 2;">'.substr(stripslashes($orderData['coupon_details'][$i]['product_title']),0,17).'</p>
    //             </td>
    //         </tr>
    //         <tr>
    //             <td>
    //                 <p style="margin: 0px;font-family:sans-serif;line-height: 2;font-weight: 700;">Coupon Code</p>
    //             </td>
    //             <td>
    //                 <p style="margin: 0px;font-family:sans-serif;text-align: end;line-height: 2;">'.$orderData['coupon_details'][$i]['coupon_code'].'</p>
    //             </td>
    //         </tr>';	
    // }
    foreach ($orderData['coupon_details'] as $key => $item) {


            $tblName                =   'da_products';
            $shortField             =   array('_id'=> -1 );
            $whereCon['where']      =   array('products_id'=>(int)$item['product_id']);
            $ProductDetails  =  $this->geneal_model->getData2('single', $tblName, $whereCon,$shortField);

    $html.=	'<tr style="border: 1px solid gray;">
                <td>
                    <p style="margin: 0px;font-family:sans-serif;line-height: 2;font-weight: 700;">Product Name</p>
                </td>
                <td>
                    <p style="margin: 0px;font-family:sans-serif;text-align: end;line-height: 2;">'.substr(stripslashes($item['product_title']),0,17).'</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="margin: 0px;font-family:sans-serif;line-height: 2;font-weight: 700;">Coupon Code</p>
                </td>
                <td>
                    <p style="margin: 0px;font-family:sans-serif;text-align: end;line-height: 2;">'.$item['coupon_code'].'</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="margin: 0px;font-family:sans-serif;line-height: 2;font-weight: 700;">Draw Date</p>
                </td>
                <td>
                    <p style="margin: 0px;font-family:sans-serif;text-align: end;line-height: 2;"> '.date('d M, Y',strtotime($ProductDetails['draw_date'].' '.$ProductDetails['draw_time'])).'</p>
                </td>
            </tr>';
    }
    $html .= '</table>';
    
    $html .= '<table style="width: 85%;margin: auto;text-align: center;">
                <tr>
                    <td colspan="2">
                        <h2 style="font-family:sans-serif;text-align: center;">Customer Support Service</h2>
                    </td>
                </tr>
                <tr style="vertical-align: bottom;">
                    <td>
                        <p style="margin: 0px;"><img src="https://dealzarabia.com/assets/img/phone-solid.jpg" alt="" width="15px"><span> <a href="tel:045541927" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color:#000;">045541927</a></span></p>
                    </td>
                    
                    <td>
                        <p  style="margin: 0px;"><img src="https://dealzarabia.com/assets/img/envelope-solid.jpg" alt="" width="15px"><span> <a href="mailto:nfo@dealzarabia.com" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color:#000;">info@dealzarabia.com</a></span></p>
                    </td>
                </tr>
            </table>
            <table style="width: 85%;margin: auto;text-align: center;">
                <tr>
                    <td> 
                        <p style="text-align: center;"><img src="https://dealzarabia.com/assets/img/globe-solid.jpg" alt="" width="15px"><span> <a href="https://dealzarabia.com/" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color:#000;">https://dealzarabia.com/</a></span></p>
                    </td>
                </tr>
            </table>
            <table style="width: 85%;margin: auto;">
                <tr>
                    <td style="background-color: red;color:#FFF;text-align: center;font-family:sans-serif;padding:10px;">Copyright© '.date('Y').'</td>
                </tr>
            </table>
    </body>
    </html>        ';

// echo($html);die();
//echo $this->config->item("root_path");die();
@unlink($this->config->item("root_path").'/assets/orderpdf/'.$orderData['order_id'].'.pdf');
  // $mpdf=new mPDF('utf-8','A4',0,'',10,10,10,24);

  $headerpdf='';
  $footerpdf='';

  $mpdf = new \Mpdf(['debug' => true]);

  $mpdf->SetHTMLHeader($headerpdf);
  $mpdf->SetHTMLFooter($footerpdf);
  $mpdf->SetDisplayMode('fullpage');
  $mpdf->AddPage();
  $mpdf->WriteHTML($html);
  $mpdf->Output($this->config->item("root_path").'/assets/orderpdf/'.$orderData['order_id'].'.pdf','F');
//   echo 'end';die();
// print_r($html);die();
?>   