<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\TblBooks;
use App\TblBounds;

date_default_timezone_set('Asia/Manila');

class DataController extends Controller
{
    public function postRequestData($what, Request $request) {
        if(!session()->has('username')) {
            return response()->json(array('status' => 'Failed', 'message' => 'Oops! Please login first...'));
        }

        switch($what) {
            case 'd4cf32e8303053a4d7ba0f0859297f83':
                // Request Book Information

                $book = TblBooks::where('Book_ID', $request->input('id'))->first();
                $authors = TblBounds::where('tbl_bounds.Book_ID', $request->input('id'))->join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();

                return response()->json(array('status' => 'Success', 'data' => ['book' => $book, 'authors' => $authors]));

                break;
            default:
                return response()->json(array('status' => 'Failed', 'message' => 'Oops! Insufficient request data.'));

                break;
        }
    }
}
