<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['ad_id'];
  /*
  if(isset($_GET['delete_pay_number']))
  {
        $id=intval($_GET['delete_pay_number']);
        $adn="delete from his_payrolls where pay_number=?";
        $stmt= $mysqli->prepare($adn);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $stmt->close();	 
  
          if($stmt)
          {
            $success = "Payroll Record Deleted";
          }
            else
            {
                $err = "Try Again Later";
            }
    }
    */

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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Payroll</a></li>
                                            <li class="breadcrumb-item active">Generate Payroll</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Employee Payroll Details</h4>
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
                                                <div class="">
                                                    <input id="demo-foo-search"
                                                           type="text"
                                                           placeholder="Search"
                                                           autocomplete="on"
                                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                            <thead class="bg-gray-100 text-gray-700  text-sm">
                                            <tr>
                                                <th class="px-4 py-2 text-left">#</th>
                                                <th class="px-4 py-2 text-left">Employee Name</th>
                                                <th class="px-4 py-2 text-left">Employee Number</th>
                                                <th class="px-4 py-2 text-left">Payroll Number</th>
                                                <th class="px-4 py-2 text-left">Date Posted</th>
                                                <th class="px-4 py-2 text-left">Employee Salary</th>
                                                <th class="px-4 py-2 text-left">Action</th>
                                            </tr>
                                            </thead>

                                            <?php
                                            
                                                $ret="SELECT * FROM  hmisphp.his_payrolls  ";
                                                //sql code to get to ten docs  randomly
                                                $stmt= $mysqli->prepare($ret) ;
                                                $stmt->execute() ;//ok
                                                $res=$stmt->get_result();
                                                $cnt=1;
                                                while($row=$res->fetch_object())
                                                {
                                                    $mysqlDateTime = $row->pay_date_generated;
                                            ?>

                                                    <tbody>
                                                    <tr class="border-b hover:bg-gray-50 text-black ">
                                                        <td class="px-4 py-2"><?php echo $cnt; ?></td>
                                                        <td class="px-4 py-2"><?php echo $row->pay_doc_name; ?></td>
                                                        <td class="px-4 py-2"><?php echo $row->pay_doc_number; ?></td>
                                                        <td class="px-4 py-2"><?php echo $row->pay_number; ?></td>
                                                        <td class="px-4 py-2"><?php echo date("d/m/Y - h:m:s", strtotime($mysqlDateTime)); ?></td>
                                                        <td class="px-4 py-2">$ <?php echo $row->pay_emp_salary; ?></td>
                                                        <td class="px-2 py-2">
                                                            <a href="his_admin_generate_single_employee_payroll.php?pay_number=<?php echo $row->pay_number;?>&&pay_doc_number=<?php echo $row->pay_doc_number;?>"
                                                               class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs px-1 py-1 rounded-md">
                                                                <i class="fas fa-file-invoice-dollar"></i> Generate Payroll
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