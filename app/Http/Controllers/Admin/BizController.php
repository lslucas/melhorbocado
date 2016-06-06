<?php namespace App\Http\Controllers\Admin;

use App\Biz;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use \lslucas\Files\Upload;
use \lslucas\Files\Retrieve;
use Aura\Payload\PayloadFactory;
use Aura\Payload_Interface\PayloadStatus;

class BizController extends Controller {

    protected $payloadFactory;
    protected $redirectTo = 'admin/biz';

    public function __construct(PayloadFactory $payloadFactory)
    {
        $this->payloadFactory = $payloadFactory;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $data = Biz::get();

        if ( \Input::has('paying') )
            return view('admin.biz.paying')->with('data', $data);


        return view('admin.biz.index')->with('data', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('admin.biz.form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $self = new Biz();

        $this->insertUpdate($self, $request);

        $msg = 'Loja "'.$self->nome_fantasia.'" salvo!';

        return redirect('admin/biz')->with('response', $msg);
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
        $data = Biz::find($id);

        $payload = $this->payloadFactory->newInstance();

        if ( !$data ) {
            return $this->response(
                $payload
                    ->setStatus(PayloadStatus::NOT_FOUND)
                    ->setInput(func_get_args())
                );
        }

        return view('admin.biz.form')->with('data', $data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $self = Biz::find($id);

        if ( !$self ) {
            return $this->response(
                $payload
                    ->setStatus(PayloadStatus::NOT_FOUND)
                    ->setInput(func_get_args())
                );
        }

        $this->insertUpdate($self, $request);

        if ( !$self ) {
            return $this->response(
                $payload
                    ->setStatus(PayloadStatus::UPDATED)
                    ->setInput(func_get_args())
                );
        }

        $msg = 'Loja "'.$self->nome_fantasia.'" atualizado!';

        return redirect('admin/biz')->with('response', $msg);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        try {

            $self = Biz::find($id);

            if ( !$self ) {
                return $this->response(
                    $payload
                        ->setStatus(PayloadStatus::NOT_FOUND)
                        ->setInput(func_get_args())
                    );
            }

            $self->delete();

            if ( !$self ) {
                return $this->response(
                    $payload
                        ->setStatus(PayloadStatus::DELETED)
                        ->setInput(func_get_args())
                    );
            }

            $msg = 'Loja "'.$self->nome_fantasia.'" removido!';

            return redirect('admin/biz')->with('response', $msg);

        } catch (Exception $e) {
            return $this->error($e, func_get_args());
        }
	}

    /**
     * Realiza o insert ou update
     * */
    public function insertUpdate($self, $request)
    {
        $input = $request->all();

        unset($input['_wysihtml5_mode'], $input['_method'], $input['_token'], $input['logo']);

        if ( isset($input['data_publicacao']) ) {
            $date = explode('/', $input['data_publicacao']);
            $input['data_publicacao'] = null;

            if ( isset($date[0]) && isset($date[1]) && isset($date[2]) )
                $input['data_publicacao'] = $date[2].'-'.$date[1].'-'.$date[0];
        }

        // save all data
        foreach ($input as $key=>$val) {
            if ( is_array($val) )
                $val = serialize($val);

            $self->{$key} = $val;
        }

        // SE SALVAR SOBE IMAGEM
        if ( $self->save() ) {
            if ( $file = \Request::file('logo') ) {

              $Upload = new Upload();
              $Retrieve = new Retrieve();

              if ( !empty($self->logo) )
                  $Upload->remove($self->logo);

              $id = $Upload->start($file, ['token' => $self->token, 'area' => 'biz']);

              $self->logo = @$Retrieve->find($id)['file'];
              $self->save();
            }
        }

    }

}
