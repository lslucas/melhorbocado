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
  Atualizar Categoria: {{ $data->titulo }}
@else
  Nova Categoria
@endif
</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="{{ action('Admin\DashboardController@index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{ action('Admin\CategoryController@index') }}">Categoria</a></li>
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
                <form role="form" method="POST" action="{{ route('admin.category.update', $data->id) }}" enctype="multipart/form-data">
                <input type='hidden' name='_method' value='put'/>
            @else
                <form role="form" method="POST" action="{{ route('admin.category.store') }}" enctype="multipart/form-data">
            @endif
              {!! csrf_field() !!}
             <input type='hidden' name='token' value='{{ $token }}'/>

             <fieldset>
                <legend>Dados da Empresa</legend>

                 <div class="form-group">
                    <label for="file" class="control-label pull-left col-lg-2">
                        Logo
                    </label>
                    <input type='file' name='logo' class='form-control'/>
                    @if ( isset($data->logo) && strlen($data->logo) )
                        <?php
                            $logo_id = 0;
                            $image = null;
                            if ( isset($data->logo) && !empty($data->logo) ) {
                                $image = $data->logo;
                                $image = '/storage/'.$image;
                                $logo_id = 0;
                            }
                        ?>
                        <a href="javascript:;" data-source='#photoBox' data-method='get' data-url="/admin/ajax/drop/{{ $logo_id }}" class='btn-remove' title='Remover'>
                            <div class='fa fa-times pull-right'></div>
                        </a>
                        <a href="<?php echo '/storage/'.$data->logo; ?>" target='_blank' title='Abrir imagem em nova aba'><img src="<?php echo $image; ?>" style='max-width:261px; max-height:100px;' alt=""></a>
                    @endif
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

                  <div class="form-group">
                    <label>CRECI</label>
                    <input type="text" name='creci' class="form-control" value='{{ old('creci', @$data->creci) }}'/>
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
                    <label>Google Maps (url)</label>
                    <input type="text" name='gmaps' class="form-control" value='{{ old('gmaps', @$data->gmaps) }}'/>
                  </div>

             </fieldset>
             <fieldset>
                <legend>Dados do Contato</legend>

                  <div class="form-group">
                    <label>Contato</label>
                    <input type="text" name='contato' class="form-control" value='{{ old('contato', @$data->contato) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Telefone</label>
                    <input type="text" name='telefone_contato' class="form-control" value='{{ old('telefone_contato', @$data->telefone_contato) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Telefone Aluguel</label>
                    <input type="text" name='telefone_aluguel' class="form-control" value='{{ old('telefone_aluguel', @$data->telefone_aluguel) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Telefone Temporada</label>
                    <input type="text" name='telefone_temporada' class="form-control" value='{{ old('telefone_temporada', @$data->telefone_aluguel) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Telefone Internacional</label>
                    <input type="text" name='telefone_internacional' class="form-control" value='{{ old('telefone_internacional', @$data->telefone_internacional) }}'/>
                  </div>

                  <div class="form-group">
                    <label>Telefone Alt.</label>
                    <input type="text" name='telefone_alternativo' class="form-control" value='{{ old('telefone_alternativo', @$data->telefone_alternativo) }}'/>
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
