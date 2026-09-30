
<?php
	session_start();
	include('assets/inc/config.php');
        if(isset($_POST['add_equipments']))
        
        {
        
		    $eqp_code = $_POST['eqp_code'];
			$eqp_name = $_POST['eqp_name'];
            $eqp_vendor = $_POST['eqp_vendor'];
            $eqp_desc = $_POST['eqp_desc'];
            $eqp_dept = $_POST['eqp_dept'];
            $eqp_status = $_POST['eqp_status'];
            $eqp_qty = $_POST['eqp_qty'];
                
            //sql to insert captured values
			$query="INSERT INTO hmisphp.his_equipments (eqp_code, eqp_name, eqp_vendor, eqp_desc, eqp_dept, eqp_status, eqp_qty) VALUES (?,?,?,?,?,?,?)";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('sssssss', $eqp_code, $eqp_name, $eqp_vendor, $eqp_desc, $eqp_dept, $eqp_status, $eqp_qty);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Equipment Added";
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Surgical/ Theatre</a></li>
                                            <li class="breadcrumb-item active">Add Equipment</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Surgical Equipments</h4>
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
                                            <div class="flex flex-wrap ">

                                                <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
                                                    <label for="eqp_name" class="block text-sm font-medium text-gray-700 mb-1">Equipment Name</label>
                                                    <input type="text" required name="eqp_name" id="eqp_name"
                                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                </div>

                                                <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
                                                    <label for="eqp_vendor" class="block text-sm font-medium text-gray-700 mb-1">Equipment Vendor</label>
                                                    <input type="text" required name="eqp_vendor" id="eqp_vendor"
                                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                </div>

                                                <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
                                                    <label for="eqp_qty" class="block text-sm font-medium text-gray-700 mb-1">Equipment Quantity</label>
                                                    <input type="text" required name="eqp_qty" id="eqp_qty"
                                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                </div>

                                                <!-- Hidden Department -->
                                                <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0 hidden">
                                                    <label for="eqp_dept" class="block text-sm font-medium text-gray-700 mb-1">Equipment Department</label>
                                                    <input type="text" required name="eqp_dept" value="Surgical | Theatre" id="eqp_dept"
                                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                </div>

                                                <!-- Hidden Status -->
                                                <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0 hidden">
                                                    <label for="eqp_status" class="block text-sm font-medium text-gray-700 mb-1">Equipment Status</label>
                                                    <input type="text" required name="eqp_status" value="Functioning" id="eqp_status"
                                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                </div>

                                            </div>


                                            <div class="mb-4">
                                                <label for="eqp_code" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Equipment Barcode (EAN-8)
                                                </label>
                                                <?php
                                                $length = 10;
                                                $bcode = substr(str_shuffle('0123456789'),1,$length);
                                                ?>
                                                <input required readonly type="text" id="eqp_code" name="eqp_code"
                                                       value="<?php echo $bcode;?>"
                                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            </div>


                                            <div class="form-group" style="style:display-none">
                                                <label for="inputAddress" class="col-form-label">Pharmaceutical Category Description</label>
                                                <textarea required="required" type="text" class="form-control" name="eqp_desc" id="editor"></textarea>
                                            </div>

                                            <button type="submit" name="add_equipments"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">
                                                Add Equipment
                                            </button>


                                        </form>
                                     
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
        <!--Load CK EDITOR Javascript-->
        <script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
        <script type="text/javascript">
        CKEDITOR.replace('editor')
        </script>
       
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

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