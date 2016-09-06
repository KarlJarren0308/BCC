<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\TblAttendances;
use App\TblAccounts;
use App\TblBooks;
use App\TblBorrowers;
use App\TblBounds;
use App\TblHolidays;
use App\TblLibrarians;
use App\TblLoans;
use App\TblReservations;

date_default_timezone_set('Asia/Manila');

class CardinalController extends Controller
{
    public function getIndex() {
        if(session()->has('username')) {
            if(session()->get('type') == 'Faculty' || session()->get('type') == 'Student') {
                return redirect()->route('cardinal.getOpac');
            } else {
                return redirect()->route('dashboard.getIndex');
            }
        }

        return view('cardinal.index');
    }

    public function getAttendance() {
        return view('cardinal.attendance');
    }

    public function getAccountInformation($username = null) {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        }

        if($username == null) {
            return redirect()->route('cardinal.getAccountInformation', session()->get('username'));
        }

        $query = TblAccounts::where('Username', $username)->first();
        $data['account'] = $query;
        $data['loan_history'] = TblLoans::where('tbl_loans.Username', $username)->join('tbl_barcodes', 'tbl_loans.Accession_Number', '=', 'tbl_barcodes.Accession_Number')->join('tbl_books', 'tbl_barcodes.Book_ID', '=', 'tbl_books.Book_ID')->leftJoin('tbl_receives', 'tbl_loans.Loan_ID', '=', 'tbl_receives.Reference_ID')->select('*', 'tbl_receives.Reference_ID as Receive_Reference_ID')->get();
        $data['reservation_history'] = TblReservations::where('tbl_reservations.Username', $username)->join('tbl_books', 'tbl_reservations.Book_ID', '=', 'tbl_books.Book_ID')->get();
        
        if($query->Type == 'Librarian') {
            $data['user'] = TblLibrarians::where('Librarian_ID', $query->Owner_ID)->first();
        } else {
            $data['user'] = TblBorrowers::where('Borrower_ID', $query->Owner_ID)->first();
        }

