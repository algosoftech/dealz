<?php echo""; ?>
<!Doctype html>
<html lang="eng">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Dealz Arabia | Cart-Items</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="facebook-domain-verification" content="br469bduqxi9ktcquu78izycc2z1kf" />
<meta name="p:domain_verify" content="4b49a42ad1da3a8e25c7e8921a53e5f4"/>  
<link rel="icon" href="<?=base_url('assets/')?>img/logo.png" type="image/x-icon"/>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.1/css/all.css">
<link rel="stylesheet" href="<?=base_url('assets/')?>css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">			
<link rel="stylesheet" href="<?=base_url('assets/')?>css/style.css">		
<link rel="stylesheet" href="<?=base_url('assets/')?>css/responsive.css">
    <style>
		.textes {
    font-size: 11px;
    position: relative;
    top: 15px;
    left: 0px !important;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: #031b26;
}
	.error{
	color: red; 
}
.activess{
background-color:green !important;
}


.empty-cart-section {
    padding: 5% 0;
    margin: 0 auto;
}

.empty-cart-section .empty-shopping-cart {
    background: #a9a2a21a;
    color: #46cd46e6;
    border-radius: 50%;
    width: 150px;
    margin-bottom:20px;
}

a.home-redirect-link {
    color: #fff;
    border-color: #0090ff;
    background-color: #0090ff;
    background-image: linear-gradient(180deg,#0090ff 4%,#312dff);
    cursor: pointer;
    border-radius: 15px;
    padding: 23px 100px;
    font-weight: 900;
}

a.home-redirect-link:hover{
	color: #fff;
}

.empty-cart-heading{
	color: #031b26 !important;
    font-size: 16px;
    margin: 0px auto 40px !important;
}

.slide-product-box .coupen-img {
     max-width: 70px;
    position: relative;
    margin-top: -114px;
    display:none;
}





@media only screen and (max-width:768px){
	a.home-redirect-link{
		color: #fff !important; 
		padding: 23px 56px;
	}
}

    .outside-cart-block .toggle {
    position: relative;
    height: 14px;
    width: 50px;
    border-radius: 15px;
    background: #ddd;
    margin: 8px 0;
    float: right;
}
.outside-cart-block p {
    margin: 0;
    color: #fff;
}
.outside-cart-block .toggle input:nth-child(2):checked {
    z-index: 1;
}
.fa-trash:before {
    content: "\f1f8";
    color: #a62323;
    font-size: 19px;
}
.outside-cart-block  .toggle input:nth-child(2):checked + .toggle__pointer {
  left: 22px;
  background-color: #fff;
}
.outside-cart-block .toggle input:nth-child(2):checked + .toggle__pointer {
    left: 22px;
    background-color: #fff;
}
.outside-cart-block .toggle__pointer {
    position: absolute;
    top: -7px;
    left: 0;
    width: 28px;
    height: 28px;
    border-radius: 15px;
    background-color: #ffffff;
    -webkit-transition: left .15s ease-out;
    transition: left .15s ease-out;
}
.outside-cart-block .toggle input {
    opacity: 0;
    width: 100%;
    height: 200%;
    position: absolute;
    top: -7px;
    left: 0;
    z-index: 2;
    margin: 0;
}
.slide-product-box .product-des p {
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    text-align: center;
    color: #d12a2b;
    font-weight: 700;
    margin-bottom: 5px;
}
.card-list img{
	width: 38px !important;
	margin: 0px 3px;
}
.card-list{
	display:flex;
	justify-content: center;
}
.slide-product-box .product-des h4 {
    font-family: 'Open Sans', sans-serif;
    font-size: 16px;
    font-weight: 600;
    text-align: center;
    color: #181818;
    white-space: nowrap;
    overflow: hidden;
}
.slide-product-box .product-des h5 {
    font-family: 'Open Sans', sans-serif;
    font-size: 17px;
    font-weight: 400;
    text-align: center;
    color: #0d9e51;
    font-weight: 700;
    margin-bottom: 4px;
}
.easyPieChart{
text-align: center;
    color: #000;
}
.outside-cart-block .toggle {
    position: relative;
    height: 14px;
    width: 50px;
    border-radius: 15px;
    background: #ddd;
    margin: 8px 0;
    float: right;
}
.textes {
    font-size: 14px !important;
    position: relative;
    top: -1px !important;
    left: 0px !important;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: #031b26;
    left: 19px !important;
}
.outside-cart-block .donate-footer {
    background: #e72d2e;
    padding: 5px 30px;
    border-radius: 0 0 8px 8px;
    margin-top: 0px;
    z-index: 2;
    position: relative;
}
figure.containerZoom{background-position:50% 50%;position:relative;width:100%;overflow:hidden;cursor:zoom-in;margin:0}figure.containerZoom img{transition:opacity .5s;display:block;width:100%}figure.containerZoom.active img{opacity:0}
    timer {
    display: flex;
    /*gap: 4px;*/
    }

    .only-prize-detail{
    	border-radius: 8px !important;
    }

	.calendar_view{
		width: 100%;
    position: relative;
    top: -28px !important;
    left: 25px !important;
	}
	.calendar_timer img{
	
		height: 30px;
	}
	.timer-hours div{
    background-color: #80808033;
    border-radius: 0px;
    margin: 0px 4px;
	}
	.timer-hours h1 {
    margin: 0px;
    font-size: 10px;
    background-color: #0f7ad8b8;
    color: #fff;
    text-align: center;
}
.timer-hours p {
    margin: 0px;
    font-size: 13px !important;
    padding: 6px 14px 0px;
    line-height: normal;
}
	
.timer-hours {
    display: flex;
    justify-content: space-around;
}


.calendar p {
    margin: 0px;
    font-size: 13px !important;
	line-height: normal;
	text-align:left !important;
}
.calendar{
	margin-left:10px;
}
	.calendar h1{
		margin:0px;
		font-size: 13px;
	}
    .calendar_timer{
		display:flex;
	}

	.coming_soon_product{
		left: -6PX;
    color: white;
    transform: rotateZ(-39deg);
    text-align: center;
    margin-left: -15px;
    position: absolute;
    margin-top: 4px;
    font-weight: 700;
    border-bottom: 22px solid #d12a2b;
    border-left: 19px solid transparent;
    border-right: 26px solid transparent;
    height: 0;
    width: 125px;
    line-height: 18px;
    font-size: 11px;
	}
	.picZoomer{
	position: relative;
    /*margin-left: 40px;
    padding: 15px;*/
}
.picZoomer-pic-wp{
	position: relative;
	overflow: hidden;
    text-align: center;
}
.picZoomer-pic-wp:hover .picZoomer-cursor{
	display: block;
}
.picZoomer-zoom-pic{
	position: absolute;
	top: 0;
	left: 0;
}

.picZoomer-zoom-wp{
	display: none;
	position: absolute;
	z-index: 999;
	overflow: hidden;
    border:1px solid #eee;
    height: 460px;
    margin-top: -19px;
}
.picZoomer-cursor{
	display: none;
	cursor: crosshair;
	width: 100px;
	height: 100px;
	position: absolute;
	top: 0;
	left: 0;
	border-radius: 50%;
	border: 1px solid #eee;
	background-color: rgba(0,0,0,.1);
}
.picZoomCursor-ico{
	width: 23px;
	height: 23px;
	position: absolute;
	top: 40px;
	left: 40px;
	background: url(images/zoom-ico.png) left top no-repeat;
}
	._boxzoom .zoom-thumb {
    width: 90px;
    display: inline-block;
    vertical-align: top;
    margin-top: 0px;
}
._boxzoom .zoom-thumb ul.piclist {
    padding-left: 0px;
    top: 0px;
}
._boxzoom ._product-images {
    width: 80%;
    display: inline-block;
}
._boxzoom ._product-images .picZoomer {
    width: 100%;
}
._boxzoom ._product-images .picZoomer .picZoomer-pic-wp img {
    left: 0px;
}
._boxzoom ._product-images .picZoomer img.my_img {
    width: 100%;
}
.piclist li img {
    height:100px;
    object-fit:cover;
}
	.explore-campaign {
    padding: 40px 0 0px;
}
	.fa-calendar-check:before {
    content: "\f274";
    color: #0d9e51;
}
	.slide-product-box .product-img {
	padding: 10px;
	width: 60%;
	height: 130px;
	overflow: hidden;
	margin-left: 47px;
	} 
	.panel{
	margin:0px 10px;
	}
	button:focus {
		outline: 0px auto -webkit-focus-ring-color;
	}
	.align_leftes{
		padding: 0px;	
	}
	.text-align{
	text-align:center;
	padding: 0px;
	}
	.product-des h2{
		margin-bottom: 15px;
	}
	.product_img {
		border: 1px solid #e0e0e0;
	    border-radius: 7px;
	    margin-top: 0px;
	    margin-left: -6px;
	}
	.explore-product-box .product-des h4 {
		font-family: 'Open Sans', sans-serif;
		font-size: 19px;
		text-align: center;
		font-weight: 700;
		color: #181818;
		margin-bottom: 15px;
		margin-top: 18px;
	}
	.countdown{
		display: flex;
		justify-content: space-evenly;
		color: #000;
		align-items: baseline;
		font-family: 'Open Sans', sans-serif;
		font-size: 16px;
		font-weight: 600;
		text-align: center;
		color: #181818;
	}
	.explore_button li{
	margin: 0px;   
	}
	.explore-campaign p{
	font-family: 'Open Sans', sans-serif;
		font-size: 16px;
		font-weight: 600;
		text-align: center;
		color: #181818;  
	}
	.dolleres {
		position: relative;
		top: 58px;
		width: 104px;
		left: 0px;
	    margin-bottom: 90px;
	}
	.explore-product-box .product-img:hover {
		box-shadow: 1px 1px 5px 0px rgb(209 209 209 / 0%);
		
	}	
	.explore-product-box .product-des h5 {
		font-family: 'Open Sans', sans-serif;
		font-size: 17px;
		font-weight: 600;
		text-align: center;
		color: #181818;
		margin-top: 16px;
		margin-bottom: 8px;
	}
	.hearts{
		display: flex;
		flex-direction: column;
		align-items:center;
		margin-bottom: 40px;
	}
	.explore_boxes{
		width: 26%;
		position: absolute !important;
		top: -14px  !important;   
		left: 5px !important;   
	}
	.heading_big{
		left:58px !important;

	}
	.slide-product-box .product-des p {
		font-family: 'Open Sans', sans-serif;
		font-size: 15px;
		font-weight: 400;
		text-align: center;
		color: #d12a2b;
		margin-bottom: 5px;
	}
	.active{
			content: "\f004";
		font-weight: 800 !important;
		color: #d12a2b !important;
		}
	.fa-heart.active {
		color: #d12a2b !important;
		font-weight: 400;
	}

	.explore-product-box .product-des p {
		font-family: 'Open Sans', sans-serif;
		font-size: 14px;
		line-height: 22px;
		font-weight: 400;
		text-align: left;
		color: #6c757d;
		margin-bottom: 0;
	}
	.fa-share:hover {
		content: "\f004";
		font-weight: 900 !important;
		font-family: "Font Awesome 5 Pro";
	}
	.share {
		content: "\f1e0";
		color: rgb(227 44 45) !important;
		font-size: 18px;
	
	}
		.fa-share:hover{
	color: #d12a2b !important;   
	}
	.share i:hover {
		color: #000 !important;
	}
	.hearts a:hover{
		color: #000 !important;
	}

	.heart_icon {
		color: #000 !important;
		font-size: 26px;
	}
	.fa-heart:hover {
		color: #000 !important;
	}
	.explore-product-box {
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		padding: 15px;
		margin-top: 41px;
		height: 308px;
	}
	.box {
		width: 8%;
		position: relative;
		top: -17px;
	}

	.box h2 {
	display: block;
	text-align: center;
	color: black;
	position: relative;
		top: -23px;
		left: 13px;

	}
	.explore-product-box .action-block .coupen-img .textes {
		position: relative;
		top: -17px;
		left: 41px !important;
		font-size: 14px;
		font-weight: 600;
		color: #031b26;
		font-family: 'Open Sans', sans-serif;
	}
	.textes {
		font-size: 11px;
		position: relative;
		top: 43px;
    	left: 0px !important;
		font-family: 'Open Sans', sans-serif;
		font-size: 15px;
		font-weight: 600;
		color: #031b26;
	}
	.explore_button{
	list-style: none;
		display: inline-flex;
		padding: 0px;
		align-items: center;
		justify-content: space-between;
		margin-top:10px;
	}
	.box .chart {
		position: relative;
		width: 100%;
		height: 100%;
		text-align: center;
		font-size: 12px;
		line-height: 108px;
		height: 160px;
		color: #300808;
		left: 0px;
		background-color: #fff;
	}
	.box .chart1 {
		position: relative;
		width: 100%;
		height: 100%;
		text-align: center;
		font-size: 12px;
		line-height: 108px;
		height: 160px;
		color: #300808;
		top: 120px;
    left: -8px;
		background-color: #fff;
	}

	.box .chartdemo {
		position: relative;
		width: 100%;
		height: 100%;
		text-align: center;
		font-size: 12px;
		line-height: 108px;
		height: 160px;
		color: #300808;
		top: 18px;
	}

	.box canvas {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	width: 100%;
	}
				
	.ui-widgets .ui-values {
		top: 20px;
		position: absolute;
		left: 3px;
		right: 0;
		font-weight: 700;
		font-size: 17px;
	}
	
	.ui-widgets .ui-labels {
		left: 4px;
		bottom: -61px;
		text-shadow: 0 0 4px grey;
		color: black;
		position: absolute;
		width: 100%;
		font-size: 15px;
	}
	.text p{
		font-family: 'Open Sans', sans-serif;
		font-size: 14px;
		font-weight: 600;
		text-align: center;
		color: #181818;
		margin-bottom: 20px;  
		}
		.idealses{
		padding-top: 37px;
		border: 5px dashed #e72d2e;
		width: 107px;
		height: 104px;
		text-align: center;
		border-radius: 50%;
		font-size: 10px;
		font-weight: bold;  
		font-family: 'Open Sans', sans-serif;
		font-size: 10px;
		font-weight: 600;
		/* text-align: left; */
		color: #181818;
		margin-bottom: 20px;
		}
		.edit_cart {
	background: #c42828;
		border: 1px solid #ec2d2f;
		padding: 5px 25px;
		border-radius: 8px 0px 0px 8px;
		color: #fff;
		font-family: 'Open Sans', sans-serif;
		font-size: 15px;
		font-weight: 400;
		margin-top: 0px;
		width: 100%;
	}
	.edit_cart:hover {
		background: #ffffff;
		color: #e70909;
	}
		.load-more-product {
		padding: 55px 0px 55px !important;
		text-align: center;
	}
		.explore-product-box .action-block .coupen-img {
		max-width: 100%;
		margin-top: -58px;
	}
	.slide-product-box .coupen-img {
		max-width: 70px;
		position: relative;
	margin-top: -145px;
	}	
	.coupen-img h2 {
		font-size: 14px;
		font-weight: 600;
		color: #031b26;
		font-family: 'Open Sans', sans-serif;
		position: relative;
		left: 11px !important;
	    top: 102px !important;
	}
	b, strong {
		font-weight: 400;
		color: #495057;
	}
	.currency-switcher div.dropdown>div.caption span {
		font-weight: 400;
		font-size: 14px;
		letter-spacing: 0.3px;
		color: #6c757d;
		position: absolute;
		right: 19px;
	}
	.white-btn:hover {
		background: #ffffff !important;
		color: #d12a2b !important;
	}
	.white-btn {
		padding: 6px 15px;
	    border: 1px solid #e0e0e0 !important;
	    color: #363636;
	    border-radius: 0px 8px 8px 0px;
	    color: #363636;
	    font-family: 'Open Sans', sans-serif;
	    font-size: 15px;
        font-weight: 700;
    	background: #E8E2E2;
	    color: #000 !important;
	}
	.default-btn {
		background: #a12222C !important;
		border: 1px solid #a12222 !important;
		padding: 5px 25px !important;
		border-radius: 8px 0px 0px 8px;
		color: #FFFFFF !important;
		font-family: 'Open Sans', sans-serif;
		font-size: 15px;
		font-weight: 600;
	}
	.explore-product-box .product-des {
		text-align: center;
		
	}
	.fa-share-alt:before {
    content: "\f1e0";
    background-color: #0d9e51;
    padding: 8px;
    border-radius: 50%;
}
	.default-btn:hover{
	background:#d12a2b !important;
		color:#fff !important;  
	}
	.fa-share:before {
		content: "\f064";
		font-family: "Font Awesome 5 Pro";
	}
	.share {
		content: "\f1e0";
    font-size: 15px;
    padding-left: 5px;
    color: #fff !important;
	}
	.heart{
		display:flex;
	}
	#main-carousel .owl-dots {
		position: absolute;
		top: 91%;
		left: 50%;
	}
	.fa-heart.active {
	color: #d12a2b !important;
	}
	#main-carousel .owl-dots .owl-dot span {
		width: 8px;
		height: 8px;
		border-radius: 50%;
		display: block;
		background: #031b26;
	}
	#main-carousel .owl-dots .owl-dot.active span {
		background: #b32425;
	}
	.sociallinks ul { list-style-type: none; }
	.sociallinks ul li { cursor: pointer; }



	.heart_icon {
		color: rgb(209 209 209);
		font-size: 26px;
	}

	.fa-heart:hover {
		content: "\f004";
		font-weight: 800 !important;
	}
	
	@media (min-width: 1024px) and (max-width: 1100px) {
		.edit_cart {
    background: #c42828;
    border: 1px solid #ec2d2f;
    padding: 4px 13px;
    border-radius: 8px 0px 0px 8px;
    color: #fff;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    margin-top: 0px;
    width: 100%;
}
.white-btn {
    padding: 6px 9px;
    border: 1px solid #e0e0e0 !important;
    color: #363636;
    border-radius: 0px 8px 8px 0px;
    color: #363636;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 700;
    background: #E8E2E2;
    color: #000 !important;
}
	}
	@media (min-width: 360px) and (max-width: 600px) {
	    .modal-body .product_img {
	            margin-left:0px !important;
	        width:100% !important;
	        	z-index: 9999;
    position: relative;
	    }
	    b{
	 display: flex;
    align-items: center;
	    }
	.calendar_view {
    width: 100%;
    position: relative;
    top: 322px !important;
    left: 16px !important;
}
		.my_img{
            border: 1px solid #e0e0e0;
    border-radius: 7px;
    margin-top: 0px;
    width: 126px;
    padding: 5px;
    margin-left: 0px;
		}
	.explore-campaign .bexplore_boxess .dolleres {
    position: relative;
    top: 55px;
    width: 71px;
    left: -221px;
}
		#main-carousel {
    height: 133px !important;
}
		.timer-hours div {
    background-color: #80808033;
    border-radius: 0px;
    margin: 0px 4px;
}
		.timer-hours p {
    margin: 0px;
    font-size: 11px !important;
    padding: 6px 10px 5px;
    line-height: normal;
}
.calendar_view {
    width: 100%;
    position: relative;
    top: 323px;
    left: 20px;
}
		.slide-product-box {
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		padding: 20px;
		margin-top: 40px;
	}
	.coupen-img h2 {
		font-size: 14px;
		font-weight: 600;
		color: #031b26;
		font-family: 'Open Sans', sans-serif;
		position: relative;
		left: 12px !important;
    top: 97px !important;
	}
	.product_img {
		border: 1px solid #e0e0e0;
		border-radius: 7px;
		margin-top: 0px;
		width: 126px;
		padding: 5px;
		margin-left: 97px;
		z-index: 9;
    position: relative;
	}
	.box .chart {
		position: relative;
		width: 100%;
		height: 100%;
		text-align: center;
		font-size: 12px;
		line-height: 108px;
		height: 160px;
		color: #300808;
		left: -16px;
	}
	.textes {
		font-size: 11px;
		position: relative;
		top: 9px;
		left: 4px !important;
		font-family: 'Open Sans', sans-serif;
		font-size: 15px;
		font-weight: 600;
		color: #031b26;
	}
	.share {
    content: "\f1e0";
    font-size: 15px;
    padding-left: 5px;
    color: #fff !important;
    margin-top: 41px;
    position: relative;
    top: 42px;
    right: 9px;
}
	.dolleres {
    position: relative;
    top: 66px;
    width: 71px;
    left: -267px;
}
	.align_leftes {
		position: relative;
		top: -460px;
	}
	.box .chart1 {
		position: relative;
		width: 100%;
		height: 100%;
		text-align: center;
		font-size: 12px;
		line-height: 108px;
		height: 160px;
		color: #300808;
		top: 49px;
    left: 26px;
	}
	.hearts {
		display: flex;
		flex-direction: column;
		align-items: end;
		margin-left: 284px;
		position: initial;
	}

	.text-align{
		text-align:center;

	}
	.explore-product-box {
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		padding: 20px;
		margin-top: 25px;
		height: 510px !important;
		margin-bottom: 20px;
	}
	.explore_boxes {
		width: 26%;
		position: absolute !important;
		top: -51px !important;
		left: 8% !important;
	}
	}
	.running{
		display: flex;
		    justify-content: end !important;
	}
	@media (min-width: 360px) and (max-width: 600px){
		.coming_soon_product {
    left: -11PX;
    color: white;
    transform: rotateZ(-39deg);
    text-align: center;
    margin-left: -15px;
    position: absolute;
    margin-top: 0px;
    font-weight: 700;
    border-bottom: 22px solid #d12a2b;
    border-left: 19px solid transparent;
    border-right: 26px solid transparent;
    height: 0;
    width: 125px;
    line-height: 18px;
    font-size: 11px;
}
	    .details .legend_product p {
    text-align: center;
    padding: 0px 0px 19px !important;
    width: 292px !important;
    float: left;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: auto;
	}
	 
	video{
		margin-top: 93px;
	}
		#closing-soon .owl-nav {
		position: absolute;
		right: 0;
		top: -20px;
		display: block;
	}
	#closing-soon .owl-nav span {
		border: 1px solid #e0e0e0;
		color: #181818;
		font-size: 30px;
		width: 37px;
		height: 29px;
		display: block;
		line-height: 20px;
		padding: 0 15px;
	}
	}
	.coming-soonhome p {
	font-family: 'Open Sans', sans-serif;
		font-size: 16px;
		font-weight: 600;
		text-align: center;
		color: #181818;
	}

	#carouselExample .slide-product-box .product-img {
		padding: 10px;
		width: 62%;
		height: 174px;
		overflow: hidden;
		margin-left: 35px;
		position: relative;
		position: absolute;
		top: 46px;
	}
	#carouselExample h2{
		color: #d12a2b !important;
	}
	#carouselExample .img{
		height: 126px;
	}
	#carouselExample .slide-product-box {
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		padding: 20px 20px 30px;
		margin-top: 40px;
	}
	#carouselExample{
		margin:0px 0px 40px 0px;
	}

	@media (min-width: 768px) {
		#main-carousel {
    height: 241px ;
}	
	.carousel-inner .active,
	.carousel-inner .active + .carousel-item,
	.carousel-inner .active + .carousel-item + .carousel-item,
	.carousel-inner .active + .carousel-item + .carousel-item + .carousel-item  {
		display: block;
	}

	.carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left),
	.carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left) + .carousel-item,
	.carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left) + .carousel-item + .carousel-item,
	.carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left) + .carousel-item + .carousel-item + .carousel-item {
		transition: none;
	}

	.carousel-inner .carousel-item-next,
	.carousel-inner .carousel-item-prev {
	position: relative;
	transform: translate3d(0, 0, 0);
	}

	.carousel-inner .active.carousel-item + .carousel-item + .carousel-item + .carousel-item + .carousel-item {
		position: absolute;
		top: 0;
		right: -25%;
		z-index: -1;
		display: block;
		visibility: visible;
	}
	.active.carousel-item-left + .carousel-item-next.carousel-item-left,
	.carousel-item-next.carousel-item-left + .carousel-item,
	.carousel-item-next.carousel-item-left + .carousel-item + .carousel-item,
	.carousel-item-next.carousel-item-left + .carousel-item + .carousel-item + .carousel-item,
	.carousel-item-next.carousel-item-left + .carousel-item + .carousel-item + .carousel-item + .carousel-item {
		position: relative;
		transform: translate3d(-100%, 0, 0);
		visibility: visible;
	}
	.carousel-inner .carousel-item-prev.carousel-item-right {
		position: absolute;
		top: 0;
		left: 0;
		z-index: -1;
		display: block;
		visibility: visible;
	}
	.active.carousel-item-right + .carousel-item-prev.carousel-item-right,
	.carousel-item-prev.carousel-item-right + .carousel-item,
	.carousel-item-prev.carousel-item-right + .carousel-item + .carousel-item,
	.carousel-item-prev.carousel-item-right + .carousel-item + .carousel-item + .carousel-item,
	.carousel-item-prev.carousel-item-right + .carousel-item + .carousel-item + .carousel-item + .carousel-item {
		position: relative;
		transform: translate3d(100%, 0, 0);
		visibility: visible;
		display: block;
		visibility: visible;
	}

	}

	#profile-grid { overflow: auto; white-space: normal; } 
	#profile-grid .profile { padding-bottom: 40px; }
	#profile-grid .panel { padding: 0 }
	#profile-grid .panel-body { padding: 10px }
	#profile-grid .profile-name { font-weight: bold; }
	#profile-grid .thumbnail {margin-bottom:6px;}
	#profile-grid .panel-thumbnail { overflow: hidden; }
	#profile-grid .img-rounded { border-radius: 4px 4px 0 0;}


	.explore-product-box .product-img {
		padding: 10px;
		width: 100%;
		height: 100px;
		overflow: hidden;
	}
	
	.modal-open .modal {
        overflow-x: hidden;
        overflow-y: auto;
        margin-top: 103px !important;
    }
	.modal-body{
	    text-align: center !important;
	    
	}
	
	img.show_prize_dolleres {
        width: 80%;
    }


@media (min-width: 360px) and (max-width: 600px) {
.table td:nth-child(1){
} 
.cart-table ul li {
    display: inline-block;
    margin-left: 0px;
}

.default-heading {
    font-size: 24px !important;
    width: 100%;
    line-height: 34px;
    margin-top: 10px;
    text-align: center;
    margin-bottom: 0px !important;
}

tr{
	display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    padding: 4px 10px;
}
.cart-table ul li a.remove-item {
        border: 1px solid #c1172d;
        color: #c1172d;
        padding: 7px 36px;
        font-family: 'Open Sans', sans-serif;
        font-size: 14px;
        border-radius: 8px;
        margin-top: 42px;
        position: relative;
        top: 10px;
    }
	
}
</style>
</head>
<body>
<?php include('common/header.php') ?>
<div class="outside-cart-block">
	<div class="container">
		<div class="row">
			<?php if (!empty($this->cart->contents())): ?>
				
			<div class="col-lg-9 col-md-9 col-12">
				<?php $totalAED=0; foreach ($this->cart->contents() as $items) { 
					$totalAED = $totalAED + (int)$items['qty'] * (int)$items['other']['aed'];
					?>
				<div class="user-cart">
					<div class="cart-box">
						<div class="table-responsive cart-table">
							<table class="table">
								<tbody>
									<tr>
										<td width="20%">
											<div class="cart-product">
												<?php if(file_get_contents(base_url().$items['other']['image'])): ?>
			                                        <img src="<?=base_url().$items['other']['image']?>" class="img-fluid" alt="cart-product">
			                                    <?php else: ?>
			                                        <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-fluid" alt="cart-product">
			                                    <?php endif; ?>
										</td>
										<td>
											<div class="table-hd">


												<h3><?=str_replace("___","'",$items['name'])?></h3>
												<h5><?=strip_tags($items['other']['description'])?></h5>
											</div>
											<ul>
												<li>
													<div class="number">
														<span data-id='<?=$items['other']['aed']?>___<?=$items['rowid']?>___D' class="minus qtyA">-</span>
														<input type="number" min="1" id="qty" name="qty" value="<?=$items['qty']?>" readonly>
														<span data-id='<?=$items['other']['aed']?>___<?=$items['rowid']?>___I' class="plus qtyA">+</span>
													</div>
												</li>
												<li>
													<a href="<?=base_url('remove-item/').$items['rowid']?>"><i class="fa fa-trash" aria-hidden="true" title="Delete"></i></a>
												</li>
											</ul>
										</td>
										<td>
											<div class="inquiry-and-buy">
												<?php $total = $items['other']['aed'] * $items['qty']	?>
												<p id="<?=$items['rowid']?>">AED <?=$total?>.00</p>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="donate-footer <?php if($items['is_donated'] == 'Y'){?> activess <?php } ?>" id="d<?=$items['rowid']?>">
							<div class="row">
								<div class="col-lg-9 col-md-9 col-9">
									<p>Exchange your product and double the raffle coupon</p>
								</div>
								<div class="col-lg-3 col-md-3 col-3">
									<div class="toggle" id="toggle" data-id="<?=$items['rowid']?>">
										<input type="radio" id="radio" value="on" id="r<?=$items['rowid']?>"  name="r<?=$items['rowid']?>">
										<input type="radio" value="off" id="r<?=$items['rowid']?>"  name="r<?=$items['rowid']?>" <?php if($items['is_donated'] == 'Y'){?> checked="checked" <?php } ?> >
										<div class="toggle__pointer"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="col-lg-3 col-md-3 col-12">
				<div class="coupen-box">
					<div class="table-responsive">
						<?php
							$inclusiveOfVat = 	$totalAED;
							$totalVat		=	($totalAED * 5/100);
							$subTotal 		=	($inclusiveOfVat-$totalVat);
						?>
						<table class="table">
							<tbody><tr>
								<td>Total Amount</td>	
								<td class="text-right" id="subTotal">AED <?php echo number_format($inclusiveOfVat,2); ?></td>
							</tr>
							<!-- <tr>
								<td>Subtotal</td>	
								<td class="text-right" id="subTotalAmt">AED <?php echo number_format($subTotal,2); ?></td>		
							</tr> -->
							<!-- <tr>
								<td>VAT</td>	
								<td class="text-right" id="vat">AED<?php echo number_format($totalVat,2); ?></td>
							</tr> -->
						</tbody></table>
					</div>
				</div>
				<div class="promo-code">
					<a href="<?=base_url('login')?>?referenceUrl=user-cart" class="color-default-btn float-right">Pay Now</a>
				</div>
			</div>

			<div class="col-sm-12 col-md-12 col-lg-12 ">

				<div class="related-product">
					<h3 class="default-heading">People have also bought this together</h3>
					<div class="row">
						<?php foreach ($products as $key => $value) { ?>
						<div class="col-lg-4 col-md-4 col-12">
							<a href="<?=base_url('product-details/').base64_encode($value['products_id'])?>">
							<div class="slide-product-box">
								<?php if(isset($value['soldout_status']) && $value['soldout_status'] == 'Y'){ ?> 
								<div class="coupen-img">
									<div class="">
									<?php
								    $avlstock = $value['stock'] * 100 / $value['totalStock'];
								    $remStick = 100 - $avlstock;
									?>
								    <div class="box">
                                        <div class="chart1" data-percent="<?=number_format($remStick,0)?>" ><?=number_format($remStick,0)?>%</div>
                                    </div>
								</div>
								<h2 class="textes">Sold</h2>
								</div>
								<?php }else{ ?>
									<div class="coupen-img">
										<div class="">
											<div class="box">
	                                       	<div class="chartdemo easyPieChart" style="width: 70px; height: 70px; line-height: 70px;"></div>
	                                    	</div>
										</div>
										<h2 class="textes">&nbsp;</h2>
									</div>
								<?php } ?>
								<div class="product-img">

									<?php if(file_get_contents(base_url().$value['product_image'])): ?>
                                        <img src="<?=base_url().$value['product_image'];?>" class="img-fluid">
                                    <?php else: ?>
                                        <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-fluid">
                                    <?php endif; ?>
								</div>
								<div class="product-des">
									<p>Get a chance to win:</p>
									<h4><?=str_replace("/","",$value['title'])?> </h4>
									<h5>AED <?=number_format($value['adepoints'], 2)?></h5>
								
								</div>
							</div>
							</a>
						</div>
						<?php } ?>
					</div>
				</div>
				
			</div>

			<?php else: ?>
				<div class="col-lg-12 col-md-12 col-12">

					<div class="empty-cart-section text-center">
						<img src="<?=base_url('assets/img/cart@2x.png')?>" class="empty-shopping-cart" alt="cartusers">
						<p class=" empty-cart-heading text-center">Your cart is empty</p>
						<a href="<?=base_url('/');?>" class="home-redirect-link">Start shopping</a>
					</div>
				</div>
			<?php endif ?>

		</div>
	</div>
</div>
<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>
<script>
	$(document).ready(function() {
	$("#mobile").inputFilter(function(value) {
	return /^\d*$/.test(value);   
	});

	$("#name").inputFilter(function(value) {
	return /^[a-zA-Z-'. ]*$/.test(value);    
	});
	$("#message").inputFilter(function(value) {
	return /^[a-zA-Z-'.@,()/^\d ]*$/.test(value);    
	});
	});
</script>
<script>

	var s = $("#sticker");
	var pos = s.position();
	$(window).scroll(function() {
		var windowpos = $(window).scrollTop();
		if(windowpos > pos.top) {
			s.addClass("stick");
		} else {
			s.removeClass("stick");
		}
	});
</script>
<script>
	$('.minus').click(function () {
		var $input = $(this).parent().find('input');
		var count = parseInt($input.val()) - 1;
		count = count < 1 ? 1 : count;
		$input.val(count);
		$input.change();
		return false;
	});
	$('.plus').click(function () {
		var $input = $(this).parent().find('input');
		$input.val(parseInt($input.val()) + 1);
		$input.change();
		return false;
	});
</script>

<script>
	$('.dropdown > .caption').on('click', function() {
		$(this).parent().toggleClass('open');
	});
	
	$('.dropdown > .list > .item').on('click', function() {
		$('.dropdown > .list > .item').removeClass('selected');
		$(this).addClass('selected').parent().parent().removeClass('open').children('.caption').html($(this).html());
		if($(this).data("item") == "RUB") {
			console.log('RUB');
		} else if($(this).data("item") == "UAH") {
			console.log('UAH');
		} else {
			console.log('USD');
		}
	});
	$(document).on('keyup', function(evt) {
		if((evt.keyCode || evt.which) === 27) {
			$('.dropdown').removeClass('open');
		}
	});
	$(document).on('click', function(evt) {
		if($(evt.target).closest(".dropdown > .caption").length === 0) {
			$('.dropdown').removeClass('open');
		}
	});
</script>
<script>
var boxes = document.querySelectorAll('input[type="radio"]');
boxes = Array.prototype.slice.call(boxes);

	boxes.forEach(function(box) {
	  box.addEventListener('change', function(e) {
	  });
	});
</script>
<script>
	$(document).ready(function(){
		$('.qtyA').click(function(){
			var data 	= $(this).data('id');
			var ur = '<?=base_url()?>';
			var totalID = data.split("___");
			$.ajax({
				url : ur+ "shopping_cart/addqty",
				method: "POST", 
				data: {data: data},
				success: function(data1){
					//alert(data1)
					var totalVAL = data1.split("__");
					var A = 'AED'+totalVAL[0]
					var B = 'AED'+totalVAL[1]
					var C = 'AED'+totalVAL[2]
					var D = 'AED'+totalVAL[3]
					//alert(VAT);
					$('#'+totalID[1]).empty().append(A);
					$('#subTotal').empty().append(B);
					$('#vat').empty().append(C);
					$('#subTotalAmt').empty().append(D);
		
				}
			});
		});
	});
</script>
<script>
	$('.toggle').on('click', function() {
	  	var rowid = $(this).data('id');
	  	var id = '#r'+rowid;
	  	var divID = '#d'+rowid;
	  	var ur = "<?=base_url()?>";
	  	if ($(id).prop("checked")) {
	   		var donate = 'Y';
		}else{
			var donate = 'N';
		}
		$.ajax({
			url : ur+ "shopping_cart/donate",
			method: "POST", 
			data: {rowid: rowid, donate:donate},
			success: function(resdata){
				if(resdata === 'Y'){
					$(divID).addClass("activess");
				}
				if(resdata === 'N'){
					$(divID).removeClass("activess");
				}
			}
		}); 	 
	});
</script>
<script type="text/javascript">
  $(function() {
	  $('.chart').easyPieChart({
	    size: 70,
	    barColor: "#c02728",
	    
	    lineWidth: 6,
	    trackColor: "#98fb987d",
	    lineCap: "circle",
	    animate: 2000,
	  });
	});
	$(function() {
	  $('.chart1').easyPieChart({
	    size: 70,
	    barColor: "#c02728",
	    
	    lineWidth: 6,
	    trackColor: "#98fb987d",
	    lineCap: "circle",
	    animate: 2000,
		backgroundColor: ['#fff']
	  });
	});
</script>
</body>
</html>
