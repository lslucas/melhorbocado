<?php $token = isset($data) && strlen($data->token)>1 ? $data->token : str_random(40); ?>

<?php $__env->startSection('head-css'); ?>
<link href="/assets/vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/assets/vendor/dropzone/dropzone.css">
<link href="/components/selectize/dist/css/selectize.bootstrap3.css" rel="stylesheet" type="text/css" />
<style type='text/css'>

#floating-panel {
  position: absolute;
  top: 10px;
  left: 25%;
  z-index: 5;
  background-color: #fff;
  padding: 5px;
  border: 1px solid #999;
  text-align: center;
  font-family: 'Roboto','sans-serif';
  line-height: 30px;
  padding-left: 10px;
}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('foot-script'); ?>
<script src="/assets/vendor/dropzone/dropzone.js"></script>
<script src="/assets/vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<script src="/components/selectize/dist/js/standalone/selectize.js" type="text/javascript"></script>
<script type="text/javascript">

    $(function () {

        /**
           * Converte select simples em um autcomplete combobox
           * <... class='combobox'>
           */
          if ( $().selectize ) {
              $('.combobox').selectize({
                  create: false,
                  allowEmptyOption: false
              });

              $('.combobox-multiple').selectize({
                  plugins: ['remove_button'],
                  delimiter: ',',
                  persist: false,
                  create: function(input) {
                      return {
                          value: input,
                          text: input
                      }
                  }
              });

              $('.combobox-clone').selectize({
                  create: false,
                  allowEmptyOption: false
              });

              $('.combobox-create').selectize({
                  create: true,
                  allowEmptyOption: false
              });
          }

        $(".textarea").wysihtml5();

        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone(".dropzone",
        {
            url: "/admin/fileupload",
            method: 'post',
            paramName: "fotos",
            acceptedMimeTypes: 'image/*',
            dictDefaultMessage: 'Clique ou arraste as fotos aqui!',
            dictFileTooBig: 'Essa imagem é muito grande, tamanho máximo é 3Mb.',
            dictResponseError: 'Houve um erro na tentativa de upload. Tente mais tarde',
            dictRemoveFile: 'Remover',
            dictMaxFilesExceeded: 'Limite de N arquivos excedido!',
            dictFallbackMessage: 'Seu navegador não suporta o recurso de arrastar e soltar arquivos!',
            dictFallbackText: 'Clique no campo abaixo para selecionar e enviar os arquivos:',
            dictInvalidFileType: 'Tipo de arquivo inválido!',
            maxFilesize: 3, //Mb
            addRemoveLinks: true,
            accept: function(file, done) {
                if (file.name == "file.jpg") {
                  done("Naha, you don't.");
                } else { done(); }
            },
            sending: function (file, xhr, formData) {
                formData.append('token', '<?php echo e($token); ?>');
                formData.append('area', 'realestate');
                <?php if( @$data && $data->codigo ): ?>
                formData.append('codigo', '<?php echo e($data->codigo); ?>');
                <?php endif; ?>
            },
            canceled: function (file) {
                this.removeFile(file);
            },
            success: function (file) {
                //console.log('success' , file);
            },
            init: function() {
                this.on('error', function(file, message) {
                    console.error(message);
                });

                this.on('removedfile', function(file) {
                    var filepath = file.xhr.responseText;
                    var that     = this;

                    return removeFile ( filepath, that );

                });

            }
        });

        $('.dz-remove').on('click', function(){

            filepath = $(this).data('dz-remove');
            that = myDropzone;

            removeFile ( filepath, that );

            // apaga box da imagem
            $(this).parent().remove();

            return;

        });

    });

    function removeFile ( filepath, that ) {
        $.ajax({
            url: '/admin/fileupload/remove',
            data: { file: filepath},
            method: 'DELETE',
            done: function(response) {
                that.removeFile(file);
            }
        });
    }

    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-title'); ?>
<h1>
<?php if( isset($data) ): ?>
  Atualizar Produto: <?php echo e($data->titulo); ?>

<?php else: ?>
  Novo Produto
