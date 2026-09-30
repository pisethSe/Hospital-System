<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();


// Discharge patient
if(isset($_GET['pat_number'])){
    $pat_number = $_GET['pat_number'];
    // Set Cambodia timezone first
    date_default_timezone_set('Asia/Phnom_Penh');

// Now generate current datetime in Cambodia time
    $now = date('Y-m-d H:i:s');

// Update patient walk_out_date
    $update = "UPDATE hmisphp.his_patients SET pat_walk_out_date = ? WHERE pat_number = ?";
    $stmt = $mysqli->prepare($update);
    $stmt->bind_param('ss', $now, $pat_number);
    $stmt->execute();
    $stmt->close();


    $_SESSION['success'] = "Patient discharged successfully!";
    header("Location: his_doc_patient_transfer.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<body>

<div id="wrapper">

    <?php include('assets/inc/nav.php'); ?>
    <?php include("assets/inc/sidebar.php"); ?>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                    <li class="breadcrumb-item active">Transfer Patients</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Transfer Patients</h4>
                        </div>
                    </div>
                </div>

                <!-- Patient Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">All Patients</h4>
                            <div class="overflow-x-auto">
                                <div class="overflow-x-auto">
                                    <div class="mb-4 w-40">
                                        <input type="text" id="search_input" placeholder="Search ."
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md  text-sm">
                                    </div>

                                    <table id="patient_table" class="min-w-full border border-gray-300 divide-y divide-gray-300 text-black">

                                        <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-medium">#</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium">Patient Name</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium hidden sm:table-cell">Patient Number</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium hidden sm:table-cell">Category</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium hidden sm:table-cell">WalkIn Date</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium hidden sm:table-cell">WalkOut Date</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium hidden sm:table-cell">Room Number</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-300">
                                        <?php
                                        $ret = "SELECT * 
                                        FROM hmisphp.his_patients 
                                        WHERE pat_type IN ('Inpatient', 'Outpatient')
                                        ORDER BY pat_walk_out_date IS NULL DESC, pat_date_joined DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();

                                        $cnt = 1;
                                        date_default_timezone_set('Asia/Phnom_Penh');
                                        while($row = $res->fetch_object()){
                                            ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-2 text-sm"><?php echo $cnt; ?></td>
                                                <td class="px-4 py-2 text-sm"><?php echo $row->pat_fname . ' ' . $row->pat_lname; ?></td>
                                                <td class="px-4 py-2 text-sm hidden sm:table-cell"><?php echo $row->pat_number; ?></td>
                                                <td class="px-4 py-2 whitespace-nowrap border border-gray-300">
                                                    <?php
                                                    $colors = [
                                                            'OutPatient' => 'green',
                                                            'InPatient' => 'blue',
                                                            'Waiting' => 'yellow'
                                                    ];
                                                    $color = $colors[$row->pat_type] ?? 'gray';
                                                    ?>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-800">
                <?php echo $row->pat_type; ?>
            </span>
                                                </td>
                                                <td class="px-4 py-2 text-sm hidden sm:table-cell"><?php echo date("d-M-Y h:iA", strtotime($row->pat_date_joined)); ?></td>
                                                <td class="px-4 py-2 text-sm hidden sm:table-cell">
                                                    <?php echo $row->pat_walk_out_date ? date("d-M-Y h:iA", strtotime($row->pat_walk_out_date)) : '-'; ?>
                                                </td>
                                                <td class="px-4 py-2 text-sm hidden sm:table-cell"><?php echo $row->pat_room_number; ?></td>
                                                <td class="px-4 py-2 text-sm">
                                                    <?php if(!$row->pat_walk_out_date){ ?>
                                                        <a href="his_doc_patient_transfer.php?pat_number=<?php echo $row->pat_number; ?>"
                                                           class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                                            Discharge
                                                        </a>
                                                    <?php } else { echo 'Discharged'; } ?>
                                                </td>
                                            </tr>
                                            <?php $cnt++; } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Patient Table -->

            </div>
        </div>

        <?php include('assets/inc/footer.php'); ?>

    </div>
</div>
<script>
    document.getElementById('search_input').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#patient_table tbody tr');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            if(text.includes(filter)){
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<div class="rightbar-overlay"></div>
<script src="assets/js/vendor.min.js"></script>
<script src="assets/libs/footable/footable.all.min.js"></script>
<script src="assets/js/pages/foo-tables.init.js"></script>
<script src="assets/js/app.min.js"></script>

</body>
</html>