<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use App\User;
use Response;
use URL;

class ApiController extends Controller {

	public function index()
    {
        return 'apis';
    }

    public function base($baseName)
    {
        if ( $baseName == 'users' ) {
            return $this->getUsers();
        }

        if ( $baseName == 'products' ) {
            return $this->getProducts();
        }

        return false;
    }

	public function getProducts()
	{
		if ( !\Auth::check() )
			return 'Usuário não logado!';

        $User = new \App\User();

        $q = isset($_GET['q']) && $_GET['q'] ? trim($_GET['q']) : false;
        $autor = isset($_GET['autor']) && $_GET['autor'] ? [trim($_GET['autor'])] : false;
		$skip = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$take = isset($_GET['length']) ? intval($_GET['length']) : 10;
/*
		if ( \Auth::check() && (\Auth::user()->is('colaborador') || \Auth::user()->is('redator') ) ) {
                if ( !$autor ) {
                        if ( count(\Auth::user()->imobiliarias())==1 )
                                $autor = [$User->estate_id()];
                        else
                                $estate_id = array_keys(\Auth::user()->imobiliarias(true));
                }
 }*/

        $input = \Input::get();
        $input['autor'] = $autor;
        $data = Products::ofQuery($input);

        // Lista apenas os items cuja imobiliária seja a mesma do usuário logado
		$columns = ['autor', 'titulo', 'area', 'subcategoria', 'opt'];
        $newData = [];

		$recordsFiltered = $recordsTotal = $data->count();

        foreach ( $data->skip($skip)->take($take)->get() as $item ) {
    		if ( !\Auth::user()->is('admin') ) {
				if ( !in_array($item->autor, $autor) )
					continue;
			}

			$items = [];

			$items += $item->toArray();
			$items['autor'] = $item->autor;
			$items['titulo'] = $item->titulo;
			$items['area'] = $item->area();
			$items['subcategoria'] = $item->subcategoria();
			$items['opt'] = "<a href='".URL::route('admin.products.edit', $items['id'])."' class='btn btn-success btn-xs pull-left'><i class='ion-edit'></i></a>";

    		if ( \Auth::user()->is('admin|colaborador|redator') )
				$items['opt'] .= "
	                    <form method='post' action='". URL::route('admin.products.destroy', $items['id'])."' class='col-md-5'>
	                        <input type='hidden' name='_token' value='{{ csrf_token() }}'>
	                        <input type='hidden' name='_method' value='DELETE'/>
	                        <button type='submit' class='btn btn-danger btn-xs'><i class='ion-trash-a'></i></button>
						</form>";
			$itemsFixed = [];

			foreach($items as $key => $val) {
			    if(in_array($key, $columns))
			        $itemsFixed[$key] = $val;
			}

            $newData[] = array_values($itemsFixed);
        }

		$resp = [ "draw" => isset ( $_GET['draw'] ) ? intval( $_GET['draw'] ) : 0, 'recordsFiltered' => $recordsFiltered, 'recordsTotal' => $recordsTotal, 'data' => $newData ];

		return Response::json($resp);
	}

	public function getUsers()
	{
        $users = User::with(['roles' => function($q) {
            //$q->where('slug', 'corretores');
        }])->get();

        $data = [];

        foreach ( $users as $user ) {

            if ( isset($_GET['estate']) ) {
                $estate = $user
                    ->estate()
                    ->where('estate_id', $_GET['estate'])
                    ->where('user_id', $user->id)
                    ->count();

                if ( !$estate )
                    continue;
            }

            if ( isset($_GET['role']) && !$user->is($_GET['role']) )
                continue;

            $data[] = $user;
        }

        return $data;
	}

}
