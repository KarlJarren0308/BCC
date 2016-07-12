@extends('template')

@section('meta_tags')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <li><a href="{{ route('cardinal.getReservations') }}">My Reservations</a></li>
                        <li><a href="{{ route('cardinal.getBorrowedBooks') }}">Borrowed Books</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div>
                <div class="well" style="display: inline-block; margin-top: 40px; width: 100%;">
                    <div style="border-bottom: 2px solid #222; padding: 0 10px 10px 10px; margin-bottom: 15px;">
                        <h1 class="no-margin">
                            @if(strlen($user->Middle_Name) > 1)
                                {{ $user->First_Name . ' ' . substr($user->Middle_Name, 0, 1) . '. ' . $user->Last_Name }}
                            @else
                                {{ $user->First_Name . ' ' . $user->Last_Name }}
                            @endif
                        </h1>
                        <h4 class="no-margin">{{ $account->Type }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop