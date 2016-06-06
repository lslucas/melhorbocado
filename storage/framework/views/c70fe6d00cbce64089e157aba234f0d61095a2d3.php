<?php $__env->startSection('page-title'); ?>
<h1>
<?php if( isset($data) ): ?>
  Atualizar Usuário: <?php echo e($data->name); ?>

<?php else: ?>
  Novo Usuário
<?php endif; ?>
</h1>
<?php if( session('response') ): ?>
    <?php $responseType = @session('response-type') ? session('response-type') : 'alert-warning'; ?>
    <div class="alert <?php echo e($responseType); ?>">
        <?php echo e(session('response')); ?>

    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('foot-script'); ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<ol class="breadcrumb">
  <li><a href="<?php echo e(action('Admin\DashboardController@index')); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="<?php echo e(action('Admin\UserController@index')); ?>">Usuários</a></li>
  <li class="active">Novo</li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

        <?php if(count($errors) > 0): ?>
          <div class="alert alert-danger">
            <strong>Ops!</strong> Ouve um problema.<br><br>
            <ul>
              <?php foreach($errors->all() as $error): ?>
                <li><?php echo e($error); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <div class="box box-primary">
          <div class="box-body">
            <?php if( isset($data) ): ?>
                <form role="form" method="POST" action="<?php echo e(route('admin.user.update', $data->id)); ?>">
                <input type='hidden' name='_method' value='put'/>
            <?php else: ?>
                <form role="form" method="POST" action="<?php echo e(route('admin.user.store')); ?>">
            <?php endif; ?>
              <?php echo csrf_field(); ?>


            <fieldset>
                <legend>Dados do Usuário</legend>

                <div class="form-group">
                    <label for='name'>Nome Completo</label>
                    <input type="text" name='name' class="form-control" value='<?php echo e(old('name', @$data->name)); ?>'/>
                </div>

            </fieldset>

            <fieldset>
                <legend>Dados de Acesso</legend>

                <div class="form-group">
                    <label for='email'>Email</label>
                    <input type="text" name='email' class="form-control" value='<?php echo e(old('email', @$data->email)); ?>'/>
                </div>

                <div class="form-group">
                    <label for='password'>Senha</label>
                    <input type="text" name='password' class="form-control" value='<?php echo e(old('password')); ?>'/>
                    <?php if( isset($data) ): ?>
                    <small> Deixe em branco se a senha deve permanecer a mesma</small>
                    <?php endif; ?>
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
                        <?php foreach( [''=>'Selecione']+$permissionData as $val => $name ): ?>
                        <option value='<?php echo e($val); ?>'<?php echo e(isset($data) ? ( old('role', @$data->role_id())==$val ? ' selected' : null) : null); ?>><?php echo e($name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php } ?>

                <?php if ( \Auth::user()->is('admin') ) { ?>
                <div class="form-group" id='biz'>
                  <label>Loja</label>
                  <select name='biz_id' class="combobox form-control">
                    <option value=''>Selecione</option>
                    <?php foreach( $bizs as $es ): ?>
                    <option value='<?php echo e($es->id); ?>'<?php echo e(isset($data) ? ( old('biz_id', @$data->biz_id())==$es->id ? ' selected' : null ) : null); ?>><?php echo e($es->nome_fantasia); ?></option>
                    <?php endforeach; ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>