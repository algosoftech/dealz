<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">

        	<?php if ($this->session->flashdata('error')) : ?>
				<div class="users_members">
					<div class="userss">
					<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
					</div>
				</div>
			<?php endif; ?>

			<?php if ($this->session->flashdata('success')) : ?>
				<div class="users_members">
					<div class="userss">
	 				<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
	 				</div>
	 			</div>
			<?php  endif; ?>	

            <section class="inner_head">
                <a href="<?=base_url('/')?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    My Arabian Points
                </h1>
            </section>
            <div class="inner_pagedata">
                
                <!-- memship details starts -->
                <?php include('common/mobile/membership-details.php') ?>
                <!-- memship details end -->

                <section class="arabianpointstotal">
                    <ul>
                        <li>
                            <span>Signup Bonus</span>
                            <?php  @$signupBonus = floor(($signupBonus*100))/100;  ?>
                            <b>     
                                <img src="<?=base_url('/assets/AP-GREEN.png');?>" width="25px" alt="appgreen">
                                <?=number_format($signupBonus,2)?>
                            </b>
                        </li>
                        <li>
                            <span>Earn through Topup</span>
                            <?php  @$topup = floor(($topup*100))/100;  ?>
                            <b>
                                <img src="<?=base_url('/assets/AP-GREEN.png');?>" width="25px" alt="appgreen">
                                <?=number_format($topup,2)?>
                            </b>
                        </li>
                        <li>
                            <span>Purchase Discount</span>
                            <?php  @$cashback = floor(($cashback*100))/100;  ?>
                            <b>
                                <img src="<?=base_url('/assets/AP-GREEN.png');?>" width="25px" alt="appgreen">
                                <?=number_format($cashback,2)?>
                            </b>
                        </li>
                        <li>
                            <span>Earn through Referral</span>
                            <?php  @$referral = floor(($referral*100))/100;  ?>
                            <b>
                                <img src="<?=base_url('/assets/AP-GREEN.png');?>" width="25px" alt="appgreen">
                                 <?=number_format($referral,2)?>
                            </b>
                        </li>
                        <li class="totlearn">
                            <span>Total Earned</span>
                            <?php  @$totalEarned = floor(($totalEarned*100))/100;  ?>
                            <b>
                                <img src="<?=base_url('/assets/AP-GREEN.png');?>" width="25px" alt="appgreen">
                                <?=@number_format($totalEarned,2)?>
                            </b>
                        </li>
                    </ul>
                </section>
                <section class="deals_homesec">
                    <div class="home_deialbox">
                       
                    	<?php if($products):  
                    	 $oCi=1; 
                    	 foreach ($products as $key => $item) :
							$valid = $item['validuptodate'].' '.$item['validuptotime'].':0';
							$today = date('Y-m-d H:i:s');
							if(strtotime($valid) > strtotime($today)):
							
								if($oCi > 3):
									$currentTabClass  = 'showInLodeMore';
								else:
									$currentTabClass  = '';
								endif;

							$sharewhere['where']=	array('users_id'=>(int)$this->session->userdata('DZL_USERID'),'products_id'=>(int)$item['products_id']);
							$shareCount			=	$this->common_model->getData('count','da_product_share',$sharewhere,'','0','0');
							$accumulatedPint    =	((($item['adepoints']*$item['share_percentage_first'])/100)*$shareCount);
						 ?>

                        <!----------------->
                        <div class="deal_repeatrow myarabiatbuybox">
                            <!---->
                            <div class="deailboxrow_1">
                                <div class="deal_nameprice">
                                    <?php  $data2 = $this->geneal_model->getParticularDataByParticularField('prize_image','da_prize', 'product_id', $item['products_id']); ?>
                                    
                                    <b>Buy <?=$item['title'];?></b>
                                    <h4>Win!</h4>
                                    <p><?=$data2['title'];?></p>
                                    
                                </div>
                                <div class="deal_share">
                                    <a href="javascript:void(0);"><i class="icofont-share"></i></a>
                                </div>
                            </div>
                            <!---->
                            <div class="deailboxrow_2">
                                <div class="deal_prod">
                                    <a href="javascript:void(0);">
                                    	<?php if(file_get_contents($item['product_image'])): ?>
                                    		<img src="<?=base_url('/').$item['product_image']; ?>" class="img-responsive" alt="<?=$items['product_image_alt']?>" />

                                    	<?php else: ?>
                                    		<img src="<?=base_url('/').'assets/img/NO_IMAGE.jpg'; ?>" class="img-responsive" alt="<?=$items['product_image_alt']?>" />

                                    	<?php endif; ?>
                                    </a>
                                </div>
                                <div class="deal_win">

                                    <?php  if($dat2['prize1']): ?>
                                        <p>Cash Prize <?=$dat2['prize1'];?> AED</p>
                                    <?php endif; ?>

                                    <?php  if($dat2['prize2']): ?>
                                        <p>Cash Prize 2nd <?=$dat2['prize2'];?> AED</p>
                                    <?php endif; ?>

                                    <?php  if($dat2['prize3']): ?>
                                        <p>Cash Prize 3rd <?=$dat2['prize3'];?> AED</p>
                                    <?php endif; ?>

                                    <span>Price: <small>AED <?=number_format($item['adepoints'],2);?></small></span>
                                </div>

                                <div class="deal_cash">


                                	<a href="javascript:void(0);">
                            			 
									    <?php if($item['product_image']):  ?>
                            				<img src="<?=base_url().$data2['prize_image']?>" class="img-responsive" <?=$data2['prize_image_alt']?> />
										<?php else: ?>
											<img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-responsive" alt="<?=$data2['prize_image_alt']?>">
										<?php endif; ?>

                                	</a>
                                </div>


                            </div>
                            <!---->
                            <div class="deailboxrow_3">
                                <div class="arabianpointnsharebtn">
                                    <a href="javascript:void(0);" class="arabianpoint_btngreen">Accumulated Points <?php echo $accumulatedPint; ?></a>
                                    <a href="javascript:void(0);" class="arabianpoint_btnred">Share and Get Arabian Point <?=$item['share_percentage_first']?>%</a>
                                </div>
                            </div>
                        </div>
                    	<!----------------->
                    	<?php $oCi++; endif; endforeach; endif; ?>

                    </div>
                </section>

            </div>

            <?php include('common/mobile/footer.php'); ?>
			<?php include('common/mobile/menu.php'); ?>
             

        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>