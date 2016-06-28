<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\TblAccounts;
use App\TblBooks;
use App\TblBounds;
use App\TblLibrarians;
use App\TblLoans;
use App\TblReservations;
use App\TblStudents;

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

    public function getOpac() {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
        }

        $data['bounds'] = TblBounds::join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();
        $data['books'] = TblBooks::get();

        return view('cardinal.opac', $data);
    }

    public function getReservations() {
        if(!session()->has('username')) {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Please login first.');

            return redirect()->route('cardinal.getIndex');
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

        $data['borrowed_books'] = TblLoans::where('tbl_loans.Username', session()->get('username'))->join('tbl_books', 'tbl_loans.Book_ID', '=', 'tbl_books.Book_ID')->leftJoin('tbl_receives', 'tbl_loans.Loan_ID', '=', 'tbl_receives.Reference_ID')->get();

        return view('cardinal.borrowed_books', $data);
    }

    public function getForgotPassword() {
        return view('cardinal.forgot_password');
    }

    public function getLogout() {
        session()->flush();

        return redirect()->route('cardinal.getIndex');
    }

    public function postReserve(Request $request) {
        if(!session()->has('username')) {
            return response()->json(array('status' => 'Failed', 'message' => 'Oops! Please login first...'));
        }

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

    public function postLogin(Request $request) {
        $account = TblAccounts::where('Username', $request->input('username'))->where('Password', md5($request->input('password')))->first();

        if($account) {
            switch($account->Type) {
                case 'Faculty':
                    $faculty = TblFaculties::where('Faculty_ID', $account->Owner_ID)->first();

                    if($faculty) {
                        session()->put('username', $account->Username);
                        session()->put('first_name', $faculty->First_Name);
                        session()->put('middle_name', $faculty->Middle_Name);
                        session()->put('last_name', $faculty->Last_Name);
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
                case 'Student':
                    $student = TblStudents::where('Student_ID', $account->Owner_ID)->first();

                    if($student) {
                        session()->put('username', $account->Username);
                        session()->put('first_name', $student->First_Name);
                        session()->put('middle_name', $student->Middle_Name);
                        session()->put('last_name', $student->Last_Name);
                        session()->put('type', $account->Type);
                        session()->put('owner', $account->Owner_ID);

                        return redirect()->route('cardinal.getOpac');
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
