<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\TblAccounts;
use App\TblBarcodes;
use App\TblBooks;
use App\TblBounds;
use App\TblLoans;
use App\TblReceives;

use Storage;

date_default_timezone_set('Asia/Manila');

class DataController extends Controller
{
    public function initialize(Request $request) {
        $this->checkSettings();

        return response()->json(['status' => 'Success', 'message' => 'Initialization Complete.']);
    }

    public function checkSettings() {
        if(!Storage::has('settings.xml')) {
            Storage::put('settings.xml', '<?xml version="1.0" encoding="UTF-8"?><settings><setting name="reservation_count" value="1" /><setting name="reservation_period" value="2" /><setting name="loan_period" value="1" /><setting name="opac_version" value="v1.0" /></settings>');
        }
    }

    public function postRequestData($what, Request $request) {
        if(!session()->has('username')) {
            return response()->json(['status' => 'Failed', 'message' => 'Oops! Please login first...']);
        }

        switch($what) {
            case '07489691941dcd1830a96d9f61121278':
                // Request Borrower List

                $borrowers = TblAccounts::where('tbl_accounts.Type', '!=', 'Librarian')
                    ->leftJoin('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')
                ->get();

                return response()->json(['status' => 'Success', 'data' => $borrowers]);

                break;
            case 'd4cf32e8303053a4d7ba0f0859297f83':
                // Request Book Information

                $book = TblBooks::where('Book_ID', $request->input('id'))->first();
                $authors = TblBounds::where('tbl_bounds.Book_ID', $request->input('id'))->join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();

                return response()->json(['status' => 'Success', 'data' => ['book' => $book, 'authors' => $authors]]);

                break;
            case '531a84f73335d5abb30232cdbb7c2bd1':
                // Request Last Barcode Entry

                $barcode = TblBarcodes::orderBy('Accession_Number', 'desc')->first();

                return response()->json(['status' => 'Success', 'data' => $barcode->Accession_Number]);

                break;
            case 'e22d6930d5a3d304e7f190fc75c3d43c':
                // Request Loan Borrower Information
                $ctr = 0;

                $this->checkSettings();

                $settingsFile = storage_path('app/public') . '/settings.xml';
                $data['settings'] = simplexml_load_file($settingsFile);

                $data['borrower'] = TblAccounts::where('Username', $request->input('searchKeyword'))->join('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')->first();

                if($data['borrower']) {
                    $ctr++;
                }

                $data['loan_history'] = TblLoans::where('Username', $request->input('searchKeyword'))->whereNull('tbl_receives.Receive_ID')->leftJoin('tbl_receives', 'tbl_loans.Loan_ID', '=', 'tbl_receives.Reference_ID')->get();

                if($ctr > 0) {
                    return response()->json(['status' => 'Success', 'message' => '1 borrower found.', 'data' => $data]);
                } else {
                    return response()->json(['status' => 'Failed', 'message' => 'No results found.']);
                }

                break;
            case 'ac5196ad2cc23d528a09e0d171cebbe4':
                // Request Loan Book Information

                $this->checkSettings();

                $settingsFile = storage_path('app/public') . '/settings.xml';
                $xml = simplexml_load_file($settingsFile);

                foreach($xml as $item) {
                    if($item['name'] == 'loan_limit') {
                        $data['loan_limit'] = $item['value'];

                        break;
                    }
                }

                if(strlen($request->input('searchKeyword')) == 5 && strtoupper(substr($request->input('searchKeyword'), 0, 1)) == 'C') {
                    $data['book'] = TblBarcodes::where('tbl_barcodes.Accession_Number', (int) substr($request->input('searchKeyword'), 1))->join('tbl_books', 'tbl_barcodes.Book_ID', '=' ,'tbl_books.Book_ID')->first();
                    $data['authors'] = TblBounds::where('tbl_bounds.Book_ID', $data['book']['Book_ID'])->join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();

                    if($data['book']) {
                        return response()->json(['status' => 'Success', 'message' => ' book(s) found.', 'data' => $data]);
                    } else {
                        return response()->json(['status' => 'Failed', 'message' => 'No results found.']);
                    }
                } else {
                    return response()->json(['status' => 'Failed', 'message' => 'Invalid Accession Number.']);
                }

                break;
            default:
                return response()->json(['status' => 'Failed', 'message' => 'Oops! Insufficient request data.']);

                break;
        }
    }
}
