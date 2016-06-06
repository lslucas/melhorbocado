<?php $__env->startSection('page-title'); ?>
<h1>
  Lojas
  <small><?php echo e(count($data)); ?> itens</small>
</h1>
<?php if( session('response') ): ?>
    <?php $responseType = @session('response-type') ? session('response-type') : 'alert-success'; ?>
    <div class="alert <?php echo e($responseType); ?>">
        <?php echo e(session('response')); ?>

    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<ol class="breadcrumb">
  <li><a href="<?php echo e(action('Admin\DashboardController@index')); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="<?php echo e(action('Admin\BizController@index')); ?>">Lojas</a></li>
  <li class="active">Listagem</li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

      <div class="box">
        <div class="box-body">
          <a href='/admin/biz/create' class='btn btn-success'>Incluir Novo</a>
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Loja</th>
                <th width='175px'>Cidade/UF</th>
                <th width='40px'><i class="ion-gear-b"></i></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach( $data as $row ): ?>
              <tr>
                <td>
                    <?php echo e($row->nome_fantasia); ?> - <small><a href='mailto:<?php echo e($row->email_contato); ?>' title='Contato'><?php echo e($row->contato); ?></a></small>
                    <br/><?php echo e($row->telefone_contato); ?>

                </td>
                <td>
                    <?php echo e($row->cidade.'/'.$row->estado); ?>

                    <br/><?php echo e($row->bairro); ?>

                </td>
                <td align='center'>
                    <a href="<?php echo e(URL::route('admin.biz.edit', $row['id'])); ?>" class="btn btn-success btn-xs pull-left"><i class='ion-edit'></i></a>
                    <form method="post" action="<?php echo e(route('admin.biz.destroy', $row['id'])); ?>" class='col-md-5'>
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                        <input type='hidden' name='_method' value='DELETE'/>
                        <button type="submit" class="btn btn-danger btn-xs"><i class='ion-trash-a'></i></button>
                    </form>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th>Loja</th>
                <th>Cidade/UF</th>
                <th><i class="icon-cog"></i></th>
              </tr>
            </tfoot>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('foot-script'); ?>
<script type="text/javascript">
  $(function () {
    //$("#example1").dataTable();
    $('#example1').dataTable({
      "bPaginate": true,
      "columnDefs": [ { "targets": 2, "orderable": false } ],
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>