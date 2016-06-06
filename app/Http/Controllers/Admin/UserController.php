<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Biz;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $data = User::get();

        // Lista apenas os items cuja imobiliária seja a mesma do usuário logado
        if ( !\Auth::user()->is('admin') ) {

            $newData = [];
            foreach ( $data as $item ) {
                if ( $item->biz_id()==\Auth::user()->biz_id() )
                    $newData[] = $item;
            }

            $data = $newData;

        }

        return view('admin.user.index')->with('data', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $bizs = Biz::whereRaw('status=1')->get();
        $user = \Auth::user();

        return view('admin.user.form')->with('bizs', $bizs)->with('user', $user);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $self = new User();

        $exists = User::whereRaw('email=?', [$request->email])->count();

        if ( $exists )
            return redirect()->back()->withInput()->with('response', 'Já existe um usuário com o email '.$request->email);

        $this->insertUpdate($self, $request);

        $msg = 'Usuário "'.$self->name.'" salvo!';

        return redirect('admin/user')->with('response', $msg);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $data = User::find($id);
        $bizs = Biz::whereRaw('status=1')->get();
        $user = \Auth::user();

        return view('admin.user.form')->with('data', $data)->with('bizs', $bizs)->with('user', $user);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $self = User::find($id);

        $exists = User::whereRaw('email=? AND id!=?', [$request->email, $id])->count();

        if ( $exists )
            return redirect()->back()->withInput()->with('response', 'Já existe um usuário com o email '.$request->email);

        $this->insertUpdate($self, $request);

        $msg = 'Usuário "'.$self->name.'" atualizado!';

        return redirect('admin/user')->with('response', $msg);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $self = User::find($id);
        $self->delete();

        $msg = 'Usuário "'.$self->name.'" removido!';

        return redirect('admin/user')->with('response', $msg);
	}

    /**
     * Realiza o insert ou update
     * */
    public function insertUpdate($self, $request)
    {
        $input = $request->all();

        $role       = $input['role'];
        $biz_id  = isset($input['biz_id']) ? $input['biz_id'] : false;

        unset($input['_wysihtml5_mode'], $input['_method'], $input['_token'], $input['role'], $input['biz_id']);

        if ( isset($input['password']) )
            $input['password'] = bcrypt($input['password']);

        // save all data
        foreach ($input as $key=>$val) {
            if ( is_array($val) )
                $val = serialize($val);

            $self->{$key} = $val;
        }

        $self->save();

        // agrega role ao usuário
        if ( $role ) {
            $self->detachAllRoles();
            $self->attachRole($role);
        }

        // agrega biz_id (imobiliaria) ao usuário
        if ( $role )
            $self->attachBizId($biz_id);

        return;
    }
}
