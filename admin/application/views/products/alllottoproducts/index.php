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
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Lotto Product</a></li>
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
                <h5>Manage Lotto Products</h5>
               <!-- Edit lotto Product settings -->
                <a href="<?php echo getCurrentControllerPath('settings'); ?>" class="btn btn-sm btn-primary pull-right" > Settings</a>
               <!-- Add U Lotto Product End -->

               <!-- Add U Lotto Product start -->
                <a href="<?php echo getCurrentControllerPath('addeditdata'); ?>" class="btn btn-sm btn-primary pull-right mr-2">Add Lotto Product</a>
               <!-- Add U Lotto Product end -->

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
                                <option value="products_id" <?php if($searchField == 'products_id')echo 'selected="selected"'; ?>>Product ID</option>
                                <option value="title" <?php if($searchField == 'title')echo 'selected="selected"'; ?>>Product Name</option>
                                <option value="category_name" <?php if($searchField == 'category_name')echo 'selected="selected"'; ?>>Category Name</option>
                                <option value="sub_category_name" <?php if($searchField == 'sub_category_name')echo 'selected="selected"'; ?>>Sub Category Name</option>
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
                    <th width="15%">Product ID</th>
										<th width="20%">Product Name</th>
                    <!-- <th width="20%">Category</th>
                    <th width="25%">Sub Category</th> -->
                    <th width="25%">Created Date</th>
                    <!-- <th width="25%">Updated Date</th> -->
                    <th width="25%">Valid Upto Date</th>
                    <th width="25%">Points</th>
                    <th width="25%">Stock</th>
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
                      <td><a href="<?php echo getCurrentControllerPath('getAllusers/'.$ALLDATAINFO['products_id'])?>"><?=$ALLDATAINFO['products_id'];?></a></td>
										  <td><?=stripslashes($ALLDATAINFO['title'])?></td>
                      <!-- <td><?=stripslashes($ALLDATAINFO['category_name'])?></td>
                      <td><?=stripslashes($ALLDATAINFO['sub_category_name'])?></td> -->
                       <td><?=$this->timezone->location_date($ALLDATAINFO['creation_date'],'d F Y',DEFAULT_TIMEZONE);?></td>
                       <!-- <td><?php if($ALLDATAINFO['update_date']): echo $this->timezone->location_date($ALLDATAINFO['update_date'],'d F Y',DEFAULT_TIMEZONE); endif;  ?></td> -->
                      <td><?=$this->timezone->location_date($ALLDATAINFO['validuptodate'],'d F Y',DEFAULT_TIMEZONE);?></td>
                      <td><?=stripslashes($ALLDATAINFO['straight_add_on_amount'])?></td>
                      <td><?=stripslashes($ALLDATAINFO['stock'].'/'.$ALLDATAINFO['totalStock'])?></td>
                      <td style="text-align: right;"><?=showStatus($ALLDATAINFO['status'])?></td>
										  <td>
											<div class="btn-group">
											  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
											  <ul class="dropdown-menu" role="menu">
												<li><a href="<?php echo getCurrentControllerPath('addeditdata/'.$ALLDATAINFO['products_id'])?>"><i class="fas fa-edit"></i> Edit Details</a></li>
												<?php if($ALLDATAINFO['status'] == 'A'): ?>

                          <li><a href="<?php echo getCurrentControllerPath('updatestock/'.$ALLDATAINFO['products_id'])?>"><i class="fas fa-cubes"></i> Update Stock </a></li>

												  <li><a href="<?php echo getCurrentControllerPath('changestatus/'.$ALLDATAINFO['products_id'].'/I')?>"><i class="fas fa-thumbs-down"></i> Inactive</a></li>

                          <li><a href="<?php echo getCurrentControllerPath('changestatus/'.$ALLDATAINFO['products_id'].'/D')?>"><i class="fas fa-trash"></i> Delete</a></li>

                          <li><a href="<?php echo getCurrentControllerPath('prizeList/'.base64_encode($ALLDATAINFO['products_id']))?>"><i class="fas fa-gift"></i> Add Prize</a></li>
                          <li><a href="<?php echo base_url('winners/alllottowinners/index/'.base64_encode($ALLDATAINFO['products_id']))?>"><i class="fas fa-trophy"></i> Add Winners</a></li>

												<?php elseif($ALLDATAINFO['status'] == 'I'): ?>
												  <li><a href="<?php echo getCurrentControllerPath('changestatus/'.$ALLDATAINFO['products_id'].'/A')?>"><i class="fas fa-thumbs-up"></i> Active</a></li>

												<?php endif; ?>
                        <?php if($ALLDATAINFO['isSoldout'] == 'Y'): ?>
                          <li><a href="<?php echo getCurrentControllerPath('changesoldoutstatus/'.$ALLDATAINFO['products_id'].'/N')?>"><i class="fa fa-check-circle"></i> Mark as Available</a></li>
                        <?php else: ?>
                          <li><a href="<?php echo getCurrentControllerPath('changesoldoutstatus/'.$ALLDATAINFO['products_id'].'/Y')?>"><i class="fa fa-window-close"></i> Mark as Soldout</a></li>
                        <?php endif; ?>

												  <!-- <li><a href="<?php echo getCurrentControllerPath('deletedata/'.$ALLDATAINFO['products_id'])?>" onClick="return confirm('Want to delete!');"><i class="fas fa-trash"></i> Delete</a></li> -->
                          
											   </ul>
											</div>
										  </td>
										</tr>
									  <?php $j++; endforeach; else: ?>
										<tr>
										  <td colspan="11" style="text-align:center;">No Data Available In Table</td>
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