@extends('template')

@section('pre_ref')
    <style>
        html,
        body,
        .container-table {
            height: 100%;
        }

        body {
            background: #ce352c;
        }

        .container-table {
            display: table;
        }

        .vertical-center-row {
            display: table-cell;
            vertical-align: middle;
        }

        #logo {
            height: 100%;
        }
    </style>
@stop

@section('content')
    <div class="container container-table">
        <div class="row vertical-center-row">
            <div class="col-sm-6 text-center">
                <img src="/img/logo.png" id="logo">
            </div>
            <div class="col-sm-6">
                {!! Form::open(['route' => 'core.postLogin']) !!}
                    <div class="form-group">
                        {!! Form::label('username', 'Username:', array('class' => 'fg-yellow')) !!}
                        {!! Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Enter Username Here', 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('password', 'Password:', array('class' => 'fg-yellow')) !!}
                        {!! Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter Password Here', 'required' => 'required')) !!}
                    </div>
                    <div class="align-text">
                        <a class="btn btn-link fg-white" href="{{ route('core.getForgotPassword') }}">Forgot Password</a>
                        {!! Form::submit('Login', array('class' => 'btn btn-warning pull-right')) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop