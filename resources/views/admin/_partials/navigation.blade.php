@if (Auth::check())
<?php
    //$groups = Sentry::getUser()->getGroups();
    //$permissions = array_keys($groups[0]['permissions']);
    $permissions = [];
?>
      <ul class="nav navbar-nav">
<?php /*
        @if ( in_array('pages', $permissions) || in_array('admin', $permissions) )
        <li class="{{ Request::is('admin/pages*') ? 'active' : null }}">
            <a href="{{ URL::route('admin.pages.index') }}">Páginas</a>
        </li>
        @endif
        @if ( in_array('customer', $permissions) || in_array('admin', $permissions) )
        <li class="{{ Request::is('admin/customer*') ? 'active' : null }}">
            <a href="{{ URL::route('admin.customer.index') }}">Clientes</a>
        </li>
        @endif
        @if ( in_array('people', $permissions) || in_array('admin', $permissions) )
        <li class="{{ Request::is('admin/people*') ? 'active' : null }}">
            <a href="{{ URL::route('admin.people.index') }}">Representantes</a>
        </li>
        @endif
 */?>
        @if ( in_array('users', $permissions) || in_array('admin', $permissions) )
        <li class="{{ Request::is('admin/user*') ? 'active' : null }}">
            <a href="{{ URL::route('admin.user.index') }}">Administradores</a>
        </li>
        @endif
      </ul>
      <ul class="nav navbar-nav navbar-right">
         <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->name }} <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="{{ URL::route('admin.logout') }}">Logout </a></li>
          </ul>
        </li>
      </ul>
@endif
