<?php include('common/mobile/header.php') ?>
    

    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Add User
                </h1>
            </section>
            <div class="inner_pagedata">
               <section class="inner_forms login_tab">
                    <div class="mt-2">
                        <form class="form-inline add-userform" action="<?=base_url('add-user')?>" method="post" id="xyz">

                            <?php  if ($this->session->flashdata('error')) : ?>
                                <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('success')) : ?>
                                <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                            <?php endif; ?>



                            <div class="form-group row">
                                <div class="col p-0">
                                    <select name="user_type" id="user_type" class="form-control">
                                        <option value="User" >User</option>
                                        <option value="Retailer" >Retailer</option>
                                    </select>
                                    <?php if(form_error('user_type')): ?>
                                      <label id="user_type-error" class="error" for="user_type"><?php echo form_error('user_type'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="text" name="users_name" id="users_name" class="form-control" placeholder="First Name" autocomplete="off" value="<?php if(set_value('users_name')): echo set_value('users_name'); endif; ?>" >
                                    <?php if(form_error('users_name')): ?>
                                      <label id="users_name-error" class="error" for="users_name"><?php echo form_error('users_name'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                             <div class="form-group row">
                                <div class="col p-0">
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" autocomplete="off" value="<?php if(set_value('last_name')): echo set_value('last_name'); endif; ?>" >
                                    <?php if(form_error('last_name')): ?>
                                      <label id="last_name-error" class="error" for="last_name"><?php echo form_error('last_name'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3 ps-0">
                                   
                                    <select name="country_code" id="country_code" class="form-control">
                                        <?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>
                                            <option value="<?php echo $countryCodeKey; ?>" <?php if($countryCode == $countryCodeKey): echo 'selected="selected"'; endif; ?>><?php echo $countryCodeKey; ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>

                                </div>
                                <div class="col p-0">
                                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile No. (e.g. 502345678)" autocomplete="off" value="<?php if(set_value('mobile')): echo set_value('mobile'); endif; ?>" >
                                    <?php if(form_error('mobile')): ?>
                                      <label id="mobile-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
                                    <?php endif; ?>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" autocomplete="off" value="<?php if(set_value('email')): echo set_value('email'); endif; ?>" >
                                    <?php if(form_error('email')): ?>
                                      <label id="email-error" class="error" for="email"><?php echo form_error('email'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row store_name_section">
                                <div class="col p-0">
                                    <input type="text" name="store_name" id="store_name" class="form-control" placeholder="Store Name" autocomplete="off" value="<?php if(set_value('store_name')): echo set_value('store_name'); endif; ?>" >
                                    <?php if(form_error('store_name')): ?>
                                      <label id="store_name-error" class="error" for="store_name"><?php echo form_error('store_name'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="password" name="password" id="password" class="form-control password2" placeholder="Password" autocomplete="off">
                                    <i class="icofont-eye showpassword"></i>
                                    <?php if(form_error('password')): ?>
                                      <label id="password-error" class="error" for="password"><?php echo form_error('password'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="password" name="cpassword" id="cpassword" class="form-control password2" placeholder="Confirm Password" autocomplete="off">
                                    <i class="icofont-eye showpassword"></i>
                                    <?php if(form_error('cpassword')): ?>
                                      <label id="cpassword-error" class="error" for="cpassword"><?php echo form_error('cpassword'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row  text-center m-0">
                                <div class="col-12 p-0">
                                    <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                                    <input type="hidden" name="page" value="<?=$page?>">
                                    <input type="submit" class="login_button" id="login_button2" value="Add User">
                                </div>
                            </div>
                        </form>
                    </div>

                            
                        <?php if(!empty($users)): ?>
                            <div class="mt-2">
                                <div class="user_list" style="overflow-x: auto;">
                                  <table class="table">
                                    <tr>
                                      <th style="text-align:center;">S.No</th>
                                      <th style="text-align:center;">Name</th>
                                      <th style="text-align:center;">Email</th>
                                      <th style="text-align:center;">Phone</th>
                                      <th style="text-align:center;">Points</th>
                                      <th style="text-align:center;">Recharge Date</th>
                                    </tr>
                                    <?php $i=1; foreach ($users as $key => $items) { ?>
                                    <tr>
                                      <td width="10%"><?=$i?></td>
                                      <td width="15%"><?=$items['users_name']?></td>
                                      <td width="15%"><?=$items['users_email']?></td>
                                      <td width="20%"><?=$items['users_mobile']?></td>
                                      <td width="5%"><?=$items['availableArabianPoints']?></td>
                                      <td width="20%"><?=date('d M Y', strtotime($items['created_at']))?></td>
                                    </tr>
                                    <?php $i++; } ?>
                                  </table>
                               </div>
                        <?php endif; ?>


                    </div>


               </section>
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

            $('#user_type').on('change' , function (){

                var UserType = $(this).val();
                
                if(UserType == 'User'){
                    $('.store_name_section').hide();
                }else{
                    $('.store_name_section').show();
                }

            });

        });
    </script>