<div class="left-side-menu fixed left-0  top-0 bottom-0 w-16 md:w-64  shadow-md z-10 transition-all duration-300">

    <div class="h-full overflow-y-auto  ">
        <!-- Sidemenu -->
        <div class="px-2 md:px-4 py-6 font-bold ">
            <ul class="space-y-2 text-white">
                <li class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:block">
                    Navigation
                </li>

                <!-- Dashboard -->
                <li class="text-white"><a href="his_admin_dashboard.php"
                                          class="flex items-center justify-center md:justify-start px-2 md:px-3 py-2 rounded-lg transition-colors hover:bg-blue-200 hover:text-blue-600 <?php echo basename($_SERVER['PHP_SELF']) == 'his_doc_dashboard.php' ? 'bg-blue-50 text-blue-600' : 'text-gray-700' ?>"
                                          title="Dashboard">
                        <svg class="w-5 h-5 <?php echo basename($_SERVER['PHP_SELF']) == 'his_doc_dashboard.php' ? 'text-blue-500' : 'text-gray-500' ?>"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="ml-1 hidden md:block ">Dashboard</span> </a></li>


                <!-- Patients -->
                <li>
                    <?php
                    $patientsActive = in_array(basename($_SERVER['PHP_SELF']), [
                            'his_admin_register_patient.php',
                            'his_admin_view_patients.php',
                            'his_admin_manage_patient.php',
                            'his_admin_discharge_patient.php',
                            'his_admin_patient_transfer.php'
                    ]);
                    ?>
                    <button type="button"
                            class="flex items-center justify-between w-full px-2 md:px-3 py-2 rounded-lg transition-colors
                   hover:bg-blue-200 hover:text-blue-600
                   <?php echo $patientsActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700' ?>"
                            aria-expanded="<?php echo $patientsActive ? 'true' : 'false' ?>"
                            title="Patients">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 <?php echo $patientsActive ? 'text-blue-500' : 'text-gray-500' ?>"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 hidden md:block">Patients</span>
                        </div>
                        <svg class="w-4 h-4 <?php echo $patientsActive ? 'text-blue-500 rotate-180' : 'text-gray-500' ?> transform transition-transform hidden md:block"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <ul class="mt-1 pl-0 space-y-1 <?php echo $patientsActive ? '' : 'hidden' ?>"
                        aria-hidden="<?php echo $patientsActive ? 'false' : 'true' ?>">
                        <?php
                        $patientsSubmenu = [
                                'his_admin_register_patient.php' => 'Register Patient',
                                'his_admin_view_patients.php' => 'View Patients',
                                'his_admin_manage_patient.php' => 'Manage Patients',
                                'his_admin_patient_transfer.php' => 'Discharge'
                        ];
                        foreach ($patientsSubmenu as $file => $label):
                            $active = basename($_SERVER['PHP_SELF']) == $file ? 'bg-blue-200 text-blue-600' : 'text-gray-600';
                            ?>
                            <li class="<?php echo $label == 'Patient Transfers' ? 'border-t border-gray-200 my-1' : ''; ?>">
                                <a href="<?php echo $file; ?>"
                                   class="flex items-center px-2 py-2 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-600 <?php echo $active ?>"
                                   title="<?php echo $label; ?>">
                                    <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                    </svg>
                                    <span class="ml-8 md:ml-8 hidden md:block"><?php echo $label; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>


                <!-- Pharmacy -->
                <li>
                    <?php
                    $pharmacyActive = in_array(basename($_SERVER['PHP_SELF']), [
                            'his_admin_add_pharm_cat.php', 'his_admin_view_pharm_cat.php', 'his_admin_manage_pharm_cat.php',
                            'his_admin_add_pharmaceuticals.php', 'his_admin_view_pharmaceuticals.php', 'his_admin_manage_pharmaceuticals.php',
                            'his_admin_add_presc.php', 'his_admin_view_presc.php', 'his_admin_manage_presc.php'
                    ]);
                    ?>
                    <button type="button"
                            class="flex items-center justify-between w-full px-2 md:px-3 py-2 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-600 <?php echo $pharmacyActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700' ?>"
                            aria-expanded="<?php echo $pharmacyActive ? 'true' : 'false' ?>"
                            title="Pharmacy">
                        <div class="flex items-center">
                            <!-- Pill Icon -->
                            <svg class="w-5 h-5 <?php echo $pharmacyActive ? 'text-blue-500' : 'text-gray-500' ?>" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.79 4.21a5.5 5.5 0 00-7.78 0l-8.5 8.5a5.5 5.5 0 107.78 7.78l8.5-8.5a5.5 5.5 0 000-7.78zM7.5 20a3.5 3.5 0 01-2.47-5.97l4.24-4.24 4.95 4.95-4.24 4.24A3.48 3.48 0 017.5 20z"/>
                            </svg>
                            <span class="ml-1 hidden md:block">Pharmacy</span>
                        </div>
                        <!-- Dropdown arrow -->
                        <svg class="w-4 h-4 <?php echo $pharmacyActive ? 'text-blue-500 rotate-180' : 'text-gray-500' ?> transform transition-transform hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Sub-menu -->
                    <ul class="mt-1 space-y-1 pl-0 <?php echo $pharmacyActive ? '' : 'hidden' ?>" aria-hidden="<?php echo $pharmacyActive ? 'false' : 'true' ?>">
                        <?php
                        $pharmSubmenu = [
                                'his_admin_add_pharm_cat.php' => 'Add Pharm Category',
                                'his_admin_view_pharm_cat.php' => 'View Pharm Category',
                                'his_admin_manage_pharm_cat.php' => 'Manage Pharm Category',
                                'divider1' => 'divider',
                                'his_admin_add_pharmaceuticals.php' => 'Add Pharmaceuticals',
                                'his_admin_view_pharmaceuticals.php' => 'View Pharmaceuticals',
                                'his_admin_manage_pharmaceuticals.php' => 'Manage Pharmaceuticals',
                                'divider2' => 'divider',
                                'his_admin_add_presc.php' => 'Add Prescriptions',
                                'his_admin_view_presc.php' => 'View Prescriptions',
                                'his_admin_manage_presc.php' => 'Manage Prescriptions'
                        ];
                        foreach ($pharmSubmenu as $file => $label):
                            if ($label === 'divider') {
                                echo '<li class="border-t border-gray-200 my-1"></li>';
                                continue;
                            }
                            $active = basename($_SERVER['PHP_SELF']) == $file ? 'bg-blue-200 text-blue-600' : 'text-gray-600';
                            ?>
                            <li>
                                <a href="<?php echo $file; ?>"
                                   class="flex items-center px-2 py-2 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-600 <?php echo $active ?>">
                                    <span class="ml-8 md:ml-8 hidden md:block"><?php echo $label; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>


                <!--            Employee-->
                <li>
                    <?php
                    $employeeActive = in_array(basename($_SERVER['PHP_SELF']), [
                            'his_admin_add_employee.php', 'his_admin_view_employee.php', 'his_admin_manage_employee.php',
                            'his_admin_assaign_dept.php', 'his_admin_transfer_employee.php'
                    ]);
                    ?>
                    <button type="button"
                            class="flex items-center justify-between w-full px-2 md:px-3 py-2 rounded-lg transition-colors
                   hover:bg-blue-50 hover:text-blue-600
                   <?php echo $employeeActive ? 'bg-blue-200 text-blue-600' : 'text-gray-700' ?>"
                            aria-expanded="<?php echo $employeeActive ? 'true' : 'false' ?>"
                            title="Employee">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 <?php echo $employeeActive ? 'text-blue-500' : 'text-gray-500' ?>"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 hidden md:block ">Employee</span>
                        </div>
                        <svg class="w-4 h-4 <?php echo $employeeActive ? 'text-blue-500 rotate-180' : 'text-gray-500' ?> transform transition-transform hidden md:block"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Submenu -->
                    <ul class="mt-1 pl-0 space-y-1 <?php echo $employeeActive ? '' : 'hidden' ?>"
                        aria-hidden="<?php echo $employeeActive ? 'false' : 'true' ?>">
                        <?php
                        $employeeSubmenu = [
                                'his_admin_add_employee.php' => 'Add Employee',
                                'his_admin_view_employee.php' => 'View Employee',
                                'his_admin_manage_employee.php' => 'Manage Employee',
                                'his_admin_assaign_dept.php' => 'Assign Department',
                                'his_admin_transfer_employee.php' => 'Employee Transfers'
                        ];
                        foreach ($employeeSubmenu as $file => $label):
                            $active = basename($_SERVER['PHP_SELF']) == $file ? 'bg-blue-200 text-blue-600' : 'text-gray-600';
                            ?>
                            <li class="<?php echo $label == 'Employee Transfers' ? 'border-t border-gray-200 my-1' : ''; ?>">
                                <a href="<?php echo $file; ?>"
                                   class="flex items-center px-2 py-2 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-600 <?php echo $active ?>"
                                   title="<?php echo $label; ?>">
                                    <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                    </svg>
                                    <span class="ml-8 hidden md:block"><?php echo $label; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>






                <!-- Inventory -->
