<?php include('common/mobile/header.php') ?>

            <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href=""><i class="icofont-rounded-left"></i></a>
                <h1>
                    Transfer Arabian Points
                </h1>
            </section>
            <div class="inner_pagedata">
                <?php include('common/mobile/membership-details.php') ?>

                <div class="inner_forms login_tab">
                    <h3 class="mt-0"><span>Enter Details</span></h3>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#mobnumber">With Mobile Number</a>
                            </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#withemailaccount">With Email Account</a>
                        </li>
                    </ul>

                <form class="form-inline" id="rechargeForm" method="post" action="<?=base_url('profile/recharge')?>">

                    <?php if ($this->session->flashdata('error')): ?>
                        <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success')): ?>
                        <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                    <?php endif; ?>


                    <div class="tab-content">
                        <div class="tab-pane active" id="mobnumber">
                                <div class="form-group row">
                                    <div class="col-3 ps-0">
                                        <?php if(set_value('country_code')): $countryCode = set_value('country_code'); elseif(isset($profileDetails['country_code'])): $countryCode = stripslashes($profileDetails['country_code']); else: $countryCode = '+971'; endif; ?>
                                        <select class="form-control" name="country_code" id="country_code" <?php if($profileDetails['country_code']): echo 'disabled'; endif; ?> >
                                            <?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>
                                                <option value="<?php echo $countryCodeKey; ?>" <?php if($countryCode == $countryCodeKey): echo 'selected="selected"'; endif; ?>><?php echo $countryCodeKey; ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>

                                    <div class="col p-0">
                                        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile No. (e.g. 502345678)" autocomplete="off"  value="<?php if(set_value('mobile')): echo set_value('mobile'); endif; ?>" >
                                        <?php if(form_error('email')): ?>
                                          <label id="email-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
                                        <?php elseif($emailError): ?>
                                          <label id="email-error" class="error" for="recharge_amt"><?php echo $emailError; ?></label>
                                        <?php endif; ?>
                                    </div>
                                </div>
                        </div>
                        <div class="tab-pane fade" id="withemailaccount">
                            <div class="form-group row">
                                <div class="col-12 p-0">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" autocomplete="off" value="<?php if(set_value('email')): echo set_value('email'); endif; ?>" >
                                    <?php if(form_error('email')): ?>
                                      <label id="email-error" class="error" for="email"><?php echo form_error('email'); ?></label>
                                    <?php elseif($emailError): ?>
                                      <label id="email-error" class="error" for="email"><?php echo $emailError; ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                      <div class="form-group row">
                        <div class="col p-0">
                            <input type="text" name="recharge_amt" id="recharge_amt" class="form-control" placeholder="Arabian Points" autocomplete="off">
                            <?php if(form_error('recharge_amt')): ?>
                              <label id="recharge_amt-error" class="error" for="recharge_amt"><?php echo form_error('recharge_amt'); ?></label>
                            <?php elseif($amountError): ?>
                              <label id="recharge_amt-error" class="error" for="recharge_amt"><?php echo $amountError; ?></label>
                            <?php endif; ?>

                        </div>
                     </div> 


                <?php if($this->session->userdata('DZL_USERSTYPE') == 'Sales Person' || $this->session->userdata('DZL_USERSTYPE') == 'Freelancer'): ?>

                     <div class="form-group row">
                        <div class="col p-0 arabian_percent">
                            
                            <span id="perc_5">5%</span>
                            <input type="radio" name="set_perc" class="perc_5 hidden" value="5" hidden />
                            <span id="perc_10">10%</span>
                            <input type="radio" name="set_perc" class="perc_10 hidden" value="10" hidden />
                            <span id="perc_15">15%</span>
                            <input type="radio" name="set_perc" class="perc_15 hidden" value="15" hidden/>
                            <span id="perc_20">20%</span>
                            <input type="radio" name="set_perc" class="perc_20 hidden" value="20" hidden />

                            <input type="text" name="percentage" id="percentage" class="form-control" autocomplete="off" value="<?php if(set_value('percentage')): echo set_value('percentage'); endif; ?>" placeholder="Enter %"  >
                            <?php if(form_error('percentage')): ?>
                                <label id="percentage-error" class="error" for="percentage"><?php echo form_error('percentage'); ?></label>
                            <?php endif; ?>
                        </div>
                     </div> 

                <?php endif;?>
                     
                      <div class="form-group row text-center">
                        <div class="col-12 p-0">
                            <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                            <input type="submit" class="login_button" id="login_button2" onclick="recharge()" value="Transfer">
                        </div>
                      </div>

                </form>

                    <h3><span>Transfer History</span></h3>
                    <div class="transfer_history">

                        <?php $i=1; foreach ($users as $key => $items) :
                            if($items['created_by'] == 'ADMIN'):
                                $where          =   ['users_id'=>(int)$items['user_id_cred']];
                            else:
                                if($items['record_type'] == 'Credit') :
                                    $where          =   ['users_id'=>(int)$items['created_user_id']];
                                else:
                                    $where          =   ['users_id'=>(int)$items['user_id_to']];
                                endif;
                            endif;
                            $userData = $this->geneal_model->getOnlyOneData('da_users', $where );
                        ?>

                        <div class="transpoint_box">
                            <ul>
                                <li class="tranfer_pname">
                                    <span><i class="icofont-ui-user"></i> <?=$userData['users_name']?> </span>
                                    <small>
                                        <img src="<?=base_url('assets/AP-GREEN.png');?>" width="25px" alt="appgreen">
                                        
                                        <?php 
                                            if($items['sum_arabian_points']):
                                                echo $items['sum_arabian_points'];
                                            else:
                                                echo $items['arabian_points'];
                                            endif; 
                                        ?>


                                    </small>
                                </li>
                                <li>
                                    <span><i class="icofont-email"></i> <?=$userData['users_email']?></span>
                                </li>
                                <li>
                                    <span><i class="icofont-ui-call"></i> <?=$userData['users_mobile']?></span>
                                    
                                </li>
                                <li>
                                    <span><i class="icofont-calendar"></i> <?=date('d M, Y h:i A', strtotime($items['created_at']))?>    </span>
                                    
                                </li>
                                <li>
                                    <span><i class="icofont-sale-discount"></i> Margin Percentage</span>

                                    <?php if($items['rechargeDetails']['percentage']):  ?>
                                        <?=$items['rechargeDetails']['percentage'].'%';?>
                                    <?php else: ?>
                                        <small>--</small>
                                    <?php endif; ?>
                                </li>
                            </ul>

                            <?php if($items['record_type'] == 'Credit'):  ?>
                                <div class="stauts receive">Received</div>
                            <?php else:  ?>
                                <div class="stauts sent">Sent</div>
                            <?php endif; ?>
                        </div>


                    <?php endforeach; ?>

