<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\TblAccounts;
use App\TblBarcodes;
use App\TblBooks;
use App\TblHolidays;
use App\TblBounds;
use App\TblLoans;
use App\TblReceives;

use Storage;

date_default_timezone_set('Asia/Manila');

class DataController extends Controller
{
    private function isHoliday($date) {
        $date = date('Y-m-d', strtotime($date));
        $holidays = TblHolidays::get();

        if($holidays) {
            foreach($holidays as $holiday) {
                if($date == date('Y-m-d', strtotime($holiday->Date_Stamp))) {
                    return true;
                }
            }
        }

        return false;
    }

    private function isWeekend($date) {
        $date = date('l', strtotime($date));

        if($date == 'Sunday') {
            return true;
        } else if($date == 'Saturday') {
            return true;
        } else {
            return false;
        }
    }

    private function nextDay($date) {
        return date('Y-m-d', strtotime('+1 day', strtotime($date)));
    }

    public function initialize(Request $request) {
        $this->checkSettings();

        return response()->json(['status' => 'Success', 'message' => 'Initialization Complete.']);
    }

    public function checkSettings() {
        if(!Storage::has('settings.xml')) {
            Storage::put('settings.xml', '<?xml version="1.0" encoding="UTF-8"?><settings><setting name="reservation_count" value="1"/><setting name="reservation_period" value="1"/><setting name="loan_limit" value="1"/><setting name="loan_period" value="1"/><setting name="penalty_per_day" value="10"/><setting name="opac_version" value="v1.0"/></settings>');
        }
    }

    public function computePenalty($dateLoaned) {
        $loanPeriod = 1;
        $penaltyPerDay = 10;

        $this->checkSettings();

        $settingsFile = storage_path('app/public') . '/settings.xml';
        $xml = simplexml_load_file($settingsFile);

        foreach($xml as $item) {
            if($item['name'] == 'loan_period') {
                $loanPeriod = $item['value'];
            } else if($item['name'] == 'penalty_per_day') {
                $penaltyPerDay = $item['value'];
            }
        }

        $dateLoaned = date('Y-m-d H:i:s', strtotime($dateLoaned));
        $datePenaltyStarts = date('Y-m-d H:i:s', strtotime('+' . $loanPeriod . ' days', strtotime($dateLoaned)));
        $daysCount = ceil(strtotime($datePenaltyStarts) - strtotime($dateLoaned)) / 86400;
        
        for($i = 1; $i <= $daysCount; $i++) {
            $currentDate = date('Y-m-d H:i:s', strtotime('+' . $i . ' days', strtotime($dateLoaned)));

            if($this->isWeekend($currentDate)) {
                $daysCount++;
                $datePenaltyStarts = $this->nextDay($datePenaltyStarts);
            } else {
                if($this->isHoliday($currentDate)) {
                    $daysCount++;
                    $datePenaltyStarts = $this->nextDay($datePenaltyStarts);
                }
            }
        }

        $dateReturned = date('Y-m-d H:i:s');
        $daysCount = ceil(strtotime($dateReturned) - strtotime($datePenaltyStarts)) / 86400;

        for($j = 1; $j <= $daysCount; $j++) {
            $currentDate = date('Y-m-d H:i:s', strtotime('+' . $i . ' days', strtotime($datePenaltyStarts)));

            if($this->isWeekend($currentDate)) {
                $daysCount++;
                $datePenaltyStarts = $this->nextDay($datePenaltyStarts);
            } else {
                if($this->isHoliday($currentDate)) {
                    $daysCount++;
                    $datePenaltyStarts = $this->nextDay($datePenaltyStarts);
                }
            }
        }

        return (double) abs(ceil((strtotime($dateReturned) - strtotime($datePenaltyStarts)) / 86400)) * (double) $penaltyPerDay;
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

                if($barcode) {
                    return response()->json(['status' => 'Success', 'data' => $barcode->Accession_Number]);
                } else {
                    return response()->json(['status' => 'Success', 'data' => 0]);
                }

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
                        $data['loan_limit'] = (int) $item['value'];

                        break;
                    }
                }

                if(strlen($request->input('searchKeyword')) == 5 && strtoupper(substr($request->input('searchKeyword'), 0, 1)) == 'C') {
                    $data['book'] = TblBarcodes::where('tbl_barcodes.Accession_Number', (int) substr($request->input('searchKeyword'), 1))->join('tbl_books', 'tbl_barcodes.Book_ID', '=' ,'tbl_books.Book_ID')->first();
                    $data['authors'] = TblBounds::where('tbl_bounds.Book_ID', $data['book']['Book_ID'])->join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();

                    if($data['book']) {
                        return response()->json(['status' => 'Success', 'message' => 'Some books found.', 'data' => $data]);
                    } else {
                        return response()->json(['status' => 'Failed', 'message' => 'No results found.']);
                    }
                } else {
                    return response()->json(['status' => 'Failed', 'message' => 'Invalid Accession Number.']);
                }

                break;
            case '64d808802eda41de389118b03d15ccb9':
                // Request Loaned Book Information
                $data['loan_history'] = TblLoans::where('Username', $request->input('searchKeyword'))->where('Loan_Status', 'active')->join('tbl_barcodes', 'tbl_loans.Accession_Number', '=', 'tbl_barcodes.Accession_Number')->join('tbl_books', 'tbl_barcodes.Book_ID', '=', 'tbl_books.Book_ID')->get();

                if($data['loan_history']) {
                    return response()->json(['status' => 'Success', 'message' => 'Some books found.', 'data' => $data]);
                } else {
                    return response()->json(['status' => 'Failed', 'message' => 'No results found.']);
                }

                break;
            default:
                return response()->json(['status' => 'Failed', 'message' => 'Oops! Insufficient request data.']);

                break;
        }
    }
}
