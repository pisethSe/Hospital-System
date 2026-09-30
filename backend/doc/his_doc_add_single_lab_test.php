<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['add_patient_lab_test']))
		{
			$lab_pat_name = $_POST['lab_pat_name'];
			$lab_pat_ailment = $_POST['lab_pat_ailment'];
            $lab_pat_number  = $_POST['lab_pat_number'];
            $lab_pat_tests = $_POST['lab_pat_tests'];
            $lab_number  = $_POST['lab_number'];
            //$pres_number = $_POST['pres_number'];
            //$pres_ins = $_POST['pres_ins'];
            //$pres_pat_ailment = $_POST['pres_pat_ailment'];
            //sql to insert captured values
			$query="INSERT INTO  hmisphp.his_laboratory  (lab_pat_name, lab_pat_ailment, lab_pat_number, lab_pat_tests, lab_number ) VALUES(?,?,?,?,?)";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('sssss', $lab_pat_name, $lab_pat_ailment, $lab_pat_number, $lab_pat_tests, $lab_number);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Patient Laboratory Tests Addded";
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
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>view patients</title>
</head>
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
            <?php
                $pat_number = $_GET['pat_number'];
                $ret="SELECT  * FROM hmisphp.his_patients WHERE pat_number=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('s',$pat_number);
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
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Laboratory</a></li>
                                                <li class="breadcrumb-item active">Add Lab Test</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Add Lab Test</h4>
                                    </div>
                                </div>
                            </div>     
                            <!-- end page title --> 
                            <!-- Form row -->
                            <div class="flex flex-wrap">
                                <div class="w-full">
                                    <div class="bg-white rounded-lg shadow-md mb-6">
                                        <div class="p-6">
                                            <h4 class="text-xl font-semibold text-black mb-4">Fill all fields</h4>
                                            <!--Add Patient Form-->
                                            <form method="post">
                                                <div class="flex flex-wrap -mx-2 ">
                                                    <!-- Patient Name -->
                                                    <div class="w-full md:w-1/2 px-2 mb-4">
                                                        <label for="inputEmail4" class="block text-sm font-medium text-black mb-1">Patient Name</label>
                                                        <input type="text" required readonly name="lab_pat_name"
                                                               value="<?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?>"
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black bg-gray-100"
                                                               id="inputEmail4" placeholder="Patient's First Name">
                                                    </div>

                                                    <!-- Patient Ailment -->
                                                    <div class="w-full md:w-1/2 px-2">
                                                        <label for="inputPassword4" class="block text-sm font-medium text-black mb-1">Patient Ailment</label>
                                                        <input required type="text" readonly name="lab_pat_ailment"
                                                               value="<?php echo $row->pat_ailment;?>"
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black bg-gray-100"
                                                               id="inputPassword4" placeholder="Patient's Last Name">
                                                    </div>
                                                </div>

                                                <div class="flex flex-wrap -mx-2 ">
                                                    <!-- Patient Number -->
                                                    <div class="w-full px-2 mb-4">
                                                        <label for="inputEmail4" class="block text-sm font-medium text-black mb-1">Patient Number</label>
                                                        <input type="text" required readonly name="lab_pat_number"
                                                               value="<?php echo $row->pat_number;?>"
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black bg-gray-100"
                                                               id="inputEmail4" placeholder="DD/MM/YYYY">
                                                    </div>
                                                </div>

                                                <hr class="my-4 border-gray-300">

                                                <div class="flex flex-wrap -mx-2 ">
                                                    <!-- Hidden Lab Test Number -->
                                                    <div class="hidden w-full md:w-1/6 px-2 mb-4">
                                                        <?php
                                                        $length = 5;
                                                        $pres_no = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                                                        ?>
                                                        <label for="inputZip" class="block text-sm font-medium text-black mb-1">Lab Test Number</label>
                                                        <input type="text" name="lab_number" value="<?php echo $pres_no;?>"
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black"
                                                               id="inputZip">
                                                    </div>
                                                </div>

                                                <!-- Laboratory Tests Textarea -->
                                                <div class="mb-4">
                                                    <label for="inputAddress" class="block text-sm font-medium text-black mb-1">Laboratory Tests</label>
                                                    <textarea required type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black"
                                                              name="lab_pat_tests" id="editor"></textarea>
                                                </div>

                                                <!-- Submit Button -->
                                                <button type="submit" name="add_patient_lab_test"
                                                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                                    Add Laboratory Test
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
            <?php }?>

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