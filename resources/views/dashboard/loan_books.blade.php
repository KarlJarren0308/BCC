@extends('template')

@section('meta_tags')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('pre_ref')
    <style>
        .crank-loader {
            display: none;
            margin-top: 100px;
            text-align: center;
        }

        #search-borrower-list,
        #search-book-list {
            margin: 25px 0;
            overflow-y: scroll;
            max-height: 300px;
        }

        #search-borrower-list > .item,
        #search-book-list > .item {
            background: #eee;
            border-left: 5px solid #d9534f;
            margin-bottom: 2px;
        }

        #search-borrower-list > .item > .item-body,
        #search-book-list > .item > .item-body {
            padding: 1rem 1.5rem;
        }

        #search-book-list > .item:nth-child(even) {
            background: white;
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
                <li><a href="{{ route('cardinal.getOpac') }}">Online Public Access Catalog</a></li>
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
                        <li><a href="{{ route('dashboard.getIndex') }}">Dashboard</a></li>
                        <li><a href="{{ route('dashboard.getLoanBooks') }}">Loan Books</a></li>
                        <li><a href="{{ route('dashboard.getReservedBooks') }}">Reserved Books</a></li>
                        <li><a href="{{ route('dashboard.getReceiveBooks') }}">Receive Books</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'penalties') }}">Manage Penalties</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'books') }}">Manage Book Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'authors') }}">Manage Author Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'publishers') }}">Manage Publisher Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'categories') }}">Manage Categories Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'borrowers') }}">Manage Borrowers</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'librarians') }}">Manage Librarians</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'holidays') }}">Manage Holidays</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'weeding') }}">Weeded Books</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'reports') }}">Generate Reports</a></li>
                        <li><a href="{{ route('dashboard.getSystemSettings') }}">System Settings</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Loan Books</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="col-sm-12">
                        <label for="">Search Borrower:</label>
                    </div>
                    <div class="col-sm-12">
                        <form class="form-inline" data-form="search-borrower-form">
                            <div class="form-group">
                                <input type="text" name="searchKeyword" class="form-control" required autofocus>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-danger" value="Search">
                            </div>
                        </form>
                    </div>
                    <div id="borrower-list-crank" class="crank-loader">
                        <span class="fa fa-spinner fa-pulse fa-4x" style="color: #d9534f;"></span>
                        <h3 style="color: #d9534f; margin: 10px 0;">Searching...</h3>
                    </div>
                    <div id="search-borrower-list" class="col-sm-12"></div>
                </div>
                <div class="col-sm-4">
                    <div class="col-sm-12">
                        <label for="">Search Book:</label>
                    </div>
                    <div class="col-sm-12">
                        <form class="form-inline" data-form="search-book-form">
                            <div class="form-group">
                                <input type="text" name="searchKeyword" class="form-control" maxlength="5" required>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-danger" value="Search">
                            </div>
                        </form>
                    </div>
                    <div id="book-list-crank" class="crank-loader">
                        <span class="fa fa-spinner fa-pulse fa-4x" style="color: #d9534f;"></span>
                        <h3 style="color: #d9534f; margin: 10px 0;">Searching...</h3>
                    </div>
                    <div id="search-book-list" class="col-sm-12"></div>
                </div>
                <div class="col-sm-4">
                    <div class="well">
                        <h3 style="margin-top: 0;">Borrower</h3>
                        <div id="borrower-block" class="list-group beautify"></div>
                    </div>
                    <div class="well">
                        <h3 style="margin-top: 0;">Cart</h3>
                        <div id="books-block" class="list-group beautify"></div>
                    </div>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-right">
                        <button class="btn btn-danger btn-lg" data-button="loan-button" disabled>Loan Book(s) to Borrower</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@stop

@section('post_ref')
    <script src="/js/dashboard/loan_books.js"></script>
@stop