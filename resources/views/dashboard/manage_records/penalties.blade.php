@extends('template')

@section('meta_tags')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')
    <?php
        function isHoliday($date) {
            global $holidays;

            $date = date('Y-m-d', strtotime($date));

            if($holidays) {
                foreach($holidays as $holiday) {
                    if($date == date('Y-m-d', strtotime($holiday->Date_Stamp))) {
                        return true;
                    }
                }
            }

            return false;
        }

        function isWeekend($date) {
            $date = date('l', strtotime($date));

            if($date == 'Sunday') {
                return true;
            } else if($date == 'Saturday') {
                return true;
            } else {
                return false;
            }
        }

        function nextDay($date) {
            return date('Y-m-d', strtotime('+1 day', strtotime($date)));
        }

        function computePenalty($dateLoaned) {
            $loanPeriod = 1;
            $penaltyPerDay = 10;

            $dateLoaned = date('Y-m-d H:i:s', strtotime($dateLoaned));
            $datePenaltyStarts = date('Y-m-d H:i:s', strtotime('+' . $loanPeriod . ' days', strtotime($dateLoaned)));
            $daysCount = ceil(strtotime($datePenaltyStarts) - strtotime($dateLoaned)) / 86400;
            
            for($i = 1; $i <= $daysCount; $i++) {
                $currentDate = date('Y-m-d H:i:s', strtotime('+' . $i . ' days', strtotime($dateLoaned)));

                if(isWeekend($currentDate)) {
                    $daysCount++;
                    $datePenaltyStarts = nextDay($datePenaltyStarts);
                } else {
                    if(isHoliday($currentDate)) {
                        $daysCount++;
                        $datePenaltyStarts = nextDay($datePenaltyStarts);
                    }
                }
            }

            $dateReturned = date('Y-m-d H:i:s');
            $daysCount = ceil(strtotime($dateReturned) - strtotime($datePenaltyStarts)) / 86400;

            for($j = 1; $j <= $daysCount; $j++) {
                $currentDate = date('Y-m-d H:i:s', strtotime('+' . $i . ' days', strtotime($datePenaltyStarts)));

                if(isWeekend($currentDate)) {
                    $daysCount++;
                    $datePenaltyStarts = nextDay($datePenaltyStarts);
                } else {
                    if(isHoliday($currentDate)) {
                        $daysCount++;
                        $datePenaltyStarts = nextDay($datePenaltyStarts);
                    }
                }
            }

            return (double) abs(ceil((strtotime($dateReturned) - strtotime($datePenaltyStarts)) / 86400)) * (double) $penaltyPerDay;
        }
    ?>
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
                        <li><a href="{{ route('dashboard.getManageRecords', 'librarians') }}">Manage Holidays</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'weeding') }}">Weeding Books</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'reports') }}">Generate Reports</a></li>
                        <li><a href="{{ route('dashboard.getSystemSettings') }}">System Settings</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                </div>
            </div>
            <table id="loans-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Call Number</th>
                        <th>Title</th>
                        <th>Edition</th>
                        <th>Accession Number</th>
                        <th>Borrowed By</th>
                        <th>Date Borrowed</th>
                        <th>Date Returned</th>
                        <th>Penalty</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                        @if(strlen($loan->Middle_Name) > 1)
                            <?php $name = $loan->First_Name . ' ' . substr($loan->Middle_Name, 0, 1) . '. ' . $loan->Last_Name; ?>
                        @else
                            <?php $name = $loan->First_Name . ' ' . $loan->Last_Name; ?>
                        @endif
                        <tr>
                            <td>{{ $loan->Call_Number }}</td>
                            <td>{{ $loan->Title }}</td>
                            <td>{{ $loan->Edition }}</td>
                            <td>{{ 'C' . sprintf('%04d', $loan->Accession_Number) }}</td>
                            <td>{{ $name }}</td>
                            <td>{{ date('F d, Y (h:i A)', strtotime($loan->Loan_Date_Stamp . ' ' . $loan->Loan_Time_Stamp)) }}</td>
                            <td>
                                @if(isset($loan->Receive_ID) && $loan->Receive_ID != -1)
                                    {{ date('F d, Y (h:i A)', strtotime($loan->Receive_Date_Stamp . ' ' . $loan->Receive_Time_Stamp)) }}
                                @else
                                @endif
                            </td>
                            <td>
                                @if(isset($loan->Receive_ID) && $loan->Receive_ID != -1)
                                    &#8369; {{ $loan->Penalty }}
                                @else
                                    &#8369; {{ computePenalty($loan->Loan_Date_Stamp . ' ' . $loan->Loan_Time_Stamp) }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($loan->Receive_ID))
                                    @if($loan->Settlement_Status == 'unpaid')
                                        <button class="btn btn-primary btn-xs" data-button="settle-payment">Settle</button>
                                    @else
                                        <button class="btn btn-danger btn-xs">Already Settled</button>
                                    @endif
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
    <script src="/js/dashboard/manage_records/penalties.js"></script>
@stop