<!-- 
                        <div class="transpoint_box">
                            <ul>
                                <li class="tranfer_pname">
                                    <span><i class="icofont-ui-user"></i> Delip</span>
                                    <small>
                                        <img src="https://dealzarabia.com//assets/AP-GREEN.png" width="25px" alt="appgreen">
                                        50
                                    </small>
                                </li>
                                <li>
                                    <span><i class="icofont-email"></i> dilip.test@gmail.com</span>
                                </li>
                                <li>
                                    <span><i class="icofont-ui-call"></i> 87001144888</span>
                                    
                                </li>
                                <li>
                                    <span><i class="icofont-calendar"></i> May 02, 2023, 11:32 am</span>
                                    
                                </li>
                                <li>
                                    <span><i class="icofont-sale-discount"></i> Margin Percentage</span>
                                    <small>--</small>
                                </li>
                            </ul>
                            <div class="stauts sent">Sent</div>
                        </div>
                        <div class="transpoint_box">
                            <ul>
                                <li class="tranfer_pname">
                                    <span><i class="icofont-ui-user"></i> Delip</span>
                                    <small>
                                        <img src="https://dealzarabia.com//assets/AP-GREEN.png" width="25px" alt="appgreen">
                                        50
                                    </small>
                                </li>
                                <li>
                                    <span><i class="icofont-email"></i> dilip.test@gmail.com</span>
                                </li>
                                <li>
                                    <span><i class="icofont-ui-call"></i> 87001144888</span>
                                    
                                </li>
                                <li>
                                    <span><i class="icofont-calendar"></i> May 02, 2023, 11:32 am</span>
                                    
                                </li>
                                <li>
                                    <span><i class="icofont-sale-discount"></i> Margin Percentage</span>
                                    <small>--</small>
                                </li>
                            </ul>
                            <div class="stauts receive">Received</div>
                        </div> -->
                        

                    </div>
                </div>

            </div>

        <?php include('common/mobile/footer.php'); ?>
        <?php include('common/mobile/menu.php'); ?>
        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>
    <!-- Country code popup Ui start -->
        <?php include('common/mobile/countrycode-list.php') ?>
    <!-- Country code popup Ui end -->

