<!--Server side code to handle  Patient Registration-->
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['assaign_dept']))
		{
            $doc_dept=$_POST['doc_dept'];
            $doc_number = $_GET['doc_number'];

            //sql to insert captured values
			$query="UPDATE hmisphp.his_docs SET doc_dept=? WHERE doc_number = ?";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('ss', $doc_dept, $doc_number);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Employee Assigned Department";
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
                                            <li class="breadcrumb-item active">Assign Department</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Assign Department</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        <!-- Form row -->
                        <?php
                        $doc_number = $_GET['doc_number'];
                        $ret = "SELECT * FROM hmisphp.his_docs WHERE doc_number=?";
                        $stmt = $mysqli->prepare($ret);
                        $stmt->bind_param('s', $doc_number); // <-- 's' for string
                        $stmt->execute();
                        $res = $stmt->get_result();

                        while($row = $res->fetch_object()) {
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Fill all fields</h4>
                                        <!--Add Patient Form-->
                                        <form method="post" enctype="multipart/form-data" class="space-y-6">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <label for="inputEmail4" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                                    <input type="text" readonly value="<?php echo $row->doc_fname;?>" name="doc_fname"
                                                           class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                                </div>
                                                <div>
                                                    <label for="inputPassword4" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                                    <input type="text" readonly value="<?php echo $row->doc_lname;?>" name="doc_lname"
                                                           class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                                </div>
                                            </div>

                                            <div>
                                                <label for="inputAddress" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                <input type="email" readonly value="<?php echo $row->doc_email;?>" name="doc_email"
                                                       class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                            </div>

                                            <div>
                                                <label for="inputState" class="block text-sm font-medium text-gray-700 mb-1">Departments</label>
                                                <select id="inputState" required name="doc_dept"
                                                        class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                                    <option>Choose</option>
                                                    <option>Admin</option>
                                                    <option>IT</option>
                                                    <option>Laboratory</option>
                                                    <option>Pharmacy</option>
                                                    <option>Accounting</option>
                                                    <option>Doctor </option>
                                                </select>
                                            </div>

                                            <div>
                                                <button type="submit" name="assaign_dept"
                                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Assign Department
                                                </button>

                                            </div>
                                        </form>

                                        <!--End Patient Form-->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
                        <?php }?>

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