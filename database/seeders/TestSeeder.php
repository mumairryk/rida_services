<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service_quotes')->truncate();
        DB::table('service_quotes')->insert([
            'service_id' => 1,
            'user_id' => 2,
            'pet_id' => 1,
            'doctor_id' => 1,
            'appointment_type' => 1,
            'time' => '13:00',
            'date' => '2023-05-20',
            'status' => 0,
            'created_at' => gmdate('Y-m-d H:i:s'),
            'updated_at' => gmdate('Y-m-d H:i:s'),
        ]);


        DB::table('service_quotes')->insert([
            'service_id' => 2,
            'user_id' => 2,
            'pet_id' => 1,
            'groomer_id' => 1,
            'time' => '10:30',
            'date' => '2023-06-01',
            'status' => 0,
            'created_at' => gmdate('Y-m-d H:i:s'),
            'updated_at' => gmdate('Y-m-d H:i:s'),
        ]);

        DB::table('service_quotes')->insert([
            'service_id' => 3,
            'user_id' => 2,
            'pet_id' => 1,
            'drop_off_time' => '10:30',
            'drop_off_date' => '2023-06-01',

            'pick_up_time' => '13:30',
            'pick_up_date' => '2023-07-01',

            'feeding_schedule' => 1,
            'food' => 'Himalaya Meat & Rice Healthy Pet Adult Dog Dry Food',
            'status' => 0,
            'created_at' => gmdate('Y-m-d H:i:s'),
            'updated_at' => gmdate('Y-m-d H:i:s'),
        ]);

        DB::table('service_quotes')->insert([
            'service_id' => 4,
            'user_id' => 2,
            'pet_id' => 1,

            'drop_off_time' => '08:00',
            'drop_off_date' => '2023-08-01',

            'pick_up_time' => '21:30',
            'pick_up_date' => '2023-08-01',
            'status' => 0,
            'feeding_schedule' => 1,
            'food' => 'Himalaya Meat & Rice Healthy Pet Adult Dog Dry Food',
            'created_at' => gmdate('Y-m-d H:i:s'),
            'updated_at' => gmdate('Y-m-d H:i:s'),
        ]);


        DB::table('service_quotes')->insert([
            'service_id' => 5,
            'user_id' => 2,
            'pet_id' => 1,

            'drop_off_time' => '08:00',
            'drop_off_date' => '2023-08-01',

            'pick_up_time' => '21:30',
            'pick_up_date' => '2023-08-01',
            'status' => 0,
            'feeding_schedule' => 1,
            'food' => 'Himalaya Meat & Rice Healthy Pet Adult Dog Dry Food',
            'playtime_staff_id'=>1,
            'created_at' => gmdate('Y-m-d H:i:s'),
            'updated_at' => gmdate('Y-m-d H:i:s'),
        ]);


        // DB::table('vendor_service_timings')->truncate();
        // DB::table('vendor_service_timings')->insert([
        //     'service_id' => 1,
        //     'vendor' => 3,
        // ]);

        // DB::table('vendor_service_timings')->insert([
        //     'service_id' => 2,
        //     'vendor' => 3,
        // ]);

        // DB::table('vendor_service_timings')->insert([
        //     'service_id' => 3,
        //     'vendor' => 3,
        // ]);

        // DB::table('vendor_service_timings')->insert([
        //     'service_id' => 4,
        //     'vendor' => 3,
        // ]);

        // DB::table('vendor_service_timings')->insert([
        //     'service_id' => 5,
        //     'vendor' => 3,
        // ]);
        
    }
}
