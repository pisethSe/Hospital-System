<!--Server side code to handle  Patient Registration-->
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['add_vendor']))
		{
			$v_name=$_POST['v_name'];
			$v_adr=$_POST['v_adr'];
			$v_number=$_POST['v_number'];
            $v_email=$_POST['v_email'];
            $v_phone = $_POST['v_phone'];
            $v_desc = $_POST['v_desc'];
            //$doc_pwd=sha1(md5($_POST['doc_pwd']));
            
            //sql to insert captured values
			$query="INSERT INTO hmisphp.his_vendor (v_name, v_adr, v_number, v_email, v_phone, v_desc) values(?,?,?,?,?,?)";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('ssssss', $v_name, $v_adr, $v_number, $v_email, $v_phone, $v_desc);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Vendor Details Added";
			}
			else {
				$err = "Please Try Again Or Try Later";
			}
			
			
		}
?>
<!--End Server Side-->
<!--End Patient Registration-->
<!DOCTYPE html>
<html lang="en">
    
    <!--Head-->
    <?php include('assets/inc/head.php');?>
    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <?php include("assets/inc/nav.php");?>
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
                                            <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Vendor</a></li>
                                            <li class="breadcrumb-item active">Add Vendor</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Add Vendor Details</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        <!-- Form row -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Fill all fields</h4>
                                        <!--Add Patient Form-->
                                        <form method="post">
                                            <div class="form-row">
                                                <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
                                                    <label for="vendorName" class="block text-sm font-medium text-gray-700 mb-1">Vendor Name</label>
                                                    <input type="text"
                                                           required
                                                           name="v_name"
                                                           id="vendorName"
                                                           placeholder="Enter Vendor Name"
                                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                </div>

                                                <!-- Vendor Phone Number -->
                                                <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
                                                    <label for="vendorPhone" class="block text-sm font-medium text-gray-700 mb-1">Vendor Phone Number</label>
                                                    <input type="text"
                                                           required
                                                           name="v_phone"
                                                           id="vendorPhone"
                                                           placeholder="Enter Phone Number"
                                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                </div>

                                                <!-- Vendor Address -->
                                                <div class="w-full md:w-1/3 px-2">
                                                    <label for="vendorAddress" class="block text-sm font-medium text-gray-700 mb-1">Vendor Address</label>
                                                    <input type="text"
                                                           required
                                                           name="v_adr"
                                                           id="vendorAddress"
                                                           placeholder="Enter Vendor Address"
                                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2" style="display:none">
                                                    <?php 
                                                        $length = 5;    
                                                        $vendor_number =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                                                    ?>
                                                    <label for="inputZip" class="col-form-label">Vendor Number</label>
                                                    <input type="text" name="v_number" value="<?php echo $vendor_number;?>" class="form-control" id="inputZip">
                                                </div>

                                            <div class="mb-4">
                                                <label for="vendorEmail" class="block text-sm font-medium text-gray-700 mb-1">Vendor Email</label>
                                                <input type="email"
                                                       required
                                                       name="v_email"
                                                       id="vendorEmail"
                                                       placeholder="Enter Vendor Email"
                                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>


                                            <div class="form-group">
                                                <label for="inputAddress" class="col-form-label">Vendor Details</label>
                                                <textarea  type="text" class="form-control" name="v_desc" id="editor"></textarea>
                                            </div>

                                            <button type="submit"
                                                    name="add_vendor"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                                                Add Vendor
                                            </button>


                                        </form>
                                        <!--End Patient Form-->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
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
        <script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
        <script type="text/javascript">
        CKEDITOR.replace('editor')
        </script>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>

        <!-- Loading buttons js -->
        <script src="assets/libs/ladda/spin.js"></script>
        <script src="assets/libs/ladda/ladda.js"></script>

        <!-- Buttons init js-->
        <script src="assets/js/pages/loading-btn.init.js"></script>
        
    </body>

</html>