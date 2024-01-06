<?php include('common/mobile/header.php') ?>
<style>
    .loading {
        align-items: center;
        display: none;
        justify-content: center;
        height: 100%;
        /* position: fixed; */
        width: 100%;
    }

    .loading__dot {
        animation: dot ease-in-out 1s infinite;
        background-color: grey;
        display: inline-block;
        height: 20px;
        margin: 10px;
        width: 20px;
    }

    .loading__dot:nth-of-type(2) {
        animation-delay: 0.2s;
    }

    .loading__dot:nth-of-type(3) {
        animation-delay: 0.3s;
    }

    @keyframes dot {
        0% { background-color: grey; transform: scale(1); }
        50% { background-color: #e72d2e; transform: scale(1.3); }
        100% { background-color: grey; transform: scale(1); }
    }
</style>
<div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Sub Winners List
                </h1>
            </section>
            
            <div class="inner_pagedata">
                
            <?php if($this->session->userdata('DZL_USERSTYPE') != 'Users'): ?> 
                <section class="deals_homesec">
                    <div class="myorder_table"> 
                        <div class="cardbox">
                            <?php if($this->session->flashdata('success')): ?>
                                <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                            <?php endif; ?>

                            <?php if($this->session->flashdata('error')): ?>
                                <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                            <?php endif; ?>
                            <form id="couponForm" method="post" action="<?=base_url('collect-prize')?>">
                                <div class="col-sm-12 col-md-12 col-lg-12  p-0"> 
                                    <input type="text" style="text-transform:uppercase" name="coupon_code" id="coupon_code" class="form-control"  autocomplete="off" placeholder="Enter your Coupon Code" value="<?=set_value('coupon_code');?>">   
                                    &nbsp;
                                    <input type="number" class="form-control" autocomplete="off" name="pin" id="pin" value="<?php if(set_value('pin')): echo set_value('pin'); endif; ?>" placeholder="Enter your collection PIN">
                                    &nbsp;
                                    <input type="submit" class="login_button mt-3" id="login_button2" value="Approve">  
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

                <section class="deals_homesec">
                <form class="form-inline" id="rechargeForm" method="post">
                    <div class="col-sm-12 col-md-12 col-lg-12  p-0"> 
                        <input type="text" name="search" id="search	" class="form-control"  autocomplete="off" placeholder="Search your coupon number / order id" value="<?=set_value('search');?>">
                        <input type="submit" class="login_button mt-3" id="login_button2" value="Show">
                    </div>
                    &nbsp;
                </form>	
                   <div class="myorder_table" id="winner-list">
                        <?php if($winner_list): ?>
                            <input type="hidden" id="offset" value="<?=$offset?>"/>
                            <input type="hidden" id="limit" value="<?=$limit?>"/>
                            <input type="hidden" id="perpage" value="<?=$perpage?>"/>
                            <input type="hidden" id="code" value="<?=$code?>"/>
                            <input type="hidden" id="order_id" value="<?=$order_id?>"/>
                            <input type="hidden" id="data_count" value="<?=count($winner_list)?>"/>
                            <?php foreach($winner_list as $key => $items ) :  ?>


                                <div class="cardbox">
                                    <ul>
                                        <li class="red_txt">
                                            <strong>Order Id</strong>
                                            <span><?=$items['id'];?></span>
                                        </li>
                                       
                                        <li>
                                            <strong>Draw Date</strong>
                                            <span><?=$items['draw_date'];?></span>
                                        </li>

                                        <li>
                                            <strong>Name</strong>
                                            <span><?=$items['name'];?></span>
                                        </li>
                                        
                                        <li>
                                            <strong>Coupon Number</strong>
                                            <span><?=$items['coupon_code'];?></span>
                                        </li>

                                        <li>
                                            <strong style="color: #186706 !important; font-weight: 600;">Winning Amount</strong>
                                            <span style="color: #186706 !important; font-weight: 600;"><?=$items['amount'];?></span>
                                        </li>
                                        
                                        <?php if($this->session->userdata('DZL_USERSTYPE') != "Users"){ ?>
                                        <li>
                                            <strong>
                                            <?php if($items['collection_status'] == "Pending"){ ?>    
                                                <div class="donat_nrecipt">
                                                    <a href="javaScript:void{}" class="approved-coupon"  onclick="active_coupon('<?=$items['coupon_code']?>')">Approval</a>
                                                </div>
                                            <?php } ?>
                                            </strong>
                                            <?php if($items['collection_status'] == 'Collected'): ?>
                                                <span style="color:#186706;"><?=$items['collection_status']?></span>
                                            <?php else: ?>
                                                <span style="color:#c9261f;"><?=$items['collection_status']?></span>
                                            <?php endif; ?>
                                        </li>
                                        <?php } ?>
                                    </ul>    
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                   </div>
                   <div class="loading">
                        <span class="loading__dot"></span>
                        <span class="loading__dot"></span>
                        <span class="loading__dot"></span>
                    </div>
                </section>
                
            </div>


    <?php include('common/mobile/footer.php'); ?>
    <?php include('common/mobile/menu.php'); ?>

        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>

<script>
	function active_coupon(code =''){
		let coupon = code
		$('#coupon_code').val(coupon);
		$('#pin').focus();
	}


    let request_sent = 0;
	let offset		= $('#offset').val();
	let limit 		= $('#limit').val();
	let perpage 	= $('#perpage').val();
	let code 		= $('#code').val();
	let order_id 	= $('#order_id').val();
	let data_count 	= $('#data_count').val();
	let last 		= '';
    $(window).on('touchmove resize', function() {
    var windowHeight = $(window).height();
    var documentHeight = $(document).height();
    var scrollPosition = $(window).scrollTop();
    var lastSectionHeight = $('#winner-list').height();
    var lastSectionOffset = $('#winner-list').offset().top;

    if (scrollPosition + windowHeight >= lastSectionOffset + lastSectionHeight && request_sent === 0) {
        $('.loading').css('display','flex');
        var ur = '<?=base_url()?>';
        request_sent = 1
        $.ajax({				
            url: ur + 'get-winners-by-ajax',
            type: "POST",
            data: {
                'offset': offset,
                'limit': limit,
                'perpage': perpage,
                'code' : code,
                'order_id' : order_id
            },
            cache: false,
            success: function (result) {
                $('.loading').css('display','none');
                let res = JSON.parse(result)
                if(res.status === 200){
                    offset = res.newoffset;
                    let winnerData = JSON.parse(res.data);
                    let html = '';
                    let link = '';
                    if(winnerData  !== null){
                        winnerData.forEach(element => {
                            
                            html +=	'<div class="cardbox">';
                            html +=     '<ul>';
                            html +=         '<li class="red_txt">';
                            html +=             '<strong>Order Id</strong>';
                            html +=             '<span>'+element.id+'</span>';
                            html +=         '</li>';
                            html +=         '<li>';
                            html +=             '<strong>Draw Date</strong>';
                            html +=             '<span>'+element.draw_date+'</span>';
                            html +=         '</li>';
                            html +=         '<li>';
                            html +=             '<strong>Name</strong>';
                            html +=             '<span>'+element.name+'</span>';
                            html +=         '</li>';
                            html +=         '<li>';
                            html +=             '<strong>Coupon Number</strong>';
                            html +=             '<span>'+element.coupon_code+'</span>';
                            html +=         '</li>';
                            html +=         '<li>';
                            html +=             '<strong style="color: #186706 !important; font-weight: 600;" >Winning Amount</strong>';
                            html +=             '<span style="color: #186706 !important; font-weight: 600;" >'+element.amount+'</span>';
                            html +=         '</li>';
                            <?php if($this->session->userdata('DZL_USERSTYPE') != "Users"){ ?>
                                html +=         '<li>';
                                html +=             '<strong>';
                                html +=             '<div class="donat_nrecipt">';
                                if(element.collection_status === "Pending"){
                                    link = "active_coupon('"+element.coupon_code+"')";
                                    html +=             '<a href="javaScript:void{}" class="approved-coupon" onclick="'+link+'">Approval</a>';
                                }
                                html +=             '</div>';
                                html +=             '</strong>';
                                html +=             '<span style="color:#c9261f;">'+element.collection_status+'</span>';
                                html +=         '</li>';
                            <?php } ?>
                            
                            html +=     '</ul>';
                            html += '</div>';

                            ++data_count;
                            if(element.total === data_count){
                                last = 'END';
                            }
                        });	
                        if(last === 'END'){
                            request_sent = 1;
                        } else{
                            request_sent = 0;
                        }
                    }
                    $('#winner-list').append(html);
                    $('#data_count').val('')
                } else{
                    request_sent = 1;
                }
            }
        });	
    }
    });
</script>