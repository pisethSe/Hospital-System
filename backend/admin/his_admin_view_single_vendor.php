<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['ad_id'];
?>
<!DOCTYPE html>
<html lang="en">
    
<?php include ('assets/inc/head.php');?>

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
            <?php
                $v_number=$_GET['v_number'];
                $ret="SELECT  * FROM hmisphp.his_vendor WHERE v_number = ?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('i',$v_number);
                $stmt->execute() ;//ok
                $res=$stmt->get_result();
                //$cnt=1;
                while($row=$res->fetch_object())
                {
            ?>

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
                                                <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Vendors</a></li>
                                                <li class="breadcrumb-item active">Manage Vendors</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">#<?php echo $row->v_number;?></h4>
                                    </div>
                                </div>
                            </div>     
                            <!-- end page title --> 

                            <div class="row">
                                <div class="col-12">
                                    <div class="card-box">
                                        <div class="row">
                                            <div class="col-xl-5">

                                                <div class="tab-content pt-0">

                                                    <div class="tab-pane active show" id="product-1-item">
                                                        <img src="assets/images/vendor.png" alt="" class="img-fluid mx-auto d-block rounded">
                                                    </div>
                            
                                                </div>
                                            </div> <!-- end col -->
                                            <div class="w-full xl:w-7/12 px-2">
                                                <div class="pl-0 xl:pl-3 mt-3 xl:mt-0">

                                                    <!-- Vendor Name -->
                                                    <h2 class="text-xl font-semibold mb-3">
                                                        Vendor Name: <span class="font-normal"><?php echo $row->v_name; ?></span>
                                                    </h2>
                                                    <hr class="mb-3 border-gray-300">

                                                    <!-- Vendor Contacts -->
                                                    <h3 class="text-red-600 text-lg font-medium mb-3">
                                                        Vendor Contacts: <span class="font-normal"><?php echo $row->v_phone; ?></span>
                                                    </h3>
                                                    <hr class="mb-3 border-gray-300">

                                                    <!-- Vendor Email -->
                                                    <h3 class="text-red-600 text-lg font-medium mb-3">
                                                        Vendor Email: <span class="font-normal"><?php echo $row->v_email; ?></span>
                                                    </h3>
                                                    <hr class="mb-3 border-gray-300">

                                                    <!-- Vendor Address -->
                                                    <h3 class="text-red-600 text-lg font-medium mb-3">
                                                        Vendor Address: <span class="font-normal"><?php echo $row->v_adr; ?></span>
                                                    </h3>
                                                    <hr class="mb-3 border-gray-300">

                                                    <!-- Vendor Details Heading -->
                                                    <h2 class="text-center text-lg font-semibold mb-3">Vendor Details</h2>
                                                    <hr class="mb-3 border-gray-300">

                                                    <!-- Vendor Description -->
                                                    <p class="text-gray-500 mb-4">
                                                        <?php echo $row->v_desc; ?>
                                                    </p>
                                                    <hr class="border-gray-300">

                                                </div>
                                            </div>

                                            <!-- end col -->
                                        </div>
                                        <!-- end row -->

                                        
                                    </div> <!-- end card-->
                                </div> <!-- end col-->
                            </div>
                            <!-- end row-->
                            
                        </div> <!-- container -->

                    </div> <!-- content -->

                    <!-- Footer Start -->
                        <?php include('assets/inc/footer.php');?>
                    <!-- end Footer -->

                </div>
            <?php }?>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>