<!--Server side code to handle  Patient Registration-->
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['add_doc']))
		{
			$doc_fname=$_POST['doc_fname'];
			$doc_lname=$_POST['doc_lname'];
			$doc_number=$_POST['doc_number'];
            $doc_email=$_POST['doc_email'];
            $doc_pwd=sha1(md5($_POST['doc_pwd']));
            
            //sql to insert captured values
			$query="INSERT INTO hmisphp.his_docs (doc_fname, doc_lname, doc_number, doc_email, doc_pwd) values(?,?,?,?,?)";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('sssss', $doc_fname, $doc_lname, $doc_number, $doc_email, $doc_pwd);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Employee Details Added";
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employee</a></li>
                                            <li class="breadcrumb-item active">Add Employee</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Add Employee Details</h4>
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
                                        <form method="post" class="space-y-4">

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="doc_fname" class="block text-base font-medium text-gray-700 mb-1">
                                                        First Name <span class="text-red-600">*</span>
                                                    </label>
                                                    <input type="text" required name="doc_fname" id="doc_fname"
                                                           class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label for="doc_lname" class="block text-base font-medium text-gray-700 mb-1">
                                                        Last Name <span class="text-red-600">*</span>
                                                    </label>
                                                    <input type="text" required name="doc_lname" id="doc_lname"
                                                           class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                            </div>

                                            <div class="hidden">
                                                <?php
                                                $length = 5;
                                                $patient_number =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                                                ?>
                                                <label for="doc_number" class="block text-base font-medium text-gray-700 mb-1">
                                                    Doctor Number <span class="text-red-600">*</span>
                                                </label>
                                                <input type="text" name="doc_number" value="<?php echo $patient_number;?>" id="doc_number"
                                                       class="mt-1 block w-1/2 border border-gray-300 rounded-md px-4 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>

                                            <div>
                                                <label for="doc_email" class="block text-base font-medium text-gray-700 mb-1">
                                                    Email <span class="text-red-600">*</span>
                                                </label>
                                                <input type="email" required name="doc_email" id="doc_email"
                                                       class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="doc_pwd" class="block text-base font-medium text-gray-700 mb-1">
                                                        Password <span class="text-red-600">*</span>
                                                    </label>
                                                    <input type="password" required name="doc_pwd" id="doc_pwd"
                                                           class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                            </div>

                                            <div>
                                                <button type="submit" name="add_doc"
                                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Add Employee
                                                </button>
                                            </div>

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