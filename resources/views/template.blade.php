<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
@yield('meta_tags')
    <title>Binangonan Catholic College</title>
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
    <script src="{!! config('APP_URL') !!}/js/bootstrapValidator.min.js"></script>
    <script src="{!! config('APP_URL') !!}/js/metisMenu.min.js"></script>
    <script src="{!! config('APP_URL') !!}/js/sb-admin-2.js"></script>
    <script src="{!! config('APP_URL') !!}/js/script.js"></script>
@yield('pre_ref')
</head>
<body>
@yield('content')
@yield('post_ref')
</body>
</html>