@extends('template')

@section('meta_tags')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('pre_ref')
    <style>
        .presentation-header {
            position: absolute;
            text-align: center;
            top: 50%;
            left: calc(250px + 50%);
            margin-top: -60px;
            margin-left: -425px;
            height: 60px;
            width: 600px;
        }

        .presentation-title {
            font-size: 3em;
            margin-bottom: 15px;
        }

        .presentation-body {
            padding-top: 150px;
        }

        .presentation-loader {
            display: none;
            text-align: center;
        }

        @media (max-width: 768px) {
            .presentation-header {
                display: inline-block;
                position: relative;
                text-align: left;
                top: 25px;
                left: 0;
                margin-top: 0;
                margin-left: 0;
                width: 100%;
            }

            .presentation-body {
                padding-top: 50px;
            }
        }
    </style>
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
            <div class="presentation">
                <div class="presentation-header">
                    <div class="presentation-title">Online Public Access Catalog</div>
                    <form class="form-inline" data-form="search-form">
                        <div class="form-group">
                            <input type="text" class="form-control" name="keyword" placeholder="Search for..." required autofocus>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-danger" value="Search">
                        </div>
                    </form>
                </div>
                <div class="presentation-body">
                    <div class="presentation-loader">
                        <span class="fa fa-spinner fa-pulse fa-4x" style="color: #d9534f;"></span>
                        <h3 style="color: #d9534f; margin: 10px 0;">Searching...</h3>
                    </div>
                    <div id="presentation-content"></div>
                </div>
            </div>
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
    <script src="/js/cardinal/opac_v2_0.js"></script>
@stop