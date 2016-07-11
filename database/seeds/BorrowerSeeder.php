<?php

use Illuminate\Database\Seeder;

use App\TblAccounts;
use App\TblBorrowers;

class BorrowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TblAccounts::insert([
            'Username' => '20120123456',
            'Password' => md5('asd123'),
            'Type' => 'Student',
            'Email_Address' => 'zero.trace@gmail.com',
            'Owner_ID' => 1
        ]);
        TblBorrowers::insert([
            'First_Name' => 'Zero',
            'Middle_Name' => '',
            'Last_Name' => 'Trace',
            'Birth_Date' => date('Y-m-d', strtotime('1995-01-01')),
            'Gender' => 'Male'
        ]);
    }
}
