@extends('admin._layouts.default')

@section('main')
    <div class='page-header'>
         <h1>Desculpe-nos, área restrita</h1>
    </div>

    {{ Notification::showAll() }}
@stop

