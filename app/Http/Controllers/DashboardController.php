<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

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
}
