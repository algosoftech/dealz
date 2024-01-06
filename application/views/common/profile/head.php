<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
 .refersh {
    background-color: #ffffff;
    border: none;
    padding: 4px 11px !important;
    border-radius: 8px;
    color: #e72d2e;
    margin: 8px 0px;
    font-size: 10px !important;
    font-weight: 500 !important;
    border: 1px solid #e72d2e;
    /* margin-bottom: 21px; */
}
.my-profile .refersh:hover{
background: #e72d2e;
    color: #ffffff;
}
.my-profile .btn {
    background: #ffffff;
    border: 1px solid #e72d2e;
    padding: 8px 13px;
    border-radius: 8px;
    color: #e72d2e;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    margin-top: 0px;
}
.my-profile .content {
    background: #f5fcff00;
    padding-top: 10px;
}
.user_1{
    display:flex;
}
.user_1 h4{
margin-right:10px;
}
.refersh {
    background-color: #ffffff;
    border: none;
    padding: 4px 11px !important;
    border-radius: 8px;
    color: #e72d2e;
    margin: 0px;
    font-size: 11px !important;
    font-weight: 500 !important;
    border: 1px solid #e72d2e;
    /* margin-bottom: 21px; */
    margin-left: 9px;
}
.my-profile .user_profile {
    display: flex;
    padding: 27px;
    justify-content: center;
    background: #f5fcff00;
    padding: 20px 10px;
    position: relative;
    box-shadow: rgb(99 99 99 / 20%) 0px 2px 8px 0px;
    border-radius: 8px;
    flex-wrap: wrap;
    align-items: center;
    height: 152px;
}
.my-profile .user {
    font-size: 12px;
    font-family: 'Open Sans';
    font-weight: 600;
    background-color: #fbfbfb00;
    margin: 0px 2px -1px 38px;
}
</style>
<div class="users_members">
	<div class="userss">
		<div class="user_1">
			<?php 
				$points = (int)$this->session->userdata('DZL_TOTALPOINTS');
				$membership = $this->geneal_model->getMembership($points);
                //echo '<pre>'; print_r($membership); die;
				?>
				<h4><?=$membership['type']?></h4>

			<i class="user_content" >Expiring in <?=$this->session->userdata('DZL_EXPIRINGIN')?> Days</i>
		</div>
		<div class="user_1">
            <div >
            <img src="<?=base_url()?>/assets/AP-GREEN.png" width="25px" alt="appgreen"/>
            <?php  @$AP_points = $this->session->userdata('DZL_AVLPOINTS');  ?>
			<span  class="ared" style="font-weight: bold;"> : <?=@number_format($AP_points,2)?></span><br>
            </div>
			<a class="refersh" href="<?php echo base_url()?>refresh-point"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
		</div>
	</div>
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
                <?php /* ?>Silver Level  .25% Cash Back<br>
                Gold Level  25,000 AED  .5% Cash Back<br>
                Platinum Level  50,000 AED +  1% Cash Back<?php */ ?>
            </p>
		</div>
    <?php endif; ?>
</div>
