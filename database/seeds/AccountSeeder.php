<?php

use Illuminate\Database\Seeder;

use App\TblAccounts;
use App\TblStudents;
use App\TblLibrarians;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TblAccounts::truncate();
        TblLibrarians::truncate();
        TblStudents::truncate();

        TblAccounts::insert(array(
            'Username' => 'librarian',
            'Password' => md5('asd123'),
            'Type' => 'Librarian',
            'Owner_ID' => 1
        ));
        TblLibrarians::insert(array(
            'First_Name' => 'Akihiko',
            'Middle_Name' => '',
            'Last_Name' => 'Kayaba',
            'Birth_Date' => date('Y-m-d', strtotime('1996-01-01'))
        ));

        TblAccounts::insert(array(
            'Username' => '20120123456',
            'Password' => md5('asd123'),
            'Type' => 'Student',
            'Owner_ID' => 1
        ));
        TblStudents::insert(array(
            'First_Name' => 'Kaaru',
            'Middle_Name' => '',
            'Last_Name' => 'Makuzo',
            'Birth_Date' => date('Y-m-d', strtotime('1996-03-08'))
        ));
    }
}
