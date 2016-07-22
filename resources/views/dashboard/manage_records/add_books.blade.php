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
                        <li><a class="active" href="{{ route('dashboard.getManageRecords', 'books') }}">Manage Book Records<span class="badge pull-right">Add</span></a></li>
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
                    <h1 class="page-header">Add Book</h1>
                </div>
            </div>
            <div class="text-left" style="margin-bottom: 25px;">
                <a href="{{ route('dashboard.getManageRecords', 'books') }}" class="btn btn-danger btn-xs"><span class="fa fa-arrow-left gap-right"></span>Go Back</a>
            </div>
            {!! Form::open(array('route' => ['dashboard.postAddRecord', 'books'])) !!}
                <div class="enclosure">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('title', 'Book Title: *') !!}
                                {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('language', 'Language: *') !!}
                                {!! Form::text('language', null, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('dateAcquired', 'Date Acquired: *') !!}
                                {!! Form::date('dateAcquired', null, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('callNumber', 'Call Number:') !!}
                                {!! Form::text('callNumber', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('edition', 'Edition: *') !!}
                                {!! Form::text('edition', null, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('location', 'Location:') !!}
                                {!! Form::text('location', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('copyrightYear', 'Copyright Year: *') !!}
                                {!! Form::text('copyrightYear', null, ['class' => 'form-control', 'maxlength' => '4', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('yearPublished', 'Year Published:') !!}
                                {!! Form::text('yearPublished', null, ['class' => 'form-control', 'maxlength' => '4', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('numberOfPages', 'Number of Pages: *') !!}
                                {!! Form::number('numberOfPages', null, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('price', 'Book Price: *') !!}
                                {!! Form::number('price', null, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('isbn', 'ISBN: *') !!}
                                {!! Form::text('isbn', null, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('publisher', 'Publisher: *') !!}
                                <select name="publisher" id="publisher" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    @foreach($publishers as $publisher)
                                        <option value="{{ $publisher->Publisher_ID }}">{{ $publisher->Publisher_Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('category', 'Category: *') !!}
                                <select name="category" id="category" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->Category_ID }}">{{ $category->Category_Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {!! Form::label('description', 'Book Description:') !!}
                                {!! Form::textarea('description', null, ['class' => 'form-control irresizable', 'placeholder' => '']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div id="authors-well" class="well">
                                <div class="text-right">
                                    <button type="button" class="btn btn-success btn-xs" data-button="add-author-button">Add Author</button>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('author-1', 'Author\'s Name: *') !!}
                                    <select name="authors[]" id="author-1" class="form-control" required>
                                        <option value="" selected disabled>Select an option...</option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->Author_ID }}">
                                                @if(strlen($author->Middle_Name) > 1)
                                                    {{ $author->First_Name . ' ' . substr($author->Middle_Name, 0, 1) . '. ' . $author->Last_Name }}
                                                @else
                                                    {{ $author->First_Name . ' ' . $author->Last_Name }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="well">
                                <div class="form-group">
                                    {!! Form::label('numberOfCopies', 'Number of Copies: *') !!}
                                    {!! Form::number('numberOfCopies', null, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                                </div>
                                <label for="">Accession Number(s) to be generated:</label>
                                <div id="generated-accession-numbers" class="list-group" style="overflow-y: scroll; max-height: 500px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right no-margin">
                        <input type="submit" class="btn btn-primary" value="Add Book">
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
    <script>
        var inputTagsCount = 1;
        var authors = {!! $authors !!};
    </script>
    <script src="/js/dashboard/manage_records/books.js"></script>
@stop