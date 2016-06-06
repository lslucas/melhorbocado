<?php $token = isset($data) && strlen($data->token)==40 ? $data->token : str_random(40); ?>
@extends('admin.layout')
@section('head-css')
<link href="/assets/vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
@stop
@section('foot-script')
<script src="/assets/vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(function () {
        $(".textarea").wysihtml5();
      });
    </script>
@stop
@section('page-title')
<h1>
@if ( isset($data) )
  Atualizar Loja: {{ $data->titulo }}
@else
  Nova Loja
@endif
</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="{{ action('Admin\DashboardController@index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{ action('Admin\BizController@index') }}">Loja</a></li>
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
                <form role="form" method="POST" action="{{ route('admin.biz.update', $data->id) }}" enctype="multipart/form-data">
                <input type='hidden' name='_method' value='put'/>
            @else
                <form role="form" method="POST" action="{{ route('admin.biz.store') }}" enctype="multipart/form-data">
            @endif
              {!! csrf_field() !!}
             <input type='hidden' name='token' value='{{ $token }}'/>

             <fieldset>
                <legend>Empresa</legend>

                <div class="form-group">
                    <label>Canal</label>
                    <input type="text" name='canal' class="form-control" value='{{ old('canal', @$data->canal) }}'/>
                </div>

                <div class="form-group">
                    <label>Razão Social</label>
                    <input type="text" name='razao_social' class="form-control" value='{{ old('razao_social', @$data->razao_social) }}'/>
                </div>

                <div class="form-group">
                    <label>Nome Fantasia</label>
                    <input type="text" name='nome_fantasia' class="form-control" value='{{ old('nome_fantasia', @$data->nome_fantasia) }}'/>
                </div>

                <div class="form-group">
                    <label>CNPJ</label>
                    <input type="text" name='cnpj' class="form-control" value='{{ old('cnpj', @$data->cnpj) }}'/>
                </div>

             </fieldset>

             <fieldset>
                <legend>Localização</legend>

                 <div class="form-group">
                    <label>Endereço</label>
                    <input type="text" name='endereco' class="form-control" value='{{ old('endereco', @$data->endereco) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Número</label>
                    <input type="text" name='numero' class="form-control" value='{{ old('numero', @$data->numero) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Complemento</label>
                    <input type="text" name='complemento' class="form-control" value='{{ old('complemento', @$data->complemento) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Bairro</label>
                    <input type="text" name='bairro' class="form-control" value='{{ old('bairro', @$data->bairro) }}'/>
                  </div>

                  <div class="form-group">
                    <label>CEP</label>
                    <input type="text" name='cep' class="form-control" value='{{ old('cep', @$data->cep) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Cidade</label>
                    <input type="text" name='cidade' class="form-control" value='{{ old('cidade', @$data->cidade) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Estado</label>
                    <input type="text" name='estado' class="form-control" value='{{ old('estado', @$data->estado) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Corrente Elétrica</label>
                    <select name='corrente_eletrica' class="form-control">
                        <option value=''>Selecione</option>
                        <option value='110v'<?=old('corrente_eletrica', @$data->corrente_eletrica)=='110v' ? ' selected' : null?>>110v</option>
                        <option value='220v'<?=old('corrente_eletrica', @$data->corrente_eletrica)=='220v' ? ' selected' : null?>>220v</option>
                    </select>
                  </div>

             </fieldset>
             <fieldset>
                <legend>Contato</legend>

                  <div class="form-group">
                    <label>Contato</label>
                    <input type="text" name='contato' class="form-control" value='{{ old('contato', @$data->contato) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Telefone</label>
                    <input type="text" name='telefone_contato' class="form-control" value='{{ old('telefone_contato', @$data->telefone_contato) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Telefone Alternativo</label>
                    <input type="text" name='telefone_alternativo' class="form-control" value='{{ old('telefone_alternativo', @$data->telefone_alternativo) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Celular</label>
                    <input type="text" name='celular' class="form-control" value='{{ old('celular', @$data->celular) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" name='email_contato' class="form-control" value='{{ old('email_contato', @$data->email_contato) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Email Alternativo</label>
                    <input type="text" name='email_alternativo' class="form-control" value='{{ old('email_alternativo', @$data->email_alternativo) }}'/>
                  </div>

             </fieldset>
             <fieldset>
                <legend>Entrega</legend>

                  <div class="form-group">
                    <label>Início do horário de entrega</label>
                    <input type="text" name='horario_entrega_inicio' class="form-control hour" value='{{ old('horario_entrega_inicio', @$data->horario_entrega_inicio) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Fim do horário de entrega</label>
                    <input type="text" name='horario_entrega_fim' class="form-control hour" value='{{ old('horario_entrega_fim', @$data->horario_entrega_fim) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Restrições</label>
                    <textarea class="textarea" name='restricoes_entregas' style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('restricoes_entregas', @$data->restricoes_entregas) }}</textarea>
                  </div>

             </fieldset>

            <hr/>
              <div class="form-group">
                <label>Observações</label>
                <textarea class="textarea" name='observacoes' style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('observacoes', @$data->observacoes) }}</textarea>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Enviar</button>
              </div>

            </form>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
@stop
