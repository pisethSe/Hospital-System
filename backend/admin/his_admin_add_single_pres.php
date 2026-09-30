
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['add_patient_presc']))
		{
			$pres_pat_name = $_POST['pres_pat_name'];
			$pres_pat_number = $_POST['pres_pat_number'];
            $pres_pat_type = $_POST['pres_pat_type'];
            $pres_pat_addr = $_POST['pres_pat_addr'];
            $pres_pat_age = $_POST['pres_pat_age'];
            $pres_number = $_POST['pres_number'];
            $pres_ins = $_POST['pres_ins'];
            $pres_pat_ailment = $_POST['pres_pat_ailment'];
            //sql to insert captured values
			$query="INSERT INTO  hmisphp.his_prescriptions  (pres_pat_name, pres_pat_number, pres_pat_type, pres_pat_addr, pres_pat_age, pres_number, pres_pat_ailment, pres_ins) VALUES(?,?,?,?,?,?,?,?)";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('ssssssss', $pres_pat_name, $pres_pat_number, $pres_pat_type, $pres_pat_addr, $pres_pat_age, $pres_number, $pres_pat_ailment, $pres_ins);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Patient Prescription Addded";
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
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Pharmacy</a></li>
                                                <li class="breadcrumb-item active">Add Prescription</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Add Patient Prescription</h4>
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
                                            <form method="post" class="space-y-6">

                                                <!-- Patient Name & Age -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Patient Name</label>
                                                        <input type="text" readonly name="pres_pat_name" value="<?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?>"
                                                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Patient's Name" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Patient Age</label>
                                                        <input type="text" readonly name="pres_pat_age" value="<?php echo $row->pat_age;?>"
                                                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Patient Age" required>
                                                    </div>
                                                </div>

                                                <!-- Patient Number, Address & Type -->
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Patient Number</label>
                                                        <input type="text" readonly name="pres_pat_number" value="<?php echo $row->pat_number;?>"
                                                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Patient Number" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Patient Address</label>
                                                        <input type="text" readonly name="pres_pat_addr" value="<?php echo $row->pat_addr;?>"
                                                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Patient Address" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Patient Type</label>
                                                        <input type="text" readonly name="pres_pat_type" value="<?php echo $row->pat_type;?>"
                                                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Patient Type" required>
                                                    </div>
                                                </div>

                                                <!-- Patient Ailment -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Diagosis</label>
                                                    <input type="text" value="<?php echo $row->pat_ailment;?>" name="pres_pat_ailment"
                                                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                           required>
                                                </div>

                                                <!-- Hidden Prescription Number -->
                                                <div class="hidden">
                                                    <?php
                                                    $length = 5;
                                                    $pres_no =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                                                    ?>
                                                    <label class="block text-sm font-medium text-gray-700">Prescription Number</label>
                                                    <input type="text" name="pres_number" value="<?php echo $pres_no;?>"
                                                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                </div>

                                                <!-- Prescription Instructions -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Prescription</label>
                                                    <textarea name="pres_ins" id="editor" required
                                                              class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                              rows="4"></textarea>
                                                </div>

                                                <!-- Submit Button -->
                                                <div>
                                                    <button type="submit" name="add_patient_presc"
                                                            class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                        Add Patient Prescription
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