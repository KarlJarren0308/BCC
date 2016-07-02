<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\TblAuthors;
use App\TblBarcodes;
use App\TblBooks;
use App\TblBounds;
use App\TblCategories;
use App\TblPublishers;

date_default_timezone_set('Asia/Manila');

class DashboardController extends Controller
{
    public function getIndex() {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        } else {
            if(session()->get('type') != 'Librarian') {
                session()->flash('flash_status', 'danger');
                session()->flash('flash_message', 'Oops! You do not have to privilege to access the dashboard.');

                return redirect()->route('cardinal.getOpac');
            }
        }

        return view('dashboard.index');
    }

    public function getLoanBooks() {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        } else {
            if(session()->get('type') != 'Librarian') {
                session()->flash('flash_status', 'danger');
                session()->flash('flash_message', 'Oops! You do not have to privilege to access the dashboard.');

                return redirect()->route('cardinal.getOpac');
            }
        }

        $data['bounds'] = TblBounds::join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();
        $data['books'] = TblBooks::get();

        return view('dashboard.loan_books', $data);
    }

    public function getManageRecords($what) {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        } else {
            if(session()->get('type') != 'Librarian') {
                session()->flash('flash_status', 'danger');
                session()->flash('flash_message', 'Oops! You do not have to privilege to access the dashboard.');

                return redirect()->route('cardinal.getOpac');
            }
        }

        switch($what) {
            case 'books':
                $data['bounds'] = TblBounds::join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();
                $data['books'] = TblBooks::get();

                return view('dashboard.manage_records.books', $data);

                break;
            case 'authors':
                break;
            case 'publishers':
                break;
            case 'categories':
                break;
            default:
                break;
        }
    }

    public function getAddRecord($what) {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        } else {
            if(session()->get('type') != 'Librarian') {
                session()->flash('flash_status', 'danger');
                session()->flash('flash_message', 'Oops! You do not have to privilege to access the dashboard.');

                return redirect()->route('cardinal.getOpac');
            }
        }

        switch($what) {
            case 'books':
                $data['authors'] = TblAuthors::get();
                $data['books'] = TblBooks::get();
                $data['categories'] = TblCategories::get();
                $data['publishers'] = TblPublishers::get();

                return view('dashboard.manage_records.add_books', $data);

                break;
            case 'authors':
                break;
            case 'publishers':
                break;
            case 'categories':
                break;
            default:
                break;
        }
    }

    public function getEditRecord($what, $id) {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        } else {
            if(session()->get('type') != 'Librarian') {
                session()->flash('flash_status', 'danger');
                session()->flash('flash_message', 'Oops! You do not have to privilege to access the dashboard.');

                return redirect()->route('cardinal.getOpac');
            }
        }

        $data['id'] = $id;

        switch($what) {
            case 'books':
                $data['authors'] = TblAuthors::get();
                $data['bounds'] = TblBounds::where('Book_ID', $id)->get();
                $data['book'] = TblBooks::where('Book_ID', $id)->first();
                $data['categories'] = TblCategories::get();
                $data['publishers'] = TblPublishers::get();

                return view('dashboard.manage_records.edit_books', $data);

                break;
            case 'authors':
                break;
            case 'publishers':
                break;
            case 'categories':
                break;
            default:
                break;
        }
    }

    public function postAddRecord($what, Request $request) {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        } else {
            if(session()->get('type') != 'Librarian') {
                session()->flash('flash_status', 'danger');
                session()->flash('flash_message', 'Oops! You do not have to privilege to access the dashboard.');

                return redirect()->route('cardinal.getOpac');
            }
        }

        switch($what) {
            case 'books':
                $query = TblBooks::where('Title', $request->input('title'))->where('Edition', $request->input('edition'))->first();

                if($query) {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! A book with the same title and edition number already exist.');
                } else {
                    $bookID = TblBooks::insertGetId([
                        'Title' => $request->input('title'),
                        'Edition' => $request->input('edition'),
                        'Collection_Type' => $request->input('collectionType'),
                        'Call_Number' => $request->input('callNumber'),
                        'ISBN' => $request->input('isbn'),
                        'Location' => $request->input('location'),
                        'Copyright_Year' => $request->input('copyrightYear'),
                        'Number_Of_Copies' => $request->input('numberOfCopies'),
                        'Publisher_ID' => $request->input('publisher'),
                        'Category_ID' => $request->input('category')
                    ]);

                    if($bookID) {
                        $addedAuthors = 0;

                        for($i = 0; $i < $request->input('numberOfCopies'); $i++) {
                            $generatedBarcode = mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9);

                            $query = TblBarcodes::insert([
                                'Barcode_Number' => $generatedBarcode,
                                'Book_ID' => $bookID
                            ]);

                            if(!$query) {
                                $i--;
                            }
                        }

                        foreach(array_unique($request->input('authors')) as $author) {
                            $query = TblBounds::insert(array(
                                'Book_ID' => $bookID,
                                'Author_ID' => $author
                            ));

                            if($query) {
                                $addedAuthors++;
                            }
                        }

                        if($addedAuthors > 0) {
                            session()->flash('flash_status', 'success');
                            session()->flash('flash_message', 'Book has been added.');
                        } else {
                            session()->flash('flash_status', 'warning');
                            session()->flash('flash_message', 'Oops! Book has been added but failed to associate author(s).');
                        }
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Failed to add book. Please refresh the page and try again.');
                    }
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'authors':
                return redirect()->route('dashboard.manage_records', $what);

                break;
            case 'publishers':
                return redirect()->route('dashboard.manage_records', $what);

                break;
            case 'categories':
                return redirect()->route('dashboard.manage_records', $what);

                break;
            default:
                break;
        }
    }

    public function postEditRecord($what, $id, Request $request) {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        } else {
            if(session()->get('type') != 'Librarian') {
                session()->flash('flash_status', 'danger');
                session()->flash('flash_message', 'Oops! You do not have to privilege to access the dashboard.');

                return redirect()->route('cardinal.getOpac');
            }
        }

        switch($what) {
            case 'books':
                $query = TblBooks::where('Book_ID', $id)->first();

                if($query) {
                    $query = TblBounds::where('Book_ID', $id)->delete();

                    if($query) {
                        $addedAuthors = 0;

                        $updateBook = TblBooks::where('Book_ID', $id)->update([
                            'Title' => $request->input('title'),
                            'Edition' => $request->input('edition'),
                            'Collection_Type' => $request->input('collectionType'),
                            'Call_Number' => $request->input('callNumber'),
                            'ISBN' => $request->input('isbn'),
                            'Location' => $request->input('location'),
                            'Copyright_Year' => $request->input('copyrightYear'),
                            'Publisher_ID' => $request->input('publisher'),
                            'Category_ID' => $request->input('category')
                        ]);

                        foreach(array_unique($request->input('authors')) as $author) {
                            $query = TblBounds::insert(array(
                                'Book_ID' => $id,
                                'Author_ID' => $author
                            ));

                            if($query) {
                                $addedAuthors++;
                            }
                        }

                        if($addedAuthors > 0) {
                            session()->flash('flash_status', 'success');
                            session()->flash('flash_message', 'Book has been edited.');
                        } else {
                            session()->flash('flash_status', 'warning');
                            session()->flash('flash_message', 'Oops! Book has been edited but failed to associate author(s).');
                        }
                    } else {
                        session()->flash('flash_status', 'warning');
                        session()->flash('flash_message', 'Oops! Failed to edit book. Please refresh the page and try again.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! This book doesn\'t exist anymore.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'authors':
                return redirect()->route('dashboard.manage_records', $what);

                break;
            case 'publishers':
                return redirect()->route('dashboard.manage_records', $what);

                break;
            case 'categories':
                return redirect()->route('dashboard.manage_records', $what);

                break;
            default:
                break;
        }
    }
}
