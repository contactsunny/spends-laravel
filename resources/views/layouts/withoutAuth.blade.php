<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link href="css/bootstrap-social.css" rel="stylesheet" type="text/css">
    <link href="css/sweetalert.css" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <script src="{{ url('assets/adminlte/js/jquery-2.2.3.min.js') }}"></script>

    <script src="https://use.fontawesome.com/1de98c8980.js"></script>
    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="{{ url('js/custom.js') }}"></script>
    <script src="{{ url('js/sweetalert.min.js') }}"></script>
    <script src="{{ url('js/block_ui.js') }}"></script>

    <link rel="stylesheet" href="assets/adminlte/css/AdminLTE.min.css">
    <link rel="stylesheet" href="assets/adminlte/css/blue.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <title>Spends</title>

</head>
<body class="hold-transition login-page">

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}">Spends</a>
        </div>

        @yield('content')

        <br>
        <div class="login-logo"></div>

    </div>

</body>
</html>

@yield('script')