<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\TblBooks;
use App\TblBounds;

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
        switch($what) {
            case 'books':
                $data['bounds'] = TblBounds::join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();
                $data['books'] = TblBooks::get();

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
        switch($what) {
            case 'books':
                $data['book'] = TblBooks::where('Book_ID', $id)->first();

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

    public function postRequestData($key, Request $request) {
        if(!session()->has('username')) {
            return response()->json(array('status' => 'Failed', 'message' => 'Oops! Please login first...'));
        }

        switch($key) {
            case 'd4cf32e8303053a4d7ba0f0859297f83':
                // Request Book Information

                $book = TblBooks::where('Book_ID', $request->input('id'))->first();
                $authors = TblBounds::where('tbl_bounds.Book_ID', $request->input('id'))->join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();

                return response()->json(array('status' => 'Success', 'data' => array('book' => $book, 'authors' => $authors)));

                break;
            default:
                return response()->json(array('status' => 'Failed', 'message' => 'Oops! Insufficient request data.'));

                break;
        }
    }
}
