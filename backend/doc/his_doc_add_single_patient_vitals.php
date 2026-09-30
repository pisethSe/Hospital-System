<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['add_patient_vitals']))
		{
			$vit_number = $_POST['vit_number'];
			$vit_pat_number = $_POST['vit_pat_number'];
            $vit_bodytemp  = $_POST['vit_bodytemp'];
            $vit_heartpulse = $_POST['vit_heartpulse'];
            $vit_resprate  = $_POST['vit_resprate'];
            $vit_bloodpress = $_POST['vit_bloodpress'];
            //$pres_ins = $_POST['pres_ins'];
            //$pres_pat_ailment = $_POST['pres_pat_ailment'];
            //sql to insert captured values
			$query="INSERT INTO  his_vitals  (vit_number, vit_pat_number, vit_bodytemp, vit_heartpulse, vit_resprate, vit_bloodpress) VALUES(?,?,?,?,?,?)";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('ssssss', $vit_number, $vit_pat_number, $vit_bodytemp, $vit_heartpulse, $vit_resprate, $vit_bloodpress);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Patient Vitals Addded";
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
                                                <li class="breadcrumb-item"><a href="his_doc_dashboard.php">Dashboard</a></li>
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Laboratory</a></li>
                                                <li class="breadcrumb-item active">Capture Vitals</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Capture <?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?> Vitals</h4>
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
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <!-- Patient Name -->
                                                    <div>
                                                        <label for="inputEmail4" class="block text-sm font-medium text-gray-700">Patient Name</label>
                                                        <input
                                                                type="text"
                                                                id="inputEmail4"
                                                                value="<?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?>"
                                                                readonly
                                                                required
                                                                placeholder="Patient's First Name"
                                                                class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        >
                                                    </div>

                                                    <!-- Patient Ailment -->
                                                    <div>
                                                        <label for="inputPassword4" class="block text-sm font-medium text-gray-700">Patient Ailment</label>
                                                        <input
                                                                type="text"
                                                                id="inputPassword4"
                                                                value="<?php echo $row->pat_ailment; ?>"
                                                                readonly
                                                                required
                                                                placeholder="Patient's Ailment"
                                                                class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        >
                                                    </div>
                                                </div>


                                                <div class="form-row">

                                                    <div class="form-group col-md-12">
                                                        <label for="inputEmail4" class="col-form-label">Patient Number</label>
                                                        <input
                                                                type="text"
                                                                id="inputEmail4"
                                                                name="vit_pat_number"
                                                                value="<?php echo $row->pat_number; ?>"
                                                                readonly
                                                                required
                                                                placeholder="DD/MM/YYYY"
                                                                class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        >
                                                    </div>


                                                </div>


                                                <hr class="my-6 border-gray-300">
                                                <div class="form-row">
                                                    
                                            
                                                    <div class="form-group col-md-2" style="display:none">
                                                        <?php 
                                                            $length = 5;    
                                                            $vit_no =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                                                        ?>
                                                        <label for="inputZip" class="col-form-label">Vital Number</label>
                                                        <input type="text" name="vit_number" value="<?php echo $vit_no;?>" class="form-control" id="inputZip">
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                    <!-- Body Temperature -->
                                                    <div>
                                                        <label for="vit_bodytemp" class="block text-sm font-medium text-gray-700">Patient Body Temperature °C</label>
                                                        <input
                                                                type="text"
                                                                name="vit_bodytemp"
                                                                id="vit_bodytemp"
                                                                required
                                                                placeholder="°C"
                                                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        >
                                                    </div>

                                                    <!-- Heart Pulse -->
                                                    <div>
                                                        <label for="vit_heartpulse" class="block text-sm font-medium text-gray-700">Patient Heart Pulse/Beat BPM</label>
                                                        <input
                                                                type="text"
                                                                name="vit_heartpulse"
                                                                id="vit_heartpulse"
                                                                required
                                                                placeholder="HeartBeats Per Minute"
                                                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        >
                                                    </div>

                                                    <!-- Respiratory Rate -->
                                                    <div>
                                                        <label for="vit_resprate" class="block text-sm font-medium text-gray-700">Patient Respiratory Rate bpm</label>
                                                        <input
                                                                type="text"
                                                                name="vit_resprate"
                                                                id="vit_resprate"
                                                                required
                                                                placeholder="Breathes Per Minute"
                                                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        >
                                                    </div>

                                                    <!-- Blood Pressure -->
                                                    <div >
                                                        <label for="vit_bloodpress" class="block text-sm font-medium text-gray-700">Patient Blood Pressure mmHg</label>
                                                        <input
                                                                type="text"
                                                                name="vit_bloodpress"
                                                                id="vit_bloodpress"
                                                                required
                                                                placeholder="mmHg"
                                                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        >
                                                    </div>
                                                </div>


                                                <button
                                                        type="submit"
                                                        name="add_patient_vitals"
                                                        class="mt-2 inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition-colors duration-200"
                                                >
                                                    Add Vitals
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