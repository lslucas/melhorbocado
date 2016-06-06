@extends('admin.layout')
@section('page-title')
<h1>
  Usuários
  <small>{{ count($data) }} itens</small>
</h1>
@if ( session('response') )
    <?php $responseType = @session('response-type') ? session('response-type') : 'alert-success'; ?>
    <div class="alert {{ $responseType }}">
        {{ session('response') }}
    </div>
@endif
@stop
@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="{{ action('Admin\DashboardController@index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{ action('Admin\UserController@index') }}">Usuários</a></li>
  <li class="active">Listagem</li>
</ol>
@stop
@section('content')

      <div class="box">
        <div class="box-body">
          <a href='/admin/user/create' class='btn btn-success'>Incluir Novo</a>
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Usuário</th>
                <th>Email</th>
                <th width='115px'>Loja</th>
                <th width='115px'>Tipo de Acesso</th>
                <th width='45px'><i class="ion-gear-b"></i></th>
              </tr>
            </thead>
            <tbody>
            @foreach ( $data as $row )
              <tr>
                <td>{{ $row->name }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->loja() ?: '--'}}</td>
                <td>{{ $row->role() }}</td>
                <td align='center'>
                    <a href="{{ URL::route('admin.user.edit', $row['id']) }}" class="btn btn-success btn-xs pull-left"><i class='ion-edit'></i></a>
                    <form method="post" action="{{ route('admin.user.destroy', $row['id']) }}" class='col-md-5'>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type='hidden' name='_method' value='DELETE'/>
                        <button type="submit" class="btn btn-danger btn-xs"><i class='ion-trash-a'></i></button>
                    </form>
                </td>
              </tr>
            @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>Login</th>
                <th>Email</th>
                <th>Loja</th>
                <th>Tipo de Acesso</th>
                <th><i class="ion-gear-b"></i></th>
              </tr>
            </tfoot>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

@stop
@section('foot-script')
<script type="text/javascript">
  $(function () {
    //$("#example1").dataTable();
    $('#example1').dataTable({
      "bPaginate": true,
      "columnDefs": [ { "targets": 3, "orderable": false } ],
      "bLengthChange": false,
      "bFilter": false,
      "bSort": true,
      "bInfo": true,
      "bAutoWidth": false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/Portuguese-Brasil.json"
      }
    });
  });
</script>
@stop
