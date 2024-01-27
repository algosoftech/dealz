<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <?php /* ?>
                            <h5 class="m-b-10">Welcome <?=sessionData('HCAP_ADMIN_FIRST_NAME')?></h5>
                            <?php */ ?>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo getCurrentDashboardPath('dashboard/index'); ?>"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo correctLink('ALLLOTOPRODUCTSDATA',getCurrentControllerPath('index')); ?>"> Product</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);"><?=$EDITDATA?'Edit':'Add'?> Lotto Settings</a></li>
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
                        <h5><?=$EDITDATA?'Edit':'Add'?> Lotto Product</h5>
                        <a href="<?php echo correctLink('ALLLOTOPRODUCTSDATA',getCurrentControllerPath('index')); ?>" class="btn btn-sm btn-primary pull-right">Back</a>
                    </div>
                    <div class="card-body">
                            <form id="currentPageForm" name="currentPageForm" class="form-auth-small" method="post" action="" enctype="multipart/form-data">
                                <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="products_id"/>
                                <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?=$EDITDATA['products_id']?>"/>
                                <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['products_id']?>"/>
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                              
                            <div class="row">
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <fieldset>
                                        <legend>Enable/Disable Option</legend>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 <?php if(form_error('straight_add_on_amount')): ?>error<?php endif; ?>">
                                                <label> Straight (iPoints) <span class="required">*</span></label>
                                                <select name="straight_add_on_amount" id="straight_add_on_amount" class="form-control required">
                                                    <option>Show</option>
                                                    <option>Hide</option>
                                                </select>
                                                <?php if(form_error('straight_add_on_amount')): ?>
                                                  <span for="straight_add_on_amount" generated="true" class="help-inline"><?php echo form_error('straight_add_on_amount'); ?></span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 <?php if(form_error('rumble_add_on_amount')): ?>error<?php endif; ?>">
                                                <label> Rumble Mix (iPoints) <span class="required">*</span></label>
                                                <select name="rumble_add_on_amount" id="rumble_add_on_amount" class="form-control required">
                                                    <option>Show</option>
                                                    <option>Hide</option>
                                                </select>
                                                <?php if(form_error('rumble_add_on_amount')): ?>
                                                  <span for="rumble_add_on_amount" generated="true" class="help-inline"><?php echo form_error('rumble_add_on_amount'); ?></span>
                                                <?php endif; ?>
                                            </div>

                                             <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 <?php if(form_error('reverse_add_on_amount')): ?>error<?php endif; ?>">
                                                <label> Chance ( iPoints ) <span class="required">*</span></label>
                                                <select name="reverse_add_on_amount" id="reverse_add_on_amount" class="form-control required">
                                                    <option>Show</option>
                                                    <option>Hide</option>
                                                </select>
                                                <?php if(form_error('reverse_add_on_amount')): ?>
                                                  <span for="reverse_add_on_amount" generated="true" class="help-inline"><?php echo form_error('reverse_add_on_amount'); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </fieldset>
                                  </div>
                            </div>      
                                
                            <div class="row">
                                <div class="login-btn-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="inline-remember-me mt-4">
                                        <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                                        <button class="btn btn-primary mb-4">Submit</button>
                                        <a href="<?php echo correctLink('ALLLOTOPRODUCTSDATA',getCurrentControllerPath('index')); ?>" class="btn btn-danger has-ripple mb-4">Cancel</a>
                                        <span class="tools pull-right">Note:- <strong><span style="color:#FF0000;">*</span> Indicates Required Fields</strong> </span> 
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
    </div>
</div>
<!-- [ Main Content ] end -->
</div>
</div>