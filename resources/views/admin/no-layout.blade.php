<?php
  URL::setRootControllerNamespace('App\Http\Controllers');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{{ Config::get('app.name') }} :: Admin</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/admin/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/vendor/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
/*
      ga('create', 'UA-08764-2', 'auto');
      ga('send', 'pageview');
*/
    </script>
  </head>
  <body class="login-page">

  @yield('content')

    <!-- js -->
    <script src="/assets/vendor/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/vendor/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
