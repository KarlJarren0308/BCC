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
            {!! Form::open(array('route' => ['dashboard.postAddRecord', 'books'], 'data-form' => 'add-book-form')) !!}
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
                                <select name="language" id="language" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    <option value="English">English Language</option>
                                    <option value="Filipino">Filipino Language</option>
                                    <option value="Japanese">Japanese Language</option>
                                    <option value="Chinese">Chinese Language</option>
                                    <option value="Korean">Korean Language</option>
                                </select>
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
                                {!! Form::label('callNumber', 'Call Number: *') !!}
                                {!! Form::text('callNumber', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('edition', 'Edition: *') !!}
                                <select name="edition" id="edition" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    <option value="1st">1st</option>
                                    <option value="2nd">2nd</option>
                                    <option value="3rd">3rd</option>
                                    <option value="4th">4th</option>
                                    <option value="5th">5th</option>
                                    <option value="6th">6th</option>
                                    <option value="7th">7th</option>
                                    <option value="8th">8th</option>
                                    <option value="9th">9th</option>
                                    <option value="1th">1th</option>
                                    <option value="11th">11th</option>
                                    <option value="12th">12th</option>
                                    <option value="13th">13th</option>
                                    <option value="14th">14th</option>
                                    <option value="15th">15th</option>
                                    <option value="16th">16th</option>
                                    <option value="17th">17th</option>
                                    <option value="18th">18th</option>
                                    <option value="19th">19th</option>
                                    <option value="20th">20th</option>
                                    <option value="21th">21th</option>
                                    <option value="22th">22th</option>
                                    <option value="23th">23th</option>
                                    <option value="24th">24th</option>
                                    <option value="25th">25th</option>
                                    <option value="26th">26th</option>
                                    <option value="27th">27th</option>
                                    <option value="28th">28th</option>
                                    <option value="29th">29th</option>
                                    <option value="30th">30th</option>
                                    <option value="31th">31th</option>
                                    <option value="32th">32th</option>
                                    <option value="33th">33th</option>
                                    <option value="34th">34th</option>
                                    <option value="35th">35th</option>
                                    <option value="36th">36th</option>
                                    <option value="37th">37th</option>
                                    <option value="38th">38th</option>
                                    <option value="39th">39th</option>
                                    <option value="40th">40th</option>
                                    <option value="41th">41th</option>
                                    <option value="42th">42th</option>
                                    <option value="43th">43th</option>
                                    <option value="44th">44th</option>
                                    <option value="45th">45th</option>
                                    <option value="46th">46th</option>
                                    <option value="47th">47th</option>
                                    <option value="48th">48th</option>
                                    <option value="49th">49th</option>
                                    <option value="50th">50th</option>
                                    <option value="51th">51th</option>
                                    <option value="52th">52th</option>
                                    <option value="53th">53th</option>
                                    <option value="54th">54th</option>
                                    <option value="55th">55th</option>
                                    <option value="56th">56th</option>
                                    <option value="57th">57th</option>
                                    <option value="58th">58th</option>
                                    <option value="59th">59th</option>
                                    <option value="60th">60th</option>
                                    <option value="61th">61th</option>
                                    <option value="62th">62th</option>
                                    <option value="63th">63th</option>
                                    <option value="64th">64th</option>
                                    <option value="65th">65th</option>
                                    <option value="66th">66th</option>
                                    <option value="67th">67th</option>
                                    <option value="68th">68th</option>
                                    <option value="69th">69th</option>
                                    <option value="70th">70th</option>
                                    <option value="71th">71th</option>
                                    <option value="72th">72th</option>
                                    <option value="73th">73th</option>
                                    <option value="74th">74th</option>
                                    <option value="75th">75th</option>
                                    <option value="76th">76th</option>
                                    <option value="77th">77th</option>
                                    <option value="78th">78th</option>
                                    <option value="79th">79th</option>
                                    <option value="80th">80th</option>
                                    <option value="81th">81th</option>
                                    <option value="82th">82th</option>
                                    <option value="83th">83th</option>
                                    <option value="84th">84th</option>
                                    <option value="85th">85th</option>
                                    <option value="86th">86th</option>
                                    <option value="87th">87th</option>
                                    <option value="88th">88th</option>
                                    <option value="89th">89th</option>
                                    <option value="90th">90th</option>
                                    <option value="91th">91th</option>
                                    <option value="92th">92th</option>
                                    <option value="93th">93th</option>
                                    <option value="94th">94th</option>
                                    <option value="95th">95th</option>
                                    <option value="96th">96th</option>
                                    <option value="97th">97th</option>
                                    <option value="98th">98th</option>
                                    <option value="99th">99th</option>
                                    <option value="100th">100th</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('location', 'Location: *') !!}
                                <select name="location" id="location" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    <option value="Circulation">Circulation</option>
                                    <option value="Reference">Reference</option>
                                    <option value="Filipiniana">Filipiniana</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('copyrightYear', 'Copyright Year:') !!}
                                {!! Form::text('copyrightYear', null, ['class' => 'form-control', 'maxlength' => '4', 'placeholder' => '']) !!}
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