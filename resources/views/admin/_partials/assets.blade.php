<link href="{{ URL::asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
<link href="{{ URL::asset('assets/css/admin/main.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/js/admin/redactor/css/redactor.css') }}" rel="stylesheet">
@if (Route::getCurrentRoute()->getPath()=='/admin/login')
<link href="{{ URL::asset('assets/css/admin/login.css') }}" rel="stylesheet">
@endif
<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript">var BASE = "{{ Config::get('app.url') }}"; jQuery.migrateMute = true;</script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
<script src="{{ URL::asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/jquery.maskedinput.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/redactor/redactor.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/app.js') }}"></script>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
