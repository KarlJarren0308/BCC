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
                        <li><a class="active" href="{{ route('dashboard.getManageRecords', 'books') }}">Manage Book Records<span class="badge pull-right">A. N.</span></a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'authors') }}">Manage Author Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'publishers') }}">Manage Publisher Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'categories') }}">Manage Categories Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'borrowers') }}">Manage Borrowers</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'librarians') }}">Manage Librarians</a></li>
                        <li><a href="{{ route('dashboard.getSystemSettings') }}">System Settings</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Manage Accession Numbers</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-7">
                    <div class="text-left" style="margin-bottom: 25px;">
                        <a href="{{ route('dashboard.getManageRecords', 'books') }}" class="btn btn-danger btn-xs"><span class="fa fa-arrow-left gap-right"></span>Go Back</a>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td class="text-right" width="25%"><strong>Book Title:</strong></td>
                                <td>{{ $book->Title }}</td>
                            </tr>
                            <tr>
                                <td class="text-right" width="25%"><strong>Edition:</strong></td>
                                <td>{{ $book->Edition }}</td>
                            </tr>
                            <tr>
                                <td class="text-right" width="25%"><strong>Copyright Year:</strong></td>
                                <td>{{ $book->Copyright_Year }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-5">
                    <div class="text-left gap-bottom">
                        @include('partials.flash_alert')
                    </div>
                    <div class="text-right gap-bottom">
                        <button data-button="add-button" data-var-id="{{ $book->Book_ID }}" data-var-title="{{ $book->Title }}" class="btn btn-primary">Add Accession Number</button>
                    </div>
                    <table id="books-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Accession Number</th>
                                <th width="20%">Status</th>
                                <th width="20%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barcodes as $barcode)
                                <tr>
                                    <td>{{ $barcode->Accession_Number }}</td>
                                    <td>{{ ucfirst($barcode->Status) }}</td>
                                    <td class="text-center">
                                        @if(session()->has('username'))
                                            <a href="{{ route('dashboard.getDeleteRecord', ['barcodes', $barcode->Classification_ID]) }}" class="btn btn-danger btn-xs">Delete</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    <!-- <div class="loadr">
        <div class="loadr-dialog">
            <div class="text-center">
                <span class="fa fa-spinner fa-4x fa-pulse"></span>
                <h4 class="no-margin gap-top">Please Wait...</h4>
            </div>
        </div>
    </div> -->
@stop

@section('post_ref')
    <script src="/js/dashboard/manage_records/barcodes.js"></script>
@stop