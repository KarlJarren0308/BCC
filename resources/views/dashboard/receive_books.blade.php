@extends('template')

@section('meta_tags')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')
    <?php
        function isHoliday($date, $holidays) {
            $date = date('Y-m-d', strtotime($date));

            if(count($holidays) > 0) {
                foreach($holidays as $holiday) {
                    if($holiday->Type == 'Class Suspension') {
                        if($date == date('Y-m-d', strtotime($holiday->Date_Stamp))) {
                            return true;
                        }
                    } else if($holiday->Type == 'Special') {
                        if(date('m-d', strtotime($date)) == date('m-d', strtotime($holiday->Type))) {
                            return true;
                        }
                    } else if($holiday->Type == 'Regular') {
                        if(date('m-d', strtotime($date)) == date('m-d', strtotime($holiday->Type))) {
                            return true;
                        }
                    }
                }

                return false;
            } else {
                return false;
            }
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
                    <h1 class="page-header">Receive Books</h1>
                </div>
            </div>
            <div class="text-left gap-bottom">
                @include('partials.flash_alert')
            </div>
            <table id="books-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Accession Number</th>
                        <th>Title</th>
                        <th>Edition</th>
                        <th>Date Borrowed</th>
                        <th>Borrowed By</th>
                        <th>Author(s)</th>
                        <th>Penalty</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                        <tr>
                            <td>{{ 'C' . sprintf('%04d', $loan->Accession_Number) }}</td>
                            <td>{{ $loan->Title }}</td>
                            <td>{{ $loan->Edition }}</td>
                            <td>{{ date('F d, Y', strtotime($loan->Loan_Date_Stamp)) }}</td>
                            <td>
                                @if(strlen($loan->Middle_Name) > 1)
                                    {{ $loan->First_Name . ' ' . substr($loan->Middle_Name, 0, 1) . '. ' . $loan->Last_Name }}
                                @else
                                    {{ $loan->First_Name . ' ' . $loan->Last_Name }}
                                @endif
                            </td>
                            <td>
                                <?php $isFirst = true; ?>
                                @foreach($bounds as $bound)
                                    @if($bound->Book_ID == $loan->Book_ID)
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
                            <?php
                                // Penalty Computation
                                $dateLoaned = $loan->Loan_Date_Stamp . ' ' . $loan->Loan_Time_Stamp;
                                $dayEnd = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($dateLoaned)));
                                $dayStart = strtotime($dateLoaned);
                                $graceDays = ceil((strtotime($dayEnd) - $dayStart) / 86400);
                                $i = 1;

                                while($i <= $graceDays) {
                                    $markedDate = date('Y-m-d H:i:s', strtotime('+' . $i . ' days', strtotime($dateLoaned)));

                                    if(isWeekend($markedDate)) {
                                        $graceDays++;
                                        $dayEnd = nextDay($dayEnd);
                                    } else {
                                        if(isHoliday($markedDate, $holidays)) {
                                            $graceDays++;
                                            $dayEnd = nextDay($dayEnd);
                                        }
                                    }

                                    $i++;
                                }

                                $newDayEnd = $dayEnd;
                                $newGraceDays = ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($newDayEnd)) / 86400);
                                $j = 1;

                                while($j <= $newGraceDays) {
                                    $markedDate = date('Y-m-d H:i:s', strtotime('+' . $j . ' days', strtotime($newDayEnd)));

                                    if(isWeekend($markedDate)) {
                                        $newGraceDays++;
                                        $newDayEnd = nextDay($newDayEnd);
                                    } else {
                                        if(isHoliday($markedDate, $holidays)) {
                                            $newGraceDays++;
                                            $newDayEnd = nextDay($newDayEnd);
                                        }
                                    }

                                    $j++;
                                }

                                $totalPenalty = floor((strtotime(date('Y-m-d H:i:s')) - strtotime($newDayEnd)) / 86400) * 5;
                            ?>
                            <td>
                                @if($loan->Loan_Status == 'active')
                                    &#8369; {{ ($totalPenalty > 0 ? $totalPenalty : 0) }}.00
                                @else
                                    @foreach($receives as $receive)
                                        @if($loan->Loan_ID == $receive->Reference_ID)
                                            &#8369; {{ $receive->Penalty }}.00
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">
                                @if(session()->has('username'))
                                    @if($loan->Loan_Status == 'active')
                                        <button data-button="receive-book-button" data-var-id="{{ $loan->Loan_ID }}" data-var-penalty="{{ ($totalPenalty > 0 ? $totalPenalty : 0) }}" class="btn btn-primary btn-xs">Receive Book</button>
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
    <script src="/js/dashboard/receive_books.js"></script>
@stop