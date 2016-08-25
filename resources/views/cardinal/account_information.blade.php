@extends('template')

@section('meta_tags')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('pre_ref')
    <style>
        .account-block {
            background: #eee;
            border-left: 5px solid #dd1e2f;
            box-shadow: 0 2px 2px rgba(25, 40, 35, 0.5);
            display: inline-block;
            padding: 25px;
            margin-bottom: 10px;
            width: 100%;
        }

        .account-block.yellow {
            border-left: 5px solid #f0ad4e;
        }
    </style>
@stop

@section('content')
    <div id="wrapper">
        <nav class="navbar navbar-custom navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar bg-red"></span>
                    <span class="icon-bar bg-red"></span>
                    <span class="icon-bar bg-red"></span>
                </button>
                <div class="navbar-brand">Binangonan Catholic College</div>
            </div>
            <ul class="nav navbar-top-links navbar-right text-right">
                @if(session()->get('type') == 'Librarian')
                    <li><a href="{{ route('dashboard.getIndex') }}">Dashboard</a></li>
                @endif
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Menu
                        &nbsp;
                        <span class="fa fa-caret-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="{{ route('cardinal.getAccountInformation') }}"><i class="fa fa-user fa-fw"></i> Account Information</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('cardinal.getLogout') }}">Logout</a></li>
                    </ul>
                </li>
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="fg-yellow" style="padding: 10px 15px; text-shadow: 1px 1px 0 rgba(25, 40, 35, 0.25);">
                            @if(strlen(session()->get('middle_name')))
                                <h4 class="no-margin">{{ session()->get('first_name') . ' ' . substr(session()->get('middle_name'), 0, 1) . '. ' . session()->get('last_name') }}</h4>
                            @else
                                <h4 class="no-margin">{{ session()->get('first_name') . ' ' . session()->get('last_name') }}</h4>
                            @endif
                            <div class="text-left">{{ session()->get('type') }}</div>
                        </li>
                        <li><a href="{{ route('cardinal.getOpac') }}">Online Public Access Catalog</a></li>
                        @if(session()->get('type') != 'Librarian')
                            <li><a href="{{ route('cardinal.getReservations') }}">My Reservations</a></li>
                        @endif
                        <li><a href="{{ route('cardinal.getBorrowedBooks') }}">Borrowed Books</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div style="padding: 0 50px;">
                <div class="account-block" style="margin-top: 40px;">
                    <h1 class="no-margin">
                        @if(strlen($user->Middle_Name) > 1)
                            {{ $user->First_Name . ' ' . substr($user->Middle_Name, 0, 1) . '. ' . $user->Last_Name }}
                        @else
                            {{ $user->First_Name . ' ' . $user->Last_Name }}
                        @endif
                        <div class="pull-right">
                            <span class="fa fa-{{ ($user->Gender == 'Male' ? 'mars' : 'venus') }}"></span>
                        </div>
                    </h1>
                    <h4 class="no-margin">{{ $account->Type }}</h4>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="account-block yellow">
                            <h2 class="no-margin">Loan History</h2>
                            <hr class="line-dark">
                            <div class="list-group">
                                @if(count($loan_history) > 0)
                                    @foreach($loan_history as $loan)
                                        <div class="list-group-item">
                                            <h3 class="list-group-item-heading">{{ $loan->Title }}</h3>
                                            <div class="list-group-item-text">
                                                <div>Loaned last {{ date('F d, Y', strtotime($loan->Loan_Date_Stamp)) }}</div>
                                                @if($loan->Receive_Reference_ID == null)
                                                    <div>This book is currently on-loan.</div>
                                                @else
                                                    <div>This book has been returned.</div>
                                                    <div>Penalty: &#8369; {{ $loan->Penalty }}.00</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="list-group-item text-center">None at the moment.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="account-block yellow">
                            <h2 class="no-margin">Reservation History</h2>
                            <hr class="line-dark">
                            <div class="list-group">
                                @if(count($reservation_history) > 0)
                                    @foreach($reservation_history as $reservation)
                                        <div class="list-group-item">
                                            <h3 class="list-group-item-heading">{{ $reservation->Title }}</h3>
                                            <div class="list-group-item-text">
                                                <div>Reserved last {{ date('F d, Y', strtotime($reservation->Reservation_Date_Stamp)) }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="list-group-item text-center">None at the moment.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop