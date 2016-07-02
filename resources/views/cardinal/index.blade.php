@extends('template')

@section('pre_ref')
    <style>
        html,
        body,
        .container-table {
            height: 100%;
        }

        body {
            background: #dd1e2f;
        }

        .container-table {
            display: table;
        }

        .vertical-center-row {
            display: table-cell;
            position: relative;
            vertical-align: middle;
        }

        .logo-container {
            height: 350px;
        }

        #logo {
            display: inline-block;
            vertical-align: middle;
        }
    </style>
@stop

@section('content')
    <div class="container container-table">
        <div class="row vertical-center-row">
            <div class="col-md-6 text-center logo-container">
                <img src="/img/logo.png" id="logo">
            </div>
            <div class="col-md-6" style="padding: 0 75px;">
                <h1 class="fg-white">Login</h1>
                <hr>
                {!! Form::open(['route' => 'cardinal.postLogin']) !!}
                    <div class="form-group">
                        {!! Form::label('username', 'Username:', array('class' => 'fg-yellow')) !!}
                        {!! Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Enter Username Here', 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('password', 'Password:', array('class' => 'fg-yellow')) !!}
                        {!! Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter Password Here', 'required' => 'required')) !!}
                    </div>
                    <div class="align-text">
                        <a class="btn btn-link fg-white" href="{{ route('cardinal.getForgotPassword') }}">Forgot Password</a>
                        {!! Form::submit('Login', array('class' => 'btn btn-warning pull-right')) !!}
                    </div>
                {!! Form::close() !!}
                @include('partials.flash_alert')
            </div>
        </div>
    </div>
@stop