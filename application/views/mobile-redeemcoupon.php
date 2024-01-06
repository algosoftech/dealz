<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?= base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Redeem Voucher
                </h1>
            </section>
            <div class="inner_pagedata">
                <?php include('common/mobile/membership-details.php') ?>

                <div class="inner_forms login_tab">
                    <h3><span>Enter Voucher Code</span></h3>
                    <form action="<?=base_url('redeem-coupon')?>" id="rechargeForm" method="post">

                        <?php if ($this->session->flashdata('error')) : ?>
                            <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('success')): ?>
                            <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                        <?php  endif; ?>

                        <div class="form-group row">
                            <div class="col p-0">
                                <input type="text" name="couon_code" id="couon_code" class="form-control" placeholder="Voucher Code" autocomplete="off" value="<?php if(set_value('couon_code')): echo set_value('couon_code'); endif; ?>" >
                                
                                <?php if(form_error('couon_code')): ?>
                                  <label id="couon_code-error" class="error" for="couon_code"><?php echo form_error('couon_code'); ?></label>
                                <?php elseif($couonCodeError): ?>
                                  <label id="couon_code-error" class="error" for="couon_code"><?php echo $couonCodeError; ?></label>
                                <?php endif; ?>

                            </div>
                        </div>
                      
                        <div class="form-group row  text-center">
                            <div class="col-12 p-0">
                                <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                                <input type="submit" class="login_button" id="login_button2" value="Redeem">
                            </div>
                        </div>
                    </form>
                    <h3><span>History</span></h3>
                    <div class="voucher_history">

                    <?php $i=1; foreach ($users as $key => $items):  ?>
                        <div class="voucher_historybox">
                            <h4>Coupon Code: <span><?= $items['coupon_code']?></span></h4>
                            <p>Redeemed At: <b><?=date('d M, Y h:i A', strtotime($items['created_at']))?></b></p>
                            <div>
                                <img src="https://dealzarabia.com//assets/AP-GREEN.png" width="25px" alt="appgreen">
                                <span> <?=$items['arabian_points']?> </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>

            </div>

              
<?php include('common/mobile/footer.php'); ?>
<?php include('common/mobile/menu.php'); ?>
 
        </div>
    </div>

<?php include('common/mobile/footer_script.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if($popup_error == "YES"): ?>
    <script>
        Swal.fire(
            '<?=$error_title?>',
            '<?=$error_message?>',
            'error'
        )
    </script>
<?php endif; ?>