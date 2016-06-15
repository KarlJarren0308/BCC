<?php

use Illuminate\Database\Seeder;

use App\TblAuthors;
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
        TblBooks::truncate();
        TblBounds::truncate();
        TblCategories::truncate();
        TblPublishers::truncate();

        TblPublishers::insert(array(
            'Publisher_Name' => 'HarperCollins'
        ));
        TblCategories::insert(array(
            'Category_Name' => 'Storybook'
        ));
        TblBooks::insert(array(
            'Title' => 'What I Wish I Knew When I Was 20',
            'Edition' => '1st',
            'Collection_Type' => 'Book',
            'Call_Number' => '[None]',
            'ISBN' => '978-0-06-187247-1',
            'Location' => '',
            'Copyright_Year' => '2009',
            'Number_of_Copies' => '5',
            'Publisher_ID' => '1',
            'Category_ID' => '1'
        ));
        TblAuthors::insert(array(
            'First_Name' => 'Tina',
            'Middle_Name' => '',
            'Last_Name' => 'Seelig'
        ));
        TblBounds::insert(array(
            'Book_ID' => '1',
            'Author_ID' => '1'
        ));
    }
}
