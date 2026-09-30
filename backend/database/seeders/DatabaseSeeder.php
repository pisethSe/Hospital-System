<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Doctor;
use App\Support\HisCode;
use App\Support\HisPassword;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the demo accounts. Passwords are hashed with the legacy
     * sha1(md5()) algorithm so they work with both the new API and any
     * existing database records.
     */
    public function run(): void
    {
        if (Admin::count() === 0) {
            Admin::create([
                'ad_fname' => 'Admin',
                'ad_lname' => 'User',
                'ad_email' => 'admin@hospital.com',
                'ad_pwd' => HisPassword::hash('admin123'),
                'ad_dpic' => 'profile_admin.png',
            ]);

            Admin::create([
                'ad_fname' => 'Admin',
                'ad_lname' => 'Two',
                'ad_email' => 'admin2@hospital.com',
                'ad_pwd' => HisPassword::hash('admin123'),
                'ad_dpic' => 'profile_admin.png',
            ]);

            Admin::create([
                'ad_fname' => 'Admin',
                'ad_lname' => 'Three',
                'ad_email' => 'admin3@hospital.com',
                'ad_pwd' => HisPassword::hash('admin123'),
                'ad_dpic' => 'profile_admin.png',
            ]);
        }

        if (Doctor::count() === 0) {
            Doctor::create([
                'doc_fname' => 'Pheakdey',
                'doc_lname' => '',
                'doc_number' => 'pkd',
                'doc_pwd' => HisPassword::hash('pkd123'),
                'doc_dept' => 'General Medicine',
            ]);

            Doctor::create([
                'doc_fname' => 'Sok',
                'doc_lname' => 'Visal',
                'doc_number' => 'visal',
                'doc_pwd' => HisPassword::hash('visal123'),
                'doc_dept' => 'Laboratory',
            ]);

            Doctor::create([
                'doc_fname' => 'Somaly',
                'doc_lname' => '',
                'doc_number' => 'sml',
                'doc_pwd' => HisPassword::hash('sml123'),
                'doc_dept' => 'Surgery',
            ]);
        }
    }
}
