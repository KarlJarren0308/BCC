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
                        <li><a class="active" href="{{ route('dashboard.getManageRecords', 'books') }}">Manage Book Records<span class="badge pull-right">Edit</span></a></li>
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
                    <h1 class="page-header">Edit Book</h1>
                </div>
            </div>
            <div class="text-left" style="margin-bottom: 25px;">
                <a href="{{ route('dashboard.getManageRecords', 'books') }}" class="btn btn-danger btn-xs"><span class="fa fa-arrow-left gap-right"></span>Go Back</a>
            </div>
            {!! Form::open(array('route' => ['dashboard.postEditRecord', 'books', $id], 'data-form' => 'edit-book-form')) !!}
                <div class="enclosure">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('title', 'Book Title: *') !!}
                                {!! Form::text('title', $book->Title, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('language', 'Language: *') !!}
                                <select name="language" id="language" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    <option value="English"{{ ($book->Language == 'English' ? ' selected' : '') }}>English Language</option>
                                    <option value="Filipino"{{ ($book->Language == 'Filipino' ? ' selected' : '') }}>Filipino Language</option>
                                    <option value="Japanese"{{ ($book->Language == 'Japanese' ? ' selected' : '') }}>Japanese Language</option>
                                    <option value="Chinese"{{ ($book->Language == 'Chinese' ? ' selected' : '') }}>Chinese Language</option>
                                    <option value="Korean"{{ ($book->Language == 'Korean' ? ' selected' : '') }}>Korean Language</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('dateAcquired', 'Date Acquired: *') !!}
                                {!! Form::date('dateAcquired', $book->Date_Acquired, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('callNumber', 'Call Number: *') !!}
                                {!! Form::text('callNumber', $book->Call_Number, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('edition', 'Edition: *') !!}
                                {!! Form::text('edition', $book->Edition, ['class' => 'form-control', 'required' => 'required', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('location', 'Location:') !!}
                                <select name="location" id="location" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    <option value="Circulation"{{ ($book->Location == 'Circulation' ? ' selected' : '') }}>Circulation</option>
                                    <option value="Reference"{{ ($book->Location == 'Reference' ? ' selected' : '') }}>Reference</option>
                                    <option value="Filipiniana"{{ ($book->Location == 'Filipiniana' ? ' selected' : '') }}>Filipiniana</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('copyrightYear', 'Copyright Year:') !!}
                                {!! Form::text('copyrightYear', $book->Copyright_Year, ['class' => 'form-control', 'maxlength' => '4', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('yearPublished', 'Year Published:') !!}
                                {!! Form::text('yearPublished', $book->Year_Published, ['class' => 'form-control', 'maxlength' => '4', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('numberOfPages', 'Number of Pages: *') !!}
                                {!! Form::number('numberOfPages', $book->Number_of_Pages, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                {!! Form::label('acquisitionMethod', 'Acquisition Method: *') !!}
                                <div class="input-group">
                                    <label class="input-group-addon">
                                        <input type="radio" name="acquisitionMethod" value="Donated" required{{ ($book->Acquisition_Method == 'Donated' ? ' checked' : '') }}> Donated
                                    </label>
                                    <label class="input-group-addon">
                                        <input type="radio" name="acquisitionMethod" value="Purchased" required{{ ($book->Acquisition_Method == 'Purchased' ? ' checked' : '') }}> Purchased
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('price', 'Book Price: *') !!}
                                {!! Form::number('price', $book->Price, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('isbn', 'ISBN:') !!}
                                {!! Form::text('isbn', $book->ISBN, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('publisher', 'Publisher: *') !!}
                                <select name="publisher" id="publisher" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    @foreach($publishers as $publisher)
                                        <option value="{{ $publisher->Publisher_ID }}"{{ ($publisher->Publisher_ID == $book->Publisher_ID ? ' selected' : '') }}>{{ $publisher->Publisher_Name }}</option>
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
                                        <option value="{{ $category->Category_ID }}"{{ ($category->Category_ID == $book->Category_ID ? ' selected' : '') }}>{{ $category->Category_Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('description', 'Book Description:') !!}
                                {!! Form::textarea('description', $book->Description, ['class' => 'form-control irresizable', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="authors-well" class="well">
                                <div class="text-right">
                                    <button type="button" class="btn btn-success btn-xs" data-button="add-author-button">Add Author</button>
                                </div>
                                @foreach($bounds as $key => $bound)
                                    <div class="form-group">
                                        <label for="author-{{ ($key + 1) }}">Author's Name:{{ ($key == 0 ? ' *' : '') }}</label>
                                        @if($key != 0)
                                            <div class="input-group">
                                        @endif
                                        <select name="authors[]" id="author-{{ ($key + 1) }}" class="form-control"{{ ($key == 0 ? ' required' : '') }}>
                                            <option value="" selected disabled>Select an option...</option>
                                            @foreach($authors as $author)
                                                <option value="{{ $author->Author_ID }}"{{ ($bound->Author_ID == $author->Author_ID ? ' selected' : '') }}>
                                                    @if(strlen($author->Middle_Name) > 1)
                                                        {{ $author->First_Name . ' ' . substr($author->Middle_Name, 0, 1) . '. ' . $author->Last_Name }}
                                                    @else
                                                        {{ $author->First_Name . ' ' . $author->Last_Name }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($key != 0)
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-danger" data-button="remove-author-button">Remove</button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
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
    <script>
        var inputTagsCount = {!! count($bounds) !!};
        var authors = {!! $authors !!};
        var currentYear = {{ date('Y') }};
    </script>
    <script src="/js/dashboard/manage_records/books.js"></script>
@stop