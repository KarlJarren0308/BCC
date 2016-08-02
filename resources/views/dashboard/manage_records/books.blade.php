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
                        <li><a href="{{ route('dashboard.getManageRecords', 'books') }}">Manage Book Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'authors') }}">Manage Author Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'publishers') }}">Manage Publisher Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'categories') }}">Manage Categories Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'borrowers') }}">Manage Borrowers</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'librarians') }}">Manage Librarians</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'holidays') }}">Manage Holidays</a></li>
                        <li><a href="{{ route('dashboard.getSystemSettings') }}">System Settings</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Manage Book Records</h1>
                </div>
            </div>
            <div class="text-left gap-bottom">
                @include('partials.flash_alert')
            </div>
            <div class="text-right gap-bottom">
                <a href="{{ route('dashboard.getAddRecord', 'books') }}" class="btn btn-primary">Add Book</a>
            </div>
            <table id="books-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Call Number</th>
                        <th>Title</th>
                        <th>Edition</th>
                        <th>Copyright Year</th>
                        <th>Author(s)</th>
                        <th width="30%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td>{{ $book->Call_Number }}</td>
                            <td>{{ $book->Title }}</td>
                            <td>{{ $book->Edition }}</td>
                            <td>{{ $book->Copyright_Year }}</td>
                            <td>
                                <?php $isFirst = true; ?>
                                @foreach($bounds as $bound)
                                    @if($bound->Book_ID == $book->Book_ID)
                                        @if($isFirst)
                                            <?php $isFirst = false; ?>
                                        @else
                                            <br>
                                        @endif

                                        @if(strlen($bound->Middle_Name) > 1)
                                            {{ $bound->First_Name . ' ' . substr($bound->Middle_Name, 0, 1) . '. ' . $bound->Last_Name }}
                                        @else
                                            {{ $bound->First_Name . ' ' . $bound->Last_Name }}
                                        @endif
                                    @endif
                                @endforeach
                            </td>
                            <td class="text-center">
                                @if(session()->has('username'))
                                    <button data-button="view-button" data-var-id="{{ $book->Book_ID }}" class="btn btn-primary btn-xs">View Info</button>
                                    <a href="{{ route('dashboard.getBarcodes', $book->Book_ID) }}" class="btn btn-info btn-xs">Accession Numbers</a>
                                    <a href="{{ route('dashboard.getEditRecord', ['books', $book->Book_ID]) }}" class="btn btn-success btn-xs">Edit</a>
                                    <a href="{{ route('dashboard.getDeleteRecord', ['books', $book->Book_ID]) }}" class="btn btn-danger btn-xs">Delete</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
    <script src="/js/dashboard/manage_records/books.js"></script>
@stop