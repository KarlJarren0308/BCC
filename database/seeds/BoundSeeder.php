<?php

use Illuminate\Database\Seeder;

use App\TblAuthors;
use App\TblBarcodes;
use App\TblBooks;
use App\TblBounds;
use App\TblCategories;
use App\TblPublishers;

class BoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TblAuthors::truncate();
        TblBarcodes::truncate();
        TblBooks::truncate();
        TblBounds::truncate();
        TblCategories::truncate();
        TblPublishers::truncate();

        TblPublishers::insert([
            'Publisher_Name' => 'HarperCollins'
        ]);
        TblCategories::insert([
            'Category_Name' => 'Storybook'
        ]);
        TblBooks::insert([
            'Title' => 'What I Wish I Knew When I Was 20',
            'Edition' => '1st',
            'Collection_Type' => 'Book',
            'Call_Number' => '[None]',
            'ISBN' => '978-0-06-187247-1',
            'Location' => '',
            'Copyright_Year' => '2009',
            'Publisher_ID' => '1',
            'Category_ID' => '1'
        ]);
        TblAuthors::insert([
            'First_Name' => 'Tina',
            'Middle_Name' => '',
            'Last_Name' => 'Seelig'
        ]);
        TblBounds::insert([
            'Book_ID' => '1',
            'Author_ID' => '1'
        ]);
        TblBarcodes::insert([
            ['Barcode_Number' => 'C0001', 'Book_ID' => '1'],
            ['Barcode_Number' => 'C0002', 'Book_ID' => '1'],
            ['Barcode_Number' => 'C0003', 'Book_ID' => '1'],
            ['Barcode_Number' => 'C0004', 'Book_ID' => '1'],
            ['Barcode_Number' => 'C0005', 'Book_ID' => '1']
        ]);
    }
}
