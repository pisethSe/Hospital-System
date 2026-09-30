
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['update_pharmaceutical_category']))
		{
			$pharm_cat_name = $_GET['pharm_cat_name'];
			$pharm_cat_vendor = $_POST['pharm_cat_vendor'];
			$pharm_cat_desc=$_POST['pharm_cat_desc'];
            
            
            //sql to update captured values
			$query="UPDATE  hmisphp.his_pharmaceuticals_categories SET  pharm_cat_vendor=?, pharm_cat_desc=? WHERE pharm_cat_name = ?";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('sss',   $pharm_cat_vendor, $pharm_cat_desc, $pharm_cat_name);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Pharmaceutical Category Upadated ";
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
                $pharm_cat_name=$_GET['pharm_cat_name'];
                $ret="SELECT  * FROM hmisphp.his_pharmaceuticals_categories WHERE pharm_cat_name=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('s',$pharm_cat_name);
                $stmt->execute() ;//ok
                $res=$stmt->get_result();
                //$cnt=1;
                while($row=$res->fetch_object())
                {
            ?>
            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container mx-auto px-4 py-4">
                        <!-- start page title -->
                        <div class="flex flex-wrap">
                            <div class="w-full">
                                <div class="flex justify-between items-center mb-6">
                                    <div class="text-right">
                                        <ol class="flex flex-wrap list-reset text-sm text-black">
                                            <li class="inline-block px-2">
                                                <a href="his_doc_dashboard.php" class="text-blue-600 hover:text-blue-800">Dashboard</a>
                                            </li>
                                            <li class="inline-block px-2 text-gray-500">/</li>
                                            <li class="inline-block px-2">
                                                <a href="javascript:void(0);" class="text-blue-600 hover:text-blue-800">Pharmaceuticals</a>
                                            </li>
                                            <li class="inline-block px-2 text-gray-500">/</li>
                                            <li class="inline-block px-2 font-medium text-black">Manage Pharmaceutical Category</li>
                                        </ol>
                                    </div>
                                    <h4 class="text-xl font-semibold text-black"><?php echo $row->pharm_cat_name;?></h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <!-- Form row -->
                        <div class="flex flex-wrap">
                            <div class="w-full">
                                <div class="bg-white rounded-lg shadow-md mb-6">
                                    <div class="p-6">
                                        <h4 class="text-lg font-semibold mb-4 text-black">Fill all fields</h4>
                                        <!--Add Patient Form-->
                                        <form method="post">
                                            <div class="flex flex-wrap -mx-2">
                                                <div class="w-full md:w-1/2 px-2 hidden">
                                                    <div class="mb-4">
                                                        <label for="inputEmail4" class="block mb-2 text-sm font-medium text-black">Pharmaceutical Category Name</label>
                                                        <input type="text" value="<?php echo $row->pharm_cat_name;?>" required name="pharm_cat_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-black" id="inputEmail4">
                                                    </div>
                                                </div>
                                                <div class="w-full px-2">
                                                    <div class="mb-4">
                                                        <label for="inputPassword4" class="block mb-2 text-sm font-medium text-black">Pharmaceutical Category Vendor</label>
                                                        <input required value="<?php echo $row->pharm_cat_vendor;?>" type="text" name="pharm_cat_vendor" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-black" id="inputPassword4">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label for="inputAddress" class="block mb-2 text-sm font-medium text-black">Pharmaceutical Category Description</label>
                                                <textarea required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-black" name="pharm_cat_desc" id="editor"><?php echo $row->pharm_cat_desc;?></textarea>
                                            </div>

                                            <button type="submit" name="update_pharmaceutical_category" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition duration-300">
                                                Update Category
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                    </div><!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include('assets/inc/footer.php');?>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->
                <?php }?>

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