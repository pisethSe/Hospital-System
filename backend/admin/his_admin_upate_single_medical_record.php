<!--Server side code to handle  Patient Registration-->
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['update_patient_mdr']))
		{
			//$mdr_pat_name = $_POST['mdr_pat_name'];
			//$mdr_pat_number = $_POST['mdr_pat_number'];
            //$pres_pat_type = $_POST['pres_pat_type'];
            $mdr_pat_adr = $_POST['mdr_pat_adr'];
            $mdr_pat_age = $_POST['mdr_pat_age'];
            $mdr_number = $_GET['mdr_number'];
            $mdr_pat_prescr = $_POST['mdr_pat_prescr'];
            $mdr_pat_ailment = $_POST['mdr_pat_ailment'];
            //sql to insert captured values
			$query="UPDATE  hmisphp.his_medical_records SET  mdr_pat_adr = ?, mdr_pat_age = ?,  mdr_pat_prescr = ?, mdr_pat_ailment = ? WHERE mdr_number = ?";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('sssss',  $mdr_pat_adr, $mdr_pat_age, $mdr_pat_prescr, $mdr_pat_ailment, $mdr_number);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Patient Medical Record Updated";
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
                $mdr_number = $_GET['mdr_number'];
                $ret="SELECT  * FROM hmisphp.his_medical_records WHERE mdr_number=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('s',$mdr_number);
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
                                                <li class="breadcrumb-item active">Manage Medical Record</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Update Medical Record</h4>
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
                                                <div class="flex flex-wrap -mx-2 mb-4">
                                                    <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
                                                        <label for="mdr_pat_name" class="block text-sm font-medium text-gray-700 mb-1">Patient Name</label>
                                                        <input type="text" id="mdr_pat_name" name="mdr_pat_name" value="<?php echo $row->mdr_pat_name;?>" readonly
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Patient's Name" required>
                                                    </div>

                                                    <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
                                                        <label for="mdr_pat_age" class="block text-sm font-medium text-gray-700 mb-1">Patient Age</label>
                                                        <input type="text" id="mdr_pat_age" name="mdr_pat_age" value="<?php echo $row->mdr_pat_age;?>"
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Patient's Age" required>
                                                    </div>

                                                    <div class="w-full md:w-1/3 px-2">
                                                        <label for="mdr_pat_adr" class="block text-sm font-medium text-gray-700 mb-1">Patient Address</label>
                                                        <input type="text" id="mdr_pat_adr" name="mdr_pat_adr" value="<?php echo $row->mdr_pat_adr;?>"
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Patient's Address" required>
                                                    </div>
                                                </div>


                                                <div class="flex flex-wrap -mx-2 mb-4">
                                                    <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0">
                                                        <label for="mdr_pat_number" class="block text-sm font-medium text-gray-700 mb-1">Patient Number</label>
                                                        <input type="text" id="mdr_pat_number" name="mdr_pat_number" value="<?php echo $row->mdr_pat_number;?>" readonly
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="DD/MM/YYYY" required>
                                                    </div>

                                                    <div class="w-full md:w-1/2 px-2">
                                                        <label for="mdr_pat_ailment" class="block text-sm font-medium text-gray-700 mb-1">Patient Ailment</label>
                                                        <input type="text" id="mdr_pat_ailment" name="mdr_pat_ailment" value="<?php echo $row->mdr_pat_ailment;?>"
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Patient's Ailment" required>
                                                    </div>
                                                </div>


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
                                                
                                                <div class="form-group">
                                                        <label for="inputAddress" class="col-form-label">Patient's Prescription</label>
                                                        <textarea required="required"  type="text" class="form-control" name="mdr_pat_prescr" id="editor"><?php echo $row->mdr_pat_prescr;?> </textarea>
                                                </div>
                                                <?php }?>

                                                <button type="submit" name="update_patient_mdr"
                                                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-yellow-400">
                                                    Update Patient Medical Record
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