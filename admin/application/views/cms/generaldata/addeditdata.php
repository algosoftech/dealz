<link rel="stylesheet" href="{ASSET_INCLUDE_URL}canvasCrop/about.image.canvasCrop.css">
<script type="text/javascript" src="{ASSET_INCLUDE_URL}canvasCrop/jquery.canvasCrop.js"></script>
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
                            <li class="breadcrumb-item"><a href="<?php echo correctLink('CMSGENERALDATA',getCurrentControllerPath('index')); ?>">Website</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);"><?=$EDITDATA?'Edit':'Add'?> General Data</a></li>
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
                <h5><?=$EDITDATA?'Edit':'Add'?> General Data</h5>
                <a href="<?php echo correctLink('CMSGENERALDATA',getCurrentControllerPath('index')); ?>" class="btn btn-sm btn-primary pull-right">Back</a>
              </div>
              <div class="card-body">
                <div class="basic-login-inner">
                  <form id="currentPageForm" name="currentPageForm" class="form-auth-small" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="general_data_id"/>
                    <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?=$EDITDATA['general_data_id']?>"/>
                    <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['general_data_id']?>"/>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <fieldset>
                      <legend>General Data</legend>
                     
                      <div class="row">
                      <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('image')): ?>error<?php endif; ?>">
                             <label> Logo <span class="required"> </span></label><br>
                        <input type="file" name="image" class="image"><br>
                        <?php if($EDITDATA['logo']): ?>
                           <div id="ImageDiv"><img src="<?php echo fileBaseUrl.$EDITDATA['logo']; ?>" width="50" border="0" alt="">&nbsp;
                           <a href="javascript:void(0);" onclick="ImageDelete('<?php echo $EDITDATA['logo']; ?>','<?php echo $EDITDATA['general_data_id']; ?>');"> 
                              <img src="{ASSET_INCLUDE_URL}images/cross.png" border="0" alt="">
                            </a></div>
                          <?php endif; ?>
                        <?php if(form_error('image')): ?>
                          <span for="image" generated="true" class="help-inline"><?php echo form_error('image'); ?></span>
                        <?php endif; ?>
                        </div>
                        <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('alt_text')): ?>error<?php endif; ?>">
                        <label>Alt Text<span class="required">*</span></label>
                        <input type="text" name="alt_text" id="alt_text" value="<?php if(set_value('alt_text')): echo set_value('alt_text'); else: echo stripslashes($EDITDATA['alt_text']);endif; ?>" class="form-control required" placeholder="Alt Text">
                        <?php if(form_error('alt_text')): ?>
                          <span for="alt_text" generated="true" class="help-inline"><?php echo form_error('alt_text'); ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                        <div class="row">
                          <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('email_id')): ?>error<?php endif; ?>">
                            <label>Email<span class="required">*</span></label>
                            <input type="text" name="email_id" id="email_id" value="<?php if(set_value('email_id')): echo set_value('email_id'); else: echo stripslashes($EDITDATA['email_id']);endif; ?>" class="form-control email required" placeholder="Email">
                            <?php if(form_error('email_id')): ?>
                              <span for="email_id" generated="true" class="help-inline"><?php echo form_error('email_id'); ?></span>
                            <?php endif; ?>
                          </div>
                          <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('contact_no')): ?>error<?php endif; ?>">
                            <label>Contact<span class="required">*</span></label>
                            <input type="text" name="contact_no" id="contact_no" value="<?php if(set_value('contact_no')): echo set_value('contact_no'); else: echo stripslashes($EDITDATA['contact_no']);endif; ?>" class="form-control number required" placeholder="Contact">
                            <?php if(form_error('contact_no')): ?>
                              <span for="contact_no" generated="true" class="help-inline"><?php echo form_error('contact_no'); ?></span>
                            <?php endif; ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if(form_error('address')): ?>error<?php endif; ?>">
                            <label>Address<span class="required">*</span></label>
                            <textarea id="address" name="address" class=" form-control required" rows="4"><?php if(set_value('address')): echo set_value('address'); else: echo stripslashes($EDITDATA['address']);endif; ?></textarea>
                            <?php if(form_error('address')): ?>
                              <span for="address" generated="true" class="help-inline"><?php echo form_error('address'); ?></span>
                            <?php endif; ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('facebook_link')): ?>error<?php endif; ?>">
                            <label>Facebook Link<span class="required">*</span></label>
                            <input type="text" name="facebook_link" id="facebook_link" value="<?php if(set_value('facebook_link')): echo set_value('facebook_link'); else: echo stripslashes($EDITDATA['facebook_link']);endif; ?>" class="form-control url required" placeholder="Facebook Link">
                            <?php if(form_error('facebook_link')): ?>
                              <span for="facebook_link" generated="true" class="help-inline"><?php echo form_error('facebook_link'); ?></span>
                            <?php endif; ?>
                          </div>
                          <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('linkedin_link')): ?>error<?php endif; ?>">
                            <label>Linkedin Link<span class="required">*</span></label>
                            <input type="text" name="linkedin_link" id="linkedin_link" value="<?php if(set_value('linkedin_link')): echo set_value('linkedin_link'); else: echo stripslashes($EDITDATA['linkedin_link']);endif; ?>" class="form-control url required" placeholder="Linkedin Link">
                            <?php if(form_error('linkedin_link')): ?>
                              <span for="linkedin_link" generated="true" class="help-inline"><?php echo form_error('linkedin_link'); ?></span>
                            <?php endif; ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('twitter_link')): ?>error<?php endif; ?>">
                            <label>Twitter Link<span class="required">*</span></label>
                            <input type="text" name="twitter_link" id="twitter_link" value="<?php if(set_value('twitter_link')): echo set_value('twitter_link'); else: echo stripslashes($EDITDATA['twitter_link']);endif; ?>" class="form-control url required" placeholder="Twitter Link">
                            <?php if(form_error('twitter_link')): ?>
                              <span for="twitter_link" generated="true" class="help-inline"><?php echo form_error('twitter_link'); ?></span>
                            <?php endif; ?>
                          </div>
                          <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('insta_link')): ?>error<?php endif; ?>">
                            <label>Instagram Link<span class="required">*</span></label>
                            <input type="text" name="insta_link" id="insta_link" value="<?php if(set_value('insta_link')): echo set_value('insta_link'); else: echo stripslashes($EDITDATA['insta_link']);endif; ?>" class="form-control url required" placeholder="Twitter Link">
                            <?php if(form_error('insta_link')): ?>
                              <span for="insta_link" generated="true" class="help-inline"><?php echo form_error('insta_link'); ?></span>
                            <?php endif; ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('you_tube')): ?>error<?php endif; ?>">
                            <label>You Tube<span class="required">*</span></label>
                            <input type="text" name="you_tube" id="you_tube" value="<?php if(set_value('you_tube')): echo set_value('you_tube'); else: echo stripslashes($EDITDATA['you_tube']);endif; ?>" class="form-control url required" placeholder="YouTube Link">
                            <?php if(form_error('you_tube')): ?>
                              <span for="you_tube" generated="true" class="help-inline"><?php echo form_error('you_tube'); ?></span>
                            <?php endif; ?>
                          </div>
                          <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('slider_type')): ?>error<?php endif; ?>">
                            <label>Slider Type<span class="required">*</span></label>
                            <select name="slider_type" id="slider_type" class="form-control required">
                              <option>Select Slider Type</option>
                              <option value="Video" <?php if($EDITDATA['slider_type'] == 'Video'): echo 'selected'; endif; ?> >Video</option>
                              <option value="Image" <?php if($EDITDATA['slider_type'] == 'Image'): echo 'selected'; endif; ?>>Image</option>
                            </select>
                            <!-- <input type="text" name="slider_type" id="slider_type" value="<?php if(set_value('slider_type')): echo set_value('slider_type'); else: echo stripslashes($EDITDATA['slider_type']);endif; ?>" class="form-control url required" placeholder="Slider Type"> -->
                            <?php if(form_error('slider_type')): ?>
                              <span for="slider_type" generated="true" class="help-inline"><?php echo form_error('slider_type'); ?></span>
                            <?php endif; ?>
                          </div>

                           <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('whatsapp_no')): ?><?php endif; ?>">
                            <label>WhatsApp Number<span class="required">*</span></label>
                            <input type="text" name="whatsapp_no" id="whatsapp_no" value="<?php if(set_value('whatsapp_no')): echo set_value('whatsapp_no'); else: echo stripslashes($EDITDATA['whatsapp_no']);endif; ?>" class="form-control valid required" placeholder="WhatsApp Number">
                            <?php if(form_error('whatsapp_no')): ?>
                              <span for="whatsapp_no" generated="true" class="help-inline"><?php echo form_error('whatsapp_no'); ?></span>
                            <?php endif; ?>
                          </div>

                          <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('android_version')): ?><?php endif; ?>">
                            <label>Android Version <span class="required">*</span></label>
                            <input type="text" name="android_version" id="android_version" value="<?php if(set_value('android_version')): echo set_value('android_version'); else: echo stripslashes($EDITDATA['android_version']);endif; ?>" class="form-control valid required" placeholder="Appication Version">
                            <?php if(form_error('android_version')): ?>
                              <span for="android_version" generated="true" class="help-inline"><?php echo form_error('android_version'); ?></span>
                            <?php endif; ?>
                          </div>

                          <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('ios_version')): ?><?php endif; ?>">
                            <label>IOS Version <span class="required">*</span></label>
                            <input type="text" name="ios_version" id="ios_version" value="<?php if(set_value('ios_version')): echo set_value('ios_version'); else: echo stripslashes($EDITDATA['ios_version']);endif; ?>" class="form-control valid required" placeholder="Appication Version">
                            <?php if(form_error('ios_version')): ?>
                              <span for="ios_version" generated="true" class="help-inline"><?php echo form_error('ios_version'); ?></span>
                            <?php endif; ?>
                          </div>


                        </div>
                         
                    </fieldset>
                   <div class="row">
                      <div class="login-btn-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="inline-remember-me mt-4">
                          <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                          <button class="btn btn-primary mb-4">Submit</button>
                          <a href="<?php echo correctLink('CMSGENERALDATA',getCurrentControllerPath('index')); ?>" class="btn btn-danger has-ripple mb-4">Cancel</a>
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
  $(function(){create_editor_for_textarea('contact_description1')});
      $(function(){create_editor_for_textarea('address')});
      $(function(){create_editor_for_textarea('description2')});

</script>
<script>

  function VideoDelete(imageName,id)
  {//alert(id);
    if(confirm("Sure to delete?"))
    {//alert(CURRENTCLASS);
      $.ajax({
            type: 'post',
             url: FULLSITEURL+'website/'+CURRENTCLASS+'/videoDelete',
            data: {imageName:imageName,id,id},
         success: function(rdata) { 
              if(parseInt(rdata.status) == 1) {
                $('#image').val('');
                $('#videoDiv').html('');
              }
              return false;
            }
      });
    }
  }
</script>
<script>

  function ImageDelete(imageName,id)
  {//alert(id);
    if(confirm("Sure to delete?"))
    {//alert(CURRENTCLASS);
      $.ajax({
            type: 'post',
             url: FULLSITEURL+'website/'+CURRENTCLASS+'/imageDelete',
            data: {imageName:imageName,id,id},
         success: function(rdata) { 
              if(parseInt(rdata.status) == 1) {
                $('#image').val('');
                $('#ImageDiv').html('');
              }
              return false;
            }
      });
    }
  }
</script>