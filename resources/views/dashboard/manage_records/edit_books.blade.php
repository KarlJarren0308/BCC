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
                                <select name="edition" id="edition" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    <option value="1st"{{ ($book->Edition == '1st' ? ' selected' : '') }}>1st</option>
                                    <option value="2nd"{{ ($book->Edition == '2nd' ? ' selected' : '') }}>2nd</option>
                                    <option value="3rd"{{ ($book->Edition == '3rd' ? ' selected' : '') }}>3rd</option>
                                    <option value="4th"{{ ($book->Edition == '4th' ? ' selected' : '') }}>4th</option>
                                    <option value="5th"{{ ($book->Edition == '5th' ? ' selected' : '') }}>5th</option>
                                    <option value="6th"{{ ($book->Edition == '6th' ? ' selected' : '') }}>6th</option>
                                    <option value="7th"{{ ($book->Edition == '7th' ? ' selected' : '') }}>7th</option>
                                    <option value="8th"{{ ($book->Edition == '8th' ? ' selected' : '') }}>8th</option>
                                    <option value="9th"{{ ($book->Edition == '9th' ? ' selected' : '') }}>9th</option>
                                    <option value="1th"{{ ($book->Edition == '1th' ? ' selected' : '') }}>1th</option>
                                    <option value="11th"{{ ($book->Edition == '11th' ? ' selected' : '') }}>11th</option>
                                    <option value="12th"{{ ($book->Edition == '12th' ? ' selected' : '') }}>12th</option>
                                    <option value="13th"{{ ($book->Edition == '13th' ? ' selected' : '') }}>13th</option>
                                    <option value="14th"{{ ($book->Edition == '14th' ? ' selected' : '') }}>14th</option>
                                    <option value="15th"{{ ($book->Edition == '15th' ? ' selected' : '') }}>15th</option>
                                    <option value="16th"{{ ($book->Edition == '16th' ? ' selected' : '') }}>16th</option>
                                    <option value="17th"{{ ($book->Edition == '17th' ? ' selected' : '') }}>17th</option>
                                    <option value="18th"{{ ($book->Edition == '18th' ? ' selected' : '') }}>18th</option>
                                    <option value="19th"{{ ($book->Edition == '19th' ? ' selected' : '') }}>19th</option>
                                    <option value="20th"{{ ($book->Edition == '20th' ? ' selected' : '') }}>20th</option>
                                    <option value="21th"{{ ($book->Edition == '21th' ? ' selected' : '') }}>21th</option>
                                    <option value="22th"{{ ($book->Edition == '22th' ? ' selected' : '') }}>22th</option>
                                    <option value="23th"{{ ($book->Edition == '23th' ? ' selected' : '') }}>23th</option>
                                    <option value="24th"{{ ($book->Edition == '24th' ? ' selected' : '') }}>24th</option>
                                    <option value="25th"{{ ($book->Edition == '25th' ? ' selected' : '') }}>25th</option>
                                    <option value="26th"{{ ($book->Edition == '26th' ? ' selected' : '') }}>26th</option>
                                    <option value="27th"{{ ($book->Edition == '27th' ? ' selected' : '') }}>27th</option>
                                    <option value="28th"{{ ($book->Edition == '28th' ? ' selected' : '') }}>28th</option>
                                    <option value="29th"{{ ($book->Edition == '29th' ? ' selected' : '') }}>29th</option>
                                    <option value="30th"{{ ($book->Edition == '30th' ? ' selected' : '') }}>30th</option>
                                    <option value="31th"{{ ($book->Edition == '31th' ? ' selected' : '') }}>31th</option>
                                    <option value="32th"{{ ($book->Edition == '32th' ? ' selected' : '') }}>32th</option>
                                    <option value="33th"{{ ($book->Edition == '33th' ? ' selected' : '') }}>33th</option>
                                    <option value="34th"{{ ($book->Edition == '34th' ? ' selected' : '') }}>34th</option>
                                    <option value="35th"{{ ($book->Edition == '35th' ? ' selected' : '') }}>35th</option>
                                    <option value="36th"{{ ($book->Edition == '36th' ? ' selected' : '') }}>36th</option>
                                    <option value="37th"{{ ($book->Edition == '37th' ? ' selected' : '') }}>37th</option>
                                    <option value="38th"{{ ($book->Edition == '38th' ? ' selected' : '') }}>38th</option>
                                    <option value="39th"{{ ($book->Edition == '39th' ? ' selected' : '') }}>39th</option>
                                    <option value="40th"{{ ($book->Edition == '40th' ? ' selected' : '') }}>40th</option>
                                    <option value="41th"{{ ($book->Edition == '41th' ? ' selected' : '') }}>41th</option>
                                    <option value="42th"{{ ($book->Edition == '42th' ? ' selected' : '') }}>42th</option>
                                    <option value="43th"{{ ($book->Edition == '43th' ? ' selected' : '') }}>43th</option>
                                    <option value="44th"{{ ($book->Edition == '44th' ? ' selected' : '') }}>44th</option>
                                    <option value="45th"{{ ($book->Edition == '45th' ? ' selected' : '') }}>45th</option>
                                    <option value="46th"{{ ($book->Edition == '46th' ? ' selected' : '') }}>46th</option>
                                    <option value="47th"{{ ($book->Edition == '47th' ? ' selected' : '') }}>47th</option>
                                    <option value="48th"{{ ($book->Edition == '48th' ? ' selected' : '') }}>48th</option>
                                    <option value="49th"{{ ($book->Edition == '49th' ? ' selected' : '') }}>49th</option>
                                    <option value="50th"{{ ($book->Edition == '50th' ? ' selected' : '') }}>50th</option>
                                    <option value="51th"{{ ($book->Edition == '51th' ? ' selected' : '') }}>51th</option>
                                    <option value="52th"{{ ($book->Edition == '52th' ? ' selected' : '') }}>52th</option>
                                    <option value="53th"{{ ($book->Edition == '53th' ? ' selected' : '') }}>53th</option>
                                    <option value="54th"{{ ($book->Edition == '54th' ? ' selected' : '') }}>54th</option>
                                    <option value="55th"{{ ($book->Edition == '55th' ? ' selected' : '') }}>55th</option>
                                    <option value="56th"{{ ($book->Edition == '56th' ? ' selected' : '') }}>56th</option>
                                    <option value="57th"{{ ($book->Edition == '57th' ? ' selected' : '') }}>57th</option>
                                    <option value="58th"{{ ($book->Edition == '58th' ? ' selected' : '') }}>58th</option>
                                    <option value="59th"{{ ($book->Edition == '59th' ? ' selected' : '') }}>59th</option>
                                    <option value="60th"{{ ($book->Edition == '60th' ? ' selected' : '') }}>60th</option>
                                    <option value="61th"{{ ($book->Edition == '61th' ? ' selected' : '') }}>61th</option>
                                    <option value="62th"{{ ($book->Edition == '62th' ? ' selected' : '') }}>62th</option>
                                    <option value="63th"{{ ($book->Edition == '63th' ? ' selected' : '') }}>63th</option>
                                    <option value="64th"{{ ($book->Edition == '64th' ? ' selected' : '') }}>64th</option>
                                    <option value="65th"{{ ($book->Edition == '65th' ? ' selected' : '') }}>65th</option>
                                    <option value="66th"{{ ($book->Edition == '66th' ? ' selected' : '') }}>66th</option>
                                    <option value="67th"{{ ($book->Edition == '67th' ? ' selected' : '') }}>67th</option>
                                    <option value="68th"{{ ($book->Edition == '68th' ? ' selected' : '') }}>68th</option>
                                    <option value="69th"{{ ($book->Edition == '69th' ? ' selected' : '') }}>69th</option>
                                    <option value="70th"{{ ($book->Edition == '70th' ? ' selected' : '') }}>70th</option>
                                    <option value="71th"{{ ($book->Edition == '71th' ? ' selected' : '') }}>71th</option>
                                    <option value="72th"{{ ($book->Edition == '72th' ? ' selected' : '') }}>72th</option>
                                    <option value="73th"{{ ($book->Edition == '73th' ? ' selected' : '') }}>73th</option>
                                    <option value="74th"{{ ($book->Edition == '74th' ? ' selected' : '') }}>74th</option>
                                    <option value="75th"{{ ($book->Edition == '75th' ? ' selected' : '') }}>75th</option>
                                    <option value="76th"{{ ($book->Edition == '76th' ? ' selected' : '') }}>76th</option>
                                    <option value="77th"{{ ($book->Edition == '77th' ? ' selected' : '') }}>77th</option>
                                    <option value="78th"{{ ($book->Edition == '78th' ? ' selected' : '') }}>78th</option>
                                    <option value="79th"{{ ($book->Edition == '79th' ? ' selected' : '') }}>79th</option>
                                    <option value="80th"{{ ($book->Edition == '80th' ? ' selected' : '') }}>80th</option>
                                    <option value="81th"{{ ($book->Edition == '81th' ? ' selected' : '') }}>81th</option>
                                    <option value="82th"{{ ($book->Edition == '82th' ? ' selected' : '') }}>82th</option>
                                    <option value="83th"{{ ($book->Edition == '83th' ? ' selected' : '') }}>83th</option>
                                    <option value="84th"{{ ($book->Edition == '84th' ? ' selected' : '') }}>84th</option>
                                    <option value="85th"{{ ($book->Edition == '85th' ? ' selected' : '') }}>85th</option>
                                    <option value="86th"{{ ($book->Edition == '86th' ? ' selected' : '') }}>86th</option>
                                    <option value="87th"{{ ($book->Edition == '87th' ? ' selected' : '') }}>87th</option>
                                    <option value="88th"{{ ($book->Edition == '88th' ? ' selected' : '') }}>88th</option>
                                    <option value="89th"{{ ($book->Edition == '89th' ? ' selected' : '') }}>89th</option>
                                    <option value="90th"{{ ($book->Edition == '90th' ? ' selected' : '') }}>90th</option>
                                    <option value="91th"{{ ($book->Edition == '91th' ? ' selected' : '') }}>91th</option>
                                    <option value="92th"{{ ($book->Edition == '92th' ? ' selected' : '') }}>92th</option>
                                    <option value="93th"{{ ($book->Edition == '93th' ? ' selected' : '') }}>93th</option>
                                    <option value="94th"{{ ($book->Edition == '94th' ? ' selected' : '') }}>94th</option>
                                    <option value="95th"{{ ($book->Edition == '95th' ? ' selected' : '') }}>95th</option>
                                    <option value="96th"{{ ($book->Edition == '96th' ? ' selected' : '') }}>96th</option>
                                    <option value="97th"{{ ($book->Edition == '97th' ? ' selected' : '') }}>97th</option>
                                    <option value="98th"{{ ($book->Edition == '98th' ? ' selected' : '') }}>98th</option>
                                    <option value="99th"{{ ($book->Edition == '99th' ? ' selected' : '') }}>99th</option>
                                    <option value="100th"{{ ($book->Edition == '100th' ? ' selected' : '') }}>100th</option>
                                </select>
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
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('copyrightYear', 'Copyright Year:') !!}
                                {!! Form::text('copyrightYear', $book->Copyright_Year, ['class' => 'form-control', 'maxlength' => '4', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('yearPublished', 'Year Published:') !!}
                                {!! Form::text('yearPublished', $book->Year_Published, ['class' => 'form-control', 'maxlength' => '4', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('numberOfPages', 'Number of Pages: *') !!}
                                {!! Form::number('numberOfPages', $book->Number_of_Pages, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('price', 'Book Price: *') !!}
                                {!! Form::text('price', $book->Price, ['class' => 'form-control', 'min' => '1', 'placeholder' => '', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('isbn', 'ISBN: *') !!}
                                {!! Form::text('isbn', $book->ISBN, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
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
    </script>
    <script src="/js/dashboard/manage_records/books.js"></script>
@stop