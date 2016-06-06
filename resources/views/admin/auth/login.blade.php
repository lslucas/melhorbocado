@extends('admin.no-layout')

@section('content')
    <div class="login-box">
      <div class="login-logo">
        <a href="{{ action('Admin\DashboardController@index') }}">{{ \Config::get('app.name') }}</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Faça login para começar</p>

        @if (count($errors) > 0)
          <div class="alert alert-danger">
            <strong>Ops!</strong> Ouve um problema na tentativa de login.<br><br>
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name='email' value='{{ old('email') }}'/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name='password' placeholder="Senha"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name='remember'> Lembrar-me
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Logar</button>
            </div><!-- /.col -->
          </div>
        </form>

        <a href="{{ url('/password/email') }}">Esqueci a senha</a><br>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

@stop
