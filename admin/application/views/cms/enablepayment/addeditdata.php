<!-- <link rel="stylesheet" href="{ASSET_INCLUDE_URL}canvasCrop/user.image.canvasCrop.css">
 --><link rel="stylesheet" href="{ASSET_INCLUDE_URL}canvasCrop/about.image.canvasCrop.css">
<script type="text/javascript" src="{ASSET_INCLUDE_URL}canvasCrop/jquery.canvasCrop.js"></script>
<style type="text/css">
  input#show_vat {
    margin-right: 30%;
    margin-left: 6px;
}
</style>
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <?php /* ?><h5 class="m-b-10">Welcome <?=sessionData('HCAP_ADMIN_FIRST_NAME')?></h5><?php */ ?>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo getCurrentDashboardPath('dashboard/index'); ?>"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo correctLink('CMSENABLEPAYMENT',getCurrentControllerPath('index')); ?>"> CMS</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);"><?=$EDITDATA?'Edit':'Add'?> Enable Payment</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <h5><?=$EDITDATA?'Edit':'Add'?>  Enable Payment</h5>
                <a href="<?php echo correctLink('CMSENABLEPAYMENT',getCurrentControllerPath('index')); ?>" class="btn btn-sm btn-primary pull-right">Back</a>
              </div>
              <div class="card-body">
                <div class="basic-login-inner">
                  <form id="currentPageForm" name="currentPageForm" class="form-auth-small" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="paymentmode_id"/>
                    <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?=$EDITDATA['paymentmode_id']?>"/>
                    <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['paymentmode_id']?>"/>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                  
                    <fieldset>
                      <legend>Web</legend>
                      <div class="row">

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('title_stripe')): ?>error<?php endif; ?>">
                          <label>Title (Stripe)</label>
                          <input type="text" name="title_stripe" id="title_stripe" class="form-control" value="<?=$EDITDATA['title_stripe']?>"/>
                          <?php if(form_error('title_stripe')): ?>
                            <span for="title_stripe" generated="true" class="help-inline"><?php echo form_error('title_stripe'); ?></span>
                          <?php endif; ?>
                        </div>
                        
                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('stripe')): ?>error<?php endif; ?>">
                          <label>Enable Stripe</label>
                          <select name="stripe" id="stripe" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['stripe'] == 'enable'): echo 'selected'; endif; ?> >Stripe</option>
                          </select>
                          <?php if(form_error('stripe')): ?>
                            <span for="stripe" generated="true" class="help-inline"><?php echo form_error('stripe'); ?></span>
                          <?php endif; ?>
                        </div>

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('title_telr')): ?>error<?php endif; ?>">
                          <label>Title (Telr)</label>
                          <input type="text" name="title_telr" id="title_telr" class="form-control" value="<?=$EDITDATA['title_telr']?>"/>
                          <?php if(form_error('title_telr')): ?>
                            <span for="title_telr" generated="true" class="help-inline"><?php echo form_error('title_telr'); ?></span>
                          <?php endif; ?>
                        </div>
                       
                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('telr')): ?>error<?php endif; ?>">
                          <label>Enable Telr</label>
                          <select name="telr" id="telr" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['telr'] == 'enable'): echo 'selected'; endif; ?>  >Telr</option>
                          </select>
                          <?php if(form_error('telr')): ?>
                            <span for="telr" generated="true" class="help-inline"><?php echo form_error('telr'); ?></span>
                          <?php endif; ?>
                        </div>

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('title_noon')): ?>error<?php endif; ?>">
                          <label>Title (Noon)</label>
                          <input type="text" name="title_noon" id="title_noon" class="form-control" value="<?=$EDITDATA['title_noon']?>"/>
                          <?php if(form_error('title_noon')): ?>
                            <span for="title_noon" generated="true" class="help-inline"><?php echo form_error('title_noon'); ?></span>
                          <?php endif; ?>
                        </div>
                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('noon')): ?>error<?php endif; ?>">
                          <label>Enable Noon</label>
                          <select name="noon" id="noon" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['noon'] == 'enable'): echo 'selected'; endif; ?>  >Noon</option>
                          </select>
                          <?php if(form_error('noon')): ?>
                            <span for="noon" generated="true" class="help-inline"><?php echo form_error('noon'); ?></span>
                          <?php endif; ?>
                        </div>

                         <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('title_ngenius')): ?>error<?php endif; ?>">
                          <label>Title (Ngenius)</label>
                          <input type="text" name="title_ngenius" id="title_ngenius" class="form-control" value="<?=$EDITDATA['title_ngenius']?>"/>
                          <?php if(form_error('title_ngenius')): ?>
                            <span for="title_ngenius" generated="true" class="help-inline"><?php echo form_error('title_ngenius'); ?></span>
                          <?php endif; ?>
                        </div>
                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('ngenius')): ?>error<?php endif; ?>">
                          <label>Enable ngenius</label>
                          <select name="ngenius" id="ngenius" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['ngenius'] == 'enable'): echo 'selected'; endif; ?>  >Ngenius</option>
                          </select>
                          <?php if(form_error('ngenius')): ?>
                            <span for="ngenius" generated="true" class="help-inline"><?php echo form_error('ngenius'); ?></span>
                          <?php endif; ?>
                        </div>
                      </div>
                    </fieldset>
                    
                     <fieldset>
                      <legend>IOS</legend>
                      <div class="row">

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('ios_title_stripe')): ?>error<?php endif; ?>">
                          <label>Title (Stripe)</label>
                          <input type="text" name="ios_title_stripe" id="ios_title_stripe" class="form-control" value="<?=$EDITDATA['ios_title_stripe']?>"/>
                          <?php if(form_error('ios_title_stripe')): ?>
                            <span for="ios_title_stripe" generated="true" class="help-inline"><?php echo form_error('ios_title_stripe'); ?></span>
                          <?php endif; ?>
                        </div>
                        
                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('ios_stripe')): ?>error<?php endif; ?>">
                          <label>Enable Stripe</label>
                          <select name="ios_stripe" id="ios_stripe" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['ios_stripe'] == 'enable'): echo 'selected'; endif; ?> >ios_stripe</option>
                          </select>
                          <?php if(form_error('ios_stripe')): ?>
                            <span for="ios_stripe" generated="true" class="help-inline"><?php echo form_error('ios_stripe'); ?></span>
                          <?php endif; ?>
                        </div>

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('ios_title_telr')): ?>error<?php endif; ?>">
                          <label>Title (Telr)</label>
                          <input type="text" name="ios_title_telr" id="ios_title_telr" class="form-control" value="<?=$EDITDATA['ios_title_telr']?>"/>
                          <?php if(form_error('ios_title_telr')): ?>
                            <span for="ios_title_telr" generated="true" class="help-inline"><?php echo form_error('ios_title_telr'); ?></span>
                          <?php endif; ?>
                        </div>
                       
                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('ios_telr')): ?>error<?php endif; ?>">
                          <label>Enable Telr</label>
                          <select name="ios_telr" id="ios_telr" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['ios_telr'] == 'enable'): echo 'selected'; endif; ?>  >ios_telr</option>
                          </select>
                          <?php if(form_error('ios_telr')): ?>
                            <span for="ios_telr" generated="true" class="help-inline"><?php echo form_error('ios_telr'); ?></span>
                          <?php endif; ?>
                        </div>

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('ios_title_noon')): ?>error<?php endif; ?>">
                          <label>Title (Noon)</label>
                          <input type="text" name="ios_title_noon" id="ios_title_noon" class="form-control" value="<?=$EDITDATA['ios_title_noon']?>"/>
                          <?php if(form_error('ios_title_noon')): ?>
                            <span for="ios_title_noon" generated="true" class="help-inline"><?php echo form_error('ios_title_noon'); ?></span>
                          <?php endif; ?>
                        </div>

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('ios_noon')): ?>error<?php endif; ?>">
                          <label>Enable Noon</label>
                          <select name="ios_noon" id="ios_noon" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['ios_noon'] == 'enable'): echo 'selected'; endif; ?>  >ios_noon</option>
                          </select>
                          <?php if(form_error('ios_noon')): ?>
                            <span for="ios_noon" generated="true" class="help-inline"><?php echo form_error('ios_noon'); ?></span>
                          <?php endif; ?>
                        </div>

                         <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('ios_title_ngenius')): ?>error<?php endif; ?>">
                          <label>Title (Ngenius)</label>
                          <input type="text" name="ios_title_ngenius" id="ios_title_ngenius" class="form-control" value="<?=$EDITDATA['ios_title_ngenius']?>"/>
                          <?php if(form_error('ios_title_ngenius')): ?>
                            <span for="ios_title_ngenius" generated="true" class="help-inline"><?php echo form_error('ios_title_ngenius'); ?></span>
                          <?php endif; ?>
                        </div>

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('ios_ngenius')): ?>error<?php endif; ?>">
                          <label>Enable Ngenius</label>
                          <select name="ios_ngenius" id="ios_ngenius" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['ios_ngenius'] == 'enable'): echo 'selected'; endif; ?>  >ios_ngenius</option>
                          </select>
                          <?php if(form_error('ios_ngenius')): ?>
                            <span for="ios_ngenius" generated="true" class="help-inline"><?php echo form_error('ios_ngenius'); ?></span>
                          <?php endif; ?>
                        </div>
                        
                      </div>
                    </fieldset>

                     <fieldset>
                      <legend>Android</legend>
                      <div class="row">

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('android_title_stripe')): ?>error<?php endif; ?>">
                          <label>Title (Stripe)</label>
                          <input type="text" name="android_title_stripe" id="android_title_stripe" class="form-control" value="<?=$EDITDATA['android_title_stripe']?>"/>
                          <?php if(form_error('android_title_stripe')): ?>
                            <span for="android_title_stripe" generated="true" class="help-inline"><?php echo form_error('android_title_stripe'); ?></span>
                          <?php endif; ?>
                        </div>
                        
                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('android_stripe')): ?>error<?php endif; ?>">
                          <label>Enable Stripe</label>
                          <select name="android_stripe" id="android_stripe" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['android_stripe'] == 'enable'): echo 'selected'; endif; ?> >android_stripe</option>
                          </select>
                          <?php if(form_error('android_stripe')): ?>
                            <span for="android_stripe" generated="true" class="help-inline"><?php echo form_error('android_stripe'); ?></span>
                          <?php endif; ?>
                        </div>

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('android_title_telr')): ?>error<?php endif; ?>">
                          <label>Title (Telr)</label>
                          <input type="text" name="android_title_telr" id="android_title_telr" class="form-control" value="<?=$EDITDATA['android_title_telr']?>"/>
                          <?php if(form_error('android_title_telr')): ?>
                            <span for="android_title_telr" generated="true" class="help-inline"><?php echo form_error('android_title_telr'); ?></span>
                          <?php endif; ?>
                        </div>
                       
                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('android_telr')): ?>error<?php endif; ?>">
                          <label>Enable Telr</label>
                          <select name="android_telr" id="android_telr" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['android_telr'] == 'enable'): echo 'selected'; endif; ?>  >android_telr</option>
                          </select>
                          <?php if(form_error('android_telr')): ?>
                            <span for="android_telr" generated="true" class="help-inline"><?php echo form_error('android_telr'); ?></span>
                          <?php endif; ?>
                        </div>

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('android_title_noon')): ?>error<?php endif; ?>">
                          <label>Title (Noon)</label>
                          <input type="text" name="android_title_noon" id="android_title_noon" class="form-control" value="<?=$EDITDATA['android_title_noon']?>"/>
                          <?php if(form_error('android_title_noon')): ?>
                            <span for="android_title_noon" generated="true" class="help-inline"><?php echo form_error('android_title_noon'); ?></span>
                          <?php endif; ?>
                        </div>

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('android_noon')): ?>error<?php endif; ?>">
                          <label>Enable Noon</label>
                          <select name="android_noon" id="android_noon" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['android_noon'] == 'enable'): echo 'selected'; endif; ?>  >android_noon</option>
                          </select>
                          <?php if(form_error('android_noon')): ?>
                            <span for="android_noon" generated="true" class="help-inline"><?php echo form_error('android_noon'); ?></span>
                          <?php endif; ?>
                        </div>

                         <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('android_title_ngenius')): ?>error<?php endif; ?>">
                          <label>Title (Ngenius)</label>
                          <input type="text" name="android_title_ngenius" id="android_title_ngenius" class="form-control" value="<?=$EDITDATA['android_title_ngenius']?>"/>
                          <?php if(form_error('android_title_ngenius')): ?>
                            <span for="android_title_ngenius" generated="true" class="help-inline"><?php echo form_error('android_title_ngenius'); ?></span>
                          <?php endif; ?>
                        </div>

                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if(form_error('android_ngenius')): ?>error<?php endif; ?>">
                          <label>Enable Ngenius</label>
                          <select name="android_ngenius" id="android_ngenius" class="form-control" >
                            <option value="disable">Disabled</option>
                            <option value="enable" <?php if($EDITDATA['android_ngenius'] == 'enable'): echo 'selected'; endif; ?>  >android_ngenius</option>
                          </select>
                          <?php if(form_error('android_ngenius')): ?>
                            <span for="android_ngenius" generated="true" class="help-inline"><?php echo form_error('android_ngenius'); ?></span>
                          <?php endif; ?>
                        </div>

                      </div>
                    </fieldset>


                    <div class="row">
                      <div class="login-btn-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="inline-remember-me mt-4">
                          <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                          <button class="btn btn-primary mb-4">Submit</button>
                          <a href="<?php echo correctLink('CMSPRIVACYPOLICY',getCurrentControllerPath('index')); ?>" class="btn btn-danger has-ripple mb-4">Cancel</a>
                          <span class="tools pull-right">Note:- <strong><span style="color:#FF0000;">*</span> Indicates Required Fields</strong> </span> 
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
<script type="text/javascript">
  $(function(){create_editor_for_textarea('description')});
</script>