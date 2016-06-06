<?php $__env->startSection('head-css'); ?>
<style type="text/css">

</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-title'); ?>
<?php

$selectSabor = function($titulo, &$sabores) {

    $res = null;

    if ( isset($sabores[$titulo]) ) {

        $res .= "<select name='sabor' class='form-control input-sm'>";

        if ( count($sabores[$titulo])>1 )
            $res .= "<option value=''>Selecione</option>";

        foreach ( $sabores[$titulo] as $sabor ) {
            $res .= "<option value='{$sabor}'>{$sabor}</option>";
        }

        $res .= "</select>";

    }

    return $res;
}
?>
<h1>
<?php
if ( !isset($_GET['area']) )
  echo 'Produtos';
elseif ( $_GET['area']=='foodservice' )
  echo 'Food Service';
elseif ( $_GET['area']=='supermercado' )
  echo 'Supermercado';
?>
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
  <li><a href="<?php echo e(action('Admin\ProductsController@index')); ?>">Produtos</a></li>
  <li class="active">Listagem</li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

      <div class="box">
        <div class="box-body">

          <?php if( \Auth::user()->is('admin|loja') ): ?>
          <a href='/admin/products/create<?php if ( !empty($_SERVER['QUERY_STRING']) ) echo '?'.$_SERVER['QUERY_STRING']; ?>' class='btn btn-success'>Incluir Novo</a>
          <?php endif; ?>

        <?php

        foreach ( $entries as $tipo=>$items ) {

            $label_color = $tipo=='DOCE' ? 'primary' : 'warning';

            echo "<h1><span class='label label-{$label_color}'>{$tipo}</span></h1>";
            echo "<ul class='produtos nopadding'>";

            foreach ( $items as $item ) {
                echo "<li id='prod-{$item->id}'>\n";
                echo "<img src='http://placehold.it/170x120'/>\n";
                echo "<h4>{$item->titulo}</h4>";
                echo number_format($item->peso_unitario) . "g por unidade";
                echo "<br/>{$item->unidades_caixa} unidades por caixa";
                echo "<br/><div class='row action-bar'>";
                echo "<div class='col-sm-3 nopadding field-qtd padding5px'>";
                echo "<input type='hidden' name='id' value='{$item->id}'/>";
                echo "<input type='text' name='qtd' class='numeric input-qtd input-sm form-control' value='10'/>";
                echo "</div>";
                echo "<div class='col-sm-6 nopadding padding5px'>";
                echo $selectSabor($item->titulo, $sabores);
                echo "</div>";
                echo "<div class='col-sm-2 nopadding'>";
                echo "<button class='btn-circle add-cart' title='Adicionar ao Carrinho'/><i class='ion ion-ios-cart'></i></button>";
                echo "</div>";
                echo "</div>";

                if ( \Auth::user()->is('admin|loja') ) {

                    echo "<div class='action-bar row'><div class='col-sm-12'>";
                    echo "<div class='col-md-6'><a href='". route('admin.products.edit', $item->id) ."' class='btn btn-success' title='Editar'><i class='ion-edit'></i></a></div>";
                    echo "<form method='post' action='". route('admin.products.destroy', $item->id) ."' class='col-md-6'>
                        <input type='hidden' name='_token' value='". csrf_token() ."'>
                        <input type='hidden' name='_method' value='DELETE'/>
                        <button type='submit' class='btn btn-danger' title='Apagar'><i class='ion-trash-a'></i></button>
                    </form>";
                    echo "</div></div>";

                }

                echo "</span>";
                echo "</li>\n";
            }

            echo "</ul><br clear='all'/><br/>";
        }

        ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('foot-script'); ?>
<script type="text/javascript">
  $(function () {
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>