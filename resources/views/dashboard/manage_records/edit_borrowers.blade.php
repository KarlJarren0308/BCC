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
                        <li><a href="{{ route('dashboard.getManageRecords', 'publishers') }}">Manage Publisher Records</a></li>
                        <li><a href="{{ route('dashboard.getManageRecords', 'categories') }}">Manage Categories Records</a></li>
                        <li><a class="active" href="{{ route('dashboard.getManageRecords', 'borrowers') }}">Manage Borrowers<span class="badge pull-right">Edit</span></a></li>
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
                    <h1 class="page-header">Edit Borrower</h1>
                </div>
            </div>
            <div class="text-left" style="margin-bottom: 25px;">
                <a href="{{ route('dashboard.getManageRecords', 'borrowers') }}" class="btn btn-danger btn-xs"><span class="fa fa-arrow-left gap-right"></span>Go Back</a>
            </div>
            {!! Form::open(array('route' => ['dashboard.postEditRecord', 'borrowers', $borrower->Borrower_ID])) !!}
                <div class="enclosure">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('borrowerID', 'Borrower\'s ID:') !!}
                                {!! Form::text('borrowerID', $borrower->Username, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('birthDate', 'Birth Date:') !!}
                                {!! Form::date('birthDate', $borrower->Birth_Date, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('firstName', 'First Name:') !!}
                                {!! Form::text('firstName', $borrower->First_Name, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('middleName', 'Middle Name:') !!}
                                {!! Form::text('middleName', $borrower->Middle_Name, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('lastName', 'Last Name:') !!}
                                {!! Form::text('lastName', $borrower->Last_Name, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('address', 'Address:') !!}
                                {!! Form::text('address', $borrower->Address, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('telephoneNumber', 'Telephone Number:') !!}
                                {!! Form::text('telephoneNumber', $borrower->Telephone_Number, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('cellphoneNumber', 'Cellphone Number:') !!}
                                {!! Form::text('cellphoneNumber', $borrower->Cellphone_Number, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('gender', 'Gender:') !!}
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    <option value="Male"{{ ($borrower->Gender == 'Male' ? ' selected' : '') }}>Male</option>
                                    <option value="Female"{{ ($borrower->Gender == 'Female' ? ' selected' : '') }}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('type', 'Type:') !!}
                                <select name="type" id="type" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    <option value="Student"{{ ($borrower->Type == 'Student' ? ' selected' : '') }}>Student</option>
                                    <option value="Faculty"{{ ($borrower->Type == 'Faculty' ? ' selected' : '') }}>Faculty</option>
                                </select>
                            </div>
                        </div>
                        <fieldset data-for="Student"{{ ($borrower->Type == 'Student' ? '' : ' disabled') }}>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('yearLevel', 'Year Level:') !!}
                                    <select name="yearLevel" id="yearLevel" class="form-control" required>
                                        <option value="" selected disabled>Select an option...</option>
                                        <option value="1st"{{ ($borrower->Year_Level == '1st' ? ' selected' : '') }}>1st Year</option>
                                        <option value="2nd"{{ ($borrower->Year_Level == '2nd' ? ' selected' : '') }}>2nd Year</option>
                                        <option value="3rd"{{ ($borrower->Year_Level == '3rd' ? ' selected' : '') }}>3rd Year</option>
                                        <option value="4th"{{ ($borrower->Year_Level == '4th' ? ' selected' : '') }}>4th Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('course', 'Course:') !!}
                                    <select name="course" id="course" class="form-control" required>
                                        <option value="" selected disabled>Select an option...</option>
                                        <option value="Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
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
    <script src="/js/dashboard/manage_records/borrowers.js"></script>
@stop