<!--                <li>-->
<!--                    --><?php
//                    $inventoryActive = in_array(basename($_SERVER['PHP_SELF']), ['his_admin_pharm_inventory.php', 'his_admin_equipments_inventory.php']);
//                    ?>
<!--                    <button type="button"-->
<!--                            class="flex items-center justify-between w-full px-2 md:px-3 py-2 rounded-lg transition-colors-->
<!--                   hover:bg-blue-50 hover:text-blue-600-->
<!--                   --><?php //echo $inventoryActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700' ?><!--"-->
<!--                            aria-expanded="--><?php //echo $inventoryActive ? 'true' : 'false' ?><!--"-->
<!--                            title="Inventory">-->
<!--                        <div class="flex items-center">-->
<!--                            <svg class="w-5 h-5 --><?php //echo $inventoryActive ? 'text-blue-500' : 'text-gray-500' ?><!--"-->
<!--                                 fill="currentColor" viewBox="0 0 24 24">-->
<!--                                <path d="M3 7a1 1 0 011-1h16a1 1 0 011 1v11a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>-->
<!--                                <path d="M3 7V5a2 2 0 012-2h14a2 2 0 012 2v2H3z"/>-->
<!--                            </svg>-->
<!--                            <span class="ml-3 hidden md:block">Inventory</span>-->
<!--                        </div>-->
<!--                        <svg class="w-4 h-4 --><?php //echo $inventoryActive ? 'text-blue-500 rotate-180' : 'text-gray-500' ?><!-- transform transition-transform hidden md:block"-->
<!--                             fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
<!--                                  d="M19 9l-7 7-7-7"></path>-->
<!--                        </svg>-->
<!--                    </button>-->
<!---->
<!--                    <ul class="mt-1 pl-0 space-y-1 --><?php //echo $inventoryActive ? '' : 'hidden' ?><!--"-->
<!--                        aria-hidden="--><?php //echo $inventoryActive ? 'false' : 'true' ?><!--">-->
<!--                        <li>-->
<!--                            <a href="his_admin_pharm_inventory.php"-->
<!--                               class="flex items-center px-2 py-2 rounded-lg transition-colors-->
<!--                      hover:bg-blue-50 hover:text-blue-600-->
<!--                      --><?php //echo basename($_SERVER['PHP_SELF']) == 'his_admin_pharm_inventory.php' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' ?><!--">-->
<!--                                <span class="ml-8 md:ml-8">--><?php //echo 'Pharmaceuticals'; ?><!--</span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_equipments_inventory.php"-->
<!--                               class="flex items-center px-2 py-2 rounded-lg transition-colors-->
<!--                      hover:bg-blue-50 hover:text-blue-600-->
<!--                      --><?php //echo basename($_SERVER['PHP_SELF']) == 'his_admin_equipments_inventory.php' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' ?><!--">-->
<!--                                <span class="ml-8 md:ml-8">--><?php //echo 'Assets'; ?><!--</span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->


                <!--Reporting-->
                <li>
                    <?php
                    $reportingActive = in_array(basename($_SERVER['PHP_SELF']), [
                            'his_admin_inpatient_records.php', 'his_admin_outpatient_records.php', 'his_admin_employee_records.php',
                            'his_admin_pharmaceutical_records.php', 'his_admin_accounting_records.php', 'his_admin_medical_records.php','his_admin_discharge_records.php'

                    ]);
                    ?>
                    <button type="button"
                            class="flex items-center justify-between w-full px-2 md:px-3 py-2 rounded-lg transition-colors
                   hover:bg-blue-50 hover:text-blue-600
                   <?php echo $reportingActive ? 'bg-blue-200 text-blue-600' : 'text-gray-700' ?>"
                            aria-expanded="<?php echo $reportingActive ? 'true' : 'false' ?>"
                            title="Reporting">
                        <div class="flex items-center">
                            <!-- Reporting Icon -->
                            <svg class="w-5 h-5 <?php echo $reportingActive ? 'text-blue-500' : 'text-gray-500' ?>"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 17v-6h2v6H9zm4 0V7h2v10h-2zm-8 0v-2h2v2H5zm12 0v-8h2v8h-2zM3 3h18v2H3V3z"/>
                            </svg>
                            <span class="ml-1 hidden md:block">Reporting</span>
                        </div>
                        <!-- Dropdown arrow -->
                        <svg class="w-4 h-4 <?php echo $reportingActive ? 'text-blue-500 rotate-180' : 'text-gray-500' ?> transform transition-transform hidden md:block"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Submenu -->
                    <ul class="mt-1 space-y-1 pl-0 <?php echo $reportingActive ? '' : 'hidden' ?>"
                        aria-hidden="<?php echo $reportingActive ? 'false' : 'true' ?>">
                        <?php
                        $reportingSubmenu = [
                                'his_admin_inpatient_records.php' => 'InPatient Records',
                                'his_admin_outpatient_records.php' => 'OutPatient Records',
                                'his_admin_employee_records.php' => 'Employee Records',
                                'his_admin_pharmaceutical_records.php' => 'Pharmaceutical Records',
                                'his_admin_medical_records.php' => 'Medical Records',
                                'his_admin_discharge_records.php' => 'Discharge Records'
                        ];
                        foreach ($reportingSubmenu as $file => $label):
                            $active = basename($_SERVER['PHP_SELF']) == $file ? 'bg-blue-200 text-blue-600' : 'text-gray-600';
                            ?>
                            <li>
                                <a href="<?php echo $file; ?>"
                                   class="flex items-center px-2 py-2 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-600 <?php echo $active ?>"
                                   title="<?php echo $label; ?>">
                                    <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                    </svg>
                                    <span class="ml-8 hidden md:block"><?php echo $label; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <!--Medical Reporting-->
                <li>
                    <?php
                    $medicalRecordsActive = in_array(basename($_SERVER['PHP_SELF']), [
                            'his_admin_add_medical_record.php', 'his_admin_manage_medical_record.php'
                    ]);
                    ?>
                    <button type="button"
                            class="flex items-center justify-between w-full px-2 md:px-3 py-2 rounded-lg transition-colors
                   hover:bg-blue-50 hover:text-blue-600
                   <?php echo $medicalRecordsActive ? 'bg-blue-200 text-blue-600' : 'text-gray-700' ?>"
                            aria-expanded="<?php echo $medicalRecordsActive ? 'true' : 'false' ?>"
                            title="Medical Records">
                        <div class="flex items-center">
                            <!-- Medical Records Icon -->
                            <svg class="w-5 h-5 <?php echo $medicalRecordsActive ? 'text-blue-500' : 'text-gray-500' ?>"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l2 2h5a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="ml-1 hidden md:block">Medical Records</span>
                        </div>
                        <!-- Dropdown arrow -->
                        <svg class="w-4 h-4 <?php echo $medicalRecordsActive ? 'text-blue-500 rotate-180' : 'text-gray-500' ?> transform transition-transform hidden md:block"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Submenu -->
                    <ul class="mt-1 space-y-1 pl-0 <?php echo $medicalRecordsActive ? '' : 'hidden' ?>"
                        aria-hidden="<?php echo $medicalRecordsActive ? 'false' : 'true' ?>">
                        <?php
                        $medicalSubmenu = [
                                'his_admin_add_medical_record.php' => 'Add Medical Record',
                                'his_admin_manage_medical_record.php' => 'Manage Medical Records'
                        ];
                        foreach ($medicalSubmenu as $file => $label):
                            $active = basename($_SERVER['PHP_SELF']) == $file ? 'bg-blue-200 text-blue-600' : 'text-gray-600';
                            ?>
                            <li>
                                <a href="<?php echo $file; ?>"
                                   class="flex items-center px-2 py-2 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-600 <?php echo $active ?>"
                                   title="<?php echo $label; ?>">
                                    <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                    </svg>
                                    <span class="ml-8 hidden md:block"><?php echo $label; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <!--Labo-->
                <li>
                    <?php
                    $labActive = in_array(basename($_SERVER['PHP_SELF']), [
                            'his_admin_patient_lab_test.php', 'his_admin_patient_lab_result.php',
                            'his_admin_patient_lab_vitals.php', 'his_admin_employee_lab_vitals.php',
                            'his_admin_lab_report.php',
//                            'his_admin_add_lab_equipment.php', 'his_admin_manage_lab_equipment.php'
                    ]);
                    ?>
                    <button type="button"
                            class="flex items-center justify-between w-full px-2 md:px-3 py-2 rounded-lg transition-colors
                   hover:bg-blue-50 hover:text-blue-600
                   <?php echo $labActive ? 'bg-blue-200 text-blue-600' : 'text-gray-700' ?>"
                            aria-expanded="<?php echo $labActive ? 'true' : 'false' ?>"
                            title="Laboratory">
                        <div class="flex items-center">
                            <!-- Lab Icon -->
                            <svg class="w-5 h-5 <?php echo $labActive ? 'text-blue-500' : 'text-gray-500' ?>"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 2v6l-2 7h10l-2-7V2M9 2h6M4 21h16"/>
                            </svg>
                            <span class="ml-1 hidden md:block">Laboratory</span>
                        </div>
                        <!-- Dropdown Arrow -->
                        <svg class="w-4 h-4 <?php echo $labActive ? 'text-blue-500 rotate-180' : 'text-gray-500' ?> transform transition-transform hidden md:block"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <ul class="mt-1 space-y-1 pl-0 md:pl-8 <?php echo $labActive ? '' : 'hidden' ?>"
                        aria-hidden="<?php echo $labActive ? 'false' : 'true' ?>">

                        <?php
                        $labSubmenu = [
                                'his_admin_patient_lab_test.php' => 'Patient Lab Tests',
                                'his_admin_patient_lab_result.php' => 'Patient Lab Results',
                                'his_admin_patient_lab_vitals.php' => 'Patient Vitals',
                                'his_admin_employee_lab_vitals.php' => 'Employee Vitals',
                                'his_admin_lab_report.php' => 'Lab Reports',
                                'divider1' => 'divider',
//                                'his_admin_add_lab_equipment.php' => 'Add Lab Equipment',
//                                'his_admin_manage_lab_equipment.php' => 'Manage Lab Equipments'
                        ];

                        foreach ($labSubmenu as $file => $label):
                            if ($label === 'divider') {
                                echo '<li class="border-t border-gray-200 my-1"></li>';
                                continue;
                            }
                            $active = basename($_SERVER['PHP_SELF']) == $file ? 'bg-blue-200 text-blue-600' : 'text-gray-600';
                            ?>
                            <li>
                                <a href="<?php echo $file; ?>"
                                   class="flex items-center px-2 py-2 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-600 <?php echo $active ?>"
                                   title="<?php echo $label; ?>">
                                    <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                    </svg>
                                    <span class="ml-8 hidden md:block"><?php echo $label; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>

                    </ul>
                </li>


                <!-- Payrolls -->
                <li>
                    <?php
                    $payrollActive = in_array(basename($_SERVER['PHP_SELF']), [
                            'his_admin_add_payroll.php', 'his_admin_manage_payrolls.php', 'his_admin_generate_payrolls.php'
                    ]);
                    ?>
                    <button type="button"
                            class="flex items-center justify-between w-full px-2 md:px-3 py-2 rounded-lg transition-colors
                   hover:bg-blue-50 hover:text-blue-600
                   <?php echo $payrollActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700' ?>"
                            aria-expanded="<?php echo $payrollActive ? 'true' : 'false' ?>"
                            title="Payrolls">
                        <div class="flex items-center">
                            <i class="mdi mdi-cash-refund <?php echo $payrollActive ? 'text-blue-500' : 'text-gray-500' ?> text-lg"></i>
                            <span class="ml-1 hidden md:block">Payrolls</span>
                        </div>
                        <svg class="w-4 h-4 <?php echo $payrollActive ? 'text-blue-500 rotate-180' : 'text-gray-500' ?> transform transition-transform hidden md:block"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <ul class="mt-1 space-y-1 pl-0 md:pl-8 <?php echo $payrollActive ? '' : 'hidden' ?>"
                        aria-hidden="<?php echo $payrollActive ? 'false' : 'true' ?>">
                        <?php
                        $payrollSubmenu = [
                                'his_admin_add_payroll.php' => 'Add Payroll',
                                'his_admin_manage_payrolls.php' => 'Manage Payrolls',
                                'his_admin_generate_payrolls.php' => 'Generate Payrolls'
                        ];
                        foreach ($payrollSubmenu as $file => $label):
                            $active = basename($_SERVER['PHP_SELF']) == $file ? 'bg-blue-200 text-blue-600' : 'text-gray-600';
                            ?>
                            <li>
                                <a href="<?php echo $file; ?>"
                                   class="flex items-center px-2 md:px-3 py-2 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-600 <?php echo $active ?>"
                                   title="<?php echo $label; ?>">
                                    <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                    </svg>
                                    <span class="ml-8 hidden md:block"><?php echo $label; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>



                <!-- Vendors -->
                <li>
                    <?php
                    $vendorActive = in_array(basename($_SERVER['PHP_SELF']), [
                            'his_admin_add_vendor.php', 'his_admin_manage_vendor.php'
                    ]);
                    ?>
                    <button type="button"
                            class="flex items-center justify-between w-full px-2 md:px-3 py-2 rounded-lg transition-colors
                   hover:bg-blue-50 hover:text-blue-600
                   <?php echo $vendorActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700' ?>"
                            aria-expanded="<?php echo $vendorActive ? 'true' : 'false' ?>"
                            title="Vendors">
                        <div class="flex items-center">
                            <i class="fas fa-user-tag <?php echo $vendorActive ? 'text-blue-500' : 'text-gray-500' ?> text-lg"></i>
                            <span class="ml-1 hidden md:block">Vendors</span>
                        </div>
                        <svg class="w-4 h-4 <?php echo $vendorActive ? 'text-blue-500 rotate-180' : 'text-gray-500' ?> transform transition-transform hidden md:block"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <ul class="mt-1 space-y-1 pl-0 md:pl-8 <?php echo $vendorActive ? '' : 'hidden' ?>"
                        aria-hidden="<?php echo $vendorActive ? 'false' : 'true' ?>">
                        <?php
                        $vendorSubmenu = [
                                'his_admin_add_vendor.php' => 'Add Vendor',
                                'his_admin_manage_vendor.php' => 'Manage Vendors'
                        ];
                        foreach ($vendorSubmenu as $file => $label):
                            $active = basename($_SERVER['PHP_SELF']) == $file ? 'bg-blue-200 text-blue-600' : 'text-gray-600';
                            ?>
                            <li>
                                <a href="<?php echo $file; ?>"
                                   class="flex items-center px-2 md:px-3 py-2 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-600 <?php echo $active ?>"
                                   title="<?php echo $label; ?>">
                                    <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                    </svg>
                                    <span class="ml-8 hidden md:block"><?php echo $label; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>



                <!-- Password Resets -->
                <li>
                    <?php
                    $pwActive = (basename($_SERVER['PHP_SELF']) == 'his_admin_manage_password_resets.php');
                    ?>
                    <button type="button"
                            class="flex items-center justify-between w-full px-2 md:px-3 py-2 rounded-lg transition-colors
                   hover:bg-blue-50 hover:text-blue-600
                   <?php echo $pwActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700' ?>"
                            aria-expanded="<?php echo $pwActive ? 'true' : 'false' ?>"
                            title="Password Resets">
                        <div class="flex items-center">
                            <i class="fas fa-lock <?php echo $pwActive ? 'text-blue-500' : 'text-gray-500' ?> text-lg"></i>
                            <span class="ml-1 hidden md:block">Password Resets</span>
                        </div>
                        <svg class="w-4 h-4 <?php echo $pwActive ? 'text-blue-500 rotate-180' : 'text-gray-500' ?> transform transition-transform hidden md:block"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <ul class="mt-1 space-y-1 pl-0 md:pl-8 <?php echo $pwActive ? '' : 'hidden' ?>"
                        aria-hidden="<?php echo $pwActive ? 'false' : 'true' ?>">
                        <?php
                        $pwSubmenu = ['his_admin_manage_password_resets.php' => 'Manage'];
                        foreach ($pwSubmenu as $file => $label):
                            $active = basename($_SERVER['PHP_SELF']) == $file ? 'bg-blue-200 text-blue-600' : 'text-gray-600';
                            ?>
                            <li>
                                <a href="<?php echo $file; ?>"
                                   class="flex items-center px-2 md:px-3 py-2 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-600 <?php echo $active ?>"
                                   title="<?php echo $label; ?>">
                                    <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                    </svg>
                                    <span class="ml-8 hidden md:block"><?php echo $label; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>

<script>
    // Dropdown functionality
    document.querySelectorAll('button[aria-expanded]').forEach(button => {
        button.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', !expanded);

            // Toggle icon rotation and color
            const icon = button.querySelector('svg:last-child');
            if (icon) {
                icon.classList.toggle('rotate-180');
                icon.classList.toggle('text-blue-500');
                icon.classList.toggle('text-gray-500');
            }

            // Toggle menu visibility
            const menu = button.nextElementSibling;
            menu.classList.toggle('hidden');

            // Toggle button background and text color
            button.classList.toggle('bg-blue-50');
            button.classList.toggle('text-blue-600');
            button.classList.toggle('text-gray-700');

            // Toggle main icon color
            const mainIcon = button.querySelector('svg:first-child');
            mainIcon.classList.toggle('text-blue-500');
            mainIcon.classList.toggle('text-gray-500');
        });
    });
</script>
<!--<div class="left-side-menu">-->
<!---->
<!--    <div class="slimscroll-menu">-->
<!---->
<!--        <!--- Sidemenu -->
<!--        <div id="sidebar-menu">-->
<!---->
<!--            <ul class="metismenu" id="side-menu">-->
<!---->
<!--                <li class="menu-title">Navigation</li>-->
<!---->
<!--                <li>-->
<!--                    <a href="his_admin_dashboard.php">-->
<!--                        <i class="fe-airplay"></i>-->
<!--                        <span> Dashboard </span>-->
<!--                    </a>-->
<!---->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class="fab fa-accessible-icon "></i>-->
<!--                        <span> Patients </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!--                        <li>-->
<!--                            <a href="his_admin_register_patient.php"  >Register Patient</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_view_patients.php">View Patients</a>-->
<!--                        </li>-->
<!--                        <li class="text-sm">-->
<!--                            <a href="his_admin_manage_patient.php" >Manage Patients</a>-->
<!--                        </li>-->
<!--                        <hr>-->
<!--                        <li>-->
<!--                            <a href="his_admin_discharge_patient.php">Discharge Patients</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_patient_transfer.php">Patient Transfers</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class="mdi mdi-doctor"></i>-->
<!--                        <span> Employees </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_employee.php">Add Employee</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_view_employee.php">View Employees</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_employee.php">Manage Employees</a>-->
<!--                        </li>-->
<!--                        <hr>-->
<!--                        <li>-->
<!--                            <a href="his_admin_assaign_dept.php">Assign Department</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_transfer_employee.php">Transfer Employee</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class="mdi mdi-pill"></i>-->
<!--                        <span> Pharmacy </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_pharm_cat.php">Add Pharm Category</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_view_pharm_cat.php">View Pharm Category</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_pharm_cat.php">Manage Pharm Category</a>-->
<!--                        </li>-->
<!--                        <hr>-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_pharmaceuticals.php">Add Pharmaceuticals</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_view_pharmaceuticals.php">View Pharmaceuticals</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_pharmaceuticals.php">Manage Pharmaceuticals</a>-->
<!--                        </li>-->
<!--                        <hr>-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_presc.php">Add Prescriptions</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_view_presc.php">View Prescriptions</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_presc.php">Manage Prescriptions</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class="mdi mdi-cash-multiple "></i>-->
<!--                        <span> Accounting </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_acc.payable.php">Add Acc. Payable</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_acc_payable.php">Manage Acc. Payable</a>-->
<!--                        </li>-->
<!--                        <hr>-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_acc_receivable.php">Add Acc. Receivable</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_acc_receivable.php">Manage Acc. Receivable</a>-->
<!--                        </li>-->
<!--                        <hr>-->
<!---->
<!---->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class=" fas fa-funnel-dollar "></i>-->
<!--                        <span> Inventory </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!---->
<!--                        <li>-->
<!--                            <a href="his_admin_pharm_inventory.php">Pharmaceuticals</a>-->
<!--                        </li>-->
<!---->
<!--                        <li>-->
<!--                            <a href="his_admin_equipments_inventory.php">Assets</a>-->
<!--                        </li>-->
<!---->
<!--                    </ul>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class="fe-share"></i>-->
<!--                        <span> Reporting </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!--                        <li>-->
<!--                            <a href="his_admin_inpatient_records.php">InPatient Records</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_outpatient_records.php">OutPatient Records</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_employee_records.php">Employee Records</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_pharmaceutical_records.php">Pharmaceutical Records</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_accounting_records.php">Accounting Records</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_medical_records.php">Medical Records</a>-->
<!--                        </li>-->
<!---->
<!--                    </ul>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class="fe-file-text"></i>-->
<!--                        <span> Medical Records </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_medical_record.php">Add Medical Record</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_medical_record.php">Manage Medical Records</a>-->
<!--                        </li>-->
<!---->
<!--                    </ul>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class="mdi mdi-flask"></i>-->
<!--                        <span> Laboratory </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!--                        <li>-->
<!--                            <a href="his_admin_patient_lab_test.php">Patient Lab Tests</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_patient_lab_result.php">Patient Lab Results</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_patient_lab_vitals.php">Patient Vitals</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_employee_lab_vitals.php">Employee Vitals</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_lab_report.php">Lab Reports</a>-->
<!--                        </li>-->
<!--                        <hr>-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_lab_equipment.php">Add Lab Equipment</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_lab_equipment.php">Manage Lab Equipments</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class="mdi mdi-scissors-cutting "></i>-->
<!--                        <span> Surgical / Theatre </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_equipment.php">Add Equipment</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_equipment.php">Manage Equipments</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_theatre_patient.php">Add Patient</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_theatre_patient.php">Manage Patients</a>-->
<!--                        </li>-->
<!---->
<!--                        <li>-->
<!--                            <a href="his_admin_surgery_records.php">Surgery Records</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class="mdi mdi-cash-refund "></i>-->
<!--                        <span> Payrolls </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_payroll.php">Add Payroll</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_payrolls.php">Manage Payrolls</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_generate_payrolls.php">Generate Payrolls</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class="fas fa-user-tag"></i>-->
<!--                        <span> Vendors </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!--                        <li>-->
<!--                            <a href="his_admin_add_vendor.php">Add Vendor</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_vendor.php">Manage Vendors</a>-->
<!--                        </li>-->
<!---->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="javascript: void(0);">-->
<!--                        <i class="fas fa-lock"></i>-->
<!--                        <span> Password Resets </span>-->
<!--                        <span class="menu-arrow"></span>-->
<!--                    </a>-->
<!--                    <ul class="nav-second-level" aria-expanded="false">-->
<!--                        <li>-->
<!--                            <a href="his_admin_manage_password_resets.php">Manage</a>-->
<!--                        </li>-->
<!---->
<!--                    </ul>-->
<!--                </li>-->
<!---->
<!--            </ul>-->
<!---->
<!--        </div>-->
<!--        <End Sidebar -->
<!---->
<!--        <div class="clearfix"></div>-->
<!---->
<!--    </div>-->
<!--    < Sidebar -left -->
<!---->
<!--</div>-->