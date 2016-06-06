<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Cache, Carbon\Carbon;

class CartController extends Controller {

    public $expiresAt, $usr_id, $cart, $cartName;

    public function __construct()
    {
        $this->expiresAt = Carbon::now()->addHours(19);
        $this->usr_id = \Auth::user()->id;

        $this->cartName = 'cart.'.$this->usr_id;
        $this->cart = Cache::get($this->cartName, []);
    }

    public function index()
    {
        $data = Cache::get('cart');

        return view('admin.cart.show')->with('data', $this->cart);
    }

    public function show($resp=0)
    {
        if ( $resp==1 )
            return response()->json($this->cart);
        else
            return view('admin.cart.show')->with('data', $this->cart);
    }

    public function create(Request $request)
    {
        $code  = 200;
        $response = 'Produto adicionado!';

        $input = is_array($request) ? $request : $request->all();

        if ( $this->productExists($input['id']) ) {
            $code = 201;
            $response = 'Produto já existe no carrinho!';
        }

        // add novo item ao carrinho
        array_push($this->cart, $input);

        Cache::put($this->cartName, $this->cart, $this->expiresAt);

        $response = count($this->cart);

        return response($response, $code);
    }

    public function concluir(Request $request)
    {
        var_dump($request); exit;
        $cart = [];
        foreach ( $this->cart as $pos => $item ) {
            $cart[$pos]['qtd'] = $request->get('qtd.'.$pos);
        }
        var_dump($cart);
        var_dump($request->get('observacoes'));

    }

    public function destroy($id)
    {
    }

    public function unsetItem($pos)
    {
        $cart = [];

        foreach ($this->cart as $curPos => $item ) {
            if ( $curPos!=$pos )
                $cart[] = $item;
        }

        Cache::put($this->cartName, $cart, $this->expiresAt);

        return redirect()->back()->with('response', 'Item removido do carrinho.');
    }

    protected function productExists($val) {

        $key = 'id';

        foreach ($this->cart as $item)
            if (isset($item[$key]) && $item[$key] == $val)
                return true;

        return false;
    }

}
