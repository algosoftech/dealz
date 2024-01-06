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
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Recharge Coupons</a></li>
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
                <h5>Manage Recharge Coupons</h5>
                <!-- <a href="<?php echo getCurrentControllerPath('exportexcel'); ?>" class="btn btn-sm btn-primary pull-right" style="margin-left: 5px;">Export excel</a> -->
                <a href="<?php echo base_url('recharge/allrechargevoucher/viewredeemcoupons')?>" class="btn btn-sm btn-primary pull-right ml-2"> View Redeem Coupons </a>
                <a href="<?php echo getCurrentControllerPath('addeditdata'); ?>" class="btn btn-sm btn-primary pull-right">Add recharge coupons</a>
              </div>
              <div class="card-body">
                <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
                  <div class="dt-responsive table-responsive">
                    <div id="simpletable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                     <div class="row">
                        <div class="col-sm-12 col-md-6">
                          <div class="dataTables_length" id="simpletable_length">
                            <label>Show 
                              <select name="showLength" id="showLength" class="custom-select custom-select-sm form-control form-control-sm">
                                <option value="2" <?php if($perpage == '2')echo 'selected="selected"'; ?>>2</option>
                                <option value="10" <?php if($perpage == '10')echo 'selected="selected"'; ?>>10</option>
                                <option value="25" <?php if($perpage == '25')echo 'selected="selected"'; ?>>25</option>
                                <option value="50" <?php if($perpage == '50')echo 'selected="selected"'; ?>>50</option>
                                <option value="100" <?php if($perpage == '100')echo 'selected="selected"'; ?>>100</option>
                                <option value="All" <?php if($perpage == 'All')echo 'selected="selected"'; ?>>All</option>
                              </select>
                              entries
                            </label>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                          <div class="row" style="margin:0px;">
                            <div class="col-sm-12 col-md-6">
                              <select name="searchField" id="searchField" class="custom-select custom-select-sm form-control form-control-sm">
                                <option value="">Select Field</option>
                                <!-- <option value="prize_seq_id" <?php if($searchField == 'prize_seq_id')echo 'selected="selected"'; ?>>COUPON ID</option> -->
                                <option value="rc_seq_id" <?php if($searchField == 'rc_seq_id')echo 'selected="selected"'; ?>>ID</option>
                                <option value="coupon_code" <?php if($searchField == 'coupon_code')echo 'selected="selected"'; ?>>Coupon Code</option>
                                <option value="aed" <?php if($searchField == 'aed')echo 'selected="selected"'; ?>>ADE Amount</option>
                                <option value="generate_for" <?php if($searchField == 'generate_for')echo 'selected="selected"'; ?>>Generate for</option>

                                <option value="created_for_email" <?php if($searchField == 'created_for_email')echo 'selected="selected"'; ?>>Email</option>
                                <option value="created_for_mobile" <?php if($searchField == 'created_for_mobile')echo 'selected="selected"'; ?>>Mobile</option>
                              </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                              <input type="text" name="searchValue" id="searchValue" value="<?php echo $searchValue; ?>" class="form-control form-control-sm" placeholder="Enter Search Text">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
							<div class="table-responsive">
								<table id="simpletable" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="simpletable_info">
									<thead style="text-align: center;">
									  <tr role="row">
										<th width="5%" style="text-align: center;">S.No.</th>
                    <th width="5%" style="text-align: center;">ID</th>
                    <th width="10%">AED</th>
                    <th width="10%">iPoints</th>
                    <!-- <th width="20%">Date</th> -->
                    <th width="20%">For</th>
                    <th width="20%">Coupons</th>
                    <th width="10%" style="text-align: right;">Status</th>
										<th width="10%">Action</th>
									  </tr>
									</thead>
									<tbody style="text-align: center;">
									  <?php if($ALLDATA <> ""): $i=$first; $j=0; foreach($ALLDATA as $ALLDATAINFO): 
										if($j%2==0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;
									  ?>
										<tr role="row" class="<?php echo $rowClass; ?>">
										  <td style="text-align: center;"><?=$i++?></td>
                      <td><?=stripslashes($ALLDATAINFO['rc_seq_id'])?></td>
										  <td><?=stripslashes($ALLDATAINFO['aed'])?></td>
                      <td><?=stripslashes($ALLDATAINFO['ipoints'])?></td>
                      <!-- <td><?=$this->timezone->location_date($ALLDATAINFO['creation_date'],'d F Y',DEFAULT_TIMEZONE);?></td> -->
                      <td>
                        <?=stripslashes($ALLDATAINFO['generate_for'])?>
                        <?php if ($ALLDATAINFO['created_for_email']): ?> 
                          <br>
                          Email : <?=stripslashes($ALLDATAINFO['created_for_email']);?>
                        <?php endif; ?>
                        <?php if ($ALLDATAINFO['created_for_email']): ?>
                          <br> 
                          Mobile : <?=stripslashes($ALLDATAINFO['created_for_mobile']);?>
                        <?php endif; ?>
                        <br>
                        Date : <?=$this->timezone->location_date($ALLDATAINFO['creation_date'],'d F Y',DEFAULT_TIMEZONE);?>
                      </td>
                      <td><a href="<?php echo getCurrentControllerPath('exportexcel/'.$ALLDATAINFO['rc_id'])?>"><i class="fas fa-edit"></i> Download</a></td>
                      <td style="text-align: right;"><?=showStatus($ALLDATAINFO['status'])?></td>
										  <td>
											<div class="btn-group">
											  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
											  <ul class="dropdown-menu" role="menu">
												<li><a href="<?php echo base_url('recharge/allrechargevoucher/index/'.$ALLDATAINFO['rc_id'])?>"><i class="fas fa-eye"></i> View Vouchers</a></li>
												<?php if($ALLDATAINFO['status'] == 'A'): ?>
												  <li><a href="<?php echo getCurrentControllerPath('changestatus/'.$ALLDATAINFO['rc_id'].'/I')?>"><i class="fas fa-thumbs-down"></i> Inactive</a></li>
												<?php elseif($ALLDATAINFO['status'] == 'I'): ?>
												  <li><a href="<?php echo getCurrentControllerPath('changestatus/'.$ALLDATAINFO['rc_id'].'/A')?>"><i class="fas fa-thumbs-up"></i> Active</a></li>
												<?php endif; ?>
												  <li><a href="<?php echo getCurrentControllerPath('deletedata/'.$ALLDATAINFO['rc_id'])?>" onClick="return confirm('Want to delete!');"><i class="fas fa-trash"></i> Delete</a></li>
											   </ul>
											</div>
										  </td>
										</tr>
									  <?php $j++; endforeach; else: ?>
										<tr>
										  <td colspan="6" style="text-align:center;">No Data Available In Table</td>
										</tr>
									  <?php endif; ?>
									</tbody>
								  </table>
							</div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12 col-md-5">
                          <div class="dataTables_info" role="status" aria-live="polite"><?php echo $noOfContent; ?></div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                          <div class="dataTables_paginate paging_simple_numbers">
                            <?php echo $PAGINATION; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>