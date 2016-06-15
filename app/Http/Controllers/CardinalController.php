<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\TblAccounts;
use App\TblBooks;
use App\TblBounds;
use App\TblLibrarians;
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

        return view('cardinal.opac');
    }

    public function getForgotPassword() {
        return view('cardinal.forgot_password');
    }

    public function getLogout() {
        session()->flush();

        return redirect()->route('cardinal.getIndex');
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
