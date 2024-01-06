<section class="freelance_sec">
    <div class="freenlacebox">
        <h3><?=$this->session->userdata('DZL_USERSTYPE'); ?></h3>
        <div class="freenlacecoin">
            <?php 
            $points = (int)$this->session->userdata('DZL_TOTALPOINTS');
            $membership = $this->geneal_model->getMembership($points);
                                // echo '<pre>'; print_r($membership); die;
            ?>
            <h4>
                <?=$membership['type']?>
                <i class="user_content" >Expiring in <?=$this->session->userdata('DZL_EXPIRINGIN')?> Days</i>
            </h4>

            <div class="coins_block">
                
                <?php  @$AP_points = $this->session->userdata('DZL_AVLPOINTS');  ?>
                <?php if($AP_points): ?>
                    <div>
                        <img src="<?=base_url('/assets/AP-GREEN.png')?>" width="25px" alt="appgreen">
                        <span> : <?=@number_format($AP_points,2)?></span>
                    </div>
                    <p>Your Available arabian points</p>
                    <a class="refersh" href="<?=base_url('refresh-point');?>"><i class="icofont-refresh" aria-hidden="true"></i> Refresh</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="freenlacecoininfo">
            <?php if( isset($level_details) && $level_details == 'hide' ):?>
            <?php else: ?>
                <div class="content">
                    <?php /* ?><p style=""><?=$membership['benifitDetails']?></p><?php */ ?>
                    <p style="">
                        Members level as per 1 year trailing period<br>
                        <?php if($membership['membershipData']): $i=0;
                            foreach($membership['membershipData'] as $membershipData):
                                echo $i>0?'<br>':'';
                                echo $membershipData['membership_type'].' ';
                                echo $membershipData['ade']>0?$membershipData['ade'].' AED ':'';
                                echo $membershipData['benifit'].'% Cash Back';
                                $i++;
                            endforeach;
                        endif; 
                        ?>
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>