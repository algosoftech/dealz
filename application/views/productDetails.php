<!Doctype html>
<html lang="eng">

<head>
    <!-- Basic page needs -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
    <style>
        .details .border {
    border-radius: 10px;
    border: 1px solid rgb(209 209 209);
    overflow: hidden;
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
    .fa-share:hover {
        color: #d12a2b !important;
    }
    .share i:hover {
        color: #d12a2b !important;
    }
    .fa-heart:hover {
        content: "\f004";
        font-weight: 800 !important;
    }
    .hearts a:hover {
        color: #d12a2b !important;
    }
    .heart_icon {
        color: rgb(197 24 24) !important;
        font-size: 26px !important;
    }
    .fa-heart:hover {
        color: #d12a2b !important;
    }
    .details .legend_product {
        text-align: center;
        padding: 0px 19px 19px;
        width: 330px !important;
        float: left;
    }
    .default-heading {
        font-family: Gadugi Bold;
        text-align: left;
        font-size: 23px;
        color: #031b26;
    }
    .details {
        margin-bottom: 16px;
    }
    .active {
        content: "\f004";
        font-weight: 800 !important;
        color: #d12a2b !important;
    }
    .fa-share:before {
        content: "\f064";
        font-family: "Font Awesome 5 Pro";
    }
    .share {
        content: "\f1e0";
        color: rgb(209 209 209 / 98%);
        font-size: 18px;
        padding-left: 5px;
    }
    .prize_box {
        padding: 6px 18px;
        border-radius: 9px;
        border: 1px solid rgb(209 209 209);
    }
    .prize .prize_box h3 {
        font-family: 'Open Sans', sans-serif;
        text-align: left;
        font-size: 15px;
        color: #031b26;
        text-align: center !important;
        margin: 0px;
        margin-bottom: 2px;
        font-weight: 600;
    }
    .prize .prize_box p {
        font-family: 'Open Sans', sans-serif;
        font-weight: 600;
        font-size: 14px !important;
        color: #1e1e1e !important;
        margin: 0px;
        text-align: center;
    }
    .details .prize .default-heading {
        font-family: 'Open Sans', sans-serif;
        text-align: left;
        font-size: 22px;
        color: #031b26;
        font-weight: 700;
    }
    .box {
        width: 8%;
        position: relative;
        top: -22px;
        left: -19px;
    }
    .fa-share:hover {
        color: #d12a2b !important;
    }
    .fa-share:hover {
        color: #d12a2b !important;
    }
    .share i:hover {
        color: #d12a2b !important;
    }
    .fa-share:hover {
        color: #d12a2b !important;
    }
    .share i:hover {
        color: #d12a2b !important;
    }
    .fa-heart:hover {
        color: #d12a2b !important;
    }
    .box {
        width: 8%;
        position: relative;
        top: -30px;
    }
    .box h2 {
        display: block;
        text-align: center;
        color: black;
        position: relative;
        top: -96px;
        left: 13px;

    }
  
    .hearts {
        float: left;
        width: 20px;
        padding-top: 15px;
        position: relative;
        left: -20px;
    }
    .textes {
        position: relative;
        top: -36%;
        left: -5px;
        font-family: 'Open Sans', sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: #031b26;
        left: 12px;
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
        top: 13px;
        left: 6px;
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
        top: 18px;
    }
    .box canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        width: 100%;
    }
    .details .legend_product {
        text-align: center;
        padding: 0px 19px 19px;
        width: 378px;
        float: left;
    }
    .details .cart_1 {
        background: #e72d2e00;
        border: 2px solid #e72d2e;
        padding: 6px 8px !important;
        border-radius: 8px;
        color: #212529e6;
        font-family: 'Open Sans', sans-serif;
        font-size: 13px;
        font-weight: 400;
        margin-right: 10px !important;
    }
    .details .date_item3 {
        margin: 0px;
        margin: 0px;
        font-family: 'Open Sans', sans-serif;
        font-size: 13px;
        font-weight: 400;
        margin-top: 30px;
        text-align: center;
    }
    .details .cart_2:hover {
        transition: 0.3s;
        background: #e72d2e;
        color: #fff;
    }
    .details .edit_cart {
        background: #c42828 !important;
        border: 1px solid #ec2d2f !important;
        padding: 6px 25px;
        border-radius: 8px;
        color: #fff !important;
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
    .details .cart_2 {
        background: #e72d2e00;
        border: 2px solid #e72d2e;
        padding: 6px 8px !important;
        border-radius: 8px;
        color: #212529e6;
        font-family: 'Open Sans', sans-serif;
        font-size: 13px;
        font-weight: 400;
        margin-right: 10px !important;
    }
    .pencil {
        max-width: 100%;
        max-height: 100%;
        padding-top: 34px;
        padding-bottom: 22px;
    }
    .details .vl {
    border-left: 1px solid rgb(209 209 209);
    height: 100% !important;
    position: relative;
    top: 0px !important;
    left: 25px !important;
    }
    .details .legend_product h2 {
        margin: 0px;
        margin: 0px;
        font-family: 'Open Sans', sans-serif;
        font-weight: 700;
        font-size: 16px;
        max-width: 290px;
        margin: auto;
        text-align: center;
    }
    .details .vl_img {
        position: absolute;
        top: 46%;
        left: 44%;
    }
    .share {
        content: "\f1e0";
        color: rgb(209 209 209 / 53%);
        font-size: 18px;
        padding-left: 5px;
    }
    .details .fa-share-alt:before {
        content: "\f1e0";
        padding-right: 0px;
        font-size: 18px;
    }
    /*.details .vl {*/
    /*    border-left: 1px solid rgb(209 209 209);*/
    /*    height: 362px !important;*/
    /*    position: relative;*/
    /*    top: 19px !important;*/
    /*    left: 25px !important;*/
    /*}*/
    .edit_cart {
        background: #c42828;
        border: 1px solid #ec2d2f;
        padding: 6px 25px;
        border-radius: 8px;
        color: #fff;
        font-family: 'Open Sans', sans-serif;
        font-size: 15px;
        font-weight: 400;
        margin-top: 10px;
        width: 100%;
    }
    .details .legend_product p {
        text-align: center;
        padding: 0px 4px 1px;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .edit_cart:hover {
        background: #ffffff;
        color: #e70909;
    }
    .details .prize .prize_box p {
        font-family: 'Open Sans', sans-serif;
        font-weight: 600;
        font-size: 14px !important;
        color: #1e1e1e !important;
        margin: 0px;
        text-align: center;

    }
    .white-btn:hover {
        background: #d12a2b !important;
        color: #fff !important;
    }
    .white-btn {
        padding: 6px 15px;
        border: 1px solid #e0e0e0 !important;
        background: #fff !important;
        color: #363636;
        border-radius: 8px;
        color: #363636;
        font-family: 'Open Sans', sans-serif;
        font-size: 15px;
        font-weight: 400;
    }
    .default-btn {
        background: #e72d2e00 !important;
        border: 1px solid #d12a2b !important;
        padding: 4px 25px !important;
        border-radius: 8px;
        color: #d12a2b !important;
        font-family: 'Open Sans', sans-serif;
        font-size: 15px;
        font-weight: 400;
    }
    .default-btn:hover {
        background: #d12a2b !important;
        color: #fff !important;
    }
    .fa-heart.active {
        color: #d12a2b !important;
    }
    .sociallinks ul {
        list-style-type: none;
    }
    .sociallinks ul li {
        cursor: pointer;
    }
    .legend_product p img {
        width: 9% !important;
    }
    .details .legend_product p {
        border-radius: 8px;
        color: #020e19e6;
        font-size: 13px;
        font-weight: 700;
        font-family: 'Open Sans', sans-serif;
        padding-top: 5px;
    }
    @media (min-width: 360px) and (max-width: 600px) {
        .details .legend_product img {
            height:100%;
            max-width: 100% !important;
        }
        .pencil {
    max-width: 100%;
    max-height: 100%;
    padding-top: 0px;
    padding-bottom: 0px;
    }
            .select_size {
    display: contents !important;
        list-style: none;
        padding: 0px 1importnat;
        justify-content: left;
        margin-top: 1;
        margin: 11px 0px;
    }
            .image_container {
        width: 100%;
        height: 162px !important;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0px 0px;
        margin: 10px 0px 0px;
    }
    .legend_product1{
    margin-bottom:15px;
    }
        .box {
            width: 23% !important;
            position: relative;
            top: 0px !important;
            left: -55% !important;
        }
        .details .vl_img {
    position: absolute;
    top: 38% !important;
    left: 44% !important;
    height: 178px;
    }
        .details .textes {
          position: relative;
          top: 18px;
    left: -3px !important;
    font-family: 'Open Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #031b26;
        }
        .details .legend_product p {
            text-align: center;
            padding: 0px 19px 19px;
            width: 292px !important;
            float: left;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: auto;
        }
        .details .legend_product {
    text-align: center;
    padding: 0px 19px 19px;
    width: 378px;
    float: left;
    margin: 23px 0px;
    height: 312px;
    }
        .details .legend_product h2 {
    margin: 0px;
    margin: 0px;
    font-family: 'Open Sans', sans-serif;
    font-weight: 700;
    font-size: 16px;
    max-width: 290px;
    margin: auto;
    text-align: center;
    margin-top: 25px;
    }
        .hearts {
            float: left;
            width: 20px;
            padding-top: 15px;
            position: relative;
            left: 142px !important;
            top: -315px !important;
        }
        .border {
            border: 1px solid #dee2e6 !important;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
    }
    .image_container {
        width: 100%;
        height: 292px;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0px 0px;
        margin: 10px 0px 0px;
    }
    /* .image_container img {
        width: 400px;
        display: block;
        background-color: var(--color);
    } */

    .colors {
        display: flex;
        justify-content: left;
        align-items: center;
        width: 100%;
        height: 32px;
        margin: 10px 0px;
    }

    .colors ul li {
        width: 30px;
        height: 30px;
        display: inline-block;
        cursor: pointer;

    }

    .color_para_one p {
        font-family: 'Open Sans', sans-serif;
        font-weight: 700;
        font-size: 16px;
        text-align: left !important;

    }

    .color_para h2 {
        font-family: 'Open Sans', sans-serif;
        font-weight: 700;
        font-size: 16px;
        max-width: 290px;
        margin: auto;
        text-align: left;
        margin: 20px 0px 10px;
    }

    .color_para_one h2 {
        font-family: 'Open Sans', sans-serif;
        font-weight: 700;
        font-size: 16px;
        max-width: 290px;
        margin: auto;
        text-align: left;
        margin: 10px 0px 10px;
    }

    .radio {
        display: none;
    }

    .select_size {
        display: flex;
        list-style: none;
        padding: 0px;
        justify-content: left;
        margin-top: 1;
        margin: 11px 0px;
    }

    .select_size li {
        padding: 0px 8px;
        border: 1px solid #80808070 !important;
        margin: 0px 5px;
        border-radius: 4px;
        height: 40px;
        text-align: center;
    }

    .small {
        font-family: 'Open Sans', sans-serif;
        font-weight: 700;
        font-size: 16px;
        margin: auto;
        margin-top: 7px;
        right: 15px;
    }

    .radio:focus-visible {
        outline: -webkit-focus-ring-color auto 0px !important;
    }

    .select_size a {
        padding: 0px 8px;
        border: 1px solid #80808070 !important;
        margin: 0px 5px;
        border-radius: 4px;
        height: 40px;
        text-align: center;
    }
    .size_active {
        border: 2px solid #e0e0e03b!important;
       background-color: rgb(220 43 44);
    }
     .size_active  .small{
        color:#FFF;
    }
    .hidden {
        display: none;
    }
    .show {
        display: block;
    }
    </style>
</head>

<body>
    <?php include('common/header.php') ?>
    <!-- details-->
    <div class="details">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="row  border">
                        <div class="legend_product">
                            <div class="container">

                                <div class="image_container">
                                    <?php if(count($products['color_size_details']) != 0):   ?>
                                        <img id="productImage" src="<?=base_url().$products['color_size_details'][0]->image?>" alt="product_img" style="width: 65%;">
                                        <?php if($products['color_size_details'][0]->color) : ?>
                                            <input type='hidden' name="fcolor" id="fcolor" value="<?=$products['color_size_details'][0]->color?>"/>
                                        <?php endif; ?>
                                        <?php if($products['color_size_details'][0]->S == 'Y') : ?>
                                            <input type='hidden' name="fsize" id="fsize" value="S"/>
                                        <?php elseif($products['color_size_details'][0]->M == 'Y'): ?>
                                            <input type='hidden' name="fsize" id="fsize" value="M"/>
                                        <?php elseif($products['color_size_details'][0]->L == 'Y'): ?>
                                            <input type='hidden' name="fsize" id="fsize" value="L"/>
                                        <?php elseif($products['color_size_details'][0]->XL == 'Y'): ?>
                                            <input type='hidden' name="fsize" id="fsize" value="XL"/>
                                        <?php elseif($products['color_size_details'][0]->XXL == 'Y'): ?>
                                            <input type='hidden' name="fsize" id="fsize" value="XXL"/>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <img src="<?=base_url().$products['product_image']?>" alt="" style="width: 65%;">
                                    <?php endif; ?>
                                </div>
                                <!-- Color Option -->
                                <div class="color_para_one">
                                    <h2>Buy <br> <?=stripslashes($products['title'])?></h2>
                                    <p style="text-align:center;"><?=stripslashes(stripslashes($products['description']))?></p>
                                    <?php if(count($products['color_size_details']) != 0):   ?>
                                    <p style="margin:0px;">Select color:</p>
                                    <?php endif; ?>
                                </div>
                                <?php if(count($products['color_size_details']) != 0):   ?>
                                <div class="colors">
                                    <ul style="padding:0px;margin:0px;">
                                        <?php $i=0; foreach ($products['color_size_details'] as $key => $value): ?>
                                        <li class="colorCode" data-color="<?=$value->color?>" data-img="<?=base_url().$value->image?>" data-count="<?=count($products['color_size_details'])?>" data-size="<?=$i?>" style="background-color: <?=$value->color?>; <?php if($i == 0): ?> border: 3px solid black; <?php endif; ?>" data-firstsize="<?php if($value->S == 'Y'): ?>S<?php elseif($value->M == 'Y'): ?>M<?php elseif($value->L == 'Y'): ?>L<?php elseif($value->XL == 'Y'): ?>XL<?php elseif($value->XXL == 'Y'): ?>XXL<?php endif; ?>"
                                            data-html="<p style='margin:0px;'> Select Size</p>
                                            <ul class='select_size' id='colorSize<?=$i?>'>
                                                <?php $f=0; if($value->S == 'Y') : ?>
                                                <a onclick='S()' class='S <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='S' id='size<?=$i?>' >
                                                <label for='cat' class='small'>S</label>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($value->M == 'Y') : ?>
                                                <a onclick='M()' class='M <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='M' id='size<?=$i?>' >
                                                <label for='cat' class='small'>M</label>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($value->L == 'Y') : ?>
                                                <a onclick='L()' class='L <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='L' id='size<?=$i?>' >
                                                <label for='cat' class='small'>L</label>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($value->XL == 'Y') : ?>
                                                <a onclick='XL()' class='XL <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='XL' id='size<?=$i?>' >
                                                <label for='cat' class='small'>XL</label>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($value->XXL == 'Y') : ?>
                                                <a onclick='XXL()' class='XXL <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='XXL' id='size<?=$i?>' >
                                                <label for='cat' class='small'>XXL</label>
                                                </a>
                                                <?php endif; ?>
                                                <?php if($value->FRS == 'Y') : ?>
                                                <a onclick='FRS()' class='FRS <?php if($f == 0): ?>size_active<?php $f++; endif; ?>' data-firstSize='FRS' id='size<?=$i?>' >
                                                <label for='cat' class='small'>Free Size</label>
                                                </a>
                                                <?php endif; ?>
                                            </ul>
                                            "
                                            ></li>
                                        <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <!-- Size Option -->
                                <div class="color_para_one colorsize">
                                    <?php if($products['color_size_details'][0]->S == 'Y' || $products['color_size_details'][0]->M == 'Y' || $products['color_size_details'][0]->L == 'Y' || $products['color_size_details'][0]->XL == 'Y' || $products['color_size_details'][0]->XXL == 'Y') : ?>
                                        <p style="margin:0px;"> Select Size</p>
                                    <?php endif; ?>
                                    <?php $i=0; $j=0; $f=0;  $f=0; ?>
                                        <ul class="select_size <?php if($j != 0): ?> hidden <?php endif; ?>" id="colorSize<?=$j?>" >
                                            <?php if($products['color_size_details'][0]->S == 'Y') : ?>
                                            <a onclick="S()" class="S <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="S" id="S" >
                                                <label for="cat" class="small">S</label>
                                            </a>
                                            <?php endif; ?>
                                            <?php if($products['color_size_details'][0]->M == 'Y') : ?>
                                            <a onclick="M()" class="M <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="M" id="M">
                                                <label for="cat" class="small">M</label>
                                            </a>
                                            <?php endif; ?>
                                            <?php if($products['color_size_details'][0]->L == 'Y') : ?>
                                            <a onclick="L()" class="L <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="L" id="L">
                                                <label for="cat" class="small">L</label>
                                            </a>
                                            <?php endif; ?>
                                            <?php if($products['color_size_details'][0]->XL == 'Y') : ?>
                                            <a onclick="XL()" class="XL <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="XL" id="XL">
                                                <label for="cat" class="small">XL</label>
                                            </a>
                                            <?php endif; ?>
                                            <?php if($products['color_size_details'][0]->XXL == 'Y') : ?>
                                            <a onclick="XXL()" class="XXL <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="XXL" id="XXL">
                                                <label for="cat" class="small">XXL</label>
                                            </a>
                                            <?php endif; ?>
                                            <?php if($products['color_size_details'][0]->FRS == 'Y') : ?>
                                            <a onclick="FRS()" class="FRS <?php if($f == 0): ?> size_active <?php $f++; endif; ?>" data-firstSize="FRS" id="FRS">
                                                <label for="cat" class="small">Free Size</label>
                                            </a>
                                            <?php endif; ?>

                                        </ul>
                                    <?php $j++;  ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if(isset($products['soldout_status']) && $products['soldout_status'] == 'Y'){ ?> 
                        <div class="legend_product1">
                            <div class="vl"></div>
                            <div class="vl_img">
                                <?php
                                $avlstock = $products['stock'] * 100 / $products['totalStock'];
                                
                                if($products['sale_percentage']):
                                    $remStick = $products['sale_percentage'];
                                else:
                                   $remStick = 100 - $avlstock;
                                endif;
                                ?>
                                <div class="box">
                                    <div class="chart" data-percent="<?=number_format($remStick,0)?>" ><?=number_format($remStick,0)?>%</div>
                                </div>
                            </div>
                            <h2 class="textes" style="left:12px;">Sold</h2>
                        </div>
                        <?php }?>
                        <div class="legend_product">
                        <div class="image_container">
                            <?php if(!empty($prize['prize_image'])){ ?>
                            <img src="<?=base_url().@$prize['prize_image']?>" class="pencil" alt="prize_img">
                            <?php }else{ ?>
                            <img src="<?=base_url().'/assets/img/NO_IMAGE.jpg'?>" class="pencil" alt="user_product_img">
                            <?php } ?>
                            </div>
                            <h2>Get a chance to win<br> <?=stripslashes($prize['title'])?></h2>
                            <p style="text-align:center;"><?=stripslashes(stripslashes($prize['description']))?></p>
                        </div>
                        <div class="hearts">
                            <!-- <a href="javascript:void();" name="wishlist" data-id="<?=$products['products_id']?>"
                                class="heart_icon">
                                <?php if($prodData):if($prodData['wishlist_product'] <> 'Y'):?>
                                <i class="far fa-heart heart_icon" title="Mark Favourite"></i>
                                <?php else:?>
                                <i class="far fa-heart heart_icon active" title="Remove from Favourite"></i>
                                <?php endif;?>
                                <?php else:?>
                                <i class="far fa-heart heart_icon" title="Mark Favourite"></i>
                                <?php endif;?>
                            </a> -->
                            <?php 
                            if($this->session->userdata('DZL_USERID')):
                               if($this->session->userdata('DZL_USERSTYPE') == 'Users' && $this->session->userdata('DZL_USERS_REFERRAL_CODE')):
                                $productShareUrl  = generateProductShareUrl($products['products_id'],$this->session->userdata('DZL_USERID'),$this->session->userdata('DZL_USERS_REFERRAL_CODE'));
                            ?>
                            <a href="javascript:void(0);" class="share" data-toggle="modal" data-target="#shareModal"><i
                                    class="far fa-share" title="Share Campaigns"></i></a>
                            <!-- Modal -->
                            <div class="modal fade" id="shareModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true"
                                style="z-index: 9999;margin-top: 10%;">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                <?php echo lang('SHARE_POPUP_HEADING'); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="sociallinks">
                                                <ul>
                                                    <li class="social-share facebook"><i class="fab fa-facebook-f"
                                                            aria-hidden="true"></i><span
                                                            style="padding-left:10px;">Share on Facebook</span></li>
                                                    <li class="social-share twitter"><i class="fab fa-twitter"
                                                            aria-hidden="true"></i><span
                                                            style="padding-left:10px;">Share on Twitter</span></li>
                                                    <li class="social-share linkedin"><i class="fab fa-linkedin"
                                                            aria-hidden="true"></i><span
                                                            style="padding-left:10px;">Share on LinkedIn</span></li>
                                                    <li class="social-share google"><i class="fab fa-google"></i><span
                                                            style="padding-left:10px;">Share on Google</span></li>
                                                    <li class="social-share whatsapp"><i
                                                            class="fab fa-whatsapp"></i><span
                                                            style="padding-left:10px;">Share on Whatsapp</span></li>
                                                </ul>
                                            </div>
                                            <input type="hidden" id="copyShareInput" class="copyShareUrlInput"
                                                value="<?php echo $productShareUrl; ?>" style="width:100%">
                                            <?php /* ?>
                                            <input type="text" id="copyShareInput" class="copyShareUrlInput"
                                                value="<?php echo $productShareUrl; ?>" style="width:100%">
                                            <br>
                                            <button class="copyShareUralToClipBoard">Copy url</button>
                                            <?php */ ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <a href="javascript:void(0);" class="share"><i class="far fa-share"
                                    title="Share Campaigns"></i></a>
                            <?php endif; ?>
                            <?php else: ?>
                            <a href="javascript:void(0);" class="share userLoginError"><i class="fas fa-share"
                                    title="Share Campaigns"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-8">



                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="product_points">
                        <div class="date_item">
                            <h2>Maximum draw Date :
                                <?=isset($products['draw_date'])?date('d M, Y', strtotime($products['draw_date'])):''?>
                            </h2>
                            <div class="countdown"
                                data-Date='<?=date('Y-m-d H:i:s', strtotime($products['validuptodate'].' '.$products['validuptotime']))?>'>
                                <div class="running">
                                    <?php if($products['is_show_closing'] == 'Show'){ ?> 
                                    <timer>
                                        DRAW IN <span class="days"></span>:<span class="hours"></span>:<span
                                            class="minutes"></span>:<span class="seconds"></span>
                                    </timer>
                                    <?php } ?>
                                </div>
                            </div>
                            <p>or when the campaign is sold out, Whichever is earlier</p>
                        </div>
                        <div class="date_item1">
                            <div class="items_2">
                                <h2>Price</h2>
                                <p>Inclusive Of VAT</p>
                            </div>
                            <div class="items_2">
                                <h3>AED<?=@number_format($products['adepoints'],2)?></h3>
                            </div>
                        </div>

                        <?php
if($this->session->userdata('DZL_USERID')){
    $wcon1['where'] = [ 
        'user_id'   =>  (int)$this->session->userdata('DZL_USERID'),
        'id' => (int)$products['products_id'] 
    ];
    $check = $this->geneal_model->getData2('count','da_cartItems',$wcon1);
    if($check == 0){ ?>
                    <?php if($products['commingSoon'] == 'N'){ ?> 
                        <button class="default-btn addremove add_cart" data-id='<?=$products['products_id']?>'
                            id="add_cart">Add To Cart</button>
                    <?php } ?>
        <?php }else{ ?>
            <?php if($products['commingSoon'] == 'N'){ ?> 
            <button class=" addremove edit_cart" data-id='<?=$products['products_id']?>' id="add_cart">Added
                To Cart</button>
                <?php } ?>
            <?php }

}else{ 
    if(!empty($this->cart->contents())){
        $check = $this->geneal_model->checkCartItems($products['products_id']); 
    }else{
        $check = 0;
    }
    if($check == 1){ ?>
                    <?php if($products['commingSoon'] == 'N'){ ?> 
                        <button class=" addremove edit_cart" data-id='<?=$products['products_id']?>' id="add_cart">Added
                            To Cart</button>
                    <?php } ?>
                        <?php }else{ ?>
                    <?php if($products['commingSoon'] == 'N'){ ?> 
                        <button class="default-btn  addremove add_cart" data-id='<?=$products['products_id']?>'
                            id="add_cart">Add To Cart</button>
                    <?php } ?>
                        <?php } }   ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php if(!empty($prize['prize1']) | !empty($prize['prize2']) | !empty($prize['prize3'])){ ?>


    <div class="prize">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12 px-0">
                    <h3 class="default-heading">Prize Detail<br>For First Three Winners.</h3>
                </div>
                <?php if(!empty($prize['prize1'])){ ?>
                <div class="col-lg-3 col-12">
                    <div class="prize_box">
                        <h3 class="default-heading">Prize 1</h3>
                        <p>AED <?=@number_format($prize['prize1'],2)?> Cash</p>
                    </div>
                </div>
                <?php } ?>
                <?php if(!empty($prize['prize2'])){ ?>
                <div class="col-lg-3 col-12">
                    <div class="prize_box">
                        <h3 class="default-heading">Prize 2</h3>
                        <p>AED<?=@number_format($prize['prize2'],2)?> Cash</p>
                    </div>
                </div>
                <?php } ?>
                <?php if(!empty($prize['prize3'])){ ?>
                <div class="col-lg-3 col-12">
                    <div class="prize_box">
                        <h3 class="default-heading">Prize 3</h3>
                        <p>AED<?=@number_format($prize['prize3'],2)?> Cash</p>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php }?>

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
    <script>
        $(document).ready(function(){
            $('.colorCode').click(function(){
                var size = $(this).data('size');
                var img = $(this).data('img');
                var count = $(this).data('count');
                var fsize = $(this).data('firstsize');
                var fcolor = $(this).data('color');
                var ur = '<?=base_url()?>'
                var size_html = $(this).data('html');
                
                $('.colorsize').empty().html(size_html);
                
                $('#productImage').attr('src', img);
                $('#fcolor').val('');
                $('#fcolor').val(fcolor);
                $('#fsize').val();
                $('#fsize').val(fsize)

            });
            
        });

        function S(){
            $('#fsize').val();
            $('#fsize').val('S')
            $('.S').addClass('size_active');
            $('.M').removeClass('size_active');
            $('.L').removeClass('size_active');
            $('.XL').removeClass('size_active');
            $('.XXL').removeClass('size_active');
            $('.FRS').removeClass('size_active');
            
        }

        function M(){
            $('#fsize').val();
            $('#fsize').val('M')
            $('.S').removeClass('size_active');
            $('.M').addClass('size_active');
            $('.L').removeClass('size_active');
            $('.XL').removeClass('size_active');
            $('.XXL').removeClass('size_active');
            $('.FRS').removeClass('size_active');

        }

        function L(){
            $('#fsize').val();
            $('#fsize').val('L')
            $('.S').removeClass('size_active');
            $('.M').removeClass('size_active');
            $('.L').addClass('size_active');
            $('.XL').removeClass('size_active');
            $('.XXL').removeClass('size_active');
            $('.FRS').removeClass('size_active');

        }

        function XL(){
            $('#fsize').val();
            $('#fsize').val('XL')
            $('.S').removeClass('size_active');
            $('.M').removeClass('size_active');
            $('.L').removeClass('size_active');
            $('.XL').addClass('size_active');
            $('.XXL').removeClass('size_active');
            $('.FRS').removeClass('size_active');

        }

        function XXL(){
            $('#fsize').val();
            $('#fsize').val('XXL')
            $('.S').removeClass('size_active');
            $('.M').removeClass('size_active');
            $('.L').removeClass('size_active');
            $('.XL').removeClass('size_active');
            $('.XXL').addClass('size_active');
            $('.FRS').removeClass('size_active');
        }

        function FRS(){
            $('#fsize').val();
            $('#fsize').val('FRS')
            $('.S').removeClass('size_active');
            $('.M').removeClass('size_active');
            $('.L').removeClass('size_active');
            $('.XL').removeClass('size_active');
            $('.XXL').removeClass('size_active');
            $('.FRS').addClass('size_active');
        }
    </script>
    <script>
    $(document).ready(function() {
        $(document).on("click", "button[id='add_cart']", function() {
            var product_id = $(this).attr('data-id');
            var color = $('#fcolor').val();
            var size = $('#fsize').val();
            var curobj = $(this);
            curobj.html("Added To Cart");

            curobj.addClass('edit_cart');
            curobj.removeClass('add_cart');

            var ur = '<?=base_url()?>';
            $.ajax({
                url: ur + "shopping_cart/add",
                method: "POST",
                data: {
                    product_id: product_id,
                    fcolor : color,
                    fsize : size
                },
                success: function(data) {
                    // alert()
                    var A = '<b><i class="fas fa-shopping-cart"></i>(' + data + ')</b>'
                    $('#cart-footer').empty().append('('+data+')');
                    $('#cartA').empty().append(A)
                }
            });
        });

    });
    </script>
    <!-- Animation Js -->
    <script>
    AOS.init({
        duration: 1200,
    })
    </script>
    <script type="text/javascript">
    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    document.getElementById("defaultOpen").click();
    </script>
    <script>
    /* TOP Menu Stick
    --------------------- */
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
    <!-- Main Slider Js -->
    <script>
    jQuery("#main-carousel").owlCarousel({
        autoplay: true,
        loop: true,
        margin: 0,
        transitionStyle: "goDown",
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        lazyLoad: false,
        nav: false,
        dots: true,
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
    <!-- Home Closing Soon Slider -->
    <script>
    jQuery("#closing-soon").owlCarousel({
        autoplay: true,
        lazyLoad: true,
        loop: true,
        margin: 20,
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        dots: false,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1024: {
                items: 4
            },
            1366: {
                items: 4
            }
        }
    });
    </script>
    <!-- Home Sold Out Slider -->
    <script>
    jQuery("#sold-out").owlCarousel({
        autoplay: true,
        lazyLoad: true,
        loop: true,
        margin: 20,
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        dots: false,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1024: {
                items: 4
            },
            1366: {
                items: 4
            }
        }
    });
    </script>
    <!-- Testimonial Slider -->
    <script>
    var $owl = $('#testimonial');

    $owl.children().each(function(index) {
        $(this).attr('data-position', index); 
    });

    $owl.owlCarousel({
        center: true,
        loop: true,
        dots: true,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            800: {
                items: 3
            },
            1366: {
                items: 3
            }
        }
    });
    $(document).on('click', '.owl-item>div', function() {
        var $speed = 300; // in ms
        $owl.trigger('to.owl.carousel', [$(this).data('position'), $speed]);
    });
    </script>
    <!-- Header Dropdown -->
    <script>
    $('.dropdown > .caption').on('click', function() {
        $(this).parent().toggleClass('open');
    });

    $('.dropdown > .list > .item').on('click', function() {
        $('.dropdown > .list > .item').removeClass('selected');
        $(this).addClass('selected').parent().parent().removeClass('open').children('.caption').html($(this)
            .html());

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
    $(document).ready(function() {
        $(document).on("click", "a[name='wishlist']", function() {
            var userId = '<?php echo $this->session->userdata('DZL_USERID')?>';
            if (userId == '') {
                //alert('Please login in order to add to wishlist');
                window.location.href = '<?php echo base_url('login'); ?>';
            }
            var product_id = $(this).attr('data-id');
            var curobj = $(this);
            var ur = '<?=base_url()?>';
            $.ajax({
                url: ur + 'add-to-wishlist',
                type: "POST",
                data: {
                    'product_id': product_id
                },
                cache: false,
                success: function(result) {
                    if (result == 'addedtowishlist') {
                        curobj.html(
                            '<i class="far fa-heart heart_icon active" title="Remove from Favourite"></i>'
                            );
                    } else if (result == 'removedfromwishlist') {
                        curobj.html(
                            '<i class="far fa-heart heart_icon" title="Mark Favourite"></i>'
                            );
                    }
                }
            });
        });
    });
    </script>
    <script>
    $(function() {
        $('.chart').easyPieChart({
            size: 70,
            barColor: "#dc3545b0",

            lineWidth: 6,
            trackColor: "#98fb987d",
            lineCap: "circle",
            animate: 2000,
        });
    });
    $(function() {
        $('.chart1').easyPieChart({
            size: 70,
            barColor: "#dc3545b0",

            lineWidth: 6,
            trackColor: "#98fb987d",
            lineCap: "circle",
            animate: 2000,
        });
    });
    </script>
    <script>
    $(document).on('click', '.copyShareUralToClipBoard', function() {
        /* Get the text field */
        var copyText = $(this).parent('.modal-body').find('.copyShareUrlInput');

        /* Select the text field */
        copyText.select();
        //copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText.val());

        /* Alert the copied text */
        //alert("Copied the text: " + copyText.val());
    });
    $(document).on('click', '.userLoginError', function() {
        //alert('<?php echo lang('SHARE_LOGIN_ERROR'); ?>');
        window.location.href = '<?php echo base_url('login'); ?>';
    });
    </script>
    <script type="text/javascript">
    function socialWindow(pageUrl, url) {
        /*
        $.ajax({
            type: 'post',
            url: FULLSITEURL+'check-share-limit',
            data: {shareurl:pageUrl},
            success: function(response){ 
                if(response == 'success'){
                    var left = (screen.width -570) / 2;
                    var top = (screen.height -570) / 2;
                    var params = "menubar=no,toolbar=no,status=no,width=570,height=570,top=" + top + ",left=" + left;  window.open(url,"NewWindow",params);
                } else {
                    alert('You have consumed your share limit for the product');
                }
            }
        });
        */
        var left = (screen.width - 570) / 2;
        var top = (screen.height - 570) / 2;
        var params = "menubar=no,toolbar=no,status=no,width=570,height=570,top=" + top + ",left=" + left;
        window.open(url, "NewWindow", params);
    }
    $(document).on('click', '.social-share.facebook', function() {
        var shareUrl = $(this).closest('.sociallinks').next('.copyShareUrlInput').val();
        var pageUrl = encodeURIComponent(shareUrl);
        var url = "https://www.facebook.com/sharer.php?u=" + pageUrl;
        socialWindow(shareUrl, url);
    });
    $(document).on('click', '.social-share.twitter', function() {
        var shareUrl = $(this).closest('.sociallinks').next('.copyShareUrlInput').val();
        var pageUrl = encodeURIComponent(shareUrl);
        var tweet = encodeURIComponent($("meta[property='og:description']").attr("Dealzarabia product share"));
        var url = "https://twitter.com/intent/tweet?url=" + pageUrl + "&text=" + tweet;
        socialWindow(shareUrl, url);
    });
    $(document).on('click', '.social-share.linkedin', function() {
        var shareUrl = $(this).closest('.sociallinks').next('.copyShareUrlInput').val();
        var pageUrl = encodeURIComponent(shareUrl);
        var url = "https://www.linkedin.com/shareArticle?mini=true&url=" + pageUrl;
        socialWindow(shareUrl, url);
    });
    $(document).on('click', '.social-share.google', function() {
        var shareUrl = $(this).closest('.sociallinks').next('.copyShareUrlInput').val();
        var pageUrl = encodeURIComponent(shareUrl);
        var url = 'https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=&su=Dealzarabia+product+share&body=' +
            pageUrl + '&ui=2&tf=1&pli=1';
        socialWindow(shareUrl, url);
    });
    $(document).on('click', '.social-share.whatsapp', function() {
        var shareUrl = $(this).closest('.sociallinks').next('.copyShareUrlInput').val();
        var pageUrl = encodeURIComponent(shareUrl);
        var url = "https://wa.me/whatsappphonenumber/?text=" + pageUrl;
        socialWindow(shareUrl, url);
    });
    </script>
</body>

</html>