<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\TblAccounts;
use App\TblAttendances;
use App\TblAuthors;
use App\TblBarcodes;
use App\TblBorrowers;
use App\TblBooks;
use App\TblBounds;
use App\TblCategories;
use App\TblHolidays;
use App\TblLibrarians;
use App\TblLoans;
use App\TblPublishers;
use App\TblReceives;
use App\TblReservations;
use App\TblWeeding;

use DB;
use PDF;

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

    public function getReservedBooks() {
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
        $data['books'] = TblReservations::where('Reservation_Status', 'active')->join('tbl_accounts', 'tbl_reservations.Username', '=', 'tbl_accounts.Username')->join('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')->join('tbl_books', 'tbl_reservations.Book_ID', '=', 'tbl_books.Book_ID')->get();

        return view('dashboard.loan_reserved_books', $data);
    }

    public function getReceiveBooks() {
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
        $data['loans'] = TblLoans::join('tbl_accounts', 'tbl_loans.Username', '=', 'tbl_accounts.Username')->join('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')->join('tbl_barcodes', 'tbl_loans.Accession_Number', '=', 'tbl_barcodes.Accession_Number')->join('tbl_books', 'tbl_barcodes.Book_ID', '=', 'tbl_books.Book_ID')->get();
        $data['receives'] = TblReceives::get();
        $data['holidays'] = TblHolidays::get();

        return view('dashboard.receive_books', $data);
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
            case 'penalties':
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

                $data['loans'] = TblLoans::join('tbl_accounts', 'tbl_loans.Username', '=', 'tbl_accounts.Username')->join('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')->join('tbl_barcodes', 'tbl_loans.Accession_Number', '=', 'tbl_barcodes.Accession_Number')->join('tbl_books', 'tbl_barcodes.Book_ID', '=', 'tbl_books.Book_ID')->leftJoin('tbl_receives', 'tbl_loans.Loan_ID', '=', 'tbl_receives.Reference_ID')->get();
                $data['receives'] = TblReceives::get();
                $data['holidays'] = TblHolidays::get();

                return view('dashboard.manage_records.penalties', $data);

                break;
            case 'books':
                $data['bounds'] = TblBounds::join('tbl_authors', 'tbl_bounds.Author_ID', '=', 'tbl_authors.Author_ID')->get();
                $data['books'] = TblBooks::whereNull('tbl_weeding.Book_ID')->leftJoin('tbl_weeding', 'tbl_books.Book_ID', '=', 'tbl_weeding.Book_ID')->select('tbl_books.*')->get();

                return view('dashboard.manage_records.books', $data);

                break;
            case 'authors':
                $data['authors'] = TblAuthors::get();

                return view('dashboard.manage_records.authors', $data);

                break;
            case 'publishers':
                $data['publishers'] = TblPublishers::get();
                
                return view('dashboard.manage_records.publishers', $data);

                break;
            case 'categories':
                $data['categories'] = TblCategories::get();
                
                return view('dashboard.manage_records.categories', $data);

                break;
            case 'borrowers':
                $data['borrowers'] = TblAccounts::where('tbl_accounts.Type', '!=', 'Librarian')
                    ->leftJoin('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')
                ->get();

                return view('dashboard.manage_records.borrowers', $data);

                break;
            case 'librarians':
                $data['librarians'] = TblAccounts::where('Type', 'Librarian')->join('tbl_librarians', 'tbl_accounts.Owner_ID', '=', 'tbl_librarians.Librarian_ID')->get();

                return view('dashboard.manage_records.librarians', $data);

                break;
            case 'holidays':
                $data['holidays'] = TblHolidays::get();

                return view('dashboard.manage_records.holidays', $data);

                break;
            case 'reports':
                return view('dashboard.manage_records.reports');

                break;
            case 'weeding':
                $data['weeding'] = TblWeeding::join('tbl_books', 'tbl_weeding.Book_ID', '=', 'tbl_books.Book_ID')->get();

                return view('dashboard.manage_records.weeding', $data);

                break;
            default:
                break;
        }
    }

    public function getBarcodes($id) {
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

        $data['book'] = TblBooks::where('Book_ID', $id)->first();
        $data['barcodes'] = TblBarcodes::where('Book_ID', $id)->get();

        return view('dashboard.manage_records.barcodes', $data);
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
                return view('dashboard.manage_records.add_authors');

                break;
            case 'publishers':
                return view('dashboard.manage_records.add_publishers');

                break;
            case 'categories':
                return view('dashboard.manage_records.add_categories');

                break;
            case 'borrowers':
                return view('dashboard.manage_records.add_borrowers');

                break;
            case 'librarians':
                return view('dashboard.manage_records.add_librarians');

                break;
            case 'holidays':
                return view('dashboard.manage_records.add_holidays');

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
                $data['author'] = TblAuthors::where('Author_ID', $id)->first();

                return view('dashboard.manage_records.edit_authors', $data);

                break;
            case 'publishers':
                $data['publisher'] = TblPublishers::where('Publisher_ID', $id)->first();

                return view('dashboard.manage_records.edit_publishers', $data);

                break;
            case 'categories':
                $data['category'] = TblCategories::where('Category_ID', $id)->first();

                return view('dashboard.manage_records.edit_categories', $data);

                break;
            case 'borrowers':
                $data['borrower'] = TblAccounts::where('tbl_accounts.Owner_ID', $id)->where('tbl_accounts.Type', '!=', 'Librarian')
                    ->leftJoin('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')
                ->first();
                    
                return view('dashboard.manage_records.edit_borrowers', $data);

                break;
            case 'librarians':
                $data['librarian'] = TblAccounts::where('tbl_accounts.Owner_ID', $id)->where('tbl_accounts.Type', 'Librarian')
                    ->leftJoin('tbl_librarians', 'tbl_accounts.Owner_ID', '=', 'tbl_librarians.Librarian_ID')
                ->first();

                return view('dashboard.manage_records.edit_librarians', $data);

                break;
            case 'holidays':
                $data['holiday'] = TblHolidays::where('Holiday_ID', $id)->first();

                return view('dashboard.manage_records.edit_holidays', $data);

                break;
            default:
                break;
        }
    }

    public function getDeleteRecord($what, $id) {
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
            case 'barcodes':
                $barcode = TblBarcodes::where('Accession_Number', $id)->first();

                if($barcode) {
                    $query = TblBarcodes::where('Accession_Number', $id)->delete();

                    if($query) {
                        session()->flash('flash_status', 'success');
                        session()->flash('flash_message', 'Accession Number has been deleted.');
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Failed to delete accession number. Please refresh the page and try again.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Accession Number doesn\'t exist anymore.');
                }

                return redirect()->route('dashboard.getBarcodes', $barcode['Book_ID']);

                break;
            case 'books':
                $query = TblWeeding::where('Book_ID', $id)->delete();

                if($query) {
                    $query1 = TblBarcodes::where('Book_ID', $id)->delete();
                    $query2 = TblBooks::where('Book_ID', $id)->delete();

                    if($query1 && $query2) {
                        session()->flash('flash_status', 'success');
                        session()->flash('flash_message', 'Book has been deleted.');
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Failed to delete book. Please refresh the page and try again.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Failed to delete book. Please refresh the page and try again.');
                }

                return redirect()->route('dashboard.getManageRecords', 'weeding');

                break;
            case 'authors':
                $query = TblAuthors::where('Author_ID', $id)->delete();

                if($query) {
                    session()->flash('flash_status', 'success');
                    session()->flash('flash_message', 'Author has been deleted.');
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Failed to delete author. Please refresh the page and try again.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'publishers':
                $query = TblPublishers::where('Publisher_ID', $id)->delete();

                if($query) {
                    session()->flash('flash_status', 'success');
                    session()->flash('flash_message', 'Publisher has been deleted.');
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Failed to delete publisher. Please refresh the page and try again.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'categories':
                $query = TblCategories::where('Category_ID', $id)->delete();

                if($query) {
                    session()->flash('flash_status', 'success');
                    session()->flash('flash_message', 'Category has been deleted.');
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Failed to delete category. Please refresh the page and try again.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'borrowers':
                $query1 = TblBorrowers::where('Borrower_ID', $id)->delete();
                $query2 = TblAccounts::where('Owner_ID', $id)->whereIn('Type', ['Student', 'Faculty'])->delete();

                if($query1 && $query2) {
                    session()->flash('flash_status', 'success');
                    session()->flash('flash_message', 'Borrower has been deleted.');
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Failed to delete borrower. Please refresh the page and try again.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'librarians':
                $query1 = TblLibrarians::where('Librarian_ID', $id)->delete();
                $query2 = TblAccounts::where('Owner_ID', $id)->where('Type', 'Librarian')->delete();

                if($query1 && $query2) {
                    session()->flash('flash_status', 'success');
                    session()->flash('flash_message', 'Librarian has been deleted.');
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Failed to delete librarian. Please refresh the page and try again.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'holidays':
                $query = TblHolidays::where('Holiday_ID', $id)->delete();

                if($query) {
                    session()->flash('flash_status', 'success');
                    session()->flash('flash_message', 'Holiday has been deleted.');
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Failed to delete holiday. Please refresh the page and try again.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'weeding':
                $query = TblWeeding::insert([
                    'Book_ID' => $id,
                    'Date_Weeded' => date('Y-m-d')
                ]);

                if($query) {
                    session()->flash('flash_status', 'success');
                    session()->flash('flash_message', 'Book has been weeded.');
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Failed to weed book. Please refresh the page and try again.');
                }

                return redirect()->route('dashboard.getManageRecords', 'books');

                break;
            default:
                break;
        }
    }

    public function getRecoverBook($id) {
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

        $query = TblWeeding::where('Book_ID', $id)->delete();

        if($query) {
            session()->flash('flash_status', 'success');
            session()->flash('flash_message', 'Book has been recovered.');
        } else {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Failed to recover book. Please refresh the page and try again.');
        }

        return redirect()->route('dashboard.getManageRecords', 'weeding');
    }

    public function getChangePassword($what, $id) {
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

        $data['what'] = $what;
        $data['id'] = $id;
        if($what == 'borrowers') {
            $data['user'] = TblAccounts::where('tbl_accounts.Username', $id)->where('tbl_accounts.Type', '!=', 'Librarian')
                ->leftJoin('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')
            ->first();
        } else {
            $data['user'] = TblAccounts::where('tbl_accounts.Username', $id)->where('Type', 'Librarian')->join('tbl_librarians', 'tbl_accounts.Owner_ID', '=', 'tbl_librarians.Librarian_ID')->first();
        }

        return view('dashboard.change_password', $data);
    }

    public function getSystemSettings() {
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

        app('App\Http\Controllers\DataController')->checkSettings();

        $settingsFile = storage_path('app/public') . '/settings.xml';
        $data['settings'] = simplexml_load_file($settingsFile);

        return view('dashboard.settings', $data);
    }

    public function postDeleteRecord($what, $id, Request $request) {
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
            case 'barcodes':
                $query = TblWeeding::insert([
                    'Accession_Number' => $request->input('reason'),
                    'Book_ID' => $id,
                    'Reason' => $request->input('reason'),
                    'Date_Weeded' => date('Y-m-d')
                ]);

                if($query) {
                    return response()->json(['status' => 'success', 'message' => 'Book has been weeded.']);
                } else {
                    return response()->json(['status' => 'danger', 'message' => 'Oops! Failed to weed book. Please refresh the page and try again.']);
                }

                break;
            case 'books':
                $query = TblWeeding::insert([
                    'Book_ID' => $id,
                    'Reason' => $request->input('reason'),
                    'Date_Weeded' => date('Y-m-d')
                ]);

                if($query) {
                    return response()->json(['status' => 'success', 'message' => 'Book has been weeded.']);
                } else {
                    return response()->json(['status' => 'danger', 'message' => 'Oops! Failed to weed book. Please refresh the page and try again.']);
                }

                break;
            default:
                break;
        }
    }

    public function postAddBarcode(Request $request) {
        if(!session()->has('username')) {
            return response()->json(['status' => 'Failed', 'message' => 'Oops! Please login first...']);
        } else {
            if(session()->get('type') != 'Librarian') {
                return response()->json(['status' => 'Failed', 'message' => 'Oops! You do not have to privilege to access the dashboard.']);
            }
        }

        $addedBarcodes = 0;

        for($i = 0; $i < $request->input('numberOfCopies'); $i++) {
            $query = TblBarcodes::insert([
                'Book_ID' => $request->input('id')
            ]);

            if($query) {
                $addedBarcodes++;
            } else {
                $i--;
            }
        }

        if($addedBarcodes > 0) {
            return response()->json(['status' => 'Success', 'message' => 'Added: ' . $addedBarcodes . ' accession number(s).']);
        } else {
            return response()->json(['status' => 'Failed', 'message' => 'Oops! Failed to generate accession number.']);
        }
    }

    public function postChangePassword($what, $id, Request $request) {
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

        if($request->input('newPassword') == $request->input('confirmNewPassword')) {
            switch($what) {
                case 'borrowers':
                    $query = TblAccounts::where('Username', $id)->update([
                        'Password' => md5($request->input('newPassword'))
                    ]);

                    if($query) {
                        session()->flash('flash_status', 'success');
                        session()->flash('flash_message', 'Password has been changed.');
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Failed to change password.');
                    }

                    return redirect()->route('dashboard.getManageRecords', $what);

                    break;
                case 'librarians':
                    $query = TblAccounts::where('Username', $id)->update([
                        'Password' => md5($request->input('newPassword'))
                    ]);

                    if($query) {
                        session()->flash('flash_status', 'success');
                        session()->flash('flash_message', 'Password has been changed.');
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Failed to change password.');
                    }

                    return redirect()->route('dashboard.getManageRecords', $what);

                    break;
                default:
                    break;
            }
        } else {
            // "New Password" and "Confirm New Password" fields don't match
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
                        'Description' => $request->input('description'),
                        'Language' => $request->input('language'),
                        'Date_Acquired' => $request->input('dateAcquired'),
                        'Call_Number' => $request->input('callNumber'),
                        'Edition' => $request->input('edition'),
                        'Location' => $request->input('location'),
                        'Copyright_Year' => $request->input('copyrightYear'),
                        'Year_Published' => $request->input('yearPublished'),
                        'Number_of_Pages' => $request->input('numberOfPages'),
                        'Acquisition_Method' => $request->input('acquisitionMethod'),
                        'Price' => $request->input('price'),
                        'ISBN' => $request->input('isbn'),
                        'Publisher_ID' => $request->input('publisher'),
                        'Category_ID' => $request->input('category')
                    ]);

                    if($bookID) {
                        $addedAuthors = 0;

                        for($i = 0; $i < $request->input('numberOfCopies'); $i++) {
                            $query = TblBarcodes::insert([
                                'Book_ID' => $bookID
                            ]);

                            if(!$query) {
                                $i--;
                            }
                        }

                        foreach(array_unique($request->input('authors')) as $author) {
                            $query = TblBounds::insert([
                                'Book_ID' => $bookID,
                                'Author_ID' => $author
                            ]);

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
                $query = TblAuthors::where('First_Name', $request->input('firstName'))->where('Last_Name', $request->input('lastName'))->first();

                if(!$query) {
                    $query = TblAuthors::insert([
                        'First_Name' => $request->input('firstName'),
                        'Middle_Name' => $request->input('middleName'),
                        'Last_Name' => $request->input('lastName')
                    ]);

                    if($query) {
                        session()->flash('flash_status', 'success');
                        session()->flash('flash_message', 'Author has been added.');
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Failed to add author. Please refresh the page and try again.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Author already exist.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'publishers':
                $query = TblPublishers::where('Publisher_Name', $request->input('publisherName'))->first();

                if(!$query) {
                    $query = TblPublishers::insert([
                        'Publisher_Name' => $request->input('publisherName')
                    ]);

                    if($query) {
                        session()->flash('flash_status', 'success');
                        session()->flash('flash_message', 'Publisher has been added.');
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Failed to add publisher. Please refresh the page and try again.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Publisher already exist.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'categories':
                $query = TblCategories::where('Category_Name', $request->input('categoryName'))->first();

                if(!$query) {
                    $query = TblCategories::insert([
                        'Category_Name' => $request->input('categoryName')
                    ]);

                    if($query) {
                        session()->flash('flash_status', 'success');
                        session()->flash('flash_message', 'Category has been added.');
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Failed to add category. Please refresh the page and try again.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Category already exist.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'borrowers':
                $query = TblAccounts::where('Username', $request->input('borrowerID'))->first();

                if(!$query) {
                    $query = TblBorrowers::where('First_Name', $request->input('firstName'))->where('Last_Name', $request->input('lastName'))->first();

                    if(!$query) {
                        $borrowerID = TblBorrowers::insertGetId([
                            'First_Name' => $request->input('firstName'),
                            'Middle_Name' => $request->input('middleName'),
                            'Last_Name' => $request->input('lastName'),
                            'Birth_Date' => $request->input('birthDate'),
                            'Gender' => $request->input('gender'),
                            'Address' => $request->input('address'),
                            'Telephone_Number' => $request->input('telephoneNumber'),
                            'Cellphone_Number' => $request->input('cellphoneNumber'),
                            'Year_Level' => $request->input('yearLevel'),
                            'Course' => $request->input('course')
                        ]);

                        if($borrowerID) {
                            $query = TblAccounts::insert([
                                'Username' => $request->input('borrowerID'),
                                'Password' => md5($request->input('birthDate')),
                                'Type' => $request->input('type'),
                                'Owner_ID' => $borrowerID
                            ]);

                            if($query) {
                                session()->flash('flash_status', 'success');
                                session()->flash('flash_message', 'Borrower has been added.');
                            } else {
                                session()->flash('flash_status', 'danger');
                                session()->flash('flash_message', 'Oops! Borrower has been added but failed to associate login account.');
                            }
                        } else {
                            session()->flash('flash_status', 'danger');
                            session()->flash('flash_message', 'Oops! Failed to add borrower. Please refresh the page and try again.');
                        }
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Borrower already exist.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Borrower ID already in use by another person.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'librarians':
                $query = TblAccounts::where('Username', $request->input('librarianID'))->first();

                if(!$query) {
                    $query = TblLibrarians::where('First_Name', $request->input('firstName'))->where('Last_Name', $request->input('lastName'))->first();

                    if(!$query) {
                        $librarianID = TblLibrarians::insertGetId([
                            'First_Name' => $request->input('firstName'),
                            'Middle_Name' => $request->input('middleName'),
                            'Last_Name' => $request->input('lastName'),
                            'Birth_Date' => $request->input('birthDate'),
                            'Gender' => $request->input('gender')
                        ]);

                        if($librarianID) {
                            $query = TblAccounts::insert([
                                'Username' => $request->input('librarianID'),
                                'Password' => md5($request->input('birthDate')),
                                'Type' => 'Librarian',
                                'Email_Address' => $request->input('emailAddress'),
                                'Owner_ID' => $librarianID
                            ]);

                            if($query) {
                                session()->flash('flash_status', 'success');
                                session()->flash('flash_message', 'Librarian has been added.');
                            } else {
                                session()->flash('flash_status', 'danger');
                                session()->flash('flash_message', 'Oops! Librarian has been added but failed to associate login account.');
                            }
                        } else {
                            session()->flash('flash_status', 'danger');
                            session()->flash('flash_message', 'Oops! Failed to add librarian. Please refresh the page and try again.');
                        }
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Librarian already exist.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Username already in use by another person.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'holidays':
                $query = TblHolidays::insert([
                    'Description' => $request->input('holidayDescription'),
                    'Date_Stamp' => $request->input('dateStamp'),
                    'Type' => $request->input('type')
                ]);

                if($query) {
                    session()->flash('flash_status', 'success');
                    session()->flash('flash_message', 'Holiday has been added.');
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Failed to add holiday. Please refresh the page and try again.');
                }
                
                return redirect()->route('dashboard.getManageRecords', $what);

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
                            'Description' => $request->input('description'),
                            'Language' => $request->input('language'),
                            'Date_Acquired' => $request->input('dateAcquired'),
                            'Call_Number' => $request->input('callNumber'),
                            'Edition' => $request->input('edition'),
                            'Location' => $request->input('location'),
                            'Copyright_Year' => $request->input('copyrightYear'),
                            'Year_Published' => $request->input('yearPublished'),
                            'Number_of_Pages' => $request->input('numberOfPages'),
                            'Acquisition_Method' => $request->input('acquisitionMethod'),
                            'Price' => $request->input('price'),
                            'ISBN' => $request->input('isbn'),
                            'Publisher_ID' => $request->input('publisher'),
                            'Category_ID' => $request->input('category')
                        ]);

                        foreach(array_unique($request->input('authors')) as $author) {
                            $query = TblBounds::insert([
                                'Book_ID' => $id,
                                'Author_ID' => $author
                            ]);

                            if($query) {
                                $addedAuthors++;
                            }
                        }

                        if($addedAuthors > 0) {
                            session()->flash('flash_status', 'success');
                            session()->flash('flash_message', 'Book has been updated.');
                        } else {
                            session()->flash('flash_status', 'warning');
                            session()->flash('flash_message', 'Oops! Book has been updated but failed to associate author(s).');
                        }
                    } else {
                        session()->flash('flash_status', 'warning');
                        session()->flash('flash_message', 'Oops! Failed to update book. Please refresh the page and try again.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! This book doesn\'t exist anymore.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'authors':
                $query = TblAuthors::where('First_Name', $request->input('firstName'))->where('Last_Name', $request->input('lastName'))->first();

                if(!$query || ($query && $query->Author_ID == $id)) {
                    $query = TblAuthors::where('Author_ID', $id)->update([
                        'First_Name' => $request->input('firstName'),
                        'Middle_Name' => $request->input('middleName'),
                        'Last_Name' => $request->input('lastName')
                    ]);

                    if($query) {
                        session()->flash('flash_status', 'success');
                        session()->flash('flash_message', 'Author has been updated.');
                    } else {
                        session()->flash('flash_status', 'warning');
                        session()->flash('flash_message', 'No changes has been made.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Author already exist.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'publishers':
                $query = TblPublishers::where('Publisher_Name', $request->input('publisherName'))->first();

                if(!$query || ($query && $query->Publisher_ID == $id)) {
                    $query = TblPublishers::where('Publisher_ID', $id)->update([
                        'Publisher_Name' => $request->input('publisherName')
                    ]);

                    if($query) {
                        session()->flash('flash_status', 'success');
                        session()->flash('flash_message', 'Publisher has been updated.');
                    } else {
                        session()->flash('flash_status', 'warning');
                        session()->flash('flash_message', 'No changes has been made.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Publisher already exist.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'categories':
                $query = TblCategories::where('Category_Name', $request->input('categoryName'))->first();

                if(!$query || ($query && $query->Category_ID == $id)) {
                    $query = TblCategories::where('Category_ID', $id)->update([
                        'Category_Name' => $request->input('categoryName')
                    ]);

                    if($query) {
                        session()->flash('flash_status', 'success');
                        session()->flash('flash_message', 'Category has been updated.');
                    } else {
                        session()->flash('flash_status', 'warning');
                        session()->flash('flash_message', 'No changes has been made.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Category already exist.');
                }

                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'borrowers':
                /*
                    Possible Future Update(s):
                    => Check if name is already in the database
                */
                $query = TblAccounts::where('Owner_ID', $id)->first();

                if($query) {
                    $query = TblAccounts::where('Username', $request->input('borrowerID'))->first();

                    if(!$query || ($query && $query->Owner_ID == $id)) {
                        $query1 = TblAccounts::where('Owner_ID', $id)->where('Type', '!=', 'Librarian')->update([
                            'Username' => $request->input('borrowerID'),
                            'Type' => $request->input('type')
                        ]);

                        $query2 = TblBorrowers::where('Borrower_ID', $id)->update([
                            'First_Name' => $request->input('firstName'),
                            'Middle_Name' => $request->input('middleName'),
                            'Last_Name' => $request->input('lastName'),
                            'Birth_Date' => $request->input('birthDate'),
                            'Gender' => $request->input('gender'),
                            'Address' => $request->input('address'),
                            'Telephone_Number' => $request->input('telephoneNumber'),
                            'Cellphone_Number' => $request->input('cellphoneNumber'),
                            'Year_Level' => $request->input('yearLevel'),
                            'Course' => $request->input('course')
                        ]);

                        if($query1 || $query2) {
                            session()->flash('flash_status', 'success');
                            session()->flash('flash_message', 'Borrower has been updated.');
                        } else {
                            session()->flash('flash_status', 'warning');
                            session()->flash('flash_message', 'No changes has been made.');
                        }
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Borrower ID already in use by another person.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Borrower doesn\'t exist.');
                }
                
                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'librarians':
                /*
                    Possible Future Update(s):
                    => Check if name is already in the database
                */
                $query = TblAccounts::where('Owner_ID', $id)->where('Type', 'Librarian')->first();

                if($query) {
                    $query = TblAccounts::where('Username', $request->input('librarianID'))->first();

                    if(!$query || ($query && $query->Owner_ID == $id && $query->Type == 'Librarian')) {
                        $query1 = TblAccounts::where('Owner_ID', $id)->where('Type', 'Librarian')->update([
                            'Username' => $request->input('librarianID'),
                            'Email_Address' => $request->input('emailAddress')
                        ]);

                        $query2 = TblLibrarians::where('Librarian_ID', $id)->update([
                            'First_Name' => $request->input('firstName'),
                            'Middle_Name' => $request->input('middleName'),
                            'Last_Name' => $request->input('lastName'),
                            'Birth_Date' => $request->input('birthDate'),
                            'Gender' => $request->input('gender')
                        ]);

                        if($query1 || $query2) {
                            session()->flash('flash_status', 'success');
                            session()->flash('flash_message', 'Librarian has been updated.');
                        } else {
                            session()->flash('flash_status', 'warning');
                            session()->flash('flash_message', 'No changes has been made.');
                        }
                    } else {
                        session()->flash('flash_status', 'danger');
                        session()->flash('flash_message', 'Oops! Username already in use by another person.');
                    }
                } else {
                    session()->flash('flash_status', 'danger');
                    session()->flash('flash_message', 'Oops! Librarian doesn\'t exist.');
                }
                
                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            case 'holidays':
                $query = TblHolidays::where('Holiday_ID', $id)->update([
                    'Description' => $request->input('holidayDescription'),
                    'Date_Stamp' => $request->input('dateStamp'),
                    'Type' => $request->input('type')
                ]);

                if($query) {
                    session()->flash('flash_status', 'success');
                    session()->flash('flash_message', 'Holiday has been updated.');
                } else {
                    session()->flash('flash_status', 'warning');
                    session()->flash('flash_message', 'No changes has been made.');
                }
                
                return redirect()->route('dashboard.getManageRecords', $what);

                break;
            default:
                break;
        }
    }

    public function postLoanBooks(Request $request) {
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

        $availableBarcodes = [];

        $borrower = TblAccounts::where('tbl_accounts.Username', $request->input('borrower'))->where('tbl_accounts.Type', '!=', 'Librarian')
            ->leftJoin('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')
        ->first();

        if($borrower) {
            if(strlen($borrower->Middle_Name) > 1) {
                $borrowerName = $borrower->First_Name . ' ' . substr($borrower->Middle_Name, 0, 1) . '. ' . $borrower->Last_Name;
            } else {
                $borrowerName = $borrower->First_Name . ' ' . $borrower->Last_Name;
            }

            $barcodes = $request->input('accessions');
            $ctr = 0;

            foreach($barcodes as $barcode) {
                $query1 = TblBarcodes::where('Book_ID', $barcode['id'])->where('Status', 'available')->count();
                $query2 = TblReservations::where('Book_ID', $barcode['id'])->count();

                if($query1 - $query2 > 0) {
                    $query = TblBarcodes::where('tbl_barcodes.Accession_Number', $barcode['accession'])->where('tbl_barcodes.Status', 'available')->leftJoin('tbl_loans', 'tbl_barcodes.Accession_Number', '=', 'tbl_loans.Accession_Number')->select('tbl_barcodes.*', DB::raw('count(tbl_loans.Accession_Number) as Loan_Count'))->groupBy('tbl_barcodes.Accession_Number')->orderBy('Loan_Count')->orderBy('tbl_barcodes.Accession_Number')->first();

                    if($query) {
                        $query = TblLoans::insert([
                            'Accession_Number' => $barcode['accession'],
                            'Username' => $request->input('borrower'),
                            'Loan_Date_Stamp' => date('Y-m-d'),
                            'Loan_Time_Stamp' => date('H:i:s')
                        ]);

                        if($query) {
                            $query = TblBarcodes::where('Accession_Number', $barcode['accession'])->update([
                                'Status' => 'on-loan'
                            ]);

                            if($query) {
                                array_push($availableBarcodes, $barcode['accession']);

                                $ctr++;
                            }
                        }
                    }
                }
            }

            if($ctr > 0) {
                return response()->json(['status' => 'Success', 'message' => 'Loan Successful. You may now hand the book(s) to the borrower, ' . $borrowerName . '.', 'data' => ['barcodes' => $availableBarcodes, 'borrower' => $borrowerName]]);
            } else {
                return response()->json(['status' => 'Failed', 'message' => 'Oops! Failed to loan book.']);
            }
        } else {
            return response()->json(['status' => 'Failed', 'message' => 'Oops! Borrower doesn\'t exist. Please refresh the page and try again.']);
        }
    }

    public function postReservedBooks(Request $request) {
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

        $availableBarcodes = [];

        $reservation = TblReservations::where('Reservation_ID', $request->input('id'))->first();

        if($reservation) {
            $borrower = TblAccounts::where('tbl_accounts.Username', $reservation->Username)->where('tbl_accounts.Type', '!=', 'Librarian')
                ->leftJoin('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')
            ->first();

            if($borrower) {
                if(strlen($borrower->Middle_Name) > 1) {
                    $borrowerName = $borrower->First_Name . ' ' . substr($borrower->Middle_Name, 0, 1) . '. ' . $borrower->Last_Name;
                } else {
                    $borrowerName = $borrower->First_Name . ' ' . $borrower->Last_Name;
                }

                $query = TblBarcodes::where('Book_ID', $reservation->Book_ID)->where('Status', 'available')->leftJoin('tbl_loans', 'tbl_barcodes.Accession_Number', '=', 'tbl_loans.Accession_Number')->select('tbl_barcodes.*', DB::raw('count(tbl_loans.Accession_Number) as Loan_Count'))->groupBy('tbl_barcodes.Accession_Number')->orderBy('Loan_Count')->orderBy('tbl_barcodes.Accession_Number')->first();

                if($query) {
                    $barcode = $query->Accession_Number;

                    $query = TblLoans::insert([
                        'Accession_Number' => $barcode,
                        'Username' => $reservation->Username,
                        'Loan_Date_Stamp' => date('Y-m-d'),
                        'Loan_Time_Stamp' => date('H:i:s')
                    ]);

                    if($query) {
                        $query = TblBarcodes::where('Accession_Number', $barcode)->update([
                            'Status' => 'on-loan'
                        ]);

                        $query = TblReservations::where('Reservation_ID', $request->input('id'))->update([
                            'Reservation_Status' => 'inactive'
                        ]);

                        return response()->json(['status' => 'Success', 'message' => 'Loan Successful. You may now hand the book with an accession number of C' . sprintf('%04d', $barcode) . ' to the borrower, ' . $borrowerName . '.', 'data' => ['barcode' => $barcode, 'borrower' => $borrowerName]]);
                    } else {
                        return response()->json(['status' => 'Failed', 'message' => 'Oops! No more available copies.']);
                    }
                } else {
                    return response()->json(['status' => 'Failed', 'message' => 'Oops! No more available copies.']);
                }
            } else {
                return response()->json(['status' => 'Failed', 'message' => 'Oops! Borrower doesn\'t exist. Please refresh the page and try again.']);
            }
        } else {
            return response()->json(['status' => 'Failed', 'message' => 'Oops! Reservation doesn\'t exist anymore.']);
        }
    }

    public function postReceiveBooks(Request $request) {
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

        $availableBarcodes = [];
        $ctr = 0;

        $infos = $request->input('infos');

        foreach($infos as $info) {
            $query = TblLoans::where('Loan_ID', $info['id'])->update([
                'Loan_Status' => 'inactive'
            ]);

            if($query) {
                $thisLoan = TblLoans::where('Loan_ID', $info['id'])->first();

                if($info['condition'] == 'lost') {
                    $penalty = 0;
                } else {
                    $penalty = app('App\Http\Controllers\DataController')->computePenalty($thisLoan->Loan_Date_Stamp . ' ' . $thisLoan->Loan_Time_Stamp);
                }

                $query = TblReceives::insert([
                    'Receive_Date_Stamp' => date('Y-m-d'),
                    'Receive_Time_Stamp' => date('H:i:s'),
                    'Reference_ID' => $info['id'],
                    'Penalty' => $penalty,
                    'Settlement_Status' => ($penalty > 0 ? 'unpaid' : 'paid')
                ]);

                if($query) {
                    TblBarcodes::where('Accession_Number', $info['accession'])->update([
                        'Condition' => $info['condition'],
                        'Status' => 'available'
                    ]);

                    if($query) {
                        array_push($availableBarcodes, [
                            'accession' => $info['accession'],
                            'condition' => $info['condition'],
                            'penalty' => $penalty
                        ]);

                        $ctr++;
                    }
                }
            }
        }

        if($ctr > 0) {
            return response()->json(['status' => 'Success', 'message' => 'Receive Successful.', 'data' => ['barcodes' => $availableBarcodes]]);
        } else {
            return response()->json(['status' => 'Failed', 'message' => 'Oops! Failed to receive book.']);
        }
    }

    public function postSettlePenalty($id) {
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

        $query = TblReceives::where('Receive_ID', $id)->update([
            'Settlement_Status' => 'paid'
        ]);

        if($query) {
            session()->flash('flash_status', 'success');
            session()->flash('flash_message', 'Penalty has been settled.');
        } else {
            session()->flash('flash_status', 'warning');
            session()->flash('flash_message', 'Oops! Failed to settle penalty. Please refresh the page and try again.');
        }
    }

    public function postGenerateReport($what, Request $request) {
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
            case 'list_of_books':
                $data['from'] = date('Y-m-d', strtotime($request->input('from')));;
                $data['to'] = date('Y-m-d', strtotime($request->input('to')));;
                $data['books'] = TblBooks::whereBetween('Date_Acquired', array($data['from'], $data['to']))->get();
                $data['books_count'] = TblBooks::whereBetween('Date_Acquired', array($data['from'], $data['to']))->count();

                $pdf = PDF::loadView('reports.list_of_books', $data);

                return $pdf->stream('BCC_List_of_Books_Report.pdf');

                break;
            case 'list_of_borrowed_books':
                $data['from'] = date('Y-m-d', strtotime($request->input('from')));;
                $data['to'] = date('Y-m-d', strtotime($request->input('to')));;
                $data['books'] = TblLoans::whereBetween('tbl_loans.Loan_Date_Stamp', array($data['from'], $data['to']))
                    ->join('tbl_barcodes', 'tbl_loans.Accession_Number', '=', 'tbl_barcodes.Accession_Number')
                    ->join('tbl_books', 'tbl_barcodes.Book_ID', '=', 'tbl_books.Book_ID')
                    ->join('tbl_accounts', 'tbl_loans.Username', '=', 'tbl_accounts.Username')
                    ->join('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')
                ->get();
                $data['books_count'] = TblLoans::whereBetween('tbl_books.Date_Acquired', array($data['from'], $data['to']))->join('tbl_barcodes', 'tbl_loans.Accession_Number', '=', 'tbl_barcodes.Accession_Number')->join('tbl_books', 'tbl_barcodes.Book_ID', '=', 'tbl_books.Book_ID')->count();

                $pdf = PDF::loadView('reports.list_of_borrowed_books', $data);

                return $pdf->stream('BCC_List_of_Borrowed_Books_Report.pdf');

                break;
            case 'list_of_unreturned_books':
                $data['from'] = date('Y-m-d', strtotime($request->input('from')));;
                $data['to'] = date('Y-m-d', strtotime($request->input('to')));;
                $data['books'] = TblLoans::whereBetween('tbl_loans.Loan_Date_Stamp', array($data['from'], $data['to']))->where('tbl_receives.Reference_ID', null)
                    ->join('tbl_barcodes', 'tbl_loans.Accession_Number', '=', 'tbl_barcodes.Accession_Number')
                    ->join('tbl_books', 'tbl_barcodes.Book_ID', '=', 'tbl_books.Book_ID')
                    ->join('tbl_accounts', 'tbl_loans.Username', '=', 'tbl_accounts.Username')
                    ->join('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')
                    ->leftJoin('tbl_receives', 'tbl_loans.Loan_ID', '=', 'tbl_receives.Reference_ID')
                ->get();

                $pdf = PDF::loadView('reports.list_of_unreturned_books', $data);

                return $pdf->stream('BCC_List_of_Unreturned_Books_Report.pdf');

                break;
            case 'penalty':
                $data['from'] = date('Y-m-d', strtotime($request->input('from')));;
                $data['to'] = date('Y-m-d', strtotime($request->input('to')));;
                $data['books'] = TblLoans::whereBetween('tbl_loans.Loan_Date_Stamp', array($data['from'], $data['to']))
                    ->join('tbl_barcodes', 'tbl_loans.Accession_Number', '=', 'tbl_barcodes.Accession_Number')
                    ->join('tbl_books', 'tbl_barcodes.Book_ID', '=', 'tbl_books.Book_ID')
                    ->join('tbl_accounts', 'tbl_loans.Username', '=', 'tbl_accounts.Username')
                    ->join('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')
                    ->leftJoin('tbl_receives', 'tbl_loans.Loan_ID', '=', 'tbl_receives.Reference_ID')
                ->get();

                $pdf = PDF::loadView('reports.penalty', $data);

                return $pdf->stream('BCC_Penalty_Report.pdf');

                break;
            case 'attendance':
                $data['from'] = date('Y-m-d', strtotime($request->input('from')));;
                $data['to'] = date('Y-m-d', strtotime($request->input('to')));;
                $data['borrowers'] = TblAttendances::orderBy('tbl_attendance.Date_Stamp')->orderBy('tbl_attendance.Time_Stamp')->orderBy('tbl_borrowers.Year_Level')->orderBy('tbl_borrowers.Course')->join('tbl_accounts', 'tbl_attendance.Username', '=', 'tbl_accounts.Username')->join('tbl_borrowers', 'tbl_accounts.Owner_ID', '=', 'tbl_borrowers.Borrower_ID')->get();

                $pdf = PDF::loadView('reports.attendance', $data);

                return $pdf->stream('BCC_Attendance_Report.pdf');

                break;
            default:
                break;
        }
    }

    public function postSystemSettings(Request $request) {
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

        app('App\Http\Controllers\DataController')->checkSettings();

        $settingsFile = storage_path('app/public') . '/settings.xml';
        $xml = simplexml_load_file($settingsFile);

        foreach($xml as $item) {
            if(md5($item['name']) == $request->input('settingName')) {
                $item['value'] = $request->input('settingValue');
            }
        }

        if($xml->asXML($settingsFile)) {
            session()->flash('flash_status', 'success');
            session()->flash('flash_message', 'Changes has been saved.');
        } else {
            session()->flash('flash_status', 'danger');
            session()->flash('flash_message', 'Oops! Failed to save changes. Please refresh the page and try again.');
        }

        return redirect()->route('dashboard.getSystemSettings');
    }
}
