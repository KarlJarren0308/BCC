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
                        <li><a class="active" href="{{ route('dashboard.getManageRecords', 'publishers') }}">Manage Publisher Records<span class="badge pull-right">Edit</span></a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'categories') }}">Manage Category Records</a></li>
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
                    <h1 class="page-header">Edit Publisher</h1>
                </div>
            </div>
            <div class="text-left" style="margin-bottom: 25px;">
                <a href="{{ route('dashboard.getManageRecords', 'publishers') }}" class="btn btn-danger btn-xs"><span class="fa fa-arrow-left gap-right"></span>Go Back</a>
            </div>
            {!! Form::open(array('route' => ['dashboard.postEditRecord', 'publishers', $id], 'data-form' => 'edit-publisher-form')) !!}
                <div class="enclosure">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {!! Form::label('publisherName', 'Publisher\'s Name: *') !!}
                                {!! Form::text('publisherName', $publisher->Publisher_Name, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('publisherAddress', 'Address: *') !!}
                                {!! Form::text('publisherAddress', $publisher->Address, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('publisherTelephoneNumber', 'Telephone Number: *') !!}
                                {!! Form::text('publisherTelephoneNumber', $publisher->Telephone_Number, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('publisherEmailAddress', 'E-mail Address:') !!}
                                {!! Form::text('publisherEmailAddress', $publisher->Address, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right no-margin">
                        <input type="submit" class="btn btn-primary" value="Save Changes">
                    </div>
                </div>
            {!! Form::close() !!}
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
    <script src="/js/dashboard/manage_records/publishers.js"></script>
@stop