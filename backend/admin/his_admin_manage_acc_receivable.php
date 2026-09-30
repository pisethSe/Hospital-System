<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['ad_id'];
  if(isset($_GET['delete_account']))
  {
        $id=intval($_GET['delete_account']);
        $adn="delete from hmisphp.his_accounts where acc_number=?";
        $stmt= $mysqli->prepare($adn);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $stmt->close();	 
  
          if($stmt)
          {
            $success = "Payable Account Records Deleted";
          }
            else
            {
                $err = "Try Again Later";
            }
    }
?>

<!DOCTYPE html>
<html lang="en">
    
<?php include('assets/inc/head.php');?>

    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
                <?php include('assets/inc/nav.php');?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
                <?php include("assets/inc/sidebar.php");?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Accounts</a></li>
                                            <li class="breadcrumb-item active">Manage Receivable Accounts</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Manage Receivable Accounts</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 

                        <div class="row">
                            <div class="col-12">
                                <div class="card-box">
                                    <h4 class="header-title"></h4>
                                    <div class="mb-2">
                                        <div class="row">
                                            <div class="col-12 text-sm-center form-inline" >
                                                <div class="form-group mr-2" style="display:none">
                                                    <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                        <option value="">Show all</option>
                                                        <option value="Discharged">Discharged</option>
                                                        <option value="OutPatients">OutPatients</option>
                                                        <option value="InPatients">InPatients</option>
                                                    </select>
                                                </div>
                                                <div class="mb-4">
                                                    <input type="text"
                                                           id="demo-foo-search"
                                                           placeholder="Search"
                                                           autocomplete="on"
                                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                            <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                                            <tr>
                                                <th class="px-4 py-2 text-left">#</th>
                                                <th class="px-4 py-2 text-left">Account Name</th>
                                                <th class="px-4 py-2 text-left">Account Number</th>
                                                <th class="px-4 py-2 text-left">Account Amount</th>
                                                <th class="px-4 py-2 text-left">Action</th>
                                            </tr>
                                            </thead>

                                            <?php
                                            
                                                $ret="SELECT * FROM  hmisphp.his_accounts WHERE acc_type = 'Receivable Account' ORDER BY RAND() ";
                                                $stmt= $mysqli->prepare($ret) ;
                                                $stmt->execute() ;//ok
                                                $res=$stmt->get_result();
                                                $cnt=1;
                                                while($row=$res->fetch_object())
                                                {
                                            ?>

                                                    <tbody class="divide-y divide-gray-200">
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $cnt; ?></td>
                                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row->acc_name; ?></td>
                                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row->acc_number; ?></td>
                                                        <td class="px-4 py-2 text-sm text-gray-700">$ <?php echo $row->acc_amount; ?></td>
                                                        <td class="px-4 py-2 text-sm text-gray-700 space-x-2">
                                                            <a href="his_admin_view_single_payable_account.php?acc_number=<?php echo $row->acc_number; ?>"
                                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                                                <i class="fas fa-eye mr-1"></i> View
                                                            </a>
                                                            <a href="his_admin_update_single_receivable_account.php?acc_number=<?php echo $row->acc_number; ?>"
                                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                                                <i class="fas fa-clipboard-check mr-1"></i> Update
                                                            </a>
                                                            <a href="his_admin_manage_acc_receivable.php?delete_account=<?php echo $row->acc_number; ?>"
                                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                                                <i class="fas fa-trash-alt mr-1"></i> Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>

                                                    <?php  $cnt = $cnt +1 ; }?>
                                            <tfoot>
                                            <tr class="active">
                                                <td colspan="8">
                                                    <div class="text-right">
                                                        <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div> <!-- end .table-responsive-->
                                </div> <!-- end card-box -->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                 <?php include('assets/inc/footer.php');?>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->


        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Footable js -->
        <script src="assets/libs/footable/footable.all.min.js"></script>

        <!-- Init js -->
        <script src="assets/js/pages/foo-tables.init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>