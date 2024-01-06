<?php 
$name = explode('@', $orderData['user_email']);
//echo '<pre>';print_r($orderData);die();
$html = '<!DOCTYPE html>
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
					<body style="background-color: #1e1e1e;color: #010f19 !important;">
						<div style="width: 533px;margin: auto;background-color: #fff;padding: 19px 15px 19px;">
							<div class="row" style="margin: 0px 35px;">
								<div style="background: #fff;padding: 5px 15px 5px;border: 1px solid #d4d4d4;border-radius: 10px;margin: 0px 18px;text-align: center;">
									<img src="https://dealzarabia.com/assets/img/logo.png" alt="logo">
								</div>
							</div>';
				$html .= '<div class="main_content" style="margin:39px 0px;text-align:center;">
				<h1 style="font-family: "sans-serif;font-size: 12px;color: #343333;text-align: center;margin: 0px;line-height: 0px;"> Invoice </h1>
				<p style="font-family: "sans-serif;font-size: 10px;color: #343333;text-align: center;margin: 0px;line-height: 0px;"> Hello '.$name[0].', Your Order Details</p>
			</div>
			<div class="main_content" style="margin:39px 0px;    margin: 39px 54px;border: 1px solid #80808061;padding: 4px 16px 15px;border-radius: 5px;">
				<div class="row" style="border-bottom: 1px solid #80808030;display: inline-flex;justify-content: space-between;margin: 11px 0px 0px;">
				<div style="width: 315px;">	
				<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;margin-top: 0px;">
						Order Id</p>
						</div>
					<div>
					<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight:600;">
						'.$orderData['order_id'].'</p>
						</div>	
				</div>
				<div class="row" style="border-bottom: 1px solid #80808030;display: inline-flex;justify-content: space-between;margin: 0px 0px;">
				    <div style="width: 315px;">
					<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin: 0px;
					margin-right: 247px;margin-top: 0px"> Order Date</p>
					</div>
					<div>
					<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin:0px;font-weight:600;margin-top: 0px"> '.date('d M, Y', strtotime($orderData['created_at'])).'</p>
				</div>
				</div>
			</div>';

			foreach ($orderData['order_details'] as $key => $value) {
				$html .= ' <div class="row" style="display: flex;justify-content: space-between;margin: 11px 57px;border: 1px solid #80808061;padding: 13px;">
						<div class="product_box">
							<div class="img">
								<img src="'.base_url().$value['other']['image'].'" style="width:100px;">
							</div>
						</div>
						<div class="product" style="margin: 0px 20px;">
							<h1 style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom: 1px;"> '.stripslashes($value['product_name']).'</h1>
							<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin:0px;ine-height: 20px;"> Qty : <span style="font-weight: 600;">'.$value['quantity'].'</span></p>
							<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin: 0px;line-height: 20px;"> '.stripslashes($value['other']['description']).'</p>
						</div>
						<div class="product_details">
							<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;font-weight:600;text-align: end;margin: 12px 0px;"> AED'.number_format($value['subtotal'],2).'</p>
						</div>
					</div>
					'; 
			}

			$html .= '<div class="main_content" style="margin:0px 0px;">
            <div class="row" >
			     <div style="text-align: center;">
                <h1 style="font-family: "Open Sans", sans-serif;font-size: 9px;color: #343333;">Payment Information </h1>
				</div>
                <div class="main_content" style="margin: 29px 54px;border: 1px solid #80808061;padding: 4px 16px;border-radius: 5px;">
                    <div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
					<div style=" width: 324px;">
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;margin-top: 0px;"> Inclusice of VAT</p>
						</div>
						<div>
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['inclusice_of_vat'],2).'</p>
						</div>
                    </div>
                    <div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
					<div  style=" width: 324px;">
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Subtotal</p>
						</div>
						<div>
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['subtotal'],2).'</p>
						</div>
                    </div>
                    <div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
					<div  style=" width: 324px;">
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> VAT</p>
                     </div>
					 <div>   
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED'.number_format($orderData['vat_amount'],2).'</p>
                    </div>
					
						</div>';

            if($orderData['payment_mode'] == 'Arabian Points'){
				$html .='<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
				<div style=" width: 324px;">
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Paid Using Arabian Point</p>
                        </div>
						<div>
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
                    </div>
						</div>';
			}else{
				$html .='<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
				<div style=" width: 324px;">	
				<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Paid Using Card </p>
				</div>
				<div>	
				<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
				</div>
				</div>';
			}

                $html .= '</div>
            </div>
        </div>';
		$html .=	'<div class="main_content" style="margin:0px 0px;">
		<div class="row" >
			<div style="text-align: center;font-family: sans-serif;">
				<h1 style="font-family: sans-serif;;font-size: 18px;color: #343333;">Coupon Information </h1>
			</div>
			<div class="main_content" style="margin: 29px 54px;border: 1px solid #80808061;padding: 4px 16px;border-radius: 5px;">';
$flag = 0;
$count = count($orderData['coupon_details']);
if($count > 5){
	$flag = 1;
}

$newCount = ($flag == 0) ? $count : 5;

for ($i=0; $i < $newCount; $i++) { 
	$html .=	'<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
					<div style="width:285px">
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> Product Name</p>
					</div>
					<div>
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> '.substr(stripslashes($orderData['coupon_details'][$i]['product_title']),0,15).'</p>
					</div>
				</div>

				<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
					<div style="width:267px">	
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> Coupon Code</p>
					</div>
					<div>	
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> '.stripcslashes($orderData['coupon_details'][$i]['coupon_code']).'</p>
					</div>
				</div>';
}
$html .= '</div> </div> </div>'; 	

$html .='<div class="main_content" style="margin: -13px 41px 0px;text-align:center;">
            <div class="row" style="border-bottom: 1px solid #80808030;">
                <div style="text-aling:center;font-family:sans-serif;font-size: 26px; ">
                    <p style="color: #343333;font-family: "Open Sans", sans-serif;font-size: 26px; font-weight: 600;text-align: center;font-size: 19px;margin:0px;">Customer Support Service</p>
                </div>
            </div>
            <div class="row" style="display:inline-flex;justify-content: space-around;margin: 24px 0px;">
                <div class="" style="display: flex;flex-direction: row;justify-content: center; align-items: cente;color: #343333;margin-right: 38px;">
                    <img src="'.base_url().'assets/img/phone-solid.jpg" alt="" style="width: 20px;">
                    <a href="tel:0800" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color: #343333 !important;">0800</a>
                </div>
                <div class="" style="display:inline-flex;flex-direction: row;justify-content: center; align-items: center;color: #343333;">
                    <img src="'.base_url().'assets/img/envelope-solid.jpg" alt="" style="width: 20px;">
                    <a href="mailto:info@dealzarabia.com" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color: #343333 !important;">info@dealzarabia.com</a>
                </div>
            </div>
            <div class="row" style="display:blockjustify-content:center;margin: 0px 0px;color:#000;">
                <div class="" style="display:inline-flex;flex-direction: row;justify-content: center; align-items: center;">
                    <img src="'.base_url().'assets/img/globe-solid.jpg" alt="" style="width: 20px;height: 10%;">
                  <p style="margin:0px;color:#000;!important;line-height: 0px;">  <a href="https://dealzarabia.com/" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color:#000;">https://dealzarabia.com/</a><p>
                </div>
            </div>
        </div>
        <div class="row" style="text-align: center;display: inline-flex;">
            <div style="background-color: #d82b2b;color: #fff;font-family: "Open Sans", sans-serif;font-size: 16px; font-weight: 400;text-align: center;width:50%;background-color: #d82b2b;
			color: #fff;
			">
                <p style="margin:0px;padding: 12px 76px;background-color: #d12929;
				color: #fff;">Copyright© '.date('Y').'</p>
            </div>
            <div style="background-color: #9e9e9e3d;color: #343333;font-family: "Open Sans", sans-serif;font-size: 16px; font-weight: 400;text-align: center;width:50%;background-color: #9e9e9e3d;
			padding: 10px 76px;"
			color: #343333;">
            <a href="https://dealzarabia.com/privacy-policy" style="margin: 0px; color: #343333; text-decoration: none;font-family:sans-serif;padding: 12px 99px;line-height:3;background: #9e9e9e54;">Privacy Policy </a></div>
        </div> </div>
</body>

</html>';
//echo($html);die();
//echo $this->config->item("root_path");die();
@unlink($this->config->item("root_path").'/assets/orderpdf/'.$orderData['order_id'].'.pdf');
  $mpdf=new mPDF('utf-8','A4',0,'',10,10,10,24);

  $headerpdf='';
  $footerpdf='';

  $mpdf = new \Mpdf(['debug' => true]);

  $mpdf->SetHTMLHeader($headerpdf);
  $mpdf->SetHTMLFooter($footerpdf);
  $mpdf->SetDisplayMode('fullpage');
  $mpdf->AddPage();
  $mpdf->WriteHTML($html);
  $mpdf->Output($this->config->item("root_path").'/assets/orderpdf/'.$orderData['order_id'].'.pdf','I');  
  echo 'end';die();
print_r($html);die();
?>   