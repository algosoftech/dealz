<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
    $(function(){
     $("#date").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
 });
</script>
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
                            <li class="breadcrumb-item"><a href="<?php echo correctLink('ALLPRODUCTSDATA',getCurrentControllerPath('index')); ?>"> Product</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);"><?=$EDITDATA?'Edit':'Add'?> Product</a></li>
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
                        <h5><?=$EDITDATA?'Edit':'Add'?> Product</h5>
                        <a href="<?php echo correctLink('ALLPRODUCTSDATA',getCurrentControllerPath('index')); ?>" class="btn btn-sm btn-primary pull-right">Back</a>
                    </div>
                    <div class="card-body">
                        <div class="basic-login-inner">
                            <form id="currentPageForm" name="currentPageForm" class="form-auth-small" method="post" action="" enctype="multipart/form-data">
                                <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="products_id"/>
                                <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?=$EDITDATA['products_id']?>"/>
                                <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['products_id']?>"/>
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                                <div class="row">
                                  <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('category_id')): ?>error<?php endif; ?>">
                                    <label>Category<span class="required">*</span></label>
                                    <?php if(set_value('category_id')): $categoryiddata = explode('_____',set_value('category_id')); $category_id = $categoryiddata[0]; elseif($EDITDATA['category_id']): $category_id = stripslashes($EDITDATA['category_id']); else: $category_id = ''; endif; ?>
                                    <select name="category_id" id="category_id" class="form-control required">
                                      <?php echo $this->admin_model->getCategoryList($category_id); ?>
                                  </select>
                                  <?php if(form_error('category_id')): ?>
                                      <span for="category_id" generated="true" class="help-inline"><?php echo form_error('category_id'); ?></span>
                                  <?php endif; ?>
                              </div>
                              <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('sub_category_id')): ?>error<?php endif; ?>">
                                  <label>Sub Category<span class="required">*</span></label>
                                  <select id="sub_category_data" name="sub_category_id" class="form-control required">
                                   <option value="">Select sub_category</option>
                               </select>

                               <?php if(form_error('sub_category_id')): ?>
                                  <span for="sub_category_id" generated="true" class="help-inline"><?php echo form_error('sub_category_id'); ?></span>
                              <?php endif; ?>
                          </div>
                      </div>

                      <div class="row">
                        <div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if(form_error('title')): ?>error<?php endif; ?>">
                            <label>Title<span class="required">*</span></label>
                            <input type="text" name="title" id="title" class="form-control required" value="<?php if(set_value('title')): echo set_value('title'); else: echo stripslashes($EDITDATA['title']);endif; ?>" placeholder="Title">
                            <?php if(form_error('title')): ?>
                                <span for="title" generated="true" class="help-inline"><?php echo form_error('title'); ?></span>
                            <?php endif; ?>
                        </div>


                    </div>
                    <div class="row">
                        <div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if(form_error('Description')): ?>error<?php endif; ?>">
                            <label>Description</label>
                            <textarea id="description" placeholder="Description" class="form-control" name="description"><?php if(set_value('description')): echo set_value('description'); else: echo stripslashes($EDITDATA['description']);endif; ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                      <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('product_image')): ?>error<?php endif; ?>">
                        <label>Image</label><br>
                        <input type="file" name="product_image" id="product_image" class="" value="<?php if(set_value('product_image')): echo set_value('product_image'); endif; ?>" accept="image/png, image/jpeg, image/webp" <?php if(empty($EDITDATA['product_image'])){ ?> required <?php } ?> >
                        <p style="font-family:italic; color:red;">[Image Size : 241 x 136 px in jpg/jpeg/png]</p>
                        <?php if($EDITDATA['product_image']): ?>
                         <div id="ImageDiv2"><img src="<?php echo fileBaseUrl.$EDITDATA['product_image']; ?>" width="50" border="0" alt="">&nbsp;
                             <a href="javascript:void(0);" onclick="ImageDelete('<?php echo $EDITDATA['product_image']; ?>','<?php echo $EDITDATA['shop_category_id']; ?>','app');"> 
                              <img src="{ASSET_INCLUDE_URL}image/cross.png" border="0" alt="">
                          </a></div>
                      <?php endif; ?>
                      <?php if(form_error('product_image')): ?>
                          <span for="product_image" generated="true" class="help-inline"><?php echo form_error('product_image'); ?></span>
                      <?php endif; ?>
                  </div>
                  <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(form_error('product_image_alt')): ?>error<?php endif; ?>">
                    <label>Alt Text</label>
                    <input type="text" name="product_image_alt" id="product_image_alt" class="form-control" value="<?php if(set_value('product_image_alt')): echo set_value('product_image_alt'); else: echo stripslashes($EDITDATA['product_image_alt']);endif; ?>" placeholder="Alt Text">
                    <?php if(form_error('product_image_alt')): ?>
                      <span for="product_image_alt" generated="true" class="help-inline"><?php echo form_error('product_image_alt'); ?></span>
                  <?php endif; ?>
              </div>
          </div>

          <div class="row">
            <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-12 <?php if(form_error('stock')): ?>error<?php endif; ?>">
                <label>Quantity<span class="required">*</span></label>
                <input type="number" min="0" name="stock" id="stock" class="form-control required" value="<?php if(set_value('stock')): echo set_value('stock'); else: echo stripslashes($EDITDATA['stock']);endif; ?>" placeholder="Quantity" <?php if(($EDITDATA['stock'])){ ?>readonly <?php } ?>>
                <?php if(form_error('stock')): ?>
                    <span for="stock" generated="true" class="help-inline"><?php echo form_error('stock'); ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-12 <?php if(form_error('target_stock')): ?>error<?php endif; ?>">
                <label>Target Quantity<span class="required">*</span></label>
                <input type="number" min="0" name="target_stock" id="target_stock" class="form-control required" value="<?php if(set_value('target_stock')): echo set_value('target_stock'); else: echo stripslashes($EDITDATA['target_stock']);endif; ?>" placeholder="Target Quantity"   <?php if(($EDITDATA['target_stock'])){ ?>readonly <?php } ?>>
                <?php if(form_error('target_stock')): ?>
                    <span for="target_stock" generated="true" class="help-inline"><?php echo form_error('target_stock'); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-12 <?php if(form_error('stock')): ?>error<?php endif; ?>">
                <label>SA / iPoints<span class="required">*</span></label>
                <input type="number" min="0" name="points" id="points" class="form-control required" value="<?php if(set_value('points')): echo set_value('points'); else: echo stripslashes($EDITDATA['points']);endif; ?>" placeholder="sa_ / iPoints">
                <?php if(form_error('points')): ?>
                    <span for="points" generated="true" class="help-inline"><?php echo form_error('points'); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php if(form_error('commingSoon')): ?>error<?php endif; ?>">
                <label>Coming Soon</label>
                <select name="commingSoon" id="commingSoon" class="form-control required">
                    <option hidden>Select Coming Soon</option>
                    <?php if(stripslashes($EDITDATA['commingSoon']) == 'Y'){ ?>
                        <option value="Y" hidden selected>Yes</option>
                    <?php }else{?>
                        <option value="N" hidden selected>No</option>
                    <?php }?>
                    <option value="Y" >Yes</option>
                    <option value="N">No</option>
                </select>
                <?php if(form_error('commingSoon')): ?>
                    <span for="commingSoon" generated="true" class="help-inline"><?php echo form_error('commingSoon'); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php if(form_error('clossingSoon')): ?>error<?php endif; ?>">
                <label>Closing Soon Category</label>
                <select name="clossingSoon" id="clossingSoon" class="form-control required">
                    <option hidden>Select Closing Soon Category</option>
                    <?php if(stripslashes($EDITDATA['clossingSoon']) == 'Y'){ ?>
                        <option value="Y" hidden selected>Yes</option>
                    <?php }else{?>
                        <option value="N" hidden selected>No</option>
                    <?php }?>
                    <option value="Y" >Yes</option>
                    <option value="N">No</option>
                </select>
                <?php if(form_error('clossingSoon')): ?>
                    <span for="clossingSoon" generated="true" class="help-inline"><?php echo form_error('clossingSoon'); ?></span>
                <?php endif; ?>
            </div>




        </div>

        <div class="row">

            <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-12 <?php if(form_error('soldout_status')): ?>error<?php endif; ?>">
                <label>Show Sold Status</label>
                <select name="soldout_status" id="soldout_status" class="form-control required">
                    <option hidden>Select Sold Status</option>
                    <?php if(stripslashes($EDITDATA['soldout_status']) == 'Y'){ ?>
                        <option value="Y" hidden selected>Yes</option>
                    <?php }else{?>
                        <option value="N" hidden selected>No</option>
                    <?php }?>
                    <option value="Y" >Yes</option>
                    <option value="N">No</option>
                </select>
                <?php if(form_error('soldout_status')): ?>
                    <span for="soldout_status" generated="true" class="help-inline"><?php echo form_error('soldout_status'); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php if(form_error('sale_percentage')): ?>error<?php endif; ?>">
                <label>Campaigns Soldout Percentage<span class="required"></span></label>
                <input type="number" name="sale_percentage" id="sale_percentage" class="form-control" min="0" max="100" value="<?php if(set_value('sale_percentage')): echo set_value('sale_percentage'); else: echo stripslashes($EDITDATA['sale_percentage']);endif; ?>">
                <?php if(form_error('sale_percentage')): ?>
                    <span for="sale_percentage" generated="true" class="help-inline"><?php echo form_error('sale_percentage'); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php if(form_error('sale_percentage_final')): ?>error<?php endif; ?>">
                <label>Campaigns Soldout Percentage Final<span class="required"></span></label>
                <input type="number" name="sale_percentage_final" id="sale_percentage_final" class="form-control" min="0" max="100" value="<?php if(set_value('sale_percentage_final')): echo set_value('sale_percentage_final'); else: echo stripslashes($EDITDATA['sale_percentage_final']);endif; ?>">
                <?php if(form_error('sale_percentage_final')): ?>
                    <span for="sale_percentage_final" generated="true" class="help-inline"><?php echo form_error('sale_percentage_final'); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php if(form_error('countdown_status')): ?>error<?php endif; ?>">
                <label>countdown Show/Hide</label>
                <select name="countdown_status" id="countdown_status" class="form-control required">

                    <?php if(stripslashes($EDITDATA['countdown_status']) == 'Y'){ ?>
                        <option value="Y" hidden selected>Yes</option>
                    <?php }else{?>
                        <option value="N" hidden selected>No</option>
                    <?php }?>
                    <option value="Y" >Yes</option>
                    <option value="N">No</option>
                </select>
                <?php if(form_error('countdown_status')): ?>
                    <span for="countdown_status" generated="true" class="help-inline"><?php echo form_error('countdown_status'); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-12 <?php if(form_error('validuptodate')): ?>error<?php endif; ?>">
                <label>Valid Upto Date<span class="required">*</span></label>
                <input type="date" name="validuptodate" id="validuptodate" class="form-control required" value="<?php if(set_value('validuptodate')): echo set_value('validuptodate'); else: echo stripslashes($EDITDATA['validuptodate']);endif; ?>">
                <?php if(form_error('validuptodate')): ?>
                    <span for="validuptodate" generated="true" class="help-inline"><?php echo form_error('validuptodate'); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-12 <?php if(form_error('validuptotime')): ?>error<?php endif; ?>">
                <label>Valid Upto Time<span class="required">*</span></label>
                <input type="time" name="validuptotime" id="validuptotime" class="form-control required" value="<?php if(set_value('validuptotime')): echo set_value('validuptotime'); else: echo stripslashes($EDITDATA['validuptotime']);endif; ?>">
                <?php if(form_error('validuptotime')): ?>
                    <span for="validuptotime" generated="true" class="help-inline"><?php echo form_error('validuptotime'); ?></span>
                <?php endif; ?>
            </div>

        </div>

        <div class="row">
            <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-12 <?php if(form_error('draw_date')): ?>error<?php endif; ?>">
                <label>Campaigns closing<span class="required">*</span></label>
                <select name="is_show_closing" id="is_show_closing" class="form-control required">
                    <?php if(stripslashes($EDITDATA['is_show_closing']) == 'Hide'){ ?>
                        <option value="Hide" hidden selected>Hide</option>
                    <?php }else{?>
                        <option value="Show" hidden selected>Show</option>
                    <?php }?>
                    <option value="Show" >Show</option>
                    <option value="Hide">Hide</option>
                </select>

                <?php if(form_error('is_show_closing')): ?>
                    <span for="is_show_closing" generated="true" class="help-inline"><?php echo form_error('is_show_closing'); ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php if(form_error('draw_date')): ?>error<?php endif; ?>">
                <label>Campaigns Draw Date<span class="required">*</span></label>
                <input type="date" name="draw_date" id="draw_date" class="form-control required" value="<?php if(set_value('draw_date')): echo set_value('draw_date'); else: echo stripslashes($EDITDATA['draw_date']);endif; ?>">
                <?php if(form_error('draw_date')): ?>
                    <span for="draw_date" generated="true" class="help-inline"><?php echo form_error('draw_date'); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php if(form_error('draw_time')): ?>error<?php endif; ?>">
                <label>Campaigns Draw Time<span class="required">*</span></label>
                <input type="time" name="draw_time" id="draw_time" class="form-control required" value="<?php if(set_value('draw_time')): echo set_value('draw_time'); else: echo stripslashes($EDITDATA['draw_time']);endif; ?>">
                <?php if(form_error('draw_time')): ?>
                    <span for="draw_time" generated="true" class="help-inline"><?php echo form_error('draw_time'); ?></span>
                <?php endif; ?>
            </div> 

             <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php if(form_error('sponsored_coupon')): ?>error<?php endif; ?>">
                <label>Sponsored Coupon Count</label>
                <input type="number" min="1" name="sponsored_coupon" id="sponsored_coupon" class="form-control" value="<?php if(set_value('sponsored_coupon')): echo set_value('sponsored_coupon'); elseif($EDITDATA['sponsored_coupon']): echo stripslashes($EDITDATA['sponsored_coupon']); else: echo 1;endif; ?>" >
                <p style="font-family:italic; color:red;">[Number of coupon generated per qty in case of donate. ]</p>
                <?php if(form_error('sponsored_coupon')): ?>
                    <span for="sponsored_coupon" generated="true" class="help-inline"><?php echo form_error('sponsored_coupon'); ?></span>
                <?php endif; ?>
            </div> 

        </div>

        <div class="row">
            <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-12 <?php if(form_error('share_limit')): ?>error<?php endif; ?>">
                <label>Sequence Order</label>
                <input type="number" min="1" name="seq_order" id="seq_order" class="form-control" value="<?php if(set_value('seq_order')): echo set_value('seq_order'); elseif($EDITDATA['seq_order']): echo stripslashes($EDITDATA['seq_order']); else: echo 1;endif; ?>" placeholder="Sequence Order">
                <?php if(form_error('seq_order')): ?>
                    <span for="seq_order" generated="true" class="help-inline"><?php echo form_error('seq_order'); ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php if(form_error('share_limit')): ?>error<?php endif; ?>">
                <label>Share limit per user<span class="required">*</span></label>
                <input type="number" min="1" name="share_limit" id="share_limit" class="form-control required" value="<?php if(set_value('share_limit')): echo set_value('share_limit'); elseif($EDITDATA['share_limit']): echo stripslashes($EDITDATA['share_limit']); else: echo 1;endif; ?>" placeholder="Share limit">
                <?php if(form_error('share_limit')): ?>
                    <span for="share_limit" generated="true" class="help-inline"><?php echo form_error('share_limit'); ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php if(form_error('share_percentage_first')): ?>error<?php endif; ?>">
                <label>Share Percentage (first level)<span class="required">*</span></label>
                <input type="number" name="share_percentage_first" id="share_percentage_first" title="Share Percentage" class="form-control required" value="<?php if(set_value('share_percentage_first')): echo set_value('share_percentage_first'); elseif($EDITDATA['share_percentage_first']): echo stripslashes($EDITDATA['share_percentage_first']); else: echo 1;endif; ?>" min="0" step="0.01" placeholder="Share Percentage" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.parentNode.style.backgroundColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'inherit':'red'">
                <?php if(form_error('share_percentage_first')): ?>
                    <span for="share_percentage_first" generated="true" class="help-inline"><?php echo form_error('share_percentage_first'); ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group-inner col-lg-4 col-md-4 col-sm-4 col-xs-12 <?php if(form_error('share_percentage_second')): ?>error<?php endif; ?>">
                <label>Share Percentage (second level)<span class="required">*</span></label>                                        
                <input type="number" name="share_percentage_second" id="share_percentage_second" title="Share Percentage" class="form-control required" value="<?php if(set_value('share_percentage_second')): echo set_value('share_percentage_second'); elseif($EDITDATA['share_percentage_second']): echo stripslashes($EDITDATA['share_percentage_second']); else: echo 1;endif; ?>" min="0" step="0.01" placeholder="Share Percentage" pattern="^\d+(?:\.\d{1,2})?$" onblur="this.parentNode.style.backgroundColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'inherit':'red'">
                <?php if(form_error('share_percentage_second')): ?>
                    <span for="share_percentage_second" generated="true" class="help-inline"><?php echo form_error('share_percentage_second'); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Collor and Size Options -->
        <div class="row" >
            <div class="form-group-inner col-lg-4 col-md-4 col-sm-4 col-xs-12 <?php if(form_error('share_percentage_second')): ?>error<?php endif; ?>">
                <input type="checkbox" id="is_color" name="is_color" value="Y" <?php if(count((array)$EDITDATA['color_size_details']) != 0): echo 'checked'; endif; ?>>
                <label>Color & Size Options</label>                                        
            </div>
        </div>
        <div class="row">
            <input type="hidden" name="color_count" id="color_count" value="0">

            <fieldset id="Color_N_Size" <?php if(count((array)$EDITDATA['color_size_details']) == 0): ?> style="display:none;" <?php endif; ?> >
                <legend>Color and Size Option:</legend>
                <?php if($dataError): ?><span for="brochure" generated="true" class="help-inline"><?php echo $dataError; ?></span><?php endif; ?>

                <?php  if(set_value('TotalData')): $TotalData = set_value('TotalData'); elseif($EDITDATA['color_size_details'] <> ""): $TotalData = count($EDITDATA['color_size_details']); else: $TotalData = 1; endif; ?>
                <input type="hidden" name="TotalData" id="TotalData" value="<?php echo $TotalData; ?>" />
                <input type="hidden" name="TotalDataCount" id="TotalDataCount" value="<?php echo $TotalData; ?>" />
                <div class="col-md-12 col-sm-12 col-xs-12 padding-none" id="Datalocation">
                    <?php if(set_value('TotalData')){  
                        for($i=1; $i<= $TotalData; $i++){
                            $content_         = 'content_'.$i;
                            ?>
                            <span>
                                <div class="row">
                                    <div class="form-group-inner col-lg-10 col-md-10 col-sm-10 col-xs-10"><?php if($i > 1){ echo '<hr />'; } ?>
                                    <label><b>Select your color</b> : </label>
                                    <input type="color" class="form-group" id="color<?=$i?>" name="color0" value="<?=set_value('color'.$i)?>">

                                    <label style="margin-left: 2%;"><b>Size </b>: </label>

                                    <input type="checkbox" id="is_color" name="S<?=$i?>" value="S" <?php if(set_value('S'.$i) == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                    <label>S</label>  

                                    <input type="checkbox" id="is_color" name="M<?=$i?>" value="M" <?php if(set_value('S'.$i) == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                    <label>M</label>

                                    <input type="checkbox" id="is_color" name="L<?=$i?>" value="L" <?php if(set_value('S'.$i) == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                    <label>L</label>

                                    <input type="checkbox" id="is_color" name="XL<?=$i?>" value="XL" <?php if(set_value('S'.$i) == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                    <label>XL</label>

                                    <input type="checkbox" id="is_color" name="XXL<?=$i?>" value="XXL" <?php if(set_value('S'.$i) == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                    <label>XXL</label>

                                    <input type="checkbox" id="is_color" name="FRS<?=$i?>" value="FRS" <?php if(set_value('S'.$i) == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                    <label>Free Size</label>

                                    <label style="margin-left: 10%;"><b>Image</b> : </label>
                                    <input type="file" class="form-group required" id="color_img<?=$i?>" name="color_img<?=$i?>" > 
                                </div>
                                <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align:right;"><?php if($i > 1){ echo '<hr />'; } ?>
                                <label>&nbsp;</label>
                                <?php if($i < $TotalData){ ?>
                                    <a href="javascript:void(0);" class="removeMoreData" id="RemoveData_<?php echo $i; ?>" style="float:right;display:block;"><img src="<?php echo base_url(); ?>assets/admin/image/cross.png" alt="Remove" /></a>
                                    <a href="javascript:void(0);" class="addMoreData" id="AddData_<?php echo $i; ?>" style="float:right;display:none;"><img src="<?php echo base_url(); ?>assets/admin/images/addmore.png" alt="Add more" /></a>
                                <?php }else{ ?>
                                    <a href="javascript:void(0);" class="removeMoreData" id="RemoveData_<?php echo $i; ?>" style="float:right;display:none;"><img src="<?php echo base_url(); ?>assets/admin/images/cross.png" alt="Remove" /></a>
                                    <a href="javascript:void(0);" class="addMoreData" id="AddData_<?php echo $i; ?>" style="float:right;display:block;margin-right: 10px;"><img src="<?php echo base_url(); ?>assets/admin/image/addmore.png" alt="Add more" /></a>
                                <?php } ?>
                            </div>
                        </div>
                    </span>
                <?php }  ?>


                <!-- Edit Part start -->
            <?php  }elseif($EDITDATA['color_size_details'] <> ""){ 
                if(count($EDITDATA["color_size_details"]) == 0 ){ ?>
                    <span>
                        <div class="row">
                            <div class="form-group-inner col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                <label><b>Select your color</b> : </label>
                                <input type="color" class="form-group" id="color0" name="color0" value="#ff0000" style="margin-left: 2%;">

                                <label style="margin-left: 2%;"><b>Size </b>: </label>

                                <input type="checkbox" id="is_color" name="S0" value="S" style="margin-left: 2%;">
                                <b>&nbspS</b>  

                                <input type="checkbox" id="is_color" name="M0" value="M" style="margin-left: 2%;">
                                <b>&nbspM</b>

                                <input type="checkbox" id="is_color" name="L0" value="L" style="margin-left: 2%;">
                                <b>&nbspL</b>

                                <input type="checkbox" id="is_color" name="XL0" value="XL" style="margin-left: 2%;">
                                <b>&nbspXL</b>

                                <input type="checkbox" id="is_color" name="XXL0" value="XXL" style="margin-left: 2%;">
                                <b>&nbspXXL</b>

                                <input type="checkbox" id="is_color" name="FRS0" value="FRS" style="margin-left: 2%;">
                                <b>&nbspFree Size</b>

                                <label style="margin-left: 10%;"><b>Image</b> : &nbsp&nbsp</label>
                                <input type="file" class="form-group required" id="color_img0" name="color_img0" > 
                            </div>
                            <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align:right;">
                                <label>&nbsp;</label>
                                <a href="javascript:void(0);" class="removeMoreData" id="RemoveData_<?php echo $i; ?>" style="float:right;display:none;"><img src="<?php echo base_url(); ?>assets/admin/image/cross.png" alt="Remove" /></a>
                                <a href="javascript:void(0);" class="addMoreData" id="AddData_<?php echo $i; ?>" style="float:right;display:block;margin-right: 10px;"><img src="<?php echo base_url(); ?>assets/admin/image/addmore.png" alt="Add more" /></a>
                            </div>
                        </div>
                    </span>
                <?php }else{
                    $i=1; foreach($EDITDATA['color_size_details'] as $item){ ?>
                        <span>
                            <div class="row">
                                <div class="form-group-inner col-lg-10 col-md-10 col-sm-10 col-xs-10"><?php if($i > 1){ echo '<hr />'; } ?>
                                <label><b>Select your color</b> : </label>
                                <input type="color" class="form-group" id="color<?=$i?>" name="color<?=$i?>" value="<?=$item->color?>">

                                <label style="margin-left: 2%;"><b>Size </b>: </label>

                                <input type="checkbox" id="is_color" name="S<?=$i?>" value="S" <?php if($item->S == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                <label>S</label>  

                                <input type="checkbox" id="is_color" name="M<?=$i?>" value="M" <?php if($item->M == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                <label>M</label>

                                <input type="checkbox" id="is_color" name="L<?=$i?>" value="L" <?php if($item->L == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                <label>L</label>

                                <input type="checkbox" id="is_color" name="XL<?=$i?>" value="XL" <?php if($item->XL == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                <label>XL</label>

                                <input type="checkbox" id="is_color" name="XXL<?=$i?>" value="XXL" <?php if($item->XXL == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                <label>XXL</label>

                                <input type="checkbox" id="is_color" name="FRS<?=$i?>" value="FRS" <?php if($item->FRS == 'Y'): echo 'checked'; endif; ?> style="margin-left: 2%;">
                                <label>Free Size</label>

                                <label style="margin-left: 10%;"><b>Image</b> : </label>
                                <input type="file" class="form-group" id="color_img<?=$i?>" name="color_img<?=$i?>" >
                                <input type="hidden" name="img_exist<?=$i?>" value="<?=$item->image?>"> 
                            </div>
                            <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align:right;"><?php if($i > 1){ echo '<hr />'; } ?>
                            <label>&nbsp;</label>
                            <?php if($i < $TotalData){ ?>
                                <a href="javascript:void(0);" class="removeMoreData" id="RemoveData_<?php echo $i; ?>" style="float:right;display:block;"><img src="<?php echo base_url(); ?>assets/admin/image/cross.png" alt="Remove" /></a>
                                <a href="javascript:void(0);" class="addMoreData" id="AddData_<?php echo $i; ?>" style="float:right;display:none;"><img src="<?php echo base_url(); ?>assets/admin/image/addmore.png" alt="Add more" /></a>
                            <?php }else{ ?>
                                <a href="javascript:void(0);" class="removeMoreData" id="RemoveData_<?php echo $i; ?>" style="float:right;display:none;"><img src="<?php echo base_url(); ?>assets/admin/image/cross.png" alt="Remove" /></a>
                                <a href="javascript:void(0);" class="addMoreData" id="AddData_<?php echo $i; ?>" style="float:right;display:block;margin-right: 10px;"><img src="<?php echo base_url(); ?>assets/admin/image/addmore.png" alt="Add more" /></a>
                            <?php } ?>
                        </div>
                    </div>
                </span>
                <?php $i++; } 
            }


        }else{  ?>
            <span>
                <div class="row">
                    <div class="form-group-inner col-lg-10 col-md-10 col-sm-10 col-xs-10">
                        <label><b>Select your color</b> : </label>
                        <input type="color" class="form-group" id="color0" name="color0" value="#ff0000" style="margin-left: 2%;">

                        <label style="margin-left: 2%;"><b>Size </b>: </label>

                        <input type="checkbox" id="is_color" name="S0" value="S" style="margin-left: 2%;">
                        <b>&nbspS</b>  

                        <input type="checkbox" id="is_color" name="M0" value="M" style="margin-left: 2%;">
                        <b>&nbspM</b>

                        <input type="checkbox" id="is_color" name="L0" value="L" style="margin-left: 2%;">
                        <b>&nbspL</b>

                        <input type="checkbox" id="is_color" name="XL0" value="XL" style="margin-left: 2%;">
                        <b>&nbspXL</b>

                        <input type="checkbox" id="is_color" name="XXL0" value="XXL" style="margin-left: 2%;">
                        <b>&nbspXXL</b>

                        <input type="checkbox" id="is_color" name="FRS" value="FRS" style="margin-left: 2%;">
                        <label>Free Size</label>

                        <label style="margin-left: 10%;"><b>Image</b> : &nbsp&nbsp</label>
                        <input type="file" class="form-group required" id="color_img0" name="color_img0" > 
                    </div>
                    <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align:right;">
                        <label>&nbsp;</label>
                        <a href="javascript:void(0);" class="removeMoreData" id="RemoveData_<?php echo $i; ?>" style="float:right;display:none;"><img src="<?php echo base_url(); ?>assets/admin/image/cross.png" alt="Remove" /></a>
                        <a href="javascript:void(0);" class="addMoreData" id="AddData_<?php echo $i; ?>" style="float:right;display:block;margin-right: 10px;"><img src="<?php echo base_url(); ?>assets/admin/image/addmore.png" alt="Add more" /></a>
                    </div>
                </div>
            </span>

        <?php } ?>
    </fieldset>       
</div>
<!-- END -->


<div class="row">
    <div class="login-btn-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="inline-remember-me mt-4">
            <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
            <button class="btn btn-primary mb-4">Submit</button>
            <a href="<?php echo correctLink('ALLPRODUCTSDATA',getCurrentControllerPath('index')); ?>" class="btn btn-danger has-ripple mb-4">Cancel</a>
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
    $('#category_id').on('change',function(){
        var category_id =  $(this).val();   
        $.ajax({
            url:FULLSITEURL+'products/allproducts/getsubcategoryData',
            type:'post',
            data:{category_id:category_id},
            success:function(data){
                $('#sub_category_data').html(data);
            }
        });
    });

    $(document).ready(function(){
        var category_id =  $('#category_id').val();
        var sub_category_id  =  '<?php echo $EDITDATA['sub_category_id']?>';
        $.ajax({
            url:FULLSITEURL+'products/allproducts/getsubcategoryData',
            type:'post',
            data:{category_id:category_id,sub_category_id:sub_category_id},
            success:function(data){
                $('#sub_category_data').html(data);
            }
        });
    });
</script>
<script type="text/javascript">
  $(function(){create_editor_for_textarea('description')});
  $(function(){create_editor_for_textarea('image')});
</script>
<script>
    $(document).ready(function(){
        $('#is_color').click(function(){
            if($('#is_color').prop('checked')==true){
                $('#Color_N_Size').css('display','block');
            } else {
                $('#Color_N_Size').css('display','none');
            }
        });
    });
</script>

<script>
    $(function(){ 
      var scntDiv   =   $('#currentPageForm #Datalocation');
      var pi      =   $('#currentPageForm #Datalocation > span').length; 

      $(document).on('click', '#currentPageForm .addMoreData', function() { 

        var i     =   parseInt($('#currentPageForm #TotalDataCount').val());
        i++;
        pi++;
        $('<span><div class="row"><div class="form-group-inner col-lg-10 col-md-10 col-sm-10 col-xs-10"><hr><label><b>Select your color</b> : </label><input type="color" class="form-group" id="color'+i+'" name="color'+i+'" value="#ff0000" style="margin-left: 2%;"><label style="margin-left: 2.5%;"><b>Size </b>: </label><input type="checkbox" id="is_color" name="S'+i+'" value="S" style="margin-left: 2%;margin-top:-1.5%"><b>&nbspS</b><input type="checkbox" id="is_color" name="M'+i+'" value="M" style="margin-left: 2%;margin-top:-1.5%"><b>&nbspM</b><input type="checkbox" id="is_color" name="L'+i+'" value="L" style="margin-left: 2%;margin-top:-1.5%"><b>&nbspL</b><input type="checkbox" id="is_color" name="XL'+i+'" value="XL" style="margin-left: 2%;margin-top:-1.5%"><b>&nbspXL</b><input type="checkbox" id="is_color" name="XXL'+i+'" value="XXL" style="margin-left: 2%;margin-top:-1.5%"><b>&nbspXXL</b><input type="checkbox" id="is_color" name="FRS'+i+'" value="FRS" style="margin-left: 2%;margin-top:-1.5%"><b>&nbspFree Size</b><label style="margin-left: 10%;"><b>Image</b> : &nbsp&nbsp</label><input type="file" class="form-group required" id="color_img'+i+'" name="color_img'+i+'" ></div> <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align:right;"><hr /><label>&nbsp;</label><label>&nbsp;</label><a href="javascript:void(0);" class="removeMoreData" id="RemoveData_'+i+'" style="float:right;display:none;"><img src="<?php echo base_url(); ?>assets/admin/image/cross.png" alt="Remove" /></a><a href="javascript:void(0);" class="addMoreData" id="AddData_'+i+'" style="float:right;display:block;margin-right: 10px;"><img src="<?php echo base_url(); ?>assets/admin/image/addmore.png" alt="Add more" /></a></div></div></span>').appendTo(scntDiv);
        $('#currentPageForm #TotalData').val(pi);
        $('#currentPageForm #TotalDataCount').val(i);

        $(this).closest('#Datalocation').find('a.removeMoreData').show();
        $(this).closest('#Datalocation').find('a.addMoreData').hide();
        $('#currentPageForm #RemoveData_'+i).hide();
        $('#currentPageForm #AddData_'+i).show();

        return false;
    });

      $(document).on('click', '#currentPageForm .removeMoreData', function() {  
        if( pi > 1 ) {
          $(this).parents('span').remove();
          pi--;
          $('#currentPageForm #TotalData').val(pi);
      }
      return false;
  });
  });
</script>
