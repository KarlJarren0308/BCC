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
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">My Reservations</h1>
                </div>
            </div>
            <table id="reservations-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Call Number</th>
                        <th>Title</th>
                        <th>Edition</th>
                        <th>Copyright Year</th>
                        <th>Date & Time Reserved</th>
                        <th>Expiration Date & Time</th>
                        <th>Status</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                        <?php $datetime = strtotime($reservation->Date_Stamp . ' ' . $reservation->Time_Stamp); ?>
                        <tr>
                            <td>{{ $reservation->Call_Number }}</td>
                            <td>{{ $reservation->Title }}</td>
                            <td>{{ $reservation->Edition }}</td>
                            <td>{{ $reservation->Copyright_Year }}</td>
                            <td>{{ date('F d, Y (h:i A)', $datetime) }}</td>
                            <td>{{ date('F d, Y (h:i A)', strtotime('+1 day', $datetime)) }}</td>
                            <td>
                                @if($reservation->Reservation_Status == 'active')
                                    {{ 'Reserved' }}
                                @else
                                    @if($reservation->Reference_ID != null)
                                        {{ 'On-Loan' }}
                                    @else
                                        {{ 'Cancelled' }}
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">
                                @if($reservation->Reservation_Status == 'active')
                                    <button data-button="cancel-reservation-button" data-var-id="{{ $reservation->Reservation_ID }}" class="btn btn-danger btn-xs">Cancel Reservation</a>
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
    <script src="/js/cardinal/my_reservations.js"></script>
@stop