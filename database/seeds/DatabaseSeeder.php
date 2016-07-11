<?php

use Illuminate\Database\Seeder;

use App\TblAccounts;
use App\TblBorrowers;
use App\TblLibrarians;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TblAccounts::truncate();
        TblBorrowers::truncate();
        TblLibrarians::truncate();

        $this->call(BorrowerSeeder::class);
        $this->call(BoundSeeder::class);
        $this->call(LibrarianSeeder::class);
    }
}