<?php endif; ?>
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
  <li><a href="<?php echo e(action('Admin\ProductsController@index')); ?>">Artigos</a></li>
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
          <div class="box-header">
            <h3 class="box-title">Dados Gerais do Produto</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <?php if( isset($data) ): ?>
                <form role="form" method="POST" action="<?php echo e(route('admin.products.update', $data->id)); ?>" enctype="multipart/form-data">
                <input type='hidden' name='_method' value='put'/>
            <?php else: ?>
                <form id='fileupload' role="form" method="POST" action="<?php echo e(route('admin.products.store')); ?>" enctype="multipart/form-data">
            <?php endif; ?>
              <?php echo csrf_field(); ?>

              <input type='hidden' name='token' value='<?php echo e($token); ?>'/>

              <div class="form-group">
                <label>Fotos</label>
                <!--<input name="fotos" type="file" multiple />-->

                  <div class="fallback dropzone">
                    <?php if( isset($thumbs) ): ?>
                        <?php foreach( $thumbs as $thumb ): ?>
                            <?php if( isset($thumb['thumb']) && isset($thumb['original']) ): ?>
                            <div class="dz-preview dz-processing dz-image-preview dz-complete">
                                <div class="dz-image">
                                    <img data-dz-thumbnail src='<?=$thumb['thumb']?>'/>
                                </div>
                                <div class="dz-details">
                                    <div class="dz-size"><a href='<?=$thumb['original']?>' target='_blank' data-dz-size="">Ver original</a></div>
                                    <div class="dz-filename"><span data-dz-name=""><?=$thumb['name']?></span></div>
                                </div>
                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span></div>
                                <a class="dz-remove" href="javascript:undefined;" data-dz-remove="<?=$thumb['id']?>">Remover</a>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
              </div>

              <div class="form-group">
                <label>Área</label>
                <?php $area = isset($data) ? $data->area : @$_GET['area']; ?>
                <select name='area' class="form-control">
                  <option value=''>Selecione</option>
                  <option value='foodservice'<?php echo e(old('area', $area)=='foodservice' ? ' selected' : null); ?>>FOODSERVICE</option>
                  <option value='supermercado'<?php echo e(old('area', $area)=='supermercado' ? ' selected' : null); ?>>SUPERMERCADO</option>
                </select>
              </div>

              <div class="form-group">
                <label>SKU</label>
                <input type="text" name='sku' class="form-control" value='<?php echo e(old('sku', @$data->sku)); ?>'/>
              </div>

              <div class="form-group">
                <label>Título</label>
                <input type="text" name='titulo' class="form-control" value='<?php echo e(old('titulo', @$data->titulo)); ?>'/>
              </div>

              <div class="form-group">
                <label>Tipo</label>
                <select name='tipo' class="form-control">
                  <option value=''>Selecione</option>
                  <option value='SALGADO'<?php echo e(old('tipo', @$data->tipo)=='SALGADO' ? ' selected' : null); ?>>SALGADO</option>
                  <option value='DOCE'<?php echo e(old('tipo', @$data->tipo)=='DOCE' ? ' selected' : null); ?>>DOCE</option>
                </select>
              </div>

              <div class="form-group">
                <label>Característica</label>
                <select name='caracteristica' class="combobox form-control">
                  <option value=''>Selecione</option>
                  <option value='PRONTO CONGELADO'<?php echo e(old('caracteristica', @$data->caracteristica)=='PRONTO CONGELADO' ? ' selected' : null); ?>>PRONTO CONGELADO</option>
                  <option value='NOVIDADE'<?php echo e(old('caracteristica', @$data->caracteristica)=='NOVIDADE' ? ' selected' : null); ?>>NOVIDADE</option>
                  <option value='PROMOCIONADOS'<?php echo e(old('caracteristica', @$data->caracteristica)=='PROMOCIONADOS' ? ' selected' : null); ?>>PROMOCIONADOS</option>
                  <option value='RECHEADO PRONTO CONGELADO'<?php echo e(old('caracteristica', @$data->caracteristica)=='RECHEADO PRONTO CONGELADO' ? ' selected' : null); ?>>RECHEADO PRONTO CONGELADO</option>
                  <option value='RECHEADO PRONTO CONGELADO'<?php echo e(old('caracteristica', @$data->caracteristica)=='RECHEADO PRONTO CONGELADO' ? ' selected' : null); ?>>RECHEADO PRONTO CONGELADO</option>
                </select>
              </div>

              <div class="form-group">
                <label>Sabor</label>
                <input type="text" name='sabor' class="form-control" value='<?php echo e(old('sabor', @$data->sabor)); ?>'/>
              </div>

              <div class="form-group">
                <label>Peso unitário</label>
                <div class='input-group'>
                    <input type="text" name='peso_unitario' class="decimal form-control" value='<?php echo e(old('peso_unitario', @$data->peso_unitario)); ?>'/>
                    <div class="input-group-addon">g</div>
                </div>
              </div>

              <div class="form-group">
                <label>Unidades/caixa</label>
                <input type="text" name='unidades_caixa' class="numeric form-control" value='<?php echo e(old('unidades_caixa', @$data->unidades_caixa)); ?>'/>
              </div>

              <div class="form-group">
                <label>Peso Caixa</label>
                <div class='input-group'>
                    <input type="text" name='peso_caixa' class="decimal form-control" value='<?php echo e(old('peso_caixa', @$data->peso_caixa)); ?>'/>
                    <div class="input-group-addon">kg</div>
                </div>
              </div>

              <div class="form-group">
                <label>Valor</label>
                <input type="text" name='valor' class="brl form-control" value='<?php echo e(old('valor', @$data->valor)); ?>'/>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Enviar</button>
              </div>

            </form>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>