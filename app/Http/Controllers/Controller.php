<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Aura\Payload\PayloadFactory;
use Aura\Payload_Interface\PayloadStatus;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function error(Exception $e, array $args)
    {
        $payload = $this->payloadFactory->newInstance();

        return $payload
            ->setStatus(PayloadStatus::ERROR)
            ->setInput($args)
            ->setOutput($e);
    }

    protected function response($obj, array $args=[])
    {
        list($msg, $responseType) = $obj->getMessages() ? $obj->getMessages() : $this->defaultMessage($obj->getStatus(), $obj->getInput());

        return redirect($this->redirectTo)
            ->with('response', $msg)
            ->with('response-type', $responseType);
    }

    protected function defaultMessage($status=false, array $args=[])
    {
        if ( !$status )
            return false;

        $responseType = 'alert-success';
        $msg = null;
        $id  = false;

        if ( count($args)==1 )
            $id = $args[0];

        switch ($status) {
            case 'NOT_FOUND':
                $responseType = 'alert-danger';
                if ( $id )
                    $msg = 'ID #'.$id.' não encontrado ou não existe.';
                else
                    $msg = 'Item não existe ou está desativado.';
            break;
            default:
                $msg = $status;
                $responseType = 'alert-warning';
        }

        return [$msg, $responseType];
    }

}