        return view('cardinal.account_information', $data);
    }

    public function getOpac() {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        }

        app('App\Http\Controllers\DataController')->checkSettings();

        $settingsFile = storage_path('app/public') . '/settings.xml';
        $xml = simplexml_load_file($settingsFile);

        foreach($xml as $item) {
            if($item['name'] == 'opac_version') {
                switch($item['value']) {
                    case 'v1.0':
                        $data['bounds'] = TblBounds::join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();
                        $data['books'] = TblBooks::get();

                        return view('cardinal.opac_v1_0', $data);

                        break;
                    case 'v2.0':
                        return view('cardinal.opac_v2_0');

                        break;
                    default:
                        return view('maintenance');

                        break;
                }
            }
        }
    }

    public function getReservations() {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        } else {
            if(session()->get('type') == 'Librarian') {
                session()->flash('flash_status', 'warning');
                session()->flash('flash_message', 'Oops! Reservation module is for borrowers only.');

                return redirect()->route('cardinal.getOpac');
            }
        }

        $data['reservations'] = TblReservations::where('tbl_reservations.Username', session()->get('username'))->join('tbl_books', 'tbl_reservations.Book_ID', '=', 'tbl_books.Book_ID')->leftJoin('tbl_loans', 'tbl_reservations.Reservation_ID', '=', 'tbl_loans.Reference_ID')->get();

        return view('cardinal.my_reservations', $data);
    }

    public function getBorrowedBooks() {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        }

        app('App\Http\Controllers\DataController')->checkSettings();

        $settingsFile = storage_path('app/public') . '/settings.xml';
        $xml = simplexml_load_file($settingsFile);

        foreach($xml as $item) {
            if($item['name'] == 'loan_period') {
                $data['loanPeriod'] = $item['value'];
            } else if($item['name'] == 'penalty_per_day') {
                $data['penaltyPerDay'] = $item['value'];
            }
        }

        $data['holidays'] = TblHolidays::get();
        $data['borrowed_books'] = TblLoans::where('tbl_loans.Username', session()->get('username'))->join('tbl_barcodes', 'tbl_loans.Accession_Number', '=', 'tbl_barcodes.Accession_Number')->join('tbl_books', 'tbl_barcodes.Book_ID', '=', 'tbl_books.Book_ID')->leftJoin('tbl_receives', 'tbl_loans.Loan_ID', '=', 'tbl_receives.Reference_ID')->get();

        return view('cardinal.borrowed_books', $data);
    }

    public function getForgotPassword() {
        return view('cardinal.forgot_password');
    }

    public function getLogout() {
        session()->flush();

        return redirect()->route('cardinal.getIndex');
    }

    public function postAttendance(Request $request) {
        $query = TblAccounts::where('tbl_accounts.Username', $request->input('username'))->where('tbl_accounts.Type', '!=', 'Librarian')
            ->leftJoin('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')
        ->first();

        if($query) {
            $attendance = TblAttendances::insert([
                'Username' => $request->input('username'),
                'Date_Stamp' => date('Y-m-d'),
                'Time_Stamp' => date('H:i:s')
            ]);

            if($attendance) {
                if(strlen($query->Middle_Name) > 1) {
                    $name = $query->First_Name . ' ' . substr($query->Middle_Name, 0, 1) . '. ' . $query->Last_Name;
                } else {
                    $name = $query->First_Name . ' ' . $query->Last_Name;
                }

                session()->flash('flash_status', 'success');
                session()->flash('flash_message', $name . '\'s attendance has been recorded.');
            } else {
                session()->flash('flash_status', 'danger');
                session()->flash('flash_message', 'Oops! Failed to record attendance.');
            }
        } else {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Borrower not found.');
        }

        return redirect()->route('cardinal.getAttendance');
    }

    public function postSearchOpac(Request $request) {
        // Available and used only for OPAC Version 2.0
        if(!session()->has('username')) {
            return response()->json(array('status' => 'Failed', 'message' => 'Oops! Please login first...'));
        }

        $data['bounds'] = TblBounds::join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();
        $data['books'] = TblBooks::where('Title', 'like', '%' . $request->input('keyword') . '%')->get();
        $count = TblBooks::where('Title', 'like', '%' . $request->input('keyword') . '%')->count();

        return response()->json(array('status' => 'Success', 'message' => $count . ' result(s) found.', 'data' => $data));
    }

    public function postReserve(Request $request) {
        if(!session()->has('username')) {
            return response()->json(array('status' => 'Failed', 'message' => 'Oops! Please login first...'));
        }

        app('App\Http\Controllers\DataController')->checkSettings();

        $settingsFile = storage_path('app/public') . '/settings.xml';
        $xml = simplexml_load_file($settingsFile);
        $reservationCount = 1;

        foreach($xml as $item) {
            if($item['name'] == 'reservation_count') {
                $reservationCount = $item['value'];

                break;
            }
        }

        $query = TblReservations::where('Username', session()->get('username'))->where('Reservation_Status', 'active')->count();

        if($query < $reservationCount) {
            $query = TblReservations::where('Book_ID', $request->input('id'))->where('Username', session()->get('username'))->where('Reservation_Status', 'active')->first();

            if(!$query) {
                $query = TblReservations::insert(array(
                    'Book_ID' => $request->input('id'),
                    'Username' => session()->get('username'),
                    'Reservation_Date_Stamp' => date('Y-m-d'),
                    'Reservation_Time_Stamp' => date('H:i:s')
                ));

                if($query) {
                    return response()->json(array('status' => 'Success', 'message' => 'You have successfully reserved a copy of this book.'));
                } else {
                    return response()->json(array('status' => 'Failed', 'message' => 'Oops! Failed to reserve a copy of this book.'));
                }
            } else {
                return response()->json(array('status' => 'Failed', 'message' => 'Oops! You already reserved a copy of this book.'));
            }
        } else {
            return response()->json(array('status' => 'Failed', 'message' => 'Oops! You can only reserve ' . $reservationCount . ' book' . ($reservationCount > 1 ? 's' : '') . '.'));
        }
    }

    public function postCancelReservation(Request $request) {
        if(!session()->has('username')) {
            return response()->json(array('status' => 'Failed', 'message' => 'Oops! Please login first...'));
        }

        $query = TblReservations::where('Reservation_ID', $request->input('id'))->first();

        if($query) {
            $query = TblReservations::where('Reservation_ID', $request->input('id'))->update(array(
                'Reservation_Status' => 'inactive'
            ));

            if($query) {
                return response()->json(array('status' => 'Success', 'message' => 'You have successfully cancelled your reservation.'));
            } else {
                return response()->json(array('status' => 'Failed', 'message' => 'Oops! Failed to cancel reservation.'));
            }
        } else {
            return response()->json(array('status' => 'Failed', 'message' => 'Oops! Reservation doesn\'t exist.'));
        }
    }

    public function postLogin(Request $request) {
        $account = TblAccounts::where('Username', $request->input('username'))->where('Password', md5($request->input('password')))->first();

        if($account) {
            switch($account->Type) {
                case 'Faculty':
                case 'Student':
                    $borrower = TblBorrowers::where('Borrower_ID', $account->Owner_ID)->first();

                    if($borrower) {
                        session()->put('username', $account->Username);
                        session()->put('first_name', $borrower->First_Name);
                        session()->put('middle_name', $borrower->Middle_Name);
                        session()->put('last_name', $borrower->Last_Name);
                        session()->put('type', $account->Type);
                        session()->put('owner', $account->Owner_ID);

                        return redirect()->route('cardinal.getOpac');
                    }

                    break;
                case 'Librarian':
                    $librarian = TblLibrarians::where('Librarian_ID', $account->Owner_ID)->first();

                    if($librarian) {
                        session()->put('username', $account->Username);
                        session()->put('first_name', $librarian->First_Name);
                        session()->put('middle_name', $librarian->Middle_Name);
                        session()->put('last_name', $librarian->Last_Name);
                        session()->put('type', $account->Type);
                        session()->put('owner', $account->Owner_ID);

                        return redirect()->route('dashboard.getIndex');
                    }

                    break;
                default:
                    break;
            }
        }

        session()->flash('flash_status', 'danger');
        session()->flash('flash_message', 'Invalid username and/or password.');

        return redirect()->route('cardinal.getIndex');
    }
}
