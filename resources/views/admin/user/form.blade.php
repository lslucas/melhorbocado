@extends('admin.layout')
@section('page-title')
<h1>
@if ( isset($data) )
  Atualizar Usuário: {{ $data->name }}
@else
  Novo Usuário
@endif
</h1>
@if ( session('response') )
    <?php $responseType = @session('response-type') ? session('response-type') : 'alert-warning'; ?>
    <div class="alert {{ $responseType }}">
        {{ session('response') }}
    </div>
@endif
@stop
@section('foot-script')
<script type='text/javascript'>
    $(function() {

        bizAction($('select[name="role"]').val());

        $('select[name="role"]').on('change', function() {
            return bizAction($(this).val());
        });

    });

    function bizAction(role)
    {
        $('select[name="biz_id"]').attr('disabled', true);
        $('#biz').hide();

        if ( role==2 || role==3 ) {
            $('select[name="biz_id"]').removeAttr('disabled');
            $('#biz').show();
        }
    }
</script>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="{{ action('Admin\DashboardController@index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{ action('Admin\UserController@index') }}">Usuários</a></li>
  <li class="active">Novo</li>
</ol>
@stop
@section('content')

        @if (count($errors) > 0)
          <div class="alert alert-danger">
            <strong>Ops!</strong> Ouve um problema.<br><br>
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="box box-primary">
          <div class="box-body">
            @if ( isset($data) )
                <form role="form" method="POST" action="{{ route('admin.user.update', $data->id) }}">
                <input type='hidden' name='_method' value='put'/>
            @else
                <form role="form" method="POST" action="{{ route('admin.user.store') }}">
            @endif
              {!! csrf_field() !!}

            <fieldset>
                <legend>Dados do Usuário</legend>

                <div class="form-group">
                    <label for='name'>Nome Completo</label>
                    <input type="text" name='name' class="form-control" value='{{ old('name', @$data->name) }}'/>
                </div>

            </fieldset>

            <fieldset>
                <legend>Dados de Acesso</legend>

                <div class="form-group">
                    <label for='email'>Email</label>
                    <input type="text" name='email' class="form-control" value='{{ old('email', @$data->email) }}'/>
                </div>

                <div class="form-group">
                    <label for='password'>Senha</label>
                    <input type="text" name='password' class="form-control" value='{{ old('password') }}'/>
                    @if ( isset($data) )
                    <small> Deixe em branco se a senha deve permanecer a mesma</small>
                    @endif
                </div>

                <?php
                    if ( \Auth::user()->is('admin|bizs') ) {
                        $isAdmin = \Auth::user()->is('admin');
                ?>
                <div class="form-group">
                    <label for='permission'>Tipo de Acesso</label>
                    <?php
                        $permissionData = array(2=>'Lojista (Administrador)', 3=>'Funcionário de Loja');
                        if ( $user->is('admin') )
                            $permissionData += array(1=>'Administrador');

                        asort($permissionData);
                    ?>
                    <select name='role' class='form-control'<?php $isAdmin ? null : ' readonly="true"'; ?>>
                        @foreach ( [''=>'Selecione']+$permissionData as $val => $name )
                        <option value='{{ $val }}'{{ isset($data) ? ( old('role', @$data->role_id())==$val ? ' selected' : null) : null }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <?php } ?>

                <?php if ( \Auth::user()->is('admin') ) { ?>
                <div class="form-group" id='biz'>
                  <label>Loja</label>
                  <select name='biz_id' class="combobox form-control">
                    <option value=''>Selecione</option>
                    @foreach ( $bizs as $es )
                    <option value='{{ $es->id }}'{{ isset($data) ? ( old('biz_id', @$data->biz_id())==$es->id ? ' selected' : null ) : null }}>{{ $es->nome_fantasia }}</option>
                    @endforeach
                  </select>
                </div>
                <?php } ?>

            </fieldset>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Enviar</button>
              </div>

            </form>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
@stop
