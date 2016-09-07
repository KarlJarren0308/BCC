
@extends('template')

@section('content')
    <?php
        $version = 0;
        $reservationCount = 0;
        $reservationPeriod = 0;
        $loanPeriod = 0;
        $penaltyPerDay = 0;
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
                        <li><a href="{{ route('dashboard.getManageRecords', 'holidays') }}">Manage Holidays</a></li>
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
                    <h1 class="page-header">System Settings</h1>
                </div>
            </div>
            <div class="text-left gap-bottom">
                @include('partials.flash_alert')
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <strong class="panel-title">Online Public Access Catalog Version</strong>
                        </div>
                        <div class="panel-body">
                            <p class="text-justify">Set the version of the OPAC you want to use.</p>
                            <ul class="list-group" style="overflow-y: scroll; padding-right: 5px; max-height: 175px;">
                                <li class="list-group-item">
                                    <h4 class="list-group-item-heading">v1.0</h4>
                                    <p class="list-group-item-text">Lets you see all books and sort it. Search engine is included.</p>
                                </li>
                                <li class="list-group-item">
                                    <h4 class="list-group-item-heading">v2.0</h4>
                                    <p class="list-group-item-text">Lets you see books related to the keyword(s) you type in the search engine.</p>
                                </li>
                            </ul>
                            {!! Form::open(['route' => 'dashboard.postSystemSettings']) !!}
                                @foreach($settings as $item)
                                    @if($item['name'] == 'opac_version')
                                        <?php $version = $item['value']; ?>
                                    @endif
                                @endforeach
                                <input type="hidden" name="settingName" value="{{ md5('opac_version') }}">
                                <div class="form-group">
                                    <label for="settingValue">Select a version:</label>
                                    <select class="form-control" name="settingValue" id="settingValue" required>
                                        <option value="" selected disabled>Select an option...</option>
                                        <option value="v1.0"{{ ($version == 'v1.0' ? ' selected' : '') }}>v1.0</option>
                                        <option value="v2.0"{{ ($version == 'v2.0' ? ' selected' : '') }}>v2.0</option>
                                    </select>
                                </div>
                                <div class="form-group text-right">
                                    <input type="submit" class="btn btn-danger" value="Save Changes">
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <strong class="panel-title">Online Public Access Catalog Penalty Amount</strong>
                        </div>
                        <div class="panel-body">
                            <p class="text-justify">Set how much penalty should be accounted per day.</p>
                            <p class="text-justify"><em>Note: This setting applies to student borrowers only.</em></p>
                            {!! Form::open(['route' => 'dashboard.postSystemSettings']) !!}
                                @foreach($settings as $item)
                                    @if($item['name'] == 'penalty_per_day')
                                        <?php $penaltyPerDay = $item['value']; ?>
                                    @endif
                                @endforeach
                                <input type="hidden" name="settingName" value="{{ md5('penalty_per_day') }}">
                                <div class="form-group">
                                    <label for="settingValue">Set the penalty amount:</label>
                                    {!! Form::number('settingValue', $penaltyPerDay, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group text-right">
                                    <input type="submit" class="btn btn-danger" value="Save Changes">
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <strong class="panel-title">Online Public Access Catalog Reservation Count</strong>
                        </div>
                        <div class="panel-body">
                            <p class="text-justify">Set how many books can a borrower reserve.</p>
                            <p class="text-justify"><em>Note: This setting applies to student borrowers only.</em></p>
                            {!! Form::open(['route' => 'dashboard.postSystemSettings']) !!}
                                @foreach($settings as $item)
                                    @if($item['name'] == 'reservation_count')
                                        <?php $reservationCount = $item['value']; ?>
                                    @endif
                                @endforeach
                                <input type="hidden" name="settingName" value="{{ md5('reservation_count') }}">
                                <div class="form-group">
                                    <label for="settingValue">Set the reservation count:</label>
                                    {!! Form::number('settingValue', $reservationCount, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group text-right">
                                    <input type="submit" class="btn btn-danger" value="Save Changes">
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <strong class="panel-title">Online Public Access Catalog Loan Limit</strong>
                        </div>
                        <div class="panel-body">
                            <p class="text-justify">Set how many books can a borrower borrow.</p>
                            <p class="text-justify"><em>Note: This setting applies to student borrowers only.</em></p>
                            {!! Form::open(['route' => 'dashboard.postSystemSettings']) !!}
                                @foreach($settings as $item)
                                    @if($item['name'] == 'loan_limit')
                                        <?php $loanLimit = $item['value']; ?>
                                    @endif
                                @endforeach
                                <input type="hidden" name="settingName" value="{{ md5('loan_limit') }}">
                                <div class="form-group">
                                    <label for="settingValue">Set the loan limit:</label>
                                    {!! Form::number('settingValue', $loanLimit, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group text-right">
                                    <input type="submit" class="btn btn-danger" value="Save Changes">
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <strong class="panel-title">Online Public Access Catalog Reservation Period</strong>
                        </div>
                        <div class="panel-body">
                            <p class="text-justify">Set how many days a reservation has before expiring.</p>
                            <p class="text-justify"><em>Note: This setting applies to student borrowers only.</em></p>
                            {!! Form::open(['route' => 'dashboard.postSystemSettings']) !!}
                                @foreach($settings as $item)
                                    @if($item['name'] == 'reservation_period')
                                        <?php $reservationPeriod = $item['value']; ?>
                                    @endif
                                @endforeach
                                <input type="hidden" name="settingName" value="{{ md5('reservation_period') }}">
                                <div class="form-group">
                                    <label for="settingValue">Set the reservation period:</label>
                                    {!! Form::number('settingValue', $reservationPeriod, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group text-right">
                                    <input type="submit" class="btn btn-danger" value="Save Changes">
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <strong class="panel-title">Online Public Access Catalog Loan Period</strong>
                        </div>
                        <div class="panel-body">
                            <p class="text-justify">Set how many days a loaned book has before computation of penalty starts.</p>
                            <p class="text-justify"><em>Note: This setting applies to student borrowers only.</em></p>
                            {!! Form::open(['route' => 'dashboard.postSystemSettings']) !!}
                                @foreach($settings as $item)
                                    @if($item['name'] == 'loan_period')
                                        <?php $loanPeriod = $item['value']; ?>
                                    @endif
                                @endforeach
                                <input type="hidden" name="settingName" value="{{ md5('loan_period') }}">
                                <div class="form-group">
                                    <label for="settingValue">Set the loan period:</label>
                                    {!! Form::number('settingValue', $loanPeriod, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group text-right">
                                    <input type="submit" class="btn btn-danger" value="Save Changes">
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop