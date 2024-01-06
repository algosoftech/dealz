<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
$(function(){
   $("#fromDate").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
   $("#toDate").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
});
</script>
<style type="text/css">
   /*.d-card-body {
   overflow-y: auto;
   height: 300px;
   }*/
   #container {
   height: 400px;
   }
   .highcharts-figure, .highcharts-data-table table {
   min-width: 310px;
   max-width: 800px;
   margin: 1em auto;
   }
   #datatable {
   font-family: Verdana, sans-serif;
   border-collapse: collapse;
   border: 1px solid #EBEBEB;
   margin: 10px auto;
   text-align: center;
   width: 100%;
   max-width: 500px;
   }
   #datatable caption {
   padding: 1em 0;
   font-size: 1.2em;
   color: #555;
   }
   #datatable th {
   font-weight: 600;
   padding: 0.5em;
   }
   #datatable td, #datatable th, #datatable caption {
   padding: 0.5em;
   }
   #datatable thead tr, #datatable tr:nth-child(even) {
   background: #f8f8f8;
   }
   #datatable tr:hover {
   background: #f1f7ff;
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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Orders</a></li>
                            
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
                <h5>Statistics Reports</h5>
                <a href="<?php echo getCurrentControllerPath('getStatisticsByUserID') ?>" class="btn btn-sm btn-primary pull-right" style="margin-left:10px;">Get Statistics by email</a>
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
                                <option value="" <?php if($year == '')echo 'selected="selected"'; ?>></option>
                                <option value="2022" <?php if($year == '2022')echo 'selected="selected"'; ?>>2022</option>
                                <option value="2023" <?php if($year == '2023')echo 'selected="selected"'; ?>>2023</option>
                                <option value="2024" <?php if($year == '2024')echo 'selected="selected"'; ?>>2024</option>
                                <option value="2025" <?php if($year == '2025')echo 'selected="selected"'; ?>>2025</option>
                                <option value="2026" <?php if($year == '2026')echo 'selected="selected"'; ?>>2026</option>
                                <option value="2027" <?php if($year == '2027')echo 'selected="selected"'; ?>>2027</option>
                              </select>
                              Year
                            </label>
                          </div>
                        </div>
                        <div class="row" style="margin:10px 0 0 0;">
                          <div class="col-sm-12 col-md-4">
                            <input type="text" name="fromDate" id="fromDate" autocomplete="off" value="<?php echo $fromDate; ?>" class="form-control form-control-sm" placeholder="From Date">
                          </div>
                          <div class="col-sm-12 col-md-4">
                            <input type="text" name="toDate" id="toDate" autocomplete="off" value="<?php echo $toDate; ?>" class="form-control form-control-sm" placeholder="From Date">
                          </div>
                          <div class="col-sm-12 col-md-4">
                            <input type="submit" name="cearchFormSubmit" value="Search" class="btn btn-sm btn-primary">
                            <input type="submit" name="clearAllSearch" value="Clear Search" class="btn btn-sm btn-primary pull-right">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="table-responsive">
                            <figure class="highcharts-figure">
                              <div id="container"></div>
                              <table id="datatable">
                                  <thead>
                                    <tr>
                                        <th></th>
                                        <th>Order Statistics</th>
                                        <!-- <th>John</th> -->
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                        <th>Orders from Users</th>
                                        <td><?php echo $ALLDATA['total_urser'];?></td>
                                    </tr>
                                    <tr>
                                        <th>Orders from Retailer</th>
                                        <td><?php echo $ALLDATA['total_retailser'];?></td>
                                    </tr>
                                    
                                    <tr>
                                        <th>Orders from Sales Person</th>
                                        <td><?php echo $ALLDATA['total_sales'];?></td>
                                    </tr>

                                    <tr>
                                        <th>Quick Ticket</th>
                                        <td><?php echo $ALLDATA['total_quick_buy'];?></td>
                                    </tr>
                                  </tbody>
                              </table>
                            </figure>
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

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script type="text/javascript">
   Highcharts.chart('container', {
    data: {
      table: 'datatable'
    },
    chart: {
      type: 'column'
    },
    title: {
      text: 'Statistics chart'
    },
    yAxis: {
      allowDecimals: false,
      title: {
        text: 'Units'
      }
    },
    tooltip: {
      formatter: function () {
        return '<b>' + this.series.name + '</b><br/>' +
          this.point.y + ' ' + this.point.name.toLowerCase();
      }
    }
   });
</script>