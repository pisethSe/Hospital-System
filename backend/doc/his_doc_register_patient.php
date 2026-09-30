<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['add_patient'])) {
    $pat_fname = $_POST['pat_fname'];
    $pat_lname = $_POST['pat_lname'];
    $pat_number = $_POST['pat_number'];
    $pat_phone = $_POST['pat_phone'];
    $pat_type = $_POST['pat_type'];
    $pat_addr = $_POST['pat_addr'];
    $pat_age = $_POST['pat_age'];
    $pat_dob = $_POST['pat_dob'];
    $pat_ailment = $_POST['pat_ailment'];
    //sql to insert captured values
    $query = "insert into hmisphp.his_patients (pat_fname, pat_ailment, pat_lname, pat_age, pat_dob, pat_number, pat_phone, pat_type, pat_addr) values(?,?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssssss', $pat_fname, $pat_ailment, $pat_lname, $pat_age, $pat_dob, $pat_number, $pat_phone, $pat_type, $pat_addr);
    $stmt->execute();
    /*
    *Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
    *echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
    */
    //declare a varible which will be passed to alert function
    if ($stmt) {
        $success = "Patient Details Added";
    } else {
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<!--Head-->
<?php include('assets/inc/head.php'); ?>
<body style="color: black">

<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    <?php include("assets/inc/nav.php"); ?>
    <!-- end Topbar -->

    <!-- ========== Left Sidebar Start ========== -->
    <?php include("assets/inc/sidebar.php"); ?>
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
                                    <li class="breadcrumb-item"><a href="his_doc_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                    <li class="breadcrumb-item active">Add Patient</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Add Patient Details</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <!-- Form row -->
                <div class="flex flex-wrap">
                    <div class="w-full px-4">
                        <div class="bg-white rounded-lg shadow-md">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold mb-4 text-black">Fill all fields</h4>
                                <!--Add Patient Form-->
                                <form method="post">
                                    <div class="flex flex-wrap -mx-2 ">
                                        <div class="w-full md:w-1/2 px-2 mb-4">
                                            <label for="inputEmail4" class="block text-sm font-medium mb-1 text-black">First
                                                Name</label>
                                            <input type="text" required name="pat_fname"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-black focus:ring-2 focus:ring-blue-500 outline-none"
                                                   id="inputEmail4" placeholder="Patient's First Name">
                                        </div>
                                        <div class="w-full md:w-1/2 px-2 mb-4">
                                            <label for="inputPassword4"
                                                   class="block text-sm font-medium mb-1 text-black">Last Name</label>
                                            <input required type="text" name="pat_lname"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-black focus:ring-2 focus:ring-blue-500 outline-none"
                                                   id="inputPassword4" placeholder="Patient's Last Name">
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap -mx-2 ">
                                        <!-- Date of Birth -->
                                        <div class="w-full md:w-1/2 px-2 mb-4">
                                            <label for="pat_dob" class="block text-sm font-medium mb-1 text-black">Date
                                                Of Birth</label>
                                            <input type="date" required name="pat_dob"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-black focus:ring-2 focus:ring-blue-500 outline-none"
                                                   id="pat_dob">
                                        </div>

                                        <!-- Age -->
                                        <div class="w-full md:w-1/2 px-2 mb-4">
                                            <label for="pat_age"
                                                   class="block text-sm font-medium mb-1 text-black">Age</label>
                                            <input required type="text" name="pat_age"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-black
              focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none"
                                                   id="pat_age" placeholder="Patient's Age" readonly>
                                        </div>
                                    </div>

                                    <div>
                                        <?php
                                        // Example: all Cambodian addresses
                                        $addresses = ["Banteay Meanchey - បន្ទាយមានជ័យ", "Battambang - បាត់ដំបង", "Kampong Cham - កំពង់ចាម", "Kampong Chhnang - កំពង់ឆ្នាំង", "Kampong Speu - កំពង់ស្ពឺ", "Kampong Thom - កំពង់ធំ", "Kampot - កំពត", "Kandal - កណ្ដាល", "Kep - កែប", "Koh Kong - កោះកុង", "Kratié - ក្រចេះ", "Mondulkiri - មណ្ឌលគិរី", "Pailin - ប៉ៃលិន", "Phnom Penh - ភ្នំពេញ", "Preah Vihear - ព្រះវិហារ", "Prey Veng - ព្រៃវែង", "Pursat - ពោធិសាត់", "Ratanakiri - រតនគីរី", "Siem Reap - សៀមរាប", "Sihanoukville - សីហនុ", "Stung Treng - ស្ទឹងត្រែង", "Svay Rieng - ស្វាយរៀង", "Takeo - តាកែវ", "Tboung Khmum - ត្បូងឃ្មុំ"];


                                        // Handle form submission
                                        $selected_address = '';
                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                            $selected_address = isset($_POST['pat_addr']) ? $_POST['pat_addr'] : '';
                                            // Now you can insert $selected_address into your database
                                        }
                                        ?>

                                        <label for="pat_addr" class="block mb-1 font-medium">Address <span
                                                    class="text-red-600 focus:ring-blue-500">*</span></label>
                                        <select name="pat_addr" id="pat_addr"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 mb-2">
                                            <option value="">-- Select Address --</option>
                                            <?php foreach ($addresses as $addr): ?>
                                                <option value="<?php echo $addr; ?>" <?php if ($addr == $selected_address) echo 'selected'; ?>>
                                                    <?php echo $addr; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="flex flex-wrap gap-6 mb-6">

                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label for="pat_phone" class="block mb-1 font-medium">Mobile Number
                                                    <span class="text-red-600">*</span></label>
                                                <input type="text" required name="pat_phone" id="pat_phone"
                                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>

                                            <div>
                                                <label for="pat_type" class="block mb-1 font-medium">Patient's Type</label>
                                                <select id="pat_type" required name="pat_type"
                                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <option>Waiting</option>
                                                    <option>InPatient</option>
                                                    <option>OutPatient</option>
                                                </select>
                                            </div>

                                            <!-- Ailment (hidden by default) -->
                                            <div id="pat_ailment_div" class="hidden">
                                                <label for="pat_ailment" class="block mb-1 font-medium">Patient Ailment</label>
                                                <input type="text" name="pat_ailment" id="pat_ailment"
                                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>

                                            <!-- Room Number (hidden by default) -->
                                            <div id="room_number_div" class="hidden">
                                                <label for="room_number" class="block mb-1 font-medium">Room Number</label>
                                                <input type="number" name="room_number" id="room_number"
                                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>
                                        </div>


                                        <div class="w-full md:w-1/6 px-2 mb-4 hidden">
                                            <?php
                                            $length = 5;
                                            $patient_number = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
                                            ?>
                                            <label for="inputZip" class="block text-sm font-medium mb-1 text-black">Patient
                                                Number</label>
                                            <input type="text" name="pat_number" value="<?php echo $patient_number; ?>"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-black"
                                                   id="inputZip">
                                        </div>
                                    </div>

                                    <button type="submit" name="add_patient"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors mt-2">
                                        Add Patient
                                    </button>
                                </form>
                                <!--End Patient Form-->
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

        <!-- Footer Start -->
        <?php include('assets/inc/footer.php'); ?>
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
<script>
    document.getElementById("pat_dob").addEventListener("change", function () {
        const dob = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();

        // Adjust if birthday hasn't occurred yet this year
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        // Show age in the input field
        document.getElementById("pat_age").value = age;
    });
    document.getElementById("pat_dob").addEventListener("change", function () {
        const dob = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();

        // Adjust if birthday hasn't occurred yet this year
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        // Show age in the input field
        document.getElementById("pat_age").value = age;
    });

        const patientType = document.getElementById('pat_type');
        const ailmentDiv = document.getElementById('pat_ailment_div');
        const roomDiv = document.getElementById('room_number_div');

        patientType.addEventListener('change', function () {
        if (this.value === 'InPatient') {
        ailmentDiv.classList.remove('hidden');
        roomDiv.classList.remove('hidden');
    } else {
        ailmentDiv.classList.add('hidden');
        roomDiv.classList.add('hidden');
    }
    });
</script>
</body>

</html>