@extends('admin.layout')
@section('page-title')
<h1>
  Dashboard
  <small>{{ date('d/m/Y H:i') }}</small>
</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/" target='_blank'><i class="fa ion-home"></i> Home</a></li>
  <li class="active">Dashboard</li>
</ol>
@stop

@section('content')

              <!-- PRODUCT LIST -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Novos produtos</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <ul class="products-list product-list-in-box">
                    <?php /*$i = 0; ?>
                    @foreach ( $data['post'] as $e )
                    <li class="item">
                      <div class="product-img">
                        <img src="/dist/img/default-50x50.gif" alt="Product Image"/>
                      </div>
                      <div class="product-info">
                        <a href="javascript::;" class="product-title">{{ $e->titulo }} <span class="label label-warning pull-right">{{ $e->autor }}</span></a>
                        <span class="product-description">
                          {{ $e->linhafina }}
                        </span>
                      </div>
                    </li><!-- /.item -->
                    <?php
                        $i++;
                    @endforeach
                     */
                    ?>
                  </ul>
                </div><!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="/admin/posts" class="uppercase">Todos os produtos</a>
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

@stop
