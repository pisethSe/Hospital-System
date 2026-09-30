<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['ad_id'];
if (isset($_GET['delete_mdr_number'])) {
    $id = $_GET['delete_mdr_number']; // keep as string
    $adn = "DELETE FROM hmisphp.his_medical_records WHERE mdr_number = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id);  // <-- bind as string
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $success = "Medical Record Deleted";
    } else {
        $err = "Try Again Later";
    }

    $stmt->close();
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Medical Records</a></li>
                                            <li class="breadcrumb-item active">Manage Medical Records</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Manage Medical Records</h4>
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
                                                    <input
                                                            id="demo-foo-search"
                                                            type="text"
                                                            placeholder="Search"
                                                            autocomplete="on"
                                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    >
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                            <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Patient Name</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Patient Number</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Address</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Ailment</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Age</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Action</th>
                                            </tr>
                                            </thead>

                                            <?php
                                            /*
                                                *get details of allpatients
                                                *
                                            */
                                                $ret="SELECT * FROM  hmisphp.his_medical_records ORDER BY RAND() ";
                                                //sql code to get to ten docs  randomly
                                                $stmt= $mysqli->prepare($ret) ;
                                                $stmt->execute() ;//ok
                                                $res=$stmt->get_result();
                                                $cnt=1;
                                                while($row=$res->fetch_object())
                                                {
                                            ?>

                                                    <tbody class="divide-y divide-gray-200 text-black">
                                                    <tr class="bg-white hover:bg-gray-50">
                                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $cnt;?></td>
                                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row->mdr_pat_name;?></td>
                                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row->mdr_pat_number;?></td>
                                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row->mdr_pat_adr;?></td>
                                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row->mdr_pat_ailment;?></td>
                                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row->mdr_pat_age;?> Years</td>
                                                        <td class="px-4 py-2 text-sm space-x-1">
                                                            <a href="his_admin_view_single_medical_record.php?mdr_id=<?php echo $row->mdr_id;?>&&mdr_number=<?php echo $row->mdr_number;?>"
                                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                                                <i class="fas fa-eye mr-1"></i> View
                                                            </a>
                                                            <a href="his_admin_upate_single_medical_record.php?mdr_number=<?php echo $row->mdr_number;?>"
                                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-yellow-500 rounded-md hover:bg-yellow-600">
                                                                <i class="fas fa-eye-dropper mr-1"></i> Update
                                                            </a>
                                                            <a href="his_admin_manage_medical_record.php?delete_mdr_number=<?php echo $row->mdr_number;?>"
                                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
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