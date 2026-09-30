<!--Server side code to handle  Patient Registration-->
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['add_patient_mdr']))
		{
			$mdr_pat_name = $_POST['mdr_pat_name'];
			$mdr_pat_number = $_POST['mdr_pat_number'];
            //$pres_pat_type = $_POST['pres_pat_type'];
            $mdr_pat_adr = $_POST['mdr_pat_adr'];
            $mdr_pat_age = $_POST['mdr_pat_age'];
            $mdr_number = $_POST['mdr_number'];
            $mdr_pat_prescr = $_POST['mdr_pat_prescr'];
            $mdr_pat_ailment = $_POST['mdr_pat_ailment'];
            //sql to insert captured values
			$query="INSERT INTO  hmisphp.his_medical_records  (mdr_pat_name, mdr_pat_number, mdr_pat_adr, mdr_pat_age, mdr_number, mdr_pat_prescr, mdr_pat_ailment) VALUES(?,?,?,?,?,?,?)";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('sssssss', $mdr_pat_name, $mdr_pat_number, $mdr_pat_adr, $mdr_pat_age, $mdr_number, $mdr_pat_prescr, $mdr_pat_ailment);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Patient Medical Record Addded";
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
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Medical Records</a></li>
                                                <li class="breadcrumb-item active">Add Medical Record</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Add Medical Record</h4>
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
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                                    <div>
                                                        <label for="mdr_pat_name" class="block text-sm font-medium text-gray-700">Patient Name</label>
                                                        <input type="text" id="mdr_pat_name" name="mdr_pat_name" readonly
                                                               value="<?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?>"
                                                               placeholder="Patient's Name"
                                                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               required>
                                                    </div>

                                                    <div>
                                                        <label for="mdr_pat_age" class="block text-sm font-medium text-gray-700">Patient Age</label>
                                                        <input type="text" id="mdr_pat_age" name="mdr_pat_age" readonly
                                                               value="<?php echo $row->pat_age;?>"
                                                               placeholder="Patient's Age"
                                                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               required>
                                                    </div>

                                                    <div>
                                                        <label for="mdr_pat_adr" class="block text-sm font-medium text-gray-700">Patient Address</label>
                                                        <input type="text" id="mdr_pat_adr" name="mdr_pat_adr" readonly
                                                               value="<?php echo $row->pat_addr;?>"
                                                               placeholder="Patient's Address"
                                                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               required>
                                                    </div>

                                                </div>


                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                                                    <div>
                                                        <label for="mdr_pat_number" class="block text-sm font-medium text-gray-700">Patient Number</label>
                                                        <input type="text" id="mdr_pat_number" name="mdr_pat_number" readonly
                                                               value="<?php echo $row->pat_number;?>"
                                                               placeholder="DD/MM/YYYY"
                                                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               required>
                                                    </div>

                                                    <div class="">
                                                        <label for="mdr_pat_ailment" class="block text-sm font-medium text-gray-700">Patient Ailment</label>
                                                        <input type="text" id="mdr_pat_ailment" name="mdr_pat_ailment" readonly
                                                               value="<?php echo $row->pat_ailment;?>"
                                                               placeholder="Patient's Ailment"
                                                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               required>
                                                    </div>

                                                </div>

                                                <?php }?>
                                                <hr>
                                                <div class="form-row">


                                                    <div class="form-group col-md-2" style="display:none">
                                                        <?php
                                                            $length = 5;
                                                            $pres_no =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                                                        ?>
                                                        <label for="inputZip" class="col-form-label">Medical Record Number</label>
                                                        <input type="text" name="mdr_number" value="<?php echo $pres_no;?>" class="form-control" id="inputZip">
                                                    </div>
                                                </div>
                                                <?php
                                                    $pres_pat_number = $_GET['pat_number'];
                                                    $ret="SELECT  * FROM hmisphp.his_prescriptions WHERE pres_pat_number = ?";
                                                    $stmt= $mysqli->prepare($ret) ;
                                                    $stmt->bind_param('s',$pres_pat_number);
                                                    $stmt->execute() ;//ok
                                                    $res=$stmt->get_result();
                                                    //$cnt=1;
                                                    while($row=$res->fetch_object())
                                                    {
                                                ?>
                                                        <div class="form-group">
                                                            <label for="inputAddress" class="col-form-label">Patient's Prescription</label>
                                                            <textarea required="required"  type="text" class="form-control" name="mdr_pat_prescr" id="editor"><?php echo $row->pres_ins;?> </textarea>
                                                        </div>

                                                    <?php }?>

                                                <button type="submit" name="add_patient_mdr"
                                                        class=" mt-2 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">
                                                    Add Patient Medical Record
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