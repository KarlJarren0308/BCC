<?php

use Illuminate\Database\Seeder;

use App\TblAccounts;
use App\TblLibrarians;

class LibrarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TblAccounts::insert([
            'Username' => 'librarian',
            'Password' => md5('asd123'),
            'Type' => 'Librarian',
            'Email_Address' => 'zero.trace@gmail.com',
            'Owner_ID' => 1
        ]);
        TblLibrarians::insert([
            'First_Name' => 'Akihiko',
            'Middle_Name' => '',
            'Last_Name' => 'Kayaba',
            'Birth_Date' => date('Y-m-d', strtotime('1980-01-01')),
            'Gender' => 'Male'
        ]);
    }
}
