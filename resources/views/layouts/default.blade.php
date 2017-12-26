
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $title }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ url('assets/adminlte/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('assets/adminlte/css/AdminLTE.min.css') }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ url('assets/adminlte/css/_all-skins.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ url('assets/adminlte/css/blue.css') }}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{ url('assets/adminlte/css/morris.css') }}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ url('assets/adminlte/css/jquery-jvectormap-1.2.2.css') }}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ url('assets/adminlte/css/datepicker3.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ url('assets/adminlte/css/daterangepicker.css') }}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{ url('assets/adminlte/css/bootstrap3-wysihtml5.min.css') }}">

  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="{{ url('css/fullcalendar.css') }}">

  <link rel="stylesheet" href="{{ url('css/custom.css') }}">
  <link href="css/sweetalert.css" rel="stylesheet" type="text/css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- jQuery 2.2.3 -->
  <script src="{{ url('assets/adminlte/js/jquery-2.2.3.min.js') }}"></script>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>S</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Spends</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ asset('assets/images/power_tux.png') }}" class="user-image" alt="Power Tux User Image">
              <span class="hidden-xs" id="user_name"></span>
            </a>
            <ul class="dropdown-menu">
              </li>
              <li class="user-footer">
                <!-- <div class="pull-left"> -->
                <a href="{{ url('profile') }}" class="btn btn-default btn-flat">Profile</a>
                <!-- </div>
                <div class="pull-right"> -->
                <a href="#" id="logout_link" class="btn btn-default btn-flat">Log out</a>
                <!-- </div> -->
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
    <!-- <div class="user-panel">
        <div class="pull-left image">
          <img src="" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ \Auth::user()['name'] }}</p>
        </div>
      </div> -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <!-- <li class="header">MAIN NAVIGATION</li> -->
        <li {{ (Request::segment(1) == 'dashboard' ? 'class=active' : '') }}><a href="{{ url('/') }}"><i class="fa fa-dashboard">
            </i> <span>Dashboard</span></a>
        </li>
        <li id="uses_menu" {{ (Request::segment(1) == 'users' ? 'class=active' : '') }}><a href="{{ url('/users') }}"><i class="fa fa-users">
            </i> <span>Users</span></a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- Dashboard -->
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">



      @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; {{ date('Y', strtotime('now')) }} </strong> All rights reserved.</footer>


  <!-- jQuery UI 1.11.4 -->
  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
      $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.6 -->
  <script src="{{ url('assets/adminlte/js/bootstrap.min.js') }}"></script>
  <!-- Morris.js charts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
  <!-- <script src="assets/adminlte/js/morris.min.js"></script> -->
  <!-- Sparkline -->
  <script src="{{ url('assets/adminlte/js/jquery.sparkline.min.js') }}"></script>
  <!-- jvectormap -->
  <script src="{{ url('assets/adminlte/js/jquery-jvectormap-1.2.2.min.js') }}"></script>
  <script src="{{ url('assets/adminlte/js/jquery-jvectormap-world-mill-en.js') }}"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{ url('assets/adminlte/js/jquery.knob.js') }}"></script>
  <!-- daterangepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="{{ url('assets/adminlte/js/daterangepicker.js') }}"></script>
  <!-- datepicker -->
  <script src="{{ url('assets/adminlte/js/bootstrap-datepicker.js') }}"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="{{ url('assets/adminlte/js/bootstrap3-wysihtml5.all.min.js') }}"></script>
  <!-- Slimscroll -->
  <script src="{{ url('assets/adminlte/js/jquery.slimscroll.min.js') }}"></script>
  <!-- FastClick -->
  <script src="{{ url('assets/adminlte/js/fastclick.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ url('assets/adminlte/js/app.min.js') }}"></script>
  <script src="{{ url('js/full_calendar/fullcalendar.js') }}"></script>
  <script src="{{ url('js/custom.js') }}"></script>
  <script src="{{ url('js/common.js') }}"></script>
  <script src="http://malsup.github.io/jquery.blockUI.js"></script>
  <script src="{{ url('js/sweetalert.min.js') }}"></script>
  <script src="{{ url('js/block_ui.js') }}"></script>

  <!-- <script src="//code.jquery.com/jquery-1.12.3.js"></script> -->
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

</body>
</html>

<script type="text/javascript">

    var months = [
        'Jan.', 'Feb.', 'Mar.', 'Apr.', 'May.', 'Jun.',
        'Jul.', 'Aug.', 'Sep.', 'Oct.', 'Nov.', 'Dec.'
    ];

    var user = JSON.parse(getCookie('user'));
    $('#user_name').text(user.name);
    var user = {};

    $(document).ready(function() {
        $('#uses_menu').hide();

        var authToken = getCookie('authToken');
        if(authToken == undefined || authToken == '') {
            $.ajax({
                url: '/logout',
                method: 'DELETE'
            });
            window.location = '/';
        }

        user = JSON.parse(getCookie('user'));

        if(user.user_group != 1) {
            $('#uses_menu').hide();
        } else {
            $('#uses_menu').show();
        }

    });

</script>

@yield('script')