@extends('template')

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
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Borrowed Books</h1>
                </div>
            </div>
            <table id="borrowed-books-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Call Number</th>
                        <th>Title</th>
                        <th>Edition</th>
                        <th>Copyright Year</th>
                        <th>Date & Time Borrowed</th>
                        <th>Date & Time Returned</th>
                        <th>Penalty</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowed_books as $borrowed_book)
                        <?php
                            $loanDatetime = strtotime($borrowed_book->Loan_Date_Stamp . ' ' . $borrowed_book->Loan_Time_Stamp);
                            $receiveDatetime = strtotime($borrowed_book->Receive_Date_Stamp . ' ' . $borrowed_book->Receive_Time_Stamp);
                        ?>
                        <tr>
                            <td>{{ $borrowed_book->Call_Number }}</td>
                            <td>{{ $borrowed_book->Title }}</td>
                            <td>{{ $borrowed_book->Edition }}</td>
                            <td>{{ $borrowed_book->Copyright_Year }}</td>
                            <td>{{ date('F d, Y (h:i A)', $loanDatetime) }}</td>
                            <td>
                                @if($borrowed_book->Loan_Status == 'inactive')
                                    {{ date('F d, Y (h:i A)', $receiveDatetime) }}
                                @endif
                            </td>
                            <td>
                                <!-- TODO: Compute for penalty -->
                                @if(isset($borrowed_book->Receive_ID) && $borrowed_book->Receive_ID != -1)
                                    &#8369; {{ $borrowed_book->Penalty }}
                                @else
                                    &#8369; <span class="penalty-computation-block" data-var-date-loaned="{{ $borrowed_book->Loan_Date_Stamp . ' ' . $borrowed_book->Loan_Time_Stamp }}" data-var-penalty-per-day="{{ $penaltyPerDay }}" data-var-loan-period="{{ $loanPeriod }}" data-var-holidays="{{ $holidays }}"></span>
                                @endif
                            </td>
                            <td>
                                @if($borrowed_book->Loan_Status == 'active')
                                    {{ 'On-Loan' }}
                                @else
                                    {{ 'Returned' }}
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
    <script src="/js/cardinal/borrowed_books.js"></script>
@stop