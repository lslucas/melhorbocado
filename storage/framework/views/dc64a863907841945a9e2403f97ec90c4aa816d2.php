<?php $__env->startSection('head-css'); ?>
<style type="text/css">
#finish {
    margin-top: 60px;
}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-title'); ?>
<h1>Carrinho</h1>
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
  <li>Carrinho de Compras</li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

      <div class="box">
        <div class="box-body">

        <div class='row' style='padding-left:20px;'>
            Voltar às compras 
            <a href='/admin/products/?area=foodservice' class='btn btn-primary'>Food Service</a>
            <a href='/admin/products/?area=supermercado' class='btn btn-primary'>Supermercado</a>
        </div>

        <br/>

        <form role="form" method="POST" action="/admin/cart">
            <?php echo csrf_field(); ?>

            <input type='hidden' name='_method' value='put'/>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width='115px'>CANAL</th>
                        <th width='115px'>FOTO</th>
                        <th>ITEM</th>
                        <th width='90px'>QUANTIDADE</th>
                        <th width='35px'><i class="ion-gear-b"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $data as $pos=>$row ): ?>
                        <?php
                        $produto = App\Products::where('id', $row['id'])->first();

                        if ( !$produto ) continue;
                        ?>
                        <tr>
                            <td><?php echo e($produto->area); ?></td>
                            <td><?php echo e($produto->id); ?></td>
                            <td><?php echo e($produto->titulo); ?><br/><?php echo e($row['sabor']); ?></td>
                            <td align='center'><input type='text' name='qtd[<?php echo e($pos); ?>]' value='<?php echo e($row['qtd']); ?>' class='form-control'/></td>
                            <td align='center' valign='top'>
                                <form method="post" action="/admin/cart/unset/<?php echo e($pos); ?>">
                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                    <input type='hidden' name='_method' value='DELETE'/>
                                    <button type="submit" class="btn btn-danger"><i class='ion-trash-a'></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

          <div class="form-group col-md-8">
            <label>Observações</label>
            <textarea class="textarea" name='observacoes' style="width: 100%; height: 120px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo e(old('observacoes')); ?></textarea>
          </div>

          <div class="form-group col-md-4" align='center'>
            <button type="submit" class="btn btn-primary" id='finish'>Concluir Pedido</button>
          </div>

        </form>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('foot-script'); ?>
<script type="text/javascript">
  $(function () {
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>