<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Binangonan Catholic College</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="{!! config('APP_URL') !!}/img/logo.png">
    <link rel="stylesheet" href="{!! config('APP_URL') !!}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{!! config('APP_URL') !!}/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="{!! config('APP_URL') !!}/css/metisMenu.min.css">
    <link rel="stylesheet" href="{!! config('APP_URL') !!}/css/sb-admin-2.css">
    <link rel="stylesheet" href="{!! config('APP_URL') !!}/css/font-awesome.min.css">
    <link rel="stylesheet" href="{!! config('APP_URL') !!}/css/stylesheet.css">
    <script src="{!! config('APP_URL') !!}/js/jquery.min.js"></script>
    <script src="{!! config('APP_URL') !!}/js/jquery.dataTables.min.js"></script>
    <script src="{!! config('APP_URL') !!}/js/dataTables.bootstrap.min.js"></script>
    <script src="{!! config('APP_URL') !!}/js/bootstrap.min.js"></script>
    <script src="{!! config('APP_URL') !!}/js/metisMenu.min.js"></script>
    <script src="{!! config('APP_URL') !!}/js/sb-admin-2.js"></script>
    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: white;
            display: table;
            font-weight: bold;
            font-family: 'Lato';
        }

        a {
            color: #f0ad4e;
            text-shadow: 1px 1px 0 rgba(25, 40, 35, 0.25);
        }

        a:focus,
        a:hover {
            color: #f0ad4e;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 96px;
        }

        .context {
            font-size: 25px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="title">Oops!</div>
            <dic class="context">Sorry for the inconvenience but this page is currently under maintenance. <a href="{{ route('cardinal.getIndex') }}">Click here</a> to go back to the home page.</dic>
        </div>
    </div>
</body>
</html>