<script>
    $(document).ready(function(){

        $(".add_cart").on('click' , function(){

            var product_id = $(this).attr('data-id');
            var color = $(this).attr('data-color');
            var size = $(this).attr('data-size');
            
            var curobj = $(this);
            curobj.html('Added To <i class="fas fa-shopping-cart" aria-hidden="true"></i>');

            curobj.addClass('edit_cart');
            curobj.removeClass('add_cart');

            var ur = '<?=base_url()?>';
            $.ajax({
                url : ur+ "shopping_cart/add",
                method: "POST", 
                data: {product_id: product_id, fcolor: color, fsize:size},
                success: function(data){
                    
                    // alert(data);

                    $('.cart-button').empty();
                    var A = '<small>'+data+'</small><i class="icofont-cart-alt"></i>Cart';
                    $('.cart-button').empty().append(A);
                }
            });

        });


});
 
$(document).ready(function(){
        $('#perc_5').click(function(){
            $('.perc_5').prop('checked', true);
            if ($(".perc_5").prop("checked")) {
                $('#perc_5').addClass('percentace_active');
                $('#percentage').empty().val(5);
            } 
            $('#perc_10').removeClass('percentace_active');
            $('#perc_15').removeClass('percentace_active');
            $('#perc_20').removeClass('percentace_active');
        });
        $('#perc_10').click(function(){
            $('.perc_10').prop('checked', true);
            if ($(".perc_10").prop("checked")) {
                $('#perc_10').addClass('percentace_active');
                $('#percentage').empty().val(10);
            } 
            $('#perc_5').removeClass('percentace_active');
            $('#perc_15').removeClass('percentace_active');
            $('#perc_20').removeClass('percentace_active');
            
        });
        $('#perc_15').click(function(){
            $('.perc_15').prop('checked', true);
            if ($(".perc_15").prop("checked")) {
                $('#perc_15').addClass('percentace_active');
                $('#percentage').empty().val(15);
            } 
            $('#perc_5').removeClass('percentace_active');
            $('#perc_10').removeClass('percentace_active');
            $('#perc_20').removeClass('percentace_active');
        });
        $('#perc_20').click(function(){
            $('.perc_20').prop('checked', true);
            if ($(".perc_20").prop("checked")) {
                $('#perc_20').addClass('percentace_active');
                $('#percentage').empty().val(20);
            } 
            $('#perc_5').removeClass('percentace_active');
            $('#perc_10').removeClass('percentace_active');
            $('#perc_15').removeClass('percentace_active');
        });
        $('#percentage').change(function(){
            var perc = $('#percentage').val();
            var match = 0;
            if(perc == 5){
                $('.perc_5').prop('checked', true);
                $('#perc_5').addClass('percentace_active');

                $('#perc_10').removeClass('percentace_active');
                $('#perc_15').removeClass('percentace_active');
                $('#perc_20').removeClass('percentace_active');
                match =1;
            }

            if(perc == 10){
                $('.perc_10').prop('checked', true);
                $('#perc_10').addClass('percentace_active');

                $('#perc_5').removeClass('percentace_active');
                $('#perc_15').removeClass('percentace_active');
                $('#perc_20').removeClass('percentace_active');
                match =1;
            }
            if(perc == 15){
                $('.perc_15').prop('checked', true);
                $('#perc_15').addClass('percentace_active');

                $('#perc_5').removeClass('percentace_active');
                $('#perc_10').removeClass('percentace_active');
                $('#perc_20').removeClass('percentace_active');
                match =1;
            }
            if(perc == 20){
                $('.perc_20').prop('checked', true);
                $('#perc_20').addClass('percentace_active');

                $('#perc_5').removeClass('percentace_active');
                $('#perc_10').removeClass('percentace_active');
                $('#perc_15').removeClass('percentace_active');
                match =1;
            }
            if(match == 0){
                $('#perc_5').removeClass('percentace_active');
                $('#perc_10').removeClass('percentace_active');
                $('#perc_15').removeClass('percentace_active');
                $('#perc_20').removeClass('percentace_active');
            }
        });
});
</script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">

    $('#rechargeForm').click(function(e){
        e.preventDefault();
    });

    function recharge(){
        
        var email = $('#email').val();
        var arabianpoints = $('#recharge_amt').val();
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure to transfer AED "+arabianpoints+" to "+email+"!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Transfer it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $('#rechargeForm').submit();
                
            }
            })
    }
</script>