<?php namespace App\Http\Controllers\Admin;

use App\Category;
use App\Products;
use App\Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use \lslucas\Files;
use \lslucas\Custom;
use \lslucas\Helpers;
use Illuminate\Http\Request;

class ProductsController extends Controller {

    public $category = [], $data = [], $self;

    public function __construct()
    {
        $this->category = Category::whereRaw('status=1')->get();
        $this->data = new Custom\Data();
        $this->self = new Products();
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $productsRaw = $this->self->where('area', $request->get('area'));

        $products = $productsRaw->groupBy('titulo')->get();

        $entries = [];
        foreach ( $products as $item ) {
            $entries[$item->tipo][] = $item;
        }

        $saboresRaw = $productsRaw->groupBy('area', 'sabor', 'titulo')->get();
        $sabores = [];

        foreach ( $saboresRaw as $item ) {
            $sabores[$item->titulo][] = $item->sabor;
        }

        return view('admin.products.index')
            ->with('entries', $entries)
            ->with('sabores', $sabores)
            ->with('Data', $this->data)
            ->with('category', $this->category);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $user = \Auth::user();

        return view('admin.products.form')
            ->with('category', $this->category)
            ->with('user', $user)->with('Data', $this->data)
        ;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->insertUpdate($this->self, $request);

        if ( isset($request['origem']) && $request['origem']!='admin' )
            return $this->self->id;

        $msg = 'Produto "'.$this->self->titulo.'" salvo!';

        return redirect('admin/products?area='.$this->self->area)->with('response', $msg);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
    {
        $data = $this->self->find($id);

        $user = \Auth::user();

        $retrieve = new Files\Retrieve();
        $thumbs = $retrieve->fromStorage($data->token, 'thumbs');

        return view('admin.products.form')
            ->with('data', $data)
            ->with('Data', $this->data)
            ->with('category', $this->category)
            ->with('user', $user)
            ->with('thumbs', $thumbs)
        ;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $self = $this->self->find($id);

        $this->insertUpdate($self, $request);

        $msg = 'Produto "'.$self->titulo.'" atualizado!';

        return redirect('admin/products?area='.$this->self->area)->with('response', $msg);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $self = $this->self->find($id);
        $self->delete();

        $msg = 'Produto "'.$self->titulo.'" removido!';

        return redirect()->back()->with('response', $msg);
	}

    /**
     * Realiza o insert ou update
     * */
    public function insertUpdate($self, $request)
    {
        $Numbers = new Helpers\Numbers();

        $input = is_array($request) ? $request : $request->all();

        $input['usr_id'] = \Auth::user()->id;

        unset($input['_wysihtml5_mode'], $input['_method'], $input['_token'], $input['fotos_titulo'], $input['fotos'], $input['termos']);

        if ( isset($input['peso_caixa']) )
            $input['peso_caixa'] = $Numbers::decimalize($input['peso_caixa']);

        if ( isset($input['unidades_caixa']) )
            $input['unidades_caixa'] = $Numbers::decimalize($input['unidades_caixa']);

        if ( isset($input['peso_unitario']) )
            $input['peso_unitario'] = $Numbers::decimalize($input['peso_unitario']);

        if ( isset($input['valor']) )
            $input['valor'] = $Numbers::decimalize($input['valor']);

        // save all data
        foreach ($input as $key=>$val) {
            if ( is_array($val) )
                $val = serialize($val);

            $self->{$key} = $val;
        }

        // SE SALVAR SOBE IMAGEM
        return $self->save();
    }

}
