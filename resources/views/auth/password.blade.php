@extends('admin.no-layout')

@section('content')
    <div class="login-box">
      <div class="login-logo">
        <a href="{{ action('Admin\DashboardController@index') }}">{{ \Config::get('app.name') }}</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Esqueci a senha</p>

        @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
        @endif

        @if (count($errors) > 0)
          <div class="alert alert-danger">
            <strong>Ops!</strong> Houve um problema.<br><br>
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name='email' value='{{ old('email') }}'/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="row">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Envie-me o link para recuperar a senha</button>
          </div>
        </form>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

@